<div class="space-y-10">

    <!-- SALUDO + ESTADO CAJA -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
                <h1 class="text-5xl font-black text-gray-900">¡Hola, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-xl text-gray-600 mt-2">{{ now()->format('l d \d\e F \d\e Y') }}</p>
        </div>

        <div>
            @if(auth()->user()->esCajero() || auth()->user()->esAdmin())
                @if($cajaAbierta)
                    <div class="inline-flex items-center gap-4 px-8 py-5 bg-green-100 text-green-800 rounded-3xl font-black text-xl shadow-xl">
                        <i data-feather="check-circle" class="w-8 h-8"></i>
                        Caja Abierta · Bs {{ number_format($montoApertura, 2) }}
                    </div>
                @else
                    <a href="{{ route('caja.apertura') }}" 
                       class="inline-flex items-center gap-4 px-8 py-5 bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white rounded-3xl font-black text-xl shadow-2xl transition transform hover:scale-105">
                        <i data-feather="folder-plus" class="w-8 h-8"></i>
                        Abrir Caja del Día
                    </a>
                @endif
            @endif
        </div>
    </div>

    <!-- TARJETAS PRINCIPALES -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
        <div class="bg-gradient-to-br from-yellow-400 to-yellow-600 p-8 rounded-3xl text-white shadow-2xl transform hover:scale-105 transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-lg font-semibold">Ventas del Día</p>
                    <p class="text-5xl font-black mt-3">Bs {{ number_format($ventasHoy, 2) }}</p>
                </div>
                <i data-feather="trending-up" class="w-16 h-16 opacity-80"></i>
            </div>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-2xl border-2 border-gray-200 hover:shadow-3xl transition">
            <p class="text-gray-600 text-lg font-semibold">N° de Ventas</p>
            <p class="text-5xl font-black text-gray-900 mt-3">{{ $cantidadVentasHoy }}</p>
            <i data-feather="shopping-cart" class="w-16 h-16 text-yellow-500 absolute bottom-6 right-6 opacity-20"></i>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-2xl border-2 border-gray-200 hover:shadow-3xl transition">
            <p class="text-gray-600 text-lg font-semibold">Efectivo</p>
            <p class="text-5xl font-black text-gray-900 mt-3">Bs {{ number_format($efectivoHoy, 2) }}</p>
            <i data-feather="dollar-sign" class="w-16 h-16 text-green-500 absolute bottom-6 right-6 opacity-20"></i>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-2xl border-2 border-gray-200 hover:shadow-3xl transition">
            <p class="text-gray-600 text-lg font-semibold">QR / Transferencia</p>
            <p class="text-5xl font-black text-gray-900 mt-3">Bs {{ number_format($qrHoy, 2) }}</p>
            <i data-feather="smartphone" class="w-16 h-16 text-blue-500 absolute bottom-6 right-6 opacity-20"></i>
        </div>
    </div>

    <!-- GRÁFICO + TOP PRODUCTOS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
        <!-- Gráfico -->
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-200 p-8">
            <h2 class="text-3xl font-black text-gray-900 mb-8">Ventas Últimos 7 Días</h2>
            <canvas id="chartVentas" height="320"></canvas>
        </div>

        <!-- Top Productos -->
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-200 p-8">
            <h2 class="text-3xl font-black text-gray-900 mb-8">Top 10 Productos del Día</h2>
            <div class="space-y-5">
                @forelse($topProductos as $index => $item)
                    <div class="flex items-center justify-between p-6 rounded-2xl hover:bg-gray-50 transition border border-gray-100">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-bold text-xl text-gray-900">{{ $item->producto->nombre }}</p>
                                <p class="text-gray-600">{{ $item->producto->marca?->nombre }} • {{ $item->producto->modelo?->nombre }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-black text-2xl text-gray-900">{{ $item->total_vendido }} und.</p>
                            <p class="text-lg font-semibold text-green-600">Bs {{ number_format($item->total_ingresos, 2) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 text-gray-500">
                        <i data-feather="package" class="w-20 h-20 mx-auto mb-4 text-gray-300"></i>
                        <p class="text-xl">Aún no hay ventas hoy</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        feather.replace();

        new Chart(document.getElementById('chartVentas'), {
            type: 'bar',
            data: {
                labels: @json($fechas),
                datasets: [{
                    label: 'Ventas (Bs)',
                    data: @json($ventasSemanales),
                    backgroundColor: '#fbbf24',
                    borderColor: '#f59e0b',
                    borderWidth: 3,
                    borderRadius: 12,
                    barThickness: 35,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor: '#1f2937' }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        ticks: { 
                            callback: value => 'Bs ' + Number(value).toLocaleString()
                        }
                    }
                }
            }
        });
    });
</script>
@endpush