<?php
namespace App\Livewire\Cliente;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pedido;

class MisPedidos extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $pedidos = Pedido::where('cliente_id', auth('cliente')->id())
            ->latest()
            ->paginate(5);

        return view('livewire.cliente.mis-pedidos', compact('pedidos'));
    }
}
