<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cajero extends Model
{
    protected $table = 'cajeros';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'horario',
        'turno',
        'telefono',
        'activo',   // <- para poder usar activar/desactivar
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
