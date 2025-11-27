<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\Cajero;
use App\Models\Vendedor;
use Illuminate\Support\Facades\Hash;

class CrearPersonal extends Component
{
    public $name, $email, $password, $telefono = '';
    public $tipo = 'cajero'; // cajero o vendedor
    public $horario = '', $turno = 'mañana';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'telefono' => 'nullable|string|max:15',
        'tipo' => 'required|in:cajero,vendedor',
        'horario' => 'required_if:tipo,cajero',
        'turno' => 'required_if:tipo,cajero|in:mañana,tarde,noche',
    ];

    public function crear()
    {
        $this->validate();

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'rol' => $this->tipo === 'cajero' ? 'cajero' : 'vendedor',
        ]);

        if ($this->tipo === 'cajero') {
            Cajero::create([
                'usuario_id' => $user->id,
                'horario' => $this->horario,
                'turno' => $this->turno,
                'telefono' => $this->telefono,
            ]);
        } else {
            Vendedor::create([
                'usuario_id' => $user->id,
                'codigo_vendedor' => 'VEND-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'telefono' => $this->telefono,
            ]);
        }

        $this->reset();
        session()->flash('success', '¡Personal creado con éxito!');
    }

    public function render()
    {
        return view('livewire.admin.crear-personal')
            ->layout('layouts.app');
    }
}