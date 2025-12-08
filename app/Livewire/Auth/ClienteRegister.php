<?php

namespace App\Livewire\Auth;

use App\Models\ClienteWeb;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ClienteRegister extends Component
{
    public string $nombre = '';
    public string $email = '';
    public string $telefono = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected function rules(): array
    {
        return [
            'nombre'                  => ['required', 'string', 'max:255'],
            'email'                   => ['required', 'email', 'max:255', 'unique:clientes_web,email'],
            'telefono'                => ['nullable', 'string', 'max:30'],
            'password'                => ['required', 'string', 'min:6', 'confirmed'],
            'password_confirmation'   => ['required', 'string', 'min:6'],
        ];
    }

    public function registrar(): void
    {
        $datos = $this->validate();

        $cliente = ClienteWeb::create([
            'nombre'   => $datos['nombre'],
            'email'    => $datos['email'],
            'telefono' => $datos['telefono'],
            'password' => Hash::make($datos['password']),
        ]);

        Auth::guard('cliente')->login($cliente);
        $this->redirect('/tienda');
    }

    public function render()
    {
        return view('livewire.auth.cliente-register')
            ->layout('layouts.shop');
    }
}
