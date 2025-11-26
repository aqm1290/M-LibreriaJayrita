<?php

namespace App\Livewire\Caja;

use Livewire\Component;
use App\Models\{Venta, DetalleVenta, CierreCaja};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class CierreDiario extends Component
{
    public $montoFisico = '';
    public $observaciones = '';

    public $efectivo = 0;
    public $qr = 0;
    public $totalDia = 0;
    public $apertura = 0;
    public $productosTop10 = [];
    public $yaCerrado = false;
    public $reportePdf = null;

    public function mount()
    {
        $this->calcularTodo();
    }

    public function calcularTodo()
    {
        $hoy = today()->toDateString();

        $ventas = Venta::whereDate('created_at', $hoy)->get();
        $this->totalDia = $ventas->sum('total');
        $this->efectivo = $ventas->where('metodo_pago', 'efectivo')->sum('total');
        $this->qr = $ventas->whereIn('metodo_pago', ['qr', 'transferencia'])->sum('total');

        $cierre = CierreCaja::where('fecha', $hoy)->first();
        $this->apertura = $cierre?->monto_apertura ?? 0;
        $this->yaCerrado = $cierre?->caja_abierta == false;
        $this->reportePdf = $cierre?->reporte_pdf;

        $this->productosTop10 = DetalleVenta::selectRaw('
                productos.nombre,
                SUM(detalle_ventas.cantidad) as cantidad,
                SUM(detalle_ventas.subtotal) as monto
            ')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->whereDate('ventas.created_at', $hoy)
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('cantidad')
            ->limit(10)
            ->get();
    }

    public function generarCierre()
    {
        $this->validate(['montoFisico' => 'required|numeric|min:0']);

        $hoy = today()->toDateString();
        $esperado = $this->efectivo + $this->apertura;
        $diferencia = $this->montoFisico - $esperado;

        $pdf = Pdf::loadView('pdf.cierre-diario', [
            'fecha' => $hoy,
            'cajero' => auth()->user(),
            'apertura' => $this->apertura,
            'efectivo' => $this->efectivo,
            'qr' => $this->qr,
            'totalDia' => $this->totalDia,
            'montoFisico' => $this->montoFisico,
            'totalEsperado' => $esperado,
            'diferencia' => $diferencia,
            'observaciones' => $this->observaciones,
            'productosTop10' => $this->productosTop10,
        ])->setPaper('a4');

        $fileName = 'cierre_' . now()->format('Y_m_d_His') . '.pdf';
        $ruta = 'cierres/' . $fileName;
        Storage::disk('public')->put($ruta, $pdf->output());

        CierreCaja::updateOrCreate(['fecha' => $hoy], [
            'usuario_id' => auth()->id(),
            'monto_apertura' => $this->apertura,
            'total_efectivo' => $this->efectivo,
            'total_qr' => $this->qr,
            'total_ventas' => $this->totalDia,
            'cantidad_ventas' => Venta::whereDate('created_at', $hoy)->count(),
            'monto_cierre_fisico' => $this->montoFisico,
            'diferencia' => $diferencia,
            'observaciones' => $this->observaciones,
            'reporte_pdf' => $ruta,
            'caja_abierta' => false,
        ]);

        $this->dispatch('toast', '¡Cierre realizado con éxito! Redirigiendo...');
        $this->dispatch('open-pdf', url: asset('storage/' . $ruta));

        $this->calcularTodo();
    }

    public function render()
    {
        return view('livewire.caja.cierre-diario')->layout('layouts.pos');
    }
}