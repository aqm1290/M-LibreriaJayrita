<div>
    {{-- HERO --}}
    <section id="hero" class="jayrita-hero section py-5 py-lg-6">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="jayrita-hero-copy text-center">
                        <span class="jayrita-hero-pill mb-3 d-inline-flex align-items-center gap-2 mx-auto">
                            <span class="dot"></span>
                            Librería y Bazar en Bolivia
                        </span>

                        <h1 class="jayrita-hero-title mb-3">
                            Todo lo que necesitas para
                            <span>clases y oficina</span>
                        </h1>

                        <p class="jayrita-hero-subtitle mb-4 ">
                            Cuadernos, lapiceros, papelería escolar y accesorios de oficina, encontrarás de todo en
                            Librería Jayrita ubicada en Sacaba.
                        </p>

                        <div class="d-flex flex-wrap gap-3 justify-content-center align-items-center mt-2">
                            <a href="#categorias" class="btn btn-warning jayrita-hero-btn">
                                Ver categorías
                            </a>
                            <a href="{{ url('/catalogo') }}" class="jayrita-hero-link-outline">
                                Ver catálogo completo →
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CATEGORÍAS --}}
    <section id="categorias" class="portfolio section py-5">
        <div class="container section-title mb-4">
            <h2>Categorías</h2>
            <div>
                <span>Explora nuestros</span>
                <span class="description-title">Productos por categoría</span>
            </div>
        </div>

        <div class="container">
            <ul class="portfolio-filters">
                <li wire:click="setCategoria('todas')"
                    class="{{ $categoriaActiva === 'todas' ? 'filter-active' : '' }}">
                    <i class="bi bi-grid-3x3"></i> Todas
                </li>

                @foreach ($categorias as $cat)
                    <li wire:click="setCategoria({{ $cat->id }})"
                        class="{{ $categoriaActiva === $cat->id ? 'filter-active' : '' }}">
                        <i class="bi bi-tag"></i> {{ $cat->nombre }}
                    </li>
                @endforeach
            </ul>

            <div class="row g-4 portfolio-grid">
                @foreach ($productosFiltrados as $producto)
                    <div class="col-xl-3 col-lg-4 col-md-6 portfolio-item">
                        <article class="portfolio-entry {{ ($producto->stock ?? 0) <= 0 ? 'agotado' : '' }}">
                            <figure class="entry-image position-relative">

                                {{-- CINTA AGOTADO --}}
                                @if (($producto->stock ?? 0) <= 0)
                                    <div class="agotado-badge">Agotado</div>
                                @endif

                                <img src="{{ $producto->imagen_url ?? asset('images/no-image.png') }}" class="img-fluid"
                                    alt="{{ $producto->nombre }}" loading="lazy">

                                <div class="entry-overlay">
                                    <div class="overlay-content">
                                        <div class="entry-meta">
                                            {{ $producto->categoria?->nombre ?? 'Sin categoría' }}
                                            ·
                                            @if (($producto->stock ?? 0) > 0)
                                                Stock: <strong>{{ $producto->stock ?? 0 }}</strong>
                                            @else
                                                <strong>Sin stock</strong>
                                            @endif
                                        </div>
                                        <h3 class="entry-title">
                                            {{ $producto->nombre }}
                                        </h3>
                                        <div class="entry-links">
                                            <button type="button" class="btn p-0 border-0 bg-transparent"
                                                wire:click="seleccionarProducto({{ $producto->id }})">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <button type="button"
                                                class="btn p-0 border-0 bg-transparent agregar-carrito {{ ($producto->stock ?? 0) <= 0 ? 'btn-agregar-disabled' : '' }}"
                                                data-producto-id="{{ $producto->id }}"
                                                data-imagen="{{ $producto->imagen_url ?? asset('images/no-image.png') }}"
                                                wire:click="agregarAlPedido({{ $producto->id }})"
                                                @if (($producto->stock ?? 0) <= 0) disabled aria-disabled="true" @endif>
                                                <i class="bi bi-cart-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </figure>
                        </article>
                    </div>
                @endforeach
            </div>

            @if ($productosFiltrados->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    <div class="jayrita-pagination-pill d-inline-flex align-items-center">
                        {{ $productosFiltrados->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- MARCAS --}}
    @if ($marcas->count())
        <section class="section py-5 jayrita-marcas">
            <div class="container">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-2 mb-lg-0">Marcas que confían en nosotros</h3>
                    <p class="text-muted mb-0">Líneas escolares, oficina y regalos de las mejores marcas.</p>
                </div>

                <div class="row g-3 align-items-center">
                    @foreach ($marcas as $marca)
                        <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                            <button type="button" class="jayrita-marca-card w-100 border-0 bg-transparent"
                                wire:click="seleccionarMarca({{ $marca->id }})">
                                @if ($marca->logo_url)
                                    <img src="{{ $marca->logo_url }}" alt="{{ $marca->nombre }}">
                                @else
                                    <span>{{ $marca->nombre }}</span>
                                @endif
                            </button>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- PRODUCTOS NUEVOS --}}
    <section class="section py-5">
        <div class="container mb-4">
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <h2 class="fw-bold mb-1">Productos nuevos</h2>
                    <p class="text-muted mb-0">
                        Los últimos productos añadidos a la librería.
                    </p>
                </div>
                <div>
                    <a href="{{ url('/catalogo') }}" class="jayrita-hero-link-outline">
                        Ver catálogo completo →
                    </a>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row g-4">
                @foreach ($productosNuevos as $producto)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div
                            class="card hover-card h-100 position-relative overflow-hidden {{ ($producto->stock ?? 0) <= 0 ? 'agotado' : '' }}">

                            {{-- CINTA AGOTADO --}}
                            @if (($producto->stock ?? 0) <= 0)
                                <div class="agotado-badge">Agotado</div>
                            @endif

                            <div class="position-relative overflow-hidden">
                                <img src="{{ $producto->imagen_url ?? asset('images/no-image.png') }}"
                                    class="card-img-top producto-imagen" alt="{{ $producto->nombre }}"
                                    style="height: 220px; object-fit: contain; transition: all 0.4s;">
                                <div
                                    class="overlay-buttons position-absolute top-50 start-50 translate-middle opacity-0">
                                    <button type="button" class="btn btn-warning btn-lg rounded-circle shadow-lg"
                                        wire:click="seleccionarProducto({{ $producto->id }})">
                                        <i class="bi bi-eye fs-4"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body d-flex flex-column p-4">
                                <h5 class="card-title fw-bold mb-1">{{ $producto->nombre }}</h5>
                                <p class="text-muted small mb-1">
                                    {{ $producto->categoria?->nombre ?? 'Sin categoría' }}
                                </p>
                                <p class="text-muted small mb-2">
                                    @if (($producto->stock ?? 0) > 0)
                                        Stock: <strong>{{ $producto->stock ?? 0 }}</strong> uds.
                                    @else
                                        <strong>Sin stock</strong>
                                    @endif
                                </p>
                                <p class="fw-bold text-warning fs-5 mb-4">
                                    Bs. {{ number_format($producto->precio ?? 0, 2) }}
                                </p>
                                <div class="mt-auto d-grid gap-2">
                                    <button type="button"
                                        class="btn btn-warning fw-bold agregar-carrito {{ ($producto->stock ?? 0) <= 0 ? 'btn-agregar-disabled' : '' }}"
                                        data-producto-id="{{ $producto->id }}"
                                        data-imagen="{{ $producto->imagen_url ?? asset('images/no-image.png') }}"
                                        wire:click="agregarAlPedido({{ $producto->id }})"
                                        @if (($producto->stock ?? 0) <= 0) disabled aria-disabled="true" @endif>
                                        <i class="bi bi-cart-plus me-2"></i>
                                        {{ ($producto->stock ?? 0) > 0 ? 'Agregar al carrito' : 'Sin stock' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($productosNuevos->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    <div class="jayrita-pagination-pill d-inline-flex align-items-center">
                        {{ $productosNuevos->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- OFERTAS DESTACADAS (CARRUSEL ALTERNATIVO) --}}
    <section class="section py-5">
        <div class="container">

            @if ($promosHome && $promosHome->count())
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h3 class="fw-bold mb-1">Ofertas destacadas</h3>
                        <p class="text-muted mb-0">
                            Elige entre nuestros productos con descuento exclusivo.
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2 small text-muted">
                        <span class="rounded-circle bg-success" style="width: 10px; height: 10px;"></span>
                        En stock inmediato
                    </div>
                </div>

                <div id="promoCarouselAlt" class="carousel slide" data-bs-ride="carousel" data-bs-interval="6000">
                    <div class="carousel-inner">
                        @foreach ($promosHome->chunk(3) as $chunkIndex => $grupo)
                            <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                <div class="row g-3 carrusel-cards">
                                    @foreach ($grupo as $item)
                                        @php
                                            $producto = $item->producto;
                                            $promo = $item->promo;
                                            $precioOriginal = $item->precio_original;
                                            $precioPromo = $item->precio_promo;
                                            $descuentoPct =
                                                $precioOriginal > 0
                                                    ? round(100 - ($precioPromo * 100) / $precioOriginal)
                                                    : 0;
                                        @endphp

                                        <div class="col-lg-4">
                                            <div
                                                class="card h-100 border-0 shadow-sm overflow-hidden promo-card-alt {{ ($producto->stock ?? 0) <= 0 ? 'agotado' : '' }}">
                                                <div class="row g-0 h-100">
                                                    <div class="col-4 col-md-5 position-relative">

                                                        {{-- CINTA AGOTADO --}}
                                                        @if (($producto->stock ?? 0) <= 0)
                                                            <div class="agotado-badge">Agotado</div>
                                                        @endif

                                                        <img src="{{ $producto->imagen_url ?? asset('images/no-image.png') }}"
                                                            alt="{{ $producto->nombre }}"
                                                            class="w-100 h-100 promo-img-alt">

                                                        @if ($descuentoPct > 0)
                                                            <div class="promo-pill-discount">
                                                                -{{ $descuentoPct }}%
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-8 col-md-7 d-flex">
                                                        <div class="card-body d-flex flex-column p-3">
                                                            <span
                                                                class="badge bg-warning text-dark mb-2 align-self-start">
                                                                {{ $promo->nombre ?? 'Promoción' }}
                                                            </span>

                                                            <h6 class="fw-bold mb-1 text-truncate"
                                                                title="{{ $producto->nombre }}">
                                                                {{ $producto->nombre }}
                                                            </h6>
                                                            <p class="small text-muted mb-1 text-truncate">
                                                                {{ $producto->categoria?->nombre ?? 'Sin categoría' }}
                                                            </p>
                                                            <p class="small text-muted mb-1">
                                                                Color: <strong>{{ $producto->color ?? 'N/E' }}</strong>
                                                            </p>
                                                            <p class="small text-muted mb-1">
                                                                Color: <strong>{{ $producto->tipo ?? 'N/E' }}</strong>
                                                            </p>
                                                            <p class="small text-muted mb-1">
                                                                Modelo:
                                                                <strong>{{ $producto->modelo?->nombre ?? 'N/E' }}</strong>
                                                            </p>
                                                            <p class="small text-muted mb-2">
                                                                @if (($producto->stock ?? 0) > 0)
                                                                    Stock: <strong>{{ $producto->stock ?? 0 }}</strong>
                                                                    uds.
                                                                @else
                                                                    <strong>Sin stock</strong>
                                                                @endif
                                                            </p>

                                                            <div class="mb-2">
                                                                @if ($precioPromo < $precioOriginal)
                                                                    <div
                                                                        class="small text-muted text-decoration-line-through">
                                                                        Bs. {{ number_format($precioOriginal, 2) }}
                                                                    </div>
                                                                @endif
                                                                <div class="fw-bold text-success fs-5">
                                                                    Bs. {{ number_format($precioPromo, 2) }}
                                                                </div>
                                                            </div>

                                                            <div class="mt-auto d-flex flex-wrap gap-2">
                                                                <button type="button"
                                                                    class="btn btn-outline-secondary btn-sm"
                                                                    wire:click="seleccionarProducto({{ $producto->id }})">
                                                                    <i class="bi bi-eye me-1"></i> Ver
                                                                </button>
                                                                <button type="button"
                                                                    class="btn btn-success btn-sm fw-bold agregar-carrito {{ ($producto->stock ?? 0) <= 0 ? 'btn-agregar-disabled' : '' }}"
                                                                    data-producto-id="{{ $producto->id }}"
                                                                    data-imagen="{{ $producto->imagen_url ?? asset('images/no-image.png') }}"
                                                                    wire:click="agregarAlPedido({{ $producto->id }})"
                                                                    @if (($producto->stock ?? 0) <= 0) disabled aria-disabled="true" @endif>
                                                                    <i class="bi bi-cart-plus me-1"></i>
                                                                    {{ ($producto->stock ?? 0) > 0 ? 'Añadir' : 'Sin stock' }}
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        @foreach ($promosHome->chunk(3) as $chunkIndex => $grupo)
                            <button type="button" data-bs-target="#promoCarouselAlt"
                                data-bs-slide-to="{{ $chunkIndex }}"
                                class="promo-dot {{ $chunkIndex === 0 ? 'active' : '' }}"
                                aria-current="{{ $chunkIndex === 0 ? 'true' : 'false' }}"
                                aria-label="Slide {{ $chunkIndex + 1 }}">
                            </button>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-ticket-perforated text-warning" style="font-size: 3rem;"></i>
                    <h4 class="fw-bold mt-3" style="color: var(--default-color);">
                        No hay promociones activas
                    </h4>
                    <p class="mb-0" style="color: color-mix(in srgb, var(--default-color), transparent 45%);">
                        Vuelve pronto, estamos preparando nuevas ofertas.
                    </p>
                </div>
            @endif

        </div>
    </section>

    @include('partials.contacto')

    {{-- MODAL PRODUCTO --}}
    @if ($productoSeleccionado)
        <div class="modal fade show d-block jayrita-modal" tabindex="-1" style="background: rgba(0,0,0,0.85);">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                    <div class="modal-body p-0">
                        <div class="row g-0">
                            <div class="col-lg-5 bg-black d-flex align-items-center justify-content-center p-4 p-lg-5">
                                <img src="{{ $productoSeleccionado->imagen_url ?? asset('images/no-image.png') }}"
                                    class="img-fluid rounded-4 shadow-lg"
                                    style="max-height: 500px; object-fit: contain;">
                            </div>
                            <div class="col-lg-7 p-4 p-lg-5 position-relative">
                                <button type="button" class="btn-close position-absolute top-0 end-0 m-4"
                                    wire:click="cerrarModal"></button>

                                <h2 class="fw-bold text-warning mb-3">{{ $productoSeleccionado->nombre }}</h2>

                                <p class="mb-2 modal-text-muted">
                                    <strong>Categoría:</strong>
                                    {{ $productoSeleccionado->categoria?->nombre ?? 'Sin categoría' }}
                                </p>

                                @if ($productoSeleccionado->marca)
                                    <p class="mb-2 modal-text-muted">
                                        <strong>Marca:</strong> {{ $productoSeleccionado->marca->nombre }}
                                    </p>
                                @endif

                                <p class="mb-2 modal-text-muted">
                                    <strong>Color:</strong>
                                    {{ $productoSeleccionado->color ?? 'No especificado' }}
                                </p>

                                <p class="mb-3 modal-text-muted">
                                    <strong>Modelo:</strong>
                                    {{ $productoSeleccionado->modelo?->nombre ?? 'No especificado' }}
                                </p>

                                <div class="rounded-3 p-3 p-lg-4 mb-4 modal-desc-box">
                                    <p class="mb-0 modal-text-body">
                                        {{ $productoSeleccionado->descripcion ?? 'Sin descripción disponible.' }}
                                    </p>
                                </div>

                                @php
                                    $stockModal = $productoSeleccionado->stock ?? 0;
                                @endphp

                                <div class="d-flex align-items-center gap-4 mb-4">
                                    <h1 class="text-warning fw-black mb-0">
                                        Bs. {{ number_format($productoSeleccionado->precio ?? 0, 2) }}
                                    </h1>
                                    @if ($stockModal > 0)
                                        <span class="badge bg-success fs-6 px-3 py-2">
                                            Stock: {{ $stockModal }} {{ $stockModal === 1 ? 'unidad' : 'unidades' }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger fs-6 px-3 py-2">
                                            Sin stock
                                        </span>
                                    @endif
                                </div>

                                <div class="d-flex flex-wrap gap-3">
                                    <button type="button"
                                        class="btn btn-warning btn-lg px-5 fw-bold agregar-carrito {{ ($stockModal ?? 0) <= 0 ? 'btn-agregar-disabled' : '' }}"
                                        data-producto-id="{{ $productoSeleccionado->id }}"
                                        data-imagen="{{ $productoSeleccionado->imagen_url ?? asset('images/no-image.png') }}"
                                        wire:click="agregarAlPedido({{ $productoSeleccionado->id }})"
                                        @if (($stockModal ?? 0) <= 0) disabled aria-disabled="true" @endif>
                                        <i class="bi bi-cart-plus me-2"></i>
                                        {{ ($stockModal ?? 0) > 0 ? 'Agregar al carrito' : 'Sin stock' }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-lg px-5"
                                        wire:click="cerrarModal">
                                        Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL MARCA --}}
    @if ($marcaSeleccionada)
        <div class="modal fade show d-block jayrita-modal" tabindex="-1" style="background: rgba(0,0,0,0.85);">
            <div class="modal-dialog modal-md modal-dialog-centered">
                <div class="modal-content border-0 rounded-4 overflow-hidden shadow-lg">
                    <div class="modal-body p-4 p-lg-4 position-relative">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-3"
                            wire:click="cerrarModal"></button>

                        <div class="text-center">
                            <div class="bg-white rounded-4 px-4 py-3 shadow-lg d-inline-flex align-items-center justify-content-center mb-4"
                                style="min-width: 220px; min-height: 80px;">
                                @if ($marcaSeleccionada->logo_url)
                                    <img src="{{ $marcaSeleccionada->logo_url }}"
                                        alt="{{ $marcaSeleccionada->nombre }}"
                                        style="max-height: 60px; max-width: 200px; object-fit: contain;">
                                @else
                                    <h3 class="mb-0 fw-bold">
                                        {{ $marcaSeleccionada->nombre }}
                                    </h3>
                                @endif
                            </div>

                            <h4 class="fw-bold mb-2">
                                {{ $marcaSeleccionada->nombre }}
                            </h4>

                            <p class="mb-4 modal-text-muted">
                                Tenemos <strong>{{ $marcaProductosCount }}</strong>
                                {{ $marcaProductosCount === 1 ? 'producto' : 'productos' }} de esta marca
                                disponibles en nuestra tienda.
                            </p>

                            <div class="d-flex flex-wrap gap-3 justify-content-center">
                                <button type="button" class="btn btn-warning btn-lg px-4 fw-bold"
                                    wire:click="irACatalogoMarca">
                                    Ver productos de {{ $marcaSeleccionada->nombre }}
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-lg px-4"
                                    wire:click="cerrarModal">
                                    Cerrar
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ANIMACIÓN VOLAR AL CARRITO + TOAST --}}
    <div id="fly-to-cart"></div>

    <div x-data="{ showToast: false, mensaje: '' }" x-init="document.addEventListener('mostrar-toast', (e) => {
        mensaje = e.detail.mensaje || '¡Producto agregado!';
        showToast = true;
        setTimeout(() => showToast = false, 3000);
    });" x-show="showToast" x-transition
        class="position-fixed bottom-0 end-0 m-4 z-50">
        <div class="bg-success text-white px-4 py-3 rounded-3 shadow-lg d-flex align-items-center gap-3">
            <i class="bi bi-check-circle-fill fs-4"></i>
            <strong x-text="mensaje"></strong>
        </div>
    </div>

    {{-- JS ANIMACIONES --}}
    <script>
        function inicializarFlyToCart() {
            document.querySelectorAll('.agregar-carrito').forEach(btn => {
                if (btn.dataset.flyBound === '1') return;
                btn.dataset.flyBound = '1';

                btn.addEventListener('click', function() {
                    const imagen = this.getAttribute('data-imagen');
                    const rect = this.getBoundingClientRect();

                    const flyImg = document.createElement('img');
                    flyImg.src = imagen;
                    flyImg.style.cssText = `
                        position: fixed;
                        width: 80px;
                        height: 80px;
                        object-fit: cover;
                        border-radius: 50%;
                        border: 4px solid #ffc107;
                        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
                        z-index: 9999;
                        left: ${rect.left + rect.width / 2 - 40}px;
                        top: ${rect.top + rect.height / 2 - 40}px;
                        transition: all 1s cubic-bezier(0.2, 0.8, 0.2, 1);
                        pointer-events: none;
                    `;
                    document.body.appendChild(flyImg);

                    const carrito = document.querySelector('.btn-warning.rounded-circle') ||
                        document.querySelector('[wire\\:model]');
                    const carritoRect = carrito?.getBoundingClientRect() || {
                        top: 20,
                        right: 20
                    };

                    setTimeout(() => {
                        flyImg.style.transform =
                            `translate(${window.innerWidth - carritoRect.right - 60}px, ${carritoRect.top - 60}px) scale(0.3)`;
                        flyImg.style.opacity = '0';
                    }, 100);

                    setTimeout(() => flyImg.remove(), 1200);
                });
            });
        }

        function animarGridCategorias() {
            const grid = document.querySelector('#categorias .portfolio-grid');
            if (!grid) return;

            const items = grid.querySelectorAll('.portfolio-item');
            items.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px) scale(0.98)';
                item.style.transition = 'all 0.3s ease';
                item.style.transitionDelay = (index * 0.03) + 's';

                requestAnimationFrame(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0) scale(1)';
                });
            });
        }

        function animarCarrusel(id) {
            const carrusel = document.getElementById(id);
            if (!carrusel) return;

            function activarAnim() {
                const active = carrusel.querySelector('.carousel-item.active .carrusel-cards');
                if (!active) return;
                const cards = active.querySelectorAll('.card');
                cards.forEach((card, index) => {
                    card.classList.remove('carrusel-show');
                    card.style.transitionDelay = (index * 0.06) + 's';
                    requestAnimationFrame(() => card.classList.add('carrusel-show'));
                });
            }

            carrusel.addEventListener('slid.bs.carousel', activarAnim);
            activarAnim();
        }

        document.addEventListener('livewire:initialized', () => {
            inicializarFlyToCart();
            animarGridCategorias();
            animarCarrusel('promoCarouselAlt');

            Livewire.hook('message.processed', () => {
                inicializarFlyToCart();
                animarGridCategorias();
                animarCarrusel('promoCarouselAlt');
            });
        });
    </script>
</div>
