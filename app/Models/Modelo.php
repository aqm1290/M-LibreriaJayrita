<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    public $timestamps = true;
    

    protected $fillable = [
        'marca_id',
        'nombre',
        'descripcion',
        'activo',
    ];
    protected $casts = [
        'activo' => 'boolean',
    ];

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'modelo_id');
    }
}