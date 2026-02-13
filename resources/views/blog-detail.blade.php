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
                    <!-- Interaction & Share Section -->
                    <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-8">
                        <!-- Like Button -->
                        <div class="flex items-center gap-4">
                            <span class="text-gray-400 text-sm font-medium uppercase tracking-wide">Like this article?</span>
                            <button id="like-btn" onclick="toggleLike()" data-liked="{{ $blogPost['liked'] }}"
                                class="group flex items-center gap-2 px-5 py-2.5 rounded-full border transition-all duration-300 shadow-sm
                                {{ $blogPost['liked'] ? 'border-red-200 bg-red-50 text-red-500' : 'border-gray-200 bg-white text-gray-500 hover:border-red-200 hover:bg-red-50 hover:text-red-500' }}">
                                <i class="{{ $blogPost['liked'] ? 'ri-heart-fill' : 'ri-heart-line' }} text-xl group-hover:scale-110 transition-transform"></i>
                                <span class="font-medium text-sm"><span id="like-count">{{ $blogPost['likes'] }}</span> Likes</span>
                            </button>
                        </div>

                        <!-- Share -->
                        <div class="flex items-center gap-4">
                            <span class="text-gray-400 text-sm font-medium uppercase tracking-wide">Share</span>
                            <div class="flex items-center gap-3">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                                    target="_blank"
                                    class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-secondary hover:text-white transition-all">
                                    <i class="ri-facebook-fill text-lg"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blogPost['title']) }}"
                                    target="_blank"
                                    class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-secondary hover:text-white transition-all">
                                    <i class="ri-twitter-x-fill text-lg"></i>
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(request()->url()) }}&title={{ urlencode($blogPost['title']) }}"
                                    target="_blank"
                                    class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-secondary hover:text-white transition-all">
                                    <i class="ri-linkedin-fill text-lg"></i>
                                </a>
                                <a href="https://api.whatsapp.com/send?text={{ urlencode($blogPost['title'] . ' ' . request()->url()) }}"
                                    target="_blank"
                                    class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-secondary hover:text-white transition-all">
                                    <i class="ri-whatsapp-fill text-lg"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="mt-16 border-t border-gray-100 pt-12" id="comments-section">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-2xl font-serif font-bold text-primary">Comments</h3>
                            <span class="bg-secondary/10 text-secondary px-3 py-1 rounded-full text-xs font-bold" id="comments-count-badge" style="display:none">0</span>
                        </div>
                        
                        <!-- Comment List -->
                        <div id="comments-list" class="space-y-6 mb-12">
                            <div class="flex justify-center py-8">
                                <div class="w-8 h-8 border-4 border-secondary border-t-transparent rounded-full animate-spin"></div>
                            </div>
                        </div>

                        @if($blogPost['comment_status'] === 'open')
                        <!-- Comment Form -->
                        <div class="bg-gray-50 p-6 md:p-8 rounded-2xl border border-gray-100">
                            <h4 class="text-lg font-bold text-primary mb-6">Leave a Comment</h4>
                            <form id="comment-form" onsubmit="submitComment(event)">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                         <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Name *</label>
                                         <input type="text" name="author_name" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:border-secondary transition-colors bg-white shadow-sm font-medium text-gray-700">
                                    </div>
                                    <div>
                                         <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Email *</label>
                                         <input type="email" name="author_email" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:border-secondary transition-colors bg-white shadow-sm font-medium text-gray-700">
                                    </div>
                                </div>
                                <div class="mb-6">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Comment *</label>
                                    <textarea name="content" rows="4" required class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:outline-none focus:border-secondary transition-colors bg-white shadow-sm font-medium text-gray-700"></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit" class="bg-secondary text-white px-8 py-3 rounded-full font-bold hover:bg-opacity-90 transition-all shadow-md transform hover:-translate-y-0.5">
                                        Post Comment
                                    </button>
                                </div>
                            </form>
                        </div>
                        @else
                        <div class="bg-red-50 border border-red-100 text-red-500 px-6 py-4 rounded-xl text-center font-medium">
                            <i class="ri-door-lock-line align-middle mr-2"></i> Comments are closed for this post.
                        </div>
                        @endif
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadComments();
    });

    const postId = {{ $blogPost['id'] }};

    // Like Functionality
    async function toggleLike() {
        const btn = document.getElementById('like-btn');
        const countSpan = document.getElementById('like-count');
        const icon = btn.querySelector('i');
        const currentLiked = btn.getAttribute('data-liked') == '1';

        // Optimistic UI Update
        const newLiked = !currentLiked;
        btn.setAttribute('data-liked', newLiked ? '1' : '0');
        
        if(newLiked) {
            btn.classList.remove('border-gray-200', 'bg-white', 'text-gray-500');
            btn.classList.add('border-red-200', 'bg-red-50', 'text-red-500');
            icon.classList.remove('ri-heart-line');
            icon.classList.add('ri-heart-fill');
            countSpan.innerText = parseInt(countSpan.innerText) + 1;
        } else {
            btn.classList.add('border-gray-200', 'bg-white', 'text-gray-500');
            btn.classList.remove('border-red-200', 'bg-red-50', 'text-red-500');
            icon.classList.add('ri-heart-line');
            icon.classList.remove('ri-heart-fill');
            countSpan.innerText = Math.max(0, parseInt(countSpan.innerText) - 1);
        }

        try {
            const response = await fetch("{{ route('blog.like') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ post_id: postId })
            });
            
            const data = await response.json();
            if(data.success) {
                // Sync count from server
                countSpan.innerText = data.count;
            } else {
                console.error('Like failed');
            }
        } catch (error) {
           console.error('Error:', error);
        }
    }

    // Comment Functionality
    async function submitComment(e) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        
        const originalBtnText = submitBtn.innerText;
        submitBtn.innerText = 'Posting...';
        submitBtn.disabled = true;

        try {
            const response = await fetch("{{ route('blog.comment') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    post_id: postId,
                    author_name: formData.get('author_name'),
                    author_email: formData.get('author_email'),
                    content: formData.get('content')
                })
            });

            const data = await response.json();
            
            if(data.success) {
                alert(data.message);
                form.reset();
                if(!data.message.includes('moderation')) {
                    loadComments(); 
                }
            } else {
                alert(data.message || 'Error submitting comment');
            }
        } catch (error) {
            alert('An error occurred. Please try again.');
        } finally {
            submitBtn.innerText = originalBtnText;
            submitBtn.disabled = false;
        }
    }

    async function loadComments() {
        const container = document.getElementById('comments-list');
        try {
            const response = await fetch("{{ route('blog.comments', $blogPost['id']) }}");
            const comments = await response.json();
            
            if(comments.length === 0) {
                container.innerHTML = '<p class="text-gray-400 italic">No comments yet. Be the first to share your thoughts!</p>';
                return;
            }

            // Update Badge
            const countBadge = document.getElementById('comments-count-badge');
            if(countBadge) {
                countBadge.innerText = comments.length;
                countBadge.style.display = 'inline-block';
            }

            let html = '';
            comments.forEach(comment => {
                const date = new Date(comment.date).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
                const content = comment.content.rendered; 
                
                html += `
                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-secondary/10 flex items-center justify-center text-secondary font-bold text-lg">
                                ${comment.author_name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <h5 class="font-bold font-sans! text-primary text-sm">${comment.author_name}</h5>
                                <span class="text-xs text-gray-400">${date}</span>
                            </div>
                        </div>
                        <div class="text-gray-600 text-sm leading-relaxed prose prose-sm max-w-none">
                            ${content}
                        </div>
                    </div>
                `;
            });
            container.innerHTML = html;

        } catch (error) {
            container.innerHTML = '<p class="text-red-400 italic">Failed to load comments.</p>';
        }
    }
</script>
@endpush