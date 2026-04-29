@extends('layouts.client')

@section('title', 'Reviews')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h1 class="text-3xl font-black text-secondary tracking-tight">Reviews</h1>
            <p class="text-gray-500 font-medium mt-1">Manage your feedback and see what others are saying.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button onclick="openZayaReviewModal()" class="inline-flex items-center justify-center px-6 py-4 bg-primary text-white rounded-[1.5rem] font-black text-sm hover:opacity-90 transition-all shadow-xl shadow-primary/20 uppercase tracking-[0.2em]">
                <i class="ri-heart-pulse-line mr-2"></i> Review Zaya
            </button>
            @if($reviewablePractitioners->count() > 0)
            <button onclick="openReviewModal()" class="inline-flex items-center justify-center px-6 py-4 bg-secondary text-white rounded-[1.5rem] font-black text-sm hover:bg-primary transition-all shadow-2xl shadow-secondary/30 uppercase tracking-[0.2em]">
                <i class="ri-edit-line mr-2"></i> Review Professional
            </button>
            @endif
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex border-b border-[#2E4B3D]/12 overflow-x-auto no-scrollbar">
        <button onclick="switchReviewTab('my-reviews')" id="tab-my-reviews" class="px-8 py-4 text-xs font-black uppercase tracking-[0.2em] border-b-2 {{ request()->has('received_page') ? 'border-transparent text-gray-400' : 'border-secondary text-secondary' }} hover:text-secondary transition-all">
            My Reviews
        </button>
        @if($receivedReviews->total() > 0 || in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']))
        <button onclick="switchReviewTab('received-reviews')" id="tab-received-reviews" class="px-8 py-4 text-xs font-black uppercase tracking-[0.2em] border-b-2 {{ request()->has('received_page') ? 'border-secondary text-secondary' : 'border-transparent text-gray-400' }} hover:text-secondary transition-all">
            Received Reviews
        </button>
        @endif
    </div>

    <!-- My Reviews Panel -->
    <div id="panel-my-reviews" class="{{ request()->has('received_page') ? 'hidden' : '' }} space-y-6">
        @forelse($myReviews as $review)
        <div class="bg-white rounded-[2rem] border border-[#2E4B3D]/12 p-5 md:p-8 shadow-sm group hover:border-secondary/20 transition-all">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <img src="{{ $review->target_pic }}" 
                         class="w-16 h-16 rounded-2xl object-cover border border-gray-100">
                    <div>
                        <div class="flex items-center gap-2">
                            <h3 class="font-black text-secondary leading-tight">{{ $review->target_name }}</h3>
                            <span class="text-[8px] px-2 py-0.5 rounded-full font-black uppercase tracking-widest {{ $review->display_status === 'approved' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-orange-50 text-orange-600 border border-orange-100' }}">
                                {{ $review->display_status }}
                            </span>
                        </div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ $review->target_role }} • {{ $review->review_type }}</p>
                    </div>
                </div>
                <div class="flex flex-col items-start md:items-end">
                    <div class="flex text-[#22C55E] gap-1 text-xl mb-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="ri-star-{{ $i <= $review->rating ? 'fill' : 'line' }}"></i>
                        @endfor
                    </div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $review->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-gray-50">
                <p class="text-gray-600 leading-relaxed font-medium italic">"{{ $review->review }}"</p>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-[2rem] border border-[#2E4B3D]/12 p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="ri-chat-voice-line text-4xl"></i>
            </div>
            <h3 class="text-xl font-black text-secondary mb-2">No reviews yet</h3>
            <p class="text-gray-500 max-w-xs mx-auto">You haven't written any reviews for practitioners yet.</p>
        </div>
        @endforelse

        @if($myReviews->hasPages())
        <div class="px-6 py-4 border-t border-[#2E4B3D]/12 flex flex-col md:flex-row items-center justify-between gap-4 mt-8 bg-white rounded-2xl shadow-sm">
            <div class="text-sm text-gray-500">
                Showing <span class="font-medium text-secondary">{{ $myReviews->firstItem() }}</span> to <span class="font-medium text-secondary">{{ $myReviews->lastItem() }}</span> of <span class="font-medium text-secondary">{{ $myReviews->total() }}</span> reviews
            </div>
            <div class="flex items-center space-x-2 pagination-links">
                @if($myReviews->onFirstPage())
                    <span class="px-4 py-2 bg-gray-50 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed border border-gray-100">Prev</span>
                @else
                    <a href="{{ $myReviews->appends(['received_page' => request('received_page')])->previousPageUrl() }}" class="px-4 py-2 bg-white text-secondary hover:bg-gray-50 rounded-lg text-sm font-medium border border-gray-200 transition-colors">Prev</a>
                @endif

                <div class="flex items-center space-x-1">
                    @php $start = max(1, $myReviews->currentPage() - 2); $end = min($myReviews->lastPage(), $myReviews->currentPage() + 2); @endphp
                    @foreach ($myReviews->appends(['received_page' => request('received_page')])->getUrlRange($start, $end) as $page => $url)
                        @if ($page == $myReviews->currentPage())
                            <span class="w-10 h-10 flex items-center justify-center bg-secondary text-white rounded-lg text-sm font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center bg-white text-secondary hover:bg-gray-50 rounded-lg text-sm font-medium border border-gray-200 transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>

                @if($myReviews->hasMorePages())
                    <a href="{{ $myReviews->appends(['received_page' => request('received_page')])->nextPageUrl() }}" class="px-4 py-2 bg-white text-secondary hover:bg-gray-50 rounded-lg text-sm font-medium border border-gray-200 transition-colors">Next</a>
                @else
                    <span class="px-4 py-2 bg-gray-50 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed border border-gray-100">Next</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    <!-- Received Reviews Panel -->
    <div id="panel-received-reviews" class="{{ request()->has('received_page') ? '' : 'hidden' }} space-y-6">
        @forelse($receivedReviews as $review)
        <div class="bg-white rounded-[2rem] border border-[#2E4B3D]/12 p-5 md:p-8 shadow-sm group hover:border-secondary/20 transition-all">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <img src="{{ $review->user->profile_pic ? (str_starts_with($review->user->profile_pic, 'http') ? $review->user->profile_pic : asset('storage/' . $review->user->profile_pic)) : asset('frontend/assets/profile-dummy-img.png') }}" 
                         class="w-16 h-16 rounded-2xl object-cover border border-gray-100">
                    <div>
                        <h3 class="font-black text-secondary leading-tight">{{ $review->user->name }}</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Verified Client</p>
                    </div>
                </div>
                <div class="flex flex-col items-start md:items-end">
                    <div class="flex text-[#22C55E] gap-1 text-xl mb-1">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="ri-star-{{ $i <= $review->rating ? 'fill' : 'line' }}"></i>
                        @endfor
                    </div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ $review->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-gray-50">
                <p class="text-gray-600 leading-relaxed font-medium italic">"{{ $review->review }}"</p>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-[2rem] border border-[#2E4B3D]/12 p-12 text-center">
            <div class="w-20 h-20 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="ri-star-line text-4xl"></i>
            </div>
            <h3 class="text-xl font-black text-secondary mb-2">No received reviews</h3>
            <p class="text-gray-500 max-w-xs mx-auto">You haven't received any reviews from clients yet.</p>
        </div>
        @endforelse

        @if($receivedReviews->hasPages())
        <div class="px-6 py-4 border-t border-[#2E4B3D]/12 flex flex-col md:flex-row items-center justify-between gap-4 mt-8 bg-white rounded-2xl shadow-sm">
            <div class="text-sm text-gray-500">
                Showing <span class="font-medium text-secondary">{{ $receivedReviews->firstItem() }}</span> to <span class="font-medium text-secondary">{{ $receivedReviews->lastItem() }}</span> of <span class="font-medium text-secondary">{{ $receivedReviews->total() }}</span> reviews
            </div>
            <div class="flex items-center space-x-2 pagination-links">
                @if($receivedReviews->onFirstPage())
                    <span class="px-4 py-2 bg-gray-50 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed border border-gray-100">Prev</span>
                @else
                    <a href="{{ $receivedReviews->appends(['my_page' => request('my_page')])->previousPageUrl() }}" class="px-4 py-2 bg-white text-secondary hover:bg-gray-50 rounded-lg text-sm font-medium border border-gray-200 transition-colors">Prev</a>
                @endif

                <div class="flex items-center space-x-1">
                    @php $start = max(1, $receivedReviews->currentPage() - 2); $end = min($receivedReviews->lastPage(), $receivedReviews->currentPage() + 2); @endphp
                    @foreach ($receivedReviews->appends(['my_page' => request('my_page')])->getUrlRange($start, $end) as $page => $url)
                        @if ($page == $receivedReviews->currentPage())
                            <span class="w-10 h-10 flex items-center justify-center bg-secondary text-white rounded-lg text-sm font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center bg-white text-secondary hover:bg-gray-50 rounded-lg text-sm font-medium border border-gray-200 transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>

                @if($receivedReviews->hasMorePages())
                    <a href="{{ $receivedReviews->appends(['my_page' => request('my_page')])->nextPageUrl() }}" class="px-4 py-2 bg-white text-secondary hover:bg-gray-50 rounded-lg text-sm font-medium border border-gray-200 transition-colors">Next</a>
                @else
                    <span class="px-4 py-2 bg-gray-50 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed border border-gray-100">Next</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Write Review Modal -->
<div id="write-review-modal" class="fixed inset-0 z-[999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeReviewModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
            <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-[#2E4B3D]/12">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-xl font-black text-secondary tracking-tight">Write a Review</h3>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mt-1">Share your experience</p>
                    </div>
                    <button onclick="closeReviewModal()" class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-all shadow-sm">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <div class="px-8 py-8">
                    <form action="{{ route('reviews.store') }}" method="POST" id="review-form">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-secondary mb-3 uppercase tracking-wider text-[10px] opacity-60">Select Professional</label>
                            <select name="practitioner_id" required class="w-full rounded-2xl border-[#2E4B3D]/12 focus:border-secondary focus:ring-0 text-sm py-4 transition-all shadow-sm">
                                <option value="">Select someone you've had a session with...</option>
                                @foreach($reviewablePractitioners as $p)
                                <option value="{{ $p->id }}">{{ $p->user->name }} ({{ str_replace('_', ' ', $p->user->role) }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-bold text-secondary mb-3 uppercase tracking-wider text-[10px] opacity-60">Your Rating</label>
                            <div class="flex gap-2 text-3xl">
                                <input type="hidden" name="rating" id="rating-value" value="5" required>
                                @for($i = 1; $i <= 5; $i++)
                                <i class="ri-star-fill text-[#22C55E] cursor-pointer rating-star transition-all hover:scale-110" data-val="{{ $i }}" onclick="setRating({{ $i }})"></i>
                                @endfor
                            </div>
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-bold text-secondary mb-3 uppercase tracking-wider text-[10px] opacity-60">Your Experience</label>
                            <textarea name="review" rows="4" required placeholder="What was your experience like with this specialist?"
                                class="w-full rounded-2xl border-[#2E4B3D]/12 focus:border-secondary focus:ring-0 text-sm py-4 px-5 transition-all shadow-sm resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full py-5 bg-secondary text-white rounded-[1.5rem] font-black text-sm hover:bg-primary transition-all shadow-2xl shadow-secondary/30 uppercase tracking-[0.2em]">
                            Submit Review
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Review Zaya Wellness Modal -->
<div id="zaya-review-modal" class="fixed inset-0 z-[999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeZayaReviewModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
            <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-primary/10">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-[#F9FBF9]">
                    <div>
                        <h3 class="text-xl font-black text-secondary tracking-tight">Review Zaya Wellness</h3>
                        <p class="text-[10px] text-primary uppercase font-black tracking-widest mt-1">Help us improve your journey</p>
                    </div>
                    <button onclick="closeZayaReviewModal()" class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-all shadow-sm">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <div class="px-8 py-8">
                    <form action="{{ route('reviews.zaya.store') }}" method="POST" id="zaya-review-form">
                        @csrf
                        <div class="text-center mb-8">
                            <label class="block text-sm font-bold text-secondary mb-4 uppercase tracking-wider text-[10px] opacity-60">Your Rating for Zaya</label>
                            <div class="flex justify-center gap-3 text-4xl">
                                <input type="hidden" name="rating" id="zaya-rating-value" value="5">
                                @for($i = 1; $i <= 5; $i++)
                                <i class="ri-star-fill text-[#22C55E] cursor-pointer zaya-rating-star transition-all hover:scale-110" data-val="{{ $i }}" onclick="setZayaRating({{ $i }})"></i>
                                @endfor
                            </div>
                            <p id="zaya-rating-text" class="text-[10px] text-gray-400 mt-2 font-bold italic">Excellent Experience (5/5)</p>
                        </div>

                        <div class="mb-8">
                            <label class="block text-sm font-bold text-secondary mb-3 uppercase tracking-wider text-[10px] opacity-60">Your Feedback</label>
                            <textarea name="message" rows="4" required placeholder="Tell us how Zaya Wellness has helped you on your wellness journey..."
                                class="w-full rounded-2xl border-[#2E4B3D]/12 focus:border-secondary focus:ring-0 text-sm py-4 px-5 transition-all shadow-sm resize-none"></textarea>
                        </div>

                        <button type="submit" class="w-full py-5 bg-primary text-white rounded-[1.5rem] font-black text-sm hover:opacity-95 transition-all shadow-2xl shadow-primary/20 uppercase tracking-[0.2em]">
                            Share My Story
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function switchReviewTab(tab) {
        const panels = ['my-reviews', 'received-reviews'];
        panels.forEach(p => {
            const panel = document.getElementById('panel-' + p);
            const btn = document.getElementById('tab-' + p);
            if (p === tab) {
                panel?.classList.remove('hidden');
                btn?.classList.add('border-secondary', 'text-secondary');
                btn?.classList.remove('border-transparent', 'text-gray-400');
            } else {
                panel?.classList.add('hidden');
                btn?.classList.remove('border-secondary', 'text-secondary');
                btn?.classList.add('border-transparent', 'text-gray-400');
            }
        });
    }

    function openReviewModal() {
        document.getElementById('write-review-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeReviewModal() {
        document.getElementById('write-review-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openZayaReviewModal() {
        document.getElementById('zaya-review-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeZayaReviewModal() {
        document.getElementById('zaya-review-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function setRating(val) {
        document.getElementById('rating-value').value = val;
        const stars = document.querySelectorAll('.rating-star');
        stars.forEach(s => {
            const starVal = parseInt(s.dataset.val);
            if (starVal <= val) {
                s.classList.remove('ri-star-line');
                s.classList.add('ri-star-fill', 'text-[#22C55E]');
            } else {
                s.classList.remove('ri-star-fill', 'text-[#22C55E]');
                s.classList.add('ri-star-line');
            }
        });
    }

    function setZayaRating(val) {
        document.getElementById('zaya-rating-value').value = val;
        const stars = document.querySelectorAll('.zaya-rating-star');
        const text = document.getElementById('zaya-rating-text');
        
        const labels = {
            1: 'Poor',
            2: 'Fair',
            3: 'Good',
            4: 'Very Good',
            5: 'Excellent Experience'
        };

        if (text) text.innerText = `${labels[val]} (${val}/5)`;

        stars.forEach(s => {
            const starVal = parseInt(s.dataset.val);
            if (starVal <= val) {
                s.classList.remove('ri-star-line');
                s.classList.add('ri-star-fill', 'text-[#22C55E]');
            } else {
                s.classList.remove('ri-star-fill', 'text-[#22C55E]');
                s.classList.add('ri-star-line');
            }
        });
    }
</script>
@endsection
