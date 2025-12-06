<div class="max-w-7xl mx-auto p-6">

    @if (session()->has('message'))
        <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-6 py-4 rounded-2xl mb-6 shadow-sm">
            <strong class="font-bold">¡Éxito!</strong>
            <span class="ml-1">{{ session('message') }}</span>
        </div>
    @endif

    <!-- BUSCADOR -->
    <div class="mb-6">
        <input type="text" wire:model.live="search" placeholder="Buscar por proveedor, fecha o observación..."
            class="w-full px-5 py-3 border border-yellow-300 rounded-2xl shadow-sm bg-white/90
                   focus:ring-2 focus:ring-yellow-300 focus:border-yellow-600 text-sm">
    </div>

    <!-- CABECERA -->
    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-black text-slate-900">
                Entradas de Inventario
            </h2>
            <p class="text-sm text-orange-700/90">
                Revisa el historial de ingresos de productos a tu almacén.
            </p>
        </div>
        <a href="{{ route('entrada-inventario') }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-yellow-500 via-amber-500 to-orange-600
                   hover:from-yellow-600 hover:via-amber-600 hover:to-orange-700
                   text-white font-bold px-6 py-3 rounded-2xl shadow-lg transition transform hover:-translate-y-0.5">
            <span class="text-lg leading-none">+</span>
            <span class="text-sm">Nueva Entrada</span>
        </a>
    </div>

    <!-- TABLA ENTRADAS -->
    <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gradient-to-r from-yellow-500 via-amber-500 to-orange-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide">Proveedor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide">Fecha</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wide">Observación</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wide">Total</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide">Detalles</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold uppercase tracking-wide">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-yellow-50">
                    @forelse($entradas as $entrada)
                        <tr class="hover:bg-yellow-50/70 transition-colors">
                            <td class="px-6 py-4 font-semibold text-slate-800">
                                #{{ $entrada->id }}
                            </td>
                            <td class="px-6 py-4 text-slate-800">
                                {{ $entrada->proveedor->nombre }}
                            </td>
                            <td class="px-6 py-4 text-slate-700">
                                {{ \Carbon\Carbon::parse($entrada->fecha)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ Str::limit($entrada->observacion, 50) ?: '—' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-xs font-bold">
                                    ${{ number_format($entrada->total, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button wire:click="verDetalles({{ $entrada->id }})"
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-semibold
                                           bg-amber-100 text-amber-800 border border-amber-200 hover:bg-amber-200 transition">
                                    Ver detalles
                                </button>
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="{{ route('entradas.edit', $entrada->id) }}"
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-semibold
                                           bg-yellow-500 hover:bg-yellow-600 text-white shadow-sm transition">
                                    Editar
                                </a>
                                <button wire:click="confirmDelete({{ $entrada->id }})"
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-semibold
                                           bg-rose-600 hover:bg-rose-700 text-white shadow-sm transition">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-12 text-slate-500 text-lg">
                                No se encontraron entradas de inventario.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 bg-yellow-50 border-t border-yellow-100">
            {{ $entradas->links() }}
        </div>

        {{-- COMPARACIÓN DE COSTOS (Entrada vs Costo actual del producto) --}}
        @if ($comparaciones->count())
            <div class="mt-10 px-6 pb-6">
                <div class="flex flex-col sm:flex-row items-center justify-between mb-4 gap-3">
                    <div>
                        <h2 class="text-xl font-black text-slate-900">
                            Comparación de costos de compra
                        </h2>
                        <p class="text-xs text-orange-700/90">
                            Costo registrado en las últimas entradas vs costo de compra actual del producto.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-md border border-yellow-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-xs md:text-sm">
                            <thead class="bg-gradient-to-r from-yellow-400 via-yellow-500 to-orange-500 text-white">
                                <tr>
                                    <th class="px-4 py-3 text-left font-semibold">Entrada</th>
                                    <th class="px-4 py-3 text-left font-semibold">Fecha</th>
                                    <th class="px-4 py-3 text-left font-semibold">Proveedor</th>
                                    <th class="px-4 py-3 text-left font-semibold">Producto</th>
                                    <th class="px-4 py-3 text-center font-semibold">Costo entrada</th>
                                    <th class="px-4 py-3 text-center font-semibold">Costo Compra(Producto)</th>
                                    <th class="px-4 py-3 text-center font-semibold">Diferencia</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-yellow-50 bg-white">
                                @foreach ($comparaciones as $fila)
                                    @php
                                        $dif = $fila['diferencia_abs'];
                                        $esMayor = $dif > 0;
                                        $esMenor = $dif < 0;
                                    @endphp
                                    <tr class="hover:bg-yellow-50/70 transition-colors">
                                        <td class="px-4 py-3 text-[0.75rem] text-slate-600">
                                            #{{ $fila['entrada_id'] }}
                                        </td>
                                        <td class="px-4 py-3 text-[0.75rem] text-slate-700">
                                            {{ \Carbon\Carbon::parse($fila['fecha'])->format('d/m/Y') }}
                                        </td>
                                        <td class="px-4 py-3 text-[0.75rem] text-slate-700">
                                            {{ $fila['proveedor'] ?? '—' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="text-sm font-semibold text-slate-900">
                                                {{ $fila['producto'] }}
                                            </div>
                                            @if ($fila['codigo'])
                                                <div class="text-[0.7rem] text-slate-500 font-mono">
                                                    {{ $fila['codigo'] }}
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 text-[0.7rem] font-semibold">
                                                Bs {{ number_format($fila['costo_entrada'], 2) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full bg-slate-50 text-slate-800 border border-slate-200 text-xs font-semibold">
                                                Bs {{ number_format($fila['costo_compra_ref'], 2) }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-3 text-center">
                                            @if ($fila['diferencia_pct'] === null)
                                                <span class="text-[0.7rem] text-slate-400">Sin referencia</span>
                                            @else
                                                <div class="inline-flex flex-col items-center">
                                                    <span
                                                        class="px-3 py-1 rounded-full text-[0.7rem] font-bold
                                                        @if ($esMayor) bg-rose-100 text-rose-700 border border-rose-200
                                                        @elseif($esMenor)
                                                            bg-emerald-100 text-emerald-700 border border-emerald-200
                                                        @else
                                                            bg-slate-100 text-slate-700 border border-slate-200 @endif
                                                    ">
                                                        {{ $esMayor ? '+' : '' }}Bs {{ number_format($dif, 2) }}
                                                    </span>
                                                    <span class="mt-1 text-[0.7rem] text-slate-500">
                                                        {{ $esMayor ? '+' : '' }}{{ number_format($fila['diferencia_pct'], 1) }}%
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="px-4 py-3 bg-yellow-50 border-t border-yellow-200 text-[0.7rem] text-slate-600">
                        Mostrando hasta 30 movimientos recientes para comparación rápida.
                    </div>
                </div>
            </div>
        @endif

    </div>

    {{-- MODAL DETALLE --}}
    @if ($detalleVisible && $entradaDetalle)
        <div x-data="{ open: true }" x-show="open" x-transition @keydown.escape.window="open = false"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/70 p-4">
            <div @click.away="$wire.cerrarModal(); open = false"
                class="bg-white rounded-3xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto border border-yellow-200">
                <div
                    class="flex justify-between items-center p-6 border-b bg-gradient-to-r from-yellow-500 via-amber-500 to-orange-600 text-white rounded-t-3xl">
                    <h3 class="text-2xl font-black">
                        Detalle de Entrada #{{ $entradaDetalle->id }}
                    </h3>
                    <button @click="$wire.cerrarModal(); open = false"
                        class="text-3xl leading-none hover:scale-110 transition">
                        &times;
                    </button>
                </div>

                <div class="p-6 space-y-6">
                    <div
                        class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-yellow-50 p-5 rounded-2xl border border-yellow-100">
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase tracking-wide">Proveedor</p>
                            <p class="text-lg font-bold text-slate-900">
                                {{ $entradaDetalle->proveedor->nombre }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase tracking-wide">Fecha</p>
                            <p class="text-lg font-bold text-slate-900">
                                {{ $entradaDetalle->fecha->format('d \\de F \\de Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase tracking-wide">Total</p>
                            <p class="text-2xl font-black text-emerald-600">
                                ${{ number_format($entradaDetalle->total, 2) }}
                            </p>
                        </div>
                    </div>

                    @if ($entradaDetalle->observacion)
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase tracking-wide mb-1">
                                Observación
                            </p>
                            <p class="bg-slate-50 border border-slate-200 p-4 rounded-2xl text-sm text-slate-800">
                                {{ $entradaDetalle->observacion }}
                            </p>
                        </div>
                    @endif

                    <div>
                        <h4 class="text-lg font-black mb-3 text-slate-900">
                            Productos incluidos
                        </h4>
                        <div class="overflow-x-auto rounded-2xl border border-yellow-100">
                            <table class="w-full text-sm">
                                <thead class="bg-slate-900 text-white">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wide">
                                            Producto</th>
                                        <th
                                            class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wide">
                                            Cantidad</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide">
                                            Costo</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wide">
                                            Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach ($entradaDetalle->detalles as $detalle)
                                        <tr class="hover:bg-slate-50">
                                            <td class="px-6 py-4 font-medium text-slate-900">
                                                {{ $detalle->producto->nombre }}
                                            </td>
                                            <td class="px-6 py-4 text-center font-bold text-slate-800">
                                                {{ $detalle->cantidad }}
                                            </td>
                                            <td class="px-6 py-4 text-right text-slate-700">
                                                ${{ number_format($detalle->costo, 2) }}
                                            </td>
                                            <td class="px-6 py-4 text-right font-bold text-emerald-600">
                                                ${{ number_format($detalle->subtotal, 2) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="bg-slate-50 font-bold text-sm">
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-right text-slate-700">
                                            TOTAL:
                                        </td>
                                        <td class="px-6 py-4 text-right text-emerald-600">
                                            ${{ number_format($entradaDetalle->total, 2) }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="p-6 border-t bg-yellow-50 rounded-b-3xl text-right">
                    <button @click="$wire.cerrarModal(); open = false"
                        class="bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700
                               text-white font-semibold py-2.5 px-8 rounded-2xl shadow-md transition transform hover:-translate-y-0.5">
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
    }" x-init="$watch('showToast', value => {
        if (value) {
            setTimeout(() => { showToast = false }, 5000);
        }
    })" x-show="showToast"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="transform translate-x-full opacity-0"
        x-transition:enter-end="transform translate-x-0 opacity-100"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="transform translate-x-0 opacity-100"
        x-transition:leave-end="transform translate-x-full opacity-0" class="fixed top-4 right-4 z-50 max-w-sm">
        <div :class="{
            'bg-rose-600': toastType === 'error',
            'bg-emerald-600': toastType === 'success',
            'bg-blue-600': toastType === 'info'
        }"
            class="text-white px-6 py-4 rounded-2xl shadow-2xl flex items-center space-x-3 border border-white/30">
            <div>
                @if ($toastType === 'success')
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                @elseif($toastType === 'error')
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                @else
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"></path>
                    </svg>
                @endif
            </div>
            <div class="font-semibold text-sm">
                <span x-text="toastMessage"></span>
            </div>
            <button @click="showToast = false" class="ml-2 text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12">
                    </path>
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
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    customClass: {
                        popup: 'rounded-3xl',
                        confirmButton: 'rounded-2xl px-6 py-2 font-semibold',
                        cancelButton: 'rounded-2xl px-6 py-2 font-semibold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.call('delete', id);
                    }
                });
            });
        });
    </script>
</div>
