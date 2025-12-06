<?php

namespace App\Livewire\Inventario;

use Livewire\Component;
use App\Models\Proveedor;
use App\Models\Producto;
use App\Models\EntradaInventario;
use App\Models\DetalleEntrada;
use Illuminate\Support\Facades\DB;

class CreateEntradaInventario extends Component
{
    public $proveedor_id = '';
    public $fecha;
    public $observacion = '';
    public $processing = false; // ← agrega esta línea arriba

    public $detalles = [];
    public $total = 0.0;

    
    public $proveedores;
    public $productos;

    public $busquedas = [];  // Para el textbox de búsqueda
    public $showToast = false;
    public $toastMessage = '';

    public function mount()
    {
        $this->proveedores = Proveedor::all();
        $this->productos = Producto::all();
        $this->fecha = now()->format('Y-m-d');
        $this->addDetalle();
    }

    public function addDetalle()
    {
        $this->detalles[] = [
            'producto_id' => '',
            'nombre_producto' => '', // solo para mostrar
            'cantidad' => '',
            'costo' => '',
            'subtotal' => 0.0
        ];
        $this->busquedas[] = '';
    }

    public function removeDetalle($index)
    {
        unset($this->detalles[$index]);
        unset($this->busquedas[$index]);
        $this->detalles = array_values($this->detalles);
        $this->busquedas = array_values($this->busquedas);
        $this->calcularTotal();
    }

    // SELECCIONAR PRODUCTO
    public function seleccionarProducto($index, $productoId)
    {
        $producto = Producto::find($productoId);
        if ($producto) {
            $this->detalles[$index]['producto_id'] = $producto->id;
            $this->detalles[$index]['nombre_producto'] = $producto->nombre . ($producto->codigo ? " ({$producto->codigo})" : '');
        }
        $this->busquedas[$index] = '';
        $this->calcularTotal();
    }

    public function buscarProductos($index)
    {
        $query = trim($this->busquedas[$index] ?? '');
        if (strlen($query) < 2) return collect();

        return Producto::where('nombre', 'LIKE', "%{$query}%")
            ->orWhere('codigo', 'LIKE', "%{$query}%")
            ->limit(10)
            ->get();
    }

    // ACTUALIZA CANTIDAD Y COSTO
    public function updatedDetalles($value, $key)
    {
        if (str_contains($key, '.')) {
            [$index, $campo] = explode('.', $key);

            $cantidad = (int)($this->detalles[$index]['cantidad'] ?? 0);
            $costo = (float)str_replace(',', '.', ($this->detalles[$index]['costo'] ?? 0));

            $this->detalles[$index]['cantidad'] = $cantidad > 0 ? $cantidad : '';
            $this->detalles[$index]['costo'] = $costo > 0 ? $costo : '';
            $this->detalles[$index]['subtotal'] = $cantidad * $costo;

            $this->calcularTotal();
        }
    }

    public function calcularTotal()
    {
        $this->total = 0;
        foreach ($this->detalles as $i => $d) {
            $cantidad = (int)($d['cantidad'] ?? 0);
            $costo = (float)($d['costo'] ?? 0);
            $this->detalles[$i]['subtotal'] = $cantidad * $costo;
            $this->total += $cantidad * $costo;
        }
    }

    protected function rules()
    {
        return [
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha' => 'required|date',
            'detalles.*.producto_id' => 'required|exists:productos,id|distinct',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.costo' => 'required|numeric|min:0.01',
        ];
    }

    protected function messages()
    {
        return [
            'proveedor_id.required' => 'Selecciona un proveedor.',
            'detalles.*.producto_id.required' => 'Selecciona un producto en cada fila.',
            'detalles.*.producto_id.distinct' => 'No repitas productos.',
            'detalles.*.cantidad.required' => 'Ingresa cantidad.',
            'detalles.*.cantidad.min' => 'Mínimo 1 unidad.',
            'detalles.*.costo.required' => 'Ingresa costo.',
            'detalles.*.costo.min' => 'Costo mayor a 0.',
        ];
    }

    public function submit()
    {
        $this->validate();
        $this->dispatch('confirmar-guardado');
    }


public function guardarConfirmado()
{
    if ($this->processing) {
        return; // ← evita que se ejecute dos veces
    }

    $this->processing = true;

    try {
        DB::transaction(function () {
            $entrada = EntradaInventario::create([
                'proveedor_id' => $this->proveedor_id,
                'fecha' => $this->fecha,
                'observacion' => $this->observacion,
                'total' => $this->total,
            ]);

            foreach ($this->detalles as $d) {
                if (empty($d['producto_id'])) continue;

                DetalleEntrada::create([
                    'entrada_inventario_id' => $entrada->id,
                    'producto_id' => $d['producto_id'],
                    'cantidad' => $d['cantidad'],
                    'costo' => $d['costo'],
                    'subtotal' => $d['subtotal'],
                ]);

                // Esto se ejecuta solo una vez
                Producto::where('id', $d['producto_id'])->increment('stock', $d['cantidad']);
            }
        });

        $this->toastMessage = 'Entrada registrada correctamente';
        $this->showToast = true;

        $this->reset(['proveedor_id', 'observacion', 'detalles', 'total', 'busquedas']);
        $this->addDetalle();
        $this->fecha = now()->format('Y-m-d');

    } catch (\Exception $e) {
        $this->toastMessage = 'Error: ' . $e->getMessage();
        $this->showToast = true;
    } finally {
        $this->processing = false; // ← siempre libera el candado
    }
}

    public function render()
    {
        return view('livewire.inventario.create-entrada-inventario');
    }
}