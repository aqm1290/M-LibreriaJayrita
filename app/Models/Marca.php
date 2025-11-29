<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;

    public $timestamps = true;
   

    protected $fillable = [
        'nombre',
        'descripcion',
        'url_imagen',
        'activo',
    ];
    protected $casts = [
        'activo' => 'boolean',
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