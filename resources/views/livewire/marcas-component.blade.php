<div class="min-h-screen bg-slate-50 py-12 px-6">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- CABECERA -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                    Gestión de marcas
                </h1>
                <p class="mt-2 text-base md:text-lg text-slate-600">
                    Administra todas las marcas de tu catálogo.
                </p>
            </div>
            <button
                wire:click="crear"
                class="inline-flex items-center gap-3 px-6 py-3 md:px-8 md:py-4
                       bg-gradient-to-r from-gray-800 to-black hover:from-black hover:to-gray-900
                       text-white font-semibold md:font-black text-sm md:text-lg
                       rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 hover:scale-[1.02]
                       transition"
            >
                <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                NUEVA MARCA
            </button>
        </div>

        <!-- FILTROS -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-5 md:p-6">
            <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                <input
                    type="text"
                    wire:model.live.debounce.500ms="search"
                    placeholder="Buscar marca..."
                    class="w-full md:flex-1 px-5 md:px-6 py-3.5 md:py-4 text-sm md:text-base
                           border border-slate-200 rounded-2xl bg-white/80
                           focus:border-gray-900 focus:ring-2 focus:ring-gray-200 outline-none transition"
                >

                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input
                        type="checkbox"
                        wire:model.live="mostrarInactivos"
                        class="w-5 h-5 rounded border-slate-300 text-rose-600 focus:ring-rose-500"
                    >
                    <span class="text-sm md:text-base font-semibold text-slate-700">
                        Mostrar inactivas
                    </span>
                </label>
            </div>
        </div>

        <!-- TABLA / LISTA -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            <!-- ESCRITORIO -->
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-900 text-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left font-semibold">Logo</th>
                            <th class="px-6 py-4 text-left font-semibold">Nombre</th>
                            <th class="px-6 py-4 text-left font-semibold">Descripción</th>
                            <th class="px-6 py-4 text-left font-semibold">Productos</th>
                            <th class="px-6 py-4 text-left font-semibold">Estado</th>
                            <th class="px-6 py-4 text-right font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($marcas as $m)
                            <tr class="{{ $m->activo ? 'hover:bg-slate-50/60' : 'bg-rose-50/30 hover:bg-rose-50/60' }} transition-colors">
                                <td class="px-6 py-5 align-top">
                                    @if($m->url_imagen)
                                        <img
                                            src="{{ asset('storage/'.$m->url_imagen) }}"
                                            alt="{{ $m->nombre }}"
                                            class="w-14 h-14 object-contain rounded-xl shadow-md border border-slate-200 bg-white"
                                        >
                                    @else
                                        <div class="w-14 h-14 bg-slate-100 border border-dashed border-slate-300 rounded-xl flex items-center justify-center">
                                            <span class="text-[0.65rem] font-semibold text-slate-500">SIN LOGO</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="font-semibold text-slate-900">
                                        {{ $m->nombre }}
                                    </div>
                                    @if(!$m->activo)
                                        <span class="mt-1 inline-flex items-center px-3 py-1 text-[0.65rem] font-bold
                                                     text-rose-700 bg-rose-100 rounded-full">
                                            INACTIVA
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-top text-slate-600">
                                    {{ $m->descripcion ? \Illuminate\Support\Str::limit($m->descripcion, 70) : '—' }}
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full bg-amber-100
                                                 text-amber-800 font-semibold text-xs">
                                        {{ $m->productos_count ?? $m->productos()->count() }} productos
                                    </span>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-bold
                                                 {{ $m->activo ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $m->activo ? 'ACTIVA' : 'INACTIVA' }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="flex justify-end gap-2">
                                        <button
                                            wire:click="editar({{ $m->id }})"
                                            class="p-2.5 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200
                                                   shadow-sm transition"
                                            title="Editar marca"
                                        >
                                            <i data-lucide="pencil" class="w-4 h-4"></i>
                                        </button>

                                        <button
                                            wire:click="confirmarEliminar({{ $m->id }})"
                                            class="p-2.5 rounded-lg {{ $m->activo ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }}
                                                   text-white shadow-sm transition"
                                            title="{{ $m->activo ? 'Desactivar marca' : 'Reactivar marca' }}"
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
                                <td colspan="6" class="py-16 text-center text-slate-500 text-base font-medium">
                                    No se encontraron marcas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- MÓVIL -->
            <div class="lg:hidden divide-y divide-slate-100">
                @forelse($marcas as $m)
                    <div class="{{ $m->activo ? 'bg-white' : 'bg-rose-50/40' }} p-5">
                        <div class="flex items-start gap-4">
                            <div>
                                @if($m->url_imagen)
                                    <img
                                        src="{{ asset('storage/'.$m->url_imagen) }}"
                                        alt="{{ $m->nombre }}"
                                        class="w-16 h-16 object-contain rounded-xl shadow-md border border-slate-200 bg-white"
                                    >
                                @else
                                    <div class="w-16 h-16 bg-slate-100 border border-dashed border-slate-300 rounded-xl flex items-center justify-center">
                                        <span class="text-[0.7rem] font-semibold text-slate-500">LOGO</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-semibold text-slate-900 text-base">
                                        {{ $m->nombre }}
                                    </h3>
                                    @if(!$m->activo)
                                        <span class="px-2.5 py-0.5 text-[0.65rem] font-bold text-rose-700 bg-rose-100 rounded-full">
                                            INACTIVA
                                        </span>
                                    @endif
                                </div>

                                <p class="mt-1 text-sm text-slate-600">
                                    {{ $m->descripcion ? \Illuminate\Support\Str::limit($m->descripcion, 60) : 'Sin descripción' }}
                                </p>

                                <div class="mt-3 flex flex-wrap items-center gap-2">
                                    <span class="px-4 py-1.5 rounded-full bg-amber-100 text-amber-800 font-semibold text-xs">
                                        {{ $m->productos_count ?? $m->productos()->count() }} prod.
                                    </span>
                                    <span class="px-4 py-1.5 rounded-full text-xs font-bold
                                                 {{ $m->activo ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $m->activo ? 'ACTIVA' : 'INACTIVA' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex flex-col gap-2">
                                <button
                                    wire:click="editar({{ $m->id }})"
                                    class="p-2.5 rounded-lg bg-amber-100 text-amber-800 hover:bg-amber-200 transition"
                                    title="Editar marca"
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
                        No se encontraron marcas
                    </div>
                @endforelse
            </div>
        </div>

        <!-- PAGINACIÓN -->
        <div class="flex justify-center">
            {{ $marcas->links() }}
        </div>
    </div>

    <!-- MODAL CREAR/EDITAR -->
    @if($modal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div class="w-full max-w-3xl rounded-3xl bg-white/95 shadow-2xl border border-white/60">
                <div class="bg-gradient-to-r from-gray-800 to-black text-white px-8 py-5 text-center rounded-t-3xl">
                    <h2 class="text-2xl md:text-3xl font-black tracking-tight">
                        {{ $marca_id ? 'Editar marca' : 'Nueva marca' }}
                    </h2>
                </div>

                <div class="px-8 py-7 space-y-7">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                                Nombre *
                            </label>
                            <input
                                type="text"
                                wire:model="nombre"
                                class="w-full px-4 py-2.5 rounded-2xl border border-slate-200 bg-white/80 text-sm
                                       focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition"
                            >
                            @error('nombre')
                                <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                                Logo de la marca
                            </label>
                            <input
                                type="file"
                                wire:model="imagen"
                                accept="image/*"
                                class="block w-full text-xs text-slate-700
                                       file:mr-4 file:py-2.5 file:px-5 file:rounded-full
                                       file:border-0 file:text-xs file:font-semibold
                                       file:bg-gray-900 file:text-white hover:file:bg-black
                                       cursor-pointer"
                            >

                            <!-- PREVIEW -->
                            <div class="mt-4 flex justify-start">
                                @if($imagen)
                                    {{-- Nueva imagen subida --}}
                                    <img
                                        src="{{ $imagen->temporaryUrl() }}"
                                        class="w-24 h-24 object-contain rounded-xl shadow-md border border-slate-200 bg-white"
                                    >
                                @elseif($url_imagen)
                                    {{-- Imagen ya guardada --}}
                                    <img
                                        src="{{ asset('storage/'.$url_imagen) }}"
                                        class="w-24 h-24 object-contain rounded-xl shadow-md border border-slate-200 bg-white"
                                    >
                                @else
                                    <div class="w-24 h-24 bg-slate-100 border border-dashed border-slate-300 rounded-xl flex items-center justify-center">
                                        <span class="text-[0.7rem] font-semibold text-slate-500">SIN LOGO</span>
                                    </div>
                                @endif
                            </div>

                            @error('imagen')
                                <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                            Descripción
                        </label>
                        <textarea
                            wire:model="descripcion"
                            rows="3"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 bg-white/80 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-gray-200 focus:border-gray-900 transition resize-none"
                        ></textarea>
                    </div>
                </div>

                <div class="px-8 py-5 bg-slate-50/80 border-t border-slate-100 rounded-b-3xl flex justify-end gap-3">
                    <button
                        wire:click="cerrarModal"
                        class="px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 text-sm font-medium
                               hover:bg-slate-100 hover:border-slate-400 transition"
                    >
                        Cancelar
                    </button>
                    <button
                        wire:click="guardar"
                        class="px-7 py-2.5 rounded-xl bg-gray-900 text-white text-sm font-semibold
                               hover:bg-black shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition"
                    >
                        Guardar marca
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL CONFIRMAR ACTIVAR/DESACTIVAR -->
    @if($confirmDelete)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
            <div class="w-full max-w-md rounded-3xl bg-white/95 shadow-2xl border border-white/60 px-8 py-7 text-center">
                <div class="w-20 h-20 mx-auto mb-5 rounded-2xl flex items-center justify-center
                            {{ $this->marca?->activo ? 'bg-rose-100' : 'bg-emerald-100' }}">
                    <svg
                        class="w-10 h-10 {{ $this->marca?->activo ? 'text-rose-600' : 'text-emerald-600' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="{{ $this->marca?->activo
                                ? 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'
                                : 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' }}"
                        />
                    </svg>
                </div>

                <h3 class="text-xl font-black text-slate-900 mb-3">
                    {{ $this->marca?->activo ? '¿Desactivar marca?' : '¿Reactivar marca?' }}
                </h3>
                <p class="text-sm text-slate-600 mb-7">
                    {{ $this->marca?->activo
                        ? 'La marca quedará inactiva y no aparecerá en el catálogo.'
                        : 'La marca volverá a estar disponible en el catálogo.' }}
                </p>

                <div class="flex justify-center gap-3">
                    <button
                        wire:click="$set('confirmDelete', false)"
                        class="px-6 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-700 text-sm font-medium
                               hover:bg-slate-100 hover:border-slate-400 transition"
                    >
                        Cancelar
                    </button>
                    <button
                        wire:click="eliminar"
                        class="px-7 py-2.5 rounded-xl text-sm font-semibold text-white shadow-md hover:shadow-lg
                               transform hover:-translate-y-0.5 transition
                               {{ $this->marca?->activo ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }}"
                    >
                        {{ $this->marca?->activo ? 'Desactivar' : 'Reactivar' }}
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
