<div class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-amber-100 py-12 px-6">
    <div class="max-w-7xl mx-auto">

        <div class="text-center mb-10">
            <p class="text-sm font-bold tracking-widest text-amber-700 uppercase">Punto de Venta</p>
            <h1 class="text-5xl font-black text-amber-900 mt-2">Cierre de Caja</h1>
            <p class="text-lg text-amber-800 mt-2">{{ now()->format('l d \d\e F \d\e Y - H:i') }}</p>
        </div>

        @if($yaCerrado)
            <!-- PANTALLA DE ÉXITO -->
            <div class="bg-gradient-to-r from-emerald-50 to-green-50 rounded-3xl p-12 text-center shadow-2xl border border-emerald-200">
                <h2 class="text-5xl font-black text-emerald-700 mb-6">¡Caja Cerrada con Éxito!</h2>
                <p class="text-3xl mb-8">
                    Total del día:
                    <span class="text-emerald-600 font-black">
                        Bs {{ number_format($totalDia, 2) }}
                    </span>
                </p>
                <div class="flex flex-col md:flex-row gap-6 justify-center">
                    <a href="{{ route('cierre.pdf', $turno->id) }}" target="_blank"
                       class="px-12 py-5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xl rounded-2xl shadow-lg transform hover:scale-105 transition">
                        Ver Reporte PDF
                    </a>
                    <a href="{{ route('cierre.descargar', $turno->id) }}"
                       class="px-12 py-5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xl rounded-2xl shadow-lg transform hover:scale-105 transition">
                        Descargar Reporte
                    </a>
                    <a href="{{ route('dashboard') }}"
                       class="px-12 py-5 bg-slate-800 hover:bg-black text-white font-bold text-xl rounded-2xl shadow-lg transform hover:scale-105 transition">
                        Volver al Dashboard
                    </a>
                </div>
            </div>

        @else
            <!-- FORMULARIO DE CIERRE -->
            <div class="bg-gradient-to-r from-amber-400 to-orange-500 rounded-3xl p-10 text-white shadow-2xl text-center mb-10">
                <p class="text-xl font-bold uppercase tracking-wider">Dinero Esperado en Caja</p>
                <p class="text-7xl font-black mt-4">Bs {{ number_format($efectivo + $apertura, 2) }}</p>
            </div>

            <form wire:submit="generarCierre" class="bg-white rounded-3xl shadow-2xl p-10 border border-amber-100 space-y-10">
                <div>
                    <label class="block text-3xl font-bold text-center mb-6 text-slate-900">
                        Monto Físico en Caja
                    </label>
                    <input type="number" step="0.01" wire:model.live="montoFisico" required autofocus
                           class="w-full text-7xl font-black text-center bg-amber-50 border-4 border-amber-400 rounded-3xl py-8 focus:border-amber-600 outline-none"
                           placeholder="0.00">
                </div>

                <div>
                    <label class="block text-xl font-semibold mb-3">Observaciones (opcional)</label>
                    <textarea wire:model="observaciones" rows="4"
                              class="w-full rounded-2xl border border-slate-300 p-5 text-lg focus:border-slate-500 outline-none"></textarea>
                </div>

                <div class="text-center">
                    <button type="submit"
                            class="px-20 py-7 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white text-3xl font-black rounded-3xl shadow-2xl transform hover:scale-105 transition duration-300">
                        Cerrar Caja
                    </button>
                </div>
            </form>

            @if($productosVendidos->count())
                <div class="mt-12 bg-gradient-to-r from-amber-50 to-orange-50 rounded-3xl p-10 shadow-xl">
                    <h3 class="text-3xl font-black text-center text-amber-900 mb-8">
                        Productos Vendidos Hoy ({{ $productosVendidos->count() }})
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($productosVendidos as $i => $p)
                            <div class="bg-white rounded-2xl p-8 text-center shadow-lg border border-amber-100 transform hover:scale-105 transition">
                                <div class="text-5xl font-black text-amber-600">#{{ $i + 1 }}</div>
                                <h4 class="text-xl font-bold text-slate-800 mt-3">{{ $p->nombre }}</h4>
                                <p class="text-4xl font-black text-emerald-600 mt-4">{{ $p->cantidad }} und</p>
                                <p class="text-2xl text-slate-700 mt-2">Bs {{ number_format($p->monto, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>

    <!-- SWEETALERT FUNCIONANDO PERFECTO -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('swal', (event) => {
                Swal.fire({
                    title: event.title,
                    html: event.html || event.text,
                    icon: event.icon,
                    confirmButtonText: '¡Perfecto! CAJA CERRADA',
                    allowOutsideClick: false,
                    customClass: {
                        confirmButton: 'px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl'
                    },
                    buttonsStyling: false
                });
            });
        });
    </script>
</div>