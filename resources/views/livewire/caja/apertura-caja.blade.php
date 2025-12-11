<div
    class="min-h-screen bg-gradient-to-br from-amber-50 via-white to-amber-100 flex items-center justify-center px-4 py-8">
    <div class="max-w-3xl w-full">
        <div class="bg-white rounded-3xl shadow-2xl border border-amber-100 overflow-hidden">

            {{-- CABECERA --}}
            <div
                class="px-8 md:px-10 pt-8 pb-6 text-center bg-gradient-to-r from-amber-300 via-amber-200 to-amber-300 border-b border-amber-200">
                <p class="text-xs font-semibold tracking-[0.3em] text-amber-800 uppercase mb-2">
                    Punto de venta
                </p>
                <h1 class="text-3xl md:text-4xl font-black text-amber-900">
                    Apertura de caja
                </h1>
                <p class="text-sm md:text-base text-amber-900/80 mt-2 font-medium">
                    {{ now('America/La_Paz')->translatedFormat('l j \d\e F \d\e Y') }}
                </p>
            </div>

            {{-- CONTENIDO --}}
            <div class="px-8 md:px-10 py-8 md:py-10 bg-gradient-to-b from-white to-amber-50/60">
                <form wire:submit="abrirCaja" class="space-y-8">

                    {{-- MONTO --}}
                    <div class="bg-white border border-amber-200 rounded-2xl p-6 md:p-8 shadow-sm">
                        <label class="block text-center text-base md:text-lg font-semibold text-amber-900 mb-4">
                            ¿Cuánto dinero hay físicamente en caja?
                        </label>

                        <input type="number" step="0.01" wire:model.live="monto"
                            class="w-full text-4xl md:text-5xl font-black text-center
                                   bg-amber-50 border-2 border-amber-300 rounded-2xl
                                   py-4 md:py-5 text-amber-900 tracking-widest
                                   focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-300/60 transition"
                            placeholder="0.00" autofocus>

                        <p class="text-center mt-4 text-sm md:text-base text-amber-800/80">
                            Monto de apertura
                        </p>
                        <p class="text-center mt-1 text-3xl md:text-4xl font-black text-amber-900">
                            Bs
                            <span wire:loading.remove>{{ number_format($monto, 2) }}</span>
                            <span wire:loading>0.00</span>
                        </p>
                    </div>

                    {{-- BOTONES --}}
                    <div class="text-center pt-2 flex flex-col md:flex-row items-center justify-center gap-4">
                        <button type="submit"
                            class="inline-flex items-center justify-center px-10 md:px-16 py-4 md:py-5
               rounded-2xl bg-amber-500 hover:bg-amber-600
               text-white text-lg md:text-2xl font-black tracking-wide
               shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 active:scale-95
               transition">
                            Abrir caja y empezar a vender
                        </button>

                        <a href="{{ route('dashboard') }}" {{-- o route('caja.index') / url()->previous() --}}
                            class="inline-flex items-center justify-center px-8 md:px-10 py-3 md:py-4
               rounded-2xl border-2 border-amber-400
               text-amber-700 text-base md:text-lg font-bold
               bg-white hover:bg-amber-50 shadow-md hover:shadow-lg
               transform hover:-translate-y-0.5 active:scale-95 transition">
                            Cancelar / Volver
                        </a>
                    </div>

                    <p class="mt-3 text-[0.7rem] text-amber-800/70 text-center">
                        Este monto se usará como base para el cierre del día.
                    </p>

                </form>

                {{-- RESUMEN ABAJO --}}
                <div
                    class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4 bg-amber-50 border border-amber-200 rounded-2xl px-4 md:px-5 py-4 text-xs md:text-sm">
                    <div>
                        <p class="text-amber-700 font-semibold uppercase tracking-wide">
                            Usuario
                        </p>
                        <p class="mt-1 text-amber-900 font-bold">
                            {{ auth()->user()->name ?? 'Usuario' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-amber-700 font-semibold uppercase tracking-wide">
                            Fecha
                        </p>
                        <p class="mt-1 text-amber-900 font-bold">
                            {{ now('America/La_Paz')->translatedFormat('d/m/Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-amber-700 font-semibold uppercase tracking-wide">
                            Hora actual
                        </p>
                        <p class="mt-1 text-amber-900 font-bold">
                            {{ now('America/La_Paz')->format('H:i:s') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
