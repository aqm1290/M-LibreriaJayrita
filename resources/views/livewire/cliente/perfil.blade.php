<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-black border-warning rounded-4 shadow-lg">
                <div class="card-body p-5">
                    <h2 class="text-warning fw-black mb-4">
                        <i class="bi bi-person-circle"></i> Mi Perfil
                    </h2>
                    <div class="row g-4 text-white">
                        <div class="col-md-6">
                            <strong>Nombre:</strong><br>
                            <span class="fs-5">{{ $nombre }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Email:</strong><br>
                            <span class="fs-5">{{ $email }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Teléfono:</strong><br>
                            <span class="fs-5">{{ $telefono ?: 'No registrado' }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Dirección:</strong><br>
                            <span class="fs-5">{{ $direccion ?: 'No registrada' }}</span>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ url('/tienda') }}" class="btn btn-outline-warning">
                            Volver a la tienda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
