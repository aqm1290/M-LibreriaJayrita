<div>
    @section('title', 'Marcas - ' . ($marca->nombre ?? 'Catálogo'))

    <div class="container pt-5 mt-5">

        {{-- TÍTULO + VOLVER --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <h1 class="display-6 fw-black text-warning mb-1">
                    Productos de {{ $marca->nombre }}
                </h1>
                <p class="mb-0 subtitulo-modelos">
                    Filtra por modelo, busca por nombre o código y explora los productos de esta marca.
                </p>
            </div>
            <div>
                <a href="{{ route('tienda.marcas') }}" class="btn btn-outline-warning rounded-pill">
                    ← Ver todas las marcas
                </a>
            </div>
        </div>

        {{-- FILTRO DE MODELOS + BUSCADOR + ORDEN --}}
        <div class="mb-4">
            <div class="d-flex flex-column flex-lg-row gap-3 align-items-lg-center justify-content-between">
                <div>
                    <h2 class="fw-black titulo-filtro-modelos mb-2">Modelos</h2>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="button" wire:click="setModelo(null)"
                            class="btn btn-sm rounded-pill chip-modelo {{ $modeloId === null ? 'chip-modelo--active' : '' }}">
                            Todos
                        </button>

                        @foreach ($modelos as $mod)
                            <button type="button" wire:click="setModelo({{ $mod->id }})"
                                class="btn btn-sm rounded-pill chip-modelo
                                        {{ $modeloId === $mod->id ? 'chip-modelo--active' : '' }}">
                                {{ $mod->nombre }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div class="d-flex flex-column flex-sm-row gap-2 align-items-stretch ms-lg-auto">
                    {{-- Buscador --}}
                    <div class="input-group buscador-marca">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" placeholder="Buscar por nombre o código..."
                            wire:model.debounce.400ms="busqueda">
                    </div>

                    {{-- Orden --}}
                    <select class="form-select select-orden" wire:model.live="orden">
                        <option value="relevancia">Ordenar por: Relevancia</option>
                        <option value="precio_asc">Precio: menor a mayor</option>
                        <option value="precio_desc">Precio: mayor a menor</option>
                        <option value="nombre_asc">Nombre A–Z</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- LAYOUT: GALERÍA + DETALLE --}}
        <div class="row g-4">
            {{-- GALERÍA --}}
            <div class="col-12 col-lg-6">
                <div class="row g-3">
                    @forelse ($productos as $producto)
                        @php
                            $stock = $producto->stock ?? 0;
                            $agotado = $stock <= 0;
                            $stockBajo = $stock > 0 && $stock <= 5;
                        @endphp
                        <div class="col-6 col-md-4">
                            <button type="button" wire:click="seleccionarProducto({{ $producto->id }})"
                                class="galeria-card w-100 {{ $productoSeleccionado && $producto->id === $productoSeleccionado->id ? 'galeria-card--active' : '' }}">
                                <div class="galeria-thumb tooltip-producto">
                                    @if ($producto->imagen_url)
                                        <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}">
                                    @else
                                        <div
                                            class="galeria-thumb-empty d-flex align-items-center justify-content-center">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    @endif

                                    {{-- Tooltip (nombre + precio) --}}
                                    <div class="tooltip-producto-content">
                                        <div class="fw-bold">{{ $producto->nombre }}</div>
                                        <div class="small">
                                            Bs {{ number_format($producto->precio, 2) }}
                                        </div>
                                    </div>

                                    {{-- Badge stock --}}
                                    @if ($agotado)
                                        <span class="badge-stock badge-stock--agotado">Agotado</span>
                                    @elseif ($stockBajo)
                                        <span class="badge-stock badge-stock--bajo">
                                            Quedan {{ $stock }} uds.
                                        </span>
                                    @endif
                                </div>
                            </button>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-emoji-frown display-1 text-warning mb-3"></i>
                            <h3 class="empty-title-text">No se encontraron productos.</h3>
                            <p class="empty-sub-text">Prueba con otra búsqueda o modelo.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $productos->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>

            {{-- DETALLE --}}
            <div class="col-12 col-lg-6">
                @if ($productoSeleccionado)
                    @php
                        $stockSel = $productoSeleccionado->stock ?? 0;
                        $agotadoSel = $stockSel <= 0;
                        $stockBajoSel = $stockSel > 0 && $stockSel <= 5;
                    @endphp

                    <div class="detalle-card p-4 p-lg-5 rounded-4 shadow-lg">
                        <div class="row g-4 align-items-center">
                            <div class="col-md-6">
                                <div class="detalle-img-wrapper mb-3 mb-md-0 position-relative">
                                    <img src="{{ $productoSeleccionado->imagen_url ?? asset('images/no-image.png') }}"
                                        alt="{{ $productoSeleccionado->nombre }}">

                                    @if ($agotadoSel)
                                        <span class="badge-stock badge-stock--agotado detalle-badge-top">Agotado</span>
                                    @elseif ($stockBajoSel)
                                        <span class="badge-stock badge-stock--bajo detalle-badge-top">
                                            ¡Últimas {{ $stockSel }} uds.!
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h2 class="detalle-title mb-3">
                                    {{ $productoSeleccionado->nombre }}
                                </h2>

                                <div class="small detalle-meta mb-3">
                                    <div><strong>Marca:</strong>
                                        {{ $productoSeleccionado->marca?->nombre ?? 'Sin marca' }}</div>
                                    <div><strong>Modelo:</strong>
                                        {{ $productoSeleccionado->modelo?->nombre ?? 'Sin modelo' }}</div>
                                    <div><strong>Categoría:</strong>
                                        {{ $productoSeleccionado->categoria?->nombre ?? 'Sin categoría' }}</div>
                                    <div><strong>Código:</strong> {{ $productoSeleccionado->codigo ?? 'N/A' }}</div>
                                    <div>
                                        <strong>Stock:</strong>
                                        <span class="{{ $agotadoSel ? 'text-danger fw-bold' : '' }}">
                                            {{ $stockSel }} uds.
                                        </span>
                                    </div>
                                </div>

                                @if ($productoSeleccionado->descripcion)
                                    <p class="detalle-desc mb-3">
                                        {{ $productoSeleccionado->descripcion }}
                                    </p>
                                @endif

                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <h3 class="detalle-price mb-0">
                                        Bs {{ number_format($productoSeleccionado->precio, 2) }}
                                    </h3>
                                    @if ($productoSeleccionado->promo)
                                        <span class="badge modal-badge-oferta">
                                            OFERTA ESPECIAL
                                        </span>
                                    @endif
                                </div>

                                <button type="button"
                                    class="btn btn-add-cart-modern btn-lg px-4 {{ $agotadoSel ? 'btn-add-cart-disabled' : '' }}"
                                    wire:click="agregarAlPedido({{ $productoSeleccionado->id }})"
                                    @if ($agotadoSel) disabled @endif>
                                    <i class="bi bi-cart-plus fs-4 me-2"></i>
                                    {{ $agotadoSel ? 'Agotado' : 'Añadir al carrito' }}
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="detalle-placeholder rounded-4 p-4 p-lg-5 text-center">
                        <i class="bi bi-mouse display-3 mb-3"></i>
                        <h4 class="mb-1">Selecciona un producto</h4>
                        <p class="mb-0">Haz clic en cualquiera de las imágenes de la izquierda para ver los detalles.
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- TOAST --}}
    <div x-data="{ showToast: false, mensaje: '' }" x-init="document.addEventListener('mostrar-toast', (e) => {
        mensaje = e.detail.mensaje || '¡Producto agregado al carrito!';
        showToast = true;
        setTimeout(() => showToast = false, 3500);
    });" x-show="showToast" x-transition
        class="position-fixed bottom-0 end-0 m-4 z-50">
        <div class="bg-success text-white px-5 py-4 rounded-4 shadow-2xl d-flex align-items-center gap-4">
            <i class="bi bi-check-circle-fill fs-3"></i>
            <strong x-text="mensaje" class="fs-5"></strong>
        </div>
    </div>

    {{-- ESTILOS --}}
    <style>
        .subtitulo-modelos {
            color: color-mix(in srgb, var(--default-color), transparent 40%);
        }

        .titulo-filtro-modelos {
            color: #facc15;
        }

        :root[data-theme="light"] .titulo-filtro-modelos {
            color: #f59e0b;
        }

        /* Chips modelos */
        .chip-modelo {
            border-radius: 999px;
            padding-inline: 1.1rem;
            border: 1px solid rgba(148, 163, 184, 0.6);
            background: transparent;
            color: #e5e7eb;
            font-weight: 600;
            font-size: 0.8rem;
        }

        :root[data-theme="light"] .chip-modelo {
            color: #374151;
            background: rgba(255, 255, 255, 0.8);
        }

        .chip-modelo--active {
            background: #facc15;
            border-color: #facc15;
            color: #111827;
        }

        /* Buscador + orden */
        .buscador-marca .input-group-text {
            background: rgba(15, 23, 42, 0.9);
            border-color: rgba(148, 163, 184, 0.5);
            color: #9ca3af;
        }

        .buscador-marca .form-control {
            background: rgba(15, 23, 42, 0.9);
            border-color: rgba(148, 163, 184, 0.5);
            color: #e5e7eb;
        }

        .buscador-marca .form-control::placeholder {
            color: #9ca3af;
        }

        .buscador-marca .form-control:focus {
            border-color: #fbbf24;
            box-shadow: 0 0 0 1px rgba(251, 191, 36, 0.6);
        }

        :root[data-theme="light"] .buscador-marca .input-group-text {
            background: #ffffff;
            color: #6b7280;
        }

        :root[data-theme="light"] .buscador-marca .form-control {
            background: #ffffff;
            color: #111827;
        }

        .select-orden {
            min-width: 220px;
            background: rgba(15, 23, 42, 0.9);
            border-color: rgba(148, 163, 184, 0.5);
            color: #e5e7eb;
        }

        :root[data-theme="light"] .select-orden {
            background: #ffffff;
            color: #111827;
        }

        .select-orden:focus {
            border-color: #fbbf24;
            box-shadow: 0 0 0 1px rgba(251, 191, 36, 0.6);
        }

        /* Galería */
        .galeria-card {
            border-radius: 1rem;
            padding: 0;
            border: 2px solid transparent;
            overflow: hidden;
            background: transparent;
            transition: all 0.2s ease;
        }

        .galeria-thumb {
            width: 100%;
            aspect-ratio: 1 / 1;
            background: #020617;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .galeria-thumb img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }

        .galeria-thumb-empty {
            width: 100%;
            height: 100%;
            background: #111827;
            color: #6b7280;
            font-size: 2rem;
        }

        :root[data-theme="light"] .galeria-thumb,
        :root[data-theme="light"] .galeria-thumb-empty {
            background: #f3f4f6;
            color: #9ca3af;
        }

        .galeria-card:hover .galeria-thumb img {
            transform: scale(1.05);
        }

        .galeria-card:hover {
            border-color: rgba(148, 163, 184, 0.8);
        }

        .galeria-card--active {
            border-color: #facc15;
            box-shadow: 0 0 0 2px rgba(250, 204, 21, 0.6);
        }

        /* Tooltip */
        .tooltip-producto {
            position: relative;
        }

        .tooltip-producto-content {
            position: absolute;
            left: 50%;
            bottom: 8px;
            transform: translateX(-50%);
            padding: 0.35rem 0.7rem;
            border-radius: 999px;
            background: rgba(15, 23, 42, 0.9);
            color: #e5e7eb;
            font-size: 0.7rem;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
            white-space: nowrap;
        }

        :root[data-theme="light"] .tooltip-producto-content {
            background: rgba(17, 24, 39, 0.95);
            color: #f9fafb;
        }

        .tooltip-producto:hover .tooltip-producto-content {
            opacity: 1;
            transform: translateX(-50%) translateY(-4px);
        }

        /* Badges de stock */
        .badge-stock {
            position: absolute;
            left: 8px;
            top: 8px;
            border-radius: 999px;
            padding: 0.15rem 0.6rem;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
        }

        .badge-stock--agotado {
            background: #ef4444;
            color: #ffffff;
        }

        .badge-stock--bajo {
            background: #f97316;
            color: #ffffff;
        }

        .detalle-badge-top {
            top: 10px;
            left: 10px;
        }

        /* Detalle */
        .detalle-card {
            background:
                radial-gradient(circle at top left, rgba(251, 191, 36, 0.16), transparent 55%),
                #020617;
            border: 1px solid rgba(148, 163, 184, 0.4);
            color: #e5e7eb;
        }

        :root[data-theme="light"] .detalle-card {
            background:
                radial-gradient(circle at top left, rgba(251, 191, 36, 0.10), transparent 55%),
                #ffffff;
            color: #111827;
        }

        .detalle-img-wrapper {
            background: #020617;
            border-radius: 1.25rem;
            padding: 1rem;
        }

        .detalle-img-wrapper img {
            width: 100%;
            height: 260px;
            object-fit: contain;
        }

        :root[data-theme="light"] .detalle-img-wrapper {
            background: #f9fafb;
        }

        .detalle-title {
            color: #facc15;
            font-weight: 800;
        }

        :root[data-theme="light"] .detalle-title {
            color: #f59e0b;
        }

        .detalle-meta {
            color: rgba(226, 232, 240, 0.85);
        }

        :root[data-theme="light"] .detalle-meta {
            color: #6b7280;
        }

        .detalle-desc {
            font-size: 0.95rem;
            color: #e5e7eb;
        }

        :root[data-theme="light"] .detalle-desc {
            color: #111827;
        }

        .detalle-price {
            color: #facc15;
            font-weight: 800;
        }

        :root[data-theme="light"] .detalle-price {
            color: #eab308;
        }

        .modal-badge-oferta {
            background: linear-gradient(135deg, #f97316, #ef4444);
            color: #ffffff;
            border-radius: 999px;
            letter-spacing: 0.06em;
        }

        .detalle-placeholder {
            border-radius: 1.5rem;
            border: 1px dashed rgba(148, 163, 184, 0.5);
            color: rgba(148, 163, 184, 0.9);
            background: rgba(15, 23, 42, 0.35);
        }

        :root[data-theme="light"] .detalle-placeholder {
            background: #f9fafb;
            color: #6b7280;
            border-color: rgba(148, 163, 184, 0.7);
        }

        .empty-title-text {
            color: #f9fafb;
        }

        .empty-sub-text {
            color: color-mix(in srgb, var(--default-color), transparent 45%);
        }

        :root[data-theme="light"] .empty-title-text {
            color: #111827;
        }

        @media (max-width: 992px) {
            .detalle-img-wrapper img {
                height: 220px;
            }
        }
    </style>
</div>
