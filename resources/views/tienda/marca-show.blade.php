@extends('layouts.shop')

@section('title', 'Marca: ' . $marca->nombre)

@section('content')
    <main class="main">

        <!-- Page Title -->
        <div class="page-title dark-background" data-aos="fade">
            <div class="container position-relative">
                <h1>{{ $marca->nombre }}</h1>
                <p>
                    {{ $marca->descripcion ?? 'Sin descripción para esta marca.' }}
                </p>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="{{ route('tienda.home') }}">Inicio</a></li>
                        <li class="current">Marca: {{ $marca->nombre }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Portfolio Details Section -->
        <section id="portfolio-details" class="portfolio-details section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <!-- IZQUIERDA: logo -->
                    <div class="col-lg-6" data-aos="fade-right">
                        <div class="portfolio-details-media">

                            <div class="main-image">
                                <div class="portfolio-details-slider swiper init-swiper" data-aos="zoom-in">

                                    <script type="application/json" class="swiper-config">
                                {
                                  "loop": true,
                                  "speed": 800,
                                  "autoplay": { "delay": 5000 },
                                  "slidesPerView": 1,
                                  "navigation": {
                                    "nextEl": ".swiper-button-next",
                                    "prevEl": ".swiper-button-prev"
                                  }
                                }
                                </script>

                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide text-center">
                                            <img src="{{ $marca->logo_url ?? asset('images/no-image.png') }}"
                                                alt="{{ $marca->nombre }}" class="img-fluid"
                                                style="max-height:320px; object-fit:contain;">
                                        </div>
                                    </div>

                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-button-next"></div>

                                </div>
                            </div>

                            <div class="thumbnail-grid" data-aos="fade-up" data-aos-delay="200">
                                <div class="row g-2 mt-3 justify-content-center">
                                    <div class="col-3">
                                        <img src="{{ $marca->logo_url ?? asset('images/no-image.png') }}"
                                            alt="{{ $marca->nombre }}" class="img-fluid glightbox">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- DERECHA: detalles y modelos -->
                    <div class="col-lg-6" data-aos="fade-left">
                        <div class="portfolio-details-content">

                            <div class="project-meta">
                                <div class="badge-wrapper">
                                    <span class="project-badge">Marca</span>
                                </div>
                                <div class="date-client">
                                    <div class="meta-item">
                                        <i class="bi bi-buildings"></i>
                                        <span>{{ $marca->nombre }}</span>
                                    </div>
                                </div>
                            </div>

                            <h2 class="project-title">
                                Modelos de {{ $marca->nombre }}
                            </h2>

                            <div class="project-overview mb-3">
                                <p class="lead">
                                    Selecciona alguno de los modelos disponibles de esta marca para ver sus productos.
                                </p>
                            </div>

                            <!-- MODELOS COMO BOTONES CON CANTIDAD DE PRODUCTOS -->
                            <div class="mb-4">
                                @forelse($modelos as $modelo)
                                    <a href="{{ route('modelos.show', $modelo->id) }}"
                                        class="btn btn-outline-warning btn-sm me-2 mb-2">
                                        {{ $modelo->nombre }}
                                        <span class="badge bg-dark border border-warning ms-1">
                                            {{ $modelo->productos_count }}
                                            {{ Str::plural('producto', $modelo->productos_count) }}
                                        </span>
                                    </a>
                                @empty
                                    <p class="text-muted mb-0">
                                        Esta marca aún no tiene modelos registrados.
                                    </p>
                                @endforelse
                            </div>

                            <div class="cta-buttons" data-aos="fade-up" data-aos-delay="300">
                                <a href="{{ route('tienda.home') }}" class="btn-next-project">
                                    Volver a la tienda <i class="bi bi-arrow-left"></i>
                                </a>
                            </div>

                        </div>
                    </div>

                </div>

            </div>

        </section>

    </main>
@endsection
