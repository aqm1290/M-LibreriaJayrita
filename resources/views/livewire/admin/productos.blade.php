<div class="min-h-screen bg-gray-50 py-12 px-6">
    <div class="max-w-7xl mx-auto">

        <!-- CABECERA -->
        <div class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-5xl font-black text-gray-900">Gestión de Productos</h1>
                <p class="text-gray-600 mt-2 text-lg">Administra el catálogo completo de Librería Jayrita</p>
            </div>
            <button wire:click="crear"
                class="px-8 py-4 bg-gradient-to-r from-gray-800 to-black hover:from-black hover:to-gray-900 text-white font-bold text-lg rounded-xl shadow-xl flex items-center gap-3 transform hover:scale-105 transition duration-300">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
                </svg>
                NUEVO PRODUCTO
            </button>
        </div>

        <!-- BUSCADOR -->
        <div class="mb-8">
            <input type="text" wire:model.live="search" placeholder="Buscar por nombre o código..."
                class="w-full px-6 py-4 text-lg border border-gray-300 rounded-xl shadow focus:border-gray-800 focus:ring-4 focus:ring-gray-200 transition">
        </div>

        <!-- TABLA -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-900 text-white">
                    <tr>
                        <th class="px-6 py-5 text-left font-semibold">Imagen</th>
                        <th class="px-6 py-5 text-left font-semibold">Nombre</th>
                        <th class="px-6 py-5 text-left font-semibold">Código</th>
                        <th class="px-6 py-5 text-left font-semibold">Precio</th>
                        <th class="px-6 py-5 text-left font-semibold">Stock</th>
                        <th class="px-6 py-5 text-left font-semibold">Marca</th>
                        <th class="px-6 py-5 text-left font-semibold">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($productos as $p)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <img src="{{ $p->imagen_url }}" 
                                 class="w-16 h-16 rounded-lg object-cover shadow border border-gray-200"
                                 alt="{{ $p->nombre }}">
                        </td>
                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $p->nombre }}</td>
                        <td class="px-6 py-4 font-mono text-gray-700">{{ $p->codigo }}</td>
                        <td class="px-6 py-4 font-bold text-green-700 text-lg">Bs {{ number_format($p->precio, 2) }}</td>
                        <td class="px-7 py-4 text-center">
                            @if($p->stock == 0)
                                <span class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-bold text-sm rounded-full shadow-md">
                                    Sin stock
                                </span>
                            @elseif($p->stock <= 5)
                                <span class="inline-flex items-center px-4 py-2 bg-orange-500 text-white font-bold text-sm rounded-full shadow-md ring-2 ring-orange-300">
                                    {{ $p->stock }} und
                                </span>
                            @else
                                <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 font-bold text-sm rounded-full">
                                    {{ $p->stock }} und
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-700">{{ $p->marca->nombre ?? '-' }}</td>
                        <td class="px-6 py-4 space-x-3">
                            <button wire:click="ver({{ $p->id }})"
                                class="px-5 py-2 bg-gray-700 hover:bg-gray-800 text-white font-semibold rounded-lg shadow hover:shadow-lg transition">
                                Ver
                            </button>
                            <button wire:click="editar({{ $p->id }})"
                                class="px-5 py-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg shadow hover:shadow-lg transition">
                                Editar
                            </button>
                            <button wire:click="confirmarEliminar({{ $p->id }})"
                                class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg shadow hover:shadow-lg transition">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-20 text-gray-500 text-xl">
                            No se encontraron productos
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- PAGINACIÓN -->
        <div class="mt-10 flex justify-center">
            {{ $productos->links() }}
        </div>
    </div>

    <!-- MODAL CREAR / EDITAR -->
    @if($modal)
    <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[92vh] overflow-y-auto">
            <div class="bg-gray-900 text-white p-8 rounded-t-2xl">
                <h2 class="text-4xl font-black text-center">
                    {{ $productoId ? 'EDITAR PRODUCTO' : 'NUEVO PRODUCTO' }}
                </h2>
            </div>

            <div class="p-10 space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Todos los campos iguales... (los dejo como estaban) -->
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Nombre *</label><input type="text" wire:model="nombre" class="w-full px-5 py-3 border border-gray-300 rounded-lg focus:border-gray-800 focus:ring-2 focus:ring-gray-200"> @error('nombre') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Código *</label><input type="text" wire:model="codigo" class="w-full px-5 py-3 border border-gray-300 rounded-lg"> @error('codigo') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Precio Venta *</label><input type="number" step="0.01" wire:model="precio" class="w-full px-5 py-3 border border-gray-300 rounded-lg"> @error('precio') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror</div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Costo Compra *</label><input type="number" step="0.01" wire:model="costo_compra" class="w-full px-5 py-3 border border-gray-300 rounded-lg"></div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Stock *</label><input type="number" wire:model="stock" class="w-full px-5 py-3 border border-gray-300 rounded-lg"></div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Categoría *</label><select wire:model="categoria_id" class="w-full px-5 py-3 border border-gray-300 rounded-lg"><option value="">Seleccione...</option>@foreach($categorias as $c)<option value="{{ $c->id }}">{{ $c->nombre }}</option>@endforeach</select></div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Marca *</label><select wire:model.live="marca_id" class="w-full px-5 py-3 border border-gray-300 rounded-lg"><option value="">Seleccione...</option>@foreach($marcas as $m)<option value="{{ $m->id }}">{{ $m->nombre }}</option>@endforeach</select></div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Modelo *</label><select wire:model="modelo_id" class="w-full px-5 py-3 border border-gray-300 rounded-lg"><option value="">Seleccione...</option>@foreach($modelos as $mo) @if(!$marca_id || $mo->marca_id == $marca_id)<option value="{{ $mo->id }}">{{ $mo->nombre }}</option>@endif @endforeach</select></div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Color</label><input type="text" wire:model="color" class="w-full px-5 py-3 border border-gray-300 rounded-lg"></div>
                    <div><label class="block text-sm font-bold text-gray-700 mb-2">Tipo</label><input type="text" wire:model="tipo" class="w-full px-5 py-3 border border-gray-300-rounded-lg"></div>
                </div>

                <div><label class="block text-sm font-bold text-gray-700 mb-2">Descripción</label><textarea wire:model="descripcion" rows="4" class="w-full px-5 py-4 border border-gray-300 rounded-lg"></textarea></div>

                <!-- IMAGEN -->
                <div>
                    <label class="block text-lg font-bold text-gray-800 mb-3">Imagen del producto</label>
                    <input type="file" wire:model="imagen" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                    
                    <div class="mt-6 flex justify-center">
                        @if($imagen)
                            <img src="{{ $imagen->temporaryUrl() }}" class="w-80 h-80 object-cover rounded-2xl shadow-2xl border-4 border-gray-300">
                        @elseif($url_imagen ?? false)
                            <img src="{{ $p?->imagen_url ?? asset('images/no-image.png') }}" class="w-80 h-80 object-cover rounded-2xl shadow-2xl border-4 border-gray-300">
                        @else
                            <div class="bg-gray-200 border-4 border-dashed rounded-2xl w-80 h-80 flex items-center justify-center text-2xl font-bold text-gray-500">Sin imagen</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-6 pt-8 border-t border-gray-200 px-10 pb-10">
                <button wire:click="cerrarModal" class="px-10 py-4 bg-gray-600 hover:bg-gray-700 text-white font-bold text-lg rounded-xl shadow-lg transition">CANCELAR</button>
                <button wire:click="guardar" class="px-12 py-4 bg-gradient-to-r from-gray-800 to-black hover:from-black hover:to-gray-900 text-white font-black text-xl rounded-xl shadow-2xl transform hover:scale-105 transition">GUARDAR PRODUCTO</button>
            </div>
        </div>
    </div>
    @endif

    <!-- MODAL VER (también corregido) -->
    @if($modalVer && $productoSeleccionado)
    <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50 p-8">
        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="bg-gray-900 text-white p-8 rounded-t-2xl text-center">
                <h2 class="text-5xl font-black">DETALLE DEL PRODUCTO</h2>
            </div>
            <div class="p-10 grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="text-center">
                    <img src="{{ $productoSeleccionado->imagen_url }}" class="w-full rounded-2xl shadow-2xl">
                </div>
                <!-- resto del detalle igual... -->
            </div>
            <div class="p-8 text-center border-t border-gray-200">
                <button wire:click="cerrarModal" class="px-20 py-5 bg-gray-900 hover:bg-black text-white font-black text-2xl rounded-xl shadow-2xl transition">CERRAR</button>
            </div>
        </div>
    </div>
    @endif

    <script>
        window.addEventListener('toast', e => Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: e.detail, timer: 3000, showConfirmButton: false }));
        window.addEventListener('confirmar-eliminar', () => {
            Swal.fire({
                title: '¿Eliminar producto?', text: "No podrás revertir esta acción", icon: 'warning',
                showCancelButton: true, confirmButtonText: 'Sí, eliminar', cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc2626'
            }).then(r => r.isConfirmed && Livewire.dispatch('eliminar'));
        });
    </script>
</div>