<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FidelidadCliente extends Model
{
    protected $table = 'fidelidad_clientes';
    protected $fillable = ['cliente_id', 'compras_realizadas', 'premio_entregado', 'ultima_compra'];

    protected $casts = [
        'premio_entregado' => 'boolean',
        'ultima_compra' => 'date',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}