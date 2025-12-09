<!-- Header Glassmorphism Dorado - Versión FINAL 2025 -->
<header class="jayrita-header fixed-top" id="header">
    <div class="jayrita-header__inner container-fluid px-3 px-lg-5 py-2">
        <div class="d-flex align-items-center justify-content-between h-100">

            <!-- LOGO -->
            <a href="{{ route('tienda.home') }}" class="jayrita-logo d-flex align-items-center text-decoration-none">
                <img src="{{ asset('images/logo-jayrita.png') }}" alt="Librería Jayrita" class="jayrita-logo__img me-2">
                <span class="jayrita-logo__text fw-bold">LIBRERÍA JAYRITA</span>
            </a>

            <!-- MENÚ DESKTOP -->
            <nav class="jayrita-nav d-none d-lg-flex align-items-center gap-4">
                <a href="{{ route('tienda.home') }}"
                    class="{{ request()->is('tienda') || request()->routeIs('tienda.home') ? 'active' : '' }}">Inicio</a>
                <a href="{{ url('/catalogo') }}" class="{{ request()->is('catalogo') ? 'active' : '' }}">Catálogo</a>
                <a href="{{ route('tienda.marcas') }}"
                    class="{{ request()->routeIs('tienda.marcas') ? 'active' : '' }}">
                    Marcas
                </a> <a href="{{ url('/ofertas') }}" class="{{ request()->is('ofertas') ? 'active' : '' }}">Ofertas</a>
                <a href="{{ url('/contacto') }}" class="{{ request()->is('contacto') ? 'active' : '' }}">Contacto</a>
            </nav>

            <!-- ACCIONES DERECHA - Todo visible en móvil y escritorio -->
            <div class="jayrita-actions d-flex align-items-center gap-2 gap-lg-3">

                <!-- Switch de tema -->
                <button id="themeToggleBtn" class="jayrita-theme-toggle" type="button" aria-label="Cambiar tema">
                    <span class="sun">☀️</span>
                    <span class="moon">🌙</span>
                </button>

                <!-- MINI CARRITO → Siempre visible (móvil + escritorio) -->
                <div class="position-relative">
                    @livewire('tienda.mini-carrito')
                </div>

                <!-- PERFIL / LOGIN -->
                @auth('cliente')
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center jayrita-user-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="bi bi-person-circle fs-4"></i>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 py-3">
                            <li class="px-3 pb-2 border-bottom">
                                <small class="text-muted">Hola,</small><br>
                                <strong>{{ auth('cliente')->user()->nombre }}</strong>
                            </li>
                            <li><a class="dropdown-item py-2" href="{{ route('cliente.perfil') }}"><i
                                        class="bi bi-person me-2"></i> Mi Perfil</a></li>
                            {{-- <li><a class="dropdown-item py-2" href="{{ route('pedido.cliente') }}"><i
                                        class="bi bi-bag-check me-2"></i> Mis Pedidos</a></li> --}}
                            <li>
                                <hr class="dropdown-divider my-2">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('cliente.logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger py-2">
                                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <!-- Botón Ingresar (se esconde en móvil muy pequeño) -->
                    <a href="{{ route('cliente.login') }}"
                        class="btn jayrita-btn--login d-none d-sm-inline-flex align-items-center gap-1">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Ingresar
                    </a>
                @endauth

                <!-- HAMBURGUESA (solo móvil y tablet) -->
                <button class="jayrita-mobile-toggle d-lg-none" id="mobileToggle" aria-label="Menú">
                    <i class="bi bi-list fs-3"></i>
                </button>

            </div>
        </div>
    </div>
</header>

<!-- MENÚ MÓVIL (overlay) -->
<nav class="jayrita-mobile-menu d-lg-none" id="mobileMenu">
    <div class="jayrita-mobile-menu__inner p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="mb-0 fw-bold">Menú</h5>
            <button id="mobileClose" class="btn-close btn-close-white"></button>
        </div>

        <a href="{{ route('tienda.home') }}" class="d-block py-3 border-bottom">Inicio</a>
        <a href="{{ url('/catalogo') }}" class="d-block py-3 border-bottom">Catálogo</a>
        <a href="{{ url('/marcas') }}" class="d-block py-3 border-bottom">Marcas</a>
        <a href="{{ url('/ofertas') }}" class="d-block py-3 border-bottom">Ofertas</a>
        <a href="{{ url('/contacto') }}" class="d-block py-3 border-bottom">Contacto</a>

        @guest('cliente')
            <a href="{{ route('cliente.login') }}" class="btn jayrita-btn--login w-100 mt-4">
                Ingresar
            </a>
        @endguest
    </div>
</nav>
