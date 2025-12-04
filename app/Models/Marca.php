<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;   // ← ASÍ SÍ

class Marca extends Model
{
    use HasFactory;

    public $timestamps = true;
   

    protected $fillable = [
        'nombre',
        'descripcion',
        'url_imagen',
        'activo',
    ];
    protected $casts = [
        'activo' => 'boolean',
    ];
    public function getLogoUrlAttribute()
    {
        if (!$this->url_imagen) return null;

        if (Str::startsWith($this->url_imagen, ['http://', 'https://'])) {
            return $this->url_imagen;
        }

        return asset('storage/' . $this->url_imagen);
    }

    public function modelos()
    {
        return $this->hasMany(Modelo::class, 'marca_id');
    }

    public function productos()
    {
        return $this->hasMany(Producto::class, 'marca_id');
    }
}