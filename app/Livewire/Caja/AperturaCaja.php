<?php

namespace App\Livewire\Caja;

use Livewire\Component;
use App\Models\CierreCaja;

class AperturaCaja extends Component
{
    public $monto = 0;
    public $cajaAbierta = false;
    public $montoApertura = 0;

        // app/Livewire/Caja/AperturaCaja.php
    public function mount()
    {
        $this->redirectIfAlreadyOpen();
    }

    public function redirectIfAlreadyOpen()
    {
        if (CierreCaja::cajaAbiertaHoy()) {
            return redirect()->route('caja.pos');
        }
    }

    public function abrirCaja()
    {
        $this->validate([
            'monto' => 'required|numeric|min:0'
        ]);

        CierreCaja::updateOrCreate(
            ['fecha' => today()],
            [
                'usuario_id' => auth()->id(),
                'monto_apertura' => $this->monto,
                'caja_abierta' => true
            ]
        );

        $this->dispatch('toast', '¡Caja abierta con éxito! Puedes empezar a vender.');
        return redirect()->route('caja.pos');
    }
    public function render()
    {
        return view('livewire.caja.apertura-caja')
            ->layout('layouts.pos');
    }
}