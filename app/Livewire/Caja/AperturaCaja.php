<?php

namespace App\Livewire\Caja;

use Livewire\Component;
use App\Models\CierreCaja;

class AperturaCaja extends Component
{
    public $monto = 0;
    public $cajaAbierta = false;
    public $montoApertura = 0;

    public function mount()
    {
        $hoy = today()->toDateString();
        $cierre = CierreCaja::where('fecha', $hoy)->first();

        if ($cierre && $cierre->caja_abierta) {
            $this->cajaAbierta = true;
            $this->montoApertura = $cierre->monto_apertura;
        }
    }

    public function abrirCaja()
    {
        $this->validate([
            'monto' => 'required|numeric|min:0'
        ]);

        $hoy = today()->toDateString();

        CierreCaja::updateOrCreate(
            ['fecha' => $hoy],
            [
                'usuario_id' => auth()->check() ? auth()->id() : 1,
                'monto_apertura' => $this->monto,
                'caja_abierta' => true
            ]
        );

        $this->cajaAbierta = true;
        $this->montoApertura = $this->monto;

        $this->dispatch('toast', '¡Caja abierta con éxito! Bs ' . number_format($this->monto, 2));
    }

    public function render()
    {
        return view('livewire.caja.apertura-caja')
            ->layout('layouts.pos');
    }
}