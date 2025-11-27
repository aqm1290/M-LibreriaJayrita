<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Promo extends Model
{
    protected $table = 'promociones'; 
    protected $casts = [
        'activa' => 'boolean',
        'aplica_todo' => 'boolean',
        'inicia_en' => 'datetime',
        'termina_en' => 'datetime',
    ];

    protected $fillable = [
        'nombre','codigo','tipo','valor_descuento','producto_2x1_id',
        'producto_compra_id','producto_regalo_id','aplica_todo','categoria_id',
        'inicia_en','termina_en','activa','descripcion','descripcion',
    ];

    // Scope para promos activas (por fecha y flag)
    public function scopeActivas(Builder $q)
    {
        $now = now();

        return $q->where('activa', true)
                 ->where('inicia_en', '<=', $now)
                 ->where(function ($w) use ($now) {
                     $w->whereNull('termina_en')
                       ->orWhere('termina_en', '>=', $now);
                 });
    }

    // Accessor booleano para chequear si está activa ahora
    public function getEstaActivaAttribute()
    {
        $now = now();

        return $this->activa
            && $this->inicia_en
            && $this->inicia_en->lte($now)
            && (is_null($this->termina_en) || $this->termina_en->gte($now));
    }
    public function productos()
    {
        return $this->belongsToMany(Producto::class, 'promo_producto', 'promo_id', 'producto_id');
    }
}
