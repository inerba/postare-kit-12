@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex justify-center gap-8">
        {{-- First Page Link --}}
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="bg-gray-400 text-gray-100 text-sm md:text-base uppercase flex items-center justify-center px-3 py-2 md:px-4 md:py-3 leading-none font-medium rounded-sm transition-all cursor-default">
                @svg('heroicon-m-arrow-left', 'size-5 mr-2')
                {!! __('pagination.previous') !!}
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="bg-black text-sm md:text-base text-gray-400 hover:text-white uppercase flex items-center justify-center px-3 py-2 md:px-4 md:py-3 leading-none font-medium rounded-sm transition-all">
                @svg('heroicon-m-arrow-left', 'size-5 mr-2')
                {!! __('pagination.previous') !!}
            </a>
        @endif

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="bg-black text-sm md:text-base text-gray-400 hover:text-white uppercase flex items-center justify-center px-3 py-2 md:px-4 md:py-3 leading-none font-medium rounded-sm transition-all">
                {!! __('pagination.next') !!}
                @svg('heroicon-m-arrow-right', 'size-5 ml-2')
            </a>
        @else
            <span class="bg-gray-400 text-gray-100 text-sm md:text-base uppercase flex items-center justify-center px-3 py-2 md:px-4 md:py-3 leading-none font-medium rounded-sm transition-all cursor-default">
                {!! __('pagination.next') !!}
                @svg('heroicon-m-arrow-right', 'size-5 ml-2')
            </span>
        @endif
    </nav>
@endif
