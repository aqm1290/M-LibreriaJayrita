@extends('layouts.shop')

@section('title', 'Modelo: ' . $modelo->nombre)

@section('content')
    <main class="main">

        <div class="page-title dark-background" data-aos="fade">
            <div class="container position-relative">
                <h1>Modelo: {{ $modelo->nombre }}</h1>
                <p>{{ $modelo->descripcion ?? 'Productos de este modelo.' }}</p>
                <nav class="breadcrumbs">
                    <ol>
                        <li><a href="{{ route('tienda.home') }}">Inicio</a></li>
                        @if ($modelo->marca)
                            <li><a href="{{ route('marcas.show', $modelo->marca->id) }}">
                                    {{ $modelo->marca->nombre }}
                                </a></li>
                        @endif
                        <li class="current">{{ $modelo->nombre }}</li>
                    </ol>
                </nav>
            </div>
        </div>

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

                                    @if ($producto->categoria)
                                        <p class="small text-muted mb-1">
                                            Categoría: {{ $producto->categoria->nombre }}
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
                            Este modelo no tiene productos registrados.
                        </p>
                    @endforelse
                </div>

            </div>
        </section>

    </main>
@endsection
