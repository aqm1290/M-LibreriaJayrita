<div class="max-w-2xl mx-auto bg-white rounded-3xl shadow-2xl p-10">
    <div class="text-center mb-10">
        <h2 class="text-5xl font-black text-gray-800 mb-4">APERTURA DE CAJA</h2>
        <p class="text-2xl text-gray-600">{{ now()->format('d \d\e F \d\e Y') }}</p>
    </div>

    @if($cajaAbierta)
        <div class="bg-green-100 border-4 border-green-500 rounded-2xl p-10 text-center">
            <div class="text-8xl mb-6">OPEN</div>
            <h3 class="text-4xl font-bold text-green-800 mb-4">¡LA CAJA YA ESTÁ ABIERTA!</h3>
            <p class="text-2xl text-green-700">Monto de apertura: <strong>Bs {{ number_format($montoApertura, 2) }}</strong></p>
            <p class="text-xl text-gray-600 mt-4">Cajero: {{ auth()->user()?->name ?? 'Admin' }}</p>
        </div>
    @else
        <form wire:submit.prevent="abrirCaja" class="space-y-8">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-8 rounded-2xl border-4 border-blue-200">
                <label class="block text-2xl font-bold text-gray-800 mb-6 text-center">
                    ¿Cuánto dinero hay en caja al iniciar el día?
                </label>
                <input 
                    type="number" 
                    step="0.01" 
                    wire:model="monto" 
                    class="w-full text-6xl font-black text-center border-4 border-blue-400 rounded-2xl py-6 focus:outline-none focus:border-blue-600"
                    placeholder="0.00"
                    required
                >
                @error('monto') <span class="text-red-600 text-xl">{{ $mensaje }}</span> @enderror
            </div>

            <div class="text-center">
                <button type="submit" 
                        class="px-16 py-8 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-black text-4xl rounded-3xl shadow-2xl transform hover:scale-105 transition duration-300">
                    ABRIR CAJA DEL DÍA
                </button>
            </div>
        </form>
    @endif
</div>