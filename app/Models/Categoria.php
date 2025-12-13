<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    public $timestamps = true;
   

    protected $fillable = [
         'categoria_mayor_id', 
        'nombre',
        'descripcion',
        'activo',
    ];
    protected $casts = [
        'activo' => 'boolean',
    ];
    public function categoriaMayor()
    {
        return $this->belongsTo(CategoriaMayor::class, 'categoria_mayor_id');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }
}