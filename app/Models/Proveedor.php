<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    public $timestamps = false;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'nombre', 'correo', 'telefono', 'direccion', 'activo',
        'nit', 'empresa', 'estado',
    ];

    public function entradasInventario()
    {
        return $this->hasMany(EntradaInventario::class);
    }
}