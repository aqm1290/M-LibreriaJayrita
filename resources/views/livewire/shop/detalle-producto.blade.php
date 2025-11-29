<div class="container mx-auto p-4">
    <div class="flex flex-col md:flex-row gap-4">

        <div class="md:w-1/2">
            <img src="{{ asset('storage/' . $producto->imagen) }}" class="w-full rounded">
        </div>

        <div class="md:w-1/2">
            <h1 class="text-2xl font-bold">{{ $producto->nombre }}</h1>
            <p class="text-xl text-green-600 mt-2">Bs {{ number_format($producto->precio, 2) }}</p>
            <p class="mt-2">Stock: <strong>{{ $producto->stock > 0 ? 'Disponible' : 'Agotado' }}</strong></p>
            <p class="mt-4 text-gray-700">{{ $producto->descripcion ?? 'Sin descripción' }}</p>

            <button wire:click="agregarCarrito"
                class="bg-green-500 text-white px-4 py-2 rounded mt-4">
                Agregar al carrito
            </button>
        </div>

    </div>
</div>
