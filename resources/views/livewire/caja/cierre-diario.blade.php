<div class="min-h-screen bg-gradient-to-br from-gray-50 to-orange-50 p-6">
    <div class="max-w-6xl mx-auto">

  <h1 class="text-6xl font-black text-center text-gray-800 mb-8">CIERRE DE CAJA</h1>
  <p class="text-2xl text-center text-gray-600 mb-12">{{ now()->format('l d \d\e F \d\e Y - H:i') }}</p>

  @if($yaCerrado)
   <div class="bg-green-100 border-4 border-green-600 rounded-3xl p-16 text-center shadow-2xl">
    <h2 class="text-6xl font-black text-green-800 mb-8 leading-tight">
     ¡CAJA CERRADA CORRECTAMENTE!
    </h2>
    <p class="text-4xl mb-10 text-gray-800">
     Total del día: <strong class="text-green-700">Bs {{ number_format($totalDia, 2) }}</strong>
    </p>

    <div class="flex flex-col sm:flex-row gap-8 justify-center items-center">
     @if($reportePdf)
      <a href="{{ asset('storage/' . $reportePdf) }}" target="_blank"
         class="px-16 py-8 bg-green-600 hover:bg-green-700 text-white font-black text-3xl rounded-3xl shadow-2xl transform hover:scale-105 transition duration-300">
       DESCARGAR REPORTE PDF
      </a>
     @endif

     <a href="{{ route('dashboard') }}"
        class="px-20 py-8 bg-gradient-to-r from-indigo-600 to-purple-700 hover:from-indigo-700 hover:to-purple-800 text-white font-black text-4xl rounded-3xl shadow-2xl transform hover:scale-110 transition duration-300 flex items-center gap-4">
      <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
      </svg>
      SALIR Y VOLVER AL INICIO
     </a>
    </div>

    <div class="mt-12 text-gray-600">
     <p class="text-xl">¡Buen trabajo hoy!</p>
     <p class="text-lg mt-2">Puedes cerrar esta pestaña o ir al dashboard</p>
    </div>
   </div>

  @else
   <div class="bg-gradient-to-r from-orange-500 to-red-600 p-10 rounded-3xl text-white text-center mb-10">
    <h3 class="text-3xl font-black">DINERO ESPERADO EN CAJA</h3>
    <p class="text-8xl font-black mt-4">Bs {{ number_format($efectivo + $apertura, 2) }}</p>
    <p class="text-xl opacity-90 mt-2">Apertura + Efectivo del día</p>
   </div>

   <form wire:submit.prevent="generarCierre" class="bg-white rounded-3xl shadow-2xl p-12 space-y-10">
    <div>
     <label class="block text-3xl font-black text-center mb-8">¿Cuánto dinero físico hay en caja?</label>
     <input type="number" step="0.01" wire:model.live="montoFisico" required autofocus
            class="w-full text-7xl font-black text-center border-4 border-orange-400 rounded-3xl py-8 focus:border-orange-600 focus:ring-4 focus:ring-orange-200">
    </div>

    <div>
     <label class="block text-2xl font-bold text-center mb-4">Observaciones (opcional)</label>
     <textarea wire:model="observaciones" rows="4" class="w-full border-4 border-gray-300 rounded-2xl p-6 text-xl"></textarea>
    </div>

    <div class="text-center">
     <button type="submit"
             class="px-20 py-10 bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white font-black text-5xl rounded-3xl shadow-2xl transform hover:scale-105 transition">
      CERRAR CAJA Y GENERAR REPORTE
     </button>
    </div>
   </form>

   @if($productosTop10->count())
    <div class="mt-12 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-3xl p-10">
     <h3 class="text-4xl font-black text-center mb-8">TOP 10 PRODUCTOS MÁS VENDIDOS</h3>
     <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @foreach($productosTop10 as $i => $p)
       <div class="bg-white rounded-2xl shadow-xl p-8 text-center border-4 border-yellow-400">
        <p class="text-6xl font-black text-yellow-600">#{{ $i+1 }}</p>
        <p class="text-2xl font-black mt-4">{{ $p->nombre }}</p>
        <p class="text-4xl font-black text-green-600 mt-4">{{ $p->cantidad }} und</p>
        <p class="text-2xl font-bold">Bs {{ number_format($p->monto, 2) }}</p>
       </div>
      @endforeach
     </div>
    </div>
   @endif
  @endif
 </div>
</div>