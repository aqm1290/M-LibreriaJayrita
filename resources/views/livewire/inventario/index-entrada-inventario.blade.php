<div class="max-w-7xl mx-auto p-6">

    @if(session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-800 px-6 py-4 rounded-lg mb-6 shadow-md">
            <strong>¡Éxito!</strong> {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <h2 class="text-3xl font-extrabold text-gray-800">Entradas de Inventario</h2>
        <a href="{{ route('entrada-inventario') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-lg shadow-lg transition">
            + Nueva Entrada
        </a>
    </div>

    <div class="mb-6">
        <input type="text" wire:model.live="search"
               placeholder="Buscar por proveedor, fecha o observación..."
               class="w-full px-5 py-3 border border-gray-300 rounded-lg shadow focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-gray-800 to-gray-900 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase">Proveedor</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase">Fecha</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase">Observación</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase">Total</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase">Detalles</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($entradas as $entrada)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 font-medium">#{{ $entrada->id }}</td>
                            <td class="px-6 py-4">{{ $entrada->proveedor->nombre }}</td>
                            <td class="px-6 py-4">{{ \Carbon\Carbon::parse($entrada->fecha)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ Str::limit($entrada->observacion, 50) ?: '—' }}</td>
                            <td class="px-6 py-4 text-right font-bold text-green-600">
                                ${{ number_format($entrada->total, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="verDetalles({{ $entrada->id }})"
                                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                                    Ver Detalles
                                </button>
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="{{ route('entradas.edit', $entrada->id) }}"
                                   class="inline-block bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                                    Editar
                                </a>
                                <button wire:click="confirmDelete({{ $entrada->id }})"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-gray-500 text-lg">
                                No se encontraron entradas de inventario.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-gray-50">
            {{ $entradas->links() }}
        </div>
    </div>

    {{-- MODAL DETALLES - AHORA SÍ FUNCIONA 100% --}}
    {{-- MODAL DETALLES - AHORA SÍ 100% SIN ERRORES --}}
@if($detalleVisible && $entradaDetalle)
    <div x-data="{ open: true }"
         x-show="open"
         x-transition
         @keydown.escape.window="open = false"
         class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-70 p-4">

        <div @click.away="$wire.cerrarModal(); open = false"
             class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-screen overflow-y-auto">

            <div class="flex justify-between items-center p-6 border-b bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-2xl">
                <h3 class="text-2xl font-bold">Detalle de Entrada #{{ $entradaDetalle->id }}</h3>
                <button @click="$wire.cerrarModal(); open = false" class="text-3xl">&times;</button>
            </div>

            <div class="p-6 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50 p-5 rounded-xl">
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Proveedor</p>
                        <p class="text-xl font-bold">{{ $entradaDetalle->proveedor->nombre }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Fecha</p>
                        <p class="text-xl font-bold">{{ $entradaDetalle->fecha->format('d \\de F \\de Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Total</p>
                        <p class="text-2xl font-bold text-green-600">${{ number_format($entradaDetalle->total, 2) }}</p>
                    </div>
                </div>

                @if($entradaDetalle->observacion)
                    <div>
                        <p class="text-sm text-gray-600 font-semibold">Observación</p>
                        <p class="bg-gray-100 p-4 rounded-lg">{{ $entradaDetalle->observacion }}</p>
                    </div>
                @endif

                <div>
                    <h4 class="text-xl font-bold mb-4">Productos Incluidos</h4>
                    <div class="overflow-x-auto rounded-lg border">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-800 text-white">
                                <tr>
                                    <th class="px-6 py-3 text-left">Producto</th>
                                    <th class="px-6 py-3 text-center">Cantidad</th>
                                    <th class="px-6 py-3 text-right">Costo</th>
                                    <th class="px-6 py-3 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($entradaDetalle->detalles as $detalle)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium">{{ $detalle->producto->nombre }}</td>
                                        <td class="px-6 py-4 text-center font-bold">{{ $detalle->cantidad }}</td>
                                        <td class="px-6 py-4 text-right">${{ number_format($detalle->costo, 2) }}</td>
                                        <td class="px-6 py-4 text-right font-bold text-green-600">
                                            ${{ number_format($detalle->subtotal, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-100 font-bold text-lg">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right">TOTAL:</td>
                                    <td class="px-6 py-4 text-right text-green-600">
                                        ${{ number_format($entradaDetalle->total, 2) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t bg-gray-50 text-right">
                <button @click="$wire.cerrarModal(); open = false"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition">
                    Cerrar
                </button>
            </div>
        </div>
    </div>
@endif

{{-- NOTIFICACIONES TOAST PERSONALIZADAS --}}
<div x-data="{ 
        showToast: @entangle('showToast'), 
        toastMessage: @entangle('toastMessage'),
        toastType: @entangle('toastType')
     }"
     x-init="
        $watch('showToast', value => {
            if (value) {
                setTimeout(() => {
                    showToast = false;
                }, 5000); // 5 segundos
            }
        })
     "
     x-show="showToast"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="transform translate-x-full opacity-0"
     x-transition:enter-end="transform translate-x-0 opacity-100"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="transform translate-x-0 opacity-100"
     x-transition:leave-end="transform translate-x-full opacity-0"
     class="fixed top-4 right-4 z-50 max-w-sm"
>
    <div :class="{
            'bg-red-600': toastType === 'error',
            'bg-green-600': toastType === 'success',
            'bg-blue-600': toastType === 'info'
         }"
         class="text-white px-6 py-4 rounded-lg shadow-2xl flex items-center space-x-3 border border-white/20"
    >
        <div>
            @if($toastType === 'success')
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            @elseif($toastType === 'error')
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            @endif
        </div>
        <div class="font-bold text-lg">
            <span x-text="toastMessage"></span>
        </div>
        <button @click="showToast = false" class="ml-4 text-white/80 hover:text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>

    {{-- SweetAlert2 funcionando perfecto --}}
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('confirmar-eliminacion', (id) => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "Se eliminará la entrada y se revertirá el stock. ¡Esta acción no se puede deshacer!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('delete', id);
                    }
                });
            });
        });
    </script>
</div>