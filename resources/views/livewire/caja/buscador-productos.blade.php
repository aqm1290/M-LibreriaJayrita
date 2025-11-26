<div>
    <!-- CONTENIDO PRINCIPAL DEL BUSCADOR -->
    <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
        <h2 class="text-4xl font-black text-gray-800 mb-8 text-center bg-gradient-to-r from-yellow-400 to-yellow-500 bg-clip-text text-transparent">
            BUSCADOR AVANZADO DE PRODUCTOS
        </h2>

        <!-- FILTROS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-5 mb-10">
            <input type="text" wire:model.live.debounce.300ms="nombre" placeholder="Nombre del producto..." class="px-6 py-5 text-lg rounded-2xl border-2 border-gray-200 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-300 transition">
            <input type="text" wire:model.live.debounce.300ms="codigo" placeholder="Código de barra" class="px-6 py-5 text-lg rounded-2xl border-2 border-gray-200 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-300 transition">

            <select wire:model.live="marca_id" class="px-6 py-5 text-lg rounded-2xl border-2 border-gray-200 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-300">
                <option value="">Todas las marcas</option>
                @foreach($marcas as $m)
                    <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                @endforeach
            </select>

            <select wire:model.live="categoria_id" class="px-6 py-5 text-lg rounded-2xl border-2 border-gray-200 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-300">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $c)
                    <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                @endforeach
            </select>

            <select wire:model.live="orden" class="px-6 py-5 text-lg rounded-2xl border-2 border-gray-200 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-300">
                <option value="nombre_asc">Nombre A-Z</option>
                <option value="nombre_desc">Nombre Z-A</option>
                <option value="precio_asc">Precio: menor a mayor</option>
                <option value="precio_desc">Precio: mayor a menor</option>
                <option value="stock_asc">Menos stock</option>
                <option value="stock_desc">Más stock</option>
            </select>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-5 mb-10">
            <input type="number" wire:model.live="stock_min" placeholder="Stock mínimo" class="px-6 py-5 rounded-2xl border-2 border-gray-200 focus:border-yellow-500">
            <input type="number" wire:model.live="stock_max" placeholder="Stock máximo" class="px-6 py-5 rounded-2xl border-2 border-gray-200 focus:border-yellow-500">
            <input type="number" step="0.01" wire:model.live="precio_min" placeholder="Precio desde" class="px-6 py-5 rounded-2xl border-2 border-gray-200 focus:border-yellow-500">
            <input type="number" step="0.01" wire:model.live="precio_max" placeholder="Precio hasta" class="px-6 py-5 rounded-2xl border-2 border-gray-200 focus:border-yellow-500">
        </div>

        <div class="text-center mb-10">
            <button wire:click="limpiarFiltros" class="px-12 py-5 bg-gradient-to-r from-gray-200 to-gray-300 hover:from-gray-300 hover:to-gray-400 text-gray-800 font-black text-xl rounded-2xl shadow-lg transform hover:scale-105 transition">
                LIMPIAR FILTROS
            </button>
        </div>

        <!-- TABLA -->
        <div class="overflow-x-auto rounded-2xl border border-gray-200 shadow-lg">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-800">
                    <tr>
                        <th class="px-6 py-5 text-left font-black">Foto</th>
                        <th class="px-6 py-5 text-left font-black">Producto</th>
                        <th class="px-6 py-5 text-left font-black">Código</th>
                        <th class="px-6 py-5 text-left font-black">Marca / Cat.</th>
                        <th class="px-6 py-5 text-center font-black">Precio</th>
                        <th class="px-6 py-5 text-center font-black">Stock</th>
                        <th class="px-6 py-5 text-center font-black">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($productos as $p)
                    <tr class="hover:bg-yellow-50 transition-all">
                        <td class="px-6 py-5">
                            @if($p->url_imagen)
                                <img src="{{ asset('storage/' . $p->url_imagen) }}" class="w-20 h-20 object-contain rounded-xl shadow-md">
                            @else
                                <div class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center border-2 border-dashed border-gray-300">
                                    <span class="text-gray-400 text-xs font-bold">Sin foto</span>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-5 font-bold text-gray-800">{{ $p->nombre }}</td>
                        <td class="px-6 py-5 font-mono text-gray-700">{{ $p->codigo }}</td>
                        <td class="px-6 py-5 text-sm">
                            <div class="font-semibold">{{ $p->marca?->nombre ?? '-' }}</div>
                            <div class="text-gray-500 text-xs">{{ $p->categoria?->nombre ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-5 text-center text-2xl font-black text-gray-800">
                            Bs {{ number_format($p->precio, 2) }}
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="@if($p->stock <= 5) text-red-600 @elseif($p->stock <= 15) text-yellow-600 @else text-green-600 @endif text-3xl font-black">
                                {{ $p->stock }}
                            </span>
                            @if($p->stock <= 5)<div class="text-xs text-red-600 font-bold mt-1">¡Stock bajo!</div>@endif
                        </td>
                        <td class="px-6 py-5 space-y-3">
                            @if(Route::currentRouteName() === 'caja.pos')
                                <button wire:click="agregarAlCarrito({{ $p->id }})"
                                        class="block w-full px-6 py-4 bg-gradient-to-r from-[#3483FA] to-blue-700 text-white font-black text-lg rounded-2xl shadow-xl hover:scale-105 transition">
                                    + AGREGAR
                                </button>
                            @endif

                            <button wire:click="abrirProducto({{ $p->id }})"
                                    class="block w-full px-6 py-4 bg-gradient-to-r from-yellow-400 to-yellow-500 text-gray-800 font-black text-lg rounded-2xl shadow-xl hover:scale-105 transition">
                                VER PRODUCTO
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-20 text-gray-400 text-2xl">
                            No se encontraron productos
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8 flex justify-center">
            {{ $productos->links('vendor.pagination.tailwind') }}
        </div>
    </div>

    <!-- MODAL (DENTRO DEL MISMO ROOT) -->
    @if($productoSeleccionado && $productoDetalle)
    <div class="fixed inset-0 bg-black bg-opacity-70 z-[9999] flex items-center justify-center p-4 backdrop-blur-md" wire:click="cerrarModal">
        <div class="bg-white rounded-3xl shadow-3xl max-w-4xl w-full max-h-screen overflow-y-auto" wire:click.stop>
            <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 p-8 rounded-t-3xl flex justify-between items-center">
                <h3 class="text-4xl font-black text-gray-800">DETALLE DEL PRODUCTO</h3>
                <button wire:click="cerrarModal" class="text-gray-800 hover:text-gray-900 text-5xl font-bold">×</button>
            </div>

            <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="text-center">
                    @if($productoDetalle->url_imagen)
                        <img src="{{ asset('storage/' . $productoDetalle->url_imagen) }}" class="max-w-full max-h-96 mx-auto rounded-2xl shadow-2xl">
                    @else
                        <div class="bg-gray-100 border-4 border-dashed border-gray-300 rounded-2xl w-full h-96 flex items-center justify-center">
                            <span class="text-4xl text-gray-400 font-bold">Sin imagen</span>
                        </div>
                    @endif
                </div>

                <div class="space-y-8">
                    <h1 class="text-4xl font-black text-gray-800">{{ $productoDetalle->nombre }}</h1>
                    <div class="text-lg text-gray-600">Código: <span class="font-mono font-bold text-xl">{{ $productoDetalle->codigo }}</span></div>
                    <div class="text-lg">Marca: <span class="font-bold">{{ $productoDetalle->marca?->nombre ?? 'Sin marca' }}</span></div>
                    <div class="text-lg">Categoría: <span class="font-bold">{{ $productoDetalle->categoria?->nombre ?? 'Sin categoría' }}</span></div>

                    <div class="text-6xl font-black text-[#3483FA] my-8">
                        Bs {{ number_format($productoDetalle->precio, 2) }}
                    </div>

                    <div class="text-3xl font-bold">
                        Stock: <span class="@if($productoDetalle->stock <= 5) text-red-600 @else text-green-600 @endif text-4xl">
                            {{ $productoDetalle->stock }} und
                        </span>
                    </div>

                    @if(Route::currentRouteName() === 'caja.pos')
                    <button wire:click="agregarAlCarrito({{ $productoDetalle->id }})"
                            class="w-full py-6 bg-gradient-to-r from-[#3483FA] to-blue-700 text-white font-black text-2xl rounded-2xl shadow-2xl hover:scale-105 transition">
                        + AGREGAR AL CARRITO
                    </button>
                    @endif
                </div>
            </div>

            <div class="p-8 text-center">
                <button wire:click="cerrarModal"
                        class="px-16 py-5 bg-gradient-to-r from-gray-200 to-gray-300 hover:from-gray-300 hover:to-gray-400 text-gray-800 font-black text-xl rounded-2xl shadow-lg">
                    CERRAR
                </button>
            </div>
        </div>
    </div>
    @endif
</div>