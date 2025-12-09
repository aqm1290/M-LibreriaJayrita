<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card jayrita-auth-card rounded-4 shadow-lg">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <span class="badge jayrita-auth-badge mb-2 px-3 py-2 rounded-pill fw-bold">
                            Clientes Librería Jayrita
                        </span>
                        <h1 class="h3 jayrita-auth-title fw-black mb-1">Inicia sesión</h1>
                        <p class="jayrita-auth-subtitle mb-0">
                            Accede para ver y confirmar tus pedidos.
                        </p>
                    </div>

                    <form wire:submit.prevent="ingresar">

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
                            <label class="form-label jayrita-auth-label">Contraseña</label>
                            <input type="password"
                                class="form-control jayrita-auth-input @error('password') is-invalid @enderror"
                                wire:model.defer="password">
                            @error('password')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model="remember" id="remember">
                                <label class="form-check-label jayrita-auth-label" for="remember">
                                    Recuérdame
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn jayrita-auth-btn w-100 fw-black py-2">
                            Entrar
                        </button>
                    </form>

                    <hr class="jayrita-auth-divider my-4">

                    <p class="text-center jayrita-auth-subtitle mb-1">
                        ¿Aún no tienes cuenta?
                        <a href="{{ route('cliente.register') }}" class="jayrita-auth-link fw-bold">
                            Regístrate aquí
                        </a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
