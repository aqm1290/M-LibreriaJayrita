<?php

namespace App\Livewire\Cliente;

use Livewire\Component;

class Perfil extends Component
{
    public $nombre;
    public $email;
    public $telefono;
    public $direccion;

    public function mount()
    {
        $user = auth('cliente')->user();
        $this->nombre = $user->nombre;
        $this->email = $user->email;
        $this->telefono = $user->telefono;
        $this->direccion = $user->direccion ?? '';
    }

    public function render()
    {
        return view('livewire.cliente.perfil')->layout('layouts.shop');
    }
}