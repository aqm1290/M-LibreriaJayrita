<?php

namespace App\Services;

use App\Models\Producto;
use App\Models\Promocion;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PromocionService
{
    /**
     * 1. Buscar promociones vigentes que aplican a un producto.
     */
    public function promocionesParaProducto(Producto $producto): Collection
    {
        $now = Carbon::now();

        return Promocion::where('activa', true)
            ->where('inicia_en', '<=', $now)
            ->where(function ($q) use ($now) {
                $q->whereNull('termina_en')
                  ->orWhere('termina_en', '>=', $now);
            })
            ->where(function ($q) use ($producto) {
                $q->whereHas('productos', fn($p) => $p->where('productos.id', $producto->id))
                  ->orWhere(function ($q2) use ($producto) {
                      $q2->where('aplica_todo', false)
                         ->whereNotNull('categoria_id')
                         ->where('categoria_id', $producto->categoria_id);
                  })
                  ->orWhere('aplica_todo', true)
                  ->orWhere(function ($q3) use ($producto) {
                      $q3->where('tipo', '2x1')
                         ->whereJsonContains('products_2x1', $producto->id);
                  })
                  ->orWhere(function ($q4) use ($producto) {
                      $q4->where('tipo', 'compra_lleva')
                         ->where(function ($q5) use ($producto) {
                             $q5->whereJsonContains('products_compra', $producto->id)
                                ->orWhereJsonContains('products_regalo', $producto->id);
                         });
                  });
            })
            ->get();
    }

    /**
     * 2. Escoger la MEJOR promoción (la que más descuenta)
     *    y devolver precio final, total y ahorro.
     */
    public function aplicarMejorPromocion(Producto $producto, int $cantidad): array
    {
        $promos = $this->promocionesParaProducto($producto);

        $totalBase = $producto->precio * $cantidad;
        if ($promos->isEmpty()) {
            return [
                'precio_unitario' => $producto->precio,
                'total'           => $totalBase,
                'ahorro'          => 0,
                'promo'           => null,
            ];
        }

        $mejorPromo  = null;
        $mejorTotal  = $totalBase;
        $mejorAhorro = 0;

        foreach ($promos as $promo) {
            [$totalConPromo, $ahorro] = $this->calcularTotalConPromo($promo, $producto, $cantidad);

            if ($totalConPromo < $mejorTotal) {
                $mejorTotal  = $totalConPromo;
                $mejorAhorro = $ahorro;
                $mejorPromo  = $promo;
            }
        }

        if (!$mejorPromo) {
            return [
                'precio_unitario' => $producto->precio,
                'total'           => $totalBase,
                'ahorro'          => 0,
                'promo'           => null,
            ];
        }

        return [
            'precio_unitario' => $mejorTotal / max($cantidad, 1),
            'total'           => $mejorTotal,
            'ahorro'          => $mejorAhorro,
            'promo'           => $mejorPromo,
        ];
    }

    /**
     * 3. Lógica por tipo de promoción (según tu migración).
     */
    public function calcularTotalConPromo(Promocion $promo, Producto $producto, int $cantidad): array
    {
        $precio    = $producto->precio;
        $totalBase = $precio * $cantidad;

        switch ($promo->tipo) {
            case 'descuento_porcentaje':
                // valor_descuento = porcentaje (ej. 20 -> 20%)
                $descuento = $totalBase * ($promo->valor_descuento / 100);
                return [$totalBase - $descuento, $descuento];

            case 'descuento_monto':
                // valor_descuento = monto fijo total
                $descuento = min($promo->valor_descuento, $totalBase);
                return [$totalBase - $descuento, $descuento];

            case '2x1':
                // Aplica solo si el producto está en el array
                if (!in_array($producto->id, $promo->products_2x1 ?? [])) {
                    return [$totalBase, 0];
                }
                $pares  = intdiv($cantidad, 2);
                $resto  = $cantidad % 2;
                // Pagas solo un producto por cada par
                $total  = ($pares * $precio) + ($resto * $precio);
                $ahorro = $totalBase - $total;
                return [$total, $ahorro];

            case 'compra_lleva':
                // Aquí de momento no descontamos nada en precio,
                // el producto "regalo" se maneja en el carrito.
                return [$totalBase, 0];

            default:
                return [$totalBase, 0];
        }
    }
}