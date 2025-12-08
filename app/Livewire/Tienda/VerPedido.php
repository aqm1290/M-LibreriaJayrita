<?php

namespace App\Livewire\Tienda;

use App\Models\Pedido;
use Livewire\Component;

class VerPedido extends Component
{
    public ?Pedido $pedido = null;

    // Campos del formulario (solo si no está logueado)
    public $nombre = '';
    public $telefono = '';
    public $direccion = '';
    public $nota = '';

    protected $listeners = ['carrito-actualizado' => '$refresh'];

    public function mount()
    {
        $this->cargarPedido();
    }

    public function cargarPedido()
    {
        $pedidoId = session('pedido_borrador_id');
        if ($pedidoId) {
            $this->pedido = Pedido::with('items.producto')
                ->where('id', $pedidoId)
                ->where('estado', 'borrador')
                ->first();

            // Precargar datos si ya están
            if ($this->pedido) {
                $this->nombre = $this->pedido->cliente_nombre ?? '';
                $this->telefono = $this->pedido->cliente_telefono ?? '';
                $this->direccion = $this->pedido->cliente_direccion ?? '';
                $this->nota = $this->pedido->notas ?? '';
            }
        }
    }

    public function actualizarCantidad($itemId, $cantidad)
    {
        if ($cantidad < 1) $cantidad = 1;

        $item = $this->pedido->items()->find($itemId);
        if ($item) {
            $item->cantidad = $cantidad;
            $item->subtotal = $cantidad * $item->precio_unitario;
            $item->save();

            $this->pedido->total = $this->pedido->items()->sum('subtotal');
            $this->pedido->save();

            $this->dispatch('carrito-actualizado');
        }
    }

    public function eliminarItem($itemId)
    {
        $this->pedido->items()->where('id', $itemId)->delete();
        $this->pedido->total = $this->pedido->items()->sum('subtotal');
        $this->pedido->save();
        $this->dispatch('carrito-actualizado');
    }

    // AQUÍ ESTÁ EL ARREGLO: FUNCIÓN PARA RESERVAR
    public function confirmarPedido()
    {
        if (!auth('cliente')->check()) {
            return redirect()->route('cliente.login');
        }

        $cliente = auth('cliente')->user();

        $this->pedido->update([
            'cliente_id'        => $cliente->id,
            'cliente_nombre'    => $cliente->nombre,
            'cliente_telefono'  => $cliente->telefono,
            'cliente_email'     => $cliente->email,
            'notas'             => $this->nota,
            'estado'            => 'reservado',
            'expira_en'         => now()->addHours(48), // 48 horas desde ahora
        ]);

        session()->forget('pedido_borrador_id');
        session()->flash('mensaje', '¡Tu pedido ha sido reservado! Tienes 48 horas para confirmar el pago.');

        return redirect()->to('/tienda');
    }
    public function render()
    {
        return view('livewire.tienda.ver-pedido')->layout('layouts.shop');
    }
}