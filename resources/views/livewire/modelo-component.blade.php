<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold">Gestión de Modelos</h2>
            <p class="text-sm text-gray-500">Modelos vinculados a sus marcas.</p>
        </div>

        <div class="flex items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Buscar modelo o marca..."
                   class="border rounded px-3 py-2 w-64" />

            <button wire:click="crear"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                + Nuevo modelo
            </button>
        </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-hidden rounded-lg border border-gray-200">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Modelo</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Marca</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Descripción</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Acciones</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y">
                @forelse($modelos as $m)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">{{ $m->nombre }}</td>
                        <td class="px-6 py-4 text-sm">
                            {{ $m->marca?->nombre ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            {{ \Illuminate\Support\Str::limit($m->descripcion, 120) }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <button wire:click="editar({{ $m->id }})"
                                        class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">Editar</button>

                                <button wire:click="confirmarEliminar({{ $m->id }})"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            No hay modelos registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $modelos->links() }}
    </div>

    <!-- Modal crear/editar -->
    @if($modal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">

                <h3 class="text-lg font-semibold mb-4">
                    {{ $modeloId ? 'Editar modelo' : 'Nuevo modelo' }}
                </h3>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium">Marca</label>
                        <select wire:model="marca_id" class="mt-1 w-full border rounded px-3 py-2">
                            <option value="">-- Seleccione marca --</option>

                            @foreach($marcas as $marca)
                                <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                            @endforeach
                        </select>
                        @error('marca_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Nombre del modelo</label>
                        <input type="text" wire:model="nombre"
                               class="mt-1 w-full border rounded px-3 py-2" />
                        @error('nombre') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Descripción</label>
                        <textarea wire:model="descripcion" rows="3"
                                  class="mt-1 w-full border rounded px-3 py-2"></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button wire:click="cerrarModal"
                                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                            Cancelar
                        </button>

                        <button wire:click="guardar"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Guardar
                        </button>
                    </div>
                </div>

            </div>
        </div>
    @endif

</div>

@push('scripts')
<script>
    // Toast (SweetAlert2)
    Livewire.on('toast', message => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            timer: 3000,
            timerProgressBar: true,
            showConfirmButton: false,
            icon: 'success',
            title: message
        });
    });

    // Confirmación de eliminar
    Livewire.on('confirmar-eliminar', () => {
        Swal.fire({
            title: "¿Eliminar modelo?",
            text: "Esta acción no se puede deshacer.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6"
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('eliminar');
            }
        });
    });
</script>
@endpush
