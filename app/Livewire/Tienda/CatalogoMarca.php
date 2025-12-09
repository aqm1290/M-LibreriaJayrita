<?php

namespace App\Livewire\Tienda;

use App\Models\Marca;
use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;

class CatalogoMarca extends Component
{
    use WithPagination;

    public Marca $marca;
    public ?int $marcaId = null;

    public $productoSeleccionado = null; // para el modal

    public function mount(Marca $marca)
    {
        $this->marca   = $marca;
        $this->marcaId = $marca->id;
    }

    public function setMarca(int $id): void
    {
        $this->marca   = Marca::findOrFail($id);
        $this->marcaId = $id;
        $this->resetPage();
    }

    public function agregarAlPedido(int $productoId): void
    {
        $this->dispatch('agregar-producto-al-pedido', $productoId);
        $this->dispatch('mostrar-toast', mensaje: 'Producto agregado al carrito');
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

    public function render()
    {
        $marcas = Marca::orderBy('nombre')->get();

        $productos = Producto::with(['categoria', 'modelo', 'promociones', 'marca'])
            ->where('activo', 1)
            ->when($this->marcaId, fn ($q) => $q->where('marca_id', $this->marcaId))
            ->orderBy('nombre')
            ->paginate(12);

        return view('livewire.tienda.catalogo-marca', [
            'marcas'    => $marcas,
            'productos' => $productos,
        ])->layout('layouts.shop');
    }
}
