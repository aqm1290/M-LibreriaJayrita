<div class="min-h-screen bg-gradient-to-br from-yellow-50 via-amber-50 to-orange-50 py-10 px-6">
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 mb-10">
            <div>
                <h1 class="text-4xl font-black text-amber-900">Gestión de Promociones</h1>
                <p class="text-amber-700 mt-2 text-lg">Crea ofertas irresistibles para tus clientes</p>
            </div>
            <button wire:click="crear"
                class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-bold py-4 px-10 rounded-2xl shadow-xl transition transform hover:scale-105 flex items-center gap-3">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                Nueva Promoción
            </button>
        </div>

        <!-- Lista de promociones -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($promos as $promo)
                <div class="bg-white rounded-3xl shadow-2xl overflow-hidden hover:shadow-3xl transition-all duration-300 border border-amber-200">
                    <div class="bg-gradient-to-r from-amber-400 to-orange-500 p-6 text-white">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold uppercase tracking-wider bg-white/30 px-4 py-1 rounded-full">
                                {{ str_replace('_', ' ', ucfirst($promo->tipo)) }}
                            </span>
                            <span class="text-xs font-bold {{ $promo->esta_activa ? 'bg-green-500' : 'bg-red-500' }} px-4 py-1 rounded-full">
                                {{ $promo->esta_activa ? 'ACTIVA' : 'INACTIVA' }}
                            </span>
                        </div>
                        <h3 class="text-2xl font-black mt-4">{{ $promo->nombre }}</h3>
                        <p class="text-4xl font-extrabold mt-3">{{ $promo->descripcion }}</p>
                    </div>

                    <div class="p-6 space-y-5">
                        @if($promo->codigo)
                            <div class="bg-yellow-100 border-2 border-yellow-300 rounded-2xl p-4 text-center">
                                <p class="text-sm font-bold text-amber-800">CÓDIGO:</p>
                                <p class="text-3xl font-black text-amber-900">{{ strtoupper($promo->codigo) }}</p>
                            </div>
                        @endif

                        <div class="text-sm text-amber-800 space-y-2 bg-amber-50 p-4 rounded-xl">
                            <p><span class="font-bold">Inicio:</span> {{ $promo->inicia_en?->format('d/m/Y H:i') }}</p>
                            <p><span class="font-bold">Fin:</span> {{ $promo->termina_en?->format('d/m/Y H:i') ?? 'Sin fecha límite' }}</p>
                        </div>

                        <div class="flex gap-4">
                            <button wire:click="editar({{ $promo->id }})"
                                class="flex-1 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 text-white font-bold py-4 rounded-2xl transition">
                                Editar
                            </button>
                            <button wire:click="eliminar({{ $promo->id }})"
                                onclick="return confirm('¿Seguro que deseas eliminar esta promoción?')"
                                class="flex-1 bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white font-bold py-4 rounded-2xl transition">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-24">
                    <div class="text-8xl mb-6 opacity-10">No promotions yet</div>
                    <p class="text-3xl text-amber-800 font-bold mb-8">No hay promociones creadas</p>
                    <button wire:click="crear" class="text-4xl font-black text-amber-600 hover:text-amber-700 underline">
                        ¡Crear la primera promoción!
                    </button>
                </div>
            @endforelse
        </div>
    </div>

    <!-- MODAL CON SCROLL -->
    @if($modal)
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl shadow-3xl max-w-6xl w-full max-h-screen overflow-y-auto my-8">
                <!-- Header del modal -->
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 text-white p-8 sticky top-0 z-10 rounded-t-3xl">
                    <h2 class="text-4xl font-black">{{ $promoId ? 'Editar Promoción' : 'Crear Nueva Promoción' }}</h2>
                </div>

                <div class="p-10 pb-20">
                    <form wire:submit.prevent="guardar" class="space-y-10">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            <!-- Campos básicos -->
                            <div>
                                <label class="block text-xl font-bold text-amber-900 mb-3">Nombre de la promoción *</label>
                                <input type="text" wire:model.blur="nombre" required
                                    class="w-full px-6 py-5 rounded-2xl border-2 border-amber-300 focus:border-amber-600 focus:ring-4 focus:ring-amber-200 outline-none text-lg font-medium">
                                @error('nombre') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xl font-bold text-amber-900 mb-3">Código (opcional)</label>
                                <input type="text" wire:model.blur="codigo"
                                    placeholder="Ej: VERANO2025"
                                    class="w-full px-6 py-5 rounded-2xl border-2 border-amber-300 focus:border-amber-600 focus:ring-4 focus:ring-amber-200 outline-none text-lg uppercase tracking-wider">
                            </div>

                            <div>
                                <label class="block text-xl font-bold text-amber-900 mb-3">Tipo de promoción *</label>
                                <select wire:model.live="tipo"
                                    class="w-full px-6 py-5 rounded-2xl border-2 border-amber-300 focus:border-amber-600 focus:ring-4 focus:ring-amber-200 outline-none text-lg font-medium">
                                    <option value="2x1">2x1 - Lleva 2, paga 1</option>
                                    <option value="compra_lleva">Compra y Lleva Gratis</option>
                                    <option value="descuento_porcentaje">% Descuento</option>
                                    <option value="descuento_monto">Descuento en monto fijo</option>
                                </select>
                            </div>

                            @if(in_array($tipo, ['descuento_porcentaje', 'descuento_monto']))
                                <div>
                                    <label class="block text-xl font-bold text-amber-900 mb-3">
                                        {{ $tipo === 'descuento_porcentaje' ? 'Porcentaje (%)' : 'Monto (Bs)' }} *
                                    </label>
                                    <input type="number" step="0.01" wire:model.blur="valor_descuento"
                                        class="w-full px-6 py-5 rounded-2xl border-2 border-amber-300 focus:border-amber-600 focus:ring-4 focus:ring-amber-200 outline-none text-lg font-bold">
                                </div>
                            @endif

                            <div>
                                <label class="block text-xl font-bold text-amber-900 mb-3">Fecha y hora de inicio *</label>
                                <input type="datetime-local" wire:model.blur="inicia_en"
                                    class="w-full px-6 py-5 rounded-2xl border-2 border-amber-300 focus:border-amber-600 focus:ring-4 focus:ring-amber-200 outline-none text-lg">
                            </div>

                            <div>
                                <label class="block text-xl font-bold text-amber-900 mb-3">Fecha y hora de fin (opcional)</label>
                                <input type="datetime-local" wire:model.blur="termina_en"
                                    class="w-full px-6 py-5 rounded-2xl border-2 border-amber-300 focus:border-amber-600 focus:ring-4 focus:ring-amber-200 outline-none text-lg">
                            </div>

                            <!-- CHECKBOX APLICA A TODA LA TIENDA -->
                            <div class="lg:col-span-2">
                                <label class="flex items-center gap-6 cursor-pointer text-2xl font-black text-amber-900">
                                    <input type="checkbox" wire:model.live="aplica_todo"
                                        class="w-10 h-10 text-amber-600 rounded-xl focus:ring-amber-500 focus:ring-4">
                                    <span>Aplica a toda la tienda</span>
                                </label>
                                <p class="text-amber-700 mt-3 text-lg">Si desactivas esta opción, podrás elegir productos específicos</p>
                            </div>

                            <!-- BUSCADOR DE PRODUCTOS (solo si NO aplica a toda la tienda) -->
                            @if(!$aplica_todo)
                                <div class="lg:col-span-2 bg-gradient-to-br from-yellow-50 to-amber-100 rounded-3xl p-10 border-4 border-amber-300 shadow-2xl">
                                    <h3 class="text-3xl font-black text-amber-900 mb-8 text-center">
                                        Productos incluidos en esta promoción
                                    </h3>

                                    <!-- Buscador en tiempo real -->
                                    <div class="relative mb-10">
                                        <input type="text"
                                            wire:model.live.debounce.300ms="query"
                                            placeholder="Buscar por nombre, código o marca..."
                                            class="w-full px-8 py-6 text-xl font-medium rounded-3xl border-4 border-amber-400 focus:border-amber-600 focus:ring-8 focus:ring-amber-200 outline-none shadow-xl transition-all">

                                        @if(count($resultados) > 0)
                                            <div class="absolute z-50 w-full bg-white rounded-3xl shadow-3xl border-4 border-amber-200 mt-4 max-h-96 overflow-y-auto">
                                                @foreach($resultados as $prod)
                                                    <button type="button"
                                                        wire:click="agregarProducto({{ $prod->id }})"
                                                        class="w-full text-left px-8 py-6 hover:bg-amber-50 transition flex justify-between items-center border-b border-amber-100 last:border-0">
                                                        <div>
                                                            <p class="text-xl font-bold text-amber-900">{{ $prod->nombre }}</p>
                                                            <p class="text-amber-700">Código: {{ $prod->codigo ?? 'Sin código' }} • Stock: {{ $prod->stock ?? 0 }}</p>                                                        </div>
                                                        <span class="text-5xl text-green-600 font-black">+</span>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Lista de productos seleccionados -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        @forelse(\App\Models\Producto::find($productosSeleccionados) ?? [] as $index => $prod)
                                            <div class="bg-white rounded-2xl p-6 shadow-xl border-4 border-amber-300 hover:border-amber-500 transition flex justify-between items-center">
                                                <div>
                                                    <p class="text-xl font-black text-amber-900">{{ $prod->nombre }}</p>
                                                    <p class="text-amber-700 text-sm">ID: {{ $prod->id }}</p>
                                                </div>
                                                <button type="button"
                                                    wire:click="quitarProducto({{ $index }})"
                                                    class="text-red-600 hover:text-red-800 text-4xl font-black hover:scale-125 transition">
                                                    ×
                                                </button>
                                            </div>
                                        @empty
                                            <p class="col-span-full text-center text-amber-600 text-xl py-16 italic font-medium">
                                                No hay productos seleccionados aún
                                            </p>
                                        @endforelse
                                    </div>
                                </div>
                            @endif

                            <!-- Activa y categoría -->
                            <div class="lg:col-span-2 flex justify-between items-center gap-10">
                                <label class="flex items-center gap-5 cursor-pointer text-xl">
                                    <input type="checkbox" wire:model.blur="activa" class="w-8 h-8 text-green-600 rounded-xl">
                                    <span class="font-bold text-amber-900">Promoción activa</span>
                                </label>

                                <div>
                                    <label class="block text-xl font-bold text-amber-900 mb-3">Categoría (opcional)</label>
                                    <select wire:model.blur="categoria_id"
                                        class="px-8 py-5 rounded-2xl border-2 border-amber-300 focus:border-amber-600 focus:ring-4 focus:ring-amber-200 outline-none text-lg">
                                        <option value="">-- Sin categoría --</option>
                                        @foreach($categorias as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Botones finales -->
                        <div class="flex justify-end gap-8 pt-12 pb-10 sticky bottom-0 bg-white border-t-4 border-amber-300 -mx-10 px-10">
                            <button type="button" wire:click="cerrarModal"
                                class="px-12 py-5 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold rounded-2xl text-xl transition">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-14 py-5 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-black rounded-2xl shadow-2xl text-xl transition transform hover:scale-105">
                                {{ $promoId ? 'Actualizar Promoción' : 'Crear Promoción' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>