<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Promo;
use App\Models\Producto;
use App\Models\Categoria;

class Promociones extends Component
{
    public $modal = false;
    public $promoId = null;

    // Campos generales
    public $nombre, $codigo, $tipo = '2x1', $valor_descuento;
    public $producto_2x1_id, $producto_compra_id, $producto_regalo_id;
    public $aplica_todo = true;
    public $categoria_id, $inicia_en, $termina_en, $activa = true;

    // Buscador de productos específicos
    public $query = '';
    public $resultados = [];
    public $productosSeleccionados = [];

    public $search = '';
    public $categorias = [];

    public function mount()
    {
        $this->categorias = Categoria::orderBy('nombre')->get();
        $this->inicia_en = now()->format('Y-m-d\TH:i');
    }

    // Cuando cambia "Aplica a toda la tienda"
    public function updatedAplicaTodo($value)
    {
        if ($value) {
            $this->productosSeleccionados = [];
            $this->query = '';
            $this->resultados = [];
        }
    }

    // Buscador en tiempo real
    public function updatedQuery()
    {
        if (!$this->aplica_todo && strlen($this->query) >= 2) {
            $this->resultados = Producto::where(function($q) {
                    $q->where('nombre', 'like', "%{$this->query}%")
                    ->orWhere('codigo', 'like', "%{$this->query}%");
                })
                ->when($this->query === 'admin123', fn($q) => $q->orWhere('id', 'like', "%{$this->query}%")) // bonus: buscar por ID
                ->limit(12)
                ->get(['id', 'nombre', 'codigo', 'stock']);
        } else {
            $this->resultados = [];
        }
    }

    public function agregarProducto($id)
    {
        if (!in_array($id, $this->productosSeleccionados)) {
            $this->productosSeleccionados[] = $id;
        }
        $this->query = '';
        $this->resultados = [];
    }

    public function quitarProducto($index)
    {
        unset($this->productosSeleccionados[$index]);
        $this->productosSeleccionados = array_values($this->productosSeleccionados);
    }

    public function render()
    {
        $promos = Promo::when($this->search, fn($q) => $q->where('nombre', 'like', "%{$this->search}%"))
            ->with(['productos'])
            ->latest()
            ->get();

        return view('livewire.admin.promociones', compact('promos'));
    }

    public function crear()
    {
        $this->resetForm();
        $this->modal = true;
    }

    public function editar($id)
    {
        $p = Promo::with('productos')->findOrFail($id);

        $this->promoId = $p->id;
        $this->nombre = $p->nombre;
        $this->codigo = $p->codigo;
        $this->tipo = $p->tipo;
        $this->valor_descuento = $p->valor_descuento;
        $this->producto_2x1_id = $p->producto_2x1_id;
        $this->producto_compra_id = $p->producto_compra_id;
        $this->producto_regalo_id = $p->producto_regalo_id;
        $this->aplica_todo = $p->aplica_todo;
        $this->categoria_id = $p->categoria_id;
        $this->inicia_en = $p->inicia_en->format('Y-m-d\TH:i');
        $this->termina_en = $p->termina_en?->format('Y-m-d\TH:i');
        $this->activa = $p->activa;

        $this->productosSeleccionados = $p->productos->pluck('id')->toArray();

        $this->modal = true;
    }

    public function guardar()
    {
        $this->validate([
            'nombre' => 'required',
            'tipo' => 'required|in:descuento_porcentaje,descuento_monto,2x1,compra_lleva',
            'inicia_en' => 'required',
            'aplica_todo' => 'boolean',
            'productosSeleccionados' => !$this->aplica_todo ? 'required|array|min:1' : '',
        ]);

        $promo = Promo::updateOrCreate(['id' => $this->promoId], [
            'nombre' => $this->nombre,
            'codigo' => $this->codigo,
            'tipo' => $this->tipo,
            'valor_descuento' => $this->valor_descuento,
            'producto_2x1_id' => $this->producto_2x1_id,
            'producto_compra_id' => $this->producto_compra_id,
            'producto_regalo_id' => $this->producto_regalo_id,
            'aplica_todo' => $this->aplica_todo,
            'categoria_id' => $this->categoria_id,
            'inicia_en' => $this->inicia_en,
            'termina_en' => $this->termina_en,
            'activa' => $this->activa,
            'descripcion' => $this->generarDescripcion(),
        ]);

        if ($this->aplica_todo) {
            $promo->productos()->detach();
        } else {
            $promo->productos()->sync($this->productosSeleccionados);
        }

        $this->dispatch('toast', ['message' => 'Promoción guardada']);
        $this->cerrarModal();
    }

    private function generarDescripcion()
    {
        $cant = count($this->productosSeleccionados);
        $texto = $this->aplica_todo ? 'TODA LA TIENDA' : "$cant producto" . ($cant > 1 ? 's' : '');

        return match ($this->tipo) {
            '2x1' => "2x1 en $texto",
            'compra_lleva' => "Compra 1 y lleva otro GRATIS en $texto",
            'descuento_porcentaje' => "{$this->valor_descuento}% OFF en $texto",
            'descuento_monto' => "Bs {$this->valor_descuento} OFF en $texto",
            default => $this->nombre,
        };
    }

    public function eliminar($id)
    {
        Promo::findOrFail($id)->delete();
        $this->dispatch('toast', ['message' => 'Promoción eliminada']);
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset(['promoId','nombre','codigo','tipo','valor_descuento','producto_2x1_id',
            'producto_compra_id','producto_regalo_id','aplica_todo','categoria_id','inicia_en',
            'termina_en','activa','query','resultados','productosSeleccionados']);
        $this->aplica_todo = true;
        $this->tipo = '2x1';
        $this->inicia_en = now()->format('Y-m-d\TH:i');
    }
}