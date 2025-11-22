<div class="p-6">

    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Marcas</h2>

        <button wire:click="openModal"
            class="flex items-center gap-2 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <x-heroicon-o-plus class="w-5 h-5"/>
            Nueva Marca
        </button>
    </div>

    <table class="w-full text-left bg-white shadow rounded-lg overflow-hidden">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3">ID</th>
                <th class="p-3">Nombre</th>
                <th class="p-3">Descripción</th>
                <th class="p-3">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($marcas as $m)
            <tr class="border-b">
                <td class="p-3">{{ $m->id }}</td>
                <td class="p-3">{{ $m->nombre }}</td>
                <td class="p-3">{{ $m->descripcion }}</td>
                <td class="p-3 flex gap-2">
                    <button wire:click="edit({{ $m->id }})"
                        class="text-blue-600 hover:text-blue-800">
                        <x-heroicon-o-pencil class="w-5 h-5"/>
                    </button>

                    <button wire:click="confirmDelete({{ $m->id }})"
                        class="text-red-600 hover:text-red-800">
                        <x-heroicon-o-trash class="w-5 h-5"/>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Modal --}}
    @if($modal)
    <div class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">
        <div class="bg-white w-full max-w-lg p-6 rounded-lg shadow">
            <h3 class="text-lg font-semibold mb-4">
                {{ $marca_id ? 'Editar Marca' : 'Nueva Marca' }}
            </h3>

            <input type="text" wire:model="nombre"
                placeholder="Nombre"
                class="w-full p-2 border rounded mb-3"/>

            <textarea wire:model="descripcion"
                placeholder="Descripción"
                class="w-full p-2 border rounded mb-3"></textarea>

            <div class="flex justify-end gap-2">
                <button wire:click="closeModal"
                    class="px-4 py-2 border rounded">Cancelar</button>

                <button wire:click="store"
                    class="px-4 py-2 bg-blue-600 text-white rounded">
                    Guardar
                </button>
            </div>
        </div>
    </div>
    @endif

</div>


<!-- SWEETALERT2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('livewire:initialized', () => {

    // 🔥 TOASTS MODERNOS
    Livewire.on('toast', (data) => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: data.icon ?? 'success',
            title: data.title ?? '',
            showConfirmButton: false,
            timer: 1800,
            timerProgressBar: true,
        });
    });

    // ❗ CONFIRMACIÓN DE ELIMINACIÓN
    Livewire.on('confirmDelete', () => {
        Swal.fire({
            title: '¿Eliminar marca?',
            text: "Esta acción no se puede deshacer.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteConfirmed');
            }
        });
    });

});
</script>
