<?php
namespace App\Livewire\Tienda;

use Livewire\Component;
use App\Models\Contacto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactoDesdeWeb;

class ContactoForm extends Component
{
    public $nombre, $email, $telefono, $mensaje;

    protected $rules = [
        'nombre'   => 'required|min:3',
        'email'    => 'required|email',
        'telefono' => 'nullable',
        'mensaje'  => 'required|min:5',
    ];

    public function mount()
    {
        // Prefill con el ClienteWeb logueado (guard "cliente")
        $cliente = Auth::guard('cliente')->user();

        if ($cliente) {
            $this->nombre = $cliente->nombre;
            $this->email  = $cliente->email;
            $this->telefono = $cliente->telefono;
        }
    }

    public function enviar()
    {
        $this->validate();

        $contacto = Contacto::create([
            'nombre'   => $this->nombre,
            'email'    => $this->email,
            'telefono' => $this->telefono,
            'mensaje'  => $this->mensaje,
        ]);

        $cliente = Auth::guard('cliente')->user();

        Mail::to('amq9012@gmail.com')
            ->send(new ContactoDesdeWeb($contacto, $cliente));

        // 1) Limpiar TODOS los campos
        $this->reset(['nombre', 'email', 'telefono', 'mensaje']);

        // 2) Disparar evento JS para mostrar toast
        $this->dispatch('contacto-enviado');
    }


    public function render()
    {
        return view('livewire.tienda.contacto-form');
    }
}