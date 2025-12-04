@extends('layouts.shop')

@section('title', 'Marca: ' . $marca->nombre)

@section('content')
    <main class="main py-5">
        <div class="container">

            <nav class="mb-3">
                <a href="{{ route('tienda.home') }}" class="text-warning">Inicio</a> /
                <span>Marca: {{ $marca->nombre }}</span>
            </nav>

            <div class="d-flex align-items-center mb-4">
                @if ($marca->logo_url)
                    <img src="{{ $marca->logo_url }}" alt="{{ $marca->nombre }}" style="height:64px;" class="me-3">
                @endif
                <div>
                    <h1 class="mb-1">{{ $marca->nombre }}</h1>
                    @if ($marca->descripcion)
                        <p class="text-muted mb-0">{{ $marca->descripcion }}</p>
                    @endif
                </div>
            </div>

            <h4 class="mb-3">Modelos de esta marca</h4>

            <div class="row g-4">
                @forelse($modelos as $modelo)
                    <div class="col-md-3">
                        <div class="card h-100 bg-dark text-white">
                            <div class="card-body">
                                <h5 class="card-title">{{ $modelo->nombre }}</h5>

                                @if ($modelo->descripcion ?? false)
                                    <p class="card-text small text-muted mb-1">
                                        {{ Str::limit($modelo->descripcion, 120) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Esta marca aún no tiene modelos registrados.</p>
                @endforelse
            </div>
        </div>
    </main>
@endsection
