<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- SI NO ESTÁ LOGUEADO → OBLIGA A REGISTRARSE --}}
            @guest('cliente')
                <div class="text-center py-5">
                    <i class="bi bi-person-lock display-1 text-warning mb-4"></i>
                    <h2 class="text-white fw-black mb-4">Necesitas iniciar sesión</h2>
                    <p class="lead text-white-50 mb-5">
                        Para reservar tu pedido debes estar registrado
                    </p>
                    <div class="d-flex gap-3 justify-content-center flex-wrap">
                        <a href="{{ route('cliente.register') }}"
                            class="btn btn-warning btn-lg px-5 py-4 rounded-pill shadow-lg fw-black">
                            <i class="bi bi-person-plus fs-3 me-3"></i>
                            Registrarme ahora
                        </a>
                        <a href="{{ route('cliente.login') }}"
                            class="btn btn-outline-warning btn-lg px-5 py-4 rounded-pill">
                            <i class="bi bi-box-arrow-in-right me-3"></i>
                            Ya tengo cuenta
                        </a>
                    </div>
                    <div class="mt-5">
                        <a href="{{ url('/catalogo') }}" class="btn btn-link text-white-50">
                            ← Volver al catálogo
                        </a>
                    </div>
                </div>
            @endguest

            {{-- SI ESTÁ LOGUEADO → MUESTRA EL PEDIDO --}}
            @auth('cliente')
                @if (session('mensaje'))
                    <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-lg mb-5" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <strong>{{ session('mensaje') }}</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="text-center mb-5">
                    <div
                        class="d-inline-flex align-items-center gap-3 bg-warning text-dark px-4 py-2 rounded-pill shadow-lg mb-4">
                        <i class="bi bi-bag-check-fill fs-3"></i>
                        <span class="fw-black fs-5 text-uppercase">Resumen de tu pedido</span>
                    </div>
                    <h1 class="display-4 fw-black text-white mb-3">Tu Pedido</h1>
                    <p class="lead text-white-50 mb-0">Revisa todo antes de reservar</p>
                </div>

                @if ($pedido && $pedido->items->count() > 0)
                    {{-- TABLA DE PRODUCTOS --}}
                    <div
                        class="card bg-dark border border-warning border-opacity-50 rounded-4 shadow-2xl overflow-hidden mb-5">
                        <div class="bg-warning text-dark p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h2 class="fw-black mb-1">Detalle del pedido</h2>
                                    <p class="mb-0 opacity-75">{{ $pedido->items->count() }}
                                        producto{{ $pedido->items->count() > 1 ? 's' : '' }}</p>
                                </div>
                                <div class="text-end">
                                    <span class="fs-1 fw-black text-success">
                                        Bs {{ number_format($pedido->total, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-dark mb-0 align-middle">
                                <thead class="bg-black text-warning">
                                    <tr>
                                        <th class="ps-4">Producto</th>
                                        <th class="text-center" style="width: 170px;">Cantidad</th>
                                        <th class="text-center">Precio</th>
                                        <th class="text-center">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pedido->items as $item)
                                        <tr class="border-bottom border-secondary">
                                            <td class="ps-4 py-3">
                                                <div class="fw-bold text-white">{{ $item->nombre_producto }}</div>
                                                <small class="text-white-50">Código:
                                                    {{ $item->producto?->codigo ?? '—' }}</small>
                                            </td>
                                            <td class="text-center py-3">
                                                <div class="d-flex justify-content-center align-items-center gap-2">
                                                    <button
                                                        wire:click="actualizarCantidad({{ $item->id }}, {{ $item->cantidad - 1 }})"
                                                        class="btn btn-outline-warning btn-sm rounded-circle"
                                                        @if ($item->cantidad <= 1) disabled @endif>
                                                        <i class="bi bi-dash"></i>
                                                    </button>
                                                    <span class="bg-warning text-dark fw-bold px-3 py-1 rounded-pill">
                                                        {{ $item->cantidad }}
                                                    </span>
                                                    <button
                                                        wire:click="actualizarCantidad({{ $item->id }}, {{ $item->cantidad + 1 }})"
                                                        class="btn btn-warning btn-sm rounded-circle">
                                                        <i class="bi bi-plus"></i>
                                                    </button>
                                                </div>
                                            </td>
                                            <td class="text-center text-white-50 py-3">
                                                Bs {{ number_format($item->precio_unitario, 2) }}
                                            </td>
                                            <td class="text-center text-warning fw-bold py-3 fs-5">
                                                Bs {{ number_format($item->subtotal, 2) }}
                                            </td>
                                            <td class="text-center py-3">
                                                <button wire:click="eliminarItem({{ $item->id }})"
                                                    class="btn btn-danger btn-sm rounded-circle">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="bg-black p-4 border-top border-warning border-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h3 class="text-white fw-black mb-0">Total a pagar</h3>
                                <h2 class="text-warning fw-black mb-0">
                                    Bs {{ number_format($pedido->total, 2) }}
                                </h2>
                            </div>
                        </div>
                    </div>

                    {{-- MENSAJE DE BIENVENIDA --}}
                    <div class="alert alert-success rounded-4 shadow mb-5">
                        <div class="d-flex align-items-center gap-3">
                            <i class="bi bi-person-check-fill fs-1"></i>
                            <div>
                                <h4 class="mb-1">¡Hola {{ auth('cliente')->user()->nombre }}!</h4>
                                <p class="mb-0">Tu pedido está listo para reservar</p>
                            </div>
                        </div>
                    </div>

                    {{-- BOTÓN RESERVAR --}}
                    <div class="text-center">
                        <button wire:click="confirmarPedido"
                            class="btn btn-success btn-lg px-5 py-4 rounded-pill shadow-lg fw-black d-inline-flex align-items-center gap-4"
                            style="font-size: 1.5rem;">
                            <i class="bi bi-check-circle-fill fs-1"></i>
                            <div class="text-start">
                                <div>RESERVAR PEDIDO</div>
                                <small class="fw-normal opacity-75">Te contactaremos en minutos</small>
                            </div>
                        </button>

                        <div class="mt-4">
                            <a href="{{ url('/catalogo') }}" class="btn btn-outline-warning btn-lg px-5">
                                <i class="bi bi-arrow-left me-2"></i> Seguir comprando
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-cart-x display-1 text-warning mb-4"></i>
                        <h2 class="text-white fw-black mb-3">Tu carrito está vacío</h2>
                        <a href="{{ url('/catalogo') }}" class="btn btn-warning btn-lg px-5 py-4 rounded-pill fw-bold">
                            <i class="bi bi-bag-plus fs-4 me-3"></i> Ir al catálogo
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </div>
</div>
