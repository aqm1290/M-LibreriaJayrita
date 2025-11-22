<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    public $timestamps = false;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'nombre', 'descripcion', 'porcentaje_descuento',
        'fecha_inicio', 'fecha_fin', 'producto_id',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}