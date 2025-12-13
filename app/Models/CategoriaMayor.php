<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoriaMayor extends Model
{
    

    protected $fillable = [
        'nombre',
        
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function categorias()
    {
        return $this->hasMany(Categoria::class, 'categoria_mayor_id');
    }
}
