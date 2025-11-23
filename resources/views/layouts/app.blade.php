<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Librería Jayrita' }}</title>
    
    <script src="https://unpkg.com/feather-icons@4.29.1"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/sidebar.css', 'resources/js/sidebar.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex font-sans">

    <!-- SIDEBAR -->
    <aside id="sidebar" class="fixed inset-y-0 left-0 bg-white shadow-2xl border-r border-gray-200 flex flex-col transition-all duration-300 z-50 w-72">
        
        <div class="px-6 py-5 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v8"/>
                    </svg>
                </div>
                <div class="header-text">
                    <h1 class="text-2xl font-black text-gray-900">Jayra</h1>
                    <p class="text-xs text-gray-500">Librería & Papelería</p>
                </div>
            </div>
            <button id="btn-collapse" class="text-gray-500 hover:text-gray-700 p-2 rounded-lg hover:bg-gray-100">
                <i data-feather="chevrons-left" class="w-6 h-6"></i>
            </button>
        </div>

        <nav class="flex-1 px-4 py-6 space-y-1">
            @auth
                <a href="{{ route('dashboard') }}" class="group relative flex items-center gap-4 px-4 py-3 rounded-xl transition-all {{ Route::currentRouteName() === 'dashboard' ? 'bg-yellow-500 text-white font-bold shadow-md' : 'hover:bg-gray-100 text-gray-700' }}">
                    <i data-feather="home" class="w-5 h-5"></i>
                    <span class="nav-text">Dashboard</span>
                    <span class="tooltip">Dashboard</span>
                </a>

                @if(auth()->user()->rol === 'admin' || auth()->user()->rol === 'cajero')
                    <a href="{{ route('caja.apertura') }}" class="group relative flex items-center gap-4 px-4 py-3 rounded-xl transition-all {{ Route::currentRouteName() === 'caja.apertura' ? 'bg-yellow-500 text-white font-bold shadow-md' : 'hover:bg-gray-100 text-gray-700' }}">
                        <i data-feather="folder-plus" class="w-5 h-5"></i>
                        <span class="nav-text">Apertura de Caja</span>
                        <span class="tooltip">Apertura de Caja</span>
                    </a>
                    <a href="{{ route('caja.cierre') }}" class="group relative flex items-center gap-4 px-4 py-3 rounded-xl transition-all {{ Route::currentRouteName() === 'caja.cierre' ? 'bg-yellow-500 text-white font-bold shadow-md' : 'hover:bg-gray-100 text-gray-700' }}">
                        <i data-feather="lock" class="w-5 h-5"></i>
                        <span class="nav-text">Cierre de Caja</span>
                        <span class="tooltip">Cierre de Caja</span>
                    </a>
                @endif

                <a href="{{ route('caja.pos') }}" class="group relative flex items-center gap-4 px-4 py-3 rounded-xl transition-all {{ Route::currentRouteName() === 'caja.pos' ? 'bg-yellow-500 text-white font-bold shadow-md' : 'hover:bg-gray-100 text-gray-700' }}">
                    <i data-feather="shopping-cart" class="w-5 h-5"></i>
                    <span class="nav-text">Punto de Venta</span>
                    <span class="tooltip">Punto de Venta</span>
                </a>

                @if(auth()->user()->rol === 'admin')
                    <div class="my-6 border-t border-gray-200"></div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest px-4 mb-2 gestion-title">Gestión</p>
                    <a href="{{ route('categorias') }}" class="group relative flex items-center gap-4 px-4 py-3 rounded-xl transition-all {{ Route::currentRouteName() === 'categorias' ? 'bg-yellow-500 text-white font-bold shadow-md' : 'hover:bg-gray-100 text-gray-700' }}">
                        <i data-feather="folder" class="w-5 h-5"></i>
                        <span class="nav-text">Categorías</span>
                        <span class="tooltip">Categorías</span>
                    </a>
                    <a href="{{ route('marcas') }}" class="group relative flex items-center gap-4 px-4 py-3 rounded-xl transition-all {{ Route::currentRouteName() === 'marcas' ? 'bg-yellow-500 text-white font-bold shadow-md' : 'hover:bg-gray-100 text-gray-700' }}">
                        <i data-feather="tag" class="w-5 h-5"></i>
                        <span class="nav-text">Marcas</span>
                        <span class="tooltip">Marcas</span>
                    </a>
                    <a href="{{ route('modelos') }}" class="group relative flex items-center gap-4 px-4 py-3 rounded-xl transition-all {{ Route::currentRouteName() === 'modelos' ? 'bg-yellow-500 text-white font-bold shadow-md' : 'hover:bg-gray-100 text-gray-700' }}">
                        <i data-feather="archive" class="w-5 h-5"></i>
                        <span class="nav-text">Modelos</span>
                        <span class="tooltip">Modelos</span>
                    </a>
                    <a href="{{ route('productos') }}" class="group relative flex items-center gap-4 px-4 py-3 rounded-xl transition-all {{ Route::currentRouteName() === 'productos' ? 'bg-yellow-500 text-white font-bold shadow-md' : 'hover:bg-gray-100 text-gray-700' }}">
                        <i data-feather="package" class="w-5 h-5"></i>
                        <span class="nav-text">Productos</span>
                        <span class="tooltip">Productos</span>
                    </a>
                    {{-- <a href="{{ route('usuarios') }}" class="group relative flex items-center gap-4 px-4 py-3 rounded-xl transition-all {{ Route::currentRouteName() === 'usuarios' ? 'bg-yellow-500 text-white font-bold shadow-md' : 'hover:bg-gray-100 text-gray-700' }}">
                        <i data-feather="users" class="w-5 h-5"></i>
                        <span class="nav-text">Usuarios</span>
                        <span class="tooltip">Gestión de Usuarios</span>
                    </a> --}}
                @endif
            @endauth
        </nav>

        <div class="p-5 border-t border-gray-200 bg-gray-50">
            @auth
                {{-- <div class="flex items-center gap-3 mb-3">
                    <div class="w-11 h-11 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl flex items-center justify-center text-white font-black shadow">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0 user-info">
                        <p class="font-bold text-gray-900 truncate user-name">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-600 truncate user-role">
                            {{ auth()->user()->rol === 'admin' ? 'Administrador' : (auth()->user()->rol === 'cajero' ? 'Cajero' : 'Vendedor') }}
                        </p>
                    </div>
                </div> --}}

                <button type="button" class="group relative w-full flex items-center justify-center gap-3 py-3 bg-red-500 hover:bg-red-600 text-white rounded-xl font-bold transition shadow-lg">
                    <i data-feather="log-out" class="w-5 h-5"></i>
                    <span class="nav-text">Cerrar Sesión</span>
                    <span class="tooltip">Salir del sistema</span>
                </button>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
            @endauth
        </div>
    </aside>

    <div id="main-content" class="flex-1 transition-all duration-300 ml-72">
        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
            <div class="px-8 py-6 flex justify-between items-center">
                <h1 class="text-3xl font-black text-gray-900">{{ $title ?? 'Dashboard' }}</h1>
                <p class="text-gray-600">Bienvenido de vuelta, {{ auth()->user()->name ?? 'Usuario' }}</p>
            </div>
        </header>

        <main class="p-8">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-10">
                {{ $slot }}
                @yield('content')
            </div>
        </main>
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>