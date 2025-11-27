<div>
    <!-- Botón visible en el sidebar -->
    <button wire:click="intentarCerrarSesion"
            class="w-full flex items-center gap-3 px-4 py-2.5 rounded-lg text-red-600 hover:bg-red-50 font-semibold transition text-left">
        <i data-lucide="log-out" class="w-5 h-5"></i>
        Cerrar Sesión
    </button>

    <!-- Script del SweetAlert cuando intenta cerrar con caja abierta -->
    <script>
        document.addEventListener('livewire:initialized', () => {
            @this.on('mostrar-alerta-caja-abierta', () => {
                Swal.fire({
                    icon: 'warning',
                    title: '¡Caja Abierta!',
                    html: `
                        <div class="text-center">
                            <p class="text-lg mb-4">No puedes cerrar sesión porque <strong>tienes la caja abierta</strong>.</p>
                            <p class="text-gray-600">Debes hacer el <strong>cierre diario</strong> antes de salir.</p>
                        </div>
                    `,
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
                    if (result.isConfirmed) {
                        window.location.href = '{{ route('caja.cierre') }}';
                    }
                });
            });
        });
    </script>
</div>