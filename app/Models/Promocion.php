<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Promocion extends Model
{
    protected $table = 'promociones';

    protected $fillable = [
        'nombre', 'codigo', 'descripcion', 'tipo', 'valor_descuento', 'limite_usos',
        'products_2x1', 'products_compra', 'products_regalo', 'productos_seleccionados',
        'aplica_todo', 'categoria_id', 'marca_id', 'modelo_id',
        'inicia_en', 'termina_en', 'activa',
    ];

    protected $casts = [
        'products_2x1'            => 'array',
        'products_compra'         => 'array',
        'products_regalo'         => 'array',
        'productos_seleccionados' => 'array',
        'aplica_todo'             => 'boolean',
        'activa'                  => 'boolean',
        'inicia_en'               => 'datetime',
        'termina_en'              => 'datetime',
        'limite_usos'             => 'integer',
    ];

    // ===================================================================
    // RELACIONES
    // ===================================================================

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    public function modelo(): BelongsTo
    {
        return $this->belongsTo(Modelo::class);
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'promo_producto', 'promo_id', 'producto_id');
    }

    public function usos(): HasMany
    {
        return $this->hasMany(PromocionUso::class, 'promocion_id');
    }

    // ===================================================================
    // ACCESORS
    // ===================================================================

    public function getProducts2x1Attribute()
    {
        return Producto::whereIn('id', $this->products_2x1 ?? [])->get();
    }

    public function getProductsCompraAttribute()
    {
        return Producto::whereIn('id', $this->products_compra ?? [])->get();
    }

    public function getProductsRegaloAttribute()
    {
        return Producto::whereIn('id', $this->products_regalo ?? [])->get();
    }

    public function getProductosSeleccionadosAttribute()
    {
        return Producto::whereIn('id', $this->productos_seleccionados ?? [])->get();
    }

    // ===================================================================
    // MÉTODOS DE USOS POR CLIENTE
    // ===================================================================

    public function usosPorCliente($clienteId): int
    {
        return $this->usos()->where('cliente_id', $clienteId)->count();
    }

    public function clienteAlcanzoLimite($clienteId): bool
    {
        if (is_null($this->limite_usos)) {
            return false; // ilimitado
        }

        return $this->usosPorCliente($clienteId) >= $this->limite_usos;
    }

    public function puedeSerUsadaPorCliente($clienteId): bool
    {
        return !$this->clienteAlcanzoLimite($clienteId);
    }

    // ===================================================================
    // SCOPES
    // ===================================================================

    public function scopeActivas($query)
    {
        return $query->where('activa', true)
                     ->where('inicia_en', '<=', now())
                     ->where(function ($q) {
                         $q->whereNull('termina_en')
                           ->orWhere('termina_en', '>=', now());
                     });
    }

    public function scopeVigentes($query)
    {
        return $query->where('inicia_en', '<=', now())
                     ->where(function ($q) {
                         $q->whereNull('termina_en')
                           ->orWhere('termina_en', '>=', now());
                     });
    }

    // ===================================================================
    // HELPERS
    // ===================================================================

    public function aplicaAProducto(Producto $producto): bool
    {
        if ($this->aplica_todo) return true;

        if ($this->categoria_id && $producto->categoria_id == $this->categoria_id) return true;
        if ($this->marca_id && $producto->marca_id == $this->marca_id) return true;
        if ($this->modelo_id && $producto->modelo_id == $this->modelo_id) return true;
        if (in_array($producto->id, $this->productos_seleccionados ?? [])) return true;

        return false;
    }

    public function generarDescripcion(): string
    {
        $ambito = $this->aplica_todo ? 'Toda la tienda'
            : ($this->categoria?->nombre
                ?? $this->marca?->nombre
                ?? $this->modelo?->nombre
                ?? (count($this->productos_seleccionados ?? []) . ' producto(s)'));

        return match ($this->tipo) {
            'descuento_porcentaje' => "{$this->valor_descuento}% OFF en {$ambito}",
            'descuento_monto'      => "Bs " . number_format($this->valor_descuento, 2) . " OFF en {$ambito}",
            '2x1'                  => "2x1 en {$ambito}",
            'compra_lleva'         => "Compra y lleva gratis en {$ambito}",
            default                => $this->nombre,
        };
    }
}