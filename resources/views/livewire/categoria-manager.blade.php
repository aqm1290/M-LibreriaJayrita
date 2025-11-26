<div class="min-h-screen bg-gray-50 py-12 px-6">
    <div class="max-w-6xl mx-auto">

        <!-- CABECERA -->
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-5xl font-black text-gray-900">Gestión de Categorías</h1>
                <p class="text-xl text-gray-600 mt-2">Organiza tu catálogo de productos</p>
            </div>
            <button wire:click="crear"
                class="px-8 py-5 bg-gradient-to-r from-gray-800 to-black hover:from-black hover:to-gray-900 text-white font-black text-lg rounded-xl shadow-2xl flex items-center gap-4 transform hover:scale-105 transition duration-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                </svg>
                NUEVA CATEGORÍA
            </button>
        </div>

        <!-- BUSCADOR -->
        <div class="mb-8">
            <input type="text" wire:model.live.debounce.500ms="search"
                   placeholder="Buscar por nombre o descripción..."
                   class="w-full px-6 py-4 text-lg border-2 border-gray-300 rounded-xl shadow focus:border-gray-800 focus:ring-4 focus:ring-gray-200 transition">
        </div>

        <!-- TABLA -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="px-8 py-6 text-left font-bold text-lg">Nombre</th>
                        <th class="px-8 py-6 text-left font-bold text-lg">Descripción</th>
                        <th class="px-8 py-6 text-left font-bold text-lg">Productos</th>
                        <th class="px-8 py-6 text-left font-bold text-lg">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categorias as $cat)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-8 py-6 font-bold text-gray-900 text-lg">{{ $cat->nombre }}</td>
                            <td class="px-8 py-6 text-gray-600">
                                {{ $cat->descripcion ? \Illuminate\Support\Str::limit($cat->descripcion, 80) : 'Sin descripción' }}
                            </td>
                            <td class="px-8 py-6">
                                <span class="px-4 py-2 bg-amber-100 text-amber-800 font-bold rounded-full">
                                    {{ $cat->productos_count ?? $cat->productos()->count() }} productos
                                </span>
                            </td>
                            <td class="px-8 py-6 space-x-4">
                                <button wire:click="editar({{ $cat->id }})"
                                    class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white font-bold rounded-lg shadow-lg transform hover:scale-105 transition">
                                    Editar
                                </button>
                                <button wire:click="confirmarEliminar({{ $cat->id }})"
                                    class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg shadow-lg transform hover:scale-105 transition">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-20 text-gray-500 text-2xl font-bold">
                                No hay categorías registradas
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINACIÓN -->
        <div class="mt-10 flex justify-center">
            {{ $categorias->links() }}
        </div>
    </div>

    <!-- MODAL CREAR/EDITAR -->
    @if($modal)
        <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-6">
            <div class="bg-white rounded-2xl shadow-3xl w-full max-w-2xl">
                <div class="bg-gray-900 text-white p-8 rounded-t-2xl text-center">
                    <h2 class="text-4xl font-black">
                        {{ $categoriaId ? 'EDITAR CATEGORÍA' : 'NUEVA CATEGORÍA' }}
                    </h2>
                </div>
                <div class="p-10 space-y-8">
                    <div>
                        <label class="block text-lg font-bold text-gray-800 mb-3">Nombre de la categoría *</label>
                        <input type="text" wire:model="nombre"
                               class="w-full px-6 py-4 border-2 border-gray-300 rounded-xl text-lg focus:border-gray-800 focus:ring-4 focus:ring-gray-200">
                        @error('nombre') <span class="text-red-600 font-bold mt-2 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-lg font-bold text-gray-800 mb-3">Descripción (opcional)</label>
                        <textarea wire:model="descripcion" rows="5"
                                  class="w-full px-6 py-4 border-2 border-gray-300 rounded-xl text-lg focus:border-gray-800"></textarea>
                    </div>

                    <div class="flex justify-end gap-6 pt-6 border-t border-gray-200">
                        <button wire:click="cerrarModal"
                                class="px-10 py-4 bg-gray-600 hover:bg-gray-700 text-white font-bold text-xl rounded-xl shadow-lg transition">
                            CANCELAR
                        </button>
                        <button wire:click="guardar"
                                class="px-12 py-4 bg-gradient-to-r from-gray-800 to-black hover:from-black hover:to-gray-900 text-white font-black text-2xl rounded-xl shadow-2xl transform hover:scale-105 transition">
                            GUARDAR CATEGORÍA
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- MODAL CONFIRMAR ELIMINAR -->
    @if($confirmDelete)
        <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 p-6">
            <div class="bg-white rounded-2xl shadow-3xl max-w-md w-full p-10 text-center">
                <svg class="w-20 h-20 text-red-600 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="text-3xl font-black text-gray-900 mb-4">¿Eliminar categoría?</h3>
                <p class="text-lg text-gray-600 mb-8">
                    Esta acción no se puede deshacer.<br>
                    @if($categoriaId && ($cat = \App\Models\Categoria::find($categoriaId)) && $cat->productos()->count() > 0)
                        <strong class="text-red-600">¡Hay {{ $cat->productos()->count() }} productos asociados!</strong>
                    @endif
                </p>
                <div class="flex justify-center gap-6">
                    <button wire:click="$set('confirmDelete', false)"
                            class="px-10 py-4 bg-gray-600 hover:bg-gray-700 text-white font-bold text-xl rounded-xl shadow-lg transition">
                        CANCELAR
                    </button>
                    <button wire:click="eliminar"
                            class="px-12 py-4 bg-red-600 hover:bg-red-700 text-white font-black text-2xl rounded-xl shadow-2xl transition">
                        SÍ, ELIMINAR
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Toast -->
    <script>
        window.addEventListener('toast', event => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: event.detail,
                timer: 3000,
                showConfirmButton: false
            });
        });
    </script>
</div>