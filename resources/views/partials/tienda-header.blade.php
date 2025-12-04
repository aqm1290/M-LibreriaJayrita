<header id="header" class="header d-flex align-items-center fixed-top">
    <div
        class="header-container container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        <a href="{{ url('/') }}" class="logo d-flex align-items-center me-auto me-xl-0">
            <img src="{{ asset('images/logo.jpg') }}" alt="Librería Jayrita" style="height: 50px;">
            <h1 class="sitename ms-3 text-warning fw-bold">LIBRERÍA "JAYRITA"</h1>
        </a>

        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="#hero" class="active">Inicio</a></li>
                <li><a href="#services">Libros</a>
                <li><a href="#portfolio">Productos</a>

                </li>

                <li class="dropdown">
                    <a href="#"><span>Categorías</span> <i class="bi bi-chevron-down toggle-dropdown"></i></a>
                    <ul>
                        <li><a href="{{ url('/categoria/novelas') }}">Novelas</a></li>
                        <li><a href="{{ url('/categoria/infantiles') }}">Infantiles</a></li>
                        <li><a href="{{ url('/categoria/autoayuda') }}">Autoayuda</a></li>
                        <li><a href="{{ url('/categoria/academicos') }}">Académicos</a></li>
                        <li><a href="{{ url('/categoria/biografias') }}">Biografías</a></li>
                        <li><a href="{{ url('/categoria/escolares') }}">Escolares</a></li>
                        <li><a href="{{ url('/categoria/papeleria') }}">Papelería</a></li>
                    </ul>
                </li>

                <li><a href="{{ url('/ofertas') }}" class="{{ request()->is('ofertas') ? 'active' : '' }}">Ofertas</a>
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
