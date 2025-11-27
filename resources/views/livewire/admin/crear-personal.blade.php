<div class="max-w-4xl mx-auto p-6">
    <div class="bg-white rounded-2xl shadow-xl p-8">
        <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">
            Crear Cajero o Vendedor
        </h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        <form wire:submit.prevent="crear" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nombre completo</label>
                    <input type="text" wire:model="name" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Correo electrónico</label>
                    <input type="email" wire:model="email" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Contraseña</label>
                    <input type="password" wire:model="password" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 transition">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Teléfono (opcional)</label>
                    <input type="text" wire:model="telefono" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Tipo de personal</label>
                    <select wire:model="tipo" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:border-indigo-500">
                        <option value="cajero">Cajero</option>
                        <option value="vendedor">Vendedor</option>
                    </select>
                </div>

                @if($tipo === 'cajero')
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Horario</label>
                        <input type="text" wire:model="horario" placeholder="Ej: 8:00 - 16:00" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
                        @error('horario') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Turno</label>
                        <select wire:model="turno" class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl">
                            <option value="mañana">Mañana</option>
                            <option value="tarde">Tarde</option>
                            <option value="noche">Noche</option>
                        </select>
                    </div>
                @endif
            </div>

            <div class="text-center mt-8">
                <button type="submit" class="px-12 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-bold text-xl rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
                    CREAR {{ strtoupper($tipo) }}
                </button>
            </div>
        </form>
    </div>
</div>