<div class="space-y-12">

    <!-- SALUDO + FECHA EN ESPAÑOL + HORA -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-8">
        <div>
            <h1 class="text-5xl font-black text-gray-900 leading-tight">
                ¡Hola, {{ auth()->user()->name }}!
            </h1>
            <p class="text-2xl text-gray-600 mt-4 font-bold">
                Hoy es <span class="text-yellow-600">
                     {{ \Carbon\Carbon::now()->locale('es')->translatedFormat('l d \\d\\e F \\d\\e\\l Y') }}
                </span>
            </p>
            <p class="text-lg text-gray-500 mt-2">
                Hora actual : 
                <span class="font-bold text-gray-700">
                    {{ \Carbon\Carbon::now('America/La_Paz')->isoFormat('HH:mm:ss') }}
                </span>
            </p>
        </div>

        @if(auth()->user()->esCajero() || auth()->user()->esAdmin())
            @if($cajaAbierta)
                <div class="inline-flex items-center gap-5 px-10 py-6 bg-green-100 text-green-800 rounded-3xl font-black text-2xl shadow-2xl border-4 border-green-200">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Caja Abierta · Bs {{ number_format($montoApertura, 2, ',', '.') }}
                </div>
            @else
                <a href="{{ route('caja.apertura') }}" 
                class="inline-flex items-center justify-center gap-3 px-8 py-4
                        bg-gradient-to-r from-yellow-500 to-orange-600 
                        hover:from-yellow-600 hover:to-orange-700
                        text-white font-bold rounded-2xl shadow-lg 
                        hover:shadow-xl transform hover:-translate-y-0.5 hover:scale-[1.02] 
                        transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="text-base tracking-wide">ABRIR CAJA DEL DÍA</span>
                </a>

            @endif
        @endif
    </div>

    <!-- RESUMEN DE VENTAS -->
    <div>
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-4xl font-black text-gray-900 flex items-center gap-4">
                <span class="bg-yellow-400 text-white w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-lg">1</span>
                Resumen de Ventas del Día
            </h2>
{{--             <a href="{{ route('ventas.index') }}" class="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 text-white rounded-2xl font-black shadow-xl transition transform hover:scale-105">
 --}}             {{--    Ver Todas las Ventas
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                </svg> --}}
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-gradient-to-br from-yellow-400 to-orange-500 p-8 rounded-3xl text-white shadow-2xl transform hover:scale-105 transition">
                <p class="text-yellow-100 text-lg font-bold">Total Ventas Hoy</p>
                <p class="text-4xl font-black mt-4">Bs {{ number_format($ventasHoy, 2, ',', '.') }}</p>
            </div>
            <div class="bg-white p-8 rounded-3xl shadow-2xl border-4 border-gray-100 hover:shadow-3xl transition cursor-pointer">
                <p class="text-gray-600 text-lg font-bold">Cantidad de Ventas</p>
                <p class="text-4xl font-black text-gray-900 mt-4">{{ $cantidadVentasHoy }}</p>
                <p class="text-gray-500 mt-2">transacciones hoy</p>
            </div>
            <div class="bg-gradient-to-br from-green-400 to-emerald-600 p-8 rounded-3xl text-white shadow-2xl transform hover:scale-105 transition">
                <p class="text-green-100 text-lg font-bold">Efectivo</p>
                <p class="text-4xl font-black mt-4">Bs {{ number_format($efectivoHoy, 2, ',', '.') }}</p>
            </div>
            <div class="bg-gradient-to-br from-blue-500 to-indigo-800 p-8 rounded-3xl text-white shadow-2xl transform hover:scale-105 transition">
                <p class="text-blue-100 text-lg font-bold">Pagos Electrónicos - QR</p>
                <p class="text-4xl font-black mt-4">Bs {{ number_format($qrHoy, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- ALERTAS DE STOCK -->
    <div>
        <h2 class="text-4xl font-black text-gray-900 mb-8 flex items-center gap-4">
            <span class="bg-red-500 text-white w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-lg">2</span>
            Alertas de Stock Crítico
        </h2>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- STOCK BAJO -->
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-3xl shadow-2xl border-4 border-yellow-300 p-8">
                <h3 class="text-3xl font-black text-yellow-800 mb-6">Poca Existencia (1-10 und)</h3>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse($stockBajo as $p)
                        <div class="bg-white p-5 rounded-2xl shadow-lg border-2 border-yellow-400 flex justify-between items-center">
                            <div>
                                <p class="font-black text-xl">{{ $p->nombre }}</p>
                                <p class="text-gray-600">{{ $p->marca?->nombre }} • {{ $p->modelo?->nombre }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-4xl font-black text-yellow-600">{{ $p->stock }}</p>
                                <p class="text-yellow-700 font-bold">unidades</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-yellow-700 text-xl py-12">¡Todo bien! No hay productos con poco stock</p>
                    @endforelse
                </div>
            </div>

            <!-- SIN STOCK -->
            <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-3xl shadow-2xl border-4 border-red-400 p-8">
                <h3 class="text-3xl font-black text-red-800 mb-6">¡SIN STOCK!</h3>
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @forelse($sinStock as $p)
                        <div class="bg-white p-5 rounded-2xl shadow-lg border-2 border-red-500 flex justify-between items-center">
                            <div>
                                <p class="font-black text-xl">{{ $p->nombre }}</p>
                                <p class="text-gray-600">{{ $p->marca?->nombre }} • {{ $p->modelo?->nombre }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-5xl font-black text-red-600">0</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-red-700 text-xl py-12"> No hay productos agotados</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- PRODUCTOS QUE NO SE MUEVEN -->
    <div>
        <h2 class="text-4xl font-black text-gray-900 mb-8 flex items-center gap-4">
            <span class="bg-gray-600 text-white w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-lg">3</span>
            Productos que NO están saliendo (30 días sin ventas)
        </h2>

        <div class="bg-white rounded-3xl shadow-2xl border border-gray-300 p-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($productosMuertos as $p)
                    <div class="bg-gray-50 p-6 rounded-2xl border-2 border-gray-300 hover:border-gray-500 transition">
                        <p class="font-black text-lg text-gray-800">{{ $p->nombre }}</p>
                        <p class="text-gray-600 text-sm">{{ $p->marca?->nombre }} • {{ $p->modelo?->nombre }}</p>
                        <div class="mt-4 flex justify-between items-center">
                            <span class="text-3xl font-black text-gray-700">{{ $p->stock }}</span>
                            <span class="bg-red-100 text-red-800 px-4 py-2 rounded-full text-sm font-bold">Estancado</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-16">
                        <p class="text-3xl font-black text-gray-600">¡Todos los productos están girando!</p>
                        <p class="text-gray-500 mt-4">No hay productos estancados en los últimos 30 días</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- GRÁFICO + TOP PRODUCTOS -->
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-12">
        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <h2 class="text-4xl font-black text-gray-900 mb-8 flex items-center gap-4">
                <span class="bg-yellow-400 text-white w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-lg">4</span>
                Ventas Últimos 7 Días
            </h2>
            <canvas id="chartVentas"></canvas>
        </div>

        <div class="bg-white rounded-3xl shadow-2xl p-8">
            <h2 class="text-4xl font-black text-gray-900 mb-8 flex items-center gap-4">
                <span class="bg-yellow-400 text-white w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-lg">5</span>
                Top 10 Productos del Día
            </h2>
            <div class="space-y-5 max-h-96 overflow-y-auto">
                @forelse($topProductos as $i => $item)
                    <div class="flex items-center justify-between p-6 rounded-2xl hover:bg-yellow-50 transition border-2 border-gray-100">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center text-white font-black text-3xl shadow-xl">
                                {{ $i + 1 }}
                            </div>
                            <div>
                                <p class="font-black text-xl">{{ Str::limit($item->producto->nombre, 30) }}</p>
                                <p class="text-gray-600">{{ $item->producto->marca?->nombre }} • {{ $item->producto->modelo?->nombre }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-black text-3xl text-gray-900">{{ $item->total_vendido }}</p>
                            <p class="text-xl font-bold text-green-600">Bs {{ number_format($item->total_ingresos, 2, ',', '.') }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 py-20 text-2xl">Aún no hay ventas hoy</p>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new Chart(document.getElementById('chartVentas'), {
                    type: 'bar',
                    data: {
                        labels: @json($fechas),
                        datasets: [{
                            label: 'Ventas (Bs)',
                            data: @json($ventasSemanales),
                            backgroundColor: 'rgba(251, 191, 36, 0.9)',
                            borderColor: '#f59e0b',
                            borderWidth: 3,
                            borderRadius: 16,
                            barThickness: 40,
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1f2937',
                                callbacks: {
                                    label: ctx => ' Bs ' + Number(ctx.parsed.y).toLocaleString('es-VE')
                                }
                            }
                        },
                        scales: {
                            y: { 
                                beginAtZero: true,
                                ticks: { 
                                    callback: value => 'Bs ' + Number(value).toLocaleString('es-VE')
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endpush

</div>
