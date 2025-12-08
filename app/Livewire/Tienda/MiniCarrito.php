<?php

namespace App\Livewire\Tienda;

use App\Models\Pedido;
use Livewire\Component;

class MiniCarrito extends Component
{
    public $pedido = null;
    public $cantidadItems = 0;
    public $total = 0;                 // ← ESTO ES LO QUE TE FALTABA DECLARAR

    protected $listeners = [
        'carrito-actualizado' => 'cargarPedido',
    ];

    public function mount(): void
    {
        $this->cargarPedido();
    }

    public function cargarPedido(): void
    {
        $this->cantidadItems = 0;
        $this->total = 0;              // ← Inicializamos en 0 siempre
        $this->pedido = null;

        $pedidoId = session('pedido_borrador_id');

        if (!$pedidoId) {
            return;
        }

        $this->pedido = Pedido::with('items.producto')
            ->where('id', $pedidoId)
            ->where('estado', 'borrador')
            ->first();

        if ($this->pedido) {
            $this->cantidadItems = $this->pedido->items->sum('cantidad');
            $this->total = $this->pedido->total ?? 0;  // ← Aquí cargamos el total
        }
    }

    public function render()
    {
        return view('livewire.tienda.mini-carrito');
    }
}