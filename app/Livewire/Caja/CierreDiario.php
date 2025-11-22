<?php

namespace App\Livewire\Caja;

use Livewire\Component;
use App\Models\Venta;
use App\Models\CierreCaja as CierreCajaModel;
use App\Models\DetalleVenta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CierreDiario extends Component
{
    public $hoy;
    public $totalDia = 0;
    public $efectivo = 0;
    public $qr = 0;
    public $totalVentas = 0;
    public $ventasEfectivo = 0;
    public $ventasQr = 0;
    public $productosTop10 = [];
    public $yaCerrado = false;
    public $cierre;
    public $apertura = 0;
    public $cajaAbierta = false;

    public function mount()
    {
        $this->hoy = today()->toDateString();
        $this->calcularVentasDelDia();
    }

    public function calcularVentasDelDia()
    {
        $ventas = Venta::whereDate('created_at', $this->hoy)->get();

        $this->totalDia = $ventas->sum('total');
        $this->efectivo = $ventas->where('metodo_pago', 'efectivo')->sum('total');
        $this->qr = $ventas->where('metodo_pago', 'qr')->sum('total');
        $this->totalVentas = $ventas->count();
        $this->ventasEfectivo = $ventas->where('metodo_pago', 'efectivo')->count();
        $this->ventasQr = $ventas->where('metodo_pago', 'qr')->count();

        // TOP 10 PRODUCTOS
        $this->productosTop10 = DetalleVenta::selectRaw('
            productos.nombre,
            SUM(detalle_ventas.cantidad) as cantidad_vendida,
            SUM(detalle_ventas.subtotal) as monto_vendido
        ')
        ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
        ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
        ->whereDate('ventas.created_at', $this->hoy)
        ->groupBy('productos.id', 'productos.nombre')
        ->orderByDesc('cantidad_vendida')
        ->limit(10)
        ->get();

        // === APERTURA DE CAJA (ESTO ES LO QUE FALTABA) ===
        $cierreHoy = CierreCajaModel::where('fecha', $this->hoy)->first();
        $this->apertura = $cierreHoy?->monto_apertura ?? 0;
        $this->cajaAbierta = $cierreHoy?->caja_abierta ?? false;

        // Para el cierre (si ya está cerrado)
        $this->yaCerrado = CierreCajaModel::where('fecha', $this->hoy)
            ->whereNotNull('total_ventas')
            ->exists();

        if ($this->yaCerrado) {
            $this->cierre = CierreCajaModel::where('fecha', $this->hoy)->first();
        }
    }

    public function generarCierre()
    {
        $usuarioId = auth()->check() ? auth()->id() : 1;

        // Forzamos que exista el registro con monto_apertura
        $cierreHoy = CierreCajaModel::firstOrCreate(
            ['fecha' => $this->hoy],
            ['usuario_id' => $usuarioId, 'monto_apertura' => 0, 'caja_abierta' => true]
        );

        $pdf = Pdf::loadView('pdf.cierre-diario', [
            'fecha' => $this->hoy,
            'totalDia' => $this->totalDia,
            'efectivo' => $this->efectivo,
            'qr' => $this->qr,
            'totalVentas' => $this->totalVentas,
            'totalVentasEfectivo' => $this->ventasEfectivo,
            'totalVentasQr' => $this->ventasQr,
            'productosTop10' => $this->productosTop10,
            'apertura' => $cierreHoy->monto_apertura ?? 0,
            'totalEsperado' => $this->efectivo + ($cierreHoy->monto_apertura ?? 0),
            'cajero' => auth()->user() ?? \App\Models\User::find(1)
        ]);

        // ← ESTO ES CLAVE: forzar que use fuentes que SÍ existen
        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true
        ]);

        $pdf->setPaper('a4');

        $fileName = 'cierre_caja_' . now()->format('Y_m_d_H_i_s') . '.pdf';
        $ruta = 'cierres/' . $fileName;
        Storage::disk('public')->put($ruta, $pdf->output());

        // Actualizamos el registro con los totales del cierre
        $cierreHoy->update([
            'total_efectivo' => $this->efectivo,
            'total_qr' => $this->qr,
            'total_ventas' => $this->totalDia,
            'cantidad_ventas' => $this->totalVentas,
            'reporte_pdf' => $ruta,
        ]);

        $this->dispatch('toast', '¡Cierre de caja generado con éxito!');
        $this->calcularVentasDelDia();
        $this->dispatch('open-pdf', url: asset('storage/' . $ruta));
    }

    public function render()
    {
        return view('livewire.caja.cierre-diario')
            ->layout('layouts.pos'); // ← CREA ESTE LAYOUT
    }
}