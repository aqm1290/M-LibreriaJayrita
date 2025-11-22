<div class="p-6">

    {{-- Título + botón crear --}}
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Gestión de Productos</h1>

        <button wire:click="crear"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
            + Nuevo Producto
        </button>
    </div>

    {{-- Buscador --}}
    <div class="mb-4">
        <input type="text" wire:model.live="search"
            placeholder="Buscar productos..."
            class="w-full px-4 py-2 border rounded-lg shadow-sm focus:ring focus:ring-blue-300">
    </div>

    {{-- Tabla --}}
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="w-full table-auto">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="px-4 py-2">Imagen</th>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Código</th>
                    <th class="px-4 py-2">Precio</th>
                    <th class="px-4 py-2">Stock</th>
                    <th class="px-4 py-2">Marca</th>
                    <th class="px-4 py-2">Modelo</th>
                    <th class="px-4 py-2">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $p)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-2">
                        @if($p->url_imagen)
                            <img src="{{ asset('storage/'.$p->url_imagen) }}" class="w-14 h-14 rounded object-cover">
                        @else
                            <span class="text-gray-400">Sin imagen</span>
                        @endif
                    </td>

                    <td class="px-4 py-2">{{ $p->nombre }}</td>
                    <td class="px-4 py-2">{{ $p->codigo }}</td>
                    <td class="px-4 py-2">{{ number_format($p->precio, 2) }}</td>
                    <td class="px-4 py-2">{{ $p->stock }}</td>
                    <td class="px-4 py-2">{{ $p->marca->nombre ?? '-' }}</td>
                    <td class="px-4 py-2">{{ $p->modelo->nombre ?? '-' }}</td>

                    <td class="px-4 py-2">
                        <button wire:click="editar({{ $p->id }})"
                            class="text-blue-600 hover:underline mr-3">Editar</button>

                        <button wire:click="confirmarEliminar({{ $p->id }})"
                            class="text-red-600 hover:underline">Eliminar</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10" class="text-center py-4 text-gray-500">
                        No hay productos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $productos->links() }}
    </div>

    {{-- Modal --}}
    @if ($modal)
    <div class="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50 px-4">
        <div class="bg-white w-full max-w-2xl rounded-lg shadow-lg p-6 
                max-h-[90vh] overflow-y-auto flex flex-col">
                
            <h2 class="text-xl font-bold mb-4">
                {{ $productoId ? 'Editar Producto' : 'Nuevo Producto' }}
            </h2>

            <div class="grid grid-cols-2 gap-4">

                {{-- Nombre --}}
                <div>
                    <label class="block text-sm font-medium">Nombre</label>
                    <input type="text" wire:model="nombre"
                        class="w-full border px-3 py-2 rounded">
                    @error('nombre')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                {{-- Código --}}
                <div>
                    <label class="block text-sm font-medium">Código</label>
                    <input type="text" wire:model="codigo"
                        class="w-full border px-3 py-2 rounded">
                    @error('codigo')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                {{-- Precio --}}
                <div>
                    <label class="block text-sm font-medium">Precio</label>
                    <input type="number" wire:model="precio" step="0.01" min="0"
                        class="w-full border px-3 py-2 rounded">
                    @error('precio')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                {{-- Costo Compra --}}
                <div>
                    <label class="block text-sm font-medium">Costo Compra</label>
                    <input type="number" wire:model="costo_compra" step="0.01" min="0"
                        class="w-full border px-3 py-2 rounded">
                    @error('costo_compra')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                {{-- Stock --}}
                <div>
                    <label class="block text-sm font-medium">Stock</label>
                    <input type="number" wire:model="stock" min="0"
                        class="w-full border px-3 py-2 rounded">
                    @error('stock')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror
                </div>

                {{-- Color --}}
                <div>
                    <label class="block text-sm font-medium">Color</label>
                    <input type="text" wire:model="color"
                        class="w-full border px-3 py-2 rounded">
                </div>

                {{-- Tipo --}}
                <div>
                    <label class="block text-sm font-medium">Tipo</label>
                    <input type="text" wire:model="tipo"
                        class="w-full border px-3 py-2 rounded">
                </div>

                {{-- Categoría --}}
                <div>
                    <label class="block text-sm font-medium">Categoría</label>
                    <select wire:model="categoria_id" class="w-full border px-3 py-2 rounded">
                        <option value="">Seleccione</option>
                        @foreach($categorias as $c)
                            <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Marca --}}
                <div>
                    <label class="block text-sm font-medium">Marca</label>
                    <select wire:model.live="marca_id" class="w-full border px-3 py-2 rounded">
                        <option value="">Seleccione</option>
                        @foreach($marcas as $m)
                            <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Modelo --}}
                <div>
                    <label class="block text-sm font-medium">Modelo</label>
                    <select wire:model="modelo_id" class="w-full border px-3 py-2 rounded">
                        <option value="">Seleccione</option>
                        @foreach($modelos as $mo)
                            @if(!$marca_id || $mo->marca_id == $marca_id)
                                <option value="{{ $mo->id }}">{{ $mo->nombre }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>

                {{-- Promo --}}
                <div>
                    <label class="block text-sm font-medium">Promo</label>
                    <select wire:model="promo_id" class="w-full border px-3 py-2 rounded">
                        <option value="">Seleccione</option>
                        @foreach($promos as $promo)
                            <option value="{{ $promo->id }}">{{ $promo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Imagen --}}
                <div class="col-span-2">
                    <label class="block text-sm font-medium">Imagen</label>
                    <input type="file" wire:model="imagen" class="w-full border px-3 py-2 rounded">

                    @if ($imagen)
                        <img src="{{ $imagen->temporaryUrl() }}" class="w-24 h-24 mt-2 rounded shadow">
                    @elseif($url_imagen)
                        <img src="{{ asset('storage/'.$url_imagen) }}" class="w-24 h-24 mt-2 rounded shadow">
                    @endif
                </div>

            </div>

            {{-- Descripción --}}
            <div class="mt-3">
                <label class="block text-sm font-medium">Descripción</label>
                <textarea wire:model="descripcion"
                    class="w-full border px-3 py-2 rounded" rows="3"></textarea>
            </div>

            {{-- Botones --}}
            <div class="flex justify-end space-x-3 mt-6">
                <button wire:click="cerrarModal"
                    class="px-4 py-2 bg-gray-400 hover:bg-gray-500 text-white rounded-lg">
                    Cancelar
                </button>

                <button wire:click="guardar"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Guardar
                </button>
            </div>

        </div>
    </div>
    @endif

    {{-- SweetAlert2 --}}
    <script>
        window.addEventListener('toast', event => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                timer: 3000,
                showConfirmButton: false,
                icon: 'success',
                title: event.detail,
            });
        });

        window.addEventListener('confirmar-eliminar', event => {
            Swal.fire({
                title: "¿Eliminar?",
                text: "Esta acción no se puede revertir",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, eliminar",
                cancelButtonText: "Cancelar",
            }).then(result => {
                if (result.isConfirmed) {
                    Livewire.dispatch('eliminar');
                }
            });
        });
    </script>

</div>
