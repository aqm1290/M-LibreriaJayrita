<?php

namespace App\Livewire\Admin;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class PedidosWeb extends Component
{
    use WithPagination;

    public $estadoFiltro = 'reservado';
    public $pedidoDetalle = null;

    public function mount()
    {
        $this->dispatch('actualizar-pedidos');
    }

    public function cambiarEstado($pedidoId, $nuevoEstado)
    {
        $pedido = Pedido::findOrFail($pedidoId);
        $pedido->estado = $nuevoEstado;
        $pedido->save();

        if ($pedido->cliente_telefono && $nuevoEstado === 'confirmado') {
            $mensaje  = "¡Hola {$pedido->cliente_nombre}! \n\n";
            $mensaje .= "Tu pedido #{$pedido->id} ha sido *CONFIRMADO*.\n\n";
            $mensaje .= "Total: Bs " . number_format($pedido->total, 2) . "\n\n";
            $mensaje .= "¡Gracias por confiar en Librería Jayrita!";

            $whatsappUrl = "https://wa.me/591{$pedido->cliente_telefono}?text=" . urlencode($mensaje);
            $this->dispatch('abrir-whatsapp', url: $whatsappUrl);
        }

        $this->dispatch('mostrar-toast', mensaje: "Pedido #{$pedidoId} actualizado");
        $this->reset('pedidoDetalle');
    }

    public function verDetalle($pedidoId)
    {
        $this->pedidoDetalle = Pedido::with('items.producto')->find($pedidoId);
    }

    public function cancelarVencidosManual()
    {
        Artisan::call('pedidos:cancelar-vencidos');
        $this->dispatch('mostrar-toast', mensaje: 'Pedidos vencidos cancelados');
    }

    /**
     * Paso 1: desde el botón ENTREGADO (estado confirmado) abrimos el modal de pago.
     */
    public function marcarEntregado($pedidoId)
    {
        $pedido = Pedido::with('items.producto')->findOrFail($pedidoId);

        if ($pedido->estado !== 'confirmado') {
            $this->dispatch('mostrar-toast', mensaje: 'El pedido debe estar Confirmado');
            return;
        }

        // Verificar stock antes de abrir modal
        foreach ($pedido->items as $item) {
            if ($item->producto && $item->producto->stock < $item->cantidad) {
                $this->dispatch('mostrar-toast', mensaje: "Sin stock suficiente para {$item->nombre_producto}");
                return;
            }
        }

        $this->dispatch('abrir-modal-pago', [
            'pedidoId' => (int) $pedido->id,
            'total'    => (float) $pedido->total,
            'cliente'  => $pedido->cliente_nombre ?: 'Cliente Web',
        ]);
    }


    
    public function procesarEntrega($pedidoId, $metodo, $recibido, $vuelto)
    {
        $pedido   = Pedido::with('items.producto')->findOrFail($pedidoId);
        $metodo   = $metodo === 'qr' ? 'qr' : 'efectivo';
        $recibido = (float) $recibido;
        $vuelto   = (float) $vuelto;

        if ($pedido->estado !== 'confirmado') {
            $this->dispatch('mostrar-toast', mensaje: 'El pedido debe estar Confirmado');
            return;
        }

        DB::beginTransaction();
        try {
            // Verificar stock nuevamente dentro de la transacción
            foreach ($pedido->items as $item) {
                if (!$item->producto || $item->producto->stock < $item->cantidad) {
                    DB::rollBack();
                    $this->dispatch('mostrar-toast', mensaje: "Sin stock suficiente para {$item->nombre_producto}");
                    return;
                }
            }

            // Crear venta
            $venta = Venta::create([
                'usuario_id'        => auth()->id(),
                'total'             => $pedido->total,
                'estado_pago'       => $metodo === 'efectivo' ? 'completada' : 'pendiente',
                'cliente_nombre'    => $pedido->cliente_nombre ?? 'Cliente Web',
                'cliente_documento' => $pedido->cliente_email ?? '',
                'metodo_pago'       => $metodo,
                'descuento_total'   => 0,
                'ticket_pdf'        => null,
                'turno_id'          => session('turno_activo_id'),
            ]);

            // Detalles y descuento de stock
            foreach ($pedido->items as $item) {
                DetalleVenta::create([
                    'venta_id'   => $venta->id,
                    'producto_id'=> $item->producto_id,
                    'cantidad'   => $item->cantidad,
                    'precio'     => $item->precio_unitario,
                    'subtotal'   => $item->subtotal,
                ]);

                if ($item->producto) {
                    $item->producto->decrement('stock', $item->cantidad);
                }
            }

            // Marcar pedido como entregado
            $pedido->update([
                'estado'       => 'entregado',
                'entregado_en' => now(),
            ]);

            DB::commit();

            // Imprimir ticket + toast
            $this->dispatch('imprimir-ticket', ventaId: $venta->id);
            $this->dispatch(
                'mostrar-toast',
                mensaje: "Entrega confirmada • Pago {$metodo} • Vuelto Bs " . number_format($vuelto, 2)
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Error al procesar entrega de pedido web: '.$e->getMessage(), ['exception' => $e]);
            $this->dispatch('mostrar-toast', mensaje: 'Error al procesar la entrega del pedido');
            $this->dispatch('imprimir-ticket', ventaId: $venta->id);

        }
    }

    public function render()
    {
        $pedidos = Pedido::with(['items.producto'])
            ->when($this->estadoFiltro !== 'todos', fn ($q) => $q->where('estado', $this->estadoFiltro))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.pedidos-web', compact('pedidos'))
            ->layout('layouts.app');
    }
}
