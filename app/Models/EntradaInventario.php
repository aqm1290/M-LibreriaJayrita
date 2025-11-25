<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EntradaInventario extends Model
{
    protected $fillable = [
        'proveedor_id',
        'fecha',
        'observacion',
        'total',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function detalles()
    {
        return $this->hasMany(DetalleEntrada::class);
    }
}