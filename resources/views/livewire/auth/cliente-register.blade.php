<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="card bg-black border border-warning border-opacity-50 rounded-4 shadow-lg">
                <div class="card-body p-4 p-md-5">

                    <div class="text-center mb-4">
                        <span class="badge bg-warning text-dark fw-bold mb-2 px-3 py-2 rounded-pill">
                            Registro de cliente
                        </span>
                        <h1 class="h3 text-white fw-black mb-1">Crea tu cuenta</h1>
                        <p class="text-white-50 mb-0">
                            Guarda tus datos y sigue tus pedidos fácilmente.
                        </p>
                    </div>

                    <form wire:submit.prevent="registrar">

                        <div class="mb-3">
                            <label class="form-label text-white-50">Nombre completo</label>
                            <input type="text"
                                class="form-control bg-dark text-white border-warning @error('nombre') is-invalid @enderror"
                                wire:model.defer="nombre">
                            @error('nombre')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

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
                            <label class="form-label text-white-50">Teléfono (opcional)</label>
                            <input type="text"
                                class="form-control bg-dark text-white border-secondary @error('telefono') is-invalid @enderror"
                                wire:model.defer="telefono">
                            @error('telefono')
                                <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white-50">Contraseña</label>
                                <input type="password"
                                    class="form-control bg-dark text-white border-warning @error('password') is-invalid @enderror"
                                    wire:model.defer="password">
                                @error('password')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label text-white-50">Confirmar contraseña</label>
                                <input type="password"
                                    class="form-control bg-dark text-white border-warning @error('password_confirmation') is-invalid @enderror"
                                    wire:model.defer="password_confirmation">
                                @error('password_confirmation')
                                    <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 fw-black py-2 text-dark mt-2">
                            Crear cuenta
                        </button>
                    </form>

                    <hr class="border-secondary my-4">

                    <p class="text-center text-white-50 mb-0">
                        ¿Ya tienes cuenta?
                        <a href="{{ route('cliente.login') }}" class="text-warning fw-bold">
                            Inicia sesión aquí
                        </a>
                    </p>

                </div>
            </div>
        </div>
    </div>
</div>
