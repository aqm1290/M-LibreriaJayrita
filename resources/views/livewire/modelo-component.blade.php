<div class="min-h-screen bg-slate-50 py-12 px-6">
    <div class="max-w-7xl mx-auto space-y-8">

        <!-- CABECERA -->
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div>
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
                    Gestión de Modelos
                </h1>
                <p class="mt-2 text-base md:text-lg text-slate-600">
                    Administra los modelos por marca de tus productos.
                </p>
            </div>
            <button
                wire:click="abrirCrear"
                class="inline-flex items-center gap-3 px-6 py-3 md:px-8 md:py-4
                       bg-slate-900 hover:bg-black text-white font-semibold md:font-black text-sm md:text-lg
                       rounded-2xl shadow-xl hover:shadow-2xl transform hover:-translate-y-0.5 hover:scale-[1.02]
                       transition duration-200"
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
            @forelse($modelos as $m)
                <div class="{{ $m->activo ? 'hover:bg-slate-50/60' : 'bg-rose-50/30 hover:bg-rose-50/60' }} transition-colors border-b border-slate-100 last:border-0">
                    <div class="p-5 lg:p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <h3 class="text-lg font-bold text-slate-900">{{ $m->nombre }}</h3>
                                @if(!$m->activo)
                                    <span class="px-3 py-1 text-xs font-bold text-rose-700 bg-rose-100 rounded-full">INACTIVO</span>
                                @endif
                            </div>
                            <div class="mt-2 flex flex-wrap gap-3 text-sm">
                                <span class="inline-flex items-center px-3 py-1 bg-emerald-100 text-emerald-800 font-medium rounded-full">
                                    {{ $m->marca?->nombre ?? 'Sin marca' }}
                                </span>
                                <span class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-800 font-medium rounded-full">
                                    {{ $m->productos_count }} productos
                                </span>
                            </div>
                            @if($m->descripcion)
                                <p class="mt-3 text-sm text-slate-600">{{ \Illuminate\Support\Str::limit($m->descripcion, 100) }}</p>
                            @endif
                        </div>

                        <div class="flex gap-2">
                            <button
                                wire:click="editar({{ $m->id }})"
                                class="p-3 rounded-xl bg-amber-100 text-amber-700 hover:bg-amber-200 transition"
                                title="Editar"
                            >
                                <i data-lucide="pencil" class="w-5 h-5"></i>
                            </button>
                            <button
                                wire:click="confirmarEliminar({{ $m->id }})"
                                class="p-3 rounded-xl {{ $m->activo ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white transition"
                                title="{{ $m->activo ? 'Desactivar' : 'Reactivar' }}"
                            >
                                @if($m->activo)
                                    <i data-lucide="x-circle" class="w-5 h-5"></i>
                                @else
                                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-16 text-center text-slate-500">
                    <p class="text-xl font-semibold">No se encontraron modelos</p>
                    <p class="mt-2">¡Crea el primero ahora!</p>
                </div>
            @endforelse
        </div>

        <!-- PAGINACIÓN -->
        <div class="flex justify-center mt-8">
            {{ $modelos->links() }}
        </div>
    </div>

    <!-- MODAL CREAR / EDITAR -->
    @if($modal)
        <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="p-8">
                    <h2 class="text-3xl font-black text-slate-900 mb-6">
                        {{ $modeloId ? 'Editar Modelo' : 'Nuevo Modelo' }}
                    </h2>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Nombre del modelo</label>
                            <input type="text" wire:model="nombre"
                                   class="w-full px-5 py-4 rounded-2xl border border-slate-300 focus:border-slate-900 focus:ring-4 focus:ring-slate-100 outline-none transition">
                            @error('nombre') <span class="text-rose-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Marca</label>
                            <select wire:model="marca_id"
                                    class="w-full px-5 py-4 rounded-2xl border border-slate-300 focus:border-slate-900 focus:ring-4 focus:ring-slate-100 outline-none transition">
                                <option value="">Seleccionar marca</option>
                                @foreach($marcas as $marca)
                                    <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                                @endforeach
                            </select>
                            @error('marca_id') <span class="text-rose-600 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Descripción (opcional)</label>
                            <textarea wire:model="descripcion" rows="4"
                                      class="w-full px-5 py-4 rounded-2xl border border-slate-300 focus:border-slate-900 focus:ring-4 focus:ring-slate-100 outline-none transition"></textarea>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-4 justify-end">
                        <button wire:click="cerrarModal"
                                class="px-8 py-4 rounded-2xl bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold transition">
                            Cancelar
                        </button>
                        <button wire:click="guardar"
                                class="px-10 py-4 rounded-2xl bg-slate-900 hover:bg-black text-white font-bold transition shadow-lg">
                            {{ $modeloId ? 'Actualizar' : 'Crear Modelo' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- CONFIRMAR ELIMINAR -->
    @if($confirmDelete)
        <div class="fixed inset-0 bg-black/60 flex items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl shadow-2xl p-8 max-w-md">
                <h3 class="text-2xl font-black text-slate-900 mb-4">
                    {{ Modelo::find($modeloId)?->activo ? 'Desactivar' : 'Reactivar' }} modelo
                </h3>
                <p class="text-slate-600 mb-8">
                    {{ Modelo::find($modeloId)?->activo
                        ? 'Se desactivará este modelo y todos sus productos.'
                        : 'Se reactivará este modelo y sus productos.' }}
                </p>
                <div class="flex gap-4 justify-end">
                    <button wire:click="$set('confirmDelete', false)"
                            class="px-8 py-4 rounded-2xl bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold transition">
                        Cancelar
                    </button>
                    <button wire:click="eliminar"
                            class="px-10 py-4 rounded-2xl {{ Modelo::find($modeloId)?->activo ? 'bg-rose-600 hover:bg-rose-700' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white font-bold transition shadow-lg">
                        {{ Modelo::find($modeloId)?->activo ? 'Desactivar' : 'Reactivar' }}
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('toast', (message) => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: message,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        });
    });
</script>