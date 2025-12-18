<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Producto extends Model
{
    use HasFactory;

    public $timestamps = false;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'nombre', 'codigo', 'precio', 'costo_compra', 'stock',
        'categoria_id', 'marca_id', 'modelo_id', 'promo_id',
        'color', 'tipo', 'descripcion', 'url_imagen', 'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];


    
    // Accessor URL imagen
    public function getImagenUrlAttribute()
    {
        if (!$this->url_imagen) return null;

        if (Str::startsWith($this->url_imagen, ['http://', 'https://'])) {
            return $this->url_imagen;
        }

        return asset('storage/' . $this->url_imagen);
    }
    
    // Relaciones básicas
    public function detallesEntradas()
    {
        return $this->hasMany(DetalleEntrada::class, 'producto_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class);
    }

    // Relación 1 a N antigua (campo promo_id en productos) -> ahora a Promocion
    public function promo()
    {
        return $this->belongsTo(Promocion::class, 'promo_id');
    }

   
    public function carritoDetalles()
    {
        return $this->hasMany(CarritoDetalle::class);
    }

    public function detalleVentas()
    {
        return $this->hasMany(DetalleVenta::class);
    }

    public function entradasInventario()
    {
        return $this->hasMany(EntradaInventario::class);
    }
}
