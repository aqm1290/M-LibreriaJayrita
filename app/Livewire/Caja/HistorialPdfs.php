<?php

namespace App\Livewire\Caja; 

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Venta;
use App\Models\TurnoCaja;

class HistorialPdfs extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $tipo = 'tickets'; // tickets | cierres
    public $buscar = '';

    protected $queryString = [
        'tipo' => ['except' => 'tickets'],
        'buscar' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    // Resetear página al cambiar búsqueda
    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function cambiarTipo($tipo)
    {
        $this->tipo = $tipo;
        $this->resetPage();
    }

    public function render()
    {
        $user = auth()->user();

        if ($this->tipo === 'cierres') {
            $query = TurnoCaja::with('usuario')
                ->whereNotNull('reporte_pdf')
                ->where('activo', false);

            // Si NO es admin, solo sus cierres
            if (! method_exists($user, 'esAdmin') || ! $user->esAdmin()) {
                $query->where('usuario_id', $user->id);
            }

            $items = $query
                ->when($this->buscar, function ($q) {
                    $buscar = $this->buscar;

                    $q->where(function ($q2) use ($buscar) {
                        // por fecha de cierre
                        $q2->whereDate('fecha', $buscar)
                           // por nombre de cajero
                           ->orWhereHas('usuario', function ($u) use ($buscar) {
                               $u->where('name', 'like', "%{$buscar}%");
                           });
                    });
                })
                ->orderByDesc('fecha')
                ->paginate(15);
        } else {
            // TICKETS
            $query = Venta::with(['usuario'])
                ->select([
                    'ventas.*',
                    'clientes.nombre as nombre_cliente_real',
                    'ventas.cliente_nombre as nombre_cliente_fallback',
                    'ventas.cliente_documento',
                ])
                ->leftJoin('clientes', 'ventas.cliente_id', '=', 'clientes.id')
                ->whereNotNull('ticket_pdf');

            // Si NO es admin, solo ventas de sus turnos
            if (! method_exists($user, 'esAdmin') || ! $user->esAdmin()) {
                $turnoIds = TurnoCaja::where('usuario_id', $user->id)->pluck('id');
                $query->whereIn('ventas.turno_id', $turnoIds);
                // o: $query->where('ventas.usuario_id', $user->id);
            }

            $items = $query
                ->when($this->buscar, function ($q) {
                    $buscar = $this->buscar;

                    $q->where(function ($q2) use ($buscar) {
                        $q2->where('ventas.id', 'like', "%{$buscar}%")
                           ->orWhere('clientes.nombre', 'like', "%{$buscar}%")
                           ->orWhere('ventas.cliente_nombre', 'like', "%{$buscar}%")
                           ->orWhere('ventas.cliente_documento', 'like', "%{$buscar}%")
                           ->orWhereHas('usuario', function ($u) use ($buscar) {
                               $u->where('name', 'like', "%{$buscar}%");
                           });
                    });
                })
                ->orderByDesc('ventas.created_at')
                ->paginate(15);
        }

        return view('livewire.caja.historial-pdfs', compact('items'))
            ->layout('layouts.app');
    }
}
