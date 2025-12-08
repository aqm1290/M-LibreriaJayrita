<div>
    {{-- HERO --}}
    <section id="hero" class="hero section bg py-5">
        <div class="container">
            <div class="row align-items-center gy-5">
                <div class="col-lg-6">
                    <div class="pe-lg-8">
                        <h5 class="text-warning fw-bold mb-3">¡BIENVENIDOS A LIBRERÍA JAYRITA!</h5>
                        <h1 class="display-4 fw-bold mb-4">
                            Encuentra todo <span class="text-warning">Tu Material Escolar</span>
                        </h1>
                        <p class="lead mb-4">
                            Libros, papelería escolar y accesorios de oficina para todo Bolivia.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="#productos" class="btn btn-warning btn-lg px-5 py-3">
                                Ver Novedades
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="{{ asset('shop/assets/img/woman-png-3871948_1280.png') }}" alt="Librería Jayrita"
                        class="img-fluid rounded-4 shadow-lg" style="max-height: 500px; object-fit: cover;">
                </div>
            </div>
        </div>
    </section>

    {{-- PRODUCTOS DESTACADOS --}}
    <section id="productos" class="section py-5 bg-black text-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold text-white">Productos destacados</h2>
                <p class="text-white-50">Explora algunos de nuestros productos activos</p>
            </div>

            <div class="row g-4">
                @foreach ($productos as $producto)
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div
                            class="card bg-dark border-0 h-100 position-relative overflow-hidden rounded-3 shadow-lg hover-card">

                            <!-- IMAGEN CON EFECTO -->
                            <div class="position-relative overflow-hidden">
                                <img src="{{ $producto->imagen_url ?? asset('images/no-image.png') }}"
                                    class="card-img-top producto-imagen" alt="{{ $producto->nombre }}"
                                    style="height: 250px; object-fit: cover; transition: all 0.4s;">
                                <div
                                    class="overlay-buttons position-absolute top-50 start-50 translate-middle opacity-0">
                                    <button type="button" class="btn btn-warning btn-lg rounded-circle shadow-lg"
                                        wire:click="seleccionarProducto({{ $producto->id }})">
                                        <i class="bi bi-eye fs-4"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="card-body d-flex flex-column p-4">
                                <h5 class="card-title text-white fw-bold">
                                    {{ $producto->nombre }}
                                </h5>
                                <p class="text-white-50 small mb-2">
                                    {{ $producto->categoria?->nombre ?? 'Sin categoría' }}
                                </p>
                                <p class="fw-bold text-warning fs-4 mb-4">
                                    Bs. {{ number_format($producto->precio ?? 0, 2) }}
                                </p>

                                <div class="mt-auto d-grid gap-2">
                                    <button type="button" class="btn btn-warning fw-bold agregar-carrito"
                                        data-producto-id="{{ $producto->id }}"
                                        data-imagen="{{ $producto->imagen_url ?? asset('images/no-image.png') }}"
                                        wire:click="agregarAlPedido({{ $producto->id }})">
                                        <i class="bi bi-cart-plus me-2"></i>
                                        Agregar al carrito
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="text-center mt-5">
                <a href="{{ url('/catalogo') }}" class="btn btn-outline-warning btn-lg px-5">
                    Ver todo el catálogo →
                </a>
            </div>
        </div>
    </section>

    {{-- MODAL MEJORADO --}}
    @if ($productoSeleccionado)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.85);">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content bg-dark text-white border-0 rounded-4 overflow-hidden shadow-lg">

                    <div class="modal-body p-0">
                        <div class="row g-0">
                            <div class="col-lg-5 bg-black d-flex align-items-center justify-content-center p-5">
                                <img src="{{ $productoSeleccionado->imagen_url ?? asset('images/no-image.png') }}"
                                    class="img-fluid rounded-4 shadow-lg"
                                    style="max-height: 500px; object-fit: contain;">
                            </div>
                            <div class="col-lg-7 p-5">
                                <button type="button"
                                    class="btn-close btn-close-white position-absolute top-0 end-0 m-4"
                                    wire:click="cerrarModal"></button>

                                <h2 class="fw-bold text-warning mb-3">{{ $productoSeleccionado->nombre }}</h2>

                                <p class="text-white-50 mb-3">
                                    <strong>Categoría:</strong>
                                    {{ $productoSeleccionado->categoria?->nombre ?? 'Sin categoría' }}
                                </p>

                                <div class="bg-white-10 rounded-3 p-4 mb-4">
                                    <p class="text-white lh-lg mb-0">
                                        {{ $productoSeleccionado->descripcion ?? 'Sin descripción disponible.' }}
                                    </p>
                                </div>

                                <div class="d-flex align-items-center gap-4 mb-4">
                                    <h1 class="text-warning fw-black mb-0">
                                        Bs. {{ number_format($productoSeleccionado->precio ?? 0, 2) }}
                                    </h1>
                                    <span class="badge bg-success fs-6 px-3 py-2">Stock disponible</span>
                                </div>

                                <div class="d-flex gap-3">
                                    <button type="button" class="btn btn-warning btn-lg px-5 fw-bold agregar-carrito"
                                        data-producto-id="{{ $productoSeleccionado->id }}"
                                        data-imagen="{{ $productoSeleccionado->imagen_url ?? asset('images/no-image.png') }}"
                                        wire:click="agregarAlPedido({{ $productoSeleccionado->id }})">
                                        <i class="bi bi-cart-plus me-2"></i>
                                        Agregar al carrito
                                    </button>
                                    <button type="button" class="btn btn-outline-light btn-lg px-5"
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

    {{-- CSS PERSONALIZADO --}}
    <style>
        .hover-card:hover .overlay-buttons {
            opacity: 1 !important;
            transition: all 0.4s;
        }

        .hover-card:hover .producto-imagen {
            transform: scale(1.1);
        }

        .overlay-buttons {
            transition: all 0.4s;
        }
    </style>

    {{-- JS ANIMACIÓN VOLAR AL CARRITO --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.agregar-carrito').forEach(btn => {
                btn.addEventListener('click', function() {
                    const imagen = this.getAttribute('data-imagen');
                    const rect = this.getBoundingClientRect();

                    // Crear imagen que vuela
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
                        left: ${rect.left + rect.width/2 - 40}px;
                        top: ${rect.top + rect.height/2 - 40}px;
                        transition: all 1s cubic-bezier(0.2, 0.8, 0.2, 1);
                        pointer-events: none;
                    `;
                    document.body.appendChild(flyImg);

                    // Posición del carrito (ajusta según tu header)
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
        });
    </script>
</div>
