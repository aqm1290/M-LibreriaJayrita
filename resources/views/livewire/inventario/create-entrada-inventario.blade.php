<div>
    <!-- TODO TU FORMULARIO (el mismo de antes) -->
    <div class="min-h-screen bg-gradient-to-br from-yellow-100 via-yellow-50 to-orange-100 py-12">
        <div class="max-w-7xl mx-auto px-4">

            <h1 class="text-4xl font-extrabold text-slate-900 text-center mb-3">
                Nueva Entrada de Inventario
            </h1>
            <p class="text-center text-sm md:text-base text-orange-700/90 mb-10">
                Registra el ingreso de productos a tu almacén.
            </p>

            <div class="bg-white rounded-3xl shadow-2xl border border-yellow-200 overflow-hidden">
                <div class="p-8">
                    <form wire:submit.prevent="submit" class="space-y-8">

                        <!-- Proveedor y Fecha -->
                        <div class="grid md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-2">Proveedor</label>
                                <select wire:model.live="proveedor_id"
                                    class="w-full px-5 py-4 border border-yellow-300 rounded-xl focus:ring-4 focus:ring-yellow-300 focus:border-yellow-600 text-lg bg-white/90">
                                    <option value="">-- Seleccione proveedor --</option>
                                    @foreach ($proveedores as $p)
                                        <option value="{{ $p->id }}">{{ $p->nombre }} @if ($p->empresa)
                                                - {{ $p->empresa }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('proveedor_id')
                                    <p class="text-rose-600 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-800 mb-2">Fecha</label>
                                <input type="date" wire:model.live="fecha"
                                    class="w-full px-5 py-4 border border-yellow-300 rounded-xl focus:ring-4 focus:ring-yellow-300 focus:border-yellow-600 text-lg bg-white/90">
                                @error('fecha')
                                    <p class="text-rose-600 text-sm mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-800 mb-2">Observación (opcional)</label>
                            <input type="text" wire:model.live="observacion"
                                placeholder="Ej: Factura #5891 - Contado"
                                class="w-full px-5 py-4 border border-yellow-300 rounded-xl focus:ring-4 focus:ring-yellow-300 focus:border-yellow-600 text-lg bg-white/90">
                        </div>

                        <!-- BUSCADOR GLOBAL + BOTÓN AGREGAR -->
                        <div class="border-t-4 border-yellow-500 pt-8 space-y-4">
                            <h2 class="text-2xl font-bold text-slate-900 mt-4">
                                Detalle de productos
                            </h2>
                        </div>

                        <!-- LISTA DE PRODUCTOS (SIN TABLA) -->
                        <div class="space-y-4">
                            @foreach ($detalles as $index => $detalle)
                                <div class="bg-white rounded-2xl border border-yellow-200 shadow-sm p-5 space-y-4">

                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex-1">
                                            <label
                                                class="block text-xs font-semibold text-slate-700 mb-1 uppercase tracking-wide">
                                                Producto
                                            </label>
                                            <input type="text"
                                                wire:model.live.debounce.300ms="busquedas.{{ $index }}"
                                                placeholder="Buscar producto..."
                                                class="w-full px-4 py-3 border border-yellow-300 rounded-xl focus:ring-4 focus:ring-yellow-300 focus:border-yellow-600 text-base bg-white/90"
                                                autocomplete="off">

                                            @if (!empty($busquedas[$index]))
                                                <div
                                                    class="mt-2 bg-white border border-yellow-200 rounded-2xl shadow-2xl max-h-60 overflow-y-auto">
                                                    @foreach ($this->buscarProductos($index) as $prod)
                                                        <button type="button"
                                                            wire:click="seleccionarProducto({{ $index }}, {{ $prod->id }})"
                                                            class="w-full text-left px-4 py-3 hover:bg-yellow-50 flex justify-between border-b last:border-b-0">
                                                            <div>
                                                                <strong>{{ $prod->nombre }}</strong>
                                                                @if ($prod->codigo)
                                                                    <span
                                                                        class="text-xs text-slate-500">({{ $prod->codigo }})</span>
                                                                @endif
                                                            </div>
                                                            <span class="text-xs text-slate-500">
                                                                Stock: {{ $prod->stock }}
                                                            </span>
                                                        </button>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if (!empty($detalle['producto_id']))
                                                @php
                                                    $pModel = \App\Models\Producto::find($detalle['producto_id']);
                                                @endphp
                                                @if ($pModel)
                                                    <div
                                                        class="mt-3 p-3 bg-emerald-50 border border-emerald-300 rounded-lg">
                                                        <span class="text-emerald-800 font-bold text-sm md:text-base">
                                                            {{ $pModel->nombre }}
                                                            @if ($pModel->codigo)
                                                                ({{ $pModel->codigo }})
                                                            @endif
                                                        </span>
                                                    </div>
                                                @endif
                                            @endif

                                            <input type="hidden"
                                                wire:model="detalles.{{ $index }}.producto_id">
                                            @error("detalles.$index.producto_id")
                                                <p class="text-rose-600 text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        @if (count($detalles) > 1)
                                            <button type="button" wire:click="removeDetalle({{ $index }})"
                                                class="text-rose-600 hover:text-rose-800 text-3xl font-bold hover:scale-125 transition leading-none"
                                                title="Quitar producto">
                                                ×
                                            </button>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-slate-700 mb-1 uppercase tracking-wide">
                                                Cantidad
                                            </label>
                                            <input type="text" inputmode="numeric"
                                                wire:model.blur="detalles.{{ $index }}.cantidad"
                                                onclick="this.select()" placeholder="0"
                                                class="w-full px-4 py-3 text-center font-bold text-lg border border-yellow-300 rounded-xl focus:ring-4 focus:ring-yellow-300 focus:border-yellow-600 bg-white/90">
                                            @error("detalles.$index.cantidad")
                                                <p class="text-rose-600 text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label
                                                class="block text-xs font-semibold text-slate-700 mb-1 uppercase tracking-wide">
                                                Costo unitario (Bs)
                                            </label>
                                            <input type="text" inputmode="decimal"
                                                wire:model.blur="detalles.{{ $index }}.costo"
                                                onclick="this.select()" placeholder="0.00"
                                                class="w-full px-4 py-3 text-right font-bold text-emerald-700 border border-yellow-300 rounded-xl focus:ring-4 focus:ring-yellow-300 focus:border-yellow-600 bg-white/90">
                                            @error("detalles.$index.costo")
                                                <p class="text-rose-600 text-xs mt-2">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div class="md:text-right">
                                            <p
                                                class="text-xs font-semibold text-slate-700 mb-1 uppercase tracking-wide">
                                                Subtotal
                                            </p>
                                            <div class="text-2xl font-black text-orange-600">
                                                Bs {{ number_format($detalle['subtotal'] ?? 0, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- TOTAL GENERAL -->
                        <div
                            class="mt-10 p-8 bg-gradient-to-r from-yellow-500 via-amber-500 to-orange-600 rounded-3xl text-white text-center shadow-2xl border border-yellow-200">
                            <h2 class="text-3xl font-extrabold mb-3">TOTAL GENERAL</h2>
                            <div class="text-5xl font-black">
                                <span wire:loading wire:target="detalles" class="inline-block">
                                    <svg class="animate-spin h-14 w-14 text-white/80" viewBox="0 0 24 24">
                                        <rcle cx="12" cy="12" r="10" stroke="currentColor"
                                            stroke-width="4" fill="none" class="opacity-25">
                                            </circle>
                                            <path fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                                class="opacity-75"></path>
                                    </svg>
                                </span>
                                <span wire:loading.remove wire:target="detalles">
                                    Bs {{ number_format($total, 2) }}
                                </span>
                            </div>
                        </div>

                        <div class="text-center mt-10">
                            <button type="submit"
                                class="bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800
                                       text-white font-bold text-2xl py-6 px-32 rounded-3xl shadow-2xl transition transform hover:scale-105">
                                Guardar Entrada
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- TOAST PEQUEÑO, LIMPIO Y SIN NINGÚN MENSAJE EN CONSOLA -->
    @if ($showToast)
        <div x-data="{ show: true }" x-show="show" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-[-10px]"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0"
            x-init="setTimeout(() => show = false, 3000)" class="fixed top-6 right-6 z-50">
            <div
                class="bg-emerald-600 text-white px-7 py-3 rounded-full shadow-xl flex items-center space-x-3 border border-white/30">
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
