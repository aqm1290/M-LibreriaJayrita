@extends('layouts.shop')

@section('title', 'Todas las marcas')

@section('content')
    <main class="main">

        <div class="page-title dark-background" data-aos="fade">
            <div class="container position-relative">
                <h1>Todas las marcas</h1>
                <p>Listado completo de marcas disponibles en la tienda.</p>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="{{ route('tienda.home') }}">Inicio</a></li>
                        <li class="current">Marcas</li>
                    </ol>
                </nav>
            </div>
        </div>

        <section id="services" class="services section py-5 bg-black">
            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row justify-content-center g-4 g-xl-5">
                    @forelse($marcas as $marca)
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                            <div
                                class="service-card position-relative z-1 overflow-hidden rounded-4
                                    bg-dark border border-secondary hover-lift transition text-center p-5">

                                <div class="service-icon brand-icon mx-auto mb-4">
                                    <img src="{{ $marca->logo_url ?? asset('images/no-image.png') }}"
                                        alt="{{ $marca->nombre }}" class="img-fluid brand-logo"
                                        style="max-height: 100px; width: auto; object-fit: contain;">
                                </div>

                                <a href="{{ route('marcas.show', $marca->id) }}"
                                    class="card-action d-flex align-items-center justify-content-center rounded-circle shadow-lg">
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>

                                <h3 class="h4 fw-bold text-white mb-3">
                                    <a href="{{ route('marcas.show', $marca->id) }}"
                                        class="text-white text-decoration-none stretched-link">
                                        {{ $marca->nombre }}
                                    </a>
                                </h3>

                                @if ($marca->descripcion)
                                    <p class="text-white-50 small mb-0">
                                        {{ Str::limit($marca->descripcion, 100) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <p class="text-white-50 fs-3">
                                Pronto tendremos nuestras marcas destacadas
                            </p>
                        </div>
                    @endforelse
                </div>

            </div>
        </section>

    </main>
@endsection
