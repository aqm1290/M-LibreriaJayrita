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

        $stockDisponible = $producto->stock ?? 0;
        $cantidadActual  = $item?->cantidad ?? 0;

        // 3.1 Si no hay stock o ya se alcanzó el máximo, NO agregar más
        if ($stockDisponible <= 0) {
            $this->dispatch('mostrar-toast', mensaje: 'Producto agotado, no hay unidades disponibles.');
            return;
        }

        if ($cantidadActual >= $stockDisponible) {
            $this->dispatch(
                'mostrar-toast',
                mensaje: "Solo hay {$stockDisponible} unidades disponibles de este producto."
            );
            return;
        }

        // 4. Agregar una unidad más sin pasar el stock
        if ($item) {
            $item->increment('cantidad');
            $item->subtotal = $item->cantidad * $item->precio_unitario;
            $item->save();
        } else {
            $this->pedido->items()->create([
                'producto_id'     => $producto->id,
                'nombre_producto' => $producto->nombre,
                'cantidad'        => 1,
                'precio_unitario' => $producto->precio,
                'subtotal'        => $producto->precio,
            ]);
        }

        // 5. Recalcular total del pedido
        $this->pedido->total = $this->pedido->items()->sum('subtotal');
        $this->pedido->save();

        // 6. Actualizar mini‑carrito
        $this->dispatch('carrito-actualizado');

        // 7. Toast OK
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