{{-- resources/views/livewire/tienda/mini-carrito.blade.php --}}
<div x-data="{ open: false }" @click.away="open = false" class="position-relative">

    <!-- BOTÓN DEL CARRITO (el que está en el header) -->
    <button @click.stop="open = !open"
        class="btn btn-warning rounded-circle shadow-lg position-relative d-flex align-items-center justify-content-center border-0"
        style="width: 56px; height: 56px;">
        <i class="bi bi-cart3 fs-4"></i>

        @if ($cantidadItems > 0)
            <span
                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white"
                style="font-size: 0.75rem; min-width: 22px; height: 22px; display: flex; align-items: center; justify-content: center;">
                {{ $cantidadItems }}
            </span>
        @endif
    </button>

    <!-- DROPDOWN DEL CARRITO -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform translate-y-10"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0 transform translate-y-10"
        class="position-absolute end-0 mt-3 bg-white rounded-4 shadow-2xl border-0 overflow-hidden z-50"
        style="width: 380px; max-height: 85vh;" @click.stop>

        <!-- CABECERA AMARILLA -->
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
            <!-- LISTA DE PRODUCTOS -->
            <div class="overflow-y-auto" style="max-height: 55vh;">
                @foreach ($pedido->items as $item)
                    <div class="p-4 border-bottom border-gray-100 hover:bg-gray-50 transition-all">
                        <div class="d-flex gap-3">
                            <!-- Imagen placeholder (puedes mejorarlo después con la real) -->
                            <div class="flex-shrink-0">
                                <div
                                    class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 d-flex align-items-center justify-content-center">
                                    <i class="bi bi-box-seam text-gray-400"></i>
                                </div>
                            </div>

                            <div class="flex-grow-1 min-width-0">
                                <h6 class="fw-bold text-dark mb-1 small">
                                    {{ \Illuminate\Support\Str::limit($item->nombre_producto, 40) }}
                                </h6>
                                <div class="d-flex justify-content-between align-items-end">
                                    <small class="text-muted">
                                        {{ $item->cantidad }} × Bs {{ number_format($item->precio_unitario, 2) }}
                                    </small>
                                    <strong class="text-warning fs-6">
                                        Bs {{ number_format($item->subtotal, 2) }}
                                    </strong>
                                </div>
                            </div>

                            <!-- Botón quitar (opcional en mini-carrito) -->
                            <button wire:click="eliminarItem({{ $item->id }})" wire:then="open = false"
                                class="text-danger opacity-0 hover:opacity-100 transition-opacity ms-2">
                                <i class="bi bi-x-circle-fill"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- PIE CON TOTAL Y BOTÓN -->
            <div class="p-4 bg-dark text-white">
                <div class="d-flex justify-content-between align-items-center mb-4">
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

                <div class="text-center mt-3">
                    <small class="text-white-50">
                        <i class="bi bi-whatsapp text-success me-1"></i>
                        Te enviaremos el pedido por WhatsApp
                    </small>
                </div>
            </div>
        @else
            <!-- CARRITO VACÍO -->
            <div class="p-5 text-center">
                <i class="bi bi-cart-x text-muted" style="font-size: 4.5rem;"></i>
                <h6 class="mt-4 text-muted fw-bold">Tu carrito está vacío</h6>
                <p class="text-muted small">¡Agrega algunos productos increíbles!</p>
                <a href="{{ url('/catalogo') }}" class="btn btn-outline-warning rounded-pill px-4 mt-3">
                    <i class="bi bi-arrow-right me-2"></i>
                    Ver Catálogo
                </a>
            </div>
        @endif
    </div>
</div>
