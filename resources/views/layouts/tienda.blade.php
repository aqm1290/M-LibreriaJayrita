<!DOCTYPE html>
<html lang="es" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'MiTienda Online')</title>

    <!-- Tailwind + CSS propio -->
    @vite(['resources/css/app.css', 'resources/css/shop.css', 'resources/js/shop.js'])
</head>
<body class="h-full font-sans antialiased">

    <!-- HEADER -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

            <!-- LOGO -->
            <a href="#" class="text-3xl font-extrabold text-blue-600 hover:text-blue-800 transition">
                MiTienda
            </a>

            <!-- BUSCADOR -->
            <div class="hidden md:block flex-1 mx-6">
                <input type="text" placeholder="Buscar productos..."
                       class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>

            <!-- MENU DERECHO -->
            <div class="flex items-center space-x-6">
                <a href="#" class="font-medium hover:text-blue-600 transition">🛒 Carrito (<span id="cart-count">0</span>)</a>
                <a href="#" class="font-medium hover:text-blue-600 transition">Iniciar Sesión</a>
            </div>
        </div>
    </header>

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="max-w-7xl mx-auto px-6 py-8 grid grid-cols-1 md:grid-cols-4 gap-6">

        <!-- SIDEBAR -->
        <aside class="md:col-span-1 bg-white p-5 rounded-xl shadow-lg sticky top-24">
            <h2 class="text-xl font-bold mb-4 border-b pb-2">Categorías</h2>
            <ul class="space-y-2">
                <li><a href="#" class="block p-2 rounded hover:bg-blue-100 text-blue-700 font-medium transition">Categoría 1</a></li>
                <li><a href="#" class="block p-2 rounded hover:bg-blue-100 text-blue-700 font-medium transition">Categoría 2</a></li>
                <li><a href="#" class="block p-2 rounded hover:bg-blue-100 text-blue-700 font-medium transition">Categoría 3</a></li>
            </ul>

            <h3 class="text-lg font-bold mt-6 mb-3 border-b pb-2">Filtros</h3>
            <label class="block">
                <span class="text-sm font-medium">Precio máximo</span>
                <input type="range" min="0" max="2000" class="w-full mt-2">
            </label>
        </aside>

        <!-- MAIN CONTENT -->
        <main class="md:col-span-3 bg-white p-6 rounded-xl shadow-lg">
            @yield('content')
        </main>
    </div>

    <!-- FOOTER -->
    <footer class="bg-white border-t py-6 mt-12 shadow-inner">
        <div class="max-w-7xl mx-auto text-center text-gray-600">
            &copy; {{ date('Y') }} MiTienda — Todos los derechos reservados
        </div>
    </footer>

    <!-- JS Dinámico -->
    <script>
        // Contador de carrito demo
        let cartCount = 0;
        function agregarCarrito() {
            cartCount++;
            document.getElementById('cart-count').innerText = cartCount;
        }
    </script>

</body>
</html>
