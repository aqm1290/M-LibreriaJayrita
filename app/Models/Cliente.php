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

    // Relación con fidelidad
    public function fidelidad()
    {
        return $this->hasOne(FidelidadCliente::class, 'cliente_id');
    }

    // Saber si tiene premio pendiente
    public function tienePremioPendiente()
    {
        return $this->fidelidad && 
               $this->fidelidad->compras_realizadas >= 10 && 
               !$this->fidelidad->premio_entregado;
    }

    // Obtener progreso (ej: 7/10)
    public function progresoFidelidad()
    {
        $compras = $this->fidelidad?->compras_realizadas ?? 0;
        return "$compras de 10 compras";
    }
    
    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'cliente_id');
    }


}