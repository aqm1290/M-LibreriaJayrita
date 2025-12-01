<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnoCaja extends Model
{
    use HasFactory;

    protected $table = 'turnos_caja';

    protected $fillable = [
        'usuario_id',
        'fecha',
        'hora_apertura',
        'monto_apertura',
        'activo',
        'hora_cierre',
        'monto_fisico_cierre',
        'total_ventas',
        'total_efectivo',
        'total_qr',
        'diferencia',
        'observaciones',
        'reporte_pdf',
        'cantidad_ventas',
    ];

    // Relación usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación ventas
    public function ventas()
    {
        return $this->hasMany(Venta::class, 'turno_id');
    }

    // Obtener turno activo del usuario
    public static function activoActual()
    {
        return self::where('usuario_id', auth()->id())
            ->where('activo', true)
            ->first();
    }
}
