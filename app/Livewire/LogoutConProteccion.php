<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\AperturaCaja;

class LogoutConProteccion extends Component
{
    public function intentarCerrarSesion()
    {
        $user = auth()->user();

        // Solo aplicamos la regla si es cajero
        if ($user->esCajero()) {
            $hoy = Carbon::today()->toDateString();

            // Verificar si tiene caja abierta
            $cajaAbierta = DB::table('aperturas_caja') // <- tu tabla de caja
                ->where('usuario_id', $user->id)
                ->whereDate('fecha_apertura', $hoy)
                ->whereNull('fecha_cierre')
                ->exists();

            if ($cajaAbierta) {
                $this->dispatch('alerta-caja-abierta');
                return; // No cerramos sesión
            }
        }

        // Si no tiene caja abierta o no es cajero, cierra sesión
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
