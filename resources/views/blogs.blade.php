@extends('layouts.app')

@section('content')

    <!-- Hero Section -->
    <section class="pt-[144px] md:pt-[150px] px-4 md:px-6 bg-white">
        <div class="container mx-auto">
            <!-- Text Content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 lg:gap-20">
                <!-- Left Text -->
                <div>
                    <div class="mb-8 animate-on-scroll">
                        <span class="bg-accent text-secondary px-8 py-2.5 rounded-full font-medium text-base inline-block">
                            {{ $settings['blogs_page_badge'] ?? 'Our Blogs' }}
                        </span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-serif font-bold text-primary mb-8 leading-tight">
                        {!! nl2br($settings['blogs_page_title'] ?? "Insights & \n Wellness Tips") !!}
                    </h1>
                </div>

                <!-- Right Text -->
                <div class="col-span-2 pt-2 lg:pt-4">
                    <h2 class="text-2xl md:text-[28px] font-serif text-secondary mb-6 leading-snug">
                        {!! nl2br($settings['blogs_page_subtitle'] ?? 'Discover expert articles, wellness guides, and holistic living tips from our practitioners.') !!}
                    </h2>
                    <p class="text-gray-500 leading-relaxed text-base font-light">
                        {{ $settings['blogs_page_description'] ?? 'Stay informed with the latest insights on Ayurveda, Yoga, mindfulness, and holistic health. Our blog features expert advice, wellness tips, and inspiring stories to guide your journey toward better health and harmony.' }}
                    </p>
                </div>
            </div> 
        </div>
    </section>

    <!-- Blogs Content with Sidebar -->
    <section class="px-4 md:px-6 py-12 min-h-[400px]">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 lg:gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-3">
                    <!-- Search Box -->
                    <div class="mb-8">
                        <form action="{{ route('blogs') }}" method="GET">
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-5 pointer-events-none">
                                    <i class="ri-search-line text-xl text-gray-400 group-focus-within:text-secondary transition-colors"></i>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       value="{{ $searchQuery ?? '' }}" 
                                       placeholder="Search articles, topics, wellness tips..." 
                                       class="w-full pl-14 pr-32 py-4 bg-gray-50 border border-gray-200 rounded-full text-gray-700 placeholder-gray-400 focus:outline-none focus:border-secondary focus:ring-2 focus:ring-secondary/20 transition-all duration-300">
                                @if(!empty($searchQuery))
                                    <a href="{{ route('blogs') }}" 
                                       class="absolute right-28 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors">
                                        <i class="ri-close-circle-line text-xl"></i>
                                    </a>
                                @endif
                                <button type="submit" 
                                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-secondary hover:bg-secondary/90 text-white px-6 py-2.5 rounded-full font-medium transition-all duration-300 hover:shadow-lg">
                                    Search
                                </button>
                            </div>
                            @if(!empty($searchQuery))
                                <div class="mt-4">
                                    <p class="text-gray-500">
                                        Showing results for "<span class="font-medium text-primary">{{ $searchQuery }}</span>"
                                        @if(isset($pagination['totalPosts']))
                                            <span class="text-gray-400">({{ $pagination['totalPosts'] }} {{ $pagination['totalPosts'] == 1 ? 'result' : 'results' }} found)</span>
                                        @endif
                                    </p>
                                </div>
                            @endif
                        </form>
                    </div>

                    <!-- Blogs Grid -->
                    @if(isset($processedPosts) && count($processedPosts) > 0)
                        <div id="blogs-grid-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                            @foreach($processedPosts as $post)
                                <article class="group bg-white rounded-[20px] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                                    <a href="{{ route('blog-detail', $post['slug']) }}" class="block">
                                        <div class="aspect-video overflow-hidden">
                                            @if($post['featured_image'])
                                                <img src="{{ $post['featured_image'] }}" alt="{{ $post['title'] }}" 
                                                    class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-accent/30 to-secondary/20 flex items-center justify-center">
                                                    <i class="ri-article-line text-5xl text-secondary/40"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                    <div class="p-6">
                                        <div class="flex items-center gap-3 mb-4">
                                            <span class="bg-accent/20 text-secondary px-4 py-1.5 rounded-full text-sm font-medium">{{ $post['category'] }}</span>
                                            <span class="text-gray-400 text-sm">{{ $post['date'] }}</span>
                                        </div>
                                        <a href="{{ route('blog-detail', $post['slug']) }}">
                                            <h3 class="text-xl font-serif font-semibold text-primary mb-3 group-hover:text-secondary transition-colors line-clamp-2">
                                                {{ $post['title'] }}
                                            </h3>
                                        </a>
                                        <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-3">
                                            {{ Str::limit($post['excerpt'], 150) }}
                                        </p>
                                        <a href="{{ route('blog-detail', $post['slug']) }}" class="inline-flex items-center text-secondary font-medium hover:text-primary transition-colors">
                                            Read More
                                            <i class="ri-arrow-right-line ml-2"></i>
                                        </a>
                                    </div>
                                </article>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if(isset($pagination) && $pagination['totalPages'] > 1)
                            <nav class="mt-16 flex justify-center" aria-label="Blog pagination">
                                <div class="flex items-center gap-2">
                                    {{-- Previous Button --}}
                                    @if($pagination['currentPage'] > 1)
                                        <a href="{{ route('blogs', array_merge(request()->query(), ['page' => $pagination['currentPage'] - 1])) }}" 
                                           class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-200 text-gray-600 hover:bg-secondary hover:text-white hover:border-secondary transition-all duration-300 group">
                                            <i class="ri-arrow-left-s-line text-xl"></i>
                                        </a>
                                    @else
                                        <span class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-100 text-gray-300 cursor-not-allowed">
                                            <i class="ri-arrow-left-s-line text-xl"></i>
                                        </span>
                                    @endif

                                    {{-- Page Numbers --}}
                                    @php
                                        $start = max(1, $pagination['currentPage'] - 2);
                                        $end = min($pagination['totalPages'], $pagination['currentPage'] + 2);
                                        
                                        // Adjust if we're near the start or end
                                        if ($pagination['currentPage'] <= 3) {
                                            $end = min(5, $pagination['totalPages']);
                                        }
                                        if ($pagination['currentPage'] >= $pagination['totalPages'] - 2) {
                                            $start = max(1, $pagination['totalPages'] - 4);
                                        }
                                    @endphp

                                    {{-- First page and ellipsis --}}
                                    @if($start > 1)
                                        <a href="{{ route('blogs', array_merge(request()->query(), ['page' => 1])) }}" 
                                           class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-200 text-gray-600 hover:bg-secondary hover:text-white hover:border-secondary transition-all duration-300">
                                            1
                                        </a>
                                        @if($start > 2)
                                            <span class="flex items-center justify-center w-8 text-gray-400">...</span>
                                        @endif
                                    @endif

                                    {{-- Page number links --}}
                                    @for($i = $start; $i <= $end; $i++)
                                        @if($i == $pagination['currentPage'])
                                            <span class="flex items-center justify-center w-12 h-12 rounded-full bg-secondary text-white font-medium shadow-lg shadow-secondary/30">
                                                {{ $i }}
                                            </span>
                                        @else
                                            <a href="{{ route('blogs', array_merge(request()->query(), ['page' => $i])) }}" 
                                               class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-200 text-gray-600 hover:bg-secondary hover:text-white hover:border-secondary transition-all duration-300">
                                                {{ $i }}
                                            </a>
                                        @endif
                                    @endfor

                                    {{-- Last page and ellipsis --}}
                                    @if($end < $pagination['totalPages'])
                                        @if($end < $pagination['totalPages'] - 1)
                                            <span class="flex items-center justify-center w-8 text-gray-400">...</span>
                                        @endif
                                        <a href="{{ route('blogs', array_merge(request()->query(), ['page' => $pagination['totalPages']])) }}" 
                                           class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-200 text-gray-600 hover:bg-secondary hover:text-white hover:border-secondary transition-all duration-300">
                                            {{ $pagination['totalPages'] }}
                                        </a>
                                    @endif

                                    {{-- Next Button --}}
                                    @if($pagination['currentPage'] < $pagination['totalPages'])
                                        <a href="{{ route('blogs', array_merge(request()->query(), ['page' => $pagination['currentPage'] + 1])) }}" 
                                           class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-200 text-gray-600 hover:bg-secondary hover:text-white hover:border-secondary transition-all duration-300 group">
                                            <i class="ri-arrow-right-s-line text-xl"></i>
                                        </a>
                                    @else
                                        <span class="flex items-center justify-center w-12 h-12 rounded-full border border-gray-100 text-gray-300 cursor-not-allowed">
                                            <i class="ri-arrow-right-s-line text-xl"></i>
                                        </span>
                                    @endif
                                </div>
                            </nav>

                            {{-- Pagination Info --}}
                            <div class="mt-6 text-center">
                                <p class="text-gray-400 text-sm">
                                    Showing {{ (($pagination['currentPage'] - 1) * $pagination['perPage']) + 1 }} - {{ min($pagination['currentPage'] * $pagination['perPage'], $pagination['totalPosts']) }} of {{ $pagination['totalPosts'] }} articles
                                </p>
                            </div>
                        @endif
                    @else
                        <!-- No Posts Found / Loading State -->
                        <div class="text-center py-16">
                            <div class="w-24 h-24 mx-auto mb-6 bg-accent/20 rounded-full flex items-center justify-center">
                                <i class="ri-article-line text-4xl text-secondary"></i>
                            </div>
                            @if(!empty($searchQuery))
                                <h3 class="text-2xl font-serif font-semibold text-primary mb-3">No Results Found</h3>
                                <p class="text-gray-500 max-w-md mx-auto mb-6">
                                    We couldn't find any articles matching "{{ $searchQuery }}". Try a different search term or browse all our articles.
                                </p>
                                <a href="{{ route('blogs') }}" class="inline-flex items-center bg-secondary text-white px-6 py-3 rounded-full font-medium hover:bg-secondary/90 transition-all duration-300">
                                    <i class="ri-refresh-line mr-2"></i>
                                    View All Articles
                                </a>
                            @else
                                <h3 class="text-2xl font-serif font-semibold text-primary mb-3">No Blog Posts Found</h3>
                                <p class="text-gray-500 max-w-md mx-auto">
                                    We're working on creating valuable content for you. Please check back soon for wellness tips, insights, and more.
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-[180px] space-y-8">
                        <!-- Categories -->
                        @if(isset($categories) && count($categories) > 0)
                            <div class="bg-gray-50 rounded-[20px] p-6">
                                <h3 class="text-xl font-serif font-bold text-primary mb-6 flex items-center gap-3">
                                    <span class="w-10 h-10 bg-accent/30 rounded-full flex items-center justify-center">
                                        <i class="ri-folder-line text-secondary"></i>
                                    </span>
                                    Categories
                                </h3>
                                <div class="space-y-2">
                                    {{-- All Categories Link --}}
                                    <a href="{{ route('blogs') }}" 
                                       class="flex items-center justify-between p-3 rounded-xl {{ !request('category') ? 'bg-secondary text-white' : 'bg-white hover:bg-secondary hover:text-white' }} transition-all duration-300 border border-gray-100 group">
                                        <span class="font-medium {{ !request('category') ? 'text-white' : 'text-gray-700 group-hover:text-white' }} transition-colors">
                                            All Categories
                                        </span>
                                        <span class="text-sm {{ !request('category') ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-white/20 group-hover:text-white' }} px-3 py-1 rounded-full transition-all">
                                            <i class="ri-apps-line"></i>
                                        </span>
                                    </a>
                                    @foreach($categories as $category)
                                        @if($category->count > 0)
                                            @php
                                                $isActive = request('category') === $category->name;
                                            @endphp
                                            <a href="{{ route('blogs', ['category' => $category->name]) }}" 
                                               class="flex items-center justify-between p-3 rounded-xl {{ $isActive ? 'bg-secondary text-white' : 'bg-white hover:bg-secondary hover:text-white' }} transition-all duration-300 border border-gray-100 group">
                                                <span class="font-medium {{ $isActive ? 'text-white' : 'text-gray-700 group-hover:text-white' }} transition-colors">
                                                    {{ $category->name }}
                                                </span>
                                                <span class="text-sm {{ $isActive ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-500 group-hover:bg-white/20 group-hover:text-white' }} px-3 py-1 rounded-full transition-all">
                                                    {{ $category->count }}
                                                </span>
                                            </a>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Newsletter CTA -->
                        <div class="bg-gradient-to-br from-secondary to-primary rounded-[20px] p-6 text-white">
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mb-4">
                                <i class="ri-mail-line text-2xl"></i>
                            </div>
                            <h3 class="text-lg font-serif font-bold mb-2">Stay Updated</h3>
                            <p class="text-white/80 text-sm mb-4">Get the latest wellness tips and insights delivered to your inbox.</p>
                            <a href="#" 
                               class="inline-flex items-center gap-2 bg-white text-secondary px-5 py-2.5 rounded-full font-medium text-sm hover:bg-accent transition-all duration-300">
                                Subscribe Now
                                <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection