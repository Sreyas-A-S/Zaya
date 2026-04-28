@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-center gap-3 lg:gap-6">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span aria-disabled="true" aria-label="@lang('pagination.previous')"
                class="w-10 h-10 lg:w-12 lg:h-12 rounded-full border border-gray-200 text-gray-300 flex items-center justify-center cursor-not-allowed">
                <i class="ri-arrow-left-line text-xl"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')"
                class="w-10 h-10 lg:w-12 lg:h-12 rounded-full border border-primary text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300">
                <i class="ri-arrow-left-line text-xl"></i>
            </a>
        @endif

        {{-- Pagination Elements --}}
        <div class="flex items-center justify-center gap-2 lg:gap-3">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span aria-disabled="true" class="w-10 h-10 lg:w-12 lg:h-12 rounded-full flex items-center justify-center text-gray-400">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                class="w-10 h-10 lg:w-12 lg:h-12 rounded-full border border-primary bg-primary text-white flex items-center justify-center font-semibold">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                                class="w-10 h-10 lg:w-12 lg:h-12 rounded-full border border-primary text-primary flex items-center justify-center font-semibold hover:bg-primary hover:text-white transition-all duration-300">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')"
                class="w-10 h-10 lg:w-12 lg:h-12 rounded-full border border-primary text-primary flex items-center justify-center hover:bg-primary hover:text-white transition-all duration-300">
                <i class="ri-arrow-right-line text-xl"></i>
            </a>
        @else
            <span aria-disabled="true" aria-label="@lang('pagination.next')"
                class="w-10 h-10 lg:w-12 lg:h-12 rounded-full border border-gray-200 text-gray-300 flex items-center justify-center cursor-not-allowed">
                <i class="ri-arrow-right-line text-xl"></i>
            </span>
        @endif
    </nav>
@endif

