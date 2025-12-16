<div class="min-h-screen bg-gradient-to-br from-yellow-100 via-yellow-50 to-orange-100 py-12 px-6">
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- CABECERA --}}
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                    Pedidos Web
                </h1>
                <p class="mt-2 text-base md:text-lg text-orange-700/90">
                    Gestión completa de reservas y entregas de Librería Jayrita.
                </p>
            </div>

            <button wire:click="cancelarVencidosManual"
                class="inline-flex items-center gap-3 px-6 py-3 md:px-8 md:py-4
                       bg-gradient-to-r from-rose-500 via-rose-600 to-red-600
                       hover:from-rose-600 hover:via-rose-700 hover:to-red-700
                       text-white font-semibold md:font-black text-sm md:text-lg
                       rounded-2xl shadow-[0_14px_40px_rgba(248,113,113,0.65)]
                       transform hover:-translate-y-0.5 hover:scale-[1.02]
                       transition">
                CANCELAR VENCIDOS
            </button>
        </div>

        {{-- FILTRO + CONTADOR --}}
        <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 p-5 md:p-6">
            <div class="flex flex-col md:flex-row gap-6 items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-xs md:text-sm font-semibold text-slate-700 uppercase tracking-wide">
                        Filtrar por
                    </span>
                    <select wire:model.live="estadoFiltro"
                        class="px-5 py-3 border border-yellow-300 rounded-2xl bg-white/80 text-sm font-semibold
                               focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 transition">
                        <option value="reservado">Reservados</option>
                        <option value="confirmado">Confirmados</option>
                        <option value="entregado">Entregados</option>
                        <option value="cancelado">Cancelados</option>
                        <option value="todos">Todos</option>
                    </select>
                </div>

                <div class="text-center">
                    <div class="text-4xl md:text-5xl font-black text-emerald-600 leading-none">
                        {{ $pedidos->total() }}
                    </div>
                    <div class="text-xs md:text-sm text-slate-600 mt-1 uppercase tracking-wide font-semibold">
                        pedidos {{ $estadoFiltro }}
                    </div>
                </div>
            </div>
        </div>

        {{-- GRID DE PEDIDOS --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-7">
            @forelse($pedidos as $pedido)
                <div
                    class="rounded-2xl border {{ $pedido->estado === 'entregado' ? 'bg-emerald-50 border-emerald-200' : 'bg-white border-yellow-200' }} shadow-xl hover:shadow-2xl hover:-translate-y-1 transition">

                    {{-- HEADER --}}
                    <div
                        class="px-6 py-4 border-b {{ $pedido->estado === 'entregado' ? 'bg-gradient-to-r from-emerald-400 to-green-500 border-emerald-200' : 'bg-gradient-to-r from-yellow-400 via-yellow-500 to-orange-500 border-yellow-300' }} text-black">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wide opacity-80">Pedido</p>
                                <h3 class="text-2xl font-black">#{{ $pedido->id }}</h3>
                            </div>
                            <div class="text-right space-y-1">
                                <span class="block text-xs font-bold uppercase tracking-wider">
                                    {{ $pedido->items->count() }} ítems
                                </span>
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-black bg-black/60 text-yellow-300">
                                    {{ $pedido->estado_texto ?? ucfirst($pedido->estado) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- BODY --}}
                    <div class="p-6 space-y-4">
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Cliente</p>
                            <p class="mt-1 text-xl font-black text-emerald-700">
                                {{ $pedido->cliente_nombre }}
                            </p>
                            <p class="text-sm text-slate-600 flex items-center gap-2 mt-1">
                                {{ $pedido->cliente_telefono }}
                            </p>
                        </div>

                        <div class="border-t border-yellow-100 pt-4 flex justify-between items-end gap-4">
                            <div>
                                <p class="text-3xl font-black text-yellow-500">
                                    Bs {{ number_format($pedido->total, 2) }}
                                </p>
                                <p class="text-xs text-slate-500 mt-1">
                                    {{ $pedido->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>

                            <div class="flex flex-col gap-3 items-end">
                                <button wire:click="verDetalle({{ $pedido->id }})"
                                    class="px-4 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold shadow-sm transition">
                                    Ver
                                </button>

                                @if ($pedido->estado === 'reservado')
                                    <button x-data
                                        x-on:click="
                                            Swal.fire({
                                                title: 'Confirmar Pedido #{{ $pedido->id }}',
                                                html: `
                                                    <div class='text-center space-y-4'>
                                                        <p class='text-lg font-bold'>Estás a punto de <span class='text-emerald-600'>CONFIRMAR</span> este pedido</p>
                                                        <div class='bg-yellow-50 border-2 border-yellow-300 rounded-xl p-4'>
                                                            <p class='font-black text-xl'>{{ $pedido->cliente_nombre }}</p>
                                                            <p class='text-2xl font-black text-yellow-600'>Bs {{ number_format($pedido->total, 2) }}</p>
                                                        </div>
                                                        <p class='text-sm text-gray-600'>El cliente será notificado por WhatsApp</p>
                                                    </div>
                                                `,
                                                icon: 'question',
                                                showCancelButton: true,
                                                confirmButtonText: 'Confirmar Pedido',
                                                cancelButtonText: 'Cancelar',
                                                confirmButtonColor: '#10b981',
                                                width: '500px'
                                            }).then(r => r.isConfirmed && @this.call('cambiarEstado', {{ $pedido->id }}, 'confirmado'))
                                        "
                                        class="px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-sm shadow-lg transition transform hover:scale-105">
                                        Confirmar
                                    </button>

                                    <button x-data
                                        x-on:click="
                                            Swal.fire({
                                                title: '¿Cancelar Pedido?',
                                                text: 'Esta acción no se puede deshacer',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonText: 'Sí, Cancelar',
                                                cancelButtonText: 'No',
                                                confirmButtonColor: '#ef4444',
                                                width: '480px'
                                            }).then(r => r.isConfirmed && @this.call('cambiarEstado', {{ $pedido->id }}, 'cancelado'))
                                        "
                                        class="px-5 py-3 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-black text-sm shadow-lg transition transform hover:scale-105">
                                        Cancelar
                                    </button>
                                @elseif($pedido->estado === 'confirmado')
                                    <button wire:click="marcarEntregado({{ $pedido->id }})"
                                        class="px-6 py-4 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white font-black rounded-xl shadow-2xl transform hover:scale-110 transition text-lg">
                                        ENTREGADO
                                    </button>
                                @elseif($pedido->estado === 'entregado')
                                    <div
                                        class="px-6 py-4 bg-emerald-600 text-white font-black rounded-xl text-center shadow-lg">
                                        <p class="text-sm uppercase tracking-wider">Entregado</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <h3 class="text-2xl font-black text-slate-500">No hay pedidos {{ $estadoFiltro }}</h3>
                    <p class="text-base text-slate-400 mt-2">Todo bajo control por ahora.</p>
                </div>
            @endforelse
        </div>

        {{-- PAGINACIÓN --}}
        <div class="px-6 py-5 bg-white border border-yellow-200 rounded-2xl shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="text-sm text-slate-600">
                    Mostrando <span class="font-bold text-slate-900">{{ $pedidos->firstItem() }}</span>
                    al <span class="font-bold text-slate-900">{{ $pedidos->lastItem() }}</span>
                    de <span class="font-bold text-slate-900">{{ $pedidos->total() }}</span> resultados
                </div>
                <div class="flex items-center gap-2">
                    {{ $pedidos->onEachSide(1)->links('vendor.pagination.tailwind-espanol') }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DETALLE --}}
    @if ($pedidoDetalle)
        <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
            <div class="absolute inset-0" wire:click="$set('pedidoDetalle', null)"></div>
            <div
                class="relative bg-white rounded-3xl shadow-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-yellow-200">
                <div
                    class="bg-gradient-to-r from-yellow-400 to-orange-500 p-5 rounded-t-3xl flex justify-between items-center text-black">
                    <h3 class="text-2xl font-black">Pedido #{{ $pedidoDetalle->id }}</h3>
                    <button wire:click="$set('pedidoDetalle', null)" class="text-2xl">x</button>
                </div>
                <div class="p-6 space-y-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                        <p class="font-black text-xl">{{ $pedidoDetalle->cliente_nombre }}</p>
                        <p class="text-emerald-700">{{ $pedidoDetalle->cliente_telefono }}</p>
                    </div>
                    <div class="space-y-3">
                        @foreach ($pedidoDetalle->items as $item)
                            <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                <div>
                                    <p class="font-semibold">{{ $item->nombre_producto }}</p>
                                    <p class="text-xs text-gray-600">{{ $item->cantidad }} x Bs
                                        {{ number_format($item->precio_unitario, 2) }}</p>
                                </div>
                                <p class="font-bold text-yellow-600">Bs {{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 rounded-xl p-5 text-center text-black">
                        <p class="text-4xl font-black">Bs {{ number_format($pedidoDetalle->total, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {

            Livewire.on('abrir-modal-pago', (payload) => {
                const data = Array.isArray(payload) ? payload[0] : payload;
                const pedidoId = data.pedidoId;
                const total = parseFloat(data.total) || 0;
                const cliente = data.cliente || 'Cliente';

                let metodo = null;

                Swal.fire({
                    title: 'Pago del Pedido',
                    width: '520px',
                    padding: '2rem',
                    html: `
                <div class="space-y-6">
                    <div class="bg-gradient-to-r from-yellow-400 to-orange-500 p-5 rounded-2xl text-black font-black text-center">
                        <p class="text-lg">${cliente}</p>
                        <p class="text-4xl mt-2">Bs ${total.toFixed(2)}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <button id="btn-efectivo" class="p-8 rounded-2xl border-4 border-emerald-500 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-black text-2xl transition-all hover:scale-105 shadow-xl">
                            EFECTIVO
                        </button>
                        <button id="btn-qr" class="p-8 rounded-2xl border-4 border-blue-500 bg-blue-50 hover:bg-blue-100 text-blue-800 font-black text-2xl transition-all hover:scale-105 shadow-xl">
                            QR
                        </button>
                    </div>

                    <div id="monto-div" class="hidden">
                        <input type="number" step="0.01" id="monto-input" class="w-full px-6 py-5 text-4xl text-center rounded-2xl border-4 border-yellow-500 focus:outline-none" placeholder="0.00">
                    </div>

                    <div id="qr-div" class="hidden text-center">
                        <div id="qr-code-canvas" class="inline-block p-6 bg-white rounded-2xl shadow-2xl"></div>
                    </div>
                </div>
            `,
                    showCancelButton: true,
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Cancelar',
                    allowOutsideClick: false,
                    didOpen: () => {
                        // BOTÓN EFECTIVO
                        document.getElementById('btn-efectivo').onclick = () => {
                            metodo = 'efectivo';
                            document.getElementById('monto-div').classList.remove('hidden');
                            document.getElementById('qr-div').classList.add('hidden');
                            document.getElementById('monto-input').focus();
                        };

                        // BOTÓN QR → AHORA SÍ GENERA EL QR
                        document.getElementById('btn-qr').onclick = () => {
                            metodo = 'qr';
                            document.getElementById('monto-div').classList.add('hidden');
                            document.getElementById('qr-div').classList.remove('hidden');

                            const canvas = document.getElementById('qr-code-canvas');
                            canvas.innerHTML = ''; // Limpiar

                            new QRCode(canvas, {
                                text: `PAGO JAYRITA - Pedido #${pedidoId} - Bs ${total.toFixed(2)}`,
                                width: 240,
                                height: 240,
                                colorDark: "#000000",
                                colorLight: "#ffffff",
                            });
                        };
                    },
                    preConfirm: () => {
                        if (!metodo) return Swal.showValidationMessage('Elige un método');
                        if (metodo === 'efectivo' && (document.getElementById('monto-input')
                                .value < total)) {
                            return Swal.showValidationMessage('Monto insuficiente');
                        }
                        return {
                            pedidoId,
                            metodo,
                            recibido: metodo === 'qr' ? total : parseFloat(document
                                .getElementById('monto-input').value || 0),
                            vuelto: metodo === 'qr' ? 0 : parseFloat(document.getElementById(
                                'monto-input').value || 0) - total
                        };
                    }
                }).then(r => {
                    if (r.isConfirmed) {
                        @this.call('procesarEntrega', r.value.pedidoId, r.value.metodo, r.value
                            .recibido, r.value.vuelto);
                    }
                });
            });

            // TOAST + IMPRESIÓN CORREGIDA
            Livewire.on('mostrar-toast', e => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: e.mensaje,
                    toast: true,
                    position: 'top-end',
                    timer: 3000,
                    timerProgressBar: true
                });
            });

            Livewire.on('imprimir-ticket', e => {
                const url = `{{ route('ticket.web', ['venta' => 'ID_VENTA']) }}`.replace('ID_VENTA', e
                    .ventaId);
                const win = window.open(url, '_blank', 'width=400,height=700');
                if (win) win.focus();
            });
        });
    </script>
@endpush
