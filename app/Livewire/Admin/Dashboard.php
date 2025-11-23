<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\CierreCaja;

class Dashboard extends Component
{
    public $ventasHoy = 0;
    public $cantidadVentasHoy = 0;
    public $efectivoHoy = 0;
    public $qrHoy = 0;
    public $cajaAbierta = false;
    public $montoApertura = 0;

    public $fechas = [];
    public $ventasSemanales = [];

    public $topProductos = [];

    public function mount()
    {
        $this->cargarDatos();
    }

    public function cargarDatos()
    {
        $hoy = today();

        // === VENTAS DEL DÍA ===
        $this->ventasHoy = Venta::whereDate('created_at', $hoy)->sum('total');
        $this->cantidadVentasHoy = Venta::whereDate('created_at', $hoy)->count();
        $this->efectivoHoy = Venta::whereDate('created_at', $hoy)->where('metodo_pago', 'efectivo')->sum('total');
        $this->qrHoy = Venta::whereDate('created_at', $hoy)->whereIn('metodo_pago', ['qr', 'transferencia'])->sum('total');

        // === ESTADO DE CAJA ===
        $cajaHoy = CierreCaja::where('fecha', $hoy)->first();
        $this->cajaAbierta = $cajaHoy?->caja_abierta ?? false;
        $this->montoApertura = $cajaHoy?->monto_apertura ?? 0;

        // === VENTAS ÚLTIMOS 7 DÍAS ===
        for ($i = 6; $i >= 0; $i--) {
            $fecha = $hoy->copy()->subDays($i);
            $this->fechas[] = $fecha->format('d/m');
            $this->ventasSemanales[] = Venta::whereDate('created_at', $fecha)->sum('total') ?: 0;
        }

        // === TOP 10 PRODUCTOS DEL DÍA (CORREGIDO 100%) ===
        $this->topProductos = DetalleVenta::with(['producto.marca', 'producto.modelo']) // ← modelo, no modelobier
            ->select('producto_id')
            ->selectRaw('SUM(cantidad) as total_vendido')
            ->selectRaw('SUM(cantidad * precio) as total_ingresos') // ← usa el precio guardado en detalle_venta
            ->whereHas('venta', fn($q) => $q->whereDate('created_at', $hoy))
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}