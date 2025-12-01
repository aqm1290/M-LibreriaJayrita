<?php

namespace App\Livewire\Caja;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\Cliente;
use App\Models\DetalleVenta;
use App\Models\FidelidadCliente;


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
   
    //cliente
    public $cliente_id = null;
    public $cliente_nombre = 'Cliente';
    public $cliente_documento = '';

    // Para el modal
    public $mostrarModalCliente = false;
    public $nuevoCliente = [
        'nombre' => '',
        'ci' => '',
        'telefono' => '',
        'direccion' => '',
        'correo' => '',
    ];

    // Para búsqueda de clientes
    public $buscarNombre = '';
    public $buscarCi = '';
    public $clientesEncontrados = [];

    protected $listeners = [
        'confirmar-venta' => 'finalizarVenta',
        'venta-creada' => 'ventaCreada',
        'add-to-pos-cart' => 'agregarProducto',
    ];

    public function mount()
    {
        $hoy = today()->toDateString();
        $cierre = \App\Models\CierreCaja::where('fecha', $hoy)->first();
        if (!session()->has('turno_activo_id')) {
            return redirect()->route('caja.apertura');
        }
        
        $this->usuario_id = auth()->id() ?? 1;
        $this->resetearCliente();
    }
    public function resetearCliente()
    {
        $this->cliente_id = null;
        $this->cliente_nombre = 'Cliente ';
        $this->cliente_documento = '';
        $this->buscarCliente = '';
        $this->clientesEncontrados = [];
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

        $this->calcularTotales();

        DB::beginTransaction();
        try {
                $venta = Venta::create([
                'usuario_id'        => $this->usuario_id,
                'cliente_id'        => $this->cliente_id,
                'cliente_nombre'    => $this->cliente_id 
                    ? Cliente::find($this->cliente_id)?->nombre 
                    : ($this->cliente_nombre ?: 'Cliente Genérico'),
                'cliente_documento' => $this->cliente_id 
                    ? Cliente::find($this->cliente_id)?->ci 
                    : $this->cliente_documento,
                'total'             => $this->total,
                'estado_pago'       => $this->metodo_pago === 'efectivo' ? 'completada' : 'pendiente',
                'metodo_pago'       => $this->metodo_pago,
                'impuesto'          => $this->total_impuesto,
                'descuento_total'   => $this->descuento,
                'turno_id' => session('turno_activo_id'),
            ]);

            foreach ($this->cart as $item) {
                DetalleVenta::create([
                    'venta_id'    => $venta->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad'    => $item['cantidad'],
                    'precio'      => $item['precio_unitario'],
                    'subtotal'    => $item['subtotal'],
                ]);

                $producto = Producto::find($item['producto_id']);
                if ($producto) {
                    $producto->decrement('stock', $item['cantidad']);
                }
            }

           DB::commit();

            // === SISTEMA CLIENTE FIEL - AUTOMÁTICO ===
            // === CLIENTE FIEL - ALERTA ÉPICA ===
            if ($this->cliente_id) {
                $cliente = \App\Models\Cliente::find($this->cliente_id);
                if ($cliente) {
                    $fidelidad = \App\Models\FidelidadCliente::firstOrCreate(
                        ['cliente_id' => $cliente->id],
                        ['compras_realizadas' => 0]
                    );

                    $fidelidad->increment('compras_realizadas');
                    $fidelidad->ultima_compra = now();
                    $fidelidad->save();

                    // SI LLEGA A 10 Y NO HA ENTREGADO PREMIO → ALERTA ÉPICA
                    if ($fidelidad->compras_realizadas >= 10 && !$fidelidad->premio_entregado) {
                        $this->dispatch('premio-cliente-fiel', [
                            'cliente_id' => $cliente->id,
                            'nombre' => $cliente->nombre,
                            'ci' => $cliente->ci ?? 'Sin CI'
                        ]);
                    }
                }
            }

            $this->dispatch('toast', '¡Venta registrada con éxito!');
            $this->dispatch('venta-creada', $venta->id);

            // === RESETEO 
            $this->resetearTodo();

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatch('toast', 'Error al procesar la venta');
            \Log::error('Error en Venta POS: ' . $e->getMessage(), ['exception' => $e]);
        }
    }
    public function entregarPremioClienteFiel($clienteId)
    {
        $fidelidad = \App\Models\FidelidadCliente::where('cliente_id', $clienteId)->first();
        if ($fidelidad && $fidelidad->compras_realizadas >= 10 && !$fidelidad->premio_entregado) {
            $fidelidad->update([
                'premio_entregado' => true,
                'compras_realizadas' => 0
            ]);

            $this->dispatch('toast', '¡Premio entregado y contador reiniciado!');
        }
    }






    // MÉTODO REUTILIZABLE 
    public function resetearTodo()
    {
        $this->reset([
            'cart',
            'subtotal',
            'total_impuesto',
            'total',
            'descuento',
            'efectivo_recibido',
            'cambio',
            'metodo_pago',
            'search',
            // Cliente
            'cliente_id',
            'cliente_nombre',
            'cliente_documento',
            'buscarNombre',
            'buscarCi',
            'clientesEncontrados',
            'mostrarModalCliente',
            'nuevoCliente' // también reseteamos el formulario del modal
        ]);

        // Valores por defecto
        $this->cliente_nombre = 'Cliente Genérico';
        $this->nuevoCliente = [
            'nombre' => '',
            'ci' => '',
            'telefono' => '',
            'direccion' => '',
            'correo' => '',
        ];

        $this->calcularTotales();
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




    public function updated($property)
    {
        if ($property === 'buscarNombre' || $property === 'buscarCi') {
            $this->buscarClientes();
        }
    }

    public function buscarClientes()
    {
        $query = Cliente::query();

        if ($this->buscarNombre !== '') {
            $query->where('nombre', 'like', "%{$this->buscarNombre}%");
        }

        if ($this->buscarCi !== '') {
            $query->where('ci', 'like', "%{$this->buscarCi}%");
        }

        // Si ambos están vacíos, no mostrar nada
        if ($this->buscarNombre === '' && $this->buscarCi === '') {
            $this->clientesEncontrados = [];
            return;
        }

        $this->clientesEncontrados = $query->limit(15)->get();
    }
    public function seleccionarCliente($clienteId)
    {
        $cliente = Cliente::find($clienteId);
        if ($cliente) {
            $this->cliente_id = $cliente->id;
            $this->cliente_nombre = $cliente->nombre;
            $this->cliente_documento = $cliente->ci ?? '';
            
            // Limpiar campos de búsqueda
            $this->buscarNombre = '';
            $this->buscarCi = '';
            $this->clientesEncontrados = [];
        }
    }
    public function abrirModalCliente()
    {
        $this->nuevoCliente = [
            'nombre' => $this->cliente_nombre !== 'Cliente' ? $this->cliente_nombre : '',
            'ci' => $this->cliente_documento,
            'telefono' => '',
            'direccion' => '',
            'correo' => '',
        ];
        $this->mostrarModalCliente = true;
    }

    public function crearCliente()
    {
        $this->validate([
            'nuevoCliente.nombre' => 'required|string|max:255',
            'nuevoCliente.ci' => 'nullable|string|max:20',
            'nuevoCliente.telefono' => 'nullable|string|max:20',
        ]);

        $cliente = Cliente::create($this->nuevoCliente);

        $this->cliente_id = $cliente->id;
        $this->cliente_nombre = $cliente->nombre;
        $this->cliente_documento = $cliente->ci ?? '';
        $this->buscarCliente = $cliente->nombre_completo;

        $this->mostrarModalCliente = false;
        $this->dispatch('toast', '¡Cliente creado y seleccionado!');
    }
    public function getNombreClienteAttribute()
    {
        if ($this->cliente_id && $this->cliente) {
            return $this->cliente->nombre . ($this->cliente->ci ? " (CI: {$this->cliente->ci})" : '');
        }

        return $this->cliente_nombre ?? 'Cliente ';
    }

    
}