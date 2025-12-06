<div>
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-black text-slate-900">Gestión de Proveedores</h2>
            <p class="text-sm text-orange-700/90">Proveedores registrados.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar proveedor o empresa..."
                class="border border-yellow-300 rounded-2xl px-4 py-2.5 w-full md:w-64 bg-white/80 text-sm
                       focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-500" />

            <select wire:model.live="filtroEstado"
                class="border border-yellow-300 rounded-2xl px-4 py-2.5 bg-white/80 text-sm
                       focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-500">
                <option value="">Todos</option>
                <option value="activo">Activo</option>
                <option value="inactivo">Inactivo</option>
            </select>

            <button wire:click="crear"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl text-sm font-semibold
                       bg-gradient-to-r from-yellow-400 via-yellow-500 to-orange-500 text-white
                       shadow-[0_10px_30px_rgba(249,115,22,0.6)]
                       hover:from-yellow-500 hover:via-yellow-600 hover:to-orange-600
                       transform hover:-translate-y-0.5 transition">
                + Nuevo proveedor
            </button>
        </div>
    </div>

    <!-- Tabla -->
    <div class="overflow-hidden rounded-2xl border border-yellow-200 bg-white shadow-sm">
        <table class="min-w-full divide-y divide-yellow-50 text-sm">
            <thead class="bg-gradient-to-r from-yellow-400 via-yellow-500 to-orange-500 text-white">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">Nombre</th>
                    <th class="px-6 py-3 text-left font-semibold">Empresa</th>
                    <th class="px-6 py-3 text-left font-semibold">Correo</th>
                    <th class="px-6 py-3 text-left font-semibold">Teléfono</th>
                    <th class="px-6 py-3 text-left font-semibold">Estado</th>
                    <th class="px-6 py-3 text-left font-semibold">Acciones</th>
                </tr>
            </thead>

            <tbody class="bg-white divide-y divide-yellow-50">
                @forelse($proveedores as $p)
                    <tr class="hover:bg-yellow-50/80 transition-colors">
                        <td class="px-6 py-4 text-sm text-slate-800">{{ $p->nombre }}</td>
                        <td class="px-6 py-4 text-sm text-slate-800">{{ $p->empresa ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $p->correo }}</td>
                        <td class="px-6 py-4 text-sm text-slate-700">{{ $p->telefono ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <span
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold
                                {{ $p->estado === 'activo'
                                    ? 'bg-emerald-100 text-emerald-800 border border-emerald-200'
                                    : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                {{ ucfirst($p->estado) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex flex-wrap items-center gap-2">
                                <button wire:click="ver({{ $p->id }})"
                                    class="px-3 py-1.5 rounded-xl text-xs font-semibold
                                           bg-yellow-50 text-yellow-800 border border-yellow-200
                                           hover:bg-yellow-100 transition">
                                    Ver
                                </button>
                                <button wire:click="editar({{ $p->id }})"
                                    class="px-3 py-1.5 rounded-xl text-xs font-semibold
                                           bg-yellow-400 text-white hover:bg-yellow-500 transition shadow-sm">
                                    Editar
                                </button>
                                <button wire:click="confirmarEliminar({{ $p->id }})"
                                    class="px-3 py-1.5 rounded-xl text-xs font-semibold
                                           bg-rose-600 text-white hover:bg-rose-700 transition shadow-sm">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-slate-500 text-sm">
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
    @if ($modal)
        <div class="fixed inset-0 z-40 flex items-center justify-center bg-black/60 backdrop-blur-sm">
            <div
                class="bg-white rounded-2xl shadow-2xl w-full max-w-md sm:max-w-lg md:max-w-xl p-6
                       border border-yellow-200 max-h-[90vh] overflow-y-auto">

                <h3 class="text-xl font-black text-slate-900 mb-4">
                    @if ($mode == 'view')
                        Detalles del proveedor
                    @else
                        {{ $proveedor_id ? 'Editar proveedor' : 'Nuevo proveedor' }}
                    @endif
                </h3>

                <div class="space-y-4 text-sm">
                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1 uppercase tracking-wide">
                            Nombre
                        </label>
                        @if ($mode == 'view')
                            <p
                                class="mt-1 w-full border border-yellow-200 rounded-xl px-3 py-2 bg-yellow-50/80 text-slate-800">
                                {{ $nombre }}
                            </p>
                        @else
                            <input type="text" wire:model="nombre"
                                class="mt-1 w-full border border-yellow-300 rounded-xl px-3 py-2 bg-white/90
                                       focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-500" />
                            @error('nombre')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1 uppercase tracking-wide">
                            Empresa
                        </label>
                        @if ($mode == 'view')
                            <p
                                class="mt-1 w-full border border-yellow-200 rounded-xl px-3 py-2 bg-yellow-50/80 text-slate-800">
                                {{ $empresa ?? '-' }}
                            </p>
                        @else
                            <input type="text" wire:model="empresa"
                                class="mt-1 w-full border border-yellow-300 rounded-xl px-3 py-2 bg-white/90
                                       focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-500" />
                            @error('empresa')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1 uppercase tracking-wide">
                            Correo
                        </label>
                        @if ($mode == 'view')
                            <p
                                class="mt-1 w-full border border-yellow-200 rounded-xl px-3 py-2 bg-yellow-50/80 text-slate-800">
                                {{ $correo }}
                            </p>
                        @else
                            <input type="email" wire:model="correo"
                                class="mt-1 w-full border border-yellow-300 rounded-xl px-3 py-2 bg-white/90
                                       focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-500" />
                            @error('correo')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1 uppercase tracking-wide">
                            Teléfono
                        </label>
                        @if ($mode == 'view')
                            <p
                                class="mt-1 w-full border border-yellow-200 rounded-xl px-3 py-2 bg-yellow-50/80 text-slate-800">
                                {{ $telefono ?? '-' }}
                            </p>
                        @else
                            <input type="text" wire:model="telefono"
                                class="mt-1 w-full border border-yellow-300 rounded-xl px-3 py-2 bg-white/90
                                       focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-500" />
                            @error('telefono')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1 uppercase tracking-wide">
                            Dirección
                        </label>
                        @if ($mode == 'view')
                            <p
                                class="mt-1 w-full border border-yellow-200 rounded-xl px-3 py-2 bg-yellow-50/80 text-slate-800">
                                {{ $direccion ?? '-' }}
                            </p>
                        @else
                            <input type="text" wire:model="direccion"
                                class="mt-1 w-full border border-yellow-300 rounded-xl px-3 py-2 bg-white/90
                                       focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-500" />
                            @error('direccion')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1 uppercase tracking-wide">
                            NIT
                        </label>
                        @if ($mode == 'view')
                            <p
                                class="mt-1 w-full border border-yellow-200 rounded-xl px-3 py-2 bg-yellow-50/80 text-slate-800">
                                {{ $nit ?? '-' }}
                            </p>
                        @else
                            <input type="text" wire:model="nit"
                                class="mt-1 w-full border border-yellow-300 rounded-xl px-3 py-2 bg-white/90
                                       focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-500" />
                            @error('nit')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1 uppercase tracking-wide">
                            Contacto nombre
                        </label>
                        @if ($mode == 'view')
                            <p
                                class="mt-1 w-full border border-yellow-200 rounded-xl px-3 py-2 bg-yellow-50/80 text-slate-800">
                                {{ $contacto_nombre ?? '-' }}
                            </p>
                        @else
                            <input type="text" wire:model="contacto_nombre"
                                class="mt-1 w-full border border-yellow-300 rounded-xl px-3 py-2 bg-white/90
                                       focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-500" />
                            @error('contacto_nombre')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1 uppercase tracking-wide">
                            Contacto teléfono
                        </label>
                        @if ($mode == 'view')
                            <p
                                class="mt-1 w-full border border-yellow-200 rounded-xl px-3 py-2 bg-yellow-50/80 text-slate-800">
                                {{ $contacto_telefono ?? '-' }}
                            </p>
                        @else
                            <input type="text" wire:model="contacto_telefono"
                                class="mt-1 w-full border border-yellow-300 rounded-xl px-3 py-2 bg-white/90
                                       focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-500" />
                            @error('contacto_telefono')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1 uppercase tracking-wide">
                            Estado
                        </label>
                        @if ($mode == 'view')
                            <p
                                class="mt-1 w-full border border-yellow-200 rounded-xl px-3 py-2 bg-yellow-50/80 text-slate-800">
                                {{ ucfirst($estado) }}
                            </p>
                        @else
                            <select wire:model="estado"
                                class="mt-1 w-full border border-yellow-300 rounded-xl px-3 py-2 bg-white/90
                                       focus:outline-none focus:ring-2 focus:ring-yellow-200 focus:border-yellow-500">
                                <option value="activo">Activo</option>
                                <option value="inactivo">Inactivo</option>
                            </select>
                            @error('estado')
                                <p class="text-rose-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @endif
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button wire:click="cerrarModal"
                            class="px-4 py-2 rounded-xl border border-yellow-300 bg-white text-slate-700 text-sm font-medium
                                   hover:bg-yellow-50 hover:border-yellow-400 transition">
                            Cerrar
                        </button>

                        @if ($mode != 'view')
                            <button wire:click="guardar"
                                class="px-6 py-2 rounded-xl bg-gradient-to-r from-yellow-500 via-orange-500 to-orange-600
                                       text-white text-sm font-semibold
                                       hover:from-yellow-600 hover:via-orange-600 hover:to-orange-700
                                       shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition">
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
