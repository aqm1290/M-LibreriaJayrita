<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Librería Jayra</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 min-h-screen">

    <div class="container mx-auto px-4 py-8">
        {{ $slot }}
    </div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @livewireScripts
    
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('toast', (message) => {
                Swal.fire('¡Éxito!', message, 'success');
            });
            Livewire.on('open-pdf', (event) => {
                window.open(event.url, '_blank');
            });
        });
    </script>
</body>
</html>