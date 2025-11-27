<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogoutConProteccion extends Component
{
    public function intentarCerrarSesion()
    {
        // Solo aplicamos la regla si es cajero o admin
        if (!auth()->user()->esCajero() && !auth()->user()->esAdmin()) {
            $this->logout();
            return;
        }

        // Verificamos si este usuario tiene caja abierta hoy
        $hoy = Carbon::today()->toDateString();

        $cajaAbierta = DB::table('aperturas_caja')
            ->where('usuario_id', auth()->id())
            ->whereDate('fecha_apertura', $hoy)
            ->whereNull('fecha_cierre')
            ->exists();

        if ($cajaAbierta) {
            $this->dispatch('mostrar-alerta-caja-abierta');
            return;
        }

        // Si no tiene caja abierta → cierra sesión normal
        $this->logout();
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');
    }

    public function render()
    {
        return view('livewire.logout-con-proteccion');
    }
}