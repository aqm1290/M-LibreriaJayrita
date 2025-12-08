<?php

namespace App\Console\Commands;

use App\Models\Pedido;
use Illuminate\Console\Command;

class CancelarPedidosVencidos extends Command
{
    protected $signature = 'pedidos:cancelar-vencidos';
    protected $description = 'Cancela automáticamente los pedidos reservados que vencieron (48h)';

    public function handle()
    {
        $vencidos = Pedido::where('estado', 'reservado')
                          ->where('expira_en', '<=', now())
                          ->get();

        foreach ($vencidos as $pedido) {
            $pedido->estado = 'cancelado';
            $pedido->save();

            $this->info("Pedido #{$pedido->id} cancelado por vencimiento");
        }

        $this->info("Cancelados: {$vencidos->count()} pedidos");
    }
}