<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cajero extends Model
{
    use HasFactory;

    public $timestamps = false;
    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'usuario_id', 'horario', 'turno', 'telefono',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}