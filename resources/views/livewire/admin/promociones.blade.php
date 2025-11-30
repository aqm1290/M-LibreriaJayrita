<div class="min-h-screen bg-slate-50 py-12 px-6">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- CABECERA -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                    Gestión de promociones
                </h1>
                <p class="mt-2 text-base md:text-lg text-slate-600">
                    Crea y administra promociones para tu catálogo.
                </p>
            </div>
            <button
                wire:click="crear"
                class="inline-flex items-center gap-3 px-6 py-3 md:px-8 md:py-4
                       bg-gradient-to-r from-gray-800 to-black hover:from-black hover:to-gray-900
                       text-white font-semibold md:font-black text-sm md:text-lg
                       rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 hover:scale-[1.02]
                       transition"
            >
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                NUEVA PROMOCIÓN
            </button>
        </div>

        <!-- FILTROS -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-5 md:p-6">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <input
                    type="text"
                    wire:model.live.debounce.500ms="search"
                    placeholder="Buscar promoción por nombre o código..."
                    class="w-full md:flex-1 px-5 md:px-6 py-3.5 md:py-4 text-sm md:text-base
                           border border-slate-200 rounded-2xl bg-white/80
                           focus:border-gray-900 focus:ring-2 focus:ring-gray-200 outline-none transition"
                >
            </div>
        </div>

        <!-- LISTADO -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-900 text-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">Nombre</th>
                            <th class="px-6 py-4 text-left font-semibold">Código</th>
                            <th class="px-6 py-4 text-left font-semibold">Tipo</th>
                            <th class="px-6 py-4 text-left font-semibold">Ámbito</th>
                            <th class="px-6 py-4 text-left font-semibold">Vigencia</th>
                            <th class="px-6 py-4 text-left font-semibold">Estado</th>
                            <th class="px-6 py-4 text-right font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($promos as $promo)
                            <tr class="hover:bg-slate-50/60 transition-colors">
                                <td class="px-6 py-4 align-top">
                                    <div class="font-semibold text-slate-900">
                                        {{ $promo->nombre }}
                                    </div>
                                    @if($promo->descripcion)
                                        <div class="mt-1 text-xs text-slate-500">
                                            {{ \Illuminate\Support\Str::limit($promo->descripcion, 60) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-xs font-mono">
                                        {{ $promo->codigo ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[0.7rem] font-semibold
                                                 {{ $promo->tipo === 'descuento_porcentaje' ? 'bg-emerald-100 text-emerald-800' :
                                                    ($promo->tipo === 'descuento_monto' ? 'bg-amber-100 text-amber-800' :
                                                    ($promo->tipo === '2x1' ? 'bg-indigo-100 text-indigo-800' : 'bg-purple-100 text-purple-800')) }}">
                                        @switch($promo->tipo)
                                            @case('descuento_porcentaje')
                                                {{ $promo->valor_descuento }}% OFF
                                                @break
                                            @case('descuento_monto')
                                                - Bs {{ $promo->valor_descuento }}
                                                @break
                                            @case('2x1')
                                                2x1
                                                @break
                                            @case('compra_lleva')
                                                Compra y lleva
                                                @break
                                            @default
                                                {{ $promo->tipo }}
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-4 align-top text-xs text-slate-700">
                                    @if($promo->aplica_todo)
                                        <span class="inline-flex px-3 py-1 rounded-full bg-slate-900 text-white text-[0.7rem] font-semibold">
                                            Toda la tienda
                                        </span>
                                    @elseif($promo->categoria)
                                        <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-800 text-[0.7rem] font-semibold">
                                            Categoría: {{ $promo->categoria->nombre }}
                                        </span>
                                    @elseif($promo->productos->count())
                                        <span class="inline-flex px-3 py-1 rounded-full bg-slate-100 text-slate-800 text-[0.7rem] font-bold">
                                            {{ $promo->productos->count() }} producto(s)
                                        </span>
                                    @else
                                        <span class="text-slate-400 text-xs">Sin ámbito definido</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top text-xs text-slate-600">
                                    <div>Desde: {{ $promo->inicia_en?->format('d/m/Y H:i') }}</div>
                                    <div>Hasta: {{ $promo->termina_en?->format('d/m/Y H:i') ?? 'Sin límite' }}</div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[0.7rem] font-bold
                                                 {{ $promo->activa ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $promo->activa ? 'ACTIVA' : 'INACTIVA' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex justify-end gap-2">
                                        <button
                                            wire:click="editar({{ $promo->id }})"
                                            class="p-2.5 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200
                                                   shadow-sm transition"
                                            title="Editar promoción"
                                        >
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </button>
                                        <button
                                            wire:click="eliminar({{ $promo->id }})"
                                            class="p-2.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition"
                                            title="Eliminar promoción"
                                        >
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center text-slate-500 text-base">
                                    No se encontraron promociones
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- MÓVIL -->
            <div class="lg:hidden divide-y divide-slate-100">
                @forelse($promos as $promo)
                    <div class="p-5">
                        <div class="flex items-start gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-slate-900 text-base">
                                        {{ $promo->nombre }}
                                    </h3>
                                    @if($promo->activa)
                                        <span class="px-2.5 py-0.5 text-[0.65rem] font-bold bg-emerald-100 text-emerald-700 rounded-full">
                                            ACTIVA
                                        </span>
                                    @else
                                        <span class="px-2.5 py-0.5 text-[0.65rem] font-bold bg-rose-100 text-rose-700 rounded-full">
                                            INACTIVA
                                        </span>
                                    @endif
                                </div>
                                @if($promo->descripcion)
                                    <p class="mt-1 text-sm text-slate-600">
                                        {{ \Illuminate\Support\Str::limit($promo->descripcion, 80) }}
                                    </p>
                                @endif

                                <div class="mt-3 flex flex-wrap gap-2 text-[0.7rem]">
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full
                                                 {{ $promo->tipo === 'descuento_porcentaje' ? 'bg-emerald-100 text-emerald-800' :
                                                    ($promo->tipo === 'descuento_monto' ? 'bg-amber-100 text-amber-800' :
                                                    ($promo->tipo === '2x1' ? 'bg-indigo-100 text-indigo-800' : 'bg-purple-100 text-purple-800')) }}">
                                        @switch($promo->tipo)
                                            @case('descuento_porcentaje')
                                                {{ $promo->valor_descuento }}% OFF
                                                @break
                                            @case('descuento_monto')
                                                - Bs {{ $promo->valor_descuento }}
                                                @break
                                            @case('2x1')
                                                2x1
                                                @break
                                            @case('compra_lleva')
                                                Compra y lleva
                                                @break
                                            @default
                                                {{ $promo->tipo }}
                                        @endswitch
                                    </span>

                                    @if($promo->aplica_todo)
                                        <span class="inline-flex px-3 py-1.5 rounded-full bg-slate-900 text-white">
                                            Toda la tienda
                                        </span>
                                    @elseif($promo->categoria)
                                        <span class="inline-flex px-3 py-1.5 rounded-full bg-slate-100 text-slate-800">
                                            Cat: {{ $promo->categoria->nombre }}
                                        </span>
                                    @elseif($promo->productos->count())
                                        <span class="inline-flex px-3 py-1.5 rounded-full bg-slate-100 text-slate-800">
                                            {{ $promo->productos->count() }} prod.
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-2 text-[0.7rem] text-slate-500">
                                    <div>Desde: {{ $promo->inicia_en?->format('d/m/Y H:i') }}</div>
                                    <div>Hasta: {{ $promo->termina_en?->format('d/m/Y H:i') ?? 'Sin límite' }}</div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                <button
                                    wire:click="editar({{ $promo->id }})"
                                    class="p-2.5 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200 transition"
                                    title="Editar"
                                >
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <button
                                    wire:click="eliminar({{ $promo->id }})"
                                    class="p-2.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white transition"
                                    title="Eliminar"
                                >
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-slate-500 text-base">
                        No se encontraron promociones
                    </div>
                @endforelse
            </div>
        </div>

        <!-- MODAL CREAR/EDITAR -->
        @if($modal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
                <div class="w-full max-w-4xl rounded-3xl bg-white/95 shadow-2xl border border-white/60">
                    <div class="bg-gradient-to-r from-gray-800 to-black text-white px-8 py-5 rounded-t-3xl">
                        <h2 class="text-2xl md:text-3xl font-black tracking-tight text-center">
                            {{ $promoId ? 'Editar promoción' : 'Nueva promoción' }}
                        </h2>
                    </div>

                    <div class="px-8 py-7 space-y-7 max-h-[75vh] overflow-y-auto">
                        <!-- Datos básicos -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                                    Nombre *
                                </label>
                                <input
                                    type="text"
                                    wire:model="nombre"
                                    class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm
                                           focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition"
                                >
                                @error('nombre')
                                    <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                                    Código (opcional)
                                </label>
                                <input
                                    type="text"
                                    wire:model="codigo"
                                    class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm
                                           focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition"
                                >
                                @error('codigo')
                                    <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                                Descripción (opcional)
                            </label>
                            <textarea
                                wire:model="descripcion"
                                rows="2"
                                class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white/80 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition resize-none"
                            ></textarea>
                        </div>

                        <!-- Tipo de promoción -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                                    Tipo de promoción *
                                </label>
                                <select
    wire:model="tipo"
    class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm
           focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition"
>
    <option value="descuento_porcentaje">% de descuento</option>
    <option value="descuento_monto">Descuento en Bs</option>
    <option value="2x1">2x1 en producto</option>
    <option value="compra_lleva">Compra X lleva Y</option>
</select>

                                @error('tipo')
                                    <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @if(in_array($tipo, ['descuento_porcentaje','descuento_monto']))
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                                        Valor del descuento *
                                    </label>
                                    <input
                                        type="number"
                                        step="0.01"
                                        wire:model="valor_descuento"
                                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm
                                               focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition"
                                    >
                                    @error('valor_descuento')
                                        <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endif

                            <div class="flex items-center gap-3 mt-4 md:mt-8">
                                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                    <input
                                        type="checkbox"
                                        wire:model="activa"
                                        class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500"
                                    >
                                    <span class="text-sm font-semibold text-slate-700">
                                        Promoción activa
                                    </span>
                                </label>
                            </div>
                        </div>

                        {{-- BLOQUE 2x1 --}}
@if($tipo === '2x1')
    <div class="border border-slate-100 rounded-2xl p-4 bg-slate-50/60 space-y-3 mt-2">
        <p class="text-xs font-semibold text-slate-600 uppercase tracking-wide">
            Productos con 2x1
        </p>

        {{-- Input que llama updatedQuery2x1 en tiempo real --}}
        <input
            type="text"
            wire:model.live.debounce.500ms="query_2x1"
            placeholder="Buscar producto por nombre o código..."
            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white text-sm
                   focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition"
        >

        {{-- Resultados --}}
        @if($resultados_2x1)
            <div class="bg-white border border-slate-200 rounded-2xl shadow max-h-48 overflow-y-auto text-sm">
                @foreach($resultados_2x1 as $p)
                    <button
                        type="button"
                        wire:click="seleccionar2x1({{ $p->id }})"
                        class="w-full text-left px-4 py-2 hover:bg-slate-50 flex justify-between items-center"
                    >
                        <span>{{ $p->nombre }} ({{ $p->codigo }})</span>
                        <span class="text-[0.7rem] text-slate-500">Stock: {{ $p->stock }}</span>
                    </button>
                @endforeach
            </div>
        @endif

        {{-- Seleccionados --}}
        <div class="flex flex-wrap gap-2">
            @foreach($productos_2x1 as $i => $id)
                @php $prod = \App\Models\Producto::find($id); @endphp
                @if($prod)
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-200 text-slate-800 text-[0.7rem]">
                        {{ $prod->nombre }}
                        <button
                            type="button"
                            wire:click="quitar2x1({{ $i }})"
                            class="ml-2 text-slate-500 hover:text-slate-800"
                        >
                            ×
                        </button>
                    </span>
                @endif
            @endforeach
        </div>

        @error('productos_2x1')
            <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
        @enderror
    </div>
@endif

                        <!-- Config especial Compra X Lleva Y -->
                        @if($tipo === 'compra_lleva')
                            <div class="p-6 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl border border-emerald-200">
                                <h3 class="text-sm font-bold text-emerald-800 uppercase mb-6 text-center">Compra X y lleva Y gratis</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                                    <!-- Producto que se compra -->
                                    <div>
                                        <p class="text-xs font-bold text-emerald-700 uppercase mb-3">Se compra</p>
                                        <input type="text" wire:model.live.debounce.500ms="query_compra" placeholder="Buscar..." class="w-full px-5 py-3 rounded-xl border border-emerald-300 focus:ring-4 focus:ring-emerald-200">
                                        
                                        @if(count($resultados_compra))
                                            <div class="mt-3 bg-white rounded-xl shadow-lg border max-h-60 overflow-y-auto">
                                                @foreach($resultados_compra as $prod)
                                                    <button type="button" wire:click="seleccionarCompra({{ $prod->id }})" class="w-full text-left px-4 py-3 hover:bg-emerald-50 flex justify-between border-b last:border-0">
                                                        <div><strong>{{ $prod->nombre }}</strong> <span class="text-xs text-slate-500">({{ $prod->codigo }})</span></div>
                                                        <div class="text-xs">Stock: {{ $prod->stock }}</div>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @foreach($productos_compra as $i => $p)
                                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-900 rounded-xl text-sm font-medium">
                                                    {{ $p['nombre'] }}
                                                    <button wire:click="quitarCompra({{ $i }})" class="text-emerald-700">×</button>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Producto de regalo -->
                                    <div>
                                        <p class="text-xs font-bold text-teal-700 uppercase mb-3">Se lleva gratis</p>
                                        <input type="text" wire:model.live.debounce.500ms="query_regalo" placeholder="Buscar..." class="w-full px-5 py-3 rounded-xl border border-teal-300 focus:ring-4 focus:ring-teal-200">
                                        
                                        @if(count($resultados_regalo))
                                            <div class="mt-3 bg-white rounded-xl shadow-lg border max-h-60 overflow-y-auto">
                                                @foreach($resultados_regalo as $prod)
                                                    <button type="button" wire:click="seleccionarRegalo({{ $prod->id }})" class="w-full text-left px-4 py-3 hover:bg-teal-50 flex justify-between border-b last:border-0">
                                                        <div><strong>{{ $prod->nombre }}</strong> <span class="text-xs text-slate-500">({{ $prod->codigo }})</span></div>
                                                        <div class="text-xs">Stock: {{ $prod->stock }}</div>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @foreach($productos_regalo as $i => $p)
                                                <span class="inline-flex items-center gap-2 px-4 py-2 bg-teal-100 text-teal-900 rounded-xl text-sm font-medium">
                                                    {{ $p['nombre'] }}
                                                    <button wire:click="quitarRegalo({{ $i }})" class="text-teal-700">×</button>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- ÁMBITO -->
                        <div class="border border-slate-100 rounded-2xl p-4 bg-slate-50/60 space-y-4">
                            <p class="text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1">
                                ¿Dónde aplica esta promoción?
                            </p>

                            <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                <input
                                    type="checkbox"
                                    wire:model="aplica_todo"
                                    class="w-5 h-5 rounded border-slate-300 text-slate-900 focus:ring-slate-800"
                                >
                                <span class="text-sm font-semibold text-slate-800">
                                    Aplica a toda la tienda
                                </span>
                            </label>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                                        Categoría (opcional)
                                    </label>
                                    <select
                                        wire:model="categoria_id"
                                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white text-sm
                                               focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition"
                                        @if($aplica_todo) disabled @endif
                                    >
                                        <option value="">Sin categoría específica</option>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                                        Productos específicos (opcional)
                                    </label>

                                    @if($aplica_todo)
                                        <p class="text-xs text-slate-500">
                                            Desactiva "toda la tienda" para seleccionar productos.
                                        </p>
                                    @else
                                        <div class="space-y-2">
                                            <input
                                                type="text"
                                                wire:model.live.debounce.500ms="query"
                                                placeholder="Buscar producto por nombre o código..."
                                                class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white text-sm
                                                       focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition"
                                            >

                                            @if(count($resultados))
                                                <div class="bg-white border border-slate-200 rounded-2xl shadow max-h-48 overflow-y-auto">
                                                    @foreach($resultados as $prod)
                                                        <button
                                                            type="button"
                                                            wire:click="agregarProducto({{ $prod->id }})"
                                                            class="w-full text-left px-4 py-2 text-xs hover:bg-slate-50 flex justify-between items-center"
                                                        >
                                                            <span>{{ $prod->nombre }} ({{ $prod->codigo }})</span>
                                                            <span class="text-[0.7rem] text-slate-500">Stock: {{ $prod->stock }}</span>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            @endif

                                            <div class="flex flex-wrap gap-2 mt-2">
                                                @foreach($productosSeleccionados as $i => $prod)
                                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full bg-slate-200 text-slate-800 text-[0.7rem]">
                                                        {{ $prod['nombre'] }}
                                                        <button
                                                            type="button"
                                                            wire:click="quitarProducto({{ $i }})"
                                                            class="ml-2 text-slate-500 hover:text-slate-800"
                                                        >
                                                            ×
                                                        </button>
                                                    </span>
                                                @endforeach
                                            </div>
                                            @error('productosSeleccionados')
                                                <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Vigencia -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                                    Inicio *
                                </label>
                                <input
                                    type="datetime-local"
                                    wire:model="inicia_en"
                                    class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm
                                           focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition"
                                >
                                @error('inicia_en')
                                    <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                                    Termina (opcional)
                                </label>
                                <input
                                    type="datetime-local"
                                    wire:model="termina_en"
                                    class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm
                                           focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition"
                                >
                                @error('termina_en')
                                    <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="px-8 py-5 bg-slate-50/80 border-t border-slate-100 rounded-b-3xl flex justify-end gap-3">
                        <button
                            wire:click="cerrarModal"
                            class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 text-sm font-medium
                                   hover:bg-slate-100 hover:border-slate-400 transition"
                        >
                            Cancelar
                        </button>
                        <button
                            wire:click="guardar"
                            class="px-7 py-2.5 rounded-xl bg-gray-900 text-white text-sm font-semibold
                                   hover:bg-black shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition"
                        >
                            Guardar promoción
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    window.addEventListener('toast', event => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: event.detail,
            timer: 3000,
            showConfirmButton: false,
            timerProgressBar: true,
        });
    });
</script>