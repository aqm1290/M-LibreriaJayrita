<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetalleEntrada extends Model
{
    protected $fillable = [
        'entrada_inventario_id',
        'producto_id',
        'cantidad',
        'costo',
        'subtotal',
    ];

    public function entrada()
    {
        return $this->belongsTo(EntradaInventario::class, 'entrada_inventario_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}