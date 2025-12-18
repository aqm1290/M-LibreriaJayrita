<?php

namespace App\Livewire\Tienda;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Promocion;
use Illuminate\Support\Collection;

class HomeProductos extends Component
{
    use WithPagination;

    public string|int $categoriaActiva = 'todas';

    public ?Producto $productoSeleccionado = null;
    public ?Marca $marcaSeleccionada = null;
    public int $marcaProductosCount = 0;

    /** @var \Illuminate\Support\Collection|null */
    public ?Collection $promosHome = null;

    protected $queryString = [
        'categoriaActiva' => ['except' => 'todas'],
    ];

    public function updatingCategoriaActiva()
    {
        $this->resetPage('cat_page');
    }

    public function setCategoria($categoriaId): void
    {
        $this->categoriaActiva = $categoriaId === 'todas' ? 'todas' : (int) $categoriaId;
        $this->resetPage('cat_page');
    }

    public function seleccionarProducto(int $id): void
    {
        $this->productoSeleccionado = Producto::with(['categoria', 'marca'])->find($id);
    }

    public function seleccionarMarca(int $id): void
    {
        $this->marcaSeleccionada = Marca::find($id);
        $this->marcaProductosCount = Producto::where('marca_id', $id)->count();
    }

    public function cerrarModal(): void
    {
        $this->productoSeleccionado = null;
        $this->marcaSeleccionada = null;
    }

    public function irACatalogoMarca()
    {
        if (! $this->marcaSeleccionada) {
            return;
        }

        return redirect()->route('tienda.marca', ['marca' => $this->marcaSeleccionada->id]);
    }

    public function agregarAlPedido(int $productoId): void
    {
        $producto = Producto::find($productoId);

        // Si no existe o no tiene stock, no permitir agregar
        if (! $producto || ($producto->stock ?? 0) <= 0) {
            $this->dispatch('mostrar-toast', mensaje: 'Sin stock para este producto');
            return;
        }

        // Lógica normal
        $this->dispatch('agregar-producto-al-pedido', $productoId);
        $this->dispatch('mostrar-toast', mensaje: '¡Agregado al carrito!');
        $this->productoSeleccionado = null;
    }


    /**
     * Construye la colección de productos en promoción para el carrusel.
     */
    protected function cargarPromosHome(): void
    {
        $hoy = now();

        $promociones = Promocion::where('activa', true)
            ->where(function ($q) use ($hoy) {
                $q->whereNull('inicia_en')->orWhere('inicia_en', '<=', $hoy);
            })
            ->where(function ($q) use ($hoy) {
                $q->whereNull('termina_en')->orWhere('termina_en', '>=', $hoy);
            })
            ->orderByDesc('id')
            ->get();

        $productosPromo = collect();

        foreach ($promociones as $promo) {
            $query = Producto::query()
                ->where('activo', true);

            if (! $promo->aplica_todo) {
                $query->where(function ($q) use ($promo) {
                    if ($promo->categoria_id) {
                        $q->orWhere('categoria_id', $promo->categoria_id);
                    }
                    if ($promo->marca_id) {
                        $q->orWhere('marca_id', $promo->marca_id);
                    }
                    if ($promo->modelo_id) {
                        $q->orWhere('modelo_id', $promo->modelo_id);
                    }
                    if (! empty($promo->productos_seleccionados)) {
                        $q->orWhereIn('id', $promo->productos_seleccionados);
                    }
                });
            }

            $productos = $query->with('categoria')->take(12)->get();

            foreach ($productos as $producto) {
                $precioOriginal = $producto->precio ?? 0;
                $precioPromo = $precioOriginal;

                if ($promo->tipo === 'porcentaje' && $promo->valor_descuento) {
                    $precioPromo = $precioOriginal * (1 - $promo->valor_descuento / 100);
                } elseif ($promo->tipo === 'monto' && $promo->valor_descuento) {
                    $precioPromo = max(0, $precioOriginal - $promo->valor_descuento);
                }

                $productosPromo->push((object) [
                    'producto'        => $producto,
                    'promo'           => $promo,
                    'precio_original' => $precioOriginal,
                    'precio_promo'    => $precioPromo,
                ]);
            }
        }

        $this->promosHome = $productosPromo
            ->unique(fn ($item) => $item->producto->id)
            ->take(16);
    }

    public function render()
    {
        $categorias = Categoria::orderBy('nombre')->get();

        $productosFiltrados = Producto::with('categoria')
            ->when($this->categoriaActiva !== 'todas', function ($q) {
                $q->where('categoria_id', $this->categoriaActiva);
            })
            ->where('activo', true)
            ->inRandomOrder()
            ->paginate(8, ['*'], pageName: 'cat_page');

        $productosNuevos = Producto::with('categoria')
            ->where('activo', true)
            ->latest('creado_en')
            ->paginate(8, ['*'], pageName: 'new_page');

        $marcas = Marca::orderBy('nombre')->get();

        $this->cargarPromosHome();

        return view('livewire.tienda.home-productos', [
            'categorias'         => $categorias,
            'productosFiltrados' => $productosFiltrados,
            'productosNuevos'    => $productosNuevos,
            'marcas'             => $marcas,
            'promosHome'         => $this->promosHome,
        ])->layout('layouts.shop');
    }
}
