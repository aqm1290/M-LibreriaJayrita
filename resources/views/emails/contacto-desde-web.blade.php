{{-- resources/views/emails/contacto-desde-web.blade.php --}}
<p>Nuevo mensaje desde la web:</p>

<p><strong>Nombre:</strong> {{ $contacto->nombre }}</p>
<p><strong>Email:</strong> {{ $contacto->email }}</p>
<p><strong>Teléfono:</strong> {{ $contacto->telefono }}</p>

@if ($cliente)
    <p><strong>Cliente logueado:</strong> {{ $cliente->nombre }} ({{ $cliente->email }})</p>
@endif

<p><strong>Mensaje:</strong></p>
<p>{!! nl2br(e($contacto->mensaje)) !!}</p>
