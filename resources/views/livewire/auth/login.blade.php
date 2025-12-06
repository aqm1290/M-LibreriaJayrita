<div class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden
    bg-gradient-to-br from-yellow-600/20 via-amber-500/10 to-orange-600/20"
    style="background-image: url('https://images.unsplash.com/photo-1497633762265-9d179a990aa6?q=80&w=2073&auto=format&fit=crop');
         background-size: cover;
         background-position: center;
         background-attachment: fixed;">

    <!-- Overlay para que se lea todo perfecto -->
    <div class="absolute inset-0 bg-black/30"></div>

    <div class="w-full max-w-md relative z-10">
        <!-- Logo + Título -->
        <div class="text-center mb-10">
            <!-- TU LOGO REAL AQUÍ -->
            <div class="mx-auto w-40 h-40 mb-6">
                <img src="{{ asset('images/logo-jayrita.png') }}" alt="Librería Jayrita"
                    class="w-full h-full object-contain drop-shadow-2xl">
            </div>

            <h1 class="text-4xl font-black text-white drop-shadow-2xl mb-2 tracking-tight">
                LIBRERÍA JAYRITA
            </h1>
            {{-- <p class="text-yellow-200 font-semibold text-lg drop-shadow-lg">
                Sistema de Gestión
            </p> --}}
        </div>

        <!-- Card del Login -->
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl p-8 border border-yellow-200/50">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Iniciar Sesión</h2>

            <form wire:submit.prevent="login" class="space-y-6">
                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Correo Electrónico</label>
                    <input type="email" wire:model="email"
                        class="w-full px-5 py-4 rounded-xl border-2 border-amber-300 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-200 transition duration-300 text-lg"
                        placeholder="" required>
                    @error('email')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Contraseña</label>
                    <input type="password" wire:model="password"
                        class="w-full px-5 py-4 rounded-xl border-2 border-amber-300 focus:border-yellow-500 focus:ring-4 focus:ring-yellow-200 transition duration-300 text-lg"
                        required>
                    @error('password')
                        <span class="text-red-600 text-sm mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Botón -->
                <button type="submit"
                    class="w-full py-5 bg-gradient-to-r from-yellow-500 to-amber-600 hover:from-yellow-600 hover:to-amber-700 text-white font-black text-xl rounded-xl shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center gap-3"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>INGRESAR AL SISTEMA</span>
                    <span wire:loading>Entrando...</span>
                    <svg wire:loading class="animate-spin w-6 h-6 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </button>

                @if (session()->has('error'))
                    <div
                        class="mt-4 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-xl text-center font-medium">
                        {{ session('error') }}
                    </div>
                @endif
            </form>

            <div class="mt-8 text-center text-sm text-gray-600">
                © {{ date('Y') }} Librería Jayrita • Todos los derechos reservados.
            </div>
        </div>
    </div>
</div>
