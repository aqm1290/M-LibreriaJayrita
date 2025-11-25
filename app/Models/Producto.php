<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    public $timestamps = false;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'stock','costo_compra', 'url_imagen', 'color', 'tipo', 'categoria_id',
        'modelo_id', 'marca_id', 'promo_id', 'codigo',
    ];

    
    public function detallesEntradas(){return $this->hasMany(DetalleEntrada::class, 'producto_id');}
    public function categoria() { return $this->belongsTo(Categoria::class); }
    public function marca()     { return $this->belongsTo(Marca::class); }
    public function modelo()    { return $this->belongsTo(Modelo::class); }
    public function promo()     { return $this->belongsTo(Promo::class); }

    public function promos()           { return $this->hasMany(Promo::class); }
    public function carritoDetalles()   { return $this->hasMany(CarritoDetalle::class); }
    public function detalleVentas()     { return $this->hasMany(DetalleVenta::class); }
    public function entradasInventario() { return $this->hasMany(EntradaInventario::class); }
}