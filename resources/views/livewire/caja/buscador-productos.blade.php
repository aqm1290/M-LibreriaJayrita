<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-amber-100 py-10 px-4 md:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- ENCABEZADO --}}
        <header class="text-center space-y-3">
            <p class="text-xs md:text-sm font-semibold tracking-[0.35em] text-amber-700 uppercase">
                Punto de venta
            </p>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-black bg-gradient-to-r from-yellow-400 via-amber-500 to-orange-500 bg-clip-text text-transparent">
                Buscador de productos
            </h1>
            <p class="text-xs md:text-sm text-amber-800/80">
                Encuentra rápido por texto, filtra por stock y precio, y ordena los resultados.
            </p>
        </header>

        {{-- PANEL DE FILTROS --}}
        <section class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-2xl border border-amber-100 p-6 md:p-8 space-y-6">

            {{-- BÚSQUEDA RÁPIDA --}}
            <div class="flex flex-col lg:flex-row gap-4 lg:items-end">
                <div class="flex-1 space-y-1">
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Buscar por nombre o descripción
                    </label>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="nombre"
                        placeholder="Ej: cuaderno, borrador, resma A4..."
                        class="w-full px-4 py-3 text-sm md:text-base rounded-2xl border-2 border-amber-100 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 bg-amber-50/60 transition"
                    >
                </div>

                <div class="w-full lg:w-72 space-y-1">
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Código de barras
                    </label>
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="codigo"
                        placeholder="Escanéalo o escríbelo"
                        class="w-full px-4 py-3 text-sm md:text-base rounded-2xl border-2 border-amber-100 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 bg-amber-50/40 transition"
                    >
                </div>
            </div>

            {{-- FILTROS AVANZADOS --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-5 pt-2 border-t border-amber-100">
                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Marca
                    </label>
                    <select
                        wire:model.live="marca_id"
                        class="w-full px-4 py-3 text-sm md:text-base rounded-2xl border-2 border-amber-100 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 bg-white transition"
                    >
                        <option value="">Todas las marcas</option>
                        @foreach($marcas as $m)
                            <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Categoría
                    </label>
                    <select
                        wire:model.live="categoria_id"
                        class="w-full px-4 py-3 text-sm md:text-base rounded-2xl border-2 border-amber-100 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 bg-white transition"
                    >
                        <option value="">Todas las categorías</option>
                        @foreach($categorias as $c)
                            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Stock (mín. / máx.)
                    </label>
                    <div class="flex gap-2">
                        <input
                            type="number"
                            wire:model.live="stock_min"
                            placeholder="Mín."
                            class="w-1/2 px-3 py-3 rounded-2xl border-2 border-amber-100 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 bg-amber-50/40 text-xs md:text-sm"
                        >
                        <input
                            type="number"
                            wire:model.live="stock_max"
                            placeholder="Máx."
                            class="w-1/2 px-3 py-3 rounded-2xl border-2 border-amber-100 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 bg-amber-50/40 text-xs md:text-sm"
                        >
                    </div>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Precio (desde / hasta)
                    </label>
                    <div class="flex gap-2">
                        <input
                            type="number"
                            step="0.01"
                            wire:model.live="precio_min"
                            placeholder="Desde"
                            class="w-1/2 px-3 py-3 rounded-2xl border-2 border-amber-100 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 bg-amber-50/40 text-xs md:text-sm"
                        >
                        <input
                            type="number"
                            step="0.01"
                            wire:model.live="precio_max"
                            placeholder="Hasta"
                            class="w-1/2 px-3 py-3 rounded-2xl border-2 border-amber-100 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 bg-amber-50/40 text-xs md:text-sm"
                        >
                    </div>
                </div>
            </div>

            {{-- ORDEN + RESUMEN --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pt-3 border-t border-amber-100">
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="space-y-1">
                        <label class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Ordenar por
                        </label>
                        <select
                            wire:model.live="orden"
                            class="px-4 py-2.5 text-xs md:text-sm rounded-2xl border-2 border-amber-100 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 bg-white transition"
                        >
                            <option value="nombre_asc">Nombre A-Z</option>
                            <option value="nombre_desc">Nombre Z-A</option>
                            <option value="precio_asc">Precio: menor a mayor</option>
                            <option value="precio_desc">Precio: mayor a menor</option>
                            <option value="stock_asc">Stock: menor a mayor</option>
                            <option value="stock_desc">Stock: mayor a menor</option>
                        </select>
                    </div>

                    <p class="text-xs md:text-sm text-slate-500">
                        Mostrando
                        <span class="font-semibold">{{ $productos->count() }}</span>
                        de
                        <span class="font-semibold">{{ $productos->total() }}</span>
                        productos.
                    </p>
                </div>

                <button
                    type="button"
                    wire:click="limpiarFiltros"
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs md:text-sm font-semibold shadow-sm hover:shadow-md transition"
                >
                    <span class="text-base">↺</span>
                    Limpiar todos los filtros
                </button>
            </div>
        </section>

        {{-- RESULTADOS --}}
        <section class="bg-white/90 backdrop-blur-sm rounded-3xl shadow-2xl border border-amber-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-yellow-400 to-amber-500 text-slate-900">
                        <tr>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[0.7rem] md:text-xs font-black">Foto</th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[0.7rem] md:text-xs font-black">Producto</th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[0.7rem] md:text-xs font-black">Código</th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-left text-[0.7rem] md:text-xs font-black">Marca / categoría</th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-center text-[0.7rem] md:text-xs font-black">Precio</th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-center text-[0.7rem] md:text-xs font-black">Stock</th>
                            <th class="px-4 md:px-6 py-3 md:py-4 text-center text-[0.7rem] md:text-xs font-black">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-amber-50">
                        @forelse($productos as $p)
                            <tr class="hover:bg-amber-50/70 transition-colors">
                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    @if($p->url_imagen)
                                        <img
                                            src="{{ asset('storage/' . $p->url_imagen) }}"
                                            class="w-14 h-14 md:w-16 md:h-16 object-contain rounded-xl shadow-sm bg-white"
                                        >
                                    @else
                                        <div class="w-14 h-14 md:w-16 md:h-16 bg-slate-50 rounded-xl flex items-center justify-center border-2 border-dashed border-slate-200">
                                            <span class="text-[0.6rem] md:text-xs text-slate-400 font-semibold">
                                                Sin foto
                                            </span>
                                        </div>
                                    @endif
                                </td>

                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    <div class="font-semibold text-sm md:text-base text-slate-900 line-clamp-2">
                                        {{ $p->nombre }}
                                    </div>
                                </td>

                                <td class="px-4 md:px-6 py-3 md:py-4">
                                    <span class="font-mono text-[0.7rem] md:text-xs text-slate-700">
                                        {{ $p->codigo }}
                                    </span>
                                </td>

                                <td class="px-4 md:px-6 py-3 md:py-4 text-[0.7rem] md:text-xs">
                                    <div class="font-semibold text-slate-800">
                                        {{ $p->marca?->nombre ?? '-' }}
                                    </div>
                                    <div class="text-slate-500">
                                        {{ $p->categoria?->nombre ?? '-' }}
                                    </div>
                                </td>

                                <td class="px-4 md:px-6 py-3 md:py-4 text-center">
                                    <span class="text-base md:text-xl font-black text-slate-900">
                                        Bs {{ number_format($p->precio, 2) }}
                                    </span>
                                </td>

                                <td class="px-4 md:px-6 py-3 md:py-4 text-center">
                                    @php
                                        $cls = $p->stock <= 5
                                            ? 'text-red-600'
                                            : ($p->stock <= 15 ? 'text-amber-600' : 'text-emerald-600');
                                    @endphp
                                    <span class="{{ $cls }} text-lg md:text-2xl font-black">
                                        {{ $p->stock }}
                                    </span>
                                    @if($p->stock <= 5)
                                        <div class="text-[0.6rem] md:text-[0.7rem] text-red-600 font-semibold mt-1">
                                            ¡Stock bajo!
                                        </div>
                                    @endif
                                </td>

                                <td class="px-4 md:px-6 py-3 md:py-4 space-y-1.5 md:space-y-2.5">
                                    @if(Route::currentRouteName() === 'caja.pos')
                                        <button
                                            type="button"
                                            wire:click="agregarAlCarrito({{ $p->id }})"
                                            class="block w-full px-3 py-2 md:py-2.5 bg-gradient-to-r from-[#3483FA] to-blue-700 text-white text-[0.7rem] md:text-xs font-black rounded-2xl shadow-md hover:shadow-lg hover:scale-[1.02] transition"
                                        >
                                            + Agregar
                                        </button>
                                    @endif

                                    <button
                                        type="button"
                                        wire:click="abrirProducto({{ $p->id }})"
                                        class="block w-full px-3 py-2 md:py-2.5 bg-gradient-to-r from-amber-300 to-amber-400 text-slate-900 text-[0.7rem] md:text-xs font-black rounded-2xl shadow-md hover:shadow-lg hover:scale-[1.02] transition"
                                    >
                                        Ver detalle
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-14">
                                    <div class="flex flex-col items-center justify-center gap-3 text-slate-400">
                                        <span class="text-5xl">🔍</span>
                                        <p class="text-base md:text-lg font-semibold">
                                            No se encontraron productos con los filtros actuales.
                                        </p>
                                        <button
                                            type="button"
                                            wire:click="limpiarFiltros"
                                            class="mt-1 px-6 py-2.5 rounded-2xl bg-amber-500 text-white text-xs md:text-sm font-semibold shadow-md hover:bg-amber-600 transition"
                                        >
                                            Quitar filtros y ver todo
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINACIÓN --}}
            <div class="px-4 py-4 border-t border-amber-100 flex justify-center">
                {{ $productos->links('vendor.pagination.tailwind') }}
            </div>
        </section>
    </div>

    {{-- MODAL DETALLE --}}
    @if($productoSeleccionado && $productoDetalle)
        <div
            class="fixed inset-0 bg-slate-900/70 backdrop-blur-sm z-[9999] flex items-center justify-center p-4"
            wire:click="cerrarModal"
        >
            <div
                class="bg-white rounded-3xl shadow-3xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
                wire:click.stop
            >
                <div class="bg-gradient-to-r from-amber-300 to-amber-400 px-6 md:px-10 py-6 rounded-t-3xl flex justify-between items-center">
                    <div>
                        <p class="text-xs font-semibold tracking-[0.35em] text-slate-700 uppercase">
                            Producto
                        </p>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-900">
                            Detalle del producto
                        </h3>
                    </div>
                    <button
                        type="button"
                        wire:click="cerrarModal"
                        class="text-slate-800 hover:text-black text-3xl md:text-4xl font-bold leading-none"
                    >
                        ×
                    </button>
                </div>

                <div class="p-6 md:p-10 grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10">
                    <div class="text-center">
                        @if($productoDetalle->url_imagen)
                            <img
                                src="{{ asset('storage/' . $productoDetalle->url_imagen) }}"
                                class="max-w-full max-h-96 mx-auto rounded-2xl shadow-2xl bg-white"
                            >
                        @else
                            <div class="bg-slate-50 border-4 border-dashed border-slate-200 rounded-2xl w-full h-72 md:h-96 flex items-center justify-center">
                                <span class="text-2xl md:text-3xl text-slate-300 font-bold">
                                    Sin imagen
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="space-y-4 md:space-y-6">
                        <h1 class="text-2xl md:text-3xl font-black text-slate-900">
                            {{ $productoDetalle->nombre }}
                        </h1>

                        <div class="space-y-1 text-sm md:text-base text-slate-700">
                            <p>
                                <span class="font-semibold">Código:</span>
                                <span class="font-mono">{{ $productoDetalle->codigo }}</span>
                            </p>
                            <p>
                                <span class="font-semibold">Marca:</span>
                                <span>{{ $productoDetalle->marca?->nombre ?? 'Sin marca' }}</span>
                            </p>
                            <p>
                                <span class="font-semibold">Categoría:</span>
                                <span>{{ $productoDetalle->categoria?->nombre ?? 'Sin categoría' }}</span>
                            </p>
                        </div>

                        <div class="text-3xl md:text-4xl font-black text-[#3483FA] my-4">
                            Bs {{ number_format($productoDetalle->precio, 2) }}
                        </div>

                        <div class="text-xl md:text-2xl font-semibold">
                            @php
                                $stockClass = $productoDetalle->stock <= 5 ? 'text-red-600' : 'text-emerald-600';
                            @endphp
                            Stock:
                            <span class="{{ $stockClass }} text-2xl md:text-3xl font-black">
                                {{ $productoDetalle->stock }} und
                            </span>
                        </div>

                        @if(Route::currentRouteName() === 'caja.pos')
                            <button
                                type="button"
                                wire:click="agregarAlCarrito({{ $productoDetalle->id }})"
                                class="w-full mt-4 py-4 md:py-5 bg-gradient-to-r from-[#3483FA] to-blue-700 text-white text-lg md:text-2xl font-black rounded-2xl shadow-2xl hover:scale-[1.03] transition"
                            >
                                + Agregar al carrito
                            </button>
                        @endif
                    </div>
                </div>

                <div class="px-6 md:px-10 pb-8 text-center">
                    <button
                        type="button"
                        wire:click="cerrarModal"
                        class="px-10 md:px-16 py-3.5 md:py-4 bg-slate-100 hover:bg-slate-200 text-slate-800 font-semibold text-sm md:text-base rounded-2xl shadow-md inline-flex items-center justify-center gap-2"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
