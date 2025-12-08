<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card bg-black border border-warning border-opacity-50 rounded-4 shadow-lg">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <span class="badge bg-warning text-dark fw-bold mb-2 px-3 py-2 rounded-pill">
                            Clientes Librería Jayrita
                        </span>
                        <h1 class="h3 text-white fw-black mb-1">Inicia sesión</h1>
                        <p class="text-white-50 mb-0">
                            Accede para ver y confirmar tus pedidos.
                        </p>
                    </div>

                    <form wire:submit.prevent="ingresar">

                        <div class="mb-3">
                            <label class="form-label text-white-50">Correo electrónico</label>
                            <input type="email"
                                class="form-control bg-dark text-white border-warning @error('email') is-invalid @enderror"
                                wire:model.defer="email">
                            @error('email')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-white-50">Contraseña</label>
                            <input type="password"
                                class="form-control bg-dark text-white border-warning @error('password') is-invalid @enderror"
                                wire:model.defer="password">
                            @error('password')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model="remember" id="remember">
                                <label class="form-check-label text-white-50" for="remember">
                                    Recuérdame
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 fw-black py-2 text-dark">
                            Entrar
                        </button>
                    </form>

                    <hr class="border-secondary my-4">

                    <p class="text-center text-white-50 mb-1">
                        ¿Aún no tienes cuenta?
                        <a href="{{ route('cliente.register') }}" class="text-warning fw-bold">
                            Regístrate aquí
                        </a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
