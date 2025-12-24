@if ($paginator->hasPages())
    <nav class="flex justify-center mt-2 mb-2" aria-label="Pagination Navigation">
        <ul class="inline-flex items-center space-x-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span class="px-3 py-1 rounded-xl bg-gray-200 text-gray-500 cursor-not-allowed">Prev</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" 
                       class="px-3 py-1 rounded-xl bg-blue-500 text-white hover:bg-blue-600">Prev</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li><span class="px-3 py-1 text-gray-500">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="px-2 py-1 ml-2 rounded-3xl bg-green-500 text-white">{{ $page }}</span></li>
                        @else
                            <li><a href="{{ $url }}" 
                                   class="px-2 py-1 mr-2 rounded-3xl bg-gray-100 hover:bg-gray-300">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" 
                       class="px-3 py-1 rounded-xl bg-blue-500 text-white hover:bg-blue-600">Next</a>
                </li>
            @else
                <li>
                    <span class="px-3 py-1 rounded-xl bg-gray-200 text-gray-500 cursor-not-allowed">Next</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
