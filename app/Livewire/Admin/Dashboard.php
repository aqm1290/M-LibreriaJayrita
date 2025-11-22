<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Venta;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $ventas = [];
    public $fechas = [];

    public function mount()
    {
        $this->calcularVentasSemanales();
    }

    public function calcularVentasSemanales()
    {
        $hoy = Carbon::today();
        $fechas = [];
        $ventas = [];

        // últimos 7 días
        for ($i = 6; $i >= 0; $i--) {
            $fecha = $hoy->copy()->subDays($i);
            $fechas[] = $fecha->format('d/m');
            $ventasDia = Venta::whereDate('created_at', $fecha)->sum('total');
            $ventas[] = $ventasDia;
        }

        $this->fechas = $fechas;
        $this->ventas = $ventas;
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
