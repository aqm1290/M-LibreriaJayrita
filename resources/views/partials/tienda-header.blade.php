<header id="header" class="header d-flex align-items-center fixed-top">
    <div
        class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        <a href="{{ route('tienda.home') }}" class="logo d-flex align-items-center me-auto me-xl-0">
            <img src="{{ asset('images/logo.jpg') }}" alt="Librería Jayrita" style="height: 50px;">
            <h1 class="sitename ms-3 text-warning fw-bold">LIBRERÍA "JAYRITA"</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="{{ route('tienda.home') }}" class="active">Inicio</a></li>
                <li><a href="{{ url('/Marcas') }}" class="{{ request()->is('ofertas') ? 'active' : '' }}">Marcas</a>
                </li>
                <li><a href="#portfolio">Productos</a>

                </li>




                <li><a href="{{ url('/contacto') }}"
                        class="{{ request()->is('contacto') ? 'active' : '' }}">Contacto</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a class="btn-getstarted rounded-pill px-4 py-2 fw-medium" href="{{ url('/catalogo') }}">
            Reserva ahora
        </a>

    </div>
</header>
