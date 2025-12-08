<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'cliente_id',
        'cliente_nombre',
        'cliente_telefono',
        'cliente_email',
        'notas',
        'estado',
        'total',
        'expira_en',
        'entregado_en',
    ];

    protected $casts = [
        'expira_en'    => 'datetime',
        'entregado_en' => 'datetime',
        'total'        => 'decimal:2',
    ];

    // =================================================================
    // RELACIONES
    // =================================================================

    // Relación con ClienteWeb (tabla clientes_web)
    public function clienteWeb()
    {
        return $this->belongsTo(ClienteWeb::class, 'cliente_id');
    }

    // Items del pedido
    public function items()
    {
        return $this->hasMany(PedidoItem::class, 'pedido_id');
    }

    // =================================================================
    // ACCESSORS BONITOS (para tu vista)
    // =================================================================

    public function getEstadoTextoAttribute()
    {
        return match($this->estado) {
            'borrador'    => 'Borrador',
            'reservado'   => 'Reservado',
            'confirmado'  => 'Confirmado',
            'entregado'   => 'Entregado',
            'cancelado'   => 'Cancelado',
            default       => 'Desconocido',
        };
    }

    public function getEstadoColorAttribute()
    {
        return match($this->estado) {
            'borrador'    => 'gray',
            'reservado'   => 'yellow',
            'confirmado'  => 'blue',
            'entregado'   => 'green',
            'cancelado'   => 'red',
            default       => 'gray',
        };
    }

    public function getEstadoBadgeClassAttribute()
    {
        return match($this->estado) {
            'borrador'    => 'bg-gray-600 text-white',
            'reservado'   => 'bg-yellow-500 text-black',
            'confirmado'  => 'bg-blue-600 text-white',
            'entregado'   => 'bg-emerald-600 text-white',
            'cancelado'   => 'bg-red-600 text-white',
            default       => 'bg-gray-600 text-white',
        };
    }

    // =================================================================
    // SCOPES ÚTILES
    // =================================================================

    public function scopeReservados($query)
    {
        return $query->where('estado', 'reservado');
    }

    public function scopeConfirmados($query)
    {
        return $query->where('estado', 'confirmado');
    }

    public function scopeEntregados($query)
    {
        return $query->where('estado', 'entregado');
    }

    public function scopeVigentes($query)
    {
        return $query->whereIn('estado', ['reservado', 'confirmado']);
    }
}