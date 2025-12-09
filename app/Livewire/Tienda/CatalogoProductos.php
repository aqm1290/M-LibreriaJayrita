<?php

namespace App\Livewire\Tienda;

use App\Models\Producto;
use App\Models\Marca;
use App\Models\Categoria;
use Livewire\Component;
use Livewire\WithPagination;

class CatalogoProductos extends Component
{
    use WithPagination;

    public string $busqueda = '';
    public ?int $categoriaId = null;
    public int $porPagina = 12;

    public $productoSeleccionado = null;

    protected $updatesQueryString = ['busqueda', 'categoriaId', 'page'];

    // MISMO FLUJO QUE EN HomeProductos
    public function agregarAlPedido(int $productoId): void
    {
        $this->dispatch('agregar-producto-al-pedido', $productoId);
    }

    public function abrirModal(int $id): void
    {
        $this->productoSeleccionado = Producto::with(['marca', 'modelo', 'categoria', 'promociones'])
            ->find($id);
    }

    public function cerrarModal(): void
    {
        $this->productoSeleccionado = null;
    }

    public function updatingBusqueda(): void
    {
        $this->resetPage();
    }

    public function updatingCategoriaId(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Producto::query()
            ->with(['marca', 'modelo', 'categoria', 'promociones'])
            ->where('activo', 1);

        if ($this->busqueda !== '') {
            $busqueda = $this->busqueda;
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombre', 'like', '%'.$busqueda.'%')
                  ->orWhere('codigo', 'like', '%'.$busqueda.'%');
            });
        }

        if ($this->categoriaId) {
            $query->where('categoria_id', $this->categoriaId);
        }

        $productos   = $query->orderBy('nombre')->paginate($this->porPagina);
        $marcas      = Marca::orderBy('nombre')->get();          // ← para filtros/pills
        $categorias  = Categoria::orderBy('nombre')->get();      // ← si el Blade las usa

        return view('livewire.tienda.catalogo-productos', [
            'productos'  => $productos,
            'marcas'     => $marcas,
            'categorias' => $categorias,
        ])->layout('layouts.shop');
    }
}
