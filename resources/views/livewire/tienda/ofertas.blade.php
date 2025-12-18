@section('title', 'Ofertas')

<div>
    <div class="container pt-5 mt-5">

        {{-- TÍTULO + VOLVER --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="display-5 fw-black titulo-ofertas mb-2">
                    Ofertas especiales
                </h1>
                <p class="lead subtitulo-ofertas mb-0">
                    Productos con promociones activas por tiempo limitado.
                </p>
            </div>
            <div>
                <a href="{{ route('tienda.home') }}" class="btn btn-outline-warning btn-lg rounded-pill">
                    ← Volver a la tienda
                </a>
            </div>
        </div>

        {{-- ORDEN + RESUMEN STOCK --}}
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3 gap-2">
            <div class="small d-flex flex-wrap align-items-center gap-3">
                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2">
                    <span class="me-1">●</span> En stock: {{ $enStock }}
                </span>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-2">
                    <span class="me-1">●</span> Agotados: {{ $agotados }}
                </span>
                <span class="small text-white-50 ms-1">
                    Total: {{ $total }}
                </span>
            </div>

            <div class="d-flex justify-content-end">
                <select class="form-select select-orden-ofertas w-auto" wire:model="orden">
                    <option value="descuento_desc">Mayor descuento</option>
                    <option value="precio_asc">Precio: menor a mayor</option>
                    <option value="precio_desc">Precio: mayor a menor</option>
                    <option value="nombre_asc">Nombre A–Z</option>
                </select>
            </div>
        </div>

        {{-- GRID DE OFERTAS --}}
        <section class="row g-4">
            @forelse ($productos as $item)
                @php
                    /** @var \App\Models\Producto $producto */
                    $producto = $item['modelo'];
                    $precioOriginal = $item['precio_original'];
                    $precioOferta = $item['precio_oferta'];
                    $descuentoPorc = $item['descuento_porc'];
                    $stock = $producto->stock ?? 0;
                    $agotado = $stock <= 0;

                    /** @var \App\Models\Promocion|null $promo */
                    $promo = $item['promocion'] ?? null;
                    $es2x1 = $promo && !empty($promo->products_2x1);
                    $esCompraRegalo = $promo && !empty($promo->products_compra) && !empty($promo->products_regalo);
                @endphp

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card-modern position-relative h-100">
                        <div class="product-thumb position-relative">
                            @if ($producto->imagen_url)
                                <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}">
                            @else
                                <div class="d-flex align-items-center justify-content-center w-100 h-100 text-white-50">
                                    <i class="bi bi-image fs-1"></i>
                                </div>
                            @endif

                            {{-- Badge de tipo de promo --}}
                            @if ($es2x1)
                                <span class="product-badge-promo">
                                    2x1
                                </span>
                            @elseif ($esCompraRegalo)
                                <span class="product-badge-promo">
                                    Compra X lleva Y
                                </span>
                            @else
                                <span class="product-badge-promo">
                                    -{{ $descuentoPorc }}%
                                </span>
                            @endif

                            {{-- Cinta AGOTADO --}}
                            @if ($agotado)
                                <span class="agotado-badge">
                                    Agotado
                                </span>
                            @endif
                        </div>

                        <div class="p-3 d-flex flex-column gap-1">
                            <p class="small text-white-50 product-meta mb-1">
                                {{ $producto->marca?->nombre ?? 'Sin marca' }}
                                @if ($producto->categoria?->nombre)
                                    · {{ $producto->categoria->nombre }}
                                @endif
                            </p>

                            <h3 class="product-title mb-1">
                                {{ $producto->nombre }}
                            </h3>

                            {{-- Texto según tipo de promo --}}
                            @if ($es2x1)
                                <p class="small text-success mb-1">
                                    Promoción 2x1 en productos seleccionados.
                                </p>
                            @elseif ($esCompraRegalo)
                                <p class="small text-success mb-1">
                                    Compra X y llévate Y de regalo.
                                </p>
                            @endif

                            <div class="d-flex align-items-baseline gap-2 mb-2">
                                <span class="product-price">
                                    Bs {{ number_format($precioOferta, 2) }}
                                </span>
                                <span class="text-white-50 text-decoration-line-through small">
                                    Bs {{ number_format($precioOriginal, 2) }}
                                </span>
                            </div>

                            <p class="small text-white-50 mb-2">
                                Stock: {{ $stock }} uds.
                            </p>

                            <div class="mt-auto">
                                <button type="button"
                                    class="btn btn-add-cart-modern w-100 {{ $agotado ? 'btn-add-cart-disabled' : '' }}"
                                    wire:click="$dispatch('agregar-producto-al-pedido', {{ $producto->id }})"
                                    @if ($agotado) disabled @endif>
                                    <span class="icon">
                                        <i class="bi bi-cart-plus"></i>
                                    </span>
                                    <span>
                                        {{ $agotado ? 'Agotado' : 'Añadir al carrito' }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-emoji-frown display-1 text-warning mb-3"></i>
                    <h3 class="texto-empty-ofertas">No hay ofertas activas.</h3>
                    <p class="texto-empty-sub">
                        Vuelve más tarde para descubrir nuevas promociones.
                    </p>
                </div>
            @endforelse
        </section>

        {{-- PAGINACIÓN --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $productos->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>

    {{-- ESTILOS --}}
    <style>
        .titulo-ofertas {
            color: #facc15;
        }

        :root[data-theme="light"] .titulo-ofertas {
            color: #f59e0b;
        }

        .subtitulo-ofertas {
            color: color-mix(in srgb, var(--default-color), transparent 40%);
        }

        .select-orden-ofertas {
            background: rgba(15, 23, 42, 0.95);
            border-color: rgba(148, 163, 184, 0.5);
            color: #e5e7eb;
        }

        :root[data-theme="light"] .select-orden-ofertas {
            background: #ffffff;
            color: #111827;
        }

        .product-card-modern {
            border-radius: 1.25rem;
            background:
                radial-gradient(circle at top left, rgba(251, 191, 36, 0.15), transparent 55%),
                #020617;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
            border: 1px solid rgba(148, 163, 184, 0.3);
        }

        :root[data-theme="light"] .product-card-modern {
            background:
                radial-gradient(circle at top left, rgba(251, 191, 36, 0.12), transparent 55%),
                #ffffff;
        }

        .product-card-modern:hover {
            transform: translateY(-8px);
            box-shadow: 0 22px 45px rgba(15, 23, 42, 0.75);
        }

        .product-thumb {
            height: 190px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.35);
            overflow: hidden;
        }

        .product-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.4s ease, opacity 0.3s ease;
        }

        .product-card-modern:hover .product-thumb img {
            transform: scale(1.05);
        }

        .product-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: #f9fafb;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        :root[data-theme="light"] .product-title {
            color: #111827;
        }

        .product-meta {
            color: #9ca3af;
        }

        :root[data-theme="light"] .product-meta {
            color: #4b5563;
        }

        .product-price {
            font-size: 1.1rem;
            font-weight: 800;
            color: #facc15;
        }

        .product-badge-promo {
            position: absolute;
            top: 10px;
            left: 10px;
            background: linear-gradient(135deg, #f97316, #ef4444);
            color: #fff;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.25rem 0.75rem;
            border-radius: 999px;
            box-shadow: 0 0 0 1px rgba(15, 23, 42, 0.6);
            z-index: 6;
        }

        .agotado-badge {
            position: absolute;
            top: 12px;
            right: -40px;
            transform: rotate(40deg);
            background: #ef4444;
            color: #fff;
            font-size: 0.7rem;
            font-weight: 800;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            padding: 0.25rem 2.5rem;
            z-index: 5;
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.5);
        }

        .btn-add-cart-modern {
            border-radius: 999px;
            border: none;
            background: linear-gradient(135deg, #facc15, #f97316);
            color: #111827;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            padding: 0.45rem 0.9rem;
            font-weight: 700;
            font-size: 0.9rem;
            box-shadow: 0 12px 25px rgba(248, 181, 0, 0.45);
            width: 100%;
            transition: transform 0.18s ease, box-shadow 0.18s ease, filter 0.18s ease;
        }

        .btn-add-cart-modern:hover:not(.btn-add-cart-disabled) {
            transform: translateY(-2px);
            box-shadow: 0 18px 40px rgba(248, 181, 0, 0.6);
            filter: brightness(1.05);
        }

        .btn-add-cart-disabled {
            background: #4b5563 !important;
            color: #d1d5db !important;
            box-shadow: none !important;
            cursor: not-allowed !important;
        }

        .texto-empty-ofertas {
            color: #f9fafb;
        }

        .texto-empty-sub {
            color: #9ca3af;
        }

        :root[data-theme="light"] .texto-empty-ofertas {
            color: #111827;
        }

        @media (max-width: 576px) {
            .product-thumb {
                height: 160px;
            }
        }
    </style>
</div>
