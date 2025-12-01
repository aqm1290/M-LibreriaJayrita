<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería Jayrita </title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>
<body class="h-full bg-gray-50 font-sans antialiased text-sm">
<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside
        id="sidebar"
        class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-2xl border-r border-gray-200
               transform -translate-x-full transition-transform duration-300 ease-in-out
               lg:translate-x-0 lg:static lg:z-auto"
    >
        <div class="flex h-full flex-col">

            <!-- Logo -->
            <div class="flex items-center justify-between px-6 py-6 bg-gradient-to-r from-yellow-400 to-orange-500">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-white shadow-xl flex items-center justify-center overflow-hidden">
                        <img
                            src="{{ asset('images/logo.jpg') }}"
                            alt="Librería Jayrita"
                            class="w-full h-full object-contain"
                        >
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-gray-900 leading-tight">LIBRERIA</h1>
                        <p class="text-sm font-black tracking-wide text-gray-900 -mt-1">JAYRITA</p>
                        <p class="text-[0.65rem] font-bold text-gray-800/90 uppercase tracking-[0.2em]">
                            Papelería & más
                        </p>
                    </div>
                </div>
                <button onclick="toggleSidebar()" class="lg:hidden">
                    <i data-lucide="x" class="w-6 h-6 text-gray-900"></i>
                </button>
            </div>


            <!-- MENÚ -->
            <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">

                <!-- Dashboard -->
                <a
                    href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-semibold transition-all
                           {{ request()->routeIs('dashboard') ? 'bg-yellow-300 text-gray-900 shadow-md' : 'text-gray-700 hover:bg-yellow-100' }}"
                >
                    <i data-lucide="home" class="w-5 h-5"></i>
                    Panel de Control
                </a>

                <!-- Caja y Ventas -->
                <div x-data="{ open: {{ request()->routeIs('caja.*') ? 'true' : 'false' }} }">
                    <button
                        @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg font-semibold transition-all
                               {{ request()->routeIs('caja.*') ? 'bg-yellow-200 text-gray-900' : 'text-gray-700 hover:bg-yellow-100' }}"
                    >
                        <div class="flex items-center gap-3">
                            <i data-lucide="wallet" class="w-5 h-5"></i>
                            Caja & Ventas
                        </div>
                        <i
                            data-lucide="chevron-down"
                            class="w-5 h-5 transition-transform"
                            :class="open ? 'rotate-180' : ''"
                        ></i>
                    </button>

                    <div x-show="open" x-transition class="ml-10 mt-2 space-y-1">
                        <a
                            href="{{ route('caja.pos') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                                   {{ request()->routeIs('caja.pos') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}"
                        >
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                            Punto de Venta
                        </a>
                        <a
                            href="{{ route('caja.apertura') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                                   {{ request()->routeIs('caja.apertura') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}"
                        >
                            <i data-lucide="door-open" class="w-4 h-4"></i>
                            Apertura de Caja
                        </a>
                        <a
                            href="{{ route('caja.cierre') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                                   {{ request()->routeIs('caja.cierre') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}"
                        >
                            <i data-lucide="door-closed" class="w-4 h-4"></i>
                            Cierre Diario
                        </a>
                        <a
                            href="{{ route('caja.buscar') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                                   {{ request()->routeIs('caja.buscar') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}"
                        >
                            <i data-lucide="search" class="w-4 h-4"></i>
                            Buscar producto
                        </a>
                    </div>
                </div>

                <!-- Inventario -->
                <div x-data="{ open: {{ request()->routeIs(['entrada-inventario','entradas.*']) ? 'true' : 'false' }} }">
                    <button
                        @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg font-semibold transition-all
                               {{ request()->routeIs(['entrada-inventario','entradas.*']) ? 'bg-yellow-200 text-gray-900' : 'text-gray-700 hover:bg-yellow-100' }}"
                    >
                        <div class="flex items-center gap-3">
                            <i data-lucide="boxes" class="w-5 h-5"></i>
                            Inventario
                        </div>
                        <i
                            data-lucide="chevron-down"
                            class="w-5 h-5 transition-transform"
                            :class="open ? 'rotate-180' : ''"
                        ></i>
                    </button>

                    <div x-show="open" x-transition class="ml-10 mt-2 space-y-1">
                        <a
                            href="{{ route('entrada-inventario') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                                   {{ request()->routeIs('entrada-inventario') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}"
                        >
                            <i data-lucide="file-plus" class="w-4 h-4"></i>
                            Nueva Entrada
                        </a>
                        <a
                            href="{{ route('entradas.index') }}"
                            class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                                   {{ request()->routeIs('entradas.index') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}"
                        >
                            <i data-lucide="history" class="w-4 h-4"></i>
                            Historial
                        </a>
                    </div>
                </div>

                <!-- Catálogos -->
                @if(Auth::user()->esAdmin())
                    <div
                        x-data="{
                            open: {{ request()->routeIs(['productos*','categorias*','marcas*','modelos*','proveedores*','admin.promociones*','admin.crear-personal*']) ? 'true' : 'false' }}
                        }"
                    >
                        <button
                            @click="open = !open"
                            class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg font-semibold transition-all
                                   {{ request()->routeIs(['productos*','categorias*','marcas*','modelos*','proveedores*','admin.promociones*','admin.crear-personal*']) ? 'bg-yellow-200 text-gray-900' : 'text-gray-700 hover:bg-yellow-100' }}"
                        >
                            <div class="flex items-center gap-3">
                                <i data-lucide="folder-tree" class="w-5 h-5"></i>
                                Catálogos
                            </div>
                            <i
                                data-lucide="chevron-down"
                                class="w-5 h-5 transition-transform"
                                :class="open ? 'rotate-180' : ''"
                            ></i>
                        </button>

                        <div x-show="open" x-transition class="ml-10 mt-2 space-y-1">
                            <a
                                href="{{ route('productos') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                                       {{ request()->routeIs('productos*') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}"
                            >
                                <i data-lucide="package" class="w-4 h-4"></i>
                                Productos
                            </a>
                            <a
                                href="{{ route('categorias') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                                       {{ request()->routeIs('categorias*') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}"
                            >
                                <i data-lucide="layers" class="w-4 h-4"></i>
                                Categorías
                            </a>
                            <a
                                href="{{ route('marcas') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                                       {{ request()->routeIs('marcas*') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}"
                            >
                                <i data-lucide="tag" class="w-4 h-4"></i>
                                Marcas
                            </a>
                            <a
                                href="{{ route('modelos') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                                       {{ request()->routeIs('modelos*') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}"
                            >
                                <i data-lucide="file-text" class="w-4 h-4"></i>
                                Modelos
                            </a>
                            <a
                                href="{{ route('proveedores') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                                       {{ request()->routeIs('proveedores*') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}"
                            >
                                <i data-lucide="truck" class="w-4 h-4"></i>
                                Proveedores
                            </a>
                            <a
                                href="{{ route('admin.promociones') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                                       {{ request()->routeIs('admin.promociones*') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}"
                            >
                                <i data-lucide="percent" class="w-4 h-4"></i>
                                <span class="font-bold">Promociones</span>
                            </a>
                            <a
                                href="{{ route('admin.crear-personal') }}"
                                class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm
                                       {{ request()->routeIs('admin.crear-personal*') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}"
                            >
                                <i data-lucide="user-plus" class="w-4 h-4"></i>
                                Crear Personal
                            </a>
                            <a
                                href="{{ route('historial.pdfs') }}"
                                class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-semibold transition-all
                                    {{ request()->routeIs('historial.pdfs') ? 'bg-yellow-300 text-gray-900 shadow-md' : 'text-gray-700 hover:bg-yellow-100' }}"
                            >
                                <i data-lucide="file-text" class="w-5 h-5"></i>
                                PDFs de tickets y cierres
                            </a>
                        </div>
                    </div>
                @endif

                @livewire('logout-con-proteccion')

            </nav>

            <!-- Usuario -->
            <div class="p-6 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-400 to-orange-500
                               flex items-center justify-center text-lg font-black text-white shadow-xl"
                    >
                        {{ Auth::user()->name ? strtoupper(substr(Auth::user()->name, 0, 2)) : 'AD' }}
                    </div>
                    <div>
                        <p class="font-black text-gray-900 text-base">{{ Auth::user()->name ?? 'Admin' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Overlay móvil -->
    <div
        id="sidebar-overlay"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden lg:hidden"
    ></div>
 
    <!-- CONTENIDO PRINCIPAL -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-lg border-b border-gray-200 z-40">
            <div class="px-6 py-4 flex items-center justify-between">

                <div class="flex items-center gap-4">
                    <button
                        id="toggle-sidebar"
                        class="p-3 rounded-xl bg-yellow-100 hover:bg-yellow-200 transition lg:hidden"
                    >
                        <i data-lucide="menu" class="w-6 h-6 text-orange-600"></i>
                    </button>

                    <!-- Título dinámico -->
                    <h1
                        class="text-3xl md:text-4xl lg:text-4xl font-black text-transparent bg-clip-text
                               bg-gradient-to-r from-yellow-500 to-orange-600 opacity-0 translate-y-6 animate-slideUp"
                    >
                        @php
                            $titles = [
                                'dashboard'             => 'Panel de Control',
                                'caja.pos'              => 'Punto de Venta',
                                'caja.apertura'         => 'Apertura de Caja',
                                'caja.cierre'           => 'Cierre de Caja',
                                'caja.buscar'           => 'Buscar Producto',
                                'entrada-inventario'    => 'Nueva Entrada',
                                'entradas.index'        => 'Historial de Entradas',
                                'productos'             => 'Productos',
                                'categorias'            => 'Categorías',
                                'marcas'                => 'Marcas',
                                'modelos'               => 'Modelos',
                                'proveedores'           => 'Proveedores',
                                'admin.promociones'     => 'Promociones',
                                'admin.crear-personal'  => 'Crear Personal',
                                'historial.pdfs'         => 'PDFs de Tickets y Cierres',
                            ];

                            $currentTitle = 'Dashboard';
                            foreach ($titles as $route => $title) {
                                if (request()->routeIs($route.'*') || request()->routeIs($route)) {
                                    $currentTitle = $title;
                                    break;
                                }
                            }
                        @endphp

                        {{ $currentTitle }}
                    </h1>
                </div>
                {{-- Estado de caja en el header usando TurnoCaja --}}
                @php
                    $turnoActivo = \App\Models\TurnoCaja::where('usuario_id', auth()->id())
                        ->whereDate('fecha', today())
                        ->where('activo', true)
                        ->first();
                    $cajaAbierta = (bool) $turnoActivo;
                @endphp

                <div>
                    @if(!$cajaAbierta && !request()->routeIs('caja.apertura'))
                        {{-- Caja cerrada → BOTÓN VERDE para abrir --}}
                        <a
                            href="{{ route('caja.apertura') }}"
                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white
                                font-black text-sm rounded-xl shadow-xl hover:scale-105 transition flex items-center gap-2"
                        >
                            <i data-lucide="unlock" class="w-5 h-5"></i>
                            ABRIR CAJA HOY
                        </a>

                    @elseif($cajaAbierta && !request()->routeIs('caja.cierre'))
                        {{-- Caja abierta → BOTÓN ROJO para cerrar --}}
                        <a
                            href="{{ route('caja.cierre') }}"
                            class="px-6 py-3 bg-gradient-to-r from-red-600 to-rose-700 text-white
                                font-black text-sm rounded-xl shadow-xl hover:scale-105 transition flex items-center gap-2"
                        >
                            <i data-lucide="lock" class="w-5 h-5"></i>
                            CERRAR CAJA
                        </a>

                    @else
                        {{-- Ya estás en apertura o cierre --}}
                        <div
                            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white
                                font-black text-sm rounded-xl shadow-xl flex items-center gap-2"
                        >
                            <i data-lucide="check-circle" class="w-5 h-5"></i>
                            CAJA ABIERTA
                        </div>
                    @endif
                </div>







            </div>
        </header>

        <main class="flex-1 overflow-y-auto bg-gray-50 p-6">
            <div class="max-w-7xl mx-auto">
                {{ $slot }}
            </div>
        </main>
    </div>
</div>

<!-- Animación del título -->
<style>
    @keyframes slideUp {
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slideUp { animation: slideUp 0.8s ease-out forwards; }
</style>

@livewireScripts
@stack('scripts')

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

<script>
    // Inicializar iconos Lucide cargados por CDN
    lucide.createIcons();

    // Sidebar móvil
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');

        if (!sidebar || !overlay) return;

        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
    }

    document.getElementById('toggle-sidebar')?.addEventListener('click', toggleSidebar);
    document.getElementById('sidebar-overlay')?.addEventListener('click', toggleSidebar);
</script>

</body>
</html>
