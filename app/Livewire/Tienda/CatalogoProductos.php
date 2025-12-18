<?php

namespace App\Livewire\Tienda;

use App\Models\Producto;
use App\Models\Marca;
use App\Models\Categoria;
use Livewire\Component;
use Livewire\WithPagination;

class CatalogoProductos extends Component
{
    use WithPagination;

    // Filtros básicos
    public string $busqueda = '';

    public array $modelosSeleccionados = [];
    // Rango de precio
    public ?float $precioMin = null;
    public ?float $precioMax = null;
    public ?string $rangoPrecio = null;

    // Multi-selección
    public array $marcasSeleccionadas = [];
    public array $categoriasSeleccionadas = [];
    public array $coloresSeleccionados = [];
    public array $tiposSeleccionados = [];

    // Filtros adicionales
    public string $disponibilidad = 'cualquiera'; // cualquiera, disponible, agotado
    public bool $enOferta = false;

    public int $porPagina = 12;

    // Modal
    public $productoSeleccionado = null;

    // Para URL limpia
    protected $queryString = [
        'busqueda' => ['except' => ''],
        'precioMin' => ['except' => null],
        'precioMax' => ['except' => null],
        'rangoPrecio' => ['except' => null],
        'disponibilidad' => ['except' => 'cualquiera'],
        'enOferta' => ['except' => false],
        'page' => ['except' => 1],
    ];

    /* ===================== LIMPIAR FILTROS ===================== */
    public function limpiarFiltros(): void
    {
        $this->reset([
            'busqueda',
            'precioMin',
            'precioMax',
            'rangoPrecio',
            'marcasSeleccionadas',
            'categoriasSeleccionadas',
            'coloresSeleccionados',
            'tiposSeleccionados',
            'modelosSeleccionados',
            'disponibilidad',
            'enOferta',
        ]);
        $this->resetPage();
    }

    /* ===================== RESET PAGINA AL CAMBIAR FILTRO ===================== */
    public function updated($property)
    {
        $filtros = [
            'busqueda',
            'precioMin',
            'precioMax',
            'rangoPrecio',
            'marcasSeleccionadas',
            'categoriasSeleccionadas',
            'coloresSeleccionados',
            'modelosSeleccionados',
            'tiposSeleccionados',
            'disponibilidad',
            'enOferta',
        ];

        if (in_array($property, $filtros)) {
            $this->resetPage();
        }
    }

    /* ===================== MODAL ===================== */
    public function abrirModal(int $id): void
    {
        $this->productoSeleccionado = Producto::with(['marca', 'modelo', 'categoria', 'promo'])->find($id);
    }

    public function cerrarModal(): void
    {
        $this->productoSeleccionado = null;
    }

    /* ===================== AGREGAR AL PEDIDO ===================== */
    public function agregarAlPedido(int $productoId): void
    {
        $producto = Producto::find($productoId);

        if (!$producto) {
            $this->dispatch('mostrar-toast', mensaje: 'Producto no encontrado.');
            return;
        }

        if ($producto->stock <= 0) {
            $this->dispatch('mostrar-toast', mensaje: 'Producto agotado.');
            return;
        }

        if ($producto->stock <= 5) {
            $this->dispatch('mostrar-toast', mensaje: '¡Últimas unidades! (Quedan ' . $producto->stock . ')');
        }

        $this->dispatch('agregar-producto-al-pedido', $productoId);
    }

    /* ===================== RENDER ===================== */
    public function render()
    {
        $query = Producto::query()
            ->with(['marca', 'modelo', 'categoria', 'promo'])
            ->where('activo', 1);

        // Búsqueda
        if ($this->busqueda !== '') {
            $query->where(function ($q) {
                $q->where('nombre', 'like', "%{$this->busqueda}%")
                ->orWhere('codigo', 'like', "%{$this->busqueda}%");
            });
        }

        // Marcas (multi)
        if (!empty($this->marcasSeleccionadas)) {
            $query->whereIn('marca_id', $this->marcasSeleccionadas);
        }

        // Categorías (multi)
        if (!empty($this->categoriasSeleccionadas)) {
            $query->whereIn('categoria_id', $this->categoriasSeleccionadas);
        }

        // Modelos (multi)
        if (!empty($this->modelosSeleccionados)) {
            $query->whereIn('modelo_id', $this->modelosSeleccionados);
        }

        // Disponibilidad
        if ($this->disponibilidad === 'disponible') {
            $query->where('stock', '>', 0);
        } elseif ($this->disponibilidad === 'agotado') {
            $query->where('stock', '<=', 0);
        }

        // En oferta
        if ($this->enOferta) {
            $query->whereNotNull('promo_id'); // Ajusta si usas otro campo para ofertas
        }

        // Rango rápido de precio (si lo usas)
        if ($this->rangoPrecio) {
            match ($this->rangoPrecio) {
                '1' => $query->where('precio', '<', 5),
                '2' => $query->whereBetween('precio', [5, 10]),
                '3' => $query->whereBetween('precio', [10, 20]),
                '4' => $query->whereBetween('precio', [20, 30]),
                '5' => $query->where('precio', '>', 30),
                default => null,
            };
        }

        // Precio manual
        if ($this->precioMin !== null) {
            $query->where('precio', '>=', $this->precioMin);
        }
        if ($this->precioMax !== null) {
            $query->where('precio', '<=', $this->precioMax);
        }

        // Productos paginados
        $productos = $query->orderBy('nombre')->paginate($this->porPagina);

        // ==================== CARGA DE OPCIONES Y CONTEOS DINÁMICOS ====================

        // Marcas, categorías y modelos
        $marcas     = Marca::orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre')->get();
        $modelos    = \App\Models\Modelo::orderBy('nombre')->get(); // Asegúrate de tener el modelo Modelo

        // Conteos dinámicos (sin filtros aplicados, para mostrar números reales al lado de cada opción)
        $conteoMarcas = Producto::where('activo', 1)
            ->select('marca_id', \DB::raw('count(*) as total'))
            ->groupBy('marca_id')
            ->pluck('total', 'marca_id');

        $conteoCategorias = Producto::where('activo', 1)
            ->select('categoria_id', \DB::raw('count(*) as total'))
            ->groupBy('categoria_id')
            ->pluck('total', 'categoria_id');

        $conteoModelos = Producto::where('activo', 1)
            ->select('modelo_id', \DB::raw('count(*) as total'))
            ->groupBy('modelo_id')
            ->pluck('total', 'modelo_id');

        // Conteos globales para Disponibilidad y Oferta
        $totalProductos   = Producto::where('activo', 1)->count();
        $totalDisponibles = Producto::where('activo', 1)->where('stock', '>', 0)->count();
        $totalAgotados    = $totalProductos - $totalDisponibles;
        $totalEnOferta    = Producto::where('activo', 1)->whereNotNull('promo_id')->count();

        // Retornar vista con todas las variables
        return view('livewire.tienda.catalogo-productos', compact(
            'productos',
            'marcas',
            'categorias',
            'modelos',
            'conteoMarcas',
            'conteoCategorias',
            'conteoModelos',
            'totalDisponibles',
            'totalAgotados',
            'totalEnOferta'
        ))->layout('layouts.shop');
    }
}