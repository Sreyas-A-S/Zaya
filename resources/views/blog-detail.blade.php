@extends('layouts.app')

@section('content')

    <!-- Blog Detail Hero -->
    <section class="pt-[144px] md:pt-[150px] px-4 md:px-6 bg-white">
        <div class="container mx-auto max-w-5xl">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center gap-2 text-sm">
                    <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-secondary transition-colors">Home</a>
                    </li>
                    <li class="text-gray-300">/</li>
                    <li><a href="{{ route('blogs') }}" class="text-gray-400 hover:text-secondary transition-colors">Blog</a>
                    </li>
                    <li class="text-gray-300">/</li>
                    <li class="text-secondary">{{ Str::limit($blogPost['title'], 40) }}</li>
                </ol>
            </nav>

            <!-- Post Header -->
            <div class="mb-8">
                <div class="flex items-center gap-4 mb-6">
                    <span class="bg-accent text-secondary px-6 py-2 rounded-full text-sm font-medium">
                        {{ $blogPost['category'] }}
                    </span>
                    <span class="text-gray-400 text-sm flex items-center gap-2">
                        <i class="ri-calendar-line"></i>
                        {{ $blogPost['date'] }}
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-primary leading-tight mb-8">
                    {{ $blogPost['title'] }}
                </h1>
            </div>

            <!-- Featured Image -->
            @if($blogPost['featured_image'])
                <div class="w-full overflow-hidden rounded-[20px] mb-12">
                    <img src="{{ $blogPost['featured_image'] }}" alt="{{ $blogPost['title'] }}"
                        class="w-full h-[300px] md:h-[400px] lg:h-[500px] object-cover">
                </div>
            @endif
        </div>
    </section>

    <!-- Blog Content -->
    <section class="px-4 md:px-6 pb-16 bg-white">
        <div class="container mx-auto max-w-4xl">
            <article class="blog-content">
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
    </section>

    <!-- Related Posts -->
    @if(isset($relatedPosts) && count($relatedPosts) > 0)
        <section class="px-4 md:px-6 py-20 bg-gray-50">
            <div class="container mx-auto">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-serif font-bold text-primary mb-4">Related Articles</h2>
                    <p class="text-gray-500 max-w-2xl mx-auto">Continue your wellness journey with more insights and tips</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($relatedPosts as $post)
                        <article
                            class="group bg-white rounded-[20px] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-gray-100">
                            <a href="{{ route('blog-detail', $post['slug']) }}" class="block">
                                <div class="aspect-video overflow-hidden">
                                    @if($post['featured_image'])
                                        <img src="{{ $post['featured_image'] }}" alt="{{ $post['title'] }}"
                                            class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                                    @else
                                        <div
                                            class="w-full h-full bg-gradient-to-br from-accent/30 to-secondary/20 flex items-center justify-center">
                                            <i class="ri-article-line text-5xl text-secondary/40"></i>
                                        </div>
                                    @endif
                                </div>
                            </a>
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <span
                                        class="bg-accent/20 text-secondary px-4 py-1.5 rounded-full text-sm font-medium">{{ $post['category'] }}</span>
                                    <span class="text-gray-400 text-sm">{{ $post['date'] }}</span>
                                </div>
                                <a href="{{ route('blog-detail', $post['slug']) }}">
                                    <h3
                                        class="text-xl font-serif font-semibold text-primary mb-3 group-hover:text-secondary transition-colors line-clamp-2">
                                        {{ $post['title'] }}
                                    </h3>
                                </a>
                                <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-3">
                                    {{ Str::limit($post['excerpt'], 120) }}
                                </p>
                                <a href="{{ route('blog-detail', $post['slug']) }}"
                                    class="inline-flex items-center text-secondary font-medium hover:text-primary transition-colors">
                                    Read More
                                    <i class="ri-arrow-right-line ml-2"></i>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection