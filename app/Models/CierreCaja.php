<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CierreCaja extends Model
{
   protected $table = 'cierre_cajas';
   protected $primaryKey = 'id';
   public $incrementing = true;
    protected $keyType = 'int';
    
    protected $fillable = [
        'fecha', 'usuario_id', 'monto_apertura', 'total_efectivo',
        'total_qr', 'total_ventas', 'cantidad_ventas', 'monto_cierre_fisico',
        'diferencia', 'observaciones', 'reporte_pdf', 'caja_abierta'
    ];

    public function usuario() { return $this->belongsTo(User::class); }

    public static function cajaAbiertaHoy()
    {
        return static::where('fecha', today())->where('caja_abierta', true)->first();
    }

    public static function hoy()
    {
        return static::firstOrCreate(
            ['fecha' => today()],
            ['usuario_id' => auth()->id() ?? 1, 'monto_apertura' => 0]
        );
    }
}