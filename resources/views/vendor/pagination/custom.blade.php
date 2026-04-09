@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between py-10">
        
        {{-- Mobile View: Compact & Premium --}}
        <div class="flex justify-between flex-1 sm:hidden gap-4">
            @if ($paginator->onFirstPage())
                <span class="flex-1 flex items-center justify-center px-6 py-4 text-sm font-bold text-slate-300 bg-white border border-slate-100 rounded-2xl cursor-default uppercase tracking-widest leading-none">
                    {!! __('pagination.previous') !!}
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="flex-1 flex items-center justify-center px-6 py-4 text-sm font-bold text-slate-700 bg-white border border-slate-200 rounded-2xl shadow-sm hover:bg-emerald-50 hover:text-emerald-700 transition-all active:scale-95 uppercase tracking-widest leading-none">
                    {!! __('pagination.previous') !!}
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="flex-1 flex items-center justify-center px-6 py-4 text-sm font-bold text-slate-700 bg-white border border-slate-200 rounded-2xl shadow-sm hover:bg-emerald-50 hover:text-emerald-700 transition-all active:scale-95 uppercase tracking-widest leading-none">
                    {!! __('pagination.next') !!}
                </a>
            @else
                <span class="flex-1 flex items-center justify-center px-6 py-4 text-sm font-bold text-slate-300 bg-white border border-slate-100 rounded-2xl cursor-default uppercase tracking-widest leading-none">
                    {!! __('pagination.next') !!}
                </span>
            @endif
        </div>

        {{-- Desktop View: Elegant Rounded Pagination --}}
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-slate-500 bg-slate-50 px-4 py-2 rounded-full border border-slate-100">
                    Showing <span class="font-extrabold text-slate-900">{{ $paginator->firstItem() }}</span> to <span class="font-extrabold text-slate-900">{{ $paginator->lastItem() }}</span> of <span class="font-extrabold text-slate-900">{{ $paginator->total() }}</span> entries
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex gap-1.5 p-1.5 bg-white border border-slate-100 rounded-full shadow-sm">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                            <span class="relative inline-flex items-center justify-center w-10 h-10 text-slate-300 bg-white cursor-default rounded-full leading-5 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                            </span>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center justify-center w-10 h-10 text-slate-500 bg-white rounded-full leading-5 hover:text-emerald-600 hover:bg-emerald-50 transition-all focus:outline-none ring-offset-2 focus:ring-2 ring-emerald-100 active:scale-90" title="{{ __('Previous') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
                        </a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <span aria-disabled="true">
                                <span class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-bold text-slate-400 bg-white cursor-default leading-5 rounded-full">{{ $element }}</span>
                            </span>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page">
                                        <span class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-black text-white bg-emerald-600 cursor-default leading-5 rounded-full shadow-lg shadow-emerald-600/30 transition-all scale-110">{{ $page }}</span>
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="relative inline-flex items-center justify-center w-10 h-10 text-sm font-bold text-slate-500 bg-white leading-5 rounded-full hover:text-emerald-600 hover:bg-emerald-50 transition-all focus:outline-none active:scale-90" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center justify-center w-10 h-10 text-slate-500 bg-white rounded-full leading-5 hover:text-emerald-600 hover:bg-emerald-50 transition-all focus:outline-none ring-offset-2 focus:ring-2 ring-emerald-100 active:scale-90" title="{{ __('Next') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    @else
                        <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                            <span class="relative inline-flex items-center justify-center w-10 h-10 text-slate-300 bg-white cursor-default rounded-full leading-5 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                            </span>
                        </span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
