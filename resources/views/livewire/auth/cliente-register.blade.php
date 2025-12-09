<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card jayrita-auth-card rounded-4 shadow-lg">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <span class="badge jayrita-auth-badge mb-2 px-3 py-2 rounded-pill fw-bold">
                            Registro de cliente
                        </span>
                        <h1 class="h3 jayrita-auth-title fw-black mb-1">Crea tu cuenta</h1>
                        <p class="jayrita-auth-subtitle mb-0">
                            Guarda tus datos y sigue tus pedidos fácilmente.
                        </p>
                    </div>

                    <form wire:submit.prevent="registrar">

                        <div class="mb-3">
                            <label class="form-label jayrita-auth-label">Nombre completo</label>
                            <input type="text"
                                class="form-control jayrita-auth-input @error('nombre') is-invalid @enderror"
                                wire:model.defer="nombre">
                            @error('nombre')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label jayrita-auth-label">Correo electrónico</label>
                            <input type="email"
                                class="form-control jayrita-auth-input @error('email') is-invalid @enderror"
                                wire:model.defer="email">
                            @error('email')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label jayrita-auth-label">Teléfono (opcional)</label>
                            <input type="text"
                                class="form-control jayrita-auth-input-secondary @error('telefono') is-invalid @enderror"
                                wire:model.defer="telefono">
                            @error('telefono')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label jayrita-auth-label">Contraseña</label>
                                <input type="password"
                                    class="form-control jayrita-auth-input @error('password') is-invalid @enderror"
                                    wire:model.defer="password">
                                @error('password')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label jayrita-auth-label">Confirmar contraseña</label>
                                <input type="password"
                                    class="form-control jayrita-auth-input @error('password_confirmation') is-invalid @enderror"
                                    wire:model.defer="password_confirmation">
                                @error('password_confirmation')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn jayrita-auth-btn w-100 fw-black py-2 mt-2">
                            Crear cuenta
                        </button>
                    </form>

                    <hr class="jayrita-auth-divider my-4">

                    <p class="text-center jayrita-auth-subtitle mb-0">
                        ¿Ya tienes cuenta?
                        <a href="{{ route('cliente.login') }}" class="jayrita-auth-link fw-bold">
                            Inicia sesión aquí
                        </a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
