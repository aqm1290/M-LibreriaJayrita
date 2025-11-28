<div>
    <button wire:click="intentarCerrarSesion"
        class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-red-600 hover:bg-red-50 font-semibold transition text-left">
        <i data-lucide="log-out" class="w-5 h-5"></i>
        Cerrar Sesión
    </button>

    <script>
        document.addEventListener('livewire:load', () => {
            window.addEventListener('alerta-caja-abierta', () => {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Caja abierta!',
                    text: 'No puedes cerrar sesión porque tienes la caja abierta. Debes hacer el cierre diario primero.',
                    confirmButtonText: 'Ir al Cierre de Caja',
                    cancelButtonText: 'Cancelar',
                    showCancelButton: true,
                    allowOutsideClick: false,
                    customClass: {
                        confirmButton: 'px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-xl shadow-lg',
                        cancelButton: 'px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded-xl'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if(result.isConfirmed){
                        window.location.href = '{{ route("caja.cierre") }}';
                    }
                });
            });
        });
    </script>
</div>
