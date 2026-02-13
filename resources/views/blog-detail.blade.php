@extends('layouts.app')

@section('content')

    <!-- Blog Detail Content with Sidebar -->
    <section class="pt-[144px] md:pt-[150px] px-4 md:px-6 pb-16 bg-white">
        <div class="container mx-auto max-w-7xl">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Main Content -->
                <div class="lg:col-span-2">
                    <!-- Breadcrumb -->
                    <nav class="mb-8">
                        <ol class="flex items-center gap-2 text-sm">
                            <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-secondary transition-colors">Home</a>
                            </li>
                            <li class="text-gray-300">/</li>
                            <li><a href="{{ route('blogs') }}" class="text-gray-400 hover:text-secondary transition-colors">Blog</a>
                            </li>
                            <li class="text-gray-300">/</li>
                            <li class="text-secondary line-clamp-1">{{ Str::limit($blogPost['title'], 40) }}</li>
                        </ol>
                    </nav>

                    <!-- Post Header -->
                    <div class="mb-10">
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-primary leading-tight mb-8">
                            {{ $blogPost['title'] }}
                        </h1>

                        <!-- Author & Meta Section -->
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                            <!-- Author -->
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-white shadow-sm ring-2 ring-gray-100">
                                    @if($blogPost['author_image'])
                                        <img src="{{ $blogPost['author_image'] }}" alt="{{ $blogPost['author'] }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-100 flex items-center justify-center text-slate-400">
                                            <i class="ri-user-line text-2xl"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1">
                                    <span class="text-base text-gray-400 italic font-regular tracking-wider">By</span>
                                    <span class="text-gray-400 font-serif font-regular text-lg leading-none italic">{{ $blogPost['author'] }}</span>
                                </div>
                            </div>

                            <!-- Meta -->
                            <div class="flex items-center gap-4 md:gap-6">
                                <div class="flex items-center gap-2 text-gray-500 text-sm">
                                    <i class="ri-calendar-line text-base leading-none"></i>
                                    <span class="font-regular leading-none">{{ $blogPost['date'] }}</span>
                                </div>
                                <div class="w-px h-8 bg-gray-200 hidden md:block"></div>
                                <span class="bg-[#FFE7CF] text-primary px-4 py-1.5 rounded-full text-xs font-regular tracking-wide">
                                    {{ $blogPost['category'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image -->
                    @if($blogPost['featured_image'])
                        <div class="w-full overflow-hidden rounded-[20px] mb-12">
                            <img src="{{ $blogPost['featured_image'] }}" alt="{{ $blogPost['title'] }}"
                                class="w-full h-[300px] md:h-[400px] lg:h-[450px] object-cover">
                        </div>
                    @endif

                    <!-- Blog Content -->
                    <article class="blog-content max-w-none">
                        {!! $blogPost['content'] !!}
                    </article>

                    <!-- Share Section -->
                    <div class="mt-12 pt-8 border-t border-gray-100">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                            <div>
                                <span class="text-gray-400 text-sm">Share this article</span>
                                <div class="flex items-center gap-4 mt-3">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                        target="_blank"
                                        class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-secondary hover:text-white transition-all">
                                        <i class="ri-facebook-fill text-xl"></i>
                                    </a>
                                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blogPost['title']) }}"
                                        target="_blank"
                                        class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-secondary hover:text-white transition-all">
                                        <i class="ri-twitter-x-fill text-xl"></i>
                                    </a>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($blogPost['title']) }}"
                                        target="_blank"
                                        class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-secondary hover:text-white transition-all">
                                        <i class="ri-linkedin-fill text-xl"></i>
                                    </a>
                                    <a href="https://api.whatsapp.com/send?text={{ urlencode($blogPost['title'] . ' ' . request()->url()) }}"
                                        target="_blank"
                                        class="w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-secondary hover:text-white transition-all">
                                        <i class="ri-whatsapp-fill text-xl"></i>
                                    </a>
                                </div>
                            </div>
                            <a href="{{ route('blogs') }}"
                                class="inline-flex items-center gap-2 text-secondary font-medium hover:text-primary transition-colors">
                                <i class="ri-arrow-left-line"></i>
                                Back to Blog
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-[180px] space-y-8">
                        <!-- Related Articles -->
                        @if(isset($relatedPosts) && count($relatedPosts) > 0)
                            <div class="bg-gray-50 rounded-[20px] p-6">
                                <h3 class="text-xl font-serif font-bold text-primary mb-6 flex items-center gap-3">
                                    <span class="w-10 h-10 bg-secondary/10 rounded-full flex items-center justify-center">
                                        <i class="ri-article-line text-secondary"></i>
                                    </span>
                                    Related Articles
                                </h3>
                                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                                    @foreach($relatedPosts as $post)
                                        <a href="{{ route('blog-detail', $post['slug']) }}" 
                                           class="group flex gap-4 p-3 rounded-xl bg-white hover:shadow-md transition-all duration-300 border border-gray-100">
                                            <!-- Thumbnail -->
                                            <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden">
                                                @if($post['featured_image'])
                                                    <img src="{{ $post['featured_image'] }}" alt="{{ $post['title'] }}" 
                                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-accent/30 to-secondary/20 flex items-center justify-center">
                                                        <i class="ri-article-line text-xl text-secondary/40"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <!-- Content -->
                                            <div class="flex-1 min-w-0">
                                                <span class="text-xs text-secondary font-medium">{{ $post['category'] }}</span>
                                                <h4 class="text-sm font-semibold text-primary group-hover:text-secondary transition-colors line-clamp-2 mt-1">
                                                    {{ $post['title'] }}
                                                </h4>
                                                <span class="text-xs text-gray-400 mt-1 block">{{ $post['date'] }}</span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

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
                                    @foreach($categories as $category)
                                        @if($category->count > 0)
                                            <a href="{{ route('blogs', ['category' => $category->name]) }}" 
                                               class="flex items-center justify-between p-3 rounded-xl bg-white hover:bg-secondary hover:text-white transition-all duration-300 border border-gray-100 group">
                                                <span class="font-medium text-gray-700 group-hover:text-white transition-colors">
                                                    {{ $category->name }}
                                                </span>
                                                <span class="text-sm bg-gray-100 text-gray-500 px-3 py-1 rounded-full group-hover:bg-white/20 group-hover:text-white transition-all">
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
                            <a href="{{ route('blogs') }}" 
                               class="inline-flex items-center gap-2 bg-white text-secondary px-5 py-2.5 rounded-full font-medium text-sm hover:bg-accent transition-all duration-300">
                                Explore More
                                <i class="ri-arrow-right-line"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection