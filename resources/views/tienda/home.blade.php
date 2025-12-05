@extends('layouts.shop')

@section('title', 'Librería Jayrita - Inicio')

@section('content')


    {{-- ================== HERO (HOME) ================== --}}
    <section id="hero" class="hero section bg py-5">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6" data-aos="fade-up">
                    <div class="pe-lg-8">
                        <h5 class="text-warning fw-bold mb-3">¡BIENVENIDOS A LIBRERÍA JAYRITA!</h5>
                        <h1 class="display-4 fw-bold mb-4">
                            Encuentra todo <span class="text-warning">Tu Material Escolar</span>
                        </h1>
                        <p class="lead mb-4">
                            Aca encontraras, papelería escolar, accesorios de oficina, cantidad de lapiceros.
                            ¡Aca en Libreria Jayrita te estamos esperando!
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="#productos" class="btn btn-warning btn-lg px-5 py-3">
                                Ver Novedades
                            </a>
                            <a href="{{ route('productos.index') }}" class="btn btn-outline-warning btn-lg px-5 py-3">
                                Todos los Productos
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center" data-aos="zoom-in" data-aos-delay="200">
                    <img src="{{ asset('shop/assets/img/woman-png-3871948_1280.png') }}" alt="Librería Jayrita"
                        class="img-fluid rounded-4 shadow-lg" style="max-height: 500px; object-fit: cover;">
                </div>
            </div>
        </div>
    </section>

    {{-- ================== MARCAS (ANTES SERVICES) ================== --}}
    <section id="services" class="services section py-5 bg-black">
        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2 class="text-white">Marcas</h2>
            <div>
                <span>Nuestras</span>
                <span class="description-title text-warning">Marcas Destacadas</span>
            </div>
            <p class="mt-3 text-white-50">
                Trabajamos con las mejores editoriales y proveedores de papelería escolar.
            </p>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <!-- Header -->
            <div class="services-header mb-5">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h2 class="services-heading fw-bold text-white">
                            <div>Proveedores de</div>
                            <div><span class="text-warning">Confianza y Calidad</span></div>
                        </h2>
                    </div>
                    <div class="col-lg-4 text-lg-end">
                        <p class="lead text-white-50 mb-3">
                            Solo trabajamos con marcas reconocidas que garantizan calidad y durabilidad.
                        </p>

                        @if ($marcas->count() > 0)
                            <a href="{{ route('marcas.index') }}" class="btn btn-outline-warning btn-lg">
                                Ver Todas las Marcas
                                <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        @else
                            <button class="btn btn-outline-secondary btn-lg" disabled>
                                Pronto más marcas
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Grid de marcas -->
            <div class="row justify-content-center g-4 g-xl-5">
                @forelse($marcas as $marca)
                    <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                        <div
                            class="service-card position-relative z-1 overflow-hidden rounded-4
                                bg-dark border border-secondary hover-lift transition text-center p-5">

                            <!-- Logo -->
                            <div class="service-icon brand-icon mx-auto mb-4">
                                <img src="{{ $marca->logo_url ?? asset('images/no-image.png') }}" alt="{{ $marca->nombre }}"
                                    class="img-fluid brand-logo"
                                    style="max-height: 100px; width: auto; object-fit: contain;">
                            </div>

                            <!-- Botón circular -->
                            <a href="{{ route('marcas.show', $marca->id) }}"
                                class="card-action d-flex align-items-center justify-content-center rounded-circle shadow-lg">
                                <i class="bi bi-arrow-up-right"></i>
                            </a>

                            <!-- Nombre -->
                            <h3 class="h4 fw-bold text-white mb-3">
                                <a href="{{ route('marcas.show', $marca->id) }}"
                                    class="text-white text-decoration-none stretched-link">
                                    {{ $marca->nombre }}
                                </a>
                            </h3>

                            <!-- Descripción -->
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


    {{-- ================== PORTFOLIO = PRODUCTOS (CON FILTRO POR CATEGORÍA) ================== --}}
    <section id="portfolio" class="portfolio section">

        <!-- TÍTULO -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Nuestros Productos</h2>
            <div>
                <span>Explora</span>
                <span class="description-title">Nuestra Tienda</span>
            </div>
        </div>

        <div class="container-fluid" data-aos="fade-up" data-aos-delay="100">

            <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

                <!-- FILTROS (CATEGORÍAS) -->
                <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="200">
                    <li data-filter="*" class="filter-active">
                        <i class="bi bi-grid-3x3"></i> Todos
                    </li>

                    @foreach ($categorias as $categoria)
                        <li data-filter=".filter-c{{ $categoria->id }}">
                            <i class="bi bi-tag"></i> {{ $categoria->nombre }}
                        </li>
                    @endforeach
                </ul>

                <!-- CONTENEDOR DE PRODUCTOS -->
                <div class="row g-4 isotope-container" data-aos="fade-up" data-aos-delay="300">

                    @foreach ($productos as $producto)
                        <div
                            class="col-xl-3 col-lg-4 col-md-6 portfolio-item isotope-item filter-c{{ $producto->categoria_id }}">
                            <article class="portfolio-entry">
                                <figure class="entry-image">

                                    <img src="{{ $producto->imagen_url ?? asset('images/no-image.png') }}"
                                        class="img-fluid" alt="{{ $producto->nombre }}" loading="lazy">

                                    <div class="entry-overlay">
                                        <div class="overlay-content">

                                            <!-- CATEGORÍA -->
                                            <div class="entry-meta">
                                                {{ $producto->categoria->nombre }}
                                            </div>

                                            <!-- NOMBRE PRODUCTO -->
                                            <a>
                                                {{ $producto->nombre }}
                                            </a>

                                            <!-- ENLACES -->
                                            <div class="entry-links">

                                                <!-- Imagen gran tamaño -->


                                                <!-- Botón detalles producto (si tienes ruta) -->
                                                <a href="javascript:void(0)"
                                                    class="btn btn-sm btn-outline-warning btn-ver-producto"
                                                    data-id="{{ $producto->id }}">
                                                    <i class="bi bi-arrows-angle-expand"></i>
                                                </a>

                                                <a href="{{ route('tienda.producto-show', $producto->id) }}">
                                                    <i class="bi bi-arrow-right"></i>
                                                </a>



                                            </div>
                                        </div>
                                    </div>
                                </figure>
                            </article>
                        </div>
                    @endforeach

                </div>

            </div>

        </div>

    </section>

    {{-- ================== PRODUCTOS DESTACADOS / LISTA SIMPLE ================== --}}
    <section id="productos" class="productos-carousel section py-5 bg-black">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="display-5 fw-bold text-white">Novedades y Más Vendidos</h2>
                <p class="lead text-white-50">Los favoritos de esta semana</p>
            </div>
        </div>

        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper init-swiper productos-hero-slider">

                <script type="application/json" class="swiper-config">
            {
              "loop": true,
              "speed": 900,
              "autoplay": { "delay": 6000 },
              "slidesPerView": 1,
              "spaceBetween": 30,
              "centeredSlides": true,
              "effect": "coverflow",
              "coverflowEffect": {
                "rotate": 20,
                "stretch": 0,
                "depth": 200,
                "modifier": 1,
                "slideShadows": false
              },
              "navigation": {
                "nextEl": ".productos-swiper-next",
                "prevEl": ".productos-swiper-prev"
              },
              "pagination": {
                "el": ".productos-swiper-pagination",
                "clickable": true
              },
              "breakpoints": {
                "992": {
                  "slidesPerView": 1.3
                }
              }
            }
            </script>

                <div class="swiper-wrapper">

                    @forelse($productos as $producto)
                        <div class="swiper-slide">
                            <div class="card bg-dark text-white border-0 rounded-4 overflow-hidden shadow-lg">
                                <div class="row g-0 align-items-stretch">

                                    <div class="col-md-5">
                                        <div class="h-100 w-100" style="background:#000;">
                                            <img src="{{ $producto->imagen_url ?? asset('images/no-image.png') }}"
                                                alt="{{ $producto->nombre }}" class="img-fluid h-100 w-100"
                                                style="object-fit: cover;">
                                        </div>
                                    </div>

                                    <div class="col-md-7">
                                        <div class="card-body d-flex flex-column h-100 p-4 p-lg-5">
                                            <span class="badge bg-warning text-dark mb-3">
                                                Novedad
                                            </span>

                                            <h3 class="card-title fw-bold mb-2">
                                                {{ Str::limit($producto->nombre, 60) }}
                                            </h3>

                                            <p class="text-white-50 mb-2">
                                                {{ $producto->autor ?? 'Autor desconocido' }}
                                            </p>

                                            <p class="text-white-50 flex-grow-1 mb-3">
                                                {{ Str::limit($producto->descripcion ?? 'Sin descripción.', 180) }}
                                            </p>

                                            <div class="d-flex align-items-center justify-content-between mt-auto">
                                                <div>
                                                    <span class="text-white-50 small d-block">Precio</span>
                                                    <span class="h4 text-warning mb-0">
                                                        Bs. {{ number_format($producto->precio ?? 0, 2) }}
                                                    </span>
                                                </div>

                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('tienda.producto-show', $producto->id) }}"
                                                        class="btn btn-warning text-dark fw-bold">
                                                        Ver detalle
                                                    </a>
                                                    <button class="btn btn-outline-light">
                                                        <i class="bi bi-cart-plus"></i>
                                                    </button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div class="text-center py-5">
                                <p class="text-white-50 fs-4 mb-0">Pronto tendremos novedades.</p>
                            </div>
                        </div>
                    @endforelse

                </div>

                <div class="d-flex justify-content-between align-items-center mt-4 px-3 px-lg-0">
                    <div class="productos-swiper-nav d-flex gap-2">
                        <button class="btn btn-outline-light rounded-circle productos-swiper-prev">
                            <i class="bi bi-arrow-left"></i>
                        </button>
                        <button class="btn btn-outline-light rounded-circle productos-swiper-next">
                            <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>

                    <div class="productos-swiper-pagination text-end"></div>
                </div>

            </div>

            <div class="text-center mt-5">
                <a href="{{ url('/catalogo') }}" class="btn btn-outline-warning btn-lg">
                    Ver todo el catálogo →
                </a>
            </div>
        </div>
    </section>


    <!-- Hover lift CSS (agrega esto en tu main.css si no lo tienes) -->



    {{-- ================== ABOUT (AL FINAL, Mejorar) ================== --}}
    <section id="about" class="about section py-5 ">
        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>About</h2>
            <div><span>Learn More</span> <span class="description-title">About Us</span></div>
        </div><!-- End Section Title -->



        <div class="container">

            <div class="row gx-5 align-items-center">
                <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
                    <div class="about-image position-relative">
                        <img src="assets/img/about/about-portrait-1.webp" class="img-fluid rounded-4 shadow-sm"
                            alt="About Image" loading="lazy">
                        <div class="experience-badge">
                            <span class="years">20+</span>
                            <span class="text">Years of Expertise</span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mt-4 mt-lg-0" data-aos="fade-left" data-aos-delay="300">
                    <div class="about-content">
                        <h2>Elevating Business Performance Through Innovation</h2>
                        <p class="lead">We focus on crafting bespoke strategies that navigate complexity and deliver
                            tangible results for our clients.</p>
                        <p>Through a blend of sophisticated analytics and creative problem-solving, we empower organizations
                            to thrive in rapidly evolving markets.</p>

                        <div class="row g-4 mt-3">
                            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="400">
                                <div class="feature-item">
                                    <i class="bi bi-check-circle-fill"></i>
                                    <h5>Dedicated Team Support</h5>
                                    <p>Our highly skilled professionals are committed to providing personalized service and
                                        impactful solutions on every engagement.</p>
                                </div>
                            </div>
                            <div class="col-md-6" data-aos="zoom-in" data-aos-delay="450">
                                <div class="feature-item">
                                    <i class="bi bi-lightbulb-fill"></i>
                                    <h5>Forward-Thinking Approach</h5>
                                    <p>We embrace innovative methodologies to develop unique strategies that drive lasting
                                        success.</p>
                                </div>
                            </div>
                        </div>

                        <a href="#" class="btn btn-primary mt-4">Explore Our Services</a>
                    </div>
                </div>
            </div>

            <div class="testimonial-section mt-5 pt-5" data-aos="fade-up" data-aos-delay="100">
                <div class="row">
                    <div class="col-lg-4" data-aos="fade-right" data-aos-delay="200">
                        <div class="testimonial-intro">
                            <h3>Our Clients Speak Highly</h3>
                            <p>Hear directly from those who have experienced the impact of our partnership and achieved
                                their strategic goals.</p>
                            <div class="swiper-nav-buttons mt-4">
                                <button class="slider-prev"><i class="bi bi-arrow-left"></i></button>
                                <button class="slider-next"><i class="bi bi-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8" data-aos="fade-left" data-aos-delay="300">
                        <div class="testimonial-slider swiper init-swiper">
                            <script type="application/json" class="swiper-config">
                  {
                    "loop": true,
                    "speed": 800,
                    "autoplay": {
                      "delay": 5000
                    },
                    "slidesPerView": 1,
                    "spaceBetween": 30,
                    "navigation": {
                      "nextEl": ".slider-next",
                      "prevEl": ".slider-prev"
                    },
                    "breakpoints": {
                      "768": {
                        "slidesPerView": 2
                      }
                    }
                  }
                </script>
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="rating mb-3">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                        <p>"Their strategic vision and unwavering commitment to results provided exceptional
                                            value. Our operational efficiency has signficantly improved."</p>
                                        <div class="client-info d-flex align-items-center mt-4">
                                            <img src="assets/img/person/person-f-1.webp" class="client-img"
                                                alt="Client" loading="lazy">
                                            <div>
                                                <h6 class="mb-0">Eleanor Vance</h6>
                                                <span>Operations Manager</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="rating mb-3">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-half"></i>
                                        </div>
                                        <p>"Collaborating with their team was a revelation. Their innovative strategies
                                            guided us toward achieving our objectives with precision and speed."</p>
                                        <div class="client-info d-flex align-items-center mt-4">
                                            <img src="assets/img/person/person-m-1.webp" class="client-img"
                                                alt="Client" loading="lazy">
                                            <div>
                                                <h6 class="mb-0">David Kim</h6>
                                                <span>Product Lead</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="rating mb-3">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                        </div>
                                        <p>"The depth of knowledge and unwavering dedication they bring to every project is
                                            exceptional. They've become an essential ally in driving our expansion."</p>
                                        <div class="client-info d-flex align-items-center mt-4">
                                            <img src="assets/img/person/person-f-2.webp" class="client-img"
                                                alt="Client" loading="lazy">
                                            <div>
                                                <h6 class="mb-0">Isabella Diaz</h6>
                                                <span>Research Analyst</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="swiper-slide">
                                    <div class="testimonial-item">
                                        <div class="rating mb-3">
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-fill"></i>
                                            <i class="bi bi-star-half"></i>
                                        </div>
                                        <p>"Their dedication to delivering superior solutions and their meticulous attention
                                            to detail have profoundly impacted our corporate growth trajectory."</p>
                                        <div class="client-info d-flex align-items-center mt-4">
                                            <img src="assets/img/person/person-f-3.webp" class="client-img"
                                                alt="Client" loading="lazy">
                                            <div>
                                                <h6 class="mb-0">Olivia Chen</h6>
                                                <span>Development Strategist</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- Contact Section -->
    <section id="contact" class="contact section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Contacto</h2>
            <div><span>Contactanos</span> <span class="description-title">Ahora!!!</span></div>
        </div><!-- End Section Title -->

        <div class="container" data-aos="fade-up" data-aos-delay="100">

            <!-- Contact Info Boxes -->
            <div class="row gy-4 mb-5">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="contact-info-box">
                        <div class="icon-box">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div class="info-content">
                            <h4>Nuestra Direccion</h4>
                            <p>Sacaba - Cochabamba - Bolivia</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="contact-info-box">
                        <div class="icon-box">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div class="info-content">
                            <h4>Envianos un Mensaje</h4>
                            <p>info@example.com</p>
                            <p>123456789</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="contact-info-box">
                        <div class="icon-box">
                            <i class="bi bi-headset"></i>
                        </div>
                        <div class="info-content">
                            <h4>Horario de Atencion</h4>
                            <p>TODOS LOS DIAS</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Google Maps (Full Width) -->
        <div class="map-section" data-aos="fade-up" data-aos-delay="200">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2509.9047587588366!2d-66.04034882860566!3d-17.406499331135727!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x93e37b002c22fd9d%3A0x3c19ddaa648d8be6!2sLibrer%C3%ADa%20Jayra!5e0!3m2!1ses!2sbo!4v1764798553984!5m2!1ses!2sbo"
                width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

        <!-- Contact Form Section (Overlapping) -->
        <div class="container form-container-overlap">
            <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="300">
                <div class="col-lg-10">
                    <div class="contact-form-wrapper">
                        <h2 class="text-center mb-4">Get in Touch</h2>

                        <form action="forms/contact.php" method="post" class="php-email-form">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-with-icon">
                                            <i class="bi bi-person"></i>
                                            <input type="text" class="form-control" name="name"
                                                placeholder="First Name" required="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="input-with-icon">
                                            <i class="bi bi-envelope"></i>
                                            <input type="email" class="form-control" name="email"
                                                placeholder="Email Address" required="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="input-with-icon">
                                            <i class="bi bi-text-left"></i>
                                            <input type="text" class="form-control" name="subject"
                                                placeholder="Subject" required="">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="input-with-icon">
                                            <i class="bi bi-chat-dots message-icon"></i>
                                            <textarea class="form-control" name="message" placeholder="Write Message..." style="height: 180px" required=""></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="loading">Loading</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Your message has been sent. Thank you!</div>
                                </div>

                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary btn-submit">SEND MESSAGE</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </section><!-- /Contact Section -->



    <!-- Modal Producto AJAX -->
    <!-- Modal Producto - VERSIÓN CORREGIDA -->
    <div class="modal fade" id="modalProductoAjax" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content bg-dark text-white border-0 rounded-4 overflow-hidden">

                <div class="modal-header border-0 pb-0 position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3"
                        data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body pt-0">
                    <div class="row g-4">

                        <!-- IMAGEN -->
                        <div class="col-md-5 text-center">
                            <div id="skeletonImg" class="skeleton rounded mx-auto"
                                style="width:100%; max-width:380px; height:380px;"></div>
                            <img id="modalImg" src="" class="img-fluid rounded d-none"
                                style="max-height:420px; object-fit:contain;">
                        </div>

                        <!-- INFO -->
                        <div class="col-md-7">
                            <div id="skeletonTitulo" class="skeleton mb-3" style="height:48px; width:90%;"></div>
                            <h3 id="modalNombre" class="fw-bold text-warning d-none mb-3"></h3>

                            <div id="skeletonCat" class="skeleton mb-3" style="height:24px; width:60%;"></div>

                            <p id="pMarca" class="text-muted mb-1 d-none">
                                Marca: <strong id="modalMarca" class="text-white"></strong>
                            </p>

                            <p id="pModelo" class="text-muted mb-3 d-none">
                                Modelo: <strong id="modalModelo" class="text-white"></strong>
                            </p>

                            <div id="skeletonDesc" class="skeleton mb-2" style="height:20px; width:100%;"></div>
                            <div class="skeleton mb-4" style="height:20px; width:85%;"></div>
                            <p id="modalDescripcion" class="text-white-70 lh-lg d-none"></p>

                            <div id="skeletonPrecio" class="skeleton mt-4" style="height:52px; width:60%;"></div>
                            <h2 id="modalPrecio" class="text-warning fw-bold d-none mb-4"></h2>

                            <div class="d-flex flex-wrap gap-3">
                                <button id="btnAgregarCarrito" class="btn btn-warning btn-lg px-5 text-dark fw-bold">
                                    Agregar al carrito
                                </button>
                                <a id="modalLink" href="#" class="btn btn-outline-warning btn-lg">
                                    Ver página completa →
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
