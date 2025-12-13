<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Promocion;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Validation\Rule;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class Promociones extends Component
{
    // Modal
    public $modal = false;
    public $promoId = null;

    // Datos básicos
    public $nombre = '';
    public $codigo = '';
    public $descripcion = '';
    public $tipo = 'descuento_porcentaje';
    public $valor_descuento = null;
    public $limite_usos = null;

    // Ámbito
    public $aplica_todo = true;
    public $categoria_id = null;
    public $marca_id = null;
    public $modelo_id = null;
    public $productosSeleccionados = []; // para el form: [['id','nombre'], ...]

    // Compra X lleva Y
    public $products_compra = [];  // [['id','nombre'], ...]
    public $products_regalo = [];  // [['id','nombre'], ...]
    public $query_compra = '';
    public $query_regalo = '';
    public $resultados_compra = [];
    public $resultados_regalo = [];

    // Fechas y estado
    public $inicia_en;
    public $termina_en;
    public $activa = true;

    // Búsqueda listado
    public $search = '';

    // Buscador productos ámbito
    public $query = '';
    public $resultados = [];

    // Catálogos
    public $categorias = [];
    public $marcas = [];
    public $modelos = [];

    public function mount()
    {
        $this->categorias = Categoria::orderBy('nombre')->get();
        $this->marcas     = Marca::orderBy('nombre')->get();
        $this->modelos    = Modelo::orderBy('nombre')->get();
        $this->inicia_en  = now()->format('Y-m-d\TH:i');
    }

    public function render()
    {
        $promos = Promocion::with(['categoria','marca','modelo','usos'])
            ->when($this->search, function ($q) {
                $q->where(function ($qq) {
                    $qq->where('nombre', 'like', "%{$this->search}%")
                       ->orWhere('codigo', 'like', "%{$this->search}%");
                });
            })
            ->orderByDesc('id')
            ->get();

        return view('livewire.admin.promociones', compact('promos'));
    }

    // ========= CRUD =========

    public function crear()
    {
        $this->resetForm();
        $this->modal = true;
    }

    public function editar($id)
    {
        $p = Promocion::findOrFail($id);

        $this->promoId         = $p->id;
        $this->nombre          = $p->nombre;
        $this->codigo          = $p->codigo;
        $this->descripcion     = $p->descripcion;
        $this->tipo            = $p->tipo;
        $this->valor_descuento = $p->valor_descuento;
        $this->limite_usos     = $p->limite_usos;
        $this->aplica_todo     = $p->aplica_todo;
        $this->categoria_id    = $p->categoria_id;
        $this->marca_id        = $p->marca_id;
        $this->modelo_id       = $p->modelo_id;
        $this->inicia_en       = $p->inicia_en?->format('Y-m-d\TH:i');
        $this->termina_en      = $p->termina_en?->format('Y-m-d\TH:i');
        $this->activa          = $p->activa;

        // Ámbito: cargar nombres desde IDs
        $this->productosSeleccionados = $this->cargarProductosDesdeIds($p->productos_seleccionados ?? []);

        // Compra X lleva Y
        $this->products_compra = $this->cargarProductosDesdeIds($p->products_compra ?? []);
        $this->products_regalo = $this->cargarProductosDesdeIds($p->products_regalo ?? []);

        $this->modal = true;
    }

    public function eliminar($id)
    {
        Promocion::findOrFail($id)->delete();
        $this->dispatch('toast', 'Promoción eliminada correctamente');
    }

    public function cerrarModal()
    {
        $this->modal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->reset([
            'promoId','nombre','codigo','descripcion','tipo','valor_descuento','limite_usos',
            'aplica_todo','categoria_id','marca_id','modelo_id','productosSeleccionados',
            'products_compra','products_regalo','inicia_en','termina_en','activa',
            'query','resultados','query_compra','query_regalo','resultados_compra','resultados_regalo',
        ]);

        $this->tipo        = 'descuento_porcentaje';
        $this->aplica_todo = true;
        $this->inicia_en   = now()->format('Y-m-d\TH:i');
        $this->activa      = true;
        $this->limite_usos = null;
    }

    public function updatedTipo()
    {
        $this->valor_descuento = null;

        if ($this->tipo === 'compra_lleva') {
            $this->aplica_todo            = false;
            $this->categoria_id           = null;
            $this->marca_id               = null;
            $this->modelo_id              = null;
            $this->productosSeleccionados = [];
        }
    }

    public function guardar()
    {
        // Limpiar límite de usos
        if ($this->limite_usos === '' || $this->limite_usos === '0') {
            $this->limite_usos = null;
        }

        $rules = [
            'nombre'      => 'required|string|max:255',
            'codigo'      => ['nullable','string','max:50', Rule::unique('promociones','codigo')->ignore($this->promoId)],
            'tipo'        => ['required', Rule::in(['descuento_porcentaje','descuento_monto','2x1','compra_lleva'])],
            'inicia_en'   => 'required|date',
            'termina_en'  => 'nullable|date|after_or_equal:inicia_en',
            'limite_usos' => 'nullable|integer|min:1',
        ];

        if (in_array($this->tipo, ['descuento_porcentaje','descuento_monto'])) {
            $rules['valor_descuento'] = $this->tipo === 'descuento_porcentaje'
                ? 'required|numeric|min:1|max:100'
                : 'required|numeric|min:0.01';
        }

        // Validación de ámbito
        if ($this->tipo !== 'compra_lleva' && !$this->aplica_todo) {
            if (
                !$this->categoria_id &&
                !$this->marca_id &&
                !$this->modelo_id &&
                empty($this->productosSeleccionados)
            ) {
                $this->addError('categoria_id', 'Debes seleccionar al menos una categoría, marca, modelo o producto específico.');
                return;
            }
        }

        // Validación Compra X Lleva Y
        if ($this->tipo === 'compra_lleva') {
            if (empty($this->products_compra) || empty($this->products_regalo)) {
                $this->addError('tipo', 'Debes seleccionar un producto para comprar y otro de regalo.');
                return;
            }
        }

        $this->validate($rules);

        $data = [
            'nombre'          => $this->nombre,
            'codigo'          => $this->codigo ?: null,
            'descripcion'     => $this->descripcion,
            'tipo'            => $this->tipo,
            'valor_descuento' => $this->valor_descuento,
            'limite_usos'     => $this->limite_usos,
            'inicia_en'       => Carbon::parse($this->inicia_en),
            'termina_en'      => $this->termina_en ? Carbon::parse($this->termina_en) : null,
            'activa'          => $this->activa,
        ];

        if ($this->tipo === 'compra_lleva') {
            $data += [
                'aplica_todo'             => false,
                'categoria_id'            => null,
                'marca_id'                => null,
                'modelo_id'               => null,
                'productos_seleccionados' => null,
                'products_compra'         => collect($this->products_compra)->pluck('id')->toArray(),
                'products_regalo'         => collect($this->products_regalo)->pluck('id')->toArray(),
            ];
        } else {
            $data += [
                'aplica_todo'             => $this->aplica_todo,
                'categoria_id'            => $this->categoria_id ?: null,
                'marca_id'                => $this->marca_id ?: null,
                'modelo_id'               => $this->modelo_id ?: null,
                'productos_seleccionados' => $this->aplica_todo
                    ? null
                    : collect($this->productosSeleccionados)->pluck('id')->toArray(),
                'products_compra'         => null,
                'products_regalo'         => null,
            ];
        }

        Promocion::updateOrCreate(['id' => $this->promoId], $data);

        $this->dispatch('toast', '¡Promoción guardada con éxito!');
        $this->cerrarModal();
    }



    // ========= BUSCADORES =========

    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->resultados = [];
            return;
        }

        $this->resultados = Producto::where('nombre', 'like', "%{$this->query}%")
            ->orWhere('codigo', 'like', "%{$this->query}%")
            ->limit(10)
            ->get(['id','nombre','codigo','stock'])
            ->map(fn($p) => [
                'id'     => $p->id,
                'nombre' => $p->nombre,
                'codigo' => $p->codigo,
                'stock'  => $p->stock,
            ])->toArray();
    }

    public function updatedQueryCompra() { $this->resultados_compra = $this->buscarProductos($this->query_compra); }
    public function updatedQueryRegalo() { $this->resultados_regalo = $this->buscarProductos($this->query_regalo); }

    private function buscarProductos($texto)
    {
        if (strlen($texto) < 2) return [];
        return Producto::where('nombre', 'like', "%{$texto}%")
            ->orWhere('codigo', 'like', "%{$texto}%")
            ->limit(10)
            ->get(['id','nombre','codigo','stock'])
            ->map(fn($p) => [
                'id'     => $p->id,
                'nombre' => $p->nombre,
                'codigo' => $p->codigo,
                'stock'  => $p->stock,
            ])->toArray();
    }

    public function agregarProducto($id)
    {
        if (collect($this->productosSeleccionados)->contains('id', $id)) return;

        $prod = Producto::select('id','nombre','codigo')->find($id);
        if ($prod) {
            $this->productosSeleccionados[] = [
                'id'     => $prod->id,
                'nombre' => $prod->nombre . ' (' . $prod->codigo . ')',
            ];
        }

        $this->query = '';
        $this->resultados = [];
    }

    public function quitarProducto($index)
    {
        unset($this->productosSeleccionados[$index]);
        $this->productosSeleccionados = array_values($this->productosSeleccionados);
    }

    public function seleccionarCompra($id)
    {
        $this->products_compra = [];
        $this->agregarALista('products_compra', $id);
        $this->query_compra = '';
        $this->resultados_compra = [];
    }

    public function seleccionarRegalo($id)
    {
        $this->products_regalo = [];
        $this->agregarALista('products_regalo', $id);
        $this->query_regalo = '';
        $this->resultados_regalo = [];
    }

    private function agregarALista($prop, $id)
    {
        $prod = Producto::select('id','nombre','codigo')->find($id);
        if ($prod) {
            $this->$prop = [[
                'id'     => $prod->id,
                'nombre' => $prod->nombre . ' (' . $prod->codigo . ')',
            ]];
        }
    }

    private function cargarProductosDesdeIds($ids): array
    {
        if (empty($ids)) return [];

        if ($ids instanceof Collection) {
            $ids = $ids->toArray();
        }

        return Producto::whereIn('id', $ids)
            ->get(['id','nombre','codigo'])
            ->map(fn($p) => [
                'id'     => $p->id,
                'nombre' => $p->nombre . ' (' . $p->codigo . ')',
            ])->toArray();
    }
}
