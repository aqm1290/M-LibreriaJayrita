<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\CierreCaja;
use App\Models\Producto;
use Carbon\Carbon;


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

        // === STOCK BAJO (1 a 10 unidades) → COLUMNA "stock" !!!
        $this->stockBajo = \App\Models\Producto::whereBetween('stock', [1, 10])
            ->with('marca', 'modelo')
            ->orderBy('stock')
            ->limit(20)
            ->get();

        // === SIN STOCK (0 unidades) → COLUMNA "stock" !!!
        $this->sinStock = \App\Models\Producto::where('stock', 0)
            ->with('marca', 'modelo')
            ->limit(20)
            ->get();

        // === PRODUCTOS QUE NO SE MUEVEN (30 días sin ventas y con stock > 0)
        $this->productosMuertos = \App\Models\Producto::where('stock', '>', 0)
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