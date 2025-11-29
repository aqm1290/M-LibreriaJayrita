<div class="container mx-auto p-4" wire:poll.30s>
    <!-- Filtros y búsqueda -->
    <div class="mb-4 flex flex-wrap gap-4">
        <input wire:model.live="search" type="text" placeholder="Buscar productos..." class="p-2 border rounded w-full md:w-1/3">

        <select wire:model="categoria" class="p-2 border rounded">
            <option value="">Todas categorías</option>
            @foreach($categorias as $cat)
                <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
            @endforeach
        </select>

        <select wire:model="sort" class="p-2 border rounded">
            <option value="nombre">Nombre</option>
            <option value="precio">Precio</option>
        </select>

        <select wire:model="direction" class="p-2 border rounded">
            <option value="asc">Asc</option>
            <option value="desc">Desc</option>
        </select>
    </div>

    <!-- Grid de productos -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        @foreach($productos as $producto)
            <div class="bg-white p-4 rounded shadow hover:shadow-lg transition">
                
                @if($producto->imagen)
                    <img src="{{ asset('storage/' . $producto->imagen) }}" class="w-full h-48 object-cover mb-2 rounded">
                @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center rounded">
                        Sin imagen
                    </div>
                @endif

                <h3 class="font-bold">{{ $producto->nombre }}</h3>
                <p class="text-green-600">Bs {{ number_format($producto->precio, 2) }}</p>
                <p class="text-sm text-gray-500">Stock: {{ $producto->stock }}</p>

                <a href="{{ route('tienda.detalle', $producto->slug ?? $producto->id) }}"
                   class="bg-blue-500 text-white px-4 py-2 rounded mt-2 block text-center">
                    Ver más
                </a>
            </div>
        @endforeach
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $productos->links() }}
    </div>
</div>
