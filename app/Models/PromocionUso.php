<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromocionUso extends Model
{
    protected $table = 'promocion_usos';

    protected $fillable = [
        'promociones_id', 'user_id', 'venta_id',
    ];

    public function promocion()
    {
        return $this->belongsTo(Promocion::class, 'promociones_id');
    }
}
