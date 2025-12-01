<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 text-gray-900">
    <div class="max-w-7xl mx-auto p-4 lg:p-6 grid gap-5 lg:grid-cols-[minmax(0,1.7fr)_minmax(0,1.3fr)]">

        {{-- IZQUIERDA: BUSCADOR + LISTA DE PRODUCTOS --}}
        <div class="space-y-6">

            {{-- Buscador --}}
            <div class="bg-white rounded-3xl shadow-lg p-6 border border-gray-100">
                <div class="flex gap-4 items-center">
                    <div class="flex-1">
                        <input type="text"
                               wire:model.live.debounce.150ms="search"
                               placeholder="Buscar producto por nombre o código (F2)..."
                               class="w-full px-6 py-5 text-xl rounded-2xl bg-gray-50 border border-gray-200 focus:ring-4 focus:ring-[#3483FA]/30 focus:border-[#3483FA] outline-none transition"
                               autofocus>
                    </div>
                    <button wire:click="$set('search','')"
                            class="px-6 py-4 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold transition whitespace-nowrap">
                        Limpiar
                    </button>
                </div>
            </div>

            {{-- Lista de productos en modo tabla --}}
            <div class="bg-white rounded-3xl shadow-lg overflow-hidden border border-gray-100" style="max-height: 65vh; overflow-y: auto;">
                <div class="hidden md:block bg-gradient-to-r from-[#FFE600] to-yellow-400 px-6 py-4 text-sm font-bold text-[#111] uppercase tracking-wide">
                    Productos encontrados
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($productos as $p)
                        <div wire:key="prod-{{ $p->id }}"
                             class="flex items-center gap-4 px-6 py-5 hover:bg-gray-50 transition">
                            <div class="hidden sm:flex items-center justify-center w-24 h-24 rounded-2xl bg-gray-50 border border-gray-200 overflow-hidden">
                                @if($p->url_imagen)
                                    <img src="{{ asset('storage/'.$p->url_imagen) }}" class="max-h-full max-w-full object-contain">
                                @else
                                    <span class="text-sm text-gray-400">Sin imagen</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between gap-4">
                                    <p class="text-lg font-bold text-gray-800 truncate">
                                        {{ $p->nombre }}
                                    </p>
                                    <p class="text-lg font-extrabold text-[#333]">
                                        Bs {{ number_format($p->precio, 2) }}
                                    </p>
                                </div>
                                <div class="mt-2 flex flex-wrap items-center gap-3 text-sm text-gray-600">
                                    <span>Cód: {{ $p->codigo ?: '—' }}</span>
                                    <span class="text-gray-400">•</span>
                                    @if($p->stock > 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-700 font-medium">
                                            Stock: {{ $p->stock }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-700 font-medium">
                                            Agotado
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                @if($p->stock > 0)
                                    <button wire:click="agregarProducto({{ $p->id }})"
                                            class="px-6 py-3 rounded-xl bg-[#3483FA] hover:bg-blue-700 text-white font-bold shadow-lg transform hover:scale-105 transition">
                                        Agregar
                                    </button>
                                @else
                                    <button disabled
                                            class="px-6 py-3 rounded-xl bg-gray-400 text-gray-600 font-bold cursor-not-allowed">
                                        Sin stock
                                    </button>
                                @endif
                            </div>
                        </div>
                    @empty
                        @if($search !== '')
                            <div class="py-24 text-center text-gray-400">
                                <p class="text-3xl font-light">No se encontraron productos para "{{ $search }}"</p>
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>
        </div>

        {{-- DERECHA: TICKET / CLIENTE / TOTAL --}}
        <div class="space-y-6">

            {{-- Ticket + carrito --}}
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-200">
                <div class="p-6 bg-gradient-to-r from-[#FFE600] to-yellow-400 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-700 uppercase">Punto de venta</p>
                        <h2 class="text-3xl font-black text-[#111]">Ticket actual</h2>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-700 uppercase">Ítems</p>
                        <p class="text-4xl font-black text-[#111]">{{ count($cart) }}</p>
                    </div>
                </div>

                <div class="max-h-[320px] overflow-y-auto bg-gray-50">
                    @forelse($cart as $i => $item)
                        <div class="px-6 py-5 border-b border-gray-200 hover:bg-white transition flex items-start gap-6">
                            <div class="flex-1 space-y-2">
                                <p class="text-xl font-bold text-gray-800">
                                    {{ $item['nombre'] }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Bs {{ number_format($item['precio_unitario'], 2) }} c/u
                                </p>
                                <div class="flex items-center gap-4 mt-4">
                                    <button wire:click="decrementarCantidad({{ $i }})"
                                            class="w-12 h-12 rounded-full bg-red-500 hover:bg-red-600 text-white font-black text-2xl shadow-xl transform hover:scale-110 transition active:scale-95">
                                        −
                                    </button>
                                    <input type="text" inputmode="numeric" pattern="[0-9]*"
                                           wire:model.live.debounce.200ms="cart.{{ $i }}.cantidad"
                                           wire:change="actualizarSubtotal({{ $i }})"
                                           onclick="this.select()" onwheel="this.blur()"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value === '') this.value = '1';"
                                           class="w-28 text-center text-4xl font-black text-gray-800 bg-white border-4 border-gray-300 rounded-2xl py-3 focus:border-[#3483FA] focus:ring-4 focus:ring-[#3483FA]/30 outline-none transition">
                                    <button wire:click="incrementarCantidad({{ $i }})"
                                            class="w-12 h-12 rounded-full bg-green-500 hover:bg-green-600 text-white font-black text-2xl shadow-xl transform hover:scale-110 transition active:scale-95">
                                        +
                                    </button>
                                </div>
                            </div>
                            <div class="text-right space-y-3">
                                <p class="text-2xl font-black text-[#333]">
                                    Bs {{ number_format($item['subtotal'], 2) }}
                                </p>
                                <button wire:click="removeItem({{ $i }})"
                                        class="text-sm text-red-600 hover:text-red-800 font-bold hover:underline">
                                    Quitar
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-24 text-center text-gray-400">
                            <p class="text-5xl font-light">Ticket vacío</p>
                            <p class="text-xl mt-3">Agrega productos para comenzar</p>
                        </div>
                    @endforelse
                </div>

                {{-- Cliente + fidelidad + totales --}}
                <div class="p-6 space-y-6 bg-gradient-to-b from-white to-gray-50">

                    {{-- Cliente --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide">Cliente</label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <input type="text" wire:model.live.debounce.300ms="buscarNombre"
                                   placeholder="Buscar por nombre..."
                                   class="w-full px-5 py-4 rounded-xl border-2 border-gray-300 focus:border-[#3483FA] focus:ring-4 focus:ring-[#3483FA]/20 text-lg transition">
                            <input type="text" wire:model.live.debounce.300ms="buscarCi"
                                   placeholder="Buscar por CI / NIT..."
                                   class="w-full px-5 py-4 rounded-xl border-2 border-gray-300 focus:border-[#3483FA] focus:ring-4 focus:ring-[#3483FA]/20 text-lg transition">
                        </div>

                        @if(count($clientesEncontrados) > 0)
                            <div class="mb-4 bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
                                @foreach($clientesEncontrados as $cli)
                                    <button type="button" wire:click="seleccionarCliente({{ $cli->id }})"
                                            class="w-full px-6 py-5 text-left hover:bg-blue-50 transition flex items-center justify-between border-b border-gray-100 last:border-0">
                                        <div>
                                            <div class="font-bold text-gray-800 text-lg">{{ $cli->nombre }}</div>
                                            <div class="text-sm text-gray-600">
                                                @if($cli->ci) CI: {{ $cli->ci }} @endif
                                                @if($cli->telefono) • {{ $cli->telefono }} @endif
                                            </div>
                                        </div>
                                        <span class="bg-[#3483FA] text-white px-4 py-2 rounded-full text-sm font-bold shadow">Usar</span>
                                    </button>
                                @endforeach
                            </div>
                        @endif

                        <div class="flex justify-between items-center bg-gradient-to-r from-blue-50 to-indigo-50 p-5 rounded-2xl border-2 border-blue-200">
                            <div>
                                <p class="text-xl font-black text-gray-800">
                                    {{ $cliente_nombre }}
                                    @if($cliente_documento)
                                        <span class="text-blue-700 font-bold">({{ $cliente_documento }})</span>
                                    @endif
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $cliente_id ? 'Cliente registrado' : 'Venta rápida' }}
                                </p>
                            </div>
                            <button type="button" wire:click="abrirModalCliente"
                                    class="px-6 py-4 bg-[#3483FA] hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transform hover:scale-105 transition">
                                + Nuevo cliente
                            </button>
                        </div>
                    </div>

                    {{-- Cliente fiel --}}
                    @if($cliente_id)
                        @php
                            $clienteFiel = \App\Models\Cliente::find($cliente_id);
                            $compras = $clienteFiel?->fidelidad?->compras_realizadas ?? 0;
                            $falta = max(0, 10 - $compras);
                            $premioListo = $compras >= 10 && !($clienteFiel?->fidelidad?->premio_entregado ?? true);
                        @endphp
                        <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border-3 border-amber-400 rounded-2xl p-5 text-center shadow-xl">
                            <p class="text-xl font-black text-amber-800 flex items-center justify-center gap-3">
                                <svg class="w-9 h-9 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2l3.09 6.26L19 8.27l-4.5 4.36 1.06 6.18L10 15.82l-5.56 2.92 1.06-6.18L1 8.27l5.91-.01L10 2z"/>
                                </svg>
                                Cliente fiel: <span class="text-4xl text-amber-600">{{ $compras }}</span><span class="text-2xl">/10</span>
                                @if($premioListo)
                                    <span class="ml-4 bg-green-600 text-white px-5 py-2 rounded-full font-bold animate-pulse shadow-lg">
                                        Premio listo
                                    </span>
                                @else
                                    <span class="ml-4 text-amber-700 font-medium">(faltan {{ $falta }})</span>
                                @endif
                            </p>
                        </div>
                    @endif

                    {{-- Totales --}}
                    <div class="space-y-6">
                        <div class="flex justify-between text-xl font-bold">
                            <span>Subtotal</span>
                            <span>Bs {{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold">Descuento (Bs)</span>
                            <input type="text" inputmode="decimal" wire:model.lazy="descuento"
                                   class="w-40 text-right text-2xl font-bold border-b-4 border-yellow-500 focus:border-[#3483FA] outline-none">
                        </div>
                        <div class="text-3xl font-black flex justify-between py-4 bg-gray-100 rounded-2xl px-6">
                            <span>Total a pagar</span>
                            <span class="text-[#3483FA]">Bs {{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    {{-- Método de pago --}}
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-3 uppercase tracking-wide">Método de pago</label>
                        <select wire:model.live="metodo_pago"
                                class="w-full px-6 py-5 rounded-xl bg-gray-100 font-bold text-xl focus:ring-4 focus:ring-[#3483FA]/30">
                            <option value="efectivo">Efectivo</option>
                            <option value="qr">QR / Transferencia</option>
                        </select>
                    </div>

                    @if($metodo_pago === 'efectivo')
                        <div class="space-y-6">
                            <input type="text" inputmode="decimal" wire:model.lazy="efectivo_recibido"
                                   placeholder="Monto recibido"
                                   class="w-full text-center text-6xl font-black py-8 rounded-3xl bg-green-50 text-green-700 border-4 border-green-300 focus:border-green-500 focus:ring-4 focus:ring-green-200 outline-none">
                            <div class="@if($cambio >= 0) bg-gradient-to-br from-green-600 to-green-700 @else bg-red-600 @endif text-white p-6 rounded-3xl text-center shadow-2xl">
                                <p class="text-3xl font-bold">@if($cambio >= 0) Cambio a devolver @else Falta dinero @endif</p>
                                <p class="text-5xl font-black mt-3">Bs {{ number_format(abs($cambio), 2) }}</p>
                            </div>
                        </div>
                    @endif

                    {{-- Botón finalizar --}}
                    <button wire:click="confirmarVenta"
                            @if($metodo_pago === 'efectivo' && $cambio < 0) disabled
                            class="w-full py-8 bg-gray-400 text-gray-200 rounded-3xl font-black text-4xl cursor-not-allowed opacity-70"
                            @else
                            class="w-full py-8 bg-[#3483FA] hover:bg-blue-700 text-white rounded-3xl font-black text-4xl shadow-2xl transform hover:scale-105 transition"
                            @endif>
                        @if($metodo_pago === 'efectivo' && $cambio < 0)
                            Falta pagar Bs {{ number_format(abs($cambio), 2) }}
                        @else
                            Finalizar venta (F4)
                        @endif
                    </button>
                </div>
            </div>

            {{-- Modal nuevo cliente --}}
            @if($mostrarModalCliente)
                <div class="fixed inset-0 bg-black bg-opacity-60 z-[9999] flex items-center justify-center p-4 backdrop-blur-sm">
                    <div class="bg-white rounded-3xl shadow-3xl max-w-2xl w-full max-h-screen overflow-y-auto">
                        <div class="p-8 pb-4 text-center">
                            <h2 class="text-4xl font-black text-gray-800 mb-2">Nuevo cliente</h2>
                            <p class="text-gray-600">Se creará y seleccionará para esta venta.</p>
                        </div>
                        <div class="px-8 pb-8 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div><input type="text" wire:model="nuevoCliente.nombre" placeholder="Nombre completo *" class="w-full px-6 py-4 rounded-xl border-2 border-gray-300 focus:border-[#3483FA] focus:ring-4 focus:ring-[#3483FA]/20 text-lg"></div>
                                <div><input type="text" wire:model="nuevoCliente.ci" placeholder="CI / NIT" class="w-full px-6 py-4 rounded-xl border-2 border-gray-300 focus:border-[#3483FA] focus:ring-4 focus:ring-[#3483FA]/20 text-lg"></div>
                                <div><input type="text" wire:model="nuevoCliente.telefono" placeholder="Teléfono" class="w-full px-6 py-4 rounded-xl border-2 border-gray-300 focus:border-[#3483FA] focus:ring-4 focus:ring-[#3483FA]/20 text-lg"></div>
                                <div><input type="email" wire:model="nuevoCliente.correo" placeholder="Correo (opcional)" class="w-full px-6 py-4 rounded-xl border-2 border-gray-300 focus:border-[#3483FA] focus:ring-4 focus:ring-[#3483FA]/20 text-lg"></div>
                                <div class="md:col-span-2"><input type="text" wire:model="nuevoCliente.direccion" placeholder="Dirección (opcional)" class="w-full px-6 py-4 rounded-xl border-2 border-gray-300 focus:border-[#3483FA] focus:ring-4 focus:ring-[#3483FA]/20 text-lg"></div>
                            </div>
                            <div class="flex gap-4 pt-6">
                                <button wire:click="$set('mostrarModalCliente', false)" class="flex-1 py-5 rounded-xl bg-gray-200 hover:bg-gray-300 font-bold text-xl transition">Cancelar</button>
                                <button wire:click="crearCliente" class="flex-1 py-5 rounded-xl bg-[#3483FA] hover:bg-blue-700 text-white font-black text-xl shadow-lg transition">Crear y seleccionar</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

    {{-- Scripts (los tuyos, solo ligeramente adaptados visualmente) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.addEventListener('toast', e => Swal.fire({
                toast: true,
                position: 'top-end',
                timer: 2500,
                icon: 'success',
                title: e.detail,
                showConfirmButton: false,
            }));

            window.addEventListener('confirmar-venta', e => {
                const d = e.detail;
                let lista = '<div style="text-align:left; max-height:220px; overflow-y:auto; font-size:13px;">';
                d.productos.forEach(p => {
                    lista += `• <strong>${p.nombre}</strong> ×${p.cantidad} → Bs ${parseFloat(p.subtotal).toFixed(2)}<br>`;
                });
                lista += '</div>';
                Swal.fire({
                    title: '¿Finalizar venta?',
                    html: `
                        <p><strong>Total:</strong>
                        <span style="font-size:24px; font-weight:800; color:#111827;">Bs ${parseFloat(d.total).toFixed(2)}</span></p>
                        <p style="margin-top:4px;"><strong>Ítems:</strong> ${d.items}</p>
                        <hr style="margin:10px 0;">${lista}
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Cobrar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#111827',
                    cancelButtonColor: '#6b7280',
                    customClass: { popup: 'rounded-2xl' },
                }).then(r => r.isConfirmed && @this.call('finalizarVenta'));
            });

            window.addEventListener('venta-creada', e => {
                Swal.fire({
                    title: 'Venta completada',
                    text: 'Venta #' + e.detail,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Imprimir ticket',
                    cancelButtonText: 'Cerrar',
                    reverseButtons: true,
                    customClass: { popup: 'rounded-2xl' },
                }).then(r => {
                    if (r.isConfirmed) window.open('/venta/ticket/' + e.detail, '_blank');
                });
            });

            window.addEventListener('premio-cliente-fiel', event => {
                const data = event.detail;
                Swal.fire({
                    icon: 'success',
                    title: '¡Premio disponible!',
                    html: `
                        <div style="text-align:center;">
                            <p style="font-size:22px; font-weight:800; color:#16a34a; margin-bottom:6px;">
                                ¡10 compras completadas!
                            </p>
                            <p style="font-size:16px; font-weight:600;">
                                Cliente: <span style="text-decoration:underline;">${data.nombre}</span>
                            </p>
                            ${data.ci !== 'Sin CI' ? `<p style="font-size:14px; color:#4b5563;">CI: ${data.ci}</p>` : ''}
                        </div>
                    `,
                    confirmButtonText: 'Entregar premio y reiniciar',
                    confirmButtonColor: '#16a34a',
                    showCancelButton: true,
                    cancelButtonText: 'Después',
                    allowOutsideClick: false,
                    customClass: { popup: 'rounded-2xl' },
                }).then(result => {
                    if (result.isConfirmed) @this.call('entregarPremioClienteFiel', data.cliente_id);
                });
            });

            document.addEventListener('keydown', e => {
                if (['INPUT','TEXTAREA','SELECT'].includes(e.target.tagName)) return;
                if (e.key === 'F4') { e.preventDefault(); @this.call('confirmarVenta'); }
                if (e.key === 'F9') { e.preventDefault(); @this.call('limpiarCarrito'); }
                if (e.key === 'F2') { e.preventDefault(); document.querySelector('input[wire\\:model\\.live]')?.focus(); }
                if (e.key === 'F6') { e.preventDefault(); @this.call('abrirModalCliente'); }
            });
        });
    </script>
