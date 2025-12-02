<div class="min-h-screen bg-slate-50 py-12 px-6">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- CABECERA -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                    Gestión de promociones
                </h1>
                <p class="mt-2 text-base md:text-lg text-slate-600">
                    Administra descuentos, 2x1 y compra‑lleva de tu catálogo.
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
                    wire:model.debounce.500ms="search"
                    placeholder="Buscar promoción o código..."
                    class="w-full md:flex-1 px-5 md:px-6 py-3.5 md:py-4 text-sm md:text-base
                           border border-slate-200 rounded-2xl bg-white/80
                           focus:border-gray-900 focus:ring-2 focus:ring-gray-200 outline-none transition"
                >
            </div>
        </div>

        <!-- LISTA / TABLA -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">

            <!-- ESCRITORIO -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-900 text-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">Promoción</th>
                            <th class="px-6 py-4 text-left font-semibold">Tipo</th>
                            <th class="px-6 py-4 text-left font-semibold">Límite</th>
                            <th class="px-6 py-4 text-left font-semibold">Ámbito</th>
                            <th class="px-6 py-4 text-left font-semibold">Vigencia</th>
                            <th class="px-6 py-4 text-left font-semibold">Estado</th>
                            <th class="px-6 py-4 text-right font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($promos as $promo)
                            <tr class="{{ $promo->activa ? 'hover:bg-slate-50/60' : 'bg-rose-50/30 hover:bg-rose-50/60' }} transition-colors">
                                <td class="px-6 py-5 align-top">
                                    <div class="font-semibold text-slate-900">{{ $promo->nombre }}</div>
                                    @if($promo->descripcion)
                                        <p class="text-xs text-slate-600">{{ \Illuminate\Support\Str::limit($promo->descripcion, 80) }}</p>
                                    @endif
                                    @if($promo->codigo)
                                        <span class="mt-1 inline-flex items-center px-3 py-1 text-[0.65rem] font-bold text-slate-700 bg-slate-100 rounded-full">
                                            {{ $promo->codigo }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                        @if($promo->tipo === 'descuento_porcentaje') bg-emerald-100 text-emerald-800
                                        @elseif($promo->tipo === 'descuento_monto') bg-amber-100 text-amber-800
                                        @elseif($promo->tipo === '2x1') bg-indigo-100 text-indigo-800
                                        @else bg-purple-100 text-purple-800 @endif">
                                        @switch($promo->tipo)
                                            @case('descuento_porcentaje') {{ $promo->valor_descuento }}% OFF @break
                                            @case('descuento_monto') - Bs {{ number_format($promo->valor_descuento,2) }} @break
                                            @case('2x1') 2x1 @break
                                            @case('compra_lleva') Compra X Lleva Y @break
                                        @endswitch
                                    </span>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    @if(is_null($promo->limite_usos))
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-slate-100 text-slate-800 font-semibold text-xs">Ilimitado</span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-rose-100 text-rose-800 font-semibold text-xs">
                                            {{ $promo->limite_usos }} uso{{ $promo->limite_usos > 1 ? 's' : '' }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-top text-xs text-slate-700">
                                    @if($promo->aplica_todo)
                                        Toda la tienda
                                    @else
                                        @if($promo->categoria)<div>Categoría: {{ $promo->categoria->nombre }}</div>@endif
                                        @if($promo->marca)<div>Marca: {{ $promo->marca->nombre }}</div>@endif
                                        @if($promo->modelo)<div>Modelo: {{ $promo->modelo->nombre }}</div>@endif
                                        @if(is_array($promo->productos_seleccionados) && count($promo->productos_seleccionados))
                                            <div class="mt-1 text-[0.7rem]">{{ count($promo->productos_seleccionados) }} producto(s) específicos</div>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-top text-xs text-slate-600">
                                    <div>Desde: {{ $promo->inicia_en?->format('d/m/Y H:i') }}</div>
                                    <div>Hasta: {{ $promo->termina_en?->format('d/m/Y H:i') ?? 'Sin límite' }}</div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold
                                        {{ $promo->activa ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $promo->activa ? 'ACTIVA' : 'INACTIVA' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="editar({{ $promo->id }})"
                                            class="p-2.5 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200 shadow-sm transition"
                                            title="Editar promoción">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </button>
                                        <button wire:click="eliminar({{ $promo->id }})"
                                            class="p-2.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition"
                                            title="Eliminar promoción">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-16 text-center text-slate-500 text-base font-medium">
                                    No se encontraron promociones
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- MÓVIL (COMPLETO, NUNCA LO QUITÉ) -->
            <div class="lg:hidden divide-y divide-slate-100">
                @forelse($promos as $promo)
                    <div class="{{ $promo->activa ? 'bg-white' : 'bg-rose-50/40' }} p-5">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-2">
                                <h3 class="font-semibold text-slate-900 text-base">{{ $promo->nombre }}</h3>
                                @if(!$promo->activa)
                                    <span class="px-2.5 py-0.5 text-[0.65rem] font-bold text-rose-700 bg-rose-100 rounded-full">INACTIVA</span>
                                @endif
                            </div>
                            <div class="flex flex-wrap items-center gap-2 text-xs">
                                <span class="px-3 py-1 rounded-full
                                    @if($promo->tipo === 'descuento_porcentaje') bg-emerald-100 text-emerald-800
                                    @elseif($promo->tipo === 'descuento_monto') bg-amber-100 text-amber-800
                                    @elseif($promo->tipo === '2x1') bg-indigo-100 text-indigo-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    @switch($promo->tipo)
                                        @case('descuento_porcentaje') {{ $promo->valor_descuento }}% OFF @break
                                        @case('descuento_monto') - Bs {{ number_format($promo->valor_descuento,2) }} @break
                                        @case('2x1') 2x1 @break
                                        @case('compra_lleva') Compra X Lleva Y @break
                                    @endswitch
                                </span>
                                <span class="px-3 py-1 rounded-full font-bold {{ $promo->activa ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $promo->activa ? 'ACTIVA' : 'INACTIVA' }}
                                </span>
                            </div>
                            <div class="text-[11px] text-slate-600 space-y-0.5">
                                <div>Desde: {{ $promo->inicia_en?->format('d/m/Y H:i') }}</div>
                                <div>Hasta: {{ $promo->termina_en?->format('d/m/Y H:i') ?? 'Sin límite' }}</div>
                            </div>
                            <div class="mt-3 flex gap-2">
                                <button wire:click="editar({{ $promo->id }})"
                                    class="p-2.5 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200 transition">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <button wire:click="eliminar({{ $promo->id }})"
                                    class="p-2.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white transition">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-slate-500 text-base">No se encontraron promociones</div>
                @endforelse
            </div>
        </div>

        <!-- MODAL COMPLETO CON BÚSQUEDA EN TIEMPO REAL -->
        @if($modal)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4 overflow-y-auto">
                <div class="w-full max-w-4xl rounded-3xl bg-white/95 shadow-2xl border border-white/60 flex flex-col max-h-[95vh]">
                    <div class="bg-gradient-to-r from-gray-800 to-black text-white px-8 py-5 text-center rounded-t-3xl">
                        <h2 class="text-2xl md:text-3xl font-black tracking-tight">
                            {{ $promoId ? 'Editar promoción' : 'Nueva promoción' }}
                        </h2>
                    </div>

                    <div class="px-8 py-7 space-y-7 overflow-y-auto flex-1">
                        <!-- Nombre, código y descripción -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">Nombre de la promoción *</label>
                                <input type="text" wire:model="nombre"
                                    class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition">
                                @error('nombre') <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">Código (opcional)</label>
                                <input type="text" wire:model="codigo"
                                    class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition">
                                @error('codigo') <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p> @enderror
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">Descripción (opcional)</label>
                                <textarea wire:model="descripcion" rows="3"
                                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition resize-none"></textarea>
                            </div>
                        </div>

                        <!-- Tipo de promoción -->
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">Tipo de promoción *</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach([
                                    'descuento_porcentaje' => 'Porcentaje %',
                                    'descuento_monto'      => 'Monto fijo',
                                    '2x1'                   => '2x1',
                                    'compra_lleva'         => 'Compra X Lleva Y',
                                ] as $value => $label)
                                    <button type="button" wire:click="$set('tipo', '{{ $value }}')"
                                        class="w-full px-4 py-3 rounded-2xl border text-sm font-semibold
                                            {{ $tipo === $value ? 'bg-black text-white border-black' : 'bg-white text-slate-700 border-slate-200 hover:border-slate-400' }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <!-- Valor del descuento -->
                        @if(in_array($tipo, ['descuento_porcentaje','descuento_monto']))
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">Valor del descuento *</label>
                                    <input type="number" wire:model="valor_descuento"
                                        step="{{ $tipo === 'descuento_porcentaje' ? 1 : 0.01 }}"
                                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition">
                                    @error('valor_descuento') <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p> @enderror
                                </div>
                                <div class="text-center text-2xl font-black text-slate-800">
                                    {{ $tipo === 'descuento_porcentaje' ? '%' : 'Bs' }}
                                </div>
                            </div>
                        @endif

                        <!-- COMPRA X LLEVA Y (con búsqueda en tiempo real) -->
                        @if($tipo === 'compra_lleva')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Producto que se compra -->
                                <div class="space-y-3">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">Producto que se compra (X)</label>
                                    <input type="text" wire:model.live.debounce.300ms="query_compra"
                                        placeholder="Buscar producto para comprar..."
                                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition">

                                    @if($query_compra !== '' && !empty($resultados_compra))
                                        <div class="mt-2 max-h-48 overflow-y-auto border border-slate-200 rounded-2xl bg-white shadow-lg z-50">
                                            @foreach($resultados_compra as $res)
                                                <button type="button" wire:click="seleccionarCompra({{ $res['id'] }})"
                                                    class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-slate-50 transition border-b last:border-0">
                                                    <div>
                                                        <div class="font-medium text-slate-900">{{ $res['nombre'] }}</div>
                                                        <div class="text-xs text-slate-500">Código: {{ $res['codigo'] }} • Stock: {{ $res['stock'] }}</div>
                                                    </div>
                                                    <span class="text-emerald-600 text-xs font-bold">Agregar</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="mt-3">
                                        @if(count($products_compra))
                                            @foreach($products_compra as $p)
                                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">
                                                    {{ $p['nombre'] }}
                                                </span>
                                            @endforeach
                                        @else
                                            <p class="text-xs text-slate-500 italic">Busca y selecciona un producto</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Producto de regalo -->
                                <div class="space-y-3">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">Producto que se regala (Y)</label>
                                    <input type="text" wire:model.live.debounce.300ms="query_regalo"
                                        placeholder="Buscar producto de regalo..."
                                        class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition">

                                    @if($query_regalo !== '' && !empty($resultados_regalo))
                                        <div class="mt-2 max-h-48 overflow-y-auto border border-slate-200 rounded-2xl bg-white shadow-lg z-50">
                                            @foreach($resultados_regalo as $res)
                                                <button type="button" wire:click="seleccionarRegalo({{ $res['id'] }})"
                                                    class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-slate-50 transition border-b last:border-0">
                                                    <div>
                                                        <div class="font-medium text-slate-900">{{ $res['nombre'] }}</div>
                                                        <div class="text-xs text-slate-500">Código: {{ $res['codigo'] }} • Stock: {{ $res['stock'] }}</div>
                                                    </div>
                                                    <span class="text-indigo-600 text-xs font-bold">Agregar</span>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="mt-3">
                                        @if(count($products_regalo))
                                            @foreach($products_regalo as $p)
                                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-100 text-indigo-800 text-xs font-bold">
                                                    {{ $p['nombre'] }}
                                                </span>
                                            @endforeach
                                        @else
                                            <p class="text-xs text-slate-500 italic">Busca y selecciona un producto</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Límite de usos -->
                        <div class="bg-rose-50 border border-rose-100 rounded-2xl p-5 space-y-3">
                            <h3 class="text-sm font-bold text-rose-800">Límite de usos por cliente</h3>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 text-xs md:text-sm">
                                <label class="inline-flex items-center gap-2 cursor-pointer"><input type="radio" wire:model="limite_usos" value="" class="w-4 h-4 text-rose-600"> Ilimitado</label>
                                <label class="inline-flex items-center gap-2 cursor-pointer"><input type="radio" wire:model="limite_usos" value="1" class="w-4 h-4 text-rose-600"> 1 uso</label>
                                <label class="inline-flex items-center gap-2 cursor-pointer"><input type="radio" wire:model="limite_usos" value="2" class="w-4 h-4 text-rose-600"> 2 usos</label>
                                <label class="inline-flex items-center gap-2 cursor-pointer"><input type="radio" wire:model="limite_usos" value="3" class="w-4 h-4 text-rose-600"> 3 usos</label>
                                <label class="inline-flex items-center gap-2 cursor-pointer"><input type="radio" wire:model="limite_usos" value="5" class="w-4 h-4 text-rose-600"> 5 usos</label>
                            </div>
                        </div>

                        <!-- Ámbito (solo si no es compra_lleva) -->
                        @if($tipo !== 'compra_lleva')
                            <div class="space-y-4">
                                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                                    <input type="checkbox" wire:click="$toggle('aplica_todo')" @checked($aplica_todo)
                                        class="w-5 h-5 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                    <span class="text-sm font-semibold text-slate-700">Aplica a toda la tienda</span>
                                </label>

                                @if(!$aplica_todo)
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">Categoría</label>
                                            <select wire:model="categoria_id"
                                                class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition">
                                                <option value="">Ninguna categoría</option>
                                                @foreach($categorias as $cat)<option value="{{ $cat->id }}">{{ $cat->nombre }}</option>@endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">Marca</label>
                                            <select wire:model="marca_id"
                                                class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition">
                                                <option value="">Ninguna marca</option>
                                                @foreach($marcas as $m)<option value="{{ $m->id }}">{{ $m->nombre }}</option>@endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">Modelo</label>
                                            <select wire:model="modelo_id"
                                                class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition">
                                                <option value="">Ningún modelo</option>
                                                @foreach($modelos as $mod)<option value="{{ $mod->id }}">{{ $mod->nombre }}</option>@endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Productos específicos (BÚSQUEDA EN TIEMPO REAL) -->
                                    <div class="space-y-3">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1 uppercase tracking-wide">Productos específicos (opcional)</label>
                                        <input type="text" wire:model.live.debounce.300ms="query"
                                            placeholder="Buscar producto por nombre o código..."
                                            class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition">

                                        @if($query !== '' && !empty($resultados))
                                            <div class="mt-2 max-h-48 overflow-y-auto border border-slate-200 rounded-2xl bg-white shadow-lg z-50">
                                                @foreach($resultados as $res)
                                                    <button type="button" wire:click="agregarProducto({{ $res['id'] }})"
                                                        class="w-full px-4 py-3 text-left flex justify-between items-center hover:bg-slate-50 transition border-b last:border-0">
                                                        <div>
                                                            <div class="font-medium text-slate-900">{{ $res['nombre'] }}</div>
                                                            <div class="text-xs text-slate-500">Código: {{ $res['codigo'] }} • Stock: {{ $res['stock'] }}</div>
                                                        </div>
                                                        <span class="text-emerald-600 text-xs font-bold">Agregar</span>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif

                                        <div class="mt-3">
                                            @if(count($productosSeleccionados))
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($productosSeleccionados as $i => $p)
                                                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold">
                                                            {{ $p['nombre'] }}
                                                            <button type="button" wire:click="quitarProducto({{ $i }})"
                                                                class="hover:bg-emerald-200 rounded-full w-5 h-5 flex items-center justify-center transition">×</button>
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-xs text-slate-500 italic">Escribe para buscar productos...</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Estado -->
                        <div class="flex items-center gap-3">
                            <input type="checkbox" wire:model="activa" id="activa"
                                class="w-5 h-5 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                            <label for="activa" class="text-sm font-semibold text-slate-700 cursor-pointer">Promoción activa</label>
                        </div>
                    </div>

                    <div class="px-8 py-5 bg-slate-50/80 border-t border-slate-100 rounded-b-3xl flex justify-end gap-3">
                        <button wire:click="cerrarModal"
                            class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 text-sm font-medium hover:bg-slate-100 transition">
                            Cancelar
                        </button>
                        <button wire:click="guardar"
                            class="px-7 py-2.5 rounded-xl bg-gray-900 text-white text-sm font-semibold hover:bg-black shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition">
                            {{ $promoId ? 'Actualizar promoción' : 'Crear promoción' }}
                        </button>
                    </div>
                </div>
            </div>
        @endif
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
</div>