<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    use HasFactory;

    protected $table = 'ventas';
    public $timestamps = true;  

    protected $fillable = [
        'usuario_id',
        'total',
        'estado_pago',
        'metodo_pago',
        'descuento_total',
        'ticket_pdf',
        
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleVenta::class, 'venta_id');
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }


    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
