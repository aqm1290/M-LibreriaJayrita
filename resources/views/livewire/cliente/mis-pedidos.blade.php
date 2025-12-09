<div class="card jayrita-profile-card rounded-4 shadow-sm">
    <div class="card-body p-3 p-md-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="h5 fw-bold mb-0">Mis pedidos</h3>
        </div>

        @if ($pedidos->count())
            <div class="d-flex flex-column gap-3">

                @foreach ($pedidos as $pedido)
                    <div class="jayrita-order-card p-3 rounded-3">

                        {{-- Cabecera: código y fecha --}}
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
                            <div>
                                <span class="small text-muted d-block">Pedido</span>
                                <span class="fw-bold">#{{ $pedido->id }}</span>
                            </div>

                            <div class="text-end">
                                <span class="small text-muted d-block">Fecha</span>
                                <span>{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>

                        {{-- Estado + total con colores según estado --}}
                        <div class="d-flex flex-wrap justify-content-between align-items-center mb-2 gap-2">
                            <div>
                                <span class="small text-muted d-block">Estado</span>

                                @php
                                    $estado = strtolower($pedido->estado);
                                    $badgeClass = match ($estado) {
                                        'cancelado' => 'bg-danger',
                                        'entregado' => 'bg-success',
                                        'confirmado' => 'bg-warning text-dark',
                                        default => 'bg-secondary',
                                    };
                                @endphp

                                <span class="badge {{ $badgeClass }}">
                                    {{ ucfirst($pedido->estado) }}
                                </span>
                            </div>

                            <div class="text-end">
                                <span class="small text-muted d-block">Total</span>
                                <span class="fw-bold text-warning">
                                    Bs. {{ number_format($pedido->total, 2) }}
                                </span>
                            </div>
                        </div>

                        {{-- Método de pago (opcional) --}}
                        @if ($pedido->metodo_pago ?? false)
                            <div class="mb-2">
                                <span class="small text-muted">Método de pago:</span>
                                <span class="small">{{ $pedido->metodo_pago }}</span>
                            </div>
                        @endif

                        {{-- Dirección de envío (opcional) --}}
                        @if ($pedido->direccion_envio ?? false)
                            <div class="mb-2">
                                <span class="small text-muted">Envío a:</span>
                                <span class="small">{{ $pedido->direccion_envio }}</span>
                            </div>
                        @endif

                        {{-- Lista corta de productos --}}
                        @if ($pedido->items?->count())
                            <div class="mt-2">
                                <span class="small text-muted d-block mb-1">Productos:</span>
                                <ul class="list-unstyled mb-0 small">
                                    @foreach ($pedido->items as $item)
                                        <li class="d-flex justify-content-between">
                                            <span>{{ $item->producto->nombre ?? 'Producto' }} ×
                                                {{ $item->cantidad }}</span>
                                            <span>Bs. {{ number_format($item->subtotal, 2) }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif


                    </div>
                @endforeach

            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $pedidos->links('vendor.pagination.bootstrap-5') }}
            </div>
        @else
            <p class="mb-0 jayrita-profile-meta">
                Aún no tienes pedidos registrados.
            </p>
        @endif

    </div>
</div>
