<?php

namespace App\Livewire\Tienda;

use App\Models\Pedido;
use App\Models\PedidoItem; // ajusta el nombre si tu modelo se llama distinto
use Livewire\Component;

class MiniCarrito extends Component
{
    public ?Pedido $pedido = null;
    public int $cantidadItems = 0;
    public float $total = 0.0;

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
        $this->total = 0;
        $this->pedido = null;

        $pedidoId = session('pedido_borrador_id');

        if (! $pedidoId) {
            return;
        }

        $this->pedido = Pedido::with('items.producto')
            ->where('id', $pedidoId)
            ->where('estado', 'borrador')
            ->first();

        if ($this->pedido) {
            $this->cantidadItems = $this->pedido->items->sum('cantidad');
            // si tu tabla tiene columna total, úsala; si no, suma subtotales
            $this->total = $this->pedido->total
                ?? $this->pedido->items->sum('subtotal');
        }
    }

    public function eliminarItem(int $itemId): void
    {
        if (! $this->pedido) {
            return;
        }

        // Ajusta PedidoItem al nombre real de tu modelo detalle (por ej. PedidoDetalle)
        $item = PedidoItem::where('id', $itemId)
            ->where('pedido_id', $this->pedido->id)
            ->first();

        if (! $item) {
            return;
        }

        $item->delete();

        // Recalcular totales del pedido (si tu modelo Pedido tiene lógica propia, llámala aquí)
        $this->pedido->refresh();
        $this->pedido->load('items');

        // Si manejas total en Pedido, actualízalo:
        $this->pedido->total = $this->pedido->items->sum('subtotal');
        $this->pedido->save();

        // Recargar datos en el mini-carrito
        $this->cargarPedido();

        // Notificar a otros componentes opcionalmente
        $this->dispatch('carrito-actualizado');
    }

    public function render()
    {
        return view('livewire.tienda.mini-carrito');
    }
}
