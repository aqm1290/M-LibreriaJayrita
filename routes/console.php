<?php

use Illuminate\Support\Facades\Schedule;

// CITA INSPIRADORA (la dejamos porque mola)
Artisan::command('inspire', function () {
    $this->comment(\Illuminate\Foundation\Inspiring::quote());
})->purpose('Display an inspiring quote');

// CANCELAR PEDIDOS VENCIDOS CADA HORA
Schedule::command('pedidos:cancelar-vencidos')
        ->hourly()
        ->onSuccess(function () {
            \Log::info('Pedidos vencidos cancelados automáticamente');
        })
        ->description('Cancela pedidos reservados que llevan más de 48 horas');