{{-- resources/views/livewire/tienda/mini-carrito.blade.php --}}
<div x-data="{ open: false }" @click.away="open = false" class="position-relative">

    {{-- BOTÓN DEL CARRITO (header) --}}
    <button @click.stop="open = !open"
        class="btn btn-warning rounded-circle shadow-lg position-relative d-flex align-items-center justify-content-center border-0"
        style="width: 36px; height: 36px;">
        <i class="bi bi-cart3 fs-4"></i>

        @if ($cantidadItems > 0)
            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white"
                style="font-size: 0.75rem; min-width: 18px; height: 18px; display: flex; align-items: center; justify-content: center;">
                {{ $cantidadItems }}
            </span>
        @endif
    </button>

    {{-- DROPDOWN DEL CARRITO --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform translate-y-10"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 transform translate-y-10"
        class="jayrita-mini-cart position-absolute end-0 mt-3 rounded-4 shadow-2xl border-0 overflow-hidden z-50"
        style="width: 380px; max-height: 85vh;" @click.stop>

        {{-- CABECERA AMARILLA --}}
        <div class="p-4 bg-warning text-dark">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="fw-black mb-0 fs-5">
                    <i class="bi bi-bag-check me-2"></i> Tu Carrito
                </h6>
                <span class="badge bg-dark rounded-pill fs-6 px-3">
                    {{ $cantidadItems }} {{ $cantidadItems === 1 ? 'ítem' : 'ítems' }}
                </span>
            </div>
        </div>

        @if ($pedido && $pedido->items->count() > 0)
            {{-- LISTA DE PRODUCTOS --}}
            <div class="overflow-y-auto jayrita-mini-cart-body">
                @foreach ($pedido->items as $item)
                    <div class="mini-cart-item">
                        <div class="d-flex gap-3 align-items-center">

                            {{-- Imagen / ícono --}}
                            <div class="flex-shrink-0">
                                <div
                                    class="mini-cart-thumb d-flex align-items-center justify-content-center overflow-hidden">
                                    @if ($item->producto && $item->producto->imagen_url)
                                        <img src="{{ $item->producto->imagen_url }}" alt="{{ $item->nombre_producto }}"
                                            class="img-fluid w-100 h-100 object-fit-cover">
                                    @else
                                        <i class="bi bi-box-seam"></i>
                                    @endif
                                </div>
                            </div>


                            {{-- Info producto --}}
                            <div class="flex-grow-1 min-width-0">
                                <h6 class="fw-bold mb-1 small mini-cart-name">
                                    {{ \Illuminate\Support\Str::limit($item->nombre_producto, 40) }}
                                </h6>
                                <div class="d-flex justify-content-between align-items-end">
                                    <small class="mini-cart-muted">
                                        {{ $item->cantidad }} × Bs {{ number_format($item->precio_unitario, 2) }}
                                    </small>
                                    <strong class="text-warning fs-6">
                                        Bs {{ number_format($item->subtotal, 2) }}
                                    </strong>
                                </div>
                            </div>

                            {{-- Botón quitar (siempre visible) --}}
                            <button type="button" wire:click="eliminarItem({{ $item->id }})"
                                class="btn btn-link p-0 ms-1 text-danger" title="Quitar">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- TOTAL + VER PEDIDO --}}
            <div class="jayrita-mini-cart-footer">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-black mb-0">Total a pagar</h5>
                    <h4 class="fw-black text-warning mb-0">
                        Bs {{ number_format($total, 2) }}
                    </h4>
                </div>

                <div class="d-grid">
                    <a href="{{ route('tienda.pedido') }}"
                        class="btn btn-warning btn-lg fw-bold rounded-pill shadow-sm">
                        <i class="bi bi-eye me-2"></i>
                        Ver Pedido Completo
                    </a>
                </div>


            </div>
        @else
            {{-- CARRITO VACÍO --}}
            <div class="p-5 text-center jayrita-mini-cart-empty">
                <i class="bi bi-cart-x mb-3 empty-icon"></i>
                <h6 class="mt-2 fw-bold empty-title">Tu carrito está vacío</h6>
                <p class="small empty-text">¡Agrega algunos productos increíbles!</p>
                <a href="{{ route('tienda.catalogo') }}" class="btn btn-outline-warning rounded-pill px-4 mt-3">
                    <i class="bi bi-arrow-right me-2"></i>
                    Ver Catálogo
                </a>
            </div>
        @endif
    </div>

    {{-- ESTILOS MINI CARRITO (usa tus variables data-theme) --}}
    <style>
        .jayrita-mini-cart {
            background: var(--surface-color);
            border: 1px solid rgba(148, 163, 184, 0.35);
        }

        .jayrita-mini-cart-body {
            max-height: 55vh;
        }

        .mini-cart-item {
            padding: 1rem 1.25rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.15);
            transition: background 0.2s ease;
        }

        .mini-cart-item:hover {
            background: color-mix(in srgb, var(--accent-color), transparent 93%);
        }

        .mini-cart-thumb {
            width: 52px;
            height: 52px;
            border-radius: 16px;
            border: 2px dashed rgba(148, 163, 184, 0.6);
            background: color-mix(in srgb, var(--surface-color), transparent 10%);
            font-size: 1.4rem;
            color: rgba(148, 163, 184, 0.9);
        }

        .mini-cart-name {
            color: var(--default-color);
        }

        .mini-cart-muted {
            color: color-mix(in srgb, var(--default-color), transparent 45%);
        }

        .jayrita-mini-cart-footer {
            padding: 1.25rem 1.25rem 1.5rem;
            background: var(--contrast-color);
            color: var(--default-color);
        }

        .jayrita-mini-cart-empty {
            background: var(--surface-color);
        }

        .jayrita-mini-cart-empty .empty-icon {
            font-size: 3.5rem;
            color: color-mix(in srgb, var(--default-color), transparent 40%);
        }

        .jayrita-mini-cart-empty .empty-title {
            color: var(--default-color);
        }

        .jayrita-mini-cart-empty .empty-text {
            color: color-mix(in srgb, var(--default-color), transparent 45%);
        }

        /* Responsive: en móviles ocupa casi todo el ancho */
        @media (max-width: 576px) {
            .jayrita-mini-cart {
                right: 0;
                left: 0;
                margin-inline: 0.75rem;
                width: auto !important;
            }
        }
    </style>
</div>
