<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    // RELACIONES
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

    public function usos(): HasMany
    {
        return $this->hasMany(PromocionUso::class, 'promocion_id');
    }

    // Accessor SOLO para mostrar nombres en la tabla
    public function getProductosSeleccionadosModelsAttribute()
    {
        $ids = $this->productos_seleccionados ?? [];

        if (empty($ids)) {
            return collect();
        }

        return Producto::whereIn('id', $ids)->get();
    }

    // HELPERS

    public function aplicaAProducto(Producto $producto): bool
    {
        if ($this->aplica_todo) return true;

        if ($this->categoria_id && $producto->categoria_id == $this->categoria_id) return true;
        if ($this->marca_id && $producto->marca_id == $this->marca_id) return true;
        if ($this->modelo_id && $producto->modelo_id == $this->modelo_id) return true;
        if (in_array($producto->id, $this->productos_seleccionados ?? [])) return true;

        return false;
    }
}
