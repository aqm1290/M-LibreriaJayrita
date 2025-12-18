<?php

namespace App\Livewire\Tienda;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Marca;

class ListaMarcas extends Component
{
    use WithPagination;

    public int $porPagina = 24;

    public function render()
    {
        $marcas = Marca::orderBy('nombre')
            ->paginate($this->porPagina);

        return view('livewire.tienda.lista-marcas', [
            'marcas' => $marcas,
        ])->layout('layouts.shop');
    }
}