@extends('layouts.shop')

@section('title', 'Categoría: ' . $categoria->nombre)

@section('content')
    <main class="main">

        <!-- Título de página -->
        <div class="page-title dark-background" data-aos="fade">
            <div class="container position-relative">
                <h1>Categoría: {{ $categoria->nombre }}</h1>
                <p>{{ $categoria->descripcion ?? 'Productos de esta categoría.' }}</p>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="{{ route('tienda.home') }}">Inicio</a></li>
                        <li class="current">{{ $categoria->nombre }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Productos de la categoría -->
        <section class="section py-5">
            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-4">
                    @forelse($productos as $producto)
                        <div class="col-md-3">
                            <div class="card h-100 bg-dark text-white">
                                <img src="{{ $producto->imagen_url ?? asset('images/no-image.png') }}" class="card-img-top"
                                    alt="{{ $producto->nombre }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $producto->nombre }}</h5>

                                    @if ($producto->marca)
                                        <p class="small text-muted mb-1">
                                            Marca: {{ $producto->marca->nombre }}
                                        </p>
                                    @endif

                                    @if ($producto->modelo)
                                        <p class="small text-muted mb-1">
                                            Modelo: {{ $producto->modelo->nombre }}
                                        </p>
                                    @endif

                                    <p class="fw-bold mb-0">
                                        Bs {{ number_format($producto->precio, 2) }}
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent border-0">
                                    <a href="{{ route('tienda.producto-show', $producto->id) }}"
                                        class="btn btn-outline-warning btn-sm w-100">
                                        Ver producto
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">
                            Esta categoría no tiene productos registrados.
                        </p>
                    @endforelse
                </div>

            </div>
        </section>

    </main>
@endsection
