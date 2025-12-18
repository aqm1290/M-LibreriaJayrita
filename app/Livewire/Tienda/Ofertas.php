<?php

namespace App\Livewire\Tienda;

use App\Models\Producto;
use App\Models\Promocion;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class Ofertas extends Component
{
    use WithPagination;

    public string $orden = 'descuento_desc'; // descuento_desc, precio_asc, precio_desc, nombre_asc

    public function updatingOrden(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $hoy = Carbon::now();

        // 1) Promociones activas, igual que en HomeProductos
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
            $query = Producto::query()->where('activo', true);

            if (!$promo->aplica_todo) {
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
                    if (!empty($promo->productos_seleccionados)) {
                        $q->orWhereIn('id', $promo->productos_seleccionados);
                    }
                });
            }

            $productos = $query->with(['categoria', 'marca', 'modelo'])->get();

            foreach ($productos as $producto) {
                $precioOriginal = $producto->precio ?? 0;
                $precioPromo = $precioOriginal;

                if ($promo->tipo === 'porcentaje' && $promo->valor_descuento) {
                    $precioPromo = $precioOriginal * (1 - $promo->valor_descuento / 100);
                } elseif ($promo->tipo === 'monto' && $promo->valor_descuento) {
                    $precioPromo = max(0, $precioOriginal - $promo->valor_descuento);
                }

                $descuentoMonto = $precioOriginal - $precioPromo;
                $descuentoPorc = $precioOriginal > 0 ? round(($descuentoMonto / $precioOriginal) * 100) : 0;

                $productosPromo->push([
                    'modelo' => $producto,
                    'promocion' => $promo,
                    'precio_original' => $precioOriginal,
                    'precio_oferta' => $precioPromo,
                    'descuento_monto' => $descuentoMonto,
                    'descuento_porc' => $descuentoPorc,
                ]);
            }
        }

        // Eliminar duplicados por producto
        $productosBase = $productosPromo->unique(fn($item) => $item['modelo']->id)->values();

        // Si no hay nada, devolvemos paginator vacío
        if ($productosBase->isEmpty()) {
            $paginadorVacio = new LengthAwarePaginator([], 0, 12, 1, [
                'path' => request()->url(),
                'query' => request()->query(),
            ]);

            return view('livewire.tienda.ofertas', [
                'productos' => $paginadorVacio,
                'enStock' => 0,
                'agotados' => 0,
                'total' => 0,
            ])->layout('layouts.shop');
        }

        // Orden
        $productosOrdenados = $productosBase
            ->sortBy(function ($item) {
                return match ($this->orden) {
                    'precio_asc' => $item['precio_oferta'],
                    'precio_desc' => -$item['precio_oferta'],
                    'nombre_asc' => $item['modelo']->nombre,
                    default => -$item['descuento_porc'],
                };
            })
            ->values();

        // Contadores stock
        $enStock = $productosOrdenados->filter(fn($i) => ($i['modelo']->stock ?? 0) > 0)->count();
        $agotados = $productosOrdenados->filter(fn($i) => ($i['modelo']->stock ?? 0) <= 0)->count();
        $total = $productosOrdenados->count();

        // Paginación manual
        $perPage = 12;
        $page = $this->page ?? 1;

        $slice = $productosOrdenados->slice(($page - 1) * $perPage, $perPage)->values();

        $paginador = new LengthAwarePaginator($slice, $productosOrdenados->count(), $perPage, $page, ['path' => request()->url(), 'query' => request()->query()]);

        return view('livewire.tienda.ofertas', [
            'productos' => $paginador,
            'enStock' => $enStock,
            'agotados' => $agotados,
            'total' => $total,
        ])->layout('layouts.shop');
    }
}