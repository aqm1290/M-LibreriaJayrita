<div x-data="{ showToast: false }"
    x-on:contacto-enviado.window="
        showToast = true;
        setTimeout(() => showToast = false, 4000);
    "
    class="contact-form-wrapper">
    {{-- TOAST --}}
    <div class="position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 1080;" x-show="showToast"
        x-transition>
        <div class="toast align-items-center text-bg-success border-0 show">
            <div class="d-flex">
                <div class="toast-body">
                    Tu mensaje fue enviado correctamente.
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" @click="showToast = false"></button>
            </div>
        </div>
    </div>

    {{-- FORMULARIO --}}
    <h2 class="text-center mb-4">Envíanos un mensaje</h2>

    <form wire:submit.prevent="enviar">
        <div class="row g-3">
            {{-- Nombre --}}
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-with-icon">
                        <i class="bi bi-person"></i>
                        <input type="text" class="form-control" placeholder="Nombre completo"
                            wire:model.defer="nombre">
                    </div>
                    @error('nombre')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Email --}}
            <div class="col-md-6">
                <div class="form-group">
                    <div class="input-with-icon">
                        <i class="bi bi-envelope"></i>
                        <input type="email" class="form-control" placeholder="Correo electrónico"
                            wire:model.defer="email">
                    </div>
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Teléfono --}}
            <div class="col-md-12">
                <div class="form-group">
                    <div class="input-with-icon">
                        <i class="bi bi-text-left"></i>
                        <input type="text" class="form-control" placeholder="Teléfono (opcional)"
                            wire:model.defer="telefono">
                    </div>
                    @error('telefono')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Mensaje --}}
            <div class="col-12">
                <div class="form-group">
                    <div class="input-with-icon">
                        <i class="bi bi-chat-dots message-icon"></i>
                        <textarea class="form-control" placeholder="Escribe tu mensaje..." wire:model.defer="mensaje"></textarea>
                    </div>
                    @error('mensaje')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Botón --}}
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-submit">
                    ENVIAR MENSAJE
                </button>
            </div>
        </div>
    </form>
</div>
