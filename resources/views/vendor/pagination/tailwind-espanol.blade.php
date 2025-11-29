{{-- resources/views/vendor/pagination/tailwind-espanol.blade.php --}}
@if ($paginator->hasPages())
    <nav class="flex items-center justify-center gap-2">
        {{-- Anterior --}}
        @if ($paginator->onFirstPage())
            <span class="px-6 py-3 text-sm font-bold text-gray-500 bg-gray-200 rounded-xl cursor-not-allowed">
                ← Anterior
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" 
               class="px-7 py-3 text-sm font-black text-gray-900 
                      bg-gradient-to-r from-yellow-400 to-orange-500 
                      hover:from-yellow-500 hover:to-orange-600 
                      rounded-xl shadow-lg hover:shadow-orange-500/50 
                      transform hover:scale-105 transition-all duration-200">
                ← Anterior
            </a>
        @endif

        {{-- Números de página --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-4 py-3 text-sm font-bold text-gray-500">...</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="px-5 py-3 text-sm font-black text-white 
                                     bg-gradient-to-r from-orange-600 to-red-600 
                                     rounded-xl shadow-xl scale-110">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" 
                           class="px-5 py-3 text-sm font-bold text-gray-900 
                                  bg-yellow-300 hover:bg-yellow-400 
                                  rounded-xl shadow-md hover:shadow-lg 
                                  transform hover:scale-110 transition-all duration-200">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Siguiente --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" 
               class="px-8 py-3 text-sm font-black text-gray-900 
                      bg-gradient-to-r from-yellow-400 to-orange-500 
                      hover:from-yellow-500 hover:to-orange-600 
                      rounded-xl shadow-lg hover:shadow-orange-500/50 
                      transform hover:scale-105 transition-all duration-200">
                Siguiente →
            </a>
        @else
            <span class="px-8 py-3 text-sm font-bold text-gray-500 bg-gray-200 rounded-xl cursor-not-allowed">
                Siguiente →
            </span>
        @endif
    </nav>
@endif