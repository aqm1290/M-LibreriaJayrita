<div x-data="{ open: false }" x-cloak class="fixed bottom-4 right-4 z-50">
    {{-- Ventana del chat --}}
    <div x-show="open" x-transition
        class="w-80 sm:w-96 h-96 bg-white shadow-xl rounded-2xl border border-yellow-200 flex flex-col">
        <div
            class="px-4 py-2 bg-gradient-to-r from-yellow-400 via-yellow-500 to-orange-500 rounded-t-2xl flex items-center justify-between">
            <h2 class="text-sm font-bold text-white">
                JAYRITA ASISTENTE
            </h2>
            <button type="button" class="text-white/80 hover:text-white text-xl leading-none" @click="open = false">
                &times;
            </button>
        </div>

        <div class="flex-1 p-3 space-y-1 overflow-y-auto text-sm">
            @foreach ($messages as $message)
                <div class="mb-1">
                    <div class="font-semibold text-[11px] text-slate-500">
                        {{ $message['from'] === 'admin' ? 'Administrador' : 'Bot' }}
                    </div>
                    <div
                        class="mt-0.5 px-3 py-1.5 rounded-xl whitespace-pre-wrap
                        {{ $message['from'] === 'admin' ? 'bg-yellow-100 text-slate-900 ml-6' : 'bg-slate-100 text-slate-800 mr-6' }}">
                        {{ $message['text'] }}
                    </div>
                </div>
            @endforeach
        </div>

        <form wire:submit.prevent="sendMessage" class="p-3 border-t border-yellow-100 flex gap-2">
            <input type="text" wire:model.defer="input"
                class="flex-1 border border-yellow-300 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-yellow-200"
                placeholder=''>
            <button type="submit"
                class="bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-semibold px-3 py-2 rounded-xl">
                Enviar
            </button>
        </form>
    </div>

    {{-- Burbuja flotante --}}
    {{-- Burbuja flotante --}}
    <button type="button" @click="open = !open"
        class="w-14 h-14 sm:w-16 sm:h-16 rounded-full
           bg-transparent
           shadow-[0_10px_25px_rgba(249,115,22,0.4)]
           flex items-center justify-center
           hover:scale-105 transform transition">
        <img src="{{ asset('images/jayrita(2).png') }}" alt="Chatbot admin"
            class="w-16 h-16 sm:w-16 sm:h-16 rounded-full object-cover">
    </button>

</div>
