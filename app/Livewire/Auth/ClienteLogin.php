<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ClienteLogin extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function ingresar(): void
    {
        $this->validate();

        if (! Auth::guard('cliente')->attempt(
            ['email' => $this->email, 'password' => $this->password],
            $this->remember
        )) {
            $this->addError('email', 'Estas credenciales no coinciden con nuestros registros.');
            return;
        }

        session()->regenerate();

        $this->redirect('/tienda');
    }

    public function salir(): void
    {
        Auth::guard('cliente')->logout();
        session()->invalidate();
        session()->regenerateToken();

        $this->redirectRoute('cliente.login');
    }

    public function render()
    {
        return view('livewire.auth.cliente-login')
            ->layout('layouts.shop');
    }
}
