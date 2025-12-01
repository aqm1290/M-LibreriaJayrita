<?php

namespace App\Livewire\Caja;

use Livewire\Component;
use App\Models\Venta;
use App\Models\TurnoCaja;
use Illuminate\Support\Facades\DB;

class CierreDiario extends Component
{
    public $montoFisico = '';
    public $observaciones = '';

    public $efectivo = 0;
    public $qr = 0;
    public $totalDia = 0;
    public $apertura = 0;
    public $cantidadVentas = 0;

    public $productosVendidos = [];
    public $yaCerrado = false;
    public $turno;

    public function mount()
    {
        $turnoId = session('turno_activo_id');

        if (!$turnoId) {
            return redirect()->route('caja.apertura');
        }

        $this->turno = TurnoCaja::find($turnoId);

        if (!$this->turno || !$this->turno->activo) {
            session()->forget('turno_activo_id');
            return redirect()->route('caja.apertura');
        }

        $this->calcularTodo();
    }

    public function calcularTodo()
    {
        $ventas = Venta::where('turno_id', $this->turno->id)->get();

        $this->totalDia       = $ventas->sum('total');
        $this->efectivo       = $ventas->where('metodo_pago', 'efectivo')->sum('total');
        $this->qr             = $ventas->whereIn('metodo_pago', ['qr', 'transferencia'])->sum('total');
        $this->apertura       = $this->turno->monto_apertura;
        $this->cantidadVentas = $ventas->count();

        $this->productosVendidos = DB::table('detalle_ventas')
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->join('productos', 'detalle_ventas.producto_id', '=', 'productos.id')
            ->where('ventas.turno_id', $this->turno->id)
            ->select(
                'productos.nombre',
                DB::raw('SUM(detalle_ventas.cantidad) as cantidad'),
                DB::raw('SUM(detalle_ventas.subtotal) as monto')
            )
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('cantidad')
            ->get();
    }

    public function generarCierre()
    {
        $this->validate([
            'montoFisico' => 'required|numeric|min:0',
        ]);

        $esperado   = $this->efectivo + $this->apertura;
        $diferencia = $this->montoFisico - $esperado;

        $this->turno->update([
            'monto_fisico_cierre' => $this->montoFisico,
            'total_ventas'        => $this->totalDia,
            'total_efectivo'      => $this->efectivo,
            'total_qr'            => $this->qr,
            'diferencia'          => $diferencia,
            'observaciones'       => $this->observaciones,
            'cantidad_ventas'     => $this->cantidadVentas,
            'hora_cierre'         => now(),
            'activo'              => false,
        ]);

        session()->forget('turno_activo_id');

        // SweetAlert + pantalla de éxito
        $this->yaCerrado = true;

        $icono = $diferencia == 0 ? 'success' : ($diferencia > 0 ? 'warning' : 'error');
        $color = $diferencia == 0 ? '#16a34a' : ($diferencia > 0 ? '#ca8a04' : '#dc2626');

        $this->dispatch('swal', [
            'title' => '¡Caja Cerrada con Éxito!',
            'html'  => "
                <p><strong>Total del día:</strong> Bs " . number_format($this->totalDia, 2) . "</p>
                <p><strong>Dinero esperado:</strong> Bs " . number_format($esperado, 2) . "</p>
                <p><strong>Dinero físico:</strong> Bs " . number_format($this->montoFisico, 2) . "</p>
                <p><strong>Diferencia:</strong> <span style='color:{$color}; font-weight:bold;'>
                    " . ($diferencia >= 0 ? '+' : '') . number_format($diferencia, 2) . " Bs
                </span></p>
            ",
            'icon'  => $icono,
        ]);
    }

    public function render()
    {
        return view('livewire.caja.cierre-diario')->layout('layouts.pos');
    }
}