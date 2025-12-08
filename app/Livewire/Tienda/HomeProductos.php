<?php

namespace App\Livewire\Tienda;

use App\Models\Categoria;
use App\Models\Producto;
use Livewire\Component;

class HomeProductos extends Component
{
    public $productoSeleccionado = null;

    public function seleccionarProducto(int $productoId): void
    {
        $this->productoSeleccionado = Producto::with(['marca', 'modelo', 'categoria'])
            ->findOrFail($productoId);
    }

    public function cerrarModal(): void
    {
        $this->productoSeleccionado = null;
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

        $productos = Producto::with('categoria')
            ->where('activo', true)
            ->latest('id')
            ->take(20)
            ->get();

        return view('livewire.tienda.home-productos', [
            'categorias' => $categorias,
            'productos'  => $productos,
        ])->layout('layouts.shop');
    }
}