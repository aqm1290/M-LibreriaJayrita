<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold">Gestión de Proveedores</h2>
            <p class="text-sm text-gray-500">Proveedores registrados.</p>
        </div>

        <div class="flex items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search"
                   placeholder="Buscar proveedor o empresa..."
                   class="border rounded px-3 py-2 w-64" />

            <select wire:model.live="filtroEstado"
                    class="border rounded px-3 py-2">
                <option value="">Todos</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>

            <button wire:click="crear"
                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                + Nuevo proveedor
            </button>
        </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-hidden rounded-lg border border-gray-200">
        <table class="min-w-full divide-y">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Nombre</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Empresa</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Correo</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Teléfono</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Estado</th>
                    <th class="px-6 py-3 text-left text-sm font-medium text-gray-700">Acciones</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y">
                @forelse($proveedores as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">{{ $p->nombre }}</td>
                        <td class="px-6 py-4 text-sm">{{ $p->empresa ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">{{ $p->correo }}</td>
                        <td class="px-6 py-4 text-sm">{{ $p->telefono ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">{{ ucfirst($p->estado) }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-2">
                                <button wire:click="ver({{ $p->id }})"
                                        class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">Ver</button>
                                <button wire:click="editar({{ $p->id }})"
                                        class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">Editar</button>

                                <button wire:click="confirmarEliminar({{ $p->id }})"
                                        class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Eliminar</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No hay proveedores registrados
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginación -->
    <div class="mt-4">
        {{ $proveedores->links() }}
    </div>

    <!-- Modal crear/editar/ver -->
    @if($modal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black bg-opacity-40 overflow-hidden">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-md sm:max-w-lg md:max-w-xl p-6 overflow-y-auto max-h-[90vh]">

                <h3 class="text-lg font-semibold mb-4">
                    @if($mode == 'view')
                        Detalles del proveedor
                    @else
                        {{ $proveedor_id ? 'Editar proveedor' : 'Nuevo proveedor' }}
                    @endif
                </h3>

                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium">Nombre</label>
                        @if($mode == 'view')
                            <p class="mt-1 w-full border rounded px-3 py-2 bg-gray-100">{{ $nombre }}</p>
                        @else
                            <input type="text" wire:model="nombre"
                                   class="mt-1 w-full border rounded px-3 py-2" />
                            @error('nombre') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Empresa</label>
                        @if($mode == 'view')
                            <p class="mt-1 w-full border rounded px-3 py-2 bg-gray-100">{{ $empresa ?? '-' }}</p>
                        @else
                            <input type="text" wire:model="empresa"
                                   class="mt-1 w-full border rounded px-3 py-2" />
                            @error('empresa') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Correo</label>
                        @if($mode == 'view')
                            <p class="mt-1 w-full border rounded px-3 py-2 bg-gray-100">{{ $correo }}</p>
                        @else
                            <input type="email" wire:model="correo"
                                   class="mt-1 w-full border rounded px-3 py-2" />
                            @error('correo') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Teléfono</label>
                        @if($mode == 'view')
                            <p class="mt-1 w-full border rounded px-3 py-2 bg-gray-100">{{ $telefono ?? '-' }}</p>
                        @else
                            <input type="text" wire:model="telefono"
                                   class="mt-1 w-full border rounded px-3 py-2" />
                            @error('telefono') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Dirección</label>
                        @if($mode == 'view')
                            <p class="mt-1 w-full border rounded px-3 py-2 bg-gray-100">{{ $direccion ?? '-' }}</p>
                        @else
                            <input type="text" wire:model="direccion"
                                   class="mt-1 w-full border rounded px-3 py-2" />
                            @error('direccion') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium">NIT</label>
                        @if($mode == 'view')
                            <p class="mt-1 w-full border rounded px-3 py-2 bg-gray-100">{{ $nit ?? '-' }}</p>
                        @else
                            <input type="text" wire:model="nit"
                                   class="mt-1 w-full border rounded px-3 py-2" />
                            @error('nit') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Contacto Nombre</label>
                        @if($mode == 'view')
                            <p class="mt-1 w-full border rounded px-3 py-2 bg-gray-100">{{ $contacto_nombre ?? '-' }}</p>
                        @else
                            <input type="text" wire:model="contacto_nombre"
                                   class="mt-1 w-full border rounded px-3 py-2" />
                            @error('contacto_nombre') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Contacto Teléfono</label>
                        @if($mode == 'view')
                            <p class="mt-1 w-full border rounded px-3 py-2 bg-gray-100">{{ $contacto_telefono ?? '-' }}</p>
                        @else
                            <input type="text" wire:model="contacto_telefono"
                                   class="mt-1 w-full border rounded px-3 py-2" />
                            @error('contacto_telefono') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Estado</label>
                        @if($mode == 'view')
                            <p class="mt-1 w-full border rounded px-3 py-2 bg-gray-100">{{ ucfirst($estado) }}</p>
                        @else
                            <select wire:model="estado" class="mt-1 w-full border rounded px-3 py-2">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                            @error('estado') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
                        @endif
                    </div>

                    <div class="flex justify-end gap-3">
                        <button wire:click="cerrarModal"
                                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                            Cerrar
                        </button>

                        @if($mode != 'view')
                            <button wire:click="guardar"
                                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Guardar
                            </button>
                        @endif
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
            title: "¿Eliminar proveedor?",
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