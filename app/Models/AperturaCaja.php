<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AperturaCaja extends Model
{
    use HasFactory;

    protected $table = 'aperturas_caja';

    protected $fillable = [
        'usuario_id',
        'fecha_apertura',
        'fecha_cierre',
        'monto_inicial',
        'monto_final',
    ];

    protected $dates = [
        'fecha_apertura',
        'fecha_cierre',
        'created_at',
        'updated_at',
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Método de ayuda para saber si la caja sigue abierta
    public function estaAbierta(): bool
    {
        return is_null($this->fecha_cierre);
    }
}
