<div class="min-h-screen bg-gradient-to-br from-amber-10 via-white to-amber-100 py-8 px-4 md:px-6">
    <div class="max-w-6xl mx-auto space-y-8">

        {{-- Título y fecha --}}
        <div class="text-center space-y-2">
            <p class="text-xs font-semibold tracking-[0.3em] text-amber-700 uppercase">
                Punto de venta
            </p>
            <h1 class="text-3xl md:text-4xl font-black text-amber-900">
                Cierre de caja
            </h1>
            <p class="text-sm md:text-base text-amber-800/80">
                {{ now()->format('l d \d\e F \d\e Y - H:i') }}
            </p>
        </div>

        @if($yaCerrado)
            {{-- CAJA YA CERRADA --}}
            <div class="bg-white rounded-3xl shadow-2xl border border-emerald-200 px-6 md:px-10 py-8 md:py-10">
                <div class="text-center space-y-4 md:space-y-6">
                    <h2 class="text-2xl md:text-3xl font-black text-emerald-700">
                        ¡Caja cerrada correctamente!
                    </h2>
                    <p class="text-lg md:text-2xl text-slate-800">
                        Total del día:
                        <span class="font-black text-emerald-700">
                            Bs {{ number_format($totalDia, 2) }}
                        </span>
                    </p>

                    <div class="flex flex-col md:flex-row gap-4 md:gap-6 justify-center items-center pt-2">
                        @if($reportePdf)
                            <a
                                href="{{ asset('storage/' . $reportePdf) }}"
                                target="_blank"
                                class="inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 rounded-2xl
                                       bg-emerald-600 hover:bg-emerald-700 text-white text-sm md:text-base font-semibold
                                       shadow-md hover:shadow-xl transform hover:-translate-y-0.5 transition"
                            >
                                Descargar reporte PDF
                            </a>
                        @endif

                        <a
                            href="{{ route('dashboard') }}"
                            class="inline-flex items-center justify-center px-6 md:px-8 py-3 md:py-4 rounded-2xl
                                   bg-slate-900 hover:bg-black text-white text-sm md:text-base font-semibold
                                   shadow-md hover:shadow-xl transform hover:-translate-y-0.5 transition gap-2"
                        >
                            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Salir y volver al inicio
                        </a>
                    </div>

                    <div class="pt-4 text-xs md:text-sm text-slate-500">
                        <p>Buen trabajo hoy.</p>
                        <p class="mt-1">Puedes cerrar esta pestaña o regresar al dashboard.</p>
                    </div>
                </div>
            </div>
        @else
            {{-- BLOQUE DINERO ESPERADO --}}
            <div class="bg-gradient-to-r from-amber-400 to-orange-500 rounded-3xl px-6 md:px-10 py-6 md:py-8 text-center text-white shadow-xl">
                <p class="text-xs md:text-sm font-semibold uppercase tracking-wide">
                    Dinero esperado en caja
                </p>
                <p class="mt-3 text-3xl md:text-5xl font-black">
                    Bs {{ number_format($efectivo + $apertura, 2) }}
                </p>
                <p class="mt-2 text-xs md:text-sm opacity-90">
                    Apertura + efectivo del día
                </p>
            </div>

            {{-- FORMULARIO CIERRE --}}
            <div class="bg-white rounded-3xl shadow-2xl border border-amber-100 px-6 md:px-10 py-8 md:py-10 space-y-8">
                <form wire:submit.prevent="generarCierre" class="space-y-8">

                    <div>
                        <label class="block text-base md:text-lg font-semibold text-center text-slate-900 mb-4">
                            ¿Cuánto dinero físico hay en caja?
                        </label>
                        <input
                            type="number"
                            step="0.01"
                            wire:model.live="montoFisico"
                            required
                            autofocus
                            class="w-full text-3xl md:text-5xl font-black text-center border-2 md:border-4 border-amber-300
                                   rounded-2xl md:rounded-3xl py-4 md:py-6 bg-amber-50 text-amber-900
                                   focus:border-amber-500 focus:ring-2 md:focus:ring-4 focus:ring-amber-200 outline-none transition"
                            placeholder="0.00"
                        >
                    </div>

                    <div>
                        <label class="block text-sm md:text-base font-semibold text-slate-800 mb-2">
                            Observaciones (opcional)
                        </label>
                        <textarea
                            wire:model="observaciones"
                            rows="4"
                            class="w-full border border-slate-200 rounded-2xl p-3 md:p-4 text-sm md:text-base
                                   bg-slate-50 focus:outline-none focus:border-slate-900 focus:ring-2 focus:ring-slate-200 transition"
                            placeholder="Ej: Diferencia por error de cambio, billetes falsos, etc."
                        ></textarea>
                    </div>

                    <div class="text-center pt-2">
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center px-8 md:px-14 py-3.5 md:py-4 rounded-2xl
                                   bg-red-600 hover:bg-red-700 text-white text-lg md:text-2xl font-black tracking-wide
                                   shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 active:scale-95 transition"
                        >
                            Cerrar caja y generar reporte
                        </button>
                        <p class="mt-3 text-[0.7rem] md:text-xs text-slate-500">
                            Se guardará un resumen del día y se bloquearán nuevas ventas hasta la próxima apertura.
                        </p>
                    </div>
                </form>
            </div>

            {{-- TOP 10 PRODUCTOS --}}
            @if($productosTop10->count())
                <div class="mt-8 bg-gradient-to-r from-amber-50 to-orange-50 rounded-3xl px-6 md:px-10 py-7 md:py-8">
                    <h3 class="text-lg md:text-2xl font-black text-center text-amber-900 mb-5">
                        Top 10 productos más vendidos
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                        @foreach($productosTop10 as $i => $p)
                            <div class="bg-white rounded-2xl shadow-md border border-amber-100 px-5 py-5 text-center">
                                <p class="text-3xl md:text-4xl font-black text-amber-500">
                                    #{{ $i + 1 }}
                                </p>
                                <p class="mt-2 text-sm md:text-base font-semibold text-slate-900 line-clamp-2">
                                    {{ $p->nombre }}
                                </p>
                                <p class="mt-3 text-xl md:text-2xl font-black text-emerald-600">
                                    {{ $p->cantidad }} und
                                </p>
                                <p class="mt-1 text-sm md:text-base font-semibold text-slate-800">
                                    Bs {{ number_format($p->monto, 2) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
