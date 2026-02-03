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

    <!-- Blogs Grid Section -->
    <section class="px-4 md:px-6 py-20 min-h-[400px]">
        <div class="container mx-auto">
            @if(isset($processedPosts) && count($processedPosts) > 0)
                <div id="blogs-grid-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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
            @else
                <!-- No Posts Found / Loading State -->
                <div class="text-center py-16">
                    <div class="w-24 h-24 mx-auto mb-6 bg-accent/20 rounded-full flex items-center justify-center">
                        <i class="ri-article-line text-4xl text-secondary"></i>
                    </div>
                    <h3 class="text-2xl font-serif font-semibold text-primary mb-3">No Blog Posts Found</h3>
                    <p class="text-gray-500 max-w-md mx-auto">
                        We're working on creating valuable content for you. Please check back soon for wellness tips, insights, and more.
                    </p>
                </div>
            @endif
        </div>
    </section>

@endsection