<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo + Título -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-full shadow-2xl mb-6">
                <svg class="w-14 h-14 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h1 class="text-4xl font-black text-gray-800 mb-2">LIBRERÍA JAYRA</h1>
        </div>

        <!-- Card del Login -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 border border-gray-100">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Iniciar Sesión</h2>

            <form wire:submit.prevent="login" class="space-y-6">
                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Correo Electrónico</label>
                    <input 
                        type="email" 
                        wire:model="email" 
                        class="w-full px-5 py-4 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition duration-200 text-lg"
                        
                        required
                    >
                    @error('email') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Contraseña -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Contraseña</label>
                    <input 
                        type="password" 
                        wire:model="password" 
                        class="w-full px-5 py-4 rounded-xl border-2 border-gray-200 focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition duration-200 text-lg"
                        
                        required
                    >
                    @error('password') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                </div>

                <!-- Recordar + Olvidé -->
                {{-- <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="remember" class="w-5 h-5 text-indigo-600 rounded focus:ring-indigo-500">
                        <span class="ml-2 text-gray-600">Recordarme</span>
                    </label>
                    <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium">¿Olvidaste tu contraseña?</a>
                </div> --}}

                <!-- Botón Login -->
                <button 
                    type="submit" 
                    class="w-full py-5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-black text-xl rounded-xl shadow-lg transform hover:scale-105 transition duration-300 flex items-center justify-center gap-3"
                    wire:loading.attr="disabled"
                >
                    <span wire:loading.remove>INGRESAR AL SISTEMA</span>
                    <span wire:loading>Entrando...</span>
                    <svg wire:loading class="animate-spin w-6 h-6" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>

                <!-- Mensaje de error general -->
                @if (session()->has('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-center">
                        {{ session('error') }}
                    </div>
                @endif
            </form>

            <!-- Footer -->
            <div class="mt-8 text-center text-sm text-gray-500">
                <footer>
                    <p>&copy; <script>document.write(new Date().getFullYear())</script> Librería Jayra • Todos los derechos reservados.</p>
                </footer>
            </div>
        </div>
    </div>
</div>