<div class="space-y-8">

    <!-- Título + Filtros -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
        <div>
            <h1 class="text-4xl font-black text-gray-900">
                Productos Más Vendidos
            </h1>
            <p class="text-gray-600 mt-2 font-medium">
                Desde <span
                    class="text-yellow-700 font-bold">{{ \Carbon\Carbon::parse($fechaInicio)->format('d/m/Y') }}</span>
                hasta <span
                    class="text-yellow-700 font-bold">{{ \Carbon\Carbon::parse($fechaFin)->format('d/m/Y') }}</span>
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <select wire:model.live="rango"
                class="px-5 py-3 rounded-xl border-2 border-amber-300 focus:border-yellow-500 focus:outline-none">
                <option value="hoy">Hoy</option>
                <option value="ayer">Ayer</option>
                <option value="semana">Esta semana</option>
                <option value="mes">Este mes</option>
                <option value="personalizado">Personalizado</option>
            </select>

            @if ($rango === 'personalizado')
                <input type="date" wire:model.live="fechaInicio"
                    class="px-4 py-3 rounded-xl border-2 border-amber-300">
                <input type="date" wire:model.live="fechaFin" class="px-4 py-3 rounded-xl border-2 border-amber-300">
            @endif

            <input type="text" wire:model.debounce.500ms="search" placeholder="Buscar por nombre o código..."
                class="px-5 py-3 rounded-xl border-2 border-amber-300 focus:border-yellow-500 focus:outline-none">
        </div>
    </div>

    <!-- Tabla -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-200">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-yellow-500 to-orange-600 text-white">
                    <tr>
                        <th class="px-6 py-5 text-left font-black uppercase tracking-wider">#</th>
                        <th class="px-6 py-5 text-left font-black uppercase tracking-wider">Producto</th>
                        <th class="px-6 py-5 text-center font-black uppercase tracking-wider">Código</th>
                        <th class="px-6 py-5 text-center font-black uppercase tracking-wider">Vendidos</th>
                        <th class="px-6 py-5 text-center font-black uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-5 text-right font-black uppercase tracking-wider">Ingresos</th>
                        <th class="px-6 py-5 text-center font-black uppercase tracking-wider">Ganancia*</th>
                        <th class="px-6 py-5 text-center font-black uppercase tracking-wider">% Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($productos as $i => $item)
                        @php
                            $producto = $item->producto;
                            $costoTotal = $producto->costo_compra * $item->total_vendido;
                            $ganancia = $item->total_ingresado - $costoTotal;
                            $porcentaje =
                                $totalGeneral > 0 ? round(($item->total_ingresado / $totalGeneral) * 100, 2) : 0;
                        @endphp
                        <tr class="hover:bg-yellow-50 transition text-sm">
                            <td class="px-6 py-5 font-bold text-gray-700">{{ $loop->iteration }}</td>
                            <td class="px-6 py-5">
                                <p class="font-black text-gray-900">{{ $producto->nombre }}</p>
                                <p class="text-gray-500 text-xs">{{ $producto->marca?->nombre }}
                                    {{ $producto->modelo?->nombre }}</p>
                            </td>
                            <td class="px-6 py-5 text-center text-gray-600">{{ $producto->codigo ?? '—' }}</td>
                            <td class="px-6 py-5 text-center">
                                <span
                                    class="inline-block px-4 py-2 rounded-full bg-green-100 text-green-800 font-black text-lg">
                                    {{ $item->total_vendido }}
                                </span>
                            </td>
                            <td
                                class="px-6 py-5 text-center font-bold {{ $producto->stock < 10 ? 'text-red-600' : 'text-gray-700' }}">
                                {{ $producto->stock }}
                            </td>
                            <td class="px-6 py-5 text-right font-black text-lg text-gray-900">
                                Bs {{ number_format($item->total_ingresado, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-5 text-center font-bold text-green-600">
                                Bs {{ number_format($ganancia, 2, ',', '.') }}
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="inline-block px-3 py-1 bg-amber-100 text-amber-800 rounded-full font-bold">
                                    {{ $porcentaje }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-16 text-center text-gray-500 text-xl font-medium">
                                No hay ventas en el rango seleccionado
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            {{ $productos->links() }}
        </div>
    </div>

    <p class="text-xs text-gray-500 text-center mt-6">
        * Ganancia = Ingresos - (Costo de compra × cantidad vendida)
    </p>
</div>
