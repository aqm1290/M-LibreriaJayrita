<div class="min-h-screen bg-gradient-to-br from-yellow-50 via-amber-50 to-orange-100 py-12 px-6">
    <div class="max-w-3xl mx-auto">

        <!-- CARD -->
        <div class="bg-white rounded-3xl shadow-xl border border-amber-100 px-6 md:px-8 py-8 md:py-10 space-y-8">
            <div class="text-center space-y-2">
                <p class="text-xs font-semibold tracking-[0.25em] text-amber-500 uppercase">
                    Personal
                </p>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900">
                    Crear cajero
                </h2>
                <p class="text-sm text-slate-500">
                    Registra un nuevo usuario con rol de cajero y su horario de trabajo.
                </p>
            </div>

            @if (session('success'))
                <div
                    class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3 rounded-2xl text-sm flex items-center gap-3">
                    <span
                        class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-emerald-600 text-white text-xs font-bold">
                        ✓
                    </span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <form wire:submit.prevent="crear" class="space-y-7">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nombre -->
                    <div>
                        <label
                            class="flex items-center mb-2 text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Nombre completo
                        </label>
                        <input type="text" wire:model="name"
                            class="w-full px-4 py-2.5 rounded-2xl border border-amber-200 bg-white/80 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-amber-200 focus:border-amber-500 transition">
                        @error('name')
                            <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label
                            class="flex items-center mb-2 text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Correo electrónico
                        </label>
                        <input type="email" wire:model="email"
                            class="w-full px-4 py-2.5 rounded-2xl border border-amber-200 bg-white/80 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-amber-200 focus:border-amber-500 transition">
                        @error('email')
                            <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label
                            class="flex items-center mb-2 text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Contraseña
                        </label>
                        <input type="password" wire:model="password"
                            class="w-full px-4 py-2.5 rounded-2xl border border-amber-200 bg-white/80 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-amber-200 focus:border-amber-500 transition">
                        @error('password')
                            <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label
                            class="flex items-center mb-2 text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Teléfono (opcional)
                        </label>
                        <input type="text" wire:model="telefono"
                            class="w-full px-4 py-2.5 rounded-2xl border border-amber-200 bg-white/80 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-amber-200 focus:border-amber-500 transition">
                    </div>

                    <!-- Turno -->
                    <div>
                        <label
                            class="flex items-center mb-2 text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Turno
                        </label>
                        <select wire:model="turno"
                            class="w-full px-4 py-2.5 rounded-2xl border border-amber-200 bg-white/80 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-amber-200 focus:border-amber-500 transition">
                            <option value="mañana">Mañana</option>
                            <option value="tarde">Tarde</option>
                            <option value="noche">Noche</option>
                        </select>
                        @error('turno')
                            <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Horario -->
                    <div>
                        <label
                            class="flex items-center mb-2 text-xs font-semibold text-slate-600 uppercase tracking-wide">
                            Horario (rango)
                        </label>
                        <input type="text" wire:model="horario" placeholder="Ej: 08:00 - 16:00"
                            class="w-full px-4 py-2.5 rounded-2xl border border-amber-200 bg-white/80 text-sm
                                   focus:outline-none focus:ring-2 focus:ring-amber-200 focus:border-amber-500 transition">
                        @error('horario')
                            <p class="mt-1 text-xs font-semibold text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-amber-100 flex justify-center">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-10 py-3.5 rounded-2xl
                               bg-gradient-to-r from-amber-600 to-yellow-500 text-white text-sm md:text-base font-semibold
                               hover:from-amber-700 hover:to-yellow-600 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition">
                        CREAR CAJERO
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
