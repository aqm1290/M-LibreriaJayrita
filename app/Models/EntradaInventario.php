<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradaInventario extends Model
{
    protected $table = 'entradas_inventario';
    use HasFactory;

    protected $fillable = [
        'proveedor_id', 'producto_id', 'cantidad',
        'precio_compra', 'fecha',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}