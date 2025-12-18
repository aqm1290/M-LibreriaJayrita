<?php

namespace App\Livewire\Tienda;

use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;

class CatalogoMarca extends Component
{
    use WithPagination;

    public Marca $marca;
    public ?int $marcaId = null;

    public ?int $modeloId = null;
    public string $busqueda = '';
    public string $orden = 'relevancia'; // relevancia, precio_asc, precio_desc, nombre_asc

    public ?Producto $productoSeleccionado = null;

    // Mantener filtros en la URL (opcional pero recomendado)
    protected $queryString = [
        'busqueda' => ['except' => ''],
        'orden'    => ['except' => 'relevancia'],
        'modeloId' => ['except' => null],
    ];

    public function mount(Marca $marca): void
    {
        $this->marca   = $marca;
        $this->marcaId = $marca->id;
    }

    // Resetear página al cambiar búsqueda u orden
    public function updatingBusqueda(): void
    {
        $this->resetPage();
    }

    public function updatingOrden(): void
    {
        $this->resetPage();
    }

    // Al cambiar modelo, resetear página y limpiar selección
    public function setModelo(?int $id): void
    {
        $this->modeloId = $id;
        $this->resetPage();
        $this->seleccionarPrimerProducto();
    }

    // Agregar al carrito con toast
    public function agregarAlPedido(int $productoId): void
    {
        $this->dispatch('agregar-producto-al-pedido', $productoId);
        $this->dispatch('mostrar-toast', mensaje: '¡Producto agregado al carrito!');
    }

    // Seleccionar producto manualmente (al hacer clic en galería)
    public function seleccionarProducto(int $id): void
    {
        $this->productoSeleccionado = Producto::with(['marca', 'modelo', 'categoria', 'promo'])
            ->findOrFail($id);
    }

    // Selecciona automáticamente el primer producto después de filtrar
    private function seleccionarPrimerProducto(): void
    {
        $primerProducto = $this->getProductosQuery()->first();

        if ($primerProducto) {
            $this->productoSeleccionado = $primerProducto;
        } else {
            $this->productoSeleccionado = null;
        }
    }

    // Query base reutilizable
    private function getProductosQuery()
    {
        return Producto::with(['marca', 'modelo', 'categoria', 'promo'])
            ->where('activo', 1)
            ->where('marca_id', $this->marcaId)
            ->when($this->modeloId, fn($q) => $q->where('modelo_id', $this->modeloId))
            ->when($this->busqueda !== '', function ($q) {
                $term = '%' . trim($this->busqueda) . '%';
                return $q->where(function ($qq) use ($term) {
                    $qq->where('nombre', 'like', $term)
                       ->orWhere('codigo', 'like', $term);
                });
            })
            ->when($this->orden, function ($q) {
                return match ($this->orden) {
                    'precio_asc'  => $q->orderBy('precio', 'asc'),
                    'precio_desc' => $q->orderBy('precio', 'desc'),
                    'nombre_asc'  => $q->orderBy('nombre', 'asc'),
                    default => $q->orderByRaw("
                        CASE
                            WHEN nombre LIKE ? OR codigo LIKE ? THEN 0
                            ELSE 1
                        END ASC, precio ASC
                    ", ['%' . $this->busqueda . '%', '%' . $this->busqueda . '%'])
                };
            });
    }

    public function render()
    {
        // Obtener modelos disponibles para esta marca
        $modelos = Modelo::whereHas('productos', fn($q) => $q->where('marca_id', $this->marcaId)->where('activo', 1))
            ->orderBy('nombre')
            ->get();

        // Productos paginados con filtros
        $productos = $this->getProductosQuery()->paginate(12);

        // Si no hay producto seleccionado (o se filtró y quedó vacío), seleccionar el primero automáticamente
        if (!$this->productoSeleccionado || !$productos->contains($this->productoSeleccionado)) {
            $this->seleccionarPrimerProducto();
        }

        return view('livewire.tienda.catalogo-marca', [
            'modelos'    => $modelos,
            'productos'  => $productos,
        ])->layout('layouts.shop');
    }
}