<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromocionUso extends Model
{
    protected $table = 'promocion_usos';

    protected $fillable = [
        'promocion_id',
        'cliente_id',
        'venta_id',
        'ip_address',
        'usado_en',
    ];

    protected $casts = [
        'usado_en' => 'datetime',
    ];

    public function promocion(): BelongsTo
    {
        return $this->belongsTo(Promocion::class, 'promocion_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function venta(): BelongsTo
    {
        return $this->belongsTo(Venta::class);
    }
}