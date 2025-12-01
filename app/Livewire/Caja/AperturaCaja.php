<?php

namespace App\Livewire\Caja;

use Livewire\Component;
use App\Models\TurnoCaja;

class AperturaCaja extends Component
{
    public $monto = 0;

    public function mount()
    {
        // Si ya hay un turno activo para este usuario, ir directo al POS
        $turno = TurnoCaja::where('usuario_id', auth()->id())
            ->where('activo', true)
            ->first();

        if ($turno) {
            session(['turno_activo_id' => $turno->id]);
            return redirect()->route('caja.pos');
        }
    }

    public function abrirCaja()
    {
        $this->validate([
            'monto' => 'required|numeric|min:0',
        ]);

        $turno = TurnoCaja::create([
            'usuario_id'      => auth()->id(),
            'fecha'           => date('Y-m-d'),
            'hora_apertura'   => date('H:i:s'),
            'monto_apertura'  => $this->monto,
            'activo'          => true,
            'total_ventas'    => 0,
            'total_efectivo'  => 0,
            'total_qr'        => 0,
            'diferencia'      => 0,
            'cantidad_ventas' => 0,
        ]);

        session(['turno_activo_id' => $turno->id]);

        $this->dispatch('swal', [
            'title' => 'Caja abierta',
            'text'  => 'El turno se inició correctamente.',
            'icon'  => 'success',
        ]);

        return redirect()->route('caja.pos');
    }

    public function render()
    {
        return view('livewire.caja.apertura-caja')
            ->layout('layouts.pos');
    }
}
