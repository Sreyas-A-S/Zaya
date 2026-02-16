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
                    <div class="mb-12">
                        <form id="blogs-filter-form" action="{{ route('blogs') }}" method="GET">
                            <div class="flex flex-col md:flex-row gap-6 mb-6">
                                @if(request()->has('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif
                                
                                <!-- Search Input -->
                                <div class="relative group grow">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-6 pointer-events-none">
                                        <i class="ri-search-line text-2xl text-[#C5896B]"></i>
                                    </div>
                                    <input type="text" 
                                           name="search" 
                                           value="{{ $searchQuery ?? '' }}" 
                                           placeholder="Search articles, topics..." 
                                           class="w-full pl-16 pr-20 h-[72px] bg-white border border-[#D4A58E] rounded-full text-[#A67B5B] placeholder-[#C5896B] outline-none focus:ring-1 focus:ring-[#D4A58E] transition-all duration-300 text-lg shadow-sm hover:shadow-md">
                                    
                                    @if(!empty($searchQuery))
                                        <a href="{{ route('blogs') }}" 
                                           class="absolute right-20 top-1/2 -translate-y-1/2 text-[#C5896B] hover:text-[#B07459] transition-colors p-2">
                                            <i class="ri-close-circle-line text-2xl"></i>
                                        </a>
                                    @endif

                                    <button type="submit" 
                                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#C5896B] hover:bg-[#B07459] text-white w-14 h-14 rounded-full flex items-center justify-center transition-all duration-300 shadow-md cursor-pointer">
                                        <i class="ri-search-line text-xl"></i>
                                    </button>
                                </div>

                                <!-- Author Dropdown -->
                                @if(isset($authors) && count($authors) > 0)
                                    <div class="relative w-full md:w-80" id="author-dropdown-container">
                                        <input type="hidden" name="author" id="author-input" value="{{ $selectedAuthorId ?? '' }}">
                                        
                                        @php
                                            $displayAuthorName = 'All Authors';
                                            if(isset($selectedAuthorId) && $selectedAuthorId) {
                                                foreach($authors as $a) {
                                                    if($a['id'] == $selectedAuthorId) {
                                                        $displayAuthorName = $a['name'];
                                                        break;
                                                    }
                                                }
                                            }
                                        @endphp

                                        <button type="button" onclick="toggleAuthorDropdown()" class="w-full h-[72px] pl-8 pr-6 bg-white border border-[#D4A58E] rounded-full text-[#C5896B] text-left outline-none focus:ring-1 focus:ring-[#D4A58E] transition-all duration-300 flex items-center justify-between shadow-sm hover:shadow-md cursor-pointer">
                                            <span class="truncate block text-lg font-sans" id="selected-author-text">
                                                {{ $displayAuthorName }}
                                            </span>
                                            <i class="ri-arrow-down-s-line text-2xl text-[#C5896B] transition-transform duration-300" id="author-dropdown-arrow"></i>
                                        </button>
                                        
                                        <!-- Dropdown List -->
                                        <div id="author-dropdown-list" class="absolute top-full left-0 w-full mt-2 bg-white border border-[#efe6e1] rounded-[2rem] shadow-xl overflow-hidden hidden z-50">
                                            <div class="max-h-60 overflow-y-auto py-2 flex flex-col gap-1 px-2">
                                                <div onclick="selectAuthor('', 'All Authors')" class="px-6 py-3 hover:bg-[#FFFBF5] rounded-xl cursor-pointer transition-colors text-[#5A3E31] font-medium text-base">
                                                    All Authors
                                                </div>
                                                @foreach($authors as $author)
                                                    <div onclick="selectAuthor('{{ $author['id'] }}', '{{ addslashes($author['name']) }}')" class="px-6 py-3 hover:bg-[#FFFBF5] rounded-xl cursor-pointer transition-colors text-[#5A3E31] font-medium text-base {{ isset($selectedAuthorId) && $selectedAuthorId == $author['id'] ? 'bg-[#FFFBF5]' : '' }}">
                                                        {{ $author['name'] }}
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if(!empty($searchQuery) || (isset($selectedAuthorId) && $selectedAuthorId))
                                <div class="mt-4 ml-2">
                                    <p class="text-gray-500">
                                        Showing results
                                        @if(!empty($searchQuery))
                                             for "<span class="font-medium text-primary">{{ $searchQuery }}</span>"
                                        @endif
                                        @if(!empty($searchQuery) && (isset($selectedAuthorId) && $selectedAuthorId))
                                            and
                                        @endif
                                        @if(isset($selectedAuthorId) && $selectedAuthorId)
                                             posts by "<span class="font-medium text-primary">{{ $displayAuthorName }}</span>"
                                        @endif
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
                                        <div class="flex items-center gap-3 mb-4 cursor-default">
                                            <span class="bg-accent/20 text-secondary px-4 py-1.5 rounded-full text-sm font-medium">{{ $post['category'] }}</span>
                                            <span class="text-gray-400 text-sm">{{ $post['date'] }}</span>
                                        </div>
                                        <a href="{{ route('blog-detail', $post['slug']) }}">
                                            <h3 class="text-xl font-serif font-semibold text-primary mb-3 group-hover:text-secondary transition-colors line-clamp-2">
                                                {{ $post['title'] }}
                                            </h3>
                                        </a>
                                        <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-3 cursor-default">
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

    <script>
        function toggleAuthorDropdown() {
            const list = document.getElementById('author-dropdown-list');
            const arrow = document.getElementById('author-dropdown-arrow');
            
            if (list.classList.contains('hidden')) {
                list.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                list.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        }

        function selectAuthor(id, name) {
            // Show preloader
            if (window.showPreloader) {
                window.showPreloader();
            }

            document.getElementById('author-input').value = id;
            document.getElementById('selected-author-text').innerText = name;
            toggleAuthorDropdown();
            document.getElementById('blogs-filter-form').submit();
        }

        // Close on click outside
        window.addEventListener('click', function(e) {
            const container = document.getElementById('author-dropdown-container');
            if (container && !container.contains(e.target)) {
                const list = document.getElementById('author-dropdown-list');
                const arrow = document.getElementById('author-dropdown-arrow');
                if (!list.classList.contains('hidden')) {
                    list.classList.add('hidden');
                    arrow.classList.remove('rotate-180');
                }
            }
        });
    </script>

@endsection