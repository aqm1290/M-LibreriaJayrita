<?php

namespace App\Livewire\Tienda;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Promocion;

class HomeProductos extends Component
{
    use WithPagination;

    public string|int $categoriaActiva = 'todas';

    public ?Producto $productoSeleccionado = null;
    public ?Marca $marcaSeleccionada = null;
    public int $marcaProductosCount = 0;

    protected $queryString = [
        'categoriaActiva' => ['except' => 'todas'],
    ];

    public function updatingCategoriaActiva()
    {
        $this->resetPage();
    }

    public function setCategoria($categoriaId): void
    {
        $this->categoriaActiva = $categoriaId === 'todas' ? 'todas' : (int) $categoriaId;
        $this->resetPage();
    }

    public function seleccionarProducto(int $id): void
    {
        $this->productoSeleccionado = Producto::with(['categoria', 'marca'])->find($id);
    }

    public function seleccionarMarca(int $id): void
    {
        $this->marcaSeleccionada = Marca::find($id);
        $this->marcaProductosCount = Producto::where('marca_id', $id)->count();
    }

    public function cerrarModal(): void
    {
        $this->productoSeleccionado = null;
        $this->marcaSeleccionada = null;
    }

    public function irACatalogoMarca()
    {
        if (! $this->marcaSeleccionada) {
            return;
        }

        return redirect()->route('tienda.marca', ['marca' => $this->marcaSeleccionada->id]);
    }

    public function agregarAlPedido(int $productoId): void
    {
        $this->dispatch('agregar-producto-al-pedido', $productoId);
        $this->dispatch('mostrar-toast', mensaje: '¡Agregado al carrito!');
        $this->productoSeleccionado = null;

    }

    public function render()
    {
        $categorias = Categoria::orderBy('nombre')->get();

        // productos por categoría (aleatorios)
        $productosFiltrados = Producto::with('categoria')
            ->when($this->categoriaActiva !== 'todas', function ($q) {
                $q->where('categoria_id', $this->categoriaActiva);
            })
            ->where('activo', true)
            ->inRandomOrder()
            ->Paginate(8, ['*'], pageName: 'cat_page');

        // productos nuevos (últimos agregados)
        $productosNuevos = Producto::with('categoria')
            ->where('activo', true)
            ->latest('creado_en')
            ->paginate(8, ['*'], pageName: 'new_page');

        $marcas = Marca::orderBy('nombre')->get();

        $promociones = Promocion::orderByDesc('id')
            ->take(8)
            ->get();

        return view('livewire.tienda.home-productos', [
            'categorias'         => $categorias,
            'productosFiltrados' => $productosFiltrados,
            'productosNuevos'    => $productosNuevos,
            'marcas'             => $marcas,
            'promociones'        => $promociones,
        ])->layout('layouts.shop');
    }

}