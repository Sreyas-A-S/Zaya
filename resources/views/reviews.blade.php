@extends('layouts.client')

@section('title', 'Reviews')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <h1 class="text-3xl font-black text-secondary tracking-tight">Reviews</h1>
            <p class="text-gray-500 font-medium mt-1">Manage your feedback and see what others are saying.</p>
        </div>
        @if($reviewablePractitioners->count() > 0)
        <button onclick="openReviewModal()" class="inline-flex items-center justify-center px-8 py-4 bg-secondary text-white rounded-[1.5rem] font-black text-sm hover:bg-primary transition-all shadow-2xl shadow-secondary/30 uppercase tracking-[0.2em]">
            <i class="ri-edit-line mr-2"></i> Write a Review
        </button>
        @endif
    </div>

    <!-- Tabs -->
    <div class="flex border-b border-[#2E4B3D]/12 mb-8 overflow-x-auto no-scrollbar">
        <button onclick="switchReviewTab('my-reviews')" id="tab-my-reviews" class="px-8 py-4 text-xs font-black uppercase tracking-[0.2em] border-b-2 border-secondary text-secondary transition-all">
            My Reviews
        </button>
        @if($receivedReviews->count() > 0 || in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']))
        <button onclick="switchReviewTab('received-reviews')" id="tab-received-reviews" class="px-8 py-4 text-xs font-black uppercase tracking-[0.2em] border-b-2 border-transparent text-gray-400 hover:text-secondary transition-all">
            Received Reviews
        </button>
        @endif
    </div>

    <!-- My Reviews Panel -->
    <div id="panel-my-reviews" class="space-y-6">
        @forelse($myReviews as $review)
        <div class="bg-white rounded-[2rem] border border-[#2E4B3D]/12 p-8 shadow-sm group hover:border-secondary/20 transition-all">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-4">
                    <img src="{{ $review->practitioner->profile_photo_path ? asset('storage/' . $review->practitioner->profile_photo_path) : asset('frontend/assets/profile-dummy-img.png') }}" 
                         class="w-16 h-16 rounded-2xl object-cover border border-gray-100">
                    <div>
                        <h3 class="font-black text-secondary leading-tight">{{ $review->practitioner->user->name }}</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">{{ str_replace('_', ' ', $review->practitioner->user->role) }}</p>
                    </div>
                </div>
                <div class="flex flex-col items-start md:items-end">
                    <div class="flex text-[#FABD4D] gap-1 text-xl mb-1">
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
    </div>

    <!-- Received Reviews Panel -->
    <div id="panel-received-reviews" class="hidden space-y-6">
        @forelse($receivedReviews as $review)
        <div class="bg-white rounded-[2rem] border border-[#2E4B3D]/12 p-8 shadow-sm group hover:border-secondary/20 transition-all">
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
                    <div class="flex text-[#FABD4D] gap-1 text-xl mb-1">
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
                                <i class="ri-star-fill text-[#FABD4D] cursor-pointer rating-star" data-val="{{ $i }}" onclick="setRating({{ $i }})"></i>
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

    function setRating(val) {
        document.getElementById('rating-value').value = val;
        const stars = document.querySelectorAll('.rating-star');
        stars.forEach(s => {
            const starVal = parseInt(s.dataset.val);
            if (starVal <= val) {
                s.classList.remove('ri-star-line');
                s.classList.add('ri-star-fill', 'text-[#FABD4D]');
            } else {
                s.classList.remove('ri-star-fill', 'text-[#FABD4D]');
                s.classList.add('ri-star-line');
            }
        });
    }
</script>
@endsection
