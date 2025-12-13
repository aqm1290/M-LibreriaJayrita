<div class="min-h-screen bg-gradient-to-br from-yellow-100 via-yellow-50 to-orange-100 py-12 px-6">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- CABECERA -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                    Gestión de categorías mayores
                </h1>
                <p class="mt-2 text-base md:text-lg text-orange-700/90">
                    Organiza las secciones principales de tu catálogo.
                </p>
            </div>
            <button wire:click="crear"
                class="inline-flex items-center gap-3 px-6 py-3 md:px-8 md:py-4
                       bg-gradient-to-r from-yellow-400 via-yellow-500 to-orange-500
                       hover:from-yellow-500 hover:via-yellow-600 hover:to-orange-600
                       text-white font-semibold md:font-black text-sm md:text-lg
                       rounded-2xl shadow-[0_14px_40px_rgba(249,115,22,0.65)]
                       transform hover:-translate-y-0.5 hover:scale-[1.02]
                       transition">
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                NUEVA CATEGORÍA MAYOR
            </button>
        </div>

        <!-- FILTROS -->
        <div class="bg-white rounded-2xl shadow-xl border border-yellow-200 p-5 md:p-6">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <input type="text" wire:model.live.debounce.500ms="search" placeholder="Buscar categoría mayor..."
                    class="w-full md:flex-1 px-5 md:px-6 py-3.5 md:py-4 text-sm md:text-base
                           border border-yellow-300 rounded-2xl bg-white/80
                           focus:border-yellow-500 focus:ring-2 focus:ring-yellow-200 outline-none transition">

                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input type="checkbox" wire:model.live="mostrarInactivos"
                        class="w-5 h-5 rounded border-yellow-300 text-rose-600 focus:ring-rose-500">
                    <span class="text-sm md:text-base font-semibold text-rose-600">
                        Mostrar inactivas
                    </span>
                </label>
            </div>
        </div>

        <!-- LISTADO -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-yellow-200">
            <!-- ESCRITORIO -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gradient-to-r from-yellow-400 via-yellow-500 to-orange-500 text-white">
                        <tr>
                            <th class="px-8 py-4 text-left font-semibold">Nombre</th>
                            <th class="px-8 py-4 text-left font-semibold">Slug</th>
                            <th class="px-8 py-4 text-left font-semibold">Estado</th>
                            <th class="px-8 py-4 text-right font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-yellow-50">
                        @forelse($categoriasMayores as $cat)
                            <tr
                                class="{{ $cat->activo ? 'hover:bg-yellow-50/70' : 'bg-rose-50/30 hover:bg-rose-50/60' }} transition-colors">
                                <td class="px-8 py-5 align-top">
                                    <div class="font-semibold text-slate-900">
                                        {{ $cat->nombre }}
                                    </div>
                                    @if (!$cat->activo)
                                        <span
                                            class="mt-1 inline-flex items-center px-3 py-1 text-[0.65rem] font-bold
                                                   text-rose-700 bg-rose-100 rounded-full">
                                            INACTIVA
                                        </span>
                                    @endif
                                </td>
                                <td class="px-8 py-5 align-top text-slate-600">
                                    {{ $cat->slug }}
                                </td>
                                <td class="px-8 py-5 align-top">
                                    <span
                                        class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold
                                        {{ $cat->activo ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $cat->activo ? 'ACTIVA' : 'INACTIVA' }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 align-top">
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="editar({{ $cat->id }})"
                                            class="p-2.5 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200
                                                   border border-yellow-200 shadow-sm transition"
                                            title="Editar categoría mayor">
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </button>
                                        <button wire:click="confirmarEliminar({{ $cat->id }})"
                                            class="p-2.5 rounded-lg {{ $cat->activo ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white shadow-sm transition"
                                            title="{{ $cat->activo ? 'Desactivar' : 'Reactivar' }}">
                                            @if ($cat->activo)
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
                                <td colspan="4" class="py-16 text-center text-slate-500 text-base">
                                    No se encontraron categorías mayores
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- MÓVIL -->
            <div class="lg:hidden divide-y divide-yellow-50">
                @forelse($categoriasMayores as $cat)
                    <div class="{{ $cat->activo ? 'bg-white' : 'bg-rose-50/40' }} p-5">
                        <div class="flex items-start gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-slate-900 text-base">
                                        {{ $cat->nombre }}
                                    </h3>
                                    @if (!$cat->activo)
                                        <span
                                            class="px-2.5 py-0.5 text-[0.65rem] font-bold text-rose-700 bg-rose-100 rounded-full">
                                            INACTIVA
                                        </span>
                                    @endif
                                </div>

                                <p class="mt-1 text-sm text-slate-600">
                                    Slug: {{ $cat->slug }}
                                </p>

                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    <span
                                        class="px-4 py-1.5 rounded-full text-xs font-bold
                                        {{ $cat->activo ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $cat->activo ? 'ACTIVA' : 'INACTIVA' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                <button wire:click="editar({{ $cat->id }})"
                                    class="p-2.5 rounded-lg bg-yellow-100 text-yellow-800 hover:bg-yellow-200 transition"
                                    title="Editar categoría mayor">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <button wire:click="confirmarEliminar({{ $cat->id }})"
                                    class="p-2.5 rounded-lg {{ $cat->activo ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white transition"
                                    title="{{ $cat->activo ? 'Desactivar' : 'Reactivar' }}">
                                    @if ($cat->activo)
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
                        No se encontraron categorías mayores
                    </div>
                @endforelse
            </div>
        </div>

        <!-- PAGINACIÓN -->
        <div class="px-6 py-5 bg-white border-t border-yellow-200 rounded-2xl shadow-sm">
            @if ($categoriasMayores->count())
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="text-sm text-slate-600">
                        Mostrando
                        <span class="font-bold text-slate-900">{{ $categoriasMayores->firstItem() }}</span>
                        al
                        <span class="font-bold text-slate-900">{{ $categoriasMayores->lastItem() }}</span>
                        de
                        <span class="font-bold text-slate-900">{{ $categoriasMayores->total() }}</span>
                        resultados
                    </div>

                    <div class="flex items-center gap-2">
                        {{ $categoriasMayores->onEachSide(1)->links('vendor.pagination.tailwind-espanol') }}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- MODAL CREAR / EDITAR -->
    @if ($modal)
        <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-6">
            <div class="bg-white rounded-3xl shadow-3xl w-full max-w-2xl overflow-hidden border border-yellow-200">
                <div
                    class="bg-gradient-to-r from-yellow-400 via-yellow-500 to-orange-500 text-white px-8 py-6 text-center rounded-t-3xl">
                    <h2 class="text-2xl md:text-3xl font-black">
                        {{ $categoriaMayorId ? 'Editar categoría mayor' : 'Nueva categoría mayor' }}
                    </h2>
                </div>

                <div class="px-8 py-7 space-y-6">
                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-2 uppercase tracking-wide">
                            Nombre *
                        </label>
                        <input type="text" wire:model="nombre"
                            class="w-full px-5 py-3 rounded-2xl border border-yellow-300 bg-white/90 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-500 transition">
                        @error('nombre')
                            <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-2 uppercase tracking-wide">
                            Web *
                        </label>
                        <input type="text" wire:model="slug"
                            class="w-full px-5 py-3 rounded-2xl border border-yellow-300 bg-white/90 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-500 transition">
                        @error('slug')
                            <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" wire:model="activo"
                            class="w-5 h-5 rounded border-yellow-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="text-sm font-semibold text-slate-800">
                            Categoría mayor activa
                        </span>
                    </div>
                </div>

                <div class="px-8 py-5 bg-yellow-50/80 border-t border-yellow-200 rounded-b-3xl flex justify-end gap-3">
                    <button wire:click="cerrarModal"
                        class="px-5 py-2.5 rounded-xl border border-yellow-300 bg-white text-slate-700 text-sm font-medium
                               hover:bg-yellow-50 hover:border-yellow-400 transition
                    <button wire:click="cerrarModal"
                        class="px-5 py-2.5 rounded-xl border border-yellow-300 bg-white text-slate-700 text-sm font-medium
                               hover:bg-yellow-50 hover:border-yellow-400 transition">
                        Cancelar
                    </button>
                    <button wire:click="guardar"
                        class="px-7 py-2.5 rounded-xl bg-gradient-to-r from-yellow-500 via-orange-500 to-orange-600 text-white text-sm font-semibold
                               hover:from-yellow-600 hover:via-orange-600 hover:to-orange-700
                               shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition">
                        Guardar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL CONFIRMAR -->
    @if ($confirmDelete && $categoriaMayor)
        <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-6">
            <div
                class="bg-white rounded-3xl shadow-3xl max-w-md w-full px-8 py-7 text-center border border-yellow-200">
                <div
                    class="w-20 h-20 mx-auto mb-5 rounded-2xl flex items-center justify-center
                    {{ $categoriaMayor->activo ? 'bg-rose-100' : 'bg-emerald-100' }}">
                    <svg class="w-10 h-10 {{ $categoriaMayor->activo ? 'text-rose-600' : 'text-emerald-600' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="{{ $categoriaMayor->activo
                                ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
                                : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}" />
                    </svg>
                </div>

                <h3 class="text-xl font-black text-slate-900 mb-3">
                    {{ $categoriaMayor->activo ? '¿Desactivar categoría mayor?' : '¿Reactivar categoría mayor?' }}
                </h3>
                <p class="text-sm text-slate-600 mb-7">
                    {{ $categoriaMayor->activo
                        ? 'Las categorías asociadas podrían dejar de mostrarse en el menú principal.'
                        : 'Las categorías asociadas volverán a estar visibles en el menú principal.' }}
                </p>

                <div class="flex justify-center gap-3">
                    <button wire:click="$set('confirmDelete', false)"
                        class="px-6 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 text-sm font-medium
                               hover:bg-slate-100 hover:border-slate-400 transition">
                        Cancelar
                    </button>
                    <button wire:click="eliminar"
                        class="px-7 py-2.5 rounded-xl text-sm font-semibold text-white shadow-md hover:shadow-lg
                               transform hover:-translate-y-0.5 transition
                               {{ $categoriaMayor->activo ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }}">
                        {{ $categoriaMayor->activo ? 'Desactivar' : 'Reactivar' }}
                    </button>
                </div>
            </div>
        </div>
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
