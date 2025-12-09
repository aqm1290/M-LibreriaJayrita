<div class="container py-5">
    <div class="row g-4">
        {{-- Columna perfil --}}
        <div class="col-lg-4">
            <div class="card jayrita-profile-card rounded-4 shadow-sm h-100">
                <div class="card-body text-center p-4">

                    <div class="jayrita-profile-avatar mx-auto mb-3">
                        <span>{{ strtoupper(substr($nombre, 0, 1)) }}</span>
                    </div>

                    <h2 class="h5 fw-bold mb-1 jayrita-profile-name">
                        {{ $nombre }}
                    </h2>

                    <p class="jayrita-profile-email mb-2">
                        {{ $email }}
                    </p>

                    @if ($telefono)
                        <p class="small mb-1">
                            <i class="bi bi-telephone me-1"></i> {{ $telefono }}
                        </p>
                    @endif

                    @if ($direccion)
                        <p class="small mb-3">
                            <i class="bi bi-geo-alt me-1"></i> {{ $direccion }}
                        </p>
                    @endif

                    <p class="small jayrita-profile-meta mb-4">
                        Cliente desde {{ auth('cliente')->user()->created_at->format('d/m/Y') }}
                    </p>

                    <a href="{{ route('cliente.logout') }}"
                        onclick="event.preventDefault(); this.nextElementSibling.submit();"
                        class="btn btn-outline-danger btn-sm px-3">
                        <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
                    </a>
                    <form method="POST" action="{{ route('cliente.logout') }}" class="d-none">
                        @csrf
                    </form>

                </div>
            </div>
        </div>

        {{-- Columna pedidos --}}
        <div class="col-lg-8">
            @livewire('cliente.mis-pedidos')
        </div>
    </div>
</div>
