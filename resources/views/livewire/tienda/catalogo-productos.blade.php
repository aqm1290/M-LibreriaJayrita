@section('title', 'Todas las marcas')

<div>
    <div class="container pt-5 mt-5"><!-- Evita que se corte con el header fijo -->

        {{-- TÍTULO --}}
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

        {{-- GRID DE MARCAS --}}
        <div class="row g-4">
            @forelse($marcas as $marca)
                <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                    <a href="{{ route('tienda.marca', $marca->id) }}" class="text-decoration-none">
                        <div class="card bg-dark border-0 rounded-4 overflow-hidden shadow-lg h-100 product-card">

                            {{-- LOGO / INICIAL --}}
                            <div class="position-relative overflow-hidden bg-black d-flex align-items-center justify-content-center"
                                style="height: 160px;">
                                @if ($marca->logo_url)
                                    <img src="{{ $marca->logo_url }}" alt="{{ $marca->nombre }}" class="img-fluid"
                                        style="max-height: 140px; object-fit: contain; transition: transform 0.5s ease;">
                                @else
                                    <span class="badge bg-warning text-dark fw-bold px-3 py-2">
                                        {{ \Illuminate\Support\Str::limit($marca->nombre, 18) }}
                                    </span>
                                @endif
                            </div>

                            {{-- CUERPO --}}
                            <div class="card-body p-3 d-flex flex-column text-center">
                                <h3 class="h6 fw-bold text-white mb-2 line-clamp-2">
                                    {{ $marca->nombre }}
                                </h3>

                                <div class="mt-auto">
                                    <span class="text-warning small fw-semibold">
                                        Ver productos →
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-emoji-frown display-1 text-warning mb-4"></i>
                    <h3 class="text-white fw-bold">No hay marcas</h3>
                    <p class="text-white-50 lead">Aún no se registraron marcas en el sistema</p>
                </div>
            @endforelse
        </div>


    </div>

    {{-- ESTILOS REUTILIZADOS --}}
    <style>
        .product-card {
            transition: all 0.4s ease;
        }

        .product-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 40px rgba(255, 193, 7, 0.25) !important;
        }

        .product-card:hover img {
            transform: scale(1.05);
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</div>
