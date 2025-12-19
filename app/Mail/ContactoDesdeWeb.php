<?php
// app/Mail/ContactoDesdeWeb.php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Contacto;

class ContactoDesdeWeb extends Mailable
{
    use Queueable, SerializesModels;

    public Contacto $contacto;
    public $cliente;

    public function __construct(Contacto $contacto, $cliente)
    {
        $this->contacto = $contacto;
        $this->cliente  = $cliente;
    }

    public function build()
    {
        return $this->from($this->cliente?->email ?? $this->contacto->email,
                           $this->cliente?->nombre ?? $this->contacto->nombre)
            ->subject('Nuevo mensaje de contacto: '.$this->contacto->nombre)
            ->view('emails.contacto-desde-web');
    }
}