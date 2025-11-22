<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    public $timestamps = false;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function modelos()
    {
        return $this->hasMany(Modelo::class, 'marca_id');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'marca_id');
    }
}