@extends('layouts.shop')



@section('title', $producto->nombre)

@section('content')

    <main class="main">

        <!-- Page Title -->
        <div class="page-title dark-background" data-aos="fade">
            <div class="container position-relative">
                <h1>Nombre: {{ $producto->nombre }}</h1>

                <p>
                    Descripcion: {{ $producto->descripcion ?? 'Sin descripción disponible.' }}
                </p>

                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="{{ route('tienda.home') }}">Inicio</a></li>
                        <li><a href="#">Productos</a></li>
                        <li class="current">{{ $producto->nombre }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Portfolio Details Section -->
        <section id="portfolio-details" class="portfolio-details section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <!-- IMÁGENES -->
                    <div class="col-lg-6" data-aos="fade-right">

                        <div class="portfolio-details-media">
                            <div class="main-image">

                                <div class="portfolio-details-slider swiper init-swiper" data-aos="zoom-in">

                                    <script type="application/json" class="swiper-config">
                    {
                      "loop": true,
                      "speed": 1000,
                      "autoplay": { "delay": 6000 },
                      "slidesPerView": 1,
                      "navigation": {
                        "nextEl": ".swiper-button-next",
                        "prevEl": ".swiper-button-prev"
                      }
                    }
                  </script>

                                    <div class="swiper-wrapper">

                                        <!-- Imagen principal -->
                                        <div class="swiper-slide">
                                            <img src="{{ $producto->imagen_url ?? asset('images/no-image.png') }}"
                                                alt="{{ $producto->nombre }}" class="img-fluid">
                                        </div>

                                        <!-- Si tiene varias imágenes (opcional) -->
                                        @if ($producto->imagenes ?? false)
                                            @foreach ($producto->imagenes as $img)
                                                <div class="swiper-slide">
                                                    <img src="{{ asset('storage/productos/' . $img->url) }}"
                                                        class="img-fluid">
                                                </div>
                                            @endforeach
                                        @endif

                                    </div>

                                    <div class="swiper-button-prev"></div>
                                    <div class="swiper-button-next"></div>

                                </div>
                            </div>

                            <!-- Miniaturas -->
                            <div class="thumbnail-grid" data-aos="fade-up" data-aos-delay="200">
                                <div class="row g-2 mt-3">

                                    <div class="col-3">
                                        <img src="{{ $producto->imagen ?? asset('images/no-image.png') }}"
                                            class="img-fluid glightbox">
                                    </div>

                                    @if ($producto->imagenes ?? false)
                                        @foreach ($producto->imagenes as $img)
                                            <div class="col-3">
                                                <img src="{{ asset('storage/' . $img->url) }}" class="img-fluid glightbox">
                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- INFORMACIÓN -->
                    <div class="col-lg-6" data-aos="fade-left">
                        <div class="portfolio-details-content">

                            <!-- META -->
                            <div class="project-meta">
                                <div class="badge-wrapper">
                                    <span class="project-badge">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</span>
                                </div>

                                <div class="date-client">
                                    <div class="meta-item">
                                        <i class="bi bi-box"></i>
                                        <span>Marca: {{ $producto->marca->nombre ?? 'Sin marca' }}</span>
                                    </div>

                                    <div class="meta-item">
                                        <i class="bi bi-archive"></i>
                                        <span>Stock: {{ $producto->stock }}</span>
                                    </div>
                                </div>
                            </div>

                            <h2 class="project-title">
                                {{ $producto->nombre }}
                            </h2>

                            <div class="project-website">
                                <i class="bi bi-cash-coin"></i>
                                <span class="text-success fw-bold">Bs {{ number_format($producto->precio, 2) }}</span>
                            </div>

                            <div class="project-overview">
                                <p class="lead">
                                    {{ $producto->descripcion ?? 'No hay descripción del producto.' }}
                                </p>
                            </div>

                            <!-- Botones -->
                            <div class="cta-buttons" data-aos="fade-up" data-aos-delay="400">
                                <a href="#" class="btn-view-project">Agregar al carrito</a>
                                <a href="{{ route('tienda.home') }}" class="btn-next-project">
                                    Volver <i class="bi bi-arrow-left"></i>
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main>

@endsection
