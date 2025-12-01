<?php

namespace App\Livewire\Caja;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Venta;
use App\Models\TurnoCaja;

class HistorialPdfs extends Component
{
    use WithPagination;

    public $tipo = 'tickets'; // 'tickets' o 'cierres'
    public $buscar = '';

    protected $queryString = [
        'tipo' => ['except' => 'tickets'],
        'buscar' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount()
    {
        $this->tipo = request()->query('tipo', 'tickets');
    }

    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function cambiarTipo($tipo)
    {
        $this->tipo = $tipo;
        $this->buscar = '';
        $this->resetPage();
    }

    public function render()
    {
        if ($this->tipo === 'cierres') {
            $items = TurnoCaja::with('usuario')
                ->whereNotNull('reporte_pdf')
                ->where('activo', false)
                ->when($this->buscar, function ($q) {
                    $q->whereDate('fecha', 'like', "%{$this->buscar}%")
                      ->orWhereHas('usuario', fn($u) => $u->where('name', 'like', "%{$this->buscar}%"));
                })
                ->orderByDesc('fecha')
                ->paginate(15);
        } else {
            // TICKETS - AHORA CON NOMBRE DEL CLIENTE BIEN MOSTRADO
            $items = Venta::with(['usuario'])
                ->select([
                    'ventas.*',
                    'clientes.nombre as nombre_cliente_real',
                    'ventas.cliente_nombre as nombre_cliente_fallback',
                    'ventas.cliente_documento'
                ])
                ->leftJoin('clientes', 'ventas.cliente_id', '=', 'clientes.id')
                ->whereNotNull('ticket_pdf')
                ->when($this->buscar, function ($q) {
                    $q->where('ventas.id', 'like', "%{$this->buscar}%")
                      ->orWhereDate('ventas.created_at', 'like', "%{$this->buscar}%")
                      ->orWhere('clientes.nombre', 'like', "%{$this->buscar}%")
                      ->orWhere('ventas.cliente_nombre', 'like', "%{$this->buscar}%")
                      ->orWhereHas('usuario', fn($u) => $u->where('name', 'like', "%{$this->buscar}%"));
                })
                ->orderByDesc('ventas.created_at')
                ->paginate(15);
        }

        return view('livewire.caja.historial-pdfs', compact('items'))
            ->layout('layouts.app');
    }
}