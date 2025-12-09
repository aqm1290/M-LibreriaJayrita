@section('title', 'Todas las marcas')

<div>
    <div class="container pt-5 mt-5"><!-- Evita que se corte con el header fijo -->

        {{-- TÍTULO + VOLVER --}}
        <div class="row align-items-center mb-5">
            <div class="col-lg-8">
                <h1 class="display-5 fw-black text-warning mb-2">
                    Todas las marcas
                </h1>
                <p class="lead text-50">Explora todas las marcas disponibles en la librería</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                <a href="{{ route('tienda.home') }}" class="btn btn-outline-warning btn-lg rounded-pill">
                    ← Volver a la tienda
                </a>
            </div>
        </div>

        {{-- SECCIÓN MARCAS (usa tus clases light/dark) --}}
        <section class="jayrita-marcas py-4 rounded-4">
            <div class="row g-4">
                @forelse($marcas as $marca)
                    <div class="col-6 col-sm-6 col-md-4 col-lg-3">
                        <a href="{{ route('tienda.marca', $marca->id) }}" class="text-decoration-none">
                            <div class="jayrita-marca-card h-100 text-center">
                                @if ($marca->logo_url)
                                    <img src="{{ $marca->logo_url }}" alt="{{ $marca->nombre }}" class="me-2">
                                @endif

                                <span>
                                    {{ $marca->nombre }}
                                </span>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-emoji-frown display-1 text-warning mb-4"></i>
                        <h3 class="fw-bold" style="color: var(--default-color);">No hay marcas</h3>
                        <p class="lead" style="color: #9ca3af;">Aún no se registraron marcas en el sistema</p>
                    </div>
                @endforelse
            </div>

            {{-- PAGINACIÓN --}}
            <div class="mt-5 d-flex justify-content-center">
                {{ $marcas->links('vendor.pagination.bootstrap-5') }}
            </div>
        </section>
    </div>

    <style>
        /* Reutilizamos tu hover general de cards */
        .jayrita-marca-card {
            cursor: pointer;
        }
    </style>
</div>
