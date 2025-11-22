<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>{{ $title ?? 'Panel' }}</title>

    <!-- ICONOS HEROICONS -->
    <script src="https://unpkg.com/feather-icons"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="min-h-screen bg-gray-100 text-gray-900 antialiased flex">

    <!-- SIDEBAR -->
    <aside id="sidebar" 
           class="fixed left-0 top-0 h-full w-64 bg-gray-900 text-gray-100 transform transition-all duration-300 shadow-xl z-30">

        <!-- TOP -->
        <div class="px-6 py-6 border-b border-gray-800 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="text-3xl">📚</span>
                <span class="text-xl font-bold tracking-wide">Librería</span>
            </div>

            <button id="btn-collapse" 
                aria-label="Colapsar sidebar"
                class="text-gray-400 hover:text-white transition">
                <i data-feather="chevron-left"></i>
            </button>
        </div>

        <!-- NAV -->
        <nav class="px-3 py-5 space-y-2">

            <a href="/" class="nav-link">
                <i data-feather="home"></i>
                <span>Dashboard</span>
            </a>

            <a href="/categorias" class="nav-link">
                <i data-feather="folder"></i>
                <span>Categorías</span>
            </a>

            <a href="/marcas" class="nav-link">
                <i data-feather="tag"></i>
                <span>Marcas</span>
            </a>

            <a href="/modelos" class="nav-link">
                <i data-feather="archive"></i>
                <span>Modelos</span>
            </a>

            <a href="/productos" class="nav-link">
                <i data-feather="package"></i>
                <span>Productos</span>
            </a>

            <a href="/caja/pos" class="nav-link">
                <i data-feather="shopping-cart"></i>
                <span>Ventas</span>
            </a>

        </nav>

        <!-- USER -->
        <div class="mt-auto px-4 py-5 border-t border-gray-800">
            <div class="text-xs uppercase text-gray-500">Usuario</div>

            <a href="#" class="flex items-center gap-2 mt-3 px-2 py-2 rounded hover:bg-gray-800 transition">
                <i data-feather="log-out"></i>
                <span>Cerrar sesión</span>
            </a>
        </div>

    </aside>

    <!-- MAIN -->
    <div id="main" class="flex-1 ml-64 transition-all duration-300">

        <!-- TOPBAR -->
        <header class="bg-white shadow px-6 py-4 sticky top-0 z-10">
            <div class="max-w-7xl mx-auto flex items-center justify-between">

                <div class="flex items-center gap-4">
                    <button id="btn-toggle" class="text-gray-700 md:hidden">
                        <i data-feather="menu"></i>
                    </button>
                    <h1 class="text-xl font-semibold">
                        {{ $title ?? 'Panel de Administración' }}
                    </h1>
                </div>
                <div class="flex items-center gap-3 text-gray-600">
                    Bienvenido 👋
                </div>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="p-6 max-w-7xl mx-auto">
            <div class="bg-white shadow rounded-lg p-6 border border-gray-100">
                {{ $slot ?? '' }}
                @yield('content')
            </div>
        </main>
    </div>

    @livewireScripts

    <!-- SIDEBAR JS -->
    <script>
        (function() {
            feather.replace(); // Cargar iconos

            const sidebar = document.getElementById('sidebar');
            const main = document.getElementById('main');
            const btnCollapse = document.getElementById('btn-collapse');
            const btnToggle = document.getElementById('btn-toggle');

            let collapsed = false;

            btnCollapse?.addEventListener('click', () => {
                collapsed = !collapsed;

                if (collapsed) {
                    sidebar.style.width = '80px';
                    main.style.marginLeft = '80px';

                    sidebar.querySelectorAll('nav a span').forEach(s => s.classList.add('hidden'));
                } else {
                    sidebar.style.width = '16rem';
                    main.style.marginLeft = '16rem';

                    sidebar.querySelectorAll('nav a span').forEach(s => s.classList.remove('hidden'));
                }
            });

            btnToggle?.addEventListener('click', () => {
                sidebar.classList.toggle('-translate-x-full');
            });

        })();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')

    <style>
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 15px;
            transition: 0.2s;
            color: #d1d5db;
        }
        .nav-link:hover {
            background-color: #1f2937;
            color: #fff;
        }
        .nav-link i {
            width: 20px;
        }
    </style>

</body>
</html>
