<div>

    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold">Gestión de Categorías</h2>
            <p class="text-sm text-gray-500">Administra y organiza tus categorías de libros.</p>
        </div>

        <div class="flex items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Buscar categoría..."
                   class="border rounded px-3 py-2 w-64" />

            <button wire:click="crear"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none">
                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Nueva categoría
            </button>
        </div>
    </div>

    <div class="overflow-hidden rounded-lg border border-gray-200">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Descripción</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y">
                @forelse($categorias as $cat)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $cat->nombre }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ \Illuminate\Support\Str::limit($cat->descripcion, 120) }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <button wire:click="editar({{ $cat->id }})" class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">Editar</button>
                                <button wire:click="confirmarEliminar({{ $cat->id }})" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">No hay categorías registradas</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $categorias->links() }}
    </div>

    <!-- Modal Crear/Editar -->
    @if($modal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
                <h3 class="text-lg font-semibold mb-4">{{ $categoriaId ? 'Editar categoría' : 'Nueva categoría' }}</h3>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nombre</label>
                        <input type="text" wire:model.defer="nombre" class="mt-1 block w-full border rounded px-3 py-2" />
                        @error('nombre') <div class="text-sm text-red-600 mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Descripción</label>
                        <textarea wire:model.defer="descripcion" rows="4" class="mt-1 block w-full border rounded px-3 py-2"></textarea>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button wire:click="cerrarModal" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancelar</button>
                        <button wire:click="guardar" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Confirm delete -->
    @if($confirmDelete)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-lg shadow-lg p-6 max-w-sm w-full">
                <p class="mb-4">¿Seguro que quieres eliminar esta categoría?</p>
                <div class="flex justify-end gap-3">
                    <button wire:click="$set('confirmDelete', false)" class="px-3 py-2 bg-gray-200 rounded">No</button>
                    <button wire:click="eliminar" class="px-3 py-2 bg-red-600 text-white rounded">Sí, eliminar</button>
                </div>
            </div>
        </div>
    @endif

</div>
