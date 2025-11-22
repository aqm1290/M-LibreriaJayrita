<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 text-gray-900">
    {{-- POS ULTRA PRO 2025 - By Grok + Tú --}}
    
    <div class="max-w-7xl mx-auto p-4 lg:p-6">

        <!-- BUSCADOR -->
        <div class="bg-white rounded-3xl shadow-lg p-6 mb-6 border border-gray-100">
            <div class="flex gap-4 items-center">
                <div class="flex-1">
                    <input type="text"
                           wire:model.live.debounce.150ms="search"
                           placeholder="Buscar por nombre o código... (F2)"
                           class="w-full px-6 py-5 text-xl rounded-2xl bg-gray-50 border border-gray-200 focus:ring-4 focus:ring-[#3483FA]/30 focus:border-[#3483FA] outline-none transition"
                           autofocus>
                </div>
                <button wire:click="$set('search','')"
                        class="px-6 py-4 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold transition whitespace-nowrap">
                    Limpiar
                </button>
            </div>
        </div>

        <!-- LISTA DE PRODUCTOS -->
        <div class="bg-white rounded-3xl shadow-lg p-6 mb-6 border border-gray-100" style="max-height: 65vh; overflow-y: auto;">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-5">

                @forelse($productos as $p)
                <div wire:key="{{ $p->id }}"
                     class="group bg-white rounded-2xl overflow-hidden shadow hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 border border-gray-200">

                    <!-- Imagen CORREGIDA (sin cuadrados duplicados) -->
                    <div class="h-40 bg-gray-50 p-4 flex items-center justify-center">
                        @if ($p->url_imagen)
                            <img src="{{ asset('storage/' . $p->url_imagen) }}" 
                                 class="max-h-full max-w-full object-contain rounded-lg">
                        @else
                            <div class="w-24 h-24 bg-gray-200 rounded-xl flex items-center justify-center border-2 border-dashed border-gray-300">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="p-4 text-center space-y-2">
                        <h3 class="font-bold text-gray-800 text-sm line-clamp-2 leading-tight">{{ $p->nombre }}</h3>
                        <p class="text-xs text-gray-500">Cód: {{ $p->codigo }}</p>
                        
                        <p class="text-3xl font-extrabold text-[#333] mt-2">
                            Bs {{ number_format($p->precio, 2) }}
                        </p>

                        @if ($p->stock <= 0)
                            <span class="inline-block px-4 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">AGOTADO</span>
                        @else
                            <span class="inline-block px-4 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                Stock: {{ $p->stock }}
                            </span>
                        @endif

                        <button @if($p->stock > 0)
                                wire:click="agregarProducto({{ $p->id }})"
                                class="mt-3 w-full py-3 bg-[#3483FA] hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg transform hover:scale-105 transition"
                                @else
                                class="mt-3 w-full py-3 bg-gray-400 text-gray-600 rounded-xl cursor-not-allowed"
                                disabled
                                @endif>
                            Agregar
                        </button>
                    </div>
                </div>
                @empty
                    @if($search !== '')
                        <div class="col-span-full text-center py-24">
                            <p class="text-3xl text-gray-400 font-light">No se encontraron productos</p>
                            <p class="text-gray-500 mt-2">Intenta con otro término</p>
                        </div>
                    @endif
                @endforelse
            </div>
        </div>

        <!-- TICKET -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-200">

            <div class="p-6 bg-gradient-to-r from-[#FFE600] to-yellow-400 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-black text-[#111]">Ticket</h2>
                    <p class="text-gray-700 font-medium">Resumen de venta</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-700">Items</p>
                    <p class="text-4xl font-black text-[#111]">{{ count($cart) }}</p>
                </div>
            </div>

            <!-- ITEMS DEL CARRITO -->
            <div class="max-h-96 overflow-y-auto bg-gray-50">
                @forelse($cart as $i => $item)
                <div class="p-5 border-b border-gray-200 hover:bg-white transition flex items-center gap-6">

                    <div class="flex-1">
                        <h4 class="text-xl font-bold text-gray-800">{{ $item['nombre'] }}</h4>
                        <p class="text-sm text-gray-600">Bs {{ number_format($item['precio_unitario'], 2) }} c/u</p>

                        <div class="flex items-center mt-4 gap-4">
                            <button wire:click="decrementarCantidad({{ $i }})"
                                    class="w-14 h-14 rounded-full bg-red-500 hover:bg-red-600 text-white font-black text-2xl shadow-xl hover:shadow-2xl transform hover:scale-110 transition active:scale-95">
                                −
                            </button>
                            <input type="text"
                                inputmode="numeric"
                                pattern="[0-9]*"
                                wire:model.live.debounce.200ms="cart.{{ $i }}.cantidad"
                                wire:change="actualizarSubtotal({{ $i }})"
                                onclick="this.select()"
                                onwheel="this.blur()"
                                oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value === '') this.value = '1';"
                                class="w-32 text-center text-5xl font-black text-gray-800 bg-white border-4 border-gray-300 rounded-2xl py-3 focus:border-[#3483FA] focus:ring-4 focus:ring-[#3483FA]/30 outline-none transition">



                            <button wire:click="incrementarCantidad({{ $i }})"
                                    class="w-14 h-14 rounded-full bg-green-500 hover:bg-green-600 text-white font-black text-3xl shadow-xl hover:shadow-2xl transform hover:scale-110 transition active:scale-95">
                                +
                            </button>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-3xl font-black text-[#333]">
                            Bs {{ number_format($item['subtotal'], 2) }}
                        </p>
                        <button wire:click="removeItem({{ $i }})"
                                class="mt-3 text-red-600 hover:text-red-800 font-bold text-sm hover:underline">
                            Quitar
                        </button>
                    </div>
                </div>
                @empty
                    <div class="text-center py-24 text-gray-400">
                        <p class="text-5xl font-light">Caja vacío</p>
                        <p class="text-xl mt-3">Agrega productos para comenzar</p>
                    </div>
                @endforelse
            </div>

            <!-- TOTALES Y PAGO -->
            <div class="p-6 space-y-6 bg-gradient-to-b from-white to-gray-50">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Cliente</label>
                        <input type="text" wire:model.lazy="cliente_nombre" placeholder="Cliente Genérico"
                               class="w-full px-5 py-4 rounded-xl border-2 border-gray-300 focus:border-[#3483FA] focus:ring-4 focus:ring-[#3483FA]/20 text-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">CI / NIT (opcional)</label>
                        <input type="text" wire:model.lazy="cliente_documento" placeholder="0000000"
                               class="w-full px-5 py-4 rounded-xl border-2 border-gray-300 focus:border-[#3483FA] focus:ring-4 focus:ring-[#3483FA]/20 text-lg">
                    </div>
                </div>

                <div class="flex justify-between text-xl font-bold">
                    <span>Subtotal</span>
                    <span>Bs {{ number_format($subtotal, 2) }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="font-bold">Descuento (Bs)</span>
                    <input type="text" inputmode="decimal" pattern="[0-9]*" wire:model.lazy="descuento" min="0" step="0.01"
                           class="w-40 text-right text-2xl font-bold border-b-4 border-yellow-500 focus:border-[#3483FA] outline-none">
                </div>

                <div class="text-3xl font-black flex justify-between py-4 bg-gray-100 rounded-2xl px-6">
                    <span>Total a pagar</span>
                    <span class="text-[#3483FA]">Bs {{ number_format($total, 2) }}</span>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">Método de pago</label>
                    <select wire:model.live="metodo_pago"
                            class="w-full px-6 py-5 rounded-xl bg-gray-100 font-bold text-xl text-gray-800 focus:ring-4 focus:ring-[#3483FA]/30">
                        <option value="efectivo">Efectivo</option>
                        <option value="qr">QR / Transferencia</option>
                    </select>
                </div>

                @if($metodo_pago === 'efectivo')
                <div class="space-y-6">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-gray-700">Ingresa el monto recibido</p>
                    </div>
                    <input type="text"
                           inputmode="decimal"
                           pattern="[0-9]*"
                           step="0.01"
                           wire:model.lazy="efectivo_recibido"
                           placeholder="0.00"
                           class="w-full text-center text-6xl font-black py-8 rounded-3xl bg-green-50 text-green-700 placeholder-green-400 border-4 border-green-300 focus:border-green-500 focus:ring-4 focus:ring-green-200 outline-none transition">

                    <div class="@if($cambio >= 0) bg-gradient-to-br from-green-600 to-green-700 @else bg-gradient-to-br from-red-600 to-red-700 animate-pulse @endif text-white p-10 rounded-3xl text-center shadow-2xl transition-all duration-500">
                        <p class="text-3xl font-bold tracking-wider">
                            @if($cambio >= 0) CAMBIO A DEVOLVER @else FALTA DINERO @endif
                        </p>
                        <p class="text-8xl font-black mt-3 drop-shadow-2xl">
                            Bs {{ number_format(abs($cambio), 2) }}
                        </p>
                        @if($cambio < 0)
                            <p class="text-2xl font-bold mt-4 opacity-90">
                                Faltan Bs {{ number_format(abs($cambio), 2) }} para completar
                            </p>
                        @endif
                    </div>
                </div>
                @endif

                <button wire:click="confirmarVenta"
                        @if($metodo_pago === 'efectivo' && $cambio < 0)
                            disabled
                            class="w-full py-8 bg-gray-400 text-gray-200 rounded-3xl font-black text-4xl shadow cursor-not-allowed opacity-70"
                        @else
                            class="w-full py-8 bg-[#3483FA] hover:bg-blue-700 text-white rounded-3xl font-black text-4xl shadow-2xl transform hover:scale-105 transition duration-200"
                        @endif>
                    @if($metodo_pago === 'efectivo' && $cambio < 0)
                        FALTA PAGAR Bs {{ number_format(abs($cambio), 2) }}
                    @else
                        FINALIZAR VENTA (F4)
                    @endif
                </button>

            </div>
        </div>
    </div>
</div>

<!-- SCRIPTS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        window.addEventListener('toast', e => Swal.fire({ toast:true, position:'top-end', timer:2500, icon:'success', title:e.detail, showConfirmButton:false }));
        window.addEventListener('confirmar-venta', e => {
            const d = e.detail;
            let lista = '<div style="text-align:left; max-height:350px; overflow-y:auto;">';
            d.productos.forEach(p => lista += `• <strong>${p.nombre}</strong> ×${p.cantidad} → Bs ${parseFloat(p.subtotal).toFixed(2)}<br>`);
            lista += '</div>';
            Swal.fire({
                title: '¿Finalizar venta?',
                html: `<p><strong>Total:</strong> <span style="font-size:32px; color:#3483FA;">Bs ${parseFloat(d.total).toFixed(2)}</span></p><p><strong>Items:</strong> ${d.items}</p><hr>${lista}`,
                icon: 'question', showCancelButton: true, confirmButtonText: 'Cobrar', cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3483FA'
            }).then(r => r.isConfirmed && @this.call('finalizarVenta'));
        });

        window.addEventListener('venta-creada', e => {
            Swal.fire({
                title: '¡Venta completada!', text: 'Venta #' + e.detail, icon: 'success',
                showCancelButton: true, confirmButtonText: 'Imprimir', cancelButtonText: 'Cerrar'
            }).then(r => r.isConfirmed && window.open(`/venta/pdf/${e.detail}`, '_blank'));
        });
        document.addEventListener('keydown', e => {
            if (['INPUT','TEXTAREA','SELECT'].includes(e.target.tagName)) return;
            if (e.key === 'F4') { e.preventDefault(); @this.call('confirmarVenta') }
            if (e.key === 'F9') { e.preventDefault(); @this.call('limpiarCarrito') }
            if (e.key === 'F2') { e.preventDefault(); document.querySelector('input[wire\\:model\\.live]')?.focus() }
        });
    });
</script>
</div>