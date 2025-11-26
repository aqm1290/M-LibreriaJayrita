<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-orange-50 flex items-center justify-center p-6">
    <div class="max-w-2xl w-full bg-white rounded-3xl shadow-2xl p-12 border-4 border-indigo-100">
        <div class="text-center mb-12">
            <h1 class="text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-orange-600">
                APERTURA DE CAJA
            </h1>
            <p class="text-2xl text-gray-600 mt-4 font-bold">
                {{ now()->format('l d \d\e F \d\e Y') }}
            </p>
        </div>

        <form wire:submit="abrirCaja" class="space-y-10">
            <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-10 rounded-3xl border-4 border-indigo-200">
                <label class="block text-3xl font-black text-center text-gray-800 mb-8">
                    ¿Cuánto dinero hay físicamente en caja?
                </label>
                <input 
                    type="number" 
                    step="0.01" 
                    wire:model="monto" 
                    class="w-full text-7xl font-black text-center bg-white border-4 border-indigo-400 rounded-3xl py-6 focus:outline-none focus:border-indigo-600 focus:ring-4 focus:ring-indigo-200 transition"
                    placeholder="0.00"
                    autofocus
                >
                <p class="text-center mt-6 text-2xl font-bold text-indigo-700">
                    Bs <span wire:loading.remove>{{ number_format($monto, 2) }}</span>
                    <span wire:loading>0.00</span>
                </p>
            </div>

            <div class="text-center">
                <button type="submit" 
                        class="px-20 py-8 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-black text-4xl rounded-3xl shadow-2xl transform hover:scale-105 transition duration-300">
                    ABRIR CAJA Y EMPEZAR A VENDER
                </button>
            </div>
        </form>
    </div>
</div>