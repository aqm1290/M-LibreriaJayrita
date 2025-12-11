<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\CierreCaja;
use App\Models\Producto;
use Carbon\Carbon;
use App\Models\TurnoCaja;


class Dashboard extends Component
{
    
    public $ventasHoy = 0;
    public $cantidadVentasHoy = 0;
    public $efectivoHoy = 0;
    public $qrHoy = 0;
    public $cajaAbierta = false;
    public $montoApertura = 0;
    public $ventasTurno = 0;
    public $cantidadVentasTurno = 0;

    public $fechas = [];
    public $ventasSemanales = [];

    public $stockBajo = [];
    public $sinStock = [];
    public $productosMuertos = [];
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
        $this->efectivoHoy = Venta::whereDate('created_at', $hoy)
            ->where('metodo_pago', 'efectivo')
            ->sum('total');
        $this->qrHoy = Venta::whereDate('created_at', $hoy)
            ->whereIn('metodo_pago', ['qr', 'transferencia'])
            ->sum('total');

        // === VENTAS DEL TURNO ACTUAL (USUARIO LOGUEADO) ===
        $turnoActual = TurnoCaja::activoActual(); // usa usuario_id y activo = true

        if ($turnoActual) {
            $this->ventasTurno = Venta::where('turno_id', $turnoActual->id)->sum('total');
            $this->cantidadVentasTurno = Venta::where('turno_id', $turnoActual->id)->count();
        } else {
            $this->ventasTurno = 0;
            $this->cantidadVentasTurno = 0;
        }

        // === ESTADO DE CAJA (DIARIO) ===
        $cajaHoy = CierreCaja::where('fecha', $hoy)->first();
        $this->cajaAbierta = $cajaHoy?->caja_abierta ?? false;
        $this->montoApertura = $cajaHoy?->monto_apertura ?? 0;

        // === VENTAS ÚLTIMOS 7 DÍAS ===
        $this->fechas = [];
        $this->ventasSemanales = [];

        for ($i = 6; $i >= 0; $i--) {
            $fecha = $hoy->copy()->subDays($i);
            $this->fechas[] = $fecha->format('d/m');
            $this->ventasSemanales[] = Venta::whereDate('created_at', $fecha)->sum('total') ?: 0;
        }

        // === TOP 10 PRODUCTOS DEL DÍA ===
        $this->topProductos = DetalleVenta::with(['producto.marca', 'producto.modelo'])
            ->select('producto_id')
            ->selectRaw('SUM(cantidad) as total_vendido')
            ->selectRaw('SUM(cantidad * precio) as total_ingresos')
            ->whereHas('venta', fn($q) => $q->whereDate('created_at', $hoy))
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->limit(10)
            ->get();

        // === STOCK BAJO ===
        $this->stockBajo = Producto::whereBetween('stock', [1, 10])
            ->with('marca', 'modelo')
            ->orderBy('stock')
            ->limit(20)
            ->get();

        // === SIN STOCK ===
        $this->sinStock = Producto::where('stock', 0)
            ->with('marca', 'modelo')
            ->limit(20)
            ->get();

        // === PRODUCTOS SIN MOVIMIENTO 30 DÍAS ===
        $this->productosMuertos = Producto::where('stock', '>', 0)
            ->whereDoesntHave('detalleVentas', function ($q) {
                $q->where('created_at', '>=', now()->subDays(30));
            })
            ->with('marca', 'modelo')
            ->limit(15)
            ->get();
    }


    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}