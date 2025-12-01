<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-amber-50 py-10 px-4 md:px-8">
    <div class="max-w-6xl mx-auto space-y-6">

        <div class="text-center space-y-2">
            <p class="text-xs md:text-sm font-semibold tracking-[0.35em] text-amber-700 uppercase">
                Punto de venta
            </p>
            <h1 class="text-3xl md:text-4xl font-black text-slate-900">
                Historial de PDFs
            </h1>
            <p class="text-xs md:text-sm text-slate-500">
                Consulta y abre los tickets de venta y reportes de cierre generados.
            </p>
        </div>

        <div class="bg-white/90 rounded-3xl shadow-2xl border border-amber-100 p-5 md:p-6 space-y-6">

            <!-- FILTROS -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="inline-flex rounded-2xl bg-slate-100 p-1 shadow-sm">
                    <button
                        wire:click="cambiarTipo('tickets')"
                        class="px-5 py-2.5 text-sm font-bold rounded-2xl transition-all duration-200
                               {{ $tipo === 'tickets' ? 'bg-amber-500 text-white shadow-lg' : 'text-slate-600 hover:bg-slate-200' }}">
                        Tickets de Venta
                    </button>
                    <button
                        wire:click="cambiarTipo('cierres')"
                        class="px-5 py-2.5 text-sm font-bold rounded-2xl transition-all duration-200
                               {{ $tipo === 'cierres' ? 'bg-emerald-500 text-white shadow-lg' : 'text-slate-600 hover:bg-slate-200' }}">
                        Cierres de Caja
                    </button>
                </div>

                <input
                    type="text"
                    wire:model.debounce.500ms="buscar"
                    placeholder="Buscar por fecha, cliente, cajero..."
                    class="px-5 py-3 rounded-2xl border border-slate-300 focus:border-amber-500 focus:outline-none focus:ring-4 focus:ring-amber-100 bg-white shadow-sm">
            </div>

            <!-- TABLA -->
            <div class="overflow-x-auto rounded-2xl border border-slate-200">
                <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-slate-800 to-slate-900 text-white">
                        <tr>
                            <th class="px-6 py-4 text-left font-bold">
                                {{ $tipo === 'tickets' ? 'Venta #' : 'Fecha Cierre' }}
                            </th>
                            <th class="px-6 py-4 text-left font-bold">
                                {{ $tipo === 'tickets' ? 'Cliente' : 'Cajero' }}
                            </th>
                            <th class="px-6 py-4 text-left font-bold">
                                {{ $tipo === 'tickets' ? 'Total' : 'Total Ventas' }}
                            </th>
                            <th class="px-6 py-4 text-left font-bold">Generado</th>
                            <th class="px-6 py-4 text-center font-bold">PDF</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($items as $item)
                            @if($tipo === 'tickets')
                                <tr class="hover:bg-amber-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-amber-700">#{{ $item->id }}</div>
                                        <div class="text-xs text-slate-500">{{ $item->created_at->format('d/m/Y H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-semibold text-amber-700">
                                            {{ $item->nombre_cliente_real ?? $item->nombre_cliente_fallback ?? 'Cliente Genérico' }}
                                        </div>
                                        @if($item->cliente_documento)
                                            <div class="text-xs text-slate-500">CI: {{ $item->cliente_documento }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-bold text-lg text-amber-600">
                                        Bs {{ number_format($item->total, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500 font-mono">
                                        {{ basename($item->ticket_pdf) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ asset('storage/' . $item->ticket_pdf) }}" target="_blank"
                                           class="inline-block px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-xs shadow">
                                            Ver Ticket
                                        </a>
                                    </td>
                                </tr>
                            @else
                                <tr class="hover:bg-emerald-50 transition">
                                    <td class="px-6 py-4 font-bold">
                                        {{ \Carbon\Carbon::parse($item->fecha)->translatedFormat('d \d\e F \d\e Y') }}
                                    </td>
                                    <td class="px-6 py-4 font-medium">
                                        {{ $item->usuario->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-lg text-emerald-600">
                                        Bs {{ number_format($item->total_ventas, 2) }}
                                    </td>
                                    <td class="px-6 py-4 text-xs text-slate-500 font-mono">
                                        {{ basename($item->reporte_pdf) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ asset('storage/' . $item->reporte_pdf) }}" target="_blank"
                                           class="inline-block px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs shadow">
                                            Ver Reporte
                                        </a>
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-12 text-slate-400">
                                    No se encontraron registros con los filtros actuales.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="flex justify-center mt-6">
                {{ $items->links() }}
            </div>
        </div>
    </div>
</div>