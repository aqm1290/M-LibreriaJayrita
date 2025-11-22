<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CierreCaja extends Model
{
    protected $table = 'cierres_caja'; // importante si el nombre es plural

    protected $fillable = [
    'usuario_id', 'fecha', 'monto_apertura', 'caja_abierta',
    'total_efectivo', 'total_qr', 'total_ventas', 'cantidad_ventas', 'reporte_pdf'
];

    protected $casts = [
        'fecha' => 'date',
        'total_efectivo' => 'decimal:2',
        'total_qr' => 'decimal:2',
        'total_ventas' => 'decimal:2',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}