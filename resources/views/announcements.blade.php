@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="pt-[144px] md:pt-[150px] px-4 md:px-6 bg-white">
        <div class="container mx-auto">
            <div class="max-w-4xl mx-auto text-center">
                <div class="mb-6 animate-on-scroll">
                    <span class="bg-accent text-secondary px-8 py-2.5 rounded-full font-medium text-base inline-block">
                        Announcements
                    </span>
                </div>
                <h1 class="text-4xl md:text-5xl font-serif font-bold text-primary mb-6 leading-tight">
                    Latest Updates
                </h1>
                <p class="text-gray-500 leading-relaxed text-lg font-light max-w-2xl mx-auto">
                    Stay up to date with the latest announcements, events, and important information from Zaya Wellness.
                </p>
            </div>
        </div>
    </section>

    <!-- Announcements Masonry Grid -->
    <section class="px-4 md:px-6 py-12 min-h-[400px]">
        <div class="container mx-auto max-w-7xl">

            @if(isset($processedPosts) && count($processedPosts) > 0)
                <div class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6">
                    @foreach($processedPosts as $post)
                        <div class="break-inside-avoid">
                            <!-- Link to Detail Page -->
                            <a href="{{ route('announcement-detail', $post['slug']) }}"
                                class="block group relative rounded-[20px] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">

                                <div class="w-full relative">
                                    @if($post['featured_image'])
                                        <img src="{{ $post['featured_image'] }}" @if(!empty($post['featured_image_srcset']))
                                            srcset="{{ $post['featured_image_srcset'] }}" sizes="{{ $post['featured_image_sizes'] }}"
                                        @endif alt="{{ $post['title'] }}" loading="lazy"
                                            class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-500 rounded-[20px]">
                                    @else
                                        <!-- Fallback for no image -->
                                        <div
                                            class="w-full aspect-3/4 bg-linear-to-br from-accent/30 to-secondary/20 flex flex-col items-center justify-center p-6 text-center rounded-[20px]">
                                            <i class="ri-notification-3-line text-5xl text-secondary/40 mb-4"></i>
                                            <h3 class="text-xl font-serif font-semibold text-primary line-clamp-2">
                                                {{ $post['title'] }}
                                            </h3>
                                        </div>
                                    @endif

                                    <!-- Hover Overlay with Title -->
                                    <div
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-6 bg-linear-to-t from-black/80 to-transparent">
                                        <h3
                                            class="text-white text-lg font-sans! font-medium line-clamp-2 drop-shadow-md transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                            {{ $post['title'] }}
                                        </h3>
                                    </div>
                                </div>

                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if(isset($pagination) && $pagination['totalPages'] > 1)
                    <nav class="mt-16 flex justify-center" aria-label="Announcements pagination">
                        <div class="flex items-center gap-2">
                            {{-- Previous Button --}}
                            @if($pagination['currentPage'] > 1)
                                <a href="{{ route('announcements', array_merge(request()->query(), ['page' => $pagination['currentPage'] - 1])) }}"
                                    class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-200 text-gray-600 hover:bg-secondary hover:text-white hover:border-secondary transition-all duration-300 group">
                                    <i class="ri-arrow-left-s-line text-xl"></i>
                                </a>
                            @else
                                <span
                                    class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-100 text-gray-300 cursor-not-allowed">
                                    <i class="ri-arrow-left-s-line text-xl"></i>
                                </span>
                            @endif

                            {{-- Page Numbers --}}
                            @php
                                $start = max(1, $pagination['currentPage'] - 2);
                                $end = min($pagination['totalPages'], $pagination['currentPage'] + 2);

                                if ($pagination['currentPage'] <= 3) {
                                    $end = min(5, $pagination['totalPages']);
                                }
                                if ($pagination['currentPage'] >= $pagination['totalPages'] - 2) {
                                    $start = max(1, $pagination['totalPages'] - 4);
                                }
                            @endphp

                            @if($start > 1)
                                <a href="{{ route('announcements', array_merge(request()->query(), ['page' => 1])) }}"
                                    class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-200 text-gray-600 hover:bg-secondary hover:text-white hover:border-secondary transition-all duration-300">
                                    1
                                </a>
                                @if($start > 2)
                                    <span class="flex items-center justify-center w-8 text-gray-400">...</span>
                                @endif
                            @endif

                            @for($i = $start; $i <= $end; $i++)
                                @if($i == $pagination['currentPage'])
                                    <span
                                        class="flex items-center justify-center w-12 h-12 rounded-full bg-secondary text-white font-medium shadow-lg shadow-secondary/30">
                                        {{ $i }}
                                    </span>
                                @else
                                    <a href="{{ route('announcements', array_merge(request()->query(), ['page' => $i])) }}"
                                        class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-200 text-gray-600 hover:bg-secondary hover:text-white hover:border-secondary transition-all duration-300">
                                        {{ $i }}
                                    </a>
                                @endif
                            @endfor

                            @if($end < $pagination['totalPages'])
                                @if($end < $pagination['totalPages'] - 1)
                                    <span class="flex items-center justify-center w-8 text-gray-400">...</span>
                                @endif
                                <a href="{{ route('announcements', array_merge(request()->query(), ['page' => $pagination['totalPages']])) }}"
                                    class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-200 text-gray-600 hover:bg-secondary hover:text-white hover:border-secondary transition-all duration-300">
                                    {{ $pagination['totalPages'] }}
                                </a>
                            @endif

                            {{-- Next Button --}}
                            @if($pagination['currentPage'] < $pagination['totalPages'])
                                <a href="{{ route('announcements', array_merge(request()->query(), ['page' => $pagination['currentPage'] + 1])) }}"
                                    class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-200 text-gray-600 hover:bg-secondary hover:text-white hover:border-secondary transition-all duration-300 group">
                                    <i class="ri-arrow-right-s-line text-xl"></i>
                                </a>
                            @else
                                <span
                                    class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-100 text-gray-300 cursor-not-allowed">
                                    <i class="ri-arrow-right-s-line text-xl"></i>
                                </span>
                            @endif
                        </div>
                    </nav>
                @endif

            @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 mx-auto mb-6 bg-accent/20 rounded-full flex items-center justify-center">
                        <i class="ri-notification-off-line text-4xl text-secondary"></i>
                    </div>
                    <h3 class="text-2xl font-serif font-semibold text-primary mb-3">No Announcements Found</h3>
                    <p class="text-gray-500 max-w-md mx-auto">
                        There are no announcements at this time. Please check back later.
                    </p>
                </div>
            @endif
        </div>
    </section>

@endsection