<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería Jayrita - @yield('title', 'Dashboard')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-gray-50 font-sans antialiased text-sm">

<div class="flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-2xl border-r border-gray-200 transform -translate-x-full transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:z-auto">

        <div class="flex h-full flex-col">

            <!-- Logo Jayrita -->
            <div class="flex items-center justify-between px-6 py-6 bg-gradient-to-r from-yellow-400 to-orange-500">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-white rounded-xl shadow-xl flex items-center justify-center font-black text-2xl text-orange-600">
                        LJ
                    </div>
                    <div>
                        <h1 class="text-2xl font-black text-gray-900">JAYRITA</h1>
                        <p class="text-xs font-bold text-gray-800">Librería & Papelería</p>
                    </div>
                </div>
                <button onclick="toggleSidebar()" class="lg:hidden">
                    <i data-lucide="x" class="w-6 h-6 text-gray-900"></i>
                </button>
            </div>

            <!-- MENÚ -->
            <nav class="flex-1 px-4 py-4 space-y-2 overflow-y-auto">

                <!-- Dashboard -->
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg font-semibold transition-all {{ request()->routeIs('dashboard') ? 'bg-yellow-300 text-gray-900 shadow-md' : 'text-gray-700 hover:bg-yellow-100' }}">
                    <i data-lucide="home" class="w-5 h-5"></i>
                    Dashboard
                </a>

                <!-- Caja y Ventas -->
                <div x-data="{ open: {{ request()->routeIs('caja.*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg font-semibold transition-all {{ request()->routeIs('caja.*') ? 'bg-yellow-200 text-gray-900' : 'text-gray-700 hover:bg-yellow-100' }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="wallet" class="w-5 h-5"></i>
                            Caja & Ventas
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-transition class="ml-10 mt-2 space-y-1">
                        <a href="{{ route('caja.pos') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('caja.pos') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}">
                            <i data-lucide="shopping-cart" class="w-4 h-4"></i> Punto de Venta
                        </a>
                        <a href="{{ route('caja.apertura') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('caja.apertura') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}">
                            <i data-lucide="door-open" class="w-4 h-4"></i> Apertura de Caja
                        </a>
                        <a href="{{ route('caja.cierre') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('caja.cierre') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}">
                            <i data-lucide="door-closed" class="w-4 h-4"></i> Cierre Diario
                        </a>
                        <a href="{{ route('caja.buscar') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('caja.buscar') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}">
                            <i data-lucide="search" class="w-4 h-4"></i> Buscar producto 
                        </a>
                    </div>
                </div>

                <!-- Inventario -->
                <div x-data="{ open: {{ request()->is('entrada*') || request()->is('entradas*') ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg font-semibold transition-all {{ request()->is('entrada*') || request()->is('entradas*') ? 'bg-yellow-200 text-gray-900' : 'text-gray-700 hover:bg-yellow-100' }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="boxes" class="w-5 h-5"></i>
                            Inventario
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-transition class="ml-10 mt-2 space-y-1">
                        <a href="{{ route('entrada-inventario') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('entrada-inventario') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}">
                            <i data-lucide="file-plus" class="w-4 h-4"></i> Nueva Entrada
                        </a>
                        <a href="{{ route('entradas.index') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('entradas.index') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}">
                            <i data-lucide="history" class="w-4 h-4"></i> Historial
                        </a>
                    </div>
                </div>

                <!-- Catálogos -->
                <div x-data="{ open: {{ request()->routeIs(['productos*','categorias*','marcas*','modelos*','proveedores*']) ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                        class="w-full flex items-center justify-between px-4 py-2.5 rounded-lg font-semibold transition-all {{ request()->routeIs(['productos*','categorias*','marcas*','modelos*','proveedores*']) ? 'bg-yellow-200 text-gray-900' : 'text-gray-700 hover:bg-yellow-100' }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="folder-tree" class="w-5 h-5"></i>
                            Catálogos
                        </div>
                        <i data-lucide="chevron-down" class="w-5 h-5 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <div x-show="open" x-transition class="ml-10 mt-2 space-y-1">
                        <a href="{{ route('productos') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('productos*') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}">
                            <i data-lucide="package" class="w-4 h-4"></i> Productos
                        </a>
                        <a href="{{ route('categorias') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('categorias*') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}">
                            <i data-lucide="layers" class="w-4 h-4"></i> Categorías
                        </a>
                        <a href="{{ route('marcas') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('marcas*') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}">
                            <i data-lucide="tag" class="w-4 h-4"></i> Marcas
                        </a>
                        <a href="{{ route('modelos') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('modelos*') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}">
                            <i data-lucide="file-text" class="w-4 h-4"></i> Modelos
                        </a>
                        <a href="{{ route('proveedores') }}" class="flex items-center gap-3 px-4 py-2 rounded-lg text-sm {{ request()->routeIs('proveedores*') ? 'bg-yellow-300 text-gray-900 font-bold' : 'text-gray-600 hover:bg-yellow-50' }}">
                            <i data-lucide="truck" class="w-4 h-4"></i> Proveedores
                        </a>
                    </div>
                </div>

            </nav>

            <!-- Usuario -->
            <div class="p-6 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center text-lg font-black text-white shadow-xl">
                        {{ Auth::user()->name ? strtoupper(substr(Auth::user()->name, 0, 2)) : 'AD' }}
                    </div>
                    <div>
                        <p class="font-black text-gray-900 text-base">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs font-medium text-gray-600">Administrador</p>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Overlay móvil -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 hidden lg:hidden"></div>

    <!-- CONTENIDO PRINCIPAL -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-lg border-b border-gray-200 z-40">
            <div class="px-6 py-4 flex items-center justify-between">

                <div class="flex items-center gap-4">
                    <!-- Botón menú móvil -->
                    <button id="toggle-sidebar" class="p-3 rounded-xl bg-yellow-100 hover:bg-yellow-200 transition lg:hidden">
                        <i data-lucide="menu" class="w-6 h-6 text-orange-600"></i>
                    </button>

                    <!-- Título dinámico con animación -->
                    <h1 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-yellow-500 to-orange-600 opacity-0 translate-y-6 animate-slideUp">
                        @php
                            $titles = [
                                'dashboard'          => 'Dashboard',
                                'caja.pos'           => 'Punto de Venta',
                                'caja.apertura'      => 'Apertura de Caja',
                                'caja.cierre'        => 'Cierre de Caja',
                                'productos'          => 'Productos',
                                'categorias'         => 'Categorías',
                                'marcas'             => 'Marcas',
                                'modelos'            => 'Modelos',
                                'proveedores'        => 'Proveedores',
                                'entrada-inventario' => 'Nueva Entrada',
                                'entradas.index'     => 'Historial de Entradas',
                            ];
                            $currentTitle = 'Dashboard';
                            foreach ($titles as $route => $title) {
                                if (request()->routeIs($route . '*') || request()->routeIs($route)) {
                                    $currentTitle = $title;
                                    break;
                                }
                            }
                        @endphp
                        {{ $currentTitle }}
                    </h1>
                </div>

                <!-- Estado de caja -->
                @php
                    $hoy = now()->format('Y-m-d');
                    $cajaAbierta = \App\Models\CierreCaja::whereDate('fecha', $hoy)->where('caja_abierta', true)->exists();
                @endphp

                <div>
                    @if(!$cajaAbierta && !request()->routeIs('caja.apertura'))
                        <a href="{{ route('caja.apertura') }}"
                           class="px-6 py-3 bg-gradient-to-r from-red-600 to-orange-600 text-white font-black text-sm rounded-xl shadow-xl hover:scale-105 transition">
                            ABRIR CAJA HOY
                        </a>
                    @else
                        <div class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-black text-sm rounded-xl shadow-xl flex items-center gap-2">
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
<script>
    window.addEventListener('redirect-after', event => {
        setTimeout(() => {
            window.location.href = event.detail.url;
        }, event.detail.delay || 0);
    });
</script>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
@vite(['resources/js/dashboard.js'])

</body>
</html>