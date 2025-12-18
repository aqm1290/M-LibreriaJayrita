<div x-data>
    @section('title', 'Catálogo de productos')

    <div class="container pt-5 mt-5">
        <div>
            <h1 class="h1 fw-black text-warning mb-1">
                Catálogo de productos
            </h1>

        </div>
        <div class="row g-4">
            {{-- ===================== SIDEBAR FILTROS - ESTILO MEJORADO COMO IMAGEN ===================== --}}
            <div class="col-lg-3">
                <div class="catalogo-filtros-elegante rounded-4 p-4 shadow-lg position-sticky top-0">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0 fw-bold filtros-title">
                            <i class="bi bi-funnel-fill me-2"></i>Filtros
                        </h5>

                        <button class="btn btn-sm filtros-btn-limpiar rounded-pill px-3 text-xs"
                            wire:click="limpiarFiltros">
                            Limpiar
                        </button>
                    </div>

                    {{-- Búsqueda --}}
                    <div class="mb-4">
                        <label class="form-label filtros-label">Buscar</label>
                        <div class="input-group filtros-input-group">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control" placeholder="Nombre o código..."
                                wire:model.live="busqueda">
                        </div>
                    </div>

                    {{-- Precio Slider --}}
                    <div class="mb-4">
                        <label class="form-label filtros-label">Precio</label>
                        <div class="filtros-precio-box">
                            <div class="d-flex justify-content-between filtros-precio-range-labels">
                                <span>Bs {{ $precioMin ?? 0 }}</span>
                                <span>Bs {{ $precioMax ?? 500 }}</span>
                            </div>
                            <input type="range" class="form-range filtros-range" min="0" max="500"
                                step="5" wire:model.live="precioMin" value="{{ $precioMin ?? 0 }}">
                            <input type="range" class="form-range filtros-range mt-3" min="0" max="500"
                                step="5" wire:model.live="precioMax" value="{{ $precioMax ?? 500 }}">
                        </div>
                    </div>

                    {{-- Marca --}}
                    {{-- Marca --}}
                    <div class="mb-4" x-data="{ mostrarTodasMarcas: false }">
                        <label class="form-label filtros-label">Marca</label>
                        <div class="filtros-chips">
                            @foreach ($marcas as $index => $m)
                                @php $count = $conteoMarcas[$m->id] ?? 0; @endphp
                                <template x-if="mostrarTodasMarcas || {{ $index }} < 5">
                                    <label
                                        class="filtro-chip {{ in_array($m->id, $marcasSeleccionadas) ? 'filtro-chip--active' : '' }}">
                                        <input type="checkbox" wire:model.live="marcasSeleccionadas"
                                            value="{{ $m->id }}" class="d-none">
                                        <span class="filtro-chip-text">
                                            {{ $m->nombre }}
                                            <span class="filtro-chip-count">({{ $count }})</span>
                                        </span>
                                    </label>
                                </template>
                            @endforeach
                        </div>

                        @if ($marcas->count() > 5)
                            <button type="button" class="btn btn-link p-0 mt-1 small text-decoration-none"
                                x-on:click="mostrarTodasMarcas = !mostrarTodasMarcas">
                                <span x-show="!mostrarTodasMarcas">Ver más ({{ $marcas->count() - 5 }})</span>
                                <span x-show="mostrarTodasMarcas">Ver menos</span>
                            </button>
                        @endif
                    </div>

                    {{-- Categoría --}}
                    <div class="mb-4" x-data="{ mostrarTodasCategorias: false }">
                        <label class="form-label filtros-label">Categoría</label>
                        <div class="filtros-chips">
                            @foreach ($categorias as $index => $cat)
                                @php $count = $conteoCategorias[$cat->id] ?? 0; @endphp
                                <template x-if="mostrarTodasCategorias || {{ $index }} < 5">
                                    <label
                                        class="filtro-chip {{ in_array($cat->id, $categoriasSeleccionadas) ? 'filtro-chip--active' : '' }}">
                                        <input type="checkbox" wire:model.live="categoriasSeleccionadas"
                                            value="{{ $cat->id }}" class="d-none">
                                        <span class="filtro-chip-text">
                                            {{ $cat->nombre }}
                                            <span class="filtro-chip-count">({{ $count }})</span>
                                        </span>
                                    </label>
                                </template>
                            @endforeach
                        </div>

                        @if ($categorias->count() > 5)
                            <button type="button" class="btn btn-link p-0 mt-1 small text-decoration-none"
                                x-on:click="mostrarTodasCategorias = !mostrarTodasCategorias">
                                <span x-show="!mostrarTodasCategorias">Ver más ({{ $categorias->count() - 5 }})</span>
                                <span x-show="mostrarTodasCategorias">Ver menos</span>
                            </button>
                        @endif
                    </div>


                    {{-- Modelos --}}
                    <div class="mb-4">
                        <label class="form-label filtros-label">Modelo</label>
                        <div class="filtros-chips">
                            @foreach ($modelos as $mod)
                                @php $count = $conteoModelos[$mod->id] ?? 0; @endphp
                                <label
                                    class="filtro-chip {{ in_array($mod->id, $modelosSeleccionados) ? 'filtro-chip--active' : '' }}">
                                    <input type="checkbox" wire:model.live="modelosSeleccionados"
                                        value="{{ $mod->id }}" class="d-none">
                                    <span class="filtro-chip-text">
                                        {{ $mod->nombre }}
                                        <span class="filtro-chip-count">({{ $count }})</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </div>



                    {{-- Disponibilidad --}}
                    <div class="mb-4">
                        <label class="form-label filtros-label">Disponibilidad</label>
                        <div class="filtros-radio-list">
                            <label class="filtros-radio-item">
                                <input type="radio" wire:model.live="disponibilidad" value="cualquiera"
                                    class="form-check-input me-2">
                                <span>Cualquiera</span>
                            </label>
                            <label class="filtros-radio-item">
                                <input type="radio" wire:model.live="disponibilidad" value="disponible"
                                    class="form-check-input me-2">
                                <span>Disponible <span
                                        class="text-success ms-1">({{ $totalDisponibles }})</span></span>
                            </label>
                            <label class="filtros-radio-item">
                                <input type="radio" wire:model.live="disponibilidad" value="agotado"
                                    class="form-check-input me-2">
                                <span>Agotado <span class="text-danger ms-1">({{ $totalAgotados }})</span></span>
                            </label>
                        </div>
                    </div>

                    {{-- En Oferta --}}
                    <div class="mb-2">
                        <label class="form-label filtros-label">Ofertas</label>
                        <div class="form-check form-switch filtros-switch">
                            <input class="form-check-input" type="checkbox" wire:model.live="enOferta"
                                id="enOferta">
                            <label class="form-check-label" for="enOferta">
                                En oferta <span class="text-warning ms-1">({{ $totalEnOferta }})</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ===================== GRID DE PRODUCTOS ===================== --}}
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-3">

                </div>

                <div class="row g-4">
                    @forelse ($productos as $producto)
                        @php
                            $stock = $producto->stock ?? 0;
                            $agotado = $stock <= 0;
                            $stockBajo = $stock > 0 && $stock <= 5;
                        @endphp

                        <div class="col-6 col-md-4 col-xl-3">
                            <div
                                class="card product-card-modern h-100 border-0 overflow-hidden position-relative {{ $agotado ? 'opacity-75' : '' }}">
                                {{-- Imagen --}}
                                <div class="product-thumb bg-black position-relative">
                                    {{-- Badge AGOTADO o ÚLTIMAS UNIDADES --}}
                                    @if ($agotado)
                                        <div class="agotado-badge">Agotado</div>
                                    @elseif ($stockBajo)
                                        <div class="agotado-badge agotado-badge--warning">
                                            ¡Últimas {{ $stock }} uds.!
                                        </div>
                                    @endif

                                    @if ($producto->imagen_url)
                                        <img src="{{ $producto->imagen_url }}" alt="{{ $producto->nombre }}"
                                            class="w-100 h-100">
                                    @else
                                        <div
                                            class="d-flex align-items-center justify-content-center h-100 text-white-50">
                                            <i class="bi bi-image fs-1"></i>
                                        </div>
                                    @endif

                                    {{-- Badge PROMO --}}
                                    @if ($producto->promo)
                                        <span class="product-badge-promo">
                                            Promoción
                                        </span>
                                    @endif

                                    {{-- Overlay acciones rápidas --}}
                                    <div class="product-quick-actions">
                                        <button type="button" class="btn btn-warning btn-sm rounded-circle shadow"
                                            wire:click="abrirModal({{ $producto->id }})">
                                            <i class="bi bi-zoom-in"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Body --}}
                                <div class="card-body d-flex flex-column p-3">
                                    <h3 class="product-title mb-1">
                                        {{ $producto->nombre }}
                                    </h3>
                                    <p class="product-meta small mb-2">
                                        {{ $producto->marca?->nombre ?? 'Sin marca' }}
                                        · {{ $producto->categoria?->nombre ?? 'Sin categoría' }}
                                        @if ($producto->modelo?->nombre)
                                            · {{ $producto->modelo->nombre }}
                                        @endif
                                    </p>

                                    <p class="small mb-1">
                                        <span class="text-white-50">
                                            Stock: <strong>{{ $stock }}</strong> uds.
                                        </span>
                                    </p>

                                    @if ($producto->promo)
                                        <p class="small text-warning fw-semibold mb-2">
                                            {{ $producto->promo->nombre }}
                                        </p>
                                    @endif

                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-end mb-2">
                                            <span class="product-price">
                                                Bs {{ number_format($producto->precio, 2) }}
                                            </span>
                                        </div>

                                        <div class="d-grid gap-2">
                                            <button type="button"
                                                class="btn btn-add-cart-modern {{ $agotado ? 'btn-add-cart-disabled' : '' }}"
                                                wire:click="agregarAlPedido({{ $producto->id }})"
                                                @if ($agotado) disabled @endif>
                                                <span class="icon">
                                                    <i class="bi bi-cart-plus"></i>
                                                </span>
                                                <span class="label">
                                                    {{ $agotado ? 'Agotado' : 'Añadir al carrito' }}
                                                </span>
                                            </button>

                                            <button type="button"
                                                class="btn btn-outline-secondary btn-sm rounded-pill"
                                                wire:click="abrirModal({{ $producto->id }})">
                                                <i class="bi bi-eye me-1"></i> Ver detalle
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="bi bi-emoji-frown display-1 text-warning mb-3"></i>
                            <h3 class="text-white fw-bold">No se encontraron productos.</h3>
                            <p class="text-white-50">
                                Ajusta los filtros o intenta con otra búsqueda.
                            </p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    {{ $productos->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    {{-- =============== MODAL DETALLE =============== --}}
    @if (!is_null($productoSeleccionado))
        @php
            $stockModal = $productoSeleccionado->stock ?? 0;
            $agotadoModal = $stockModal <= 0;
            $stockBajoModal = $stockModal > 0 && $stockModal <= 5;
        @endphp

        <div class="modal fade show d-block jayrita-modal" tabindex="-1" wire:keydown.escape="cerrarModal"
            style="background: rgba(0,0,0,0.92); z-index: 9999;">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content border-0 rounded-4 shadow-lg">
                    <div class="modal-header border-0">
                        <button wire:click="cerrarModal" class="btn-close"></button>
                    </div>

                    <div class="modal-body p-4 p-lg-5 modal-product-body">
                        <div class="row g-5 align-items-center">
                            <div class="col-lg-5">
                                <div class="position-relative">
                                    <img src="{{ $productoSeleccionado->imagen_url ?? asset('images/no-image.png') }}"
                                        class="img-fluid rounded-4 shadow-lg w-100"
                                        style="max-height: 480px; object-fit: contain;"
                                        alt="{{ $productoSeleccionado->nombre }}">

                                    @if ($productoSeleccionado->promo)
                                        <span class="product-badge-promo position-absolute top-3 start-3">
                                            Promoción
                                        </span>
                                    @endif

                                    @if ($agotadoModal)
                                        <div class="agotado-badge position-absolute top-50 start-50 translate-middle">
                                            Agotado
                                        </div>
                                    @elseif ($stockBajoModal)
                                        <div class="agotado-badge position-absolute top-50 start-50 translate-middle"
                                            style="background: #f97316;">
                                            ¡Últimas {{ $stockModal }} uds.!
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-7">
                                <h1 class="display-6 fw-black text-warning mb-3">
                                    {{ $productoSeleccionado->nombre }}
                                </h1>

                                <div class="row g-3 mb-4 small text-white-50">
                                    <div class="col-sm-6">
                                        <strong>Marca:</strong>
                                        <span class="fw-semibold text-white ms-1">
                                            {{ $productoSeleccionado->marca?->nombre ?? 'Sin marca' }}
                                        </span>
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Categoría:</strong>
                                        <span class="ms-1">
                                            {{ $productoSeleccionado->categoria?->nombre ?? 'Sin categoría' }}
                                        </span>
                                    </div>
                                    @if ($productoSeleccionado->modelo)
                                        <div class="col-sm-6">
                                            <strong>Modelo:</strong>
                                            <span class="ms-1">
                                                {{ $productoSeleccionado->modelo->nombre }}
                                            </span>
                                        </div>
                                    @endif
                                    <div class="col-sm-6">
                                        <strong>Código:</strong>
                                        <span class="ms-1">
                                            {{ $productoSeleccionado->codigo ?? 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="col-sm-6">
                                        <strong>Stock:</strong>
                                        <span class="ms-1 {{ $agotadoModal ? 'text-danger' : '' }}">
                                            {{ $stockModal }} uds.
                                        </span>
                                    </div>
                                </div>

                                @if ($productoSeleccionado->descripcion)
                                    <div class="modal-desc-box rounded-4 p-4 mb-4">
                                        <p class="modal-text-body lh-lg mb-0">
                                            {{ $productoSeleccionado->descripcion }}
                                        </p>
                                    </div>
                                @endif

                                <div class="d-flex align-items-center gap-3 mb-4">
                                    <h1 class="text-warning fw-black mb-0">
                                        Bs {{ number_format($productoSeleccionado->precio, 2) }}
                                    </h1>

                                    @if ($productoSeleccionado->promo)
                                        <span class="badge bg-danger fs-6 px-3 py-2">
                                            OFERTA ESPECIAL
                                        </span>
                                    @endif
                                </div>

                                <div class="d-flex flex-wrap gap-3">
                                    <button type="button"
                                        class="btn btn-add-cart-modern btn-lg px-4 {{ $agotadoModal ? 'btn-add-cart-disabled' : '' }}"
                                        wire:click="agregarAlPedido({{ $productoSeleccionado->id }})"
                                        @if ($agotadoModal) disabled @endif>
                                        <span class="icon">
                                            <i class="bi bi-cart-plus fs-4"></i>
                                        </span>
                                        <span class="label">
                                            {{ $agotadoModal ? 'Agotado' : 'Añadir al carrito' }}
                                        </span>
                                    </button>

                                    <button type="button" class="btn btn-outline-secondary btn-lg rounded-pill px-4"
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

    {{-- TOAST --}}
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
</div>
