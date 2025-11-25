<?php

namespace App\Observers;

use App\Models\DetalleEntrada;

class DetalleEntradaObserver
{
    public function created(DetalleEntrada $detalleEntrada)
    {
        $producto = $detalleEntrada->producto;
        if ($producto) {
            $producto->stock += $detalleEntrada->cantidad;
            $producto->save();
        }
    }
}