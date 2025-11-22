
<div class="bg-white rounded-3xl shadow-2xl p-8 max-w-6xl mx-auto">
    <div class="text-center mb-8">
        <h2 class="text-4xl font-black text-gray-800 mb-2">Cierre de Caja</h2>
        <p class="text-xl text-gray-600">{{ now()->format('d F Y') }} - {{ now()->format('H:i') }}</p>
    </div>
    <div class="bg-gradient-to-br from-orange-500 to-red-600 p-6 rounded-2xl text-white text-center">
        <h3 class="text-lg font-semibold opacity-90 mb-3">DINERO ESPERADO EN CAJA</h3>
        <p class="text-5xl font-black mb-2">Bs {{ number_format($efectivo + $apertura, 2) }}</p>
        <p class="text-sm opacity-80">
            Apertura Bs {{ number_format($apertura, 2) }} + Efectivo del día
        </p>
    </div>

    @if($yaCerrado)
        <div class="bg-green-50 border-4 border-green-500 rounded-2xl p-8 text-center">
            <div class="text-6xl mb-4">✅</div>
            <h3 class="text-3xl font-bold text-green-800 mb-4">¡Caja ya cerrada hoy!</h3>
            <p class="text-xl text-green-700 mb-6">Total del día: <strong>Bs {{ number_format($totalDia, 2) }}</strong></p>
            
            @if($cierre->reporte_pdf)
                <a href="{{ asset('storage/' . $cierre->reporte_pdf) }}" 
                   target="_blank" 
                   class="inline-block px-8 py-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg transform hover:scale-105 transition">
                    📄 Descargar Reporte del Día
                </a>
            @endif
        </div>
    @else
        <!-- RESUMEN DE VENTAS -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 p-6 rounded-2xl text-white text-center">
                <h3 class="text-lg font-semibold opacity-90 mb-3">💵 Ventas en Efectivo</h3>
                <p class="text-5xl font-black mb-2">Bs {{ number_format($efectivo, 2) }}</p>
                <p class="text-sm opacity-80">{{ $ventasEfectivo }} ventas</p>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 p-6 rounded-2xl text-white text-center">
                <h3 class="text-lg font-semibold opacity-90 mb-3">📱 Ventas QR</h3>
                <p class="text-5xl font-black mb-2">Bs {{ number_format($qr, 2) }}</p>
                <p class="text-sm opacity-80">{{ $ventasQr }} ventas</p>
            </div>

            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-6 rounded-2xl text-white text-center">
                <h3 class="text-lg font-semibold opacity-90 mb-3">📊 Total del Día</h3>
                <p class="text-5xl font-black mb-2">Bs {{ number_format($totalDia, 2) }}</p>
                <p class="text-sm opacity-80">{{ $totalVentas }} transacciones</p>
            </div>
        </div>

        <!-- TOP PRODUCTOS -->
        @if(count($productosTop10) > 0)
        <div class="bg-gradient-to-r from-yellow-50 to-orange-50 p-6 rounded-2xl border-2 border-yellow-200 mb-8">
            <h3 class="text-2xl font-bold text-yellow-800 mb-4">🏆 Productos Más Vendidos Hoy</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($productosTop10 as $index => $producto)
                <div class="bg-white p-4 rounded-xl shadow-md border-r-4 border-yellow-400">
                    <div class="font-bold text-lg text-gray-800 mb-2">#{{ $index + 1 }} {{ $producto->nombre }}</div>
                    <div class="text-2xl font-black text-yellow-600 mb-1">{{ $producto->cantidad_vendida }} BS</div>
                    <div class="text-sm text-gray-600">Bs {{ number_format($producto->monto_vendido, 2) }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- TOTAL FINAL -->
        <div class="bg-gradient-to-r from-red-500 to-pink-600 p-8 rounded-3xl text-white text-center mb-10">
            <h3 class="text-3xl font-bold mb-4">TOTAL DEL DÍA</h3>
            <p class="text-7xl font-black mb-2">Bs {{ number_format($totalDia, 2) }}</p>
            <p class="text-xl opacity-90">{{ $totalVentas }} ventas realizadas • ¡Excelente trabajo!</p>
        </div>

        <!-- BOTÓN DE CIERRE -->
        <div class="text-center">
            <button wire:click="generarCierre" 
                    wire:confirm="¿Confirmas el cierre de caja del {{ now()->format('d/m/Y') }}? Se generará el reporte PDF."
                    class="px-16 py-6 bg-red-600 hover:bg-red-700 text-white font-black text-2xl rounded-3xl shadow-2xl transform hover:scale-105 transition duration-200">
                🔒 CERRAR CAJA DEL DÍA
            </button>
        </div>
    @endif
</div>

<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('open-pdf', (event) => {
        window.open(event.url, '_blank');
    });
});
</script>