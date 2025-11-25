<?php

namespace App\Livewire\Inventario;

use Livewire\Component;
use App\Models\EntradaInventario;
use App\Models\DetalleEntrada;
use App\Models\Proveedor;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class EditEntradaInventario extends Component
{
    public $entrada;
    public $proveedor_id;
    public $fecha;
    public $observacion = '';
    public $detalles = [];
    public $total = 0.0;

    public $proveedores;
    public $productos;

    protected $listeners = ['submitEntrada' => 'submit'];

    public function mount($id)
    {
        $this->entrada = EntradaInventario::with('detalles.producto')->findOrFail($id);

        $this->proveedor_id = $this->entrada->proveedor_id;
        $this->fecha        = $this->entrada->fecha;
        $this->observacion  = $this->entrada->observacion;

        // Cargar detalles actuales
        foreach ($this->entrada->detalles as $detalle) {
            $this->detalles[] = [
                'id'          => $detalle->id,
                'producto_id' => $detalle->producto_id,
                'cantidad'    => (string)$detalle->cantidad,
                'costo'       => (string)$detalle->costo,
                'subtotal'    => $detalle->subtotal,
            ];
        }

        $this->proveedores = Proveedor::all();
        $this->productos   = Producto::all();

        $this->calcularTotal();
    }

    public function addDetalle()
    {
        $this->detalles[] = [
            'producto_id' => '',
            'cantidad'    => '',
            'costo'       => '',
            'subtotal'    => 0,
        ];
    }

    public function removeDetalle($index)
    {
        unset($this->detalles[$index]);
        $this->detalles = array_values($this->detalles);
        $this->calcularTotal();
    }

    public function updatedDetalles($value, $key)
    {
        [$index, $field] = explode('.', $key);

        if ($field === 'cantidad' || $field === 'costo') {
            $cantidad = floatval($this->detalles[$index]['cantidad'] ?: 0);
            $costo    = floatval($this->detalles[$index]['costo'] ?: 0);

            $this->detalles[$index]['subtotal'] = $cantidad * $costo;
            $this->calcularTotal();
        }
    }

    public function calcularTotal()
    {
        $this->total = array_sum(array_column($this->detalles, 'subtotal'));
    }

    public function submit()
    {
        $this->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha'        => 'required|date',
            'detalles.*.producto_id' => 'required|exists:productos,id|distinct',
            'detalles.*.cantidad'    => 'required|numeric|min:1',
            'detalles.*.costo'       => 'required|numeric|min:0',
        ]);

        try {
            DB::transaction(function () {

                // 1️⃣ Devolver stock de los detalles anteriores
                foreach ($this->entrada->detalles as $detalleViejo) {
                    $detalleViejo->producto->decrement('stock', $detalleViejo->cantidad);
                    $detalleViejo->delete();
                }

                // 2️⃣ Actualizar cabecera de la entrada
                $this->entrada->update([
                    'proveedor_id' => $this->proveedor_id,
                    'fecha'        => $this->fecha,
                    'observacion'  => $this->observacion,
                    'total'        => $this->total,
                ]);

                // 3️⃣ Insertar nuevos detalles
                foreach ($this->detalles as $detalle) {

                    $nuevoDetalle = DetalleEntrada::create([
                        'entrada_inventario_id' => $this->entrada->id,
                        'producto_id' => $detalle['producto_id'],
                        'cantidad'    => $detalle['cantidad'],
                        'costo'       => $detalle['costo'],
                        'subtotal'    => $detalle['subtotal'],
                    ]);

                    // 4️⃣ Aumentar stock nuevo
                    $nuevoDetalle->producto->increment('stock', $detalle['cantidad']);
                }
            });

            session()->flash('message', 'Entrada actualizada correctamente.');
            return redirect()->route('entradas.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.inventario.edit-entrada-inventario');
    }
}
