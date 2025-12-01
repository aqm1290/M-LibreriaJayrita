<div class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-amber-100 py-6 px-4">
    <div class="max-w-3xl mx-auto space-y-6">

        <!-- Header compacto -->
        <div class="text-center">
            <p class="text-[11px] font-black tracking-[0.3em] text-amber-600 uppercase">
                Punto de venta • Librería
            </p>
            <h1 class="text-2xl sm:text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-900 to-orange-700 mt-1 leading-tight">
                Cierre de caja
            </h1>
            <p class="text-xs sm:text-sm font-semibold text-amber-700 mt-1">
                {{ now('America/La_Paz')->locale('es')->translatedFormat('l d \\d\\e F \\d\\e Y') }}
                •
                <span class="font-black">
                    {{ now('America/La_Paz')->format('H:i') }} hs
                </span>
            </p>
        </div>

        @if($yaCerrado)
    <div class="bg-gradient-to-br from-emerald-50 to-green-50 rounded-2xl p-8 text-center shadow-xl border-2 border-emerald-200">
        <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-emerald-100 border-4 border-emerald-300 flex items-center justify-center">
            <i data-lucide="check-circle" class="w-12 h-12 text-emerald-600"></i>
        </div>

        <h2 class="text-3xl font-black text-emerald-800 mb-2">¡Caja Cerrada con Éxito!</h2>
        <p class="text-lg text-emerald-700 mb-6">Todo cuadra perfecto</p>

        <p class="text-4xl font-black text-emerald-600 mb-8">
            Bs {{ number_format($totalDia, 2) }}
        </p>

        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('cierre.pdf', $turno->id) }}" target="_blank"
               class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition flex items-center gap-2">
                <i data-lucide="file-text" class="w-5 h-5"></i> Ver PDF
            </a>
            <a href="{{ route('cierre.descargar', $turno->id) }}"
               class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition flex items-center gap-2">
                <i data-lucide="download" class="w-5 h-5"></i> Descargar
            </a>
            <a href="{{ route('dashboard') }}"
               class="px-6 py-3 bg-gray-800 hover:bg-black text-white font-bold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition flex items-center gap-2">
                <i data-lucide="home" class="w-5 h-5"></i> Panel de Control
            </a>
        </div>
    </div>

        @else
            <!-- Dinero esperado compacto -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-xl p-5 text-white shadow-lg text-center">
                <p class="text-[11px] sm:text-xs font-black uppercase tracking-[0.25em] opacity-90">
                    Dinero esperado en caja
                </p>
                <p class="text-2xl sm:text-3xl font-black mt-2 drop-shadow">
                    Bs {{ number_format($efectivo + $apertura, 2) }}
                </p>
                <p class="mt-1 text-[10px] sm:text-[11px] text-amber-100/90">
                    Efectivo del día + monto de apertura
                </p>
            </div>

            <!-- Formulario de cierre compacto -->
            <form
                wire:submit="generarCierre"
                class="bg-white rounded-xl shadow-lg p-5 sm:p-6 md:p-8 border border-amber-200 space-y-5"
            >
                <div class="space-y-2">
                    <label class="block text-lg sm:text-xl font-black text-center text-amber-900">
                        ¿Cuánto dinero físico hay en caja?
                    </label>
                    <input
                        type="number"
                        step="0.01"
                        wire:model.live="montoFisico"
                        required
                        autofocus
                        class="w-full text-2xl sm:text-3xl font-black text-center bg-gradient-to-r from-amber-50 to-orange-50 border-3 sm:border-4 border-amber-400 rounded-xl py-3 sm:py-4 focus:border-orange-600 focus:ring-2 focus:ring-amber-100 outline-none shadow-inner"
                        placeholder="0.00"
                    >
                </div>

                <div>
                    <label class="block text-xs sm:text-sm font-semibold mb-1 text-slate-800">
                        Observaciones (opcional)
                    </label>
                    <textarea
                        wire:model="observaciones"
                        rows="3"
                        class="w-full rounded-xl border border-slate-300 p-3 text-xs sm:text-sm focus:border-amber-500 focus:ring-2 focus:ring-amber-100 outline-none transition"
                        placeholder="Ej: Faltaron Bs 50 por error de cambio..."
                    ></textarea>
                </div>

                <div class="text-center pt-1">
                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 px-8 sm:px-10 py-3
                               bg-gradient-to-r from-red-600 to-rose-700 hover:from-red-700 hover:to-rose-800
                               text-white text-sm sm:text-base font-black rounded-xl shadow-xl
                               transform hover:scale-105 hover:-translate-y-0.5 transition duration-300 uppercase tracking-wide"
                    >
                        <i data-lucide="alarm-check" class="w-4 h-4"></i>
                        Cerrar caja definitivamente
                    </button>
                </div>
            </form>

            <!-- Productos vendidos compacto -->
            @if($productosVendidos->count())
                <div class="mt-6 bg-white/95 backdrop-blur rounded-xl p-5 sm:p-6 shadow-lg border border-amber-200">
                    <h3 class="text-lg sm:text-xl font-black text-center text-amber-900 mb-4">
                        Productos vendidos hoy ({{ $productosVendidos->count() }} ítems)
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($productosVendidos as $i => $p)
                            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-4 text-center shadow-md border border-amber-200 transform hover:scale-105 hover:-translate-y-0.5 transition">
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-amber-500 text-white font-black text-xs shadow">
                                    {{ $i + 1 }}
                                </div>
                                <h4 class="text-sm sm:text-base font-bold text-slate-800 mt-2 line-clamp-2">
                                    {{ $p->nombre }}
                                </h4>
                                <p class="text-xl sm:text-2xl font-black text-emerald-600 mt-2">
                                    {{ $p->cantidad }} und
                                </p>
                                <p class="text-sm sm:text-base font-semibold text-amber-900 mt-1">
                                    Bs {{ number_format($p->monto, 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>

    <!-- SweetAlert -->
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
                        confirmButton: 'px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs sm:text-sm rounded-lg'
                    },
                    buttonsStyling: false
                });
            });
        });
    </script>
</div>
