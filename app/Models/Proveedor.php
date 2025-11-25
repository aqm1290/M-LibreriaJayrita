<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'direccion',
        'nit',
        'empresa',
        'contacto_nombre',
        'contacto_telefono',
        'estado',
    ];

    protected $casts = [
        'estado' => 'string',
    ];

    public function entradasInventario()
    {
        return $this->hasMany(EntradaInventario::class);
    }
}