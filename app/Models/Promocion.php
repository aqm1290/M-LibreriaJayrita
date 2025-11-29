<?php

// app/Models/Promocion.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class Promocion extends Model
{
    protected $table = 'promociones';

    protected $fillable = [
        'nombre', 'codigo', 'descripcion',
        'tipo', 'valor_descuento',
        'producto_2x1_id',
        'producto_compra_id', 'producto_regalo_id',
        'aplica_todo', 'categoria_id',
        'inicia_en', 'termina_en', 'activa',
    ];

    protected $casts = [
        'aplica_todo' => 'boolean',
        'activa'      => 'boolean',
        'inicia_en'   => 'datetime',
        'termina_en'  => 'datetime',
    ];

    // Productos ligados manualmente (promo_producto)
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'promo_producto', 'promo_id', 'producto_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function producto2x1()
    {
        return $this->belongsTo(Producto::class, 'producto_2x1_id');
    }

    public function productoCompra()
    {
        return $this->belongsTo(Producto::class, 'producto_compra_id');
    }

    public function productoRegalo()
    {
        return $this->belongsTo(Producto::class, 'producto_regalo_id');
    }

    public function usos()
    {
        return $this->hasMany(PromocionUso::class, 'promociones_id');
    }

    // Alcance: solo promociones vigentes y activas
    public function scopeVigentes(Builder $q): Builder
    {
        $now = Carbon::now();

        return $q->where('activa', true)
            ->where('inicia_en', '<=', $now)
            ->where(function ($q2) use ($now) {
                $q2->whereNull('termina_en')
                   ->orWhere('termina_en', '>=', $now);
            });
    }
}
