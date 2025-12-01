<?php

namespace App\Livewire\Caja;

use Livewire\Component;
use App\Models\Producto;
use App\Models\Marca;
use App\Models\Categoria;

class BuscadorProductos extends Component
{
    public $nombre = '';
    public $codigo = '';
    public $marca_id = '';
    public $categoria_id = '';
    public $stock_min = '';
    public $stock_max = '';
    public $precio_min = '';
    public $precio_max = '';
    public $orden = 'nombre_asc';

    public $productoDetalle = null;
    public $productoSeleccionado = null;

    public function mount()
    {
        $this->marca_id = '';
        $this->categoria_id = '';
    }

    public function abrirProducto($id)
    {
        $this->productoSeleccionado = $id;
        $this->productoDetalle = Producto::with(['marca', 'categoria'])->find($id);
    }

    public function cerrarModal()
    {
        $this->productoSeleccionado = null;
        $this->productoDetalle = null;
    }

    public function agregarAlCarrito($productoId)
    {
        $this->dispatch('add-to-pos-cart', $productoId);
        $this->dispatch('toast', 'Producto agregado al carrito');
        $this->cerrarModal();
    }

    public function limpiarFiltros()
    {
        $this->reset([
            'nombre',
            'codigo',
            'marca_id',
            'categoria_id',
            'stock_min',
            'stock_max',
            'precio_min',
            'precio_max',
        ]);

        $this->orden = 'nombre_asc';
    }

    public function render()
    {
        $query = Producto::query()->with(['marca', 'categoria']);

        if ($this->nombre) {
            $query->where('nombre', 'like', '%' . $this->nombre . '%');
        }

        if ($this->codigo) {
            $query->where('codigo', 'like', '%' . $this->codigo . '%');
        }

        if ($this->marca_id) {
            $query->where('marca_id', $this->marca_id);
        }

        if ($this->categoria_id) {
            $query->where('categoria_id', $this->categoria_id);
        }

        if ($this->stock_min !== '' && $this->stock_min !== null) {
            $query->where('stock', '>=', (int) $this->stock_min);
        }

        if ($this->stock_max !== '' && $this->stock_max !== null) {
            $query->where('stock', '<=', (int) $this->stock_max);
        }

        if ($this->precio_min !== '' && $this->precio_min !== null) {
            $query->where('precio', '>=', $this->precio_min);
        }

        if ($this->precio_max !== '' && $this->precio_max !== null) {
            $query->where('precio', '<=', $this->precio_max);
        }

        $orden = match ($this->orden) {
            'nombre_asc'  => ['nombre', 'asc'],
            'nombre_desc' => ['nombre', 'desc'],
            'precio_asc'  => ['precio', 'asc'],
            'precio_desc' => ['precio', 'desc'],
            'stock_asc'   => ['stock', 'asc'],
            'stock_desc'  => ['stock', 'desc'],
            default       => ['nombre', 'asc'],
        };

        $query->orderBy($orden[0], $orden[1]);

        $productos  = $query->paginate(20);
        $marcas     = Marca::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre')->get();

        return view('livewire.caja.buscador-productos', compact('productos', 'marcas', 'categorias'));
    }
}
