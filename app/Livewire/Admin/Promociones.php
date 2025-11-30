<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Promocion;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class Promociones extends Component
{
    public $modal = false;
    public $promoId = null;

    // Buscadores especiales
    public $query_2x1 = '';
    public $resultados_2x1 = [];

    public $query_compra = '';
    public $resultados_compra = [];

    public $query_regalo = '';
    public $resultados_regalo = [];

    // Datos básicos
    public $nombre;
    public $codigo;
    public $descripcion;

    // Tipo de promo
    public $tipo = 'descuento_porcentaje';
    public $valor_descuento;

    // Datos especiales según tipo (ahora ARRAYS de arrays con id y nombre)
    public $productos_2x1 = [];      // [{'id' => x, 'nombre' => y}, ...]
    public $productos_compra = [];
    public $productos_regalo = [];

    // Ámbito
    public $aplica_todo = true;
    public $categoria_id;
    public $productosSeleccionados = []; // [{'id' => x, 'nombre' => y}, ...]

    // Fechas/estado
    public $inicia_en;
    public $termina_en;
    public $activa = true;

    // Buscador de productos generales
    public $query = '';
    public $resultados = [];

    // Filtros de listado
    public $search = '';

    public $categorias = [];

    public function mount()
    {
        $this->categorias = Categoria::orderBy('nombre')->get();
        $this->inicia_en  = now()->format('Y-m-d\TH:i');
        $this->activa     = true;
    }

    public function render()
    {
        $promos = Promocion::when($this->search, function ($q) {
                $q->where('nombre', 'like', "%{$this->search}%")
                  ->orWhere('codigo', 'like', "%{$this->search}%");
            })
            ->with('productos', 'categoria')
            ->orderByDesc('id')
            ->get();

        return view('livewire.admin.promociones', compact('promos'));
    }

    // ----------------- UI acciones -----------------

    public function crear()
    {
        $this->resetForm();
        $this->modal = true;
    }

    public function editar($id)
    {
        $p = Promocion::with('productos')->findOrFail($id);

        $this->promoId      = $p->id;
        $this->nombre       = $p->nombre;
        $this->codigo       = $p->codigo;
        $this->descripcion  = $p->descripcion;
        $this->tipo         = $p->tipo;
        $this->valor_descuento = $p->valor_descuento;
        $this->aplica_todo  = $p->aplica_todo;
        $this->categoria_id = $p->categoria_id;
        $this->inicia_en    = $p->inicia_en?->format('Y-m-d\TH:i');
        $this->termina_en   = $p->termina_en?->format('Y-m-d\TH:i');
        $this->activa       = $p->activa;

        // Cargar productos generales como array de ['id', 'nombre']
        $this->productosSeleccionados = $p->productos->map(function ($prod) {
            return ['id' => $prod->id, 'nombre' => $prod->nombre];
        })->toArray();

        // Cargar arrays especiales (asumiendo JSON en BD)
        $this->productos_2x1 = [];
        if ($p->products_2x1) {
            $prods = Producto::whereIn('id', $p->products_2x1)->get();
            $this->products_2x1 = $prods->map(function ($prod) {
                return ['id' => $prod->id, 'nombre' => $prod->nombre];
            })->toArray();
        }

        $this->productos_compra = [];
        if ($p->products_compra) {
            $prods = Producto::whereIn('id', $p->products_compra)->get();
            $this->productos_compra = $prods->map(function ($prod) {
                return ['id' => $prod->id, 'nombre' => $prod->nombre];
            })->toArray();
        }

        $this->productos_regalo = [];
        if ($p->products_regalo) {
            $prods = Producto::whereIn('id', $p->products_regalo)->get();
            $this->productos_regalo = $prods->map(function ($prod) {
                return ['id' => $prod->id, 'nombre' => $prod->nombre];
            })->toArray();
        }

        $this->modal = true;
    }

    public function eliminar($id)
    {
        Promocion::findOrFail($id)->delete();
        $this->dispatch('toast', 'Promoción eliminada');
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'promoId','nombre','codigo','descripcion',
            'tipo','valor_descuento',
            'productos_2x1','productos_compra','productos_regalo',
            'aplica_todo','categoria_id','productosSeleccionados',
            'inicia_en','termina_en','activa',
            'query','resultados',
            'query_2x1','resultados_2x1',
            'query_compra','resultados_compra',
            'query_regalo','resultados_regalo',
        ]);

        $this->tipo        = 'descuento_porcentaje';
        $this->aplica_todo = true;
        $this->inicia_en   = now()->format('Y-m-d\TH:i');
        $this->activa      = true;
    }

    // ----------------- Guardar -----------------

    public function guardar()
    {
        $rules = [
            'nombre'      => 'required|string|max:255',
            'codigo'      => 'nullable|string|max:50',
            'tipo'        => ['required', Rule::in(['descuento_porcentaje','descuento_monto','2x1','compra_lleva'])],
            'inicia_en'   => 'required|date',
            'termina_en'  => 'nullable|date|after_or_equal:inicia_en',
            'aplica_todo' => 'boolean',
            'activa'      => 'boolean',
        ];

        // Si no aplica a toda la tienda y no hay categoría, exigimos productos generales
        if (!$this->aplica_todo && !$this->categoria_id) {
            $rules['productosSeleccionados'] = 'required|array|min:1';
        }

        // Reglas según tipo
        if ($this->tipo === 'descuento_porcentaje') {
            $rules['valor_descuento'] = 'required|numeric|min:1|max:100';
        }

        if ($this->tipo === 'descuento_monto') {
            $rules['valor_descuento'] = 'required|numeric|min:0.1';
        }

        if ($this->tipo === '2x1') {
            $rules['productos_2x1'] = 'required|array|min:1';
        }

        if ($this->tipo === 'compra_lleva') {
            $rules['productos_compra'] = 'required|array|min:1';
            $rules['productos_regalo'] = 'required|array|min:1';
        }

        $this->validate($rules);

        $promo = Promocion::updateOrCreate(['id' => $this->promoId], [
            'nombre'          => $this->nombre,
            'codigo'          => $this->codigo,
            'descripcion'     => $this->descripcion ?: $this->generarDescripcion(),
            'tipo'            => $this->tipo,
            'valor_descuento' => $this->valor_descuento ?: null,
            'producto_2x1_id'    => null, // Deprecated, using JSON
            'producto_compra_id' => null,
            'producto_regalo_id' => null,
            'aplica_todo'        => $this->aplica_todo,
            'categoria_id'       => $this->categoria_id ?: null,
            'inicia_en'          => Carbon::parse($this->inicia_en),
            'termina_en'         => $this->termina_en ? Carbon::parse($this->termina_en) : null,
            'activa'             => $this->activa,
        ]);

        // Sync productos generales
        if ($this->aplica_todo || $this->categoria_id) {
            $promo->productos()->detach();
        }
        if (!$this->aplica_todo && $this->productosSeleccionados) {
            $promo->productos()->sync(collect($this->productosSeleccionados)->pluck('id'));
        }

        // Guardar arrays especiales como JSON (solo IDs)
        $promo->products_2x1 = collect($this->products_2x1)->pluck('id')->toArray();
        $promo->products_compra = collect($this->products_compra)->pluck('id')->toArray();
        $promo->products_regalo = collect($this->products_regalo)->pluck('id')->toArray();
        $promo->save();

        $this->dispatch('toast', 'Promoción guardada correctamente');
        $this->cerrarModal();
    }

    private function generarDescripcion()
    {
        $texto = $this->aplica_todo
            ? 'TODA LA TIENDA'
            : (count($this->productosSeleccionados).' producto'.(count($this->productosSeleccionados) > 1 ? 's' : ''));

        return match ($this->tipo) {
            '2x1'                  => "2x1 en $texto",
            'compra_lleva'         => "Compra y lleva gratis en $texto",
            'descuento_porcentaje' => "{$this->valor_descuento}% OFF en $texto",
            'descuento_monto'      => "Bs {$this->valor_descuento} OFF en $texto",
            default                => $this->nombre,
        };
    }

    // ----------------- Ámbito y buscador general -----------------

    public function updatedAplicaTodo($value)
    {
        if ($value) {
            $this->productosSeleccionados = [];
            $this->query      = '';
            $this->resultados = [];
            $this->categoria_id = null;
        }
    }

    public function updatedQuery()
    {
        if (!$this->aplica_todo && strlen($this->query) >= 2) {
            $this->resultados = Producto::where(function ($q) {
                    $q->where('nombre', 'like', "%{$this->query}%")
                      ->orWhere('codigo', 'like', "%{$this->query}%");
                })
                ->limit(12)
                ->get(['id', 'nombre', 'codigo', 'stock']);
        } else {
            $this->resultados = [];
        }
    }

    public function agregarProducto($id)
    {
        $prod = Producto::find($id);
        if ($prod && !collect($this->productosSeleccionados)->contains('id', $id)) {
            $this->productosSeleccionados[] = ['id' => $id, 'nombre' => $prod->nombre];
        }
        $this->query = '';
        $this->resultados = [];
    }

    public function quitarProducto($index)
    {
        unset($this->productosSeleccionados[$index]);
        $this->productosSeleccionados = array_values($this->productosSeleccionados);
    }

    // === BUSCADORES EN TIEMPO REAL PARA 2x1 Y COMPRA-LLEVA ===

    public function updatedQuery2x1()
    {
        if (strlen($this->query_2x1) >= 2) {
            $this->resultados_2x1 = Producto::where('nombre', 'like', "%{$this->query_2x1}%")
                ->orWhere('codigo', 'like', "%{$this->query_2x1}%")
                ->limit(10)
                ->get(['id', 'nombre', 'codigo', 'stock']);
        } else {
            $this->resultados_2x1 = [];
        }
    }

    public function updatedQueryCompra()
    {
        if (strlen($this->query_compra) >= 2) {
            $this->resultados_compra = Producto::where('nombre', 'like', "%{$this->query_compra}%")
                ->orWhere('codigo', 'like', "%{$this->query_compra}%")
                ->limit(10)
                ->get(['id', 'nombre', 'codigo', 'stock']);
        } else {
            $this->resultados_compra = [];
        }
    }

    public function updatedQueryRegalo()
    {
        if (strlen($this->query_regalo) >= 2) {
            $this->resultados_regalo = Producto::where('nombre', 'like', "%{$this->query_regalo}%")
                ->orWhere('codigo', 'like', "%{$this->query_regalo}%")
                ->limit(10)
                ->get(['id', 'nombre', 'codigo', 'stock']);
        } else {
            $this->resultados_regalo = [];
        }
    }

    // === SELECCIONAR Y QUITAR PRODUCTOS ESPECIALES (VARIOS) ===

    public function seleccionar2x1($id)
    {
        $prod = Producto::find($id);
        if ($prod && !collect($this->products_2x1)->contains('id', $id)) {
            $this->products_2x1[] = ['id' => $id, 'nombre' => $prod->nombre];
        }
        $this->query_2x1 = '';
        $this->resultados_2x1 = [];
    }

    public function quitar2x1($index)
    {
        unset($this->products_2x1[$index]);
        $this->products_2x1 = array_values($this->products_2x1);
    }

    public function seleccionarCompra($id)
    {
        $prod = Producto::find($id);
        if ($prod && !collect($this->products_compra)->contains('id', $id)) {
            $this->products_compra[] = ['id' => $id, 'nombre' => $prod->nombre];
        }
        $this->query_compra = '';
        $this->resultados_compra = [];
    }

    public function quitarCompra($index)
    {
        unset($this->products_compra[$index]);
        $this->products_compra = array_values($this->products_compra);
    }

    public function seleccionarRegalo($id)
    {
        $prod = Producto::find($id);
        if ($prod && !collect($this->products_regalo)->contains('id', $id)) {
            $this->products_regalo[] = ['id' => $id, 'nombre' => $prod->nombre];
        }
        $this->query_regalo = '';
        $this->resultados_regalo = [];
    }

    public function quitarRegalo($index)
    {
        unset($this->products_regalo[$index]);
        $this->products_regalo = array_values($this->products_regalo);
    }
}