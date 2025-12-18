@section('title', 'Todas las marcas')

<div>
    <div class="container pt-5 mt-5">

        {{-- TÍTULO + VOLVER --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
            <div>
                <h1 class="display-5 fw-black titulo-marcas mb-2">
                    Todas las marcas
                </h1>
                <p class="lead subtitulo-marcas mb-0">
                    Explora todas las marcas disponibles en la librería.
                </p>
            </div>
            <div>
                <a href="{{ route('tienda.home') }}" class="btn btn-outline-warning btn-lg rounded-pill">
                    ← Volver a la tienda
                </a>
            </div>
        </div>

        {{-- GRID DE MARCAS --}}
        <section class="seccion-marcas rounded-4 p-3 p-md-4">
            <div class="row g-4">
                @forelse($marcas as $marca)
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <a href="{{ route('tienda.marca', $marca->id) }}" class="text-decoration-none">
                            <div
                                class="tarjeta-marca d-flex flex-column align-items-center justify-content-center h-100">
                                <div class="tarjeta-marca-logo mb-2">
                                    @if ($marca->logo_url)
                                        <img src="{{ $marca->logo_url }}" alt="{{ $marca->nombre }}">
                                    @else
                                        <span class="tarjeta-marca-inicial">
                                            {{ mb_substr($marca->nombre, 0, 1) }}
                                        </span>
                                    @endif
                                </div>
                                <span class="tarjeta-marca-nombre text-center">
                                    {{ $marca->nombre }}
                                </span>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-emoji-frown display-1 text-warning mb-4"></i>
                        <h3 class="fw-bold texto-empty-marcas">No hay marcas</h3>
                        <p class="lead texto-empty-sub">
                            Aún no se registraron marcas en el sistema.
                        </p>
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
        .titulo-marcas {
            color: #facc15;
        }

        :root[data-theme="light"] .titulo-marcas {
            color: #f59e0b;
        }

        .subtitulo-marcas {
            color: color-mix(in srgb, var(--default-color), transparent 40%);
        }

        .seccion-marcas {
            background:
                radial-gradient(circle at top left, rgba(251, 191, 36, 0.15), transparent 55%),
                rgba(15, 23, 42, 0.96);
            border: 1px solid rgba(148, 163, 184, 0.35);
        }

        :root[data-theme="light"] .seccion-marcas {
            background:
                radial-gradient(circle at top left, rgba(251, 191, 36, 0.10), transparent 55%),
                #f9fafb;
            border-color: rgba(148, 163, 184, 0.4);
        }

        .tarjeta-marca {
            border-radius: 1.25rem;
            padding: 1rem 0.75rem;
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.4);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease, background 0.2s ease;
            cursor: pointer;
        }

        :root[data-theme="light"] .tarjeta-marca {
            background: #ffffff;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.1);
        }

        .tarjeta-marca:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.5);
            border-color: #fbbf24;
        }

        :root[data-theme="light"] .tarjeta-marca:hover {
            box-shadow: 0 16px 40px rgba(15, 23, 42, 0.18);
        }

        .tarjeta-marca-logo {
            width: 72px;
            height: 72px;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.9);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        :root[data-theme="light"] .tarjeta-marca-logo {
            background: #f3f4f6;
        }

        .tarjeta-marca-logo img {
            max-width: 80%;
            max-height: 80%;
            object-fit: contain;
        }

        .tarjeta-marca-inicial {
            font-size: 1.8rem;
            font-weight: 800;
            color: #facc15;
        }

        .tarjeta-marca-nombre {
            font-size: 0.9rem;
            font-weight: 700;
            color: #e5e7eb;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        :root[data-theme="light"] .tarjeta-marca-nombre {
            color: #111827;
        }

        .texto-empty-marcas {
            color: #f9fafb;
        }

        .texto-empty-sub {
            color: #9ca3af;
        }

        :root[data-theme="light"] .texto-empty-marcas {
            color: #111827;
        }

        @media (max-width: 576px) {
            .tarjeta-marca-logo {
                width: 64px;
                height: 64px;
            }

            .tarjeta-marca-nombre {
                font-size: 0.8rem;
            }
        }
    </style>
</div>
