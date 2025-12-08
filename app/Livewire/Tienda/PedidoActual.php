<?php

namespace App\Livewire\Tienda;

use App\Models\Pedido;
use App\Models\Producto;
use Livewire\Component;

class PedidoActual extends Component
{
    public ?Pedido $pedido = null;

    protected $listeners = [
        'agregar-producto-al-pedido' => 'agregarProducto',
    ];

    public function mount()
    {
        $this->cargarOPrepararPedido();
    }

    protected function cargarOPrepararPedido(): void
    {
        $pedidoId = session('pedido_borrador_id');

        if ($pedidoId) {
            $this->pedido = Pedido::with('items.producto')
                ->where('id', $pedidoId)
                ->where('estado', 'borrador')
                ->first();
        }

        if (!$this->pedido) {
            $this->pedido = Pedido::create([
                'cliente_id'     => null,
                'cliente_nombre' => 'Invitado',
                'estado'         => 'borrador',
                'total'          => 0,
            ]);
            session(['pedido_borrador_id' => $this->pedido->id]);
        }
    }

   public function agregarProducto($productoId)
    {
        // 1. Buscar producto
        $producto = Producto::findOrFail($productoId);

        // 2. Asegurar que exista pedido borrador en sesión
        if (! $this->pedido || $this->pedido->estado !== 'borrador') {
            $this->cargarOPrepararPedido();
        }

        // 3. Buscar si ya existe item de ese producto en el pedido
        $item = $this->pedido->items()->where('producto_id', $producto->id)->first();

        if ($item) {
            // Ya existe: incrementar cantidad y recalcular subtotal
            $item->increment('cantidad');
            $item->subtotal = $item->cantidad * $item->precio_unitario;
            $item->save();
        } else {
            // No existe: crear nuevo item
            $this->pedido->items()->create([
                'producto_id'     => $producto->id,
                'nombre_producto' => $producto->nombre,
                'cantidad'        => 1,
                'precio_unitario' => $producto->precio,
                'subtotal'        => $producto->precio,
            ]);
        }

        // 4. Recalcular total del pedido
        $this->pedido->total = $this->pedido->items()->sum('subtotal');
        $this->pedido->save();

        // 5. Actualizar mini-carrito
        $this->dispatch('carrito-actualizado');

        // 6. Mostrar toast en la vista (evento para JS/Alpine)
        $this->dispatch('mostrar-toast', mensaje: 'Producto agregado al carrito');
    }



    public function confirmarPedido(): void
{
    if (! $this->pedido || $this->pedido->items->isEmpty()) {
        return;
    }

    // Cambiar estado a pendiente
    $this->pedido->estado = 'pendiente';
    $this->pedido->save();

    // Limpiar el pedido borrador de la sesión
    session()->forget('pedido_borrador_id');

    // Avisar al mini‑carrito que debe recargarse (quedará en 0)
    $this->dispatch('carrito-actualizado');

    // Toast para el cliente (si ya tienes el listener JS/Alpine)
    $this->dispatch('mostrar-toast', mensaje: 'Pedido enviado al administrador');
}



    public function render()
    {
        return view('livewire.tienda.pedido-actual')
        ->layout('layouts.shop');
    }
}