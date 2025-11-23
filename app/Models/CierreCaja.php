<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CierreCaja extends Model
{
    protected $table = 'cierre_caja'; // ← FORZAMOS EL NOMBRE CORRECTO (plural)

    protected $fillable = [
        'usuario_id',
        'fecha',
        'monto_apertura',
        'total_efectivo',
        'total_qr',
        'total_ventas',
        'cantidad_ventas',
        'reporte_pdf',
        'caja_abierta'
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto_apertura' => 'decimal:2',
        'total_efectivo' => 'decimal:2',
        'total_qr' => 'decimal:2',
        'total_ventas' => 'decimal:2',
        'caja_abierta' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
}