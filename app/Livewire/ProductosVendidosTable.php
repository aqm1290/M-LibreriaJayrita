<?php

namespace App\Livewire;

use App\Models\DetalleVenta;
use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

class ProductosVendidosTable extends Component
{
    use WithPagination;

    public $search = '';
    public $fechaInicio;
    public $fechaFin;
    public $rango = 'mes'; // hoy, semana, mes, personalizado

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $this->fechaFin = today()->format('Y-m-d');
        $this->fechaInicio = today()->subMonth()->format('Y-m-d');
    }

    public function updatedRango()
    {
        switch ($this->rango) {
            case 'hoy':
                $this->fechaInicio = today()->format('Y-m-d');
                $this->fechaFin = today()->format('Y-m-d');
                break;
            case 'ayer':
                $this->fechaInicio = today()->subDay()->format('Y-m-d');
                $this->fechaFin = today()->subDay()->format('Y-m-d');
                break;
            case 'semana':
                $this->fechaInicio = today()->startOfWeek()->format('Y-m-d');
                $this->fechaFin = today()->endOfWeek()->format('Y-m-d');
                break;
            case 'mes':
                $this->fechaInicio = today()->startOfMonth()->format('Y-m-d');
                $this->fechaFin = today()->format('Y-m-d');
                break;
        }
        $this->resetPage();
    }

    public function render()
    {
        $query = DetalleVenta::query()
            ->with(['producto.marca', 'producto.modelo'])
            ->join('ventas', 'detalle_ventas.venta_id', '=', 'ventas.id')
            ->whereBetween('ventas.created_at', [$this->fechaInicio . ' 00:00:00', $this->fechaFin . ' 23:59:59'])
            ->when($this->search, function ($q) {
                $q->whereHas('producto', function ($p) {
                    $p->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('codigo', 'like', '%' . $this->search . '%');
                });
            })
            ->selectRaw('
                producto_id,
                SUM(cantidad) as total_vendido,
                SUM(subtotal) as total_ingresado,
                AVG(precio) as precio_promedio
            ')
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido');

        $productos = $query->paginate(20);

        // Total general para porcentaje
        $totalGeneral = $productos->sum('total_ingresado');

        return view('livewire.productos-vendidos-table', compact('productos', 'totalGeneral'));
    }
}