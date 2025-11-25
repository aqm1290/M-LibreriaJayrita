<div>
    <!-- TODO TU FORMULARIO (el mismo de antes) -->
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4">

            <h1 class="text-4xl font-extrabold text-gray-800 text-center mb-10">
                Nueva Entrada de Inventario
            </h1>

            <div class="bg-white rounded-3xl shadow-2xl border border-gray-200 overflow-hidden">
                <div class="p-8">
                    <form wire:submit.prevent="submit" class="space-y-8">

                        <!-- Proveedor y Fecha -->
                        <div class="grid md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Proveedor</label>
                                <select wire:model.live="proveedor_id" class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500 text-lg">
                                    <option value="">-- Seleccione proveedor --</option>
                                    @foreach($proveedores as $p)
                                        <option value="{{ $p->id }}">{{ $p->nombre }} @if($p->empresa)- {{ $p->empresa }}@endif</option>
                                    @endforeach
                                </select>
                                @error('proveedor_id') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Fecha</label>
                                <input type="date" wire:model.live="fecha" class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500 text-lg">
                                @error('fecha') <p class="text-red-600 text-sm mt-2">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Observación (opcional)</label>
                            <input type="text" wire:model.live="observacion" placeholder="Ej: Factura #5891 - Contado" class="w-full px-5 py-4 border border-gray-300 rounded-xl focus:ring-4 focus:ring-blue-500 text-lg">
                        </div>

                        <!-- TABLA DE PRODUCTOS -->
                        <div class="border-t-4 border-blue-600 pt-8">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-3xl font-bold text-gray-800">Detalle de Productos</h2>
                                <button type="button" wire:click="addDetalle"
                                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg transition transform hover:scale-105">
                                    + Agregar Producto
                                </button>
                            </div>

                            <div class="overflow-x-auto rounded-xl border border-gray-300">
                                <table class="w-full">
                                    <thead class="bg-gradient-to-r from-blue-700 to-blue-900 text-white">
                                        <tr>
                                            <th class="px-6 py-5 text-left font-bold">Producto</th>
                                            <th class="px-6 py-5 text-center font-bold">Cantidad</th>
                                            <th class="px-6 py-5 text-center font-bold">Costo Unit.</th>
                                            <th class="px-6 py-5 text-center font-bold">Subtotal</th>
                                            <th class="px-6 py-5 text-center font-bold"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($detalles as $index => $detalle)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="px-6 py-5 relative">
                                                    <input type="text"
                                                           wire:model.live.debounce.300ms="busquedas.{{ $index }}"
                                                           placeholder="Buscar producto..."
                                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-500 text-lg"
                                                           autocomplete="off">

                                                    @if(!empty($busquedas[$index]))
                                                        <div class="absolute z-50 left-0 right-0 top-full mt-1 bg-white border border-gray-300 rounded-lg shadow-2xl max-h-60 overflow-y-auto">
                                                            @foreach($this->buscarProductos($index) as $prod)
                                                                <div wire:click="seleccionarProducto({{ $index }}, {{ $prod->id }})"
                                                                     class="px-4 py-3 hover:bg-blue-100 cursor-pointer border-b last:border-b-0 flex justify-between">
                                                                    <div><strong>{{ $prod->nombre }}</strong> @if($prod->codigo)<span class="text-gray-500">({{ $prod->codigo }})</span>@endif</div>
                                                                    <span class="text-sm text-gray-500">Stock: {{ $prod->stock }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    @if(!empty($detalle['producto_id']))
                                                        <div class="mt-3 p-3 bg-emerald-50 border border-emerald-300 rounded-lg">
                                                            <span class="text-emerald-800 font-bold text-lg">
                                                                {{ \App\Models\Producto::find($detalle['producto_id'])->nombre }}
                                                                @if(\App\Models\Producto::find($detalle['producto_id'])->codigo)
                                                                    ({{ \App\Models\Producto::find($detalle['producto_id'])->codigo }})
                                                                @endif
                                                            </span>
                                                        </div>
                                                    @endif

                                                    <input type="hidden" wire:model="detalles.{{ $index }}.producto_id">
                                                    @error("detalles.$index.producto_id") <p class="text-red-600 text-xs mt-2">{{ $message }}</p> @enderror
                                                </td>

                                                <td class="px-6 py-5 text-center">
                                                    <input type="text" inputmode="numeric"
                                                           wire:model.blur="detalles.{{ $index }}.cantidad"
                                                           onclick="this.select()"
                                                           placeholder="0"
                                                           class="w-28 px-4 py-3 text-center font-bold text-xl border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-500">
                                                    @error("detalles.$index.cantidad") <p class="text-red-600 text-xs mt-2">{{ $message }}</p> @enderror
                                                </td>

                                                <td class="px-6 py-5 text-center">
                                                    <input type="text" inputmode="decimal"
                                                           wire:model.blur="detalles.{{ $index }}.costo"
                                                           onclick="this.select()"
                                                           placeholder="0.00"
                                                           class="w-36 px-4 py-3 text-right font-bold text-green-700 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-500">
                                                    @error("detalles.$index.costo") <p class="text-red-600 text-xs mt-2">{{ $message }}</p> @enderror
                                                </td>

                                                <td class="px-6 py-5 text-center">
                                                    <div class="text-2xl font-black text-blue-600">
                                                        Bs {{ number_format($detalle['subtotal'] ?? 0, 2) }}
                                                    </div>
                                                </td>

                                                <td class="px-6 py-5 text-center">
                                                    @if(count($detalles) > 1)
                                                        <button type="button" wire:click="removeDetalle({{ $index }})"
                                                                class="text-red-600 hover:text-red-800 text-4xl font-bold hover:scale-125 transition">×</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- TOTAL GENERAL -->
                        <div class="mt-10 p-8 bg-gradient-to-r from-blue-700 to-indigo-800 rounded-3xl text-white text-center shadow-2xl">
                            <h2 class="text-3xl font-extrabold mb-3">TOTAL GENERAL</h2>
                            <div class="text-5xl font-black">
                                <span wire:loading wire:target="detalles" class="inline-block">
                                    <svg class="animate-spin h-14 w-14 text-white/70" viewBox="0 0 24 24">
                                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" class="opacity-25"></circle>
                                        <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path>
                                    </svg>
                                </span>
                                <span wire:loading.remove wire:target="detalles">
                                    Bs {{ number_format($total, 2) }}
                                </span>
                            </div>
                        </div>

                        <div class="text-center mt-10">
                            <button type="submit"
                                    class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-bold text-2xl py-6 px-32 rounded-3xl shadow-2xl transition transform hover:scale-105">
                                Guardar Entrada
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

                    <!-- TOAST PEQUEÑO, LIMPIO Y SIN NINGÚN MENSAJE EN CONSOLA -->
            @if($showToast)
                <div 
                    x-data="{ show: true }" 
                    x-show="show" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform translate-y-[-10px]"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-end="opacity-0"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="fixed top-6 right-6 z-50"
                >
                    <div class="bg-emerald-600 text-white px-7 py-3 rounded-full shadow-xl flex items-center space-x-3 border border-white/30">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-sm font-bold tracking-wider">{{ $toastMessage }}</span>
                    </div>
                </div>
            @endif

    <!-- SWEETALERT2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('confirmar-guardado', () => {
                const total = @this.get('total');
                Swal.fire({
                    title: '¿Guardar entrada?',
                    html: `<p class="text-5xl font-bold text-emerald-600">Bs ${parseFloat(total).toLocaleString('es-BO', {minimumFractionDigits: 2})}</p>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, guardar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true,
                    width: '500px',
                    customClass: {
                        confirmButton: 'bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-12 rounded-2xl shadow-xl',
                        cancelButton: 'bg-gray-500 hover:bg-gray-600 text-white font-bold py-4 px-12 rounded-2xl shadow-xl',
                        popup: 'rounded-3xl'
                    }
                }).then((result) => {
                    if (result.isConfirmed) @this.call('guardarConfirmado');
                });
            });
        });
    </script>
</div>