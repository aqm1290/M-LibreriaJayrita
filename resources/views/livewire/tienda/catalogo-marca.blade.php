<div>
    @section('title', 'Marcas - ' . ($marca->nombre ?? 'Catálogo'))

    <div class="container pt-5 mt-5"><!-- Evita que se corte con el header fijo -->

        {{-- FILTRO DE MARCAS --}}
        <div class="mb-4">
            <h2 class="fw-black text-warning mb-3">Marcas</h2>
            <div class="d-flex flex-wrap gap-2">
                @foreach ($marcas as $m)
                    <button type="button" wire:click="setMarca({{ $m->id }})"
                        class="btn btn-sm text-black rounded-pill 
                            {{ $m->id === $marca->id ? 'btn-warning fw-bold text-dark' : 'btn-outline-secondary text-light' }}">
                        {{ $m->nombre }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- TÍTULO --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="display-6 fw-black text-warning mb-1">
                    Productos de {{ $marca->nombre }}
                </h1>
                <p class="mb-0" style="color: color-mix(in srgb, var(--default-color), transparent 40%);">
                    Explora todos los productos disponibles de esta marca.
                </p>
            </div>
            <div class="d-none d-md-block">
                <a href="{{ route('tienda.marcas') }}" class="btn btn-outline-warning rounded-pill">
                    ← Ver todas las marcas
                </a>
            </div>
        </div>

        {{-- GRID DE PRODUCTOS --}}
        <div class="row g-4">
            @forelse ($productos as $producto)
                <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-3">
                    <div class="card bg-dark border-0 rounded-4 overflow-hidden shadow-lg h-100 product-card">
                        {{-- IMAGEN --}}
                        <div class="position-relative overflow-hidden bg-black">
                            @if ($producto->imagen_url)
                                <img src="{{ $producto->imagen_url }}" class="card-img-top"
                                    alt="{{ $producto->nombre }}"
                                    style="height: 200px; object-fit: cover; transition: transform 0.5s ease;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-secondary"
                                    style="height: 200px;">
                                    <i class="bi bi-image display-4 text-white-50"></i>
                                </div>
                            @endif

                            @if ($producto->promociones->isNotEmpty())
                                <div class="position-absolute top-0 start-0 m-3">
                                    <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill shadow">OFERTA</span>
                                </div>
                            @endif

                            <div class="zoom-overlay">
                                <button type="button" class="btn btn-warning btn-lg rounded-circle shadow-lg"
                                    wire:click="abrirModal({{ $producto->id }})">
                                    <i class="bi bi-zoom-in fs-4"></i>
                                </button>
                            </div>
                        </div>

                        {{-- CUERPO --}}
                        <div class="card-body p-3 d-flex flex-column">
                            <h3 class="h6 fw-bold text-white mb-1 line-clamp-2">
                                {{ $producto->nombre }}
                            </h3>
                            <p class="small text-white-50 mb-2">
                                {{ $producto->categoria?->nombre ?? 'Sin categoría' }}
                                @if ($producto->modelo?->nombre)
                                    · {{ $producto->modelo->nombre }}
                                @endif
                            </p>

                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-end mb-3">
                                    <span class="fs-5 fw-black text-warning">
                                        Bs {{ number_format($producto->precio, 2) }}
                                    </span>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-warning btn-md fw-bold rounded-pill py-2"
                                        wire:click="agregarAlPedido({{ $producto->id }})">
                                        <i class="bi bi-cart-plus me-2"></i>
                                        Agregar
                                    </button>

                                    <button type="button" class="btn btn-outline-warning btn-sm rounded-pill"
                                        wire:click="abrirModal({{ $producto->id }})">
                                        <i class="bi bi-eye me-2"></i> Ver detalle
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-emoji-frown display-1 text-warning mb-4"></i>
                    <h3 class="text-white fw-bold">No hay productos para esta marca.</h3>
                    <p class="lead" style="color: color-mix(in srgb, var(--default-color), transparent 45%);">
                        Prueba seleccionando otra marca en la parte superior.
                    </p>
                </div>
            @endforelse
        </div>

        {{-- PAGINACIÓN --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $productos->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>

    {{-- MODAL DETALLE --}}
    @if (!is_null($productoSeleccionado))
        <div class="modal fade show d-block jayrita-modal" id="productoModal" tabindex="-1"
            wire:keydown.escape="cerrarModal" style="background: rgba(0,0,0,0.95); z-index: 9999;">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 rounded-4 shadow-lg">
                    <div class="modal-header border-0">
                        <button wire:click="cerrarModal" class="btn-close"></button>
                    </div>

                    <div class="modal-body p-4 p-lg-5">
                        <div class="row g-5">
                            <div class="col-lg-5">
                                <img src="{{ $productoSeleccionado->imagen_url ?? asset('images/no-image.png') }}"
                                    class="img-fluid rounded-4 shadow-lg" alt="{{ $productoSeleccionado->nombre }}"
                                    style="max-height: 500px; object-fit: contain;">
                            </div>

                            <div class="col-lg-7">
                                <h1 class="display-5 fw-black text-warning mb-3">
                                    {{ $productoSeleccionado->nombre }}
                                </h1>

                                <div class="row g-3 mb-4">
                                    <div class="col-sm-6 modal-text-muted">
                                        <strong>Marca:</strong>
                                        <span class="fw-bold">
                                            {{ $productoSeleccionado->marca?->nombre ?? 'Sin marca' }}
                                        </span>
                                    </div>
                                    <div class="col-sm-6 modal-text-muted">
                                        <strong>Categoría:</strong>
                                        {{ $productoSeleccionado->categoria?->nombre ?? 'Sin categoría' }}
                                    </div>
                                    @if ($productoSeleccionado->modelo)
                                        <div class="col-sm-6 modal-text-muted">
                                            <strong>Modelo:</strong>
                                            {{ $productoSeleccionado->modelo->nombre }}
                                        </div>
                                    @endif
                                    <div class="col-sm-6 modal-text-muted">
                                        <strong>Código:</strong>
                                        {{ $productoSeleccionado->codigo ?? 'N/A' }}
                                    </div>
                                </div>

                                @if ($productoSeleccionado->descripcion)
                                    <div class="modal-desc-box rounded-4 p-4 mb-4">
                                        <p class="lead modal-text-body lh-lg mb-0">
                                            {{ $productoSeleccionado->descripcion }}
                                        </p>
                                    </div>
                                @endif

                                <div class="d-flex align-items-center gap-4 mb-4">
                                    <h1 class="text-warning fw-black mb-0">
                                        Bs {{ number_format($productoSeleccionado->precio, 2) }}
                                    </h1>
                                    @if ($productoSeleccionado->promociones->isNotEmpty())
                                        <span class="badge bg-danger fs-4 px-4 py-2">
                                            OFERTA ESPECIAL
                                        </span>
                                    @endif
                                </div>

                                <div class="d-flex flex-wrap gap-3">
                                    <button type="button" class="btn btn-warning btn-lg px-5 rounded-pill fw-bold"
                                        wire:click="agregarAlPedido({{ $productoSeleccionado->id }})">
                                        <i class="bi bi-cart-plus fs-4 me-3"></i>
                                        Agregar al carrito
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-lg px-5 rounded-pill"
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

    {{-- TOAST "PRODUCTO AGREGADO" --}}
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
            transform: scale(1.1);
        }

        .zoom-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 10;
        }

        .product-card:hover .zoom-overlay {
            opacity: 1;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .jayrita-modal .modal-content {
            border-radius: 1.5rem;
        }
    </style>
</div>
