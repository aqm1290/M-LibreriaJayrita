<div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-2xl ring-1 ring-gray-200">
    @if(session()->has('message'))
        <div class="bg-green-50 border-l-4 border-green-400 text-green-700 p-4 rounded-md mb-6 shadow-sm">{{ session('message') }}</div>
    @endif
    @if(session()->has('error'))
        <div class="bg-red-50 border-l-4 border-red-400 text-red-700 p-4 rounded-md mb-6 shadow-sm">{{ session('error') }}</div>
    @endif

    <h4 class="text-xl font-bold text-gray-800 mb-4 tracking-tight">Editar Entrada de Inventario</h4>

    <form wire:submit.prevent="submit" class="space-y-8">
        <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2 tracking-wide">Proveedor</label>
            <select wire:model="proveedor_id" class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                <option value="">Selecciona...</option>
                @foreach($proveedores as $proveedor)
                    <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }} ({{ $proveedor->empresa ?? '' }})</option>
                @endforeach
            </select>
            @error('proveedor_id') <span class="text-red-500 text-xs mt-1 italic">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2 tracking-wide">Fecha</label>
            <input type="date" wire:model="fecha" class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
            @error('fecha') <span class="text-red-500 text-xs mt-1 italic">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-semibold mb-2 tracking-wide">Observación</label>
            <textarea wire:model="observacion" class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out" rows="4"></textarea>
            @error('observacion') <span class="text-red-500 text-xs mt-1 italic">{{ $message }}</span> @enderror
        </div>

        <div>
            <h4 class="text-xl font-bold text-gray-800 mb-4 tracking-tight">Detalles de Productos</h4>
            <div class="overflow-x-auto border border-gray-200 rounded-lg shadow-sm">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Producto</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Cantidad</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Costo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Subtotal</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($detalles as $index => $detalle)
                            <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <select wire:model="detalles.{{ $index }}.producto_id" class="block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out">
                                        <option value="">Seleccionar...</option>
                                        @foreach($productos as $producto)
                                            <option value="{{ $producto->id }}">{{ $producto->nombre }} (Stock: {{ $producto->stock }})</option>
                                        @endforeach
                                    </select>
                                    @error("detalles.$index.producto_id") <span class="text-red-500 text-xs mt-1 italic">{{ $message }}</span> @enderror
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="number"
                                        wire:model.lazy="detalles.{{ $index }}.cantidad"
                                        class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                                        min="1">                                    @error("detalles.$index.cantidad") <span class="text-red-500 text-xs mt-1 italic">{{ $message }}</span> @enderror
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <input type="number"
                                        wire:model.lazy="detalles.{{ $index }}.costo"
                                        class="block w-full px-4 py-2 border border-gray-300 rounded-md"
                                        step="0.01">
                                    @error("detalles.$index.costo") <span class="text-red-500 text-xs mt-1 italic">{{ $message }}</span> @enderror
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 font-medium">{{ number_format($detalle['subtotal'], 2) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button type="button" wire:click="removeDetalle({{ $index }})" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md shadow transition duration-150 ease-in-out">Eliminar</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <button type="button" wire:click="addDetalle" class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-md shadow transition duration-150 ease-in-out">Agregar Producto</button>
        </div>

        <div class="flex justify-between items-center mt-6">
            <strong class="text-xl text-gray-800 font-bold">Total: {{ number_format($total, 2) }}</strong>

            <div class="flex gap-4">
                <!-- BOTÓN CANCELAR -->
                <button type="button"
                    wire:click="$dispatch('confirmar-cancelacion')"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-3 px-6 rounded-md shadow">
                    Cancelar
                </button>

                <!-- BOTÓN ACTUALIZAR -->
                <button type="button"
                    wire:click="$dispatch('confirmar-actualizacion')"
                    class="bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-md shadow">
                    Actualizar Entrada
                </button>
            </div>
        </div>

    </form>


    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            Livewire.on('confirmar-actualizacion', () => {
                Swal.fire({
                    title: '¿Actualizar Entrada?',
                    html: '⚠️ <b>Se modificará el stock</b> de los productos involucrados.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('submitEntrada');  
                    }
                });
            });

            // NUEVO - CONFIRMAR CANCELACIÓN
            Livewire.on('confirmar-cancelacion', () => {
                Swal.fire({
                    title: '¿Cancelar edición?',
                    html: '⚠️ <b>Perderás los cambios no guardados.</b>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#6b7280',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, cancelar',
                    cancelButtonText: 'Volver'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('entradas.index') }}";
                    }
                });
            });

        });
    </script>
    @endpush


</div>