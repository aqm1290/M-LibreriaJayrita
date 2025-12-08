<header id="header" class="header d-flex align-items-center fixed-top">
    <div
        class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        {{-- Logo --}}
        <a href="{{ route('tienda.home') }}" class="logo d-flex align-items-center me-auto me-xl-0">
            <img src="{{ asset('images/logo-jayrita.png') }}" alt="Librería Jayrita" style="height: 150px;">
            <h1 class="sitename ms-3 text-warning fw-bold">LIBRERÍA "JAYRITA"</h1>
        </a>

        {{-- Menú principal --}}
        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ route('tienda.home') }}"
                        class="{{ request()->is('tienda') ? 'active' : '' }}">Inicio</a></li>
                <li><a href="{{ url('/marcas') }}">Marcas</a></li>
                <li><a href="#productos">Productos</a></li>
                <li><a href="{{ url('/contacto') }}">Contacto</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        {{-- DERECHA: Mini carrito + Perfil cliente --}}
        <div class="d-flex align-items-center gap-3">

            {{-- Mini carrito (siempre visible) --}}
            <livewire:tienda.mini-carrito />

            {{-- Perfil del cliente logueado --}}
            @auth('cliente')
                <div class="dropdown">
                    <button
                        class="btn btn-success dropdown-toggle rounded-pill px-4 py-2 fw-bold d-flex align-items-center gap-2"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle fs-5"></i>
                        <span class="d-none d-md-inline">
                            {{ Str::limit(auth('cliente')->user()->nombre, 12) }}
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                        <li>
                            <a class="dropdown-item fw-semibold" href="{{ route('pedido.cliente') }}">
                                <i class="bi bi-bag-check me-2 text-warning"></i>
                                Mis pedidos
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-semibold" href="{{ route('cliente.perfil') ?? '#' }}">
                                <i class="bi bi-person-lines-fill me-2 text-primary"></i>
                                Mi perfil
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form method="POST" action="{{ route('cliente.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger fw-semibold">
                                    <i class="bi bi-box-arrow-right me-2"></i>
                                    Cerrar sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                {{-- No logueado: botones de login y registro --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('cliente.login') }}"
                        class="btn btn-outline-warning rounded-pill px-3 py-2 fw-semibold">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        <span class="d-none d-sm-inline">Ingresar</span>
                    </a>
                    <a href="{{ route('cliente.register') }}" class="btn btn-warning rounded-pill px-3 py-2 fw-semibold">
                        <i class="bi bi-person-plus me-1"></i>
                        <span class="d-none d-sm-inline">Registrarme</span>
                    </a>
                </div>
            @endauth

        </div>
    </div>
</header>
