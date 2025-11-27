<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vendedor extends Model
{
    protected $table = 'vendedores';

    protected $fillable = [
        'usuario_id', 'codigo_vendedor', 'telefono', 'activo'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
