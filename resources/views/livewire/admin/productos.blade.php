<div class="min-h-screen bg-slate-50 py-10 px-6">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- CABECERA -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                    Gestión de Productos
                </h1>
                <p class="mt-2 text-slate-600 text-base md:text-lg">
                    Administra el catálogo completo de Librería Jayrita.
                </p>
            </div>

            <button
                wire:click="crear"
                class="inline-flex items-center justify-center gap-3 px-6 py-3 md:px-8 md:py-4
                       bg-gradient-to-r from-slate-900 to-slate-700 text-white font-semibold md:font-bold
                       rounded-2xl shadow-lg hover:shadow-xl hover:from-black hover:to-slate-900
                       transform hover:-translate-y-0.5 hover:scale-[1.02] transition">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                          d="M12 4v16m8-8H4"/>
                </svg>
                <span class="text-sm md:text-base tracking-wide">NUEVO PRODUCTO</span>
            </button>
        </div>

        <!--  BUSCADOR  -->
        <div class="space-y-6">
            <!-- BUSCADOR + FILTROS -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="lg:col-span-2">
                    <input
                        type="text"
                        wire:model.live.debounce.500ms="search"
                        placeholder="Buscar por nombre o código..."
                        class="w-full px-5 py-3.5 text-base border border-slate-200 bg-white/80
                            rounded-2xl shadow-sm focus:border-slate-900 focus:ring-2
                            focus:ring-slate-200 outline-none transition"
                    >
                </div>

                <select wire:model.live="filtroCategoria"
                        class="px-5 py-3.5 border border-slate-200 rounded-2xl bg-white/80 focus:border-slate-900 focus:ring-2 focus:ring-slate-200">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $c)
                        <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                    @endforeach
                </select>

                <select wire:model.live="filtroMarca"
                        class="px-5 py-3.5 border border-slate-200 rounded-2xl bg-white/80 focus:border-slate-900 focus:ring-2 focus:ring-slate-200">
                    <option value="">Todas las marcas</option>
                    @foreach($marcas as $m)
                        <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <!-- CHECKBOXES + LIMPIAR -->
            <div class="flex flex-wrap items-center gap-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="soloStockBajo"
                        class="w-5 h-5 text-orange-600 rounded focus:ring-orange-200">
                    <span class="text-sm font-medium text-slate-700">Solo stock bajo (≤ 5)</span>
                </label>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" wire:model.live="mostrarInactivos"
                        class="w-5 h-5 rounded">
                    <span class="text-sm font-medium text-slate-700">Mostrar inactivos</span>
                </label>

                <button wire:click="limpiarFiltros"
                        class="ml-auto px-5 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-medium rounded-xl transition">
                    Limpiar filtros
                </button>
            </div>
        </div>



        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-slate-100">

            <!-- TABLA NORMAL  -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm min-w-[900px]">
                    <thead class="bg-slate-900 text-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">Imagen</th>
                            <th class="px-6 py-4 text-left font-semibold">Nombre</th>
                            <th class="px-6 py-4 text-left font-semibold">Código</th>
                            <th class="px-6 py-4 text-left font-semibold">Precio</th>
                            <th class="px-6 py-4 text-left font-semibold">Stock</th>
                            <th class="px-6 py-4 text-left font-semibold">Marca</th>
                            <th class="px-6 py-4 text-left font-semibold">Estado</th>
                            <th class="px-6 py-4 text-right font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($productos as $p)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-6 py-4">
                                    <img src="{{ $p->imagen_url ?? asset('images/no-image.png') }}" alt="{{ $p->nombre }}"
                                        class="w-14 h-14 rounded-xl object-cover shadow border border-slate-200">
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-900">{{ $p->nombre }}</div>
                                    @if($p->descripcion)
                                        <div class="text-xs text-slate-500">{{ Str::limit($p->descripcion, 45) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-mono text-xs text-slate-600">{{ $p->codigo }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-sm font-semibold">
                                        Bs {{ number_format($p->precio, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($p->stock == 0)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-rose-600 text-white text-xs font-bold">Sin stock</span>
                                    @elseif($p->stock <= 5)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-amber-500 text-white text-xs font-bold ring-2 ring-amber-300/70">{{ $p->stock }} und</span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">{{ $p->stock }} und</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-700">{{ $p->marca->nombre ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold {{ $p->activo ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $p->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-2">

                                        <!-- VER -->
                                        <button wire:click="ver({{ $p->id }})"
                                                class="p-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 transition"
                                                title="Ver detalles">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>

                                        <!-- EDITAR -->
                                        <button wire:click="editar({{ $p->id }})"
                                                class="p-2.5 rounded-lg bg-amber-100 hover:bg-amber-200 transition text-amber-800"
                                                title="Editar producto">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        <!-- DESACTIVAR / REACTIVAR -->
                                        <button wire:click="confirmarEliminar({{ $p->id }})"
                                                class="p-2.5 rounded-lg {{ $p->activo ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white transition"
                                                title="{{ $p->activo ? 'Desactivar' : 'Reactivar' }} producto">
                                            @if($p->activo)
                                                <!-- X (desactivar) -->
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @else
                                                <!-- CHECK (reactivar) -->
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            @endif
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="py-16 text-center text-slate-500">No se encontraron productos</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- VISTA EN MÓVIL -->
            <div class="lg:hidden">
                @forelse($productos as $p)
                    <div class="border-b border-slate-100 p-6 hover:bg-slate-50/60 transition last:border-0">
                        <div class="flex gap-4">
                            <img src="{{ $p->imagen_url ?? asset('images/no-image.png') }}"
                                class="w-20 h-20 rounded-xl object-cover shadow flex-shrink-0">

                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-slate-900 truncate">{{ $p->nombre }}</h3>
                                <p class="text-xs text-slate-600 mt-1">{{ $p->marca->nombre ?? '-' }} • {{ $p->codigo }}</p>

                                <div class="flex flex-wrap gap-2 mt-3">
                                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-sm font-bold">
                                        Bs {{ number_format($p->precio, 2) }}
                                    </span>
                                    @if($p->stock == 0)
                                        <span class="px-3 py-1 rounded-full bg-rose-600 text-white text-xs font-bold">Sin stock</span>
                                    @elseif($p->stock <= 5)
                                        <span class="px-3 py-1 rounded-full bg-amber-500 text-white text-xs font-bold">{{ $p->stock }} und</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">{{ $p->stock }} und</span>
                                    @endif
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $p->activo ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">
                                        {{ $p->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </div>

                                <div class="flex gap-3 mt-4">
                                    <button wire:click="ver({{ $p->id }})" class="p-2.5 rounded-lg bg-slate-100 hover:bg-slate-200 transition">
                                        <i data-lucide="eye" class="w-5 h-5"></i>
                                    </button>
                                    <button wire:click="editar({{ $p->id }})" class="p-2.5 rounded-lg bg-amber-100 hover:bg-amber-200 transition">
                                        <i data-lucide="pencil" class="w-5 h-5"></i>
                                    </button>
                                    <button wire:click="confirmarEliminar({{ $p->id }})"
                                            class="p-2.5 rounded-lg {{ $p->activo ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white transition">
                                        <i data-lucide="{{ $p->activo ? 'x-circle' : 'check-circle' }}" class="w-5 h-5"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-slate-500 text-lg">
                        No se encontraron productos
                    </div>
                @endforelse
            </div>
        </div>

       













        <!-- PAGINACIÓN EN ESPAÑOL BONITA -->
        <div class="px-6 py-5 bg-white border-t border-slate-100">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Texto de resultados -->
                <div class="text-sm text-slate-600">
                    Mostrando 
                    <span class="font-bold text-slate-900">{{ $productos->firstItem() }}</span> 
                    al 
                    <span class="font-bold text-slate-900">{{ $productos->lastItem() }}</span> 
                    de 
                    <span class="font-bold text-slate-900">{{ $productos->total() }}</span> 
                    resultados
                </div>

                <!-- Paginación con íconos y estilo pro -->
                <div class="flex items-center gap-2">
                    {{ $productos->onEachSide(1)->links('vendor.pagination.tailwind-espanol') }}
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL CREAR / EDITAR -->
    @if($modal)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[92vh] overflow-y-auto border border-slate-100">
            <div class="bg-slate-900 text-white px-8 py-6 rounded-t-2xl flex items-center justify-between">
                <div>
                    <h2 class="text-2xl md:text-3xl font-black tracking-tight">
                        {{ $productoId ? 'Editar producto' : 'Nuevo producto' }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-300">
                        Completa la información del producto.
                    </p>
                </div>
                <button wire:click="cerrarModal" class="text-slate-300 hover:text-white">
                    ✕
                </button>
            </div>

            <div class="p-8 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">
                            Nombre *
                        </label>
                        <input type="text" wire:model="nombre"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-900">
                        @error('nombre')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">
                            Código *
                        </label>
                        <input type="text" wire:model="codigo"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-900">
                        @error('codigo')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">
                            Precio venta (Bs) *
                        </label>
                        <input type="number" step="0.01" wire:model="precio"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-900">
                        @error('precio')
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">
                            Costo compra (Bs) *
                        </label>
                        <input type="number" step="0.01" wire:model="costo_compra"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-900">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">
                            Stock *
                        </label>
                        <input type="number" wire:model="stock"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-900">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">
                            Categoría *
                        </label>
                        <select wire:model="categoria_id"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-900">
                            <option value="">Seleccione...</option>
                            @foreach($categorias as $c)
                                <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">
                            Marca *
                        </label>
                        <select wire:model.live="marca_id"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-900">
                            <option value="">Seleccione...</option>
                            @foreach($marcas as $m)
                                <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">
                            Modelo *
                        </label>
                        <select wire:model="modelo_id"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-white
                                       focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-900">
                            <option value="">Seleccione...</option>
                            @foreach($modelos as $mo)
                                @if(!$marca_id || $mo->marca_id == $marca_id)
                                    <option value="{{ $mo->id }}">{{ $mo->nombre }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">
                            Color
                        </label>
                        <input type="text" wire:model="color"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-900">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">
                            Tipo
                        </label>
                        <input type="text" wire:model="tipo"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm
                                      focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-900">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">
                        Descripción
                    </label>
                    <textarea
                        wire:model="descripcion"
                        rows="3"
                        class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm
                               focus:outline-none focus:ring-2 focus:ring-slate-200 focus:border-slate-900 resize-none"></textarea>
                </div>

                <!-- IMAGEN -->
                <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start.window="uploading = true; progress = 0" x-on:livewire-upload-finish.window="uploading = false" x-on:livewire-upload-error.window="uploading = false" x-on:livewire-upload-progress.window="progress = $event.detail.progress" class="space-y-6">
                    <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide">
                        Imagen del producto
                    </label>

                    <input type="file" wire:model="imagen" accept="image/*"
                        class="block w-full text-sm text-slate-700 border-2 border-dashed border-slate-300 rounded-xl cursor-pointer bg-slate-50 p-4 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-black">

                    <div class="mt-6 flex justify-center">
                        @if($imagen)
                            <!-- SUBIENDO (Alpine maneja el estado) -->
                            <div x-show="uploading" class="w-72 h-72 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl border-4 border-dashed border-slate-300 flex flex-col items-center justify-center space-y-6 shadow-2xl">
                                <div class="relative">
                                    <div class="animate-spin rounded-full h-24 w-24 border-12 border-slate-300 border-t-slate-900"></div>
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="text-5xl font-black text-slate-900" x-text="progress + '%'"></span>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-slate-800">Subiendo imagen...</p>
                                    <p class="text-slate-600">No cierres esta ventana</p>
                                </div>
                            </div>

                            <!-- YA TERMINÓ (Livewire lo maneja) -->
                            <div x-show="!uploading" class="w-72 h-72">
                                <img src="{{ $imagen->temporaryUrl() }}"
                                    class="w-full h-full object-cover rounded-3xl shadow-2xl border-8 border-white ring-8 ring-emerald-400">
                            </div>

                        @elseif($url_imagen ?? false)
                            <div class="relative group">
                                <img src="{{ $productoSeleccionado?->imagen_url ?? asset('storage/'.$url_imagen) }}"
                                    class="w-72 h-72 object-cover rounded-3xl shadow-2xl border-8 border-white ring-4 ring-slate-200">
                                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition rounded-3xl flex items-center justify-center">
                                    <p class="text-white font-bold text-xl">Imagen actual</p>
                                </div>
                            </div>

                        @else
                            <div class="w-72 h-72 flex flex-col items-center justify-center rounded-3xl border-4 border-dashed border-slate-300 bg-slate-50">
                                <svg class="w-24 h-24 text-slate-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-slate-500 font-bold text-lg">Sin imagen</p>
                                <p class="text-xs text-slate-400 mt-1">Sube una imagen del producto (máx. 5 MB)</p>
                            </div>
                        @endif
                    </div>
                </div>






            </div>

            <div class="flex justify-end gap-4 px-8 py-6 border-t border-slate-100 bg-slate-50/80 rounded-b-2xl">
                <button
                    wire:click="cerrarModal"
                    class="px-6 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 text-sm font-medium
                           hover:bg-slate-100 hover:border-slate-400 transition">
                    Cancelar
                </button>
                <button
                    wire:click="guardar"
                    class="px-8 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-semibold
                           hover:bg-black shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition">
                    Guardar producto
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL VER -->
    @if($modalVer && $productoSeleccionado)
    <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-8">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto border border-slate-100">
            <div class="bg-slate-900 text-white p-8 rounded-t-2xl text-center">
                <h2 class="text-3xl md:text-4xl font-black">Detalle del producto</h2>
            </div>
            <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="text-center">
                    <img src="{{ $productoSeleccionado->imagen_url }}"
                         class="w-full max-h-80 object-cover rounded-2xl shadow-2xl">
                </div>
                <div class="space-y-4 text-sm">
                    <h3 class="text-xl font-semibold text-slate-900">
                        {{ $productoSeleccionado->nombre }}
                    </h3>
                    <p class="text-slate-500">
                        Código: <span class="font-mono">{{ $productoSeleccionado->codigo }}</span>
                    </p>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-slate-500 text-xs uppercase tracking-wide">Precio venta</p>
                            <p class="font-semibold text-emerald-600">
                                Bs {{ number_format($productoSeleccionado->precio, 2) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs uppercase tracking-wide">Costo compra</p>
                            <p class="font-semibold text-slate-800">
                                Bs {{ number_format($productoSeleccionado->costo_compra, 2) }}
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs uppercase tracking-wide">Stock</p>
                            <p class="font-semibold">
                                {{ $productoSeleccionado->stock }} und
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs uppercase tracking-wide">Marca / Modelo</p>
                            <p class="font-semibold">
                                {{ $productoSeleccionado->marca->nombre ?? '-' }}
                                /
                                {{ $productoSeleccionado->modelo->nombre ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs uppercase tracking-wide">Categoría</p>
                            <p class="font-semibold">
                                {{ $productoSeleccionado->categoria->nombre ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-slate-500 text-xs uppercase tracking-wide">Promoción</p>
                            <p class="font-semibold">
                                {{ $productoSeleccionado->promo->nombre ?? 'Sin promoción' }}
                            </p>
                        </div>
                    </div>

                    @if($productoSeleccionado->descripcion)
                        <div>
                            <p class="text-slate-500 text-xs uppercase tracking-wide mb-1">Descripción</p>
                            <p class="text-slate-700">
                                {{ $productoSeleccionado->descripcion }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="p-8 text-center border-t border-slate-200">
                <button
                    wire:click="cerrarModal"
                    class="px-10 py-3 bg-slate-900 hover:bg-black text-white font-semibold text-sm rounded-xl shadow-2xl transition">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
    @endif

    
</div>

<script>
    window.addEventListener('toast', e => Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: e.detail,
        timer: 3000,
        showConfirmButton: false
    }));

    window.addEventListener('confirmar-eliminar', () => {
        Swal.fire({
            title: '¿Desactivar producto?',
            text: "Podrás reactivarlo después si lo necesitas",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, desactivar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#dc2626'
        }).then(r => r.isConfirmed && Livewire.dispatch('eliminar'));
    });
</script>
