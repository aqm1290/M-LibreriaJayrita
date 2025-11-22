<?php

namespace App\Livewire\Caja;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;

class VentaPos extends Component
{
    public $search = '';
    public $cart = [];
    public $impuesto = 0;
    public $descuento = 0;
    public $metodo_pago = 'efectivo';
    public $usuario_id;
    public $efectivo_recibido = 0;
    public $subtotal = 0;
    public $total_impuesto = 0;
    public $total = 0;
    public $cambio = 0;
    public $cliente_nombre = 'Cliente XXXXXX'; // valor por defecto
    public $cliente_documento = ''; // opcional

    protected $listeners = [
        'confirmar-venta' => 'finalizarVenta',
        'venta-creada' => 'ventaCreada',
    ];

    public function mount()
    {
        $this->usuario_id = auth()->id() ?? 1;
    }

    public function render()
    {
        $productos = collect();

        if (strlen($this->search) >= 1) {
            $productos = Producto::query()
                ->where(function ($q) {
                    $q->where('nombre', 'like', "%{$this->search}%")
                      ->orWhere('codigo', 'like', "%{$this->search}%");
                })
                ->orderBy('nombre')
                ->limit(30)
                ->get();
        }

        return view('livewire.caja.venta-pos', compact('productos'));
    }

    // ============= CARRITO =============
    public function agregarProducto($productoId)
    {
        $p = Producto::find($productoId);
        if (!$p || $p->stock <= 0) {
            $this->dispatch('toast', 'Producto no disponible');
            return;
        }

        foreach ($this->cart as &$item) {
            if ($item['producto_id'] == $p->id) {
                if ($item['cantidad'] + 1 > $p->stock) {
                    $this->dispatch('toast', 'Stock insuficiente');
                    return;
                }
                $item['cantidad']++;
                $item['subtotal'] = round($item['cantidad'] * $item['precio_unitario'], 2);
                $this->calcularTotales();
                return;
            }
        }

        $this->cart[] = [
            'producto_id' => $p->id,
            'nombre' => $p->nombre,
            'precio_unitario' => (float) $p->precio,
            'cantidad' => 1,
            'subtotal' => (float) $p->precio,
            'stock' => $p->stock
        ];

        $this->search = '';
        $this->calcularTotales();
    }

    public function updateCantidad($index, $cantidad)
    {
        $cantidad = max(1, (int)$cantidad);
        if (!isset($this->cart[$index])) return;

        $prod = Producto::find($this->cart[$index]['producto_id']);
        if ($cantidad > $prod->stock) {
            $this->dispatch('toast', 'Stock insuficiente');
            return;
        }

        $this->cart[$index]['cantidad'] = $cantidad;
        $this->cart[$index]['subtotal'] = round($cantidad * $this->cart[$index]['precio_unitario'], 2);
        $this->calcularTotales();
    }

    public function removeItem($index)
    {
        if (isset($this->cart[$index])) {
            array_splice($this->cart, $index, 1);
            $this->calcularTotales();
        }
    }

    public function limpiarCarrito()
    {
        $this->cart = [];
        $this->calcularTotales();
    }

    public function calcularTotales()
    {
        $this->subtotal = round(collect($this->cart)->sum('subtotal'), 2);
        $this->total_impuesto = round(($this->impuesto / 100) * $this->subtotal, 2);
        $this->total = round($this->subtotal + $this->total_impuesto - $this->descuento, 2);
        $this->total = max(0, $this->total);
        $recibido = (float) ($this->efectivo_recibido ?? 0);

        if ($this->metodo_pago === 'efectivo') {
            $this->cambio = round($recibido - $this->total, 2);
        } else {
            $this->cambio = 0;
        }
    }
    public function updatedEfectivoRecibido($value)
    {
        $this->calcularTotales();
    }

    public function updatedDescuento($value)
    {
        $this->calcularTotales();
    }

    public function updatedMetodoPago($value)
    {
        $this->calcularTotales();
    }

    public function confirmarVenta()
    {
        if (empty($this->cart)) {
            $this->dispatch('toast', 'No Hay ningun Producto');
            return;
        }

        $this->calcularTotales();

        $this->dispatch('confirmar-venta', [
            'total' => $this->total,
            'items' => count($this->cart),
            'productos' => $this->cart
        ]);
    }

    public function finalizarVenta()
    {
        if (empty($this->cart)) {
            $this->dispatch('toast', 'Carrito vacío');
            return;
        }

        DB::beginTransaction();
        try {
            $venta = Venta::create([
                'usuario_id' => $this->usuario_id,
                'total' => $this->total,
                'estado_pago' => $this->metodo_pago === 'efectivo' ? 'completada' : 'pendiente',
                'metodo_pago' => $this->metodo_pago,
                'impuesto' => $this->total_impuesto,
                'descuento_total' => $this->descuento,
                'cliente_nombre' => $this->cliente_nombre ?: 'Cliente XXXXXXXX',
                'cliente_documento' => $this->cliente_documento,
            ]);

            foreach ($this->cart as $item) {
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio_unitario'],
                    'subtotal' => $item['subtotal'], 
                ]);

                $producto = Producto::find($item['producto_id']);
                if ($producto) {
                    $producto->decrement('stock', $item['cantidad']);
                }
            }

            DB::commit();

            $this->dispatch('toast', '¡Venta registrada con éxito!');
            $this->reset(['cart',
            'subtotal',
            'descuento',
            'total',
            'efectivo_recibido',
            'cambio',
            'cliente_nombre',
            'cliente_documento',
            'metodo_pago',
            'search']);
            $this->calcularTotales();
            $this->dispatch('venta-creada', $venta->id);

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('toast', 'Error: ' . $e->getMessage());
        }

            $this->reset([
            'cart',
            'subtotal',
            'descuento',
            'total',
            'efectivo_recibido',
            'cambio',
            'cliente_nombre',
            'cliente_documento',
            'metodo_pago',
            'search'
        ]);

    }

    public function incrementarCantidad($index)
    {
        $stockDisponible = $this->cart[$index]['stock'];
        if ($this->cart[$index]['cantidad'] < $stockDisponible) {
            $this->cart[$index]['cantidad']++;
            $this->actualizarSubtotal($index);
        } else {
            $this->dispatch('toast', "Stock limitado: solo hay {$stockDisponible} unidades");
        }
    }
    public function actualizarSubtotal($index)
    {
        $stockDisponible = $this->cart[$index]['stock'];
        
        // Si pone más del stock, lo corrige automáticamente
        if ($this->cart[$index]['cantidad'] > $stockDisponible) {
            $this->cart[$index]['cantidad'] = $stockDisponible;
            $this->dispatch('toast', "Stock limitado: solo hay {$stockDisponible} unidades disponibles");
        }

        // Si pone menos de 1, pone 1
        if ($this->cart[$index]['cantidad'] < 1) {
            $this->cart[$index]['cantidad'] = 1;
        }

        $this->cart[$index]['subtotal'] = $this->cart[$index]['precio_unitario'] * $this->cart[$index]['cantidad'];
        $this->calcularTotales();
    }

    public function decrementarCantidad($index)
    {
        if (!isset($this->cart[$index])) return;

        if ($this->cart[$index]['cantidad'] > 1) {
            $this->cart[$index]['cantidad']--;
            $this->cart[$index]['subtotal'] = round($this->cart[$index]['cantidad'] * $this->cart[$index]['precio_unitario'], 2);
            $this->calcularTotales();
        } else {
            $this->dispatch('toast', 'La cantidad mínima es 1');
        }
    }
}