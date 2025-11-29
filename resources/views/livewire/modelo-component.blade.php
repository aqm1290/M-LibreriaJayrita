<div class="min-h-screen bg-slate-50 py-12 px-6">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- CABECERA -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                    Gestión de modelos
                </h1>
                <p class="mt-2 text-base md:text-lg text-slate-600">
                    Administra los modelos por marca.
                </p>
            </div>
            <button
                wire:click="crear"
                class="inline-flex items-center gap-3 px-6 py-3 md:px-8 md:py-4
                       bg-slate-900 hover:bg-black text-white font-semibold md:font-black text-sm md:text-lg
                       rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 hover:scale-[1.02]
                       transition"
            >
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                NUEVO MODELO
            </button>
        </div>

        <!-- FILTROS -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-5 md:p-6">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <input
                    type="text"
                    wire:model.live.debounce.500ms="search"
                    placeholder="Buscar modelo o marca..."
                    class="w-full md:flex-1 px-5 md:px-6 py-3.5 md:py-4 text-sm md:text-base
                           border border-slate-200 rounded-2xl bg-white/80
                           focus:border-slate-900 focus:ring-2 focus:ring-slate-100 outline-none transition"
                >

                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input
                        type="checkbox"
                        wire:model.live="mostrarInactivos"
                        class="w-5 h-5 rounded border-slate-300 text-rose-600 focus:ring-rose-500"
                    >
                    <span class="text-sm md:text-base font-semibold text-slate-700">
                        Mostrar inactivos
                    </span>
                </label>
            </div>
        </div>

        <!-- LISTADO -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            <!-- ESCRITORIO -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-900 text-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">Modelo</th>
                            <th class="px-6 py-4 text-left font-semibold">Marca</th>
                            <th class="px-6 py-4 text-left font-semibold">Descripción</th>
                            <th class="px-6 py-4 text-left font-semibold">Productos</th>
                            <th class="px-6 py-4 text-left font-semibold">Estado</th>
                            <th class="px-6 py-4 text-right font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($modelos as $m)
                            <tr class="{{ $m->activo ? 'hover:bg-slate-50/60' : 'bg-rose-50/30 hover:bg-rose-50/60' }} transition-colors">
                                <td class="px-6 py-5 align-top">
                                    <div class="font-semibold text-slate-900">
                                        {{ $m->nombre }}
                                    </div>
                                    @if(!$m->activo)
                                        <span class="mt-2 inline-flex items-center px-3 py-1 text-[0.65rem] font-bold
                                                     text-rose-700 bg-rose-100 rounded-full">
                                            INACTIVO
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="inline-flex items-center px-4 py-2 bg-emerald-100 text-emerald-800
                                                font-semibold text-[0.7rem] uppercase tracking-wide
                                                rounded-full shadow-sm border border-emerald-200">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 13l-3-3 1.5-1.5L9 10l4.5-4.5L15 7l-6 6z"/>
                                        </svg>
                                        {{ $m->marca?->nombre ?? 'Sin marca' }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top text-slate-600">
                                    {{ $m->descripcion ? \Illuminate\Support\Str::limit($m->descripcion, 80) : '—' }}
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-amber-100
                                                 text-amber-800 font-semibold text-xs">
                                        {{ $m->productos_count ?? $m->productos()->count() }} prod.
                                    </span>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold
                                                 {{ $m->activo ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $m->activo ? 'ACTIVO' : 'INACTIVO' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="flex justify-end gap-2">
                                        <button
                                            wire:click="editar({{ $m->id }})"
                                            class="p-2.5 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200
                                                   shadow-sm transition"
                                            title="Editar"
                                        >
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </button>
                                        <button
                                            wire:click="confirmarEliminar({{ $m->id }})"
                                            class="p-2.5 rounded-lg {{ $m->activo ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }}
                                                   text-white shadow-sm transition"
                                            title="{{ $m->activo ? 'Desactivar' : 'Reactivar' }}"
                                        >
                                            @if($m->activo)
                                                <i data-lucide="x-circle" class="w-4 h-4"></i>
                                            @else
                                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                            @endif
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center text-slate-500 text-base">
                                    No se encontraron modelos
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- MÓVIL -->
            <div class="lg:hidden divide-y divide-slate-100">
                @forelse($modelos as $m)
                    <div class="{{ $m->activo ? 'bg-white' : 'bg-rose-50/40' }} p-5">
                        <div class="flex items-start gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-slate-900 text-base">
                                        {{ $m->nombre }}
                                    </h3>
                                    @if(!$m->activo)
                                        <span class="px-2.5 py-0.5 text-[0.65rem] font-bold text-rose-700 bg-rose-100 rounded-full">
                                            INACTIVO
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    <div class="inline-flex items-center px-4 py-1.5 bg-emerald-100 text-emerald-800
                                                font-semibold text-[0.7rem] uppercase tracking-wide rounded-full
                                                border border-emerald-200">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 2a8 8 0 100 16 8 8 0 000-16zM9 13l-3-3 1.5-1.5L9 10l4.5-4.5L15 7l-6 6z"/>
                                        </svg>
                                        {{ $m->marca?->nombre ?? 'Sin marca' }}
                                    </div>

                                    <div class="inline-flex items-center px-3 py-1.5 bg-amber-100 text-amber-800
                                                font-semibold text-[0.7rem] rounded-full border border-amber-200">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                                        </svg>
                                        {{ $m->productos_count ?? $m->productos()->count() }} prod.
                                    </div>
                                </div>

                                <p class="mt-3 text-sm text-slate-600">
                                    {{ $m->descripcion ? \Illuminate\Support\Str::limit($m->descripcion, 80) : 'Sin descripción' }}
                                </p>
                            </div>

                            <div class="flex flex-col gap-2">
                                <button
                                    wire:click="editar({{ $m->id }})"
                                    class="p-2.5 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200 transition"
                                    title="Editar"
                                >
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <button
                                    wire:click="confirmarEliminar({{ $m->id }})"
                                    class="p-2.5 rounded-lg {{ $m->activo ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white transition"
                                    title="{{ $m->activo ? 'Desactivar' : 'Reactivar' }}"
                                >
                                    @if($m->activo)
                                        <i data-lucide="x-circle" class="w-4 h-4"></i>
                                    @else
                                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-slate-500 text-base">
                        No se encontraron modelos
                    </div>
                @endforelse
            </div>
        </div>

        <!-- PAGINACIÓN -->
        <div class="flex justify-center">
            {{ $modelos->links() }}
        </div>
    </div>

    <!-- MODAL (el que ya pegaste antes) -->
    @if($modal)
        {{-- aquí va el modal elegante que ya tienes --}}
    @endif
</div>

<script>
    window.addEventListener('toast', event => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: event.detail,
            timer: 3000,
            showConfirmButton: false,
            timerProgressBar: true,
        });
    });
</script>
