<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['nombre', 'ci', 'telefono', 'direccion', 'correo'];

    public function ventas()
    {
        return $this->hasMany(Venta::class);
    }

    public function getNombreCompletoAttribute()
    {
        return $this->ci ? "{$this->nombre} (CI: {$this->ci})" : $this->nombre;
    }
}