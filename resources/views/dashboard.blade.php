@extends('layouts.client')

@section('title', 'Client Profile')

@section('content')
<!-- Mobile Tab Navigation -->
<div class="lg:hidden flex space-x-6 overflow-x-auto scrollbar-hide mb-5">
    <button onclick="switchMobileTab('dashboard')" id="m-tab-dashboard"
        class="leading-none text-lg text-secondary font-normal whitespace-nowrap cursor-pointer transition-colors border-b-2 border-secondary pb-1">Dashboard</button>
    <button onclick="switchMobileTab('health')" id="m-tab-health"
        class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Health
        Journey</button>
    <a href="{{ route('bookings.index') }}" id="m-tab-bookings"
        class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Bookings</a>
    <button onclick="switchMobileTab('transactions')" id="m-tab-transactions"
        class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Transaction
        Vault</button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-5 md:gap-8 md:mb-8 mb-5">
    <!-- Left Column -->
    <div id="col-left" class="lg:col-span-5 xl:col-span-4 space-y-5 md:space-y-8">
        <!-- Identity Hub -->
        <div id="section-identity" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-medium font-sans! text-secondary">Identity Hub</h2>
                <button class="text-gray-400 hover:text-gray-600"><i class="ri-pencil-line"></i></button>
            </div>

            <div class="grid grid-cols-2 gap-y-6 gap-x-4 mb-6">
                <div>
                    <p class="text-base text-gray-400 mb-1">Age</p>
                    <p class="text-base font-normal text-gray-800">{{ $user->patient->age ?? 'Not set' }} {{ isset($user->patient->age) ? 'Years' : '' }}</p>
                </div>
                <div>
                    <p class="text-base text-gray-400 mb-1">Gender</p>
                    <p class="text-base font-normal text-gray-800">{{ ucfirst($user->patient->gender ?? ($user->gender ?? 'Not set')) }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-base text-gray-400 mb-1">DOB</p>
                    <p class="text-base font-normal text-gray-800">{{ $user->patient->dob ? $user->patient->dob->format('M d, Y') : 'Not set' }}</p>
                </div>
            </div>

            <hr class="border-[#DDDDDD] mb-6">

            <div class="space-y-6">
                <div>
                    <p class="text-base text-gray-400 mb-1">Email</p>
                    <p class="text-base font-normal text-gray-800">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-base text-gray-400 mb-1">Phone</p>
                    <p class="text-base font-normal text-gray-800">{{ $user->patient->phone ?? ($user->phone ?? 'Not set') }}</p>
                </div>
                <div>
                    <p class="text-base text-gray-400 mb-1">Address</p>
                    <p class="text-base font-normal text-gray-800 leading-snug">{{ $user->patient->address ?? ($user->patient->city_state ?? 'Location not set') }}</p>
                </div>
            </div>
        </div>

        <!-- Transaction Vault Snippet -->
        <div id="section-transactions" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12">
            <h2 class="text-xl font-sans! font-medium text-secondary mb-6">Transaction Vault</h2>
            <div class="space-y-5">
                @forelse($invoices as $invoice)
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-normal text-gray-800 mb-0.5">Invoice #{{ $invoice->id + 10000 }}</p>
                        <p class="text-xs text-gray-400">{{ $invoice->created_at->format('M d, Y') }}</p>
                    </div>
                    <a href="{{ $invoice->razorpay_payment_url }}" target="_blank"
                        class="px-3 py-1 bg-[#EEF2EF] text-[#2B4C3B] text-sm font-normal rounded-full">Open</a>
                </div>
                @empty
                <p class="text-center text-gray-500 text-xs py-4">No recent invoices.</p>
                @endforelse
            </div>
            <div class="mt-6 text-center">
                <a href="{{ route('transactions.index') }}" class="text-xs text-gray-400 hover:text-gray-800 font-normal tracking-wide">See
                    all</a>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div id="col-right" class="lg:col-span-7 xl:col-span-8 space-y-5 md:space-y-8">
        <!-- Consultations -->
        <div id="section-consultations" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12">
            <h2 class="text-xl  font-sans! font-medium text-secondary mb-5">Consultations</h2>

            <!-- Tabs -->
            <div class="flex space-x-2 mb-4">
                <button id="tab-upcoming" onclick="switchConsultationTab('upcoming')"
                    class="px-4 py-1.5 w-1/2 text-center bg-[#CFFAD8] text-[#2FA749] text-sm font-normal rounded-lg transition-all cursor-pointer">Upcoming</button>
                <button id="tab-completed" onclick="switchConsultationTab('completed')"
                    class="px-4 py-1.5 w-1/2 text-center bg-[#F9F9F9] text-[#8C8C8C] text-sm font-normal rounded-lg transition-all cursor-pointer">Completed</button>
            </div>

            <!-- Upcoming Sessions -->
            <div id="content-upcoming" class="space-y-6">
                @forelse($upcomingBookings as $booking)
                <div class="flex gap-2 justify-between items-center">
                    <div>
                        <div class="flex flex-wrap items-center space-x-2 mb-1">
                            @php
                            $sNames = [];
                            foreach($booking->service_ids ?? [] as $sid) {
                            if(isset($allServices[$sid])) $sNames[] = $allServices[$sid]->title;
                            }
                            @endphp
                            <p class="text-base font-normal text-gray-800">{{ implode(', ', $sNames) }}</p>
                            <span class="text-gray-800 text-base">•</span>
                            <p class="text-xs text-gray-600 font-normal">(Session with {{ $booking->practitioner->user->name }})</p>
                        </div>
                        <p class="text-xs text-gray-400">{{ $booking->booking_date->format('M d, Y') }} - {{ $booking->booking_time }}</p>
                    </div>
                    {{-- <button
                        class="px-5 py-2 bg-[#D1EBE1] text-[#2B4C3B] hover:bg-[#bce0d2] rounded-full text-xs font-normal transition-colors">Reschedule</button> --}}
                    <span class="px-3 py-1 bg-[#EEF2EF] text-[#2FA749] text-xs font-normal rounded-full capitalize">{{ $booking->status }}</span>
                </div>
                @empty
                <p class="text-center text-gray-500 text-sm py-6">No upcoming sessions.</p>
                @endforelse
            </div>

            <!-- Completed Sessions -->
            <div id="content-completed" class="space-y-6 hidden">
                @forelse($completedBookings as $booking)
                <div class="flex gap-2 justify-between items-center">
                    <div>
                        <div class="flex flex-wrap items-center space-x-2 mb-1">
                            @php
                            $sNames = [];
                            foreach($booking->service_ids ?? [] as $sid) {
                            if(isset($allServices[$sid])) $sNames[] = $allServices[$sid]->title;
                            }
                            @endphp
                            <p class="text-base font-normal text-gray-800">{{ implode(', ', $sNames) }}</p>
                            <span class="text-gray-800 text-base">•</span>
                            <p class="text-xs text-gray-600 font-normal">(Session with {{ $booking->practitioner->user->name }})</p>
                        </div>
                        <p class="text-xs text-gray-400">{{ $booking->booking_date->format('M d, Y') }} - {{ $booking->booking_time }}</p>
                    </div>
                    <span class="px-3 py-1 bg-gray-100 text-gray-600 text-xs font-normal rounded-full capitalize">Completed</span>
                </div>
                @empty
                <p class="text-center text-gray-500 text-sm py-10">No completed sessions recently.</p>
                @endforelse
            </div>
            <a href="{{ route('bookings.index') }}" class="block text-center text-sm font-medium text-secondary hover:underline pt-4">View All Bookings</a>
        </div>

        <!-- Clinical Document Portal -->
        <div id="section-clinical" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12">
            <h2 class="text-xl font-sans! font-medium text-secondary mb-6">Clinical Document Portal</h2>

            <!-- Upload Area -->
            <div
                class="border-2 border-dashed border-[#8FC0A8] rounded-xl p-6 md:p-8 text-center bg-white mb-8">
                <p class="text-lg font-medium text-secondary mb-1.5">Drag and Drop files here</p>
                <p class="text-xs text-gray-500 mb-1 leading-relaxed">Upload X-Rays, MRIs, Blood tests
                    and other clinical documents</p>
                <p class="text-xs text-gray-400 mb-6 leading-relaxed">JPG, JPEG, PNG, WPS, DOC & PDF (Max
                    20MB)</p>
                <button
                    class="inline-flex items-center justify-center px-4 py-2 border border-gray-200 bg-white rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 shadow-sm transition-colors">
                    <i class="ri-upload-2-line mr-2"></i> Upload
                </button>
            </div>

            <!-- Uploaded Documents -->
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-medium font-sans! text-secondary">Uploaded Documents</h3>
                <a href="#"
                    class="text-[11px] text-gray-400 hover:text-gray-700 font-medium tracking-wide">See
                    all</a>
            </div>

            <div class="swiper document-swiper pb-4 w-full">
                <div class="swiper-wrapper">
                    <!-- Doc Card 1 -->
                    <div
                        class="swiper-slide w-[133px]! bg-white px-3 md:px-5 pt-6 md:pt-16 pb-3 md:pb-5 rounded-xl relative flex flex-col items-center justify-center border border-gray-200">
                        <button
                            class="absolute top-1 md:top-2.5 right-1 md:right-2.5 w-8 h-8 bg-red-50 text-red-500 rounded-full flex items-center justify-center hover:bg-red-100 transition-colors"><i
                                class="ri-delete-bin-line text-sm"></i></button>
                        <div
                            class="w-10 h-10 justify-self-center bg-[#E1EAF2] text-[#3E7CB1] flex items-center justify-center rounded-lg mb-3">
                            <i class="ri-file-text-fill text-lg"></i>
                        </div>
                        <p class="text-xs font-semibold text-gray-800 truncate w-full text-center select-none">
                            X-RayJan.DOC</p>
                    </div>
                    <!-- Doc Card 2 -->
                    <div
                        class="swiper-slide w-[133px]! bg-white px-3 md:px-5 pt-6 md:pt-16 pb-3 md:pb-5 rounded-xl relative flex flex-col items-center justify-center border border-gray-200">
                        <button
                            class="absolute top-1 md:top-2.5 right-1 md:right-2.5 w-8 h-8 bg-red-50 text-red-500 rounded-full flex items-center justify-center hover:bg-red-100 transition-colors"><i
                                class="ri-delete-bin-line text-sm"></i></button>
                        <div
                            class="w-10 h-10 justify-self-center bg-[#FEE2E2] text-[#EF4444] flex items-center justify-center rounded-lg mb-3">
                            <i class="ri-file-pdf-fill text-lg"></i>
                        </div>
                        <p class="text-xs font-semibold text-gray-800 truncate w-full text-center select-none">
                            Bloodtst.PDF</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reviews -->
<div id="section-reviews" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12 mb-5 md:mb-8">
    <h2 class="text-xl font-medium font-sans! text-secondary mb-6">Your Reviews</h2>
    <div class="space-y-6">
        @forelse($reviews as $review)
        <div class="border-b border-[#DDDDDD] pb-6">
            <div class="flex items-center space-x-3 mb-3">
                <h3 class="font-sans! text-base font-medium text-gray-800">{{ $review->practitioner->user->name }}</h3>
                <span class="text-xs md:text-sm text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                {{-- <div class="flex items-center gap-3 ml-auto shrink-0">
                    <button
                        class="md:w-10 md:h-10 w-8 h-8 md:text-lg text-sm rounded-full flex items-center justify-center text-[#2B4C3B] hover:bg-gray-50 transition-colors cursor-pointer"><i
                            class="ri-pencil-line"></i></button>
                    <button
                        class="md:w-10 md:h-10 w-8 h-8 md:text-lg text-sm rounded-full flex items-center justify-center bg-red-50 text-red-400 hover:bg-red-100 transition-colors cursor-pointer"><i
                            class="ri-delete-bin-line"></i></button>
                </div> --}}
            </div>
            <div class="flex flex-wrap gap-2 justify-between items-start">
                <div>
                    <p class="text-sm text-gray-600 mb-2.5 leading-relaxed">Comment: "{{ $review->review }}"</p>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-500 mr-3">Rating:</span>
                        <div class="flex text-[#FFD166] space-x-0.5">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <=floor($review->rating))
                                <i class="ri-star-fill text-sm"></i>
                                @elseif($i - 0.5 <= $review->rating)
                                    <i class="ri-star-half-fill text-sm"></i>
                                    @else
                                    <i class="ri-star-line text-sm text-gray-300"></i>
                                    @endif
                                    @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <p class="text-center text-gray-500 text-sm py-6">You haven't written any reviews yet.</p>
        @endforelse
    </div>
</div>

<!-- GDPR Center -->
<div id="section-gdpr"
    class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12 flex flex-col md:flex-row flex-wrap gap-4 items-center justify-between">
    <div class="flex flex-1 items-center space-x-3">
        <i class="ri-shield-check-fill text-secondary text-xl"></i>
        <h2 class="text-sm md:text-lg font-sans! font-medium text-secondary leading-snug">General Data
            Protection Regulation Control
            Center</h2>
    </div>
    <div class="flex flex-1 items-center justify-end space-x-4 lg:border-l lg:border-gray-100 lg:h-8">
        <span class="text-base md:text-lg text-gray-600">Data sharing with Practitioners</span>
        <!-- Toggle Switch -->
        <button
            id="gdpr-toggle"
            onclick="toggleConsent(this)"
            class="w-10 h-5 {{ $user->patient->data_sharing_consent ? 'bg-secondary' : 'bg-gray-300' }} rounded-full relative flex items-center transition-colors cursor-pointer">
            <div
                class="w-4 h-4 bg-white rounded-full absolute left-0.5 shadow-sm transition-transform duration-300 {{ $user->patient->data_sharing_consent ? 'translate-x-5' : '' }}">
            </div>
        </button>
    </div>
</div>

<!-- GDPR Confirmation Modal -->
<div id="gdpr-modal" class="fixed inset-0 z-[100002] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="closeGdprModal()"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-[32px] p-8 md:p-10 max-w-[450px] w-[90%] text-center shadow-[0_20px_50px_rgba(0,0,0,0.1)] transform transition-all duration-300 scale-90">
        <!-- Close Button -->
        <button onclick="closeGdprModal()" class="absolute top-6 right-8 text-gray-300 hover:text-gray-500 transition-colors">
            <i class="ri-close-line text-2xl"></i>
        </button>

        <div class="mb-6">
            <div class="w-16 h-16 bg-[#EEF2EF] rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-shield-user-line text-secondary text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold font-sans! text-secondary mb-2">Update Data Sharing?</h3>
            <p id="gdpr-modal-text" class="text-gray-500 text-sm leading-relaxed">
                Are you sure you want to change your data sharing preferences?
            </p>
        </div>

        <div class="flex gap-4">
            <button onclick="closeGdprModal()" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 rounded-full font-medium hover:bg-gray-50 transition-colors">
                Cancel
            </button>
            <button id="confirm-gdpr-btn" class="flex-1 px-6 py-3 bg-secondary text-white rounded-full font-medium hover:bg-opacity-90 transition-all">
                Confirm
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function switchConsultationTab(tab) {
        const upcomingBtn = document.getElementById('tab-upcoming');
        const completedBtn = document.getElementById('tab-completed');
        const upcomingContent = document.getElementById('content-upcoming');
        const completedContent = document.getElementById('content-completed');

        const activeBtnClasses = 'px-4 py-1.5 w-1/2 text-center bg-[#CFFAD8] text-[#2FA749] text-sm font-normal rounded-lg transition-all cursor-pointer';
        const inactiveBtnClasses = 'px-4 py-1.5 w-1/2 text-center bg-[#F9F9F9] text-[#8C8C8C] text-sm font-normal rounded-lg transition-all cursor-pointer';

        if (tab === 'upcoming') {
            upcomingBtn.className = activeBtnClasses;
            completedBtn.className = inactiveBtnClasses;
            upcomingContent.classList.remove('hidden');
            completedContent.classList.add('hidden');
        } else {
            completedBtn.className = activeBtnClasses;
            upcomingBtn.className = inactiveBtnClasses;
            completedContent.classList.remove('hidden');
            upcomingContent.classList.add('hidden');
        }
    }

    function switchMobileTab(selectedTab) {
        const tabs = ['dashboard', 'health', 'bookings', 'transactions'];
        tabs.forEach(tab => {
            const btn = document.getElementById('m-tab-' + tab);
            if (btn) {
                if (tab === 'dashboard') {
                    btn.className = (tab === selectedTab) ?
                        "leading-none text-lg text-secondary font-normal whitespace-nowrap cursor-pointer transition-colors border-b-2 border-secondary pb-1" :
                        "leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors";
                } else {
                    btn.className = (tab === selectedTab) ?
                        "leading-none text-lg text-secondary font-normal whitespace-nowrap cursor-pointer transition-colors border-b-2 border-secondary pb-1" :
                        "leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors";
                }
            }
        });

        const allSections = [
            'section-identity', 'section-gdpr',
            'section-clinical', 'section-reviews',
            'section-consultations', 'section-transactions',
            'col-left', 'col-right'
        ];

        allSections.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.classList.add('mobile-hidden');
        });

        const tabMap = {
            'dashboard': [
                'section-identity', 'section-gdpr',
                'section-clinical', 'section-reviews',
                'section-consultations', 'section-transactions',
                'col-left', 'col-right'
            ],
            'health': ['section-clinical', 'section-reviews', 'col-right'],
            'transactions': ['section-transactions', 'col-left']
        };

        if (tabMap[selectedTab]) {
            tabMap[selectedTab].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.classList.remove('mobile-hidden');
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Swiper !== 'undefined') {
            new Swiper('.document-swiper', {
                slidesPerView: 'auto',
                spaceBetween: 16,
                grabCursor: true,
                freeMode: true,
            });
        }
    });

    function openGdprModal(callback) {
        const modal = document.getElementById('gdpr-modal');
        const content = modal.querySelector('.relative.bg-white');
        const confirmBtn = document.getElementById('confirm-gdpr-btn');

        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100');

        setTimeout(() => {
            content.classList.remove('scale-90');
            content.classList.add('scale-100');
        }, 10);

        confirmBtn.onclick = () => {
            callback();
            closeGdprModal();
        };
    }

    function closeGdprModal() {
        const modal = document.getElementById('gdpr-modal');
        const content = modal.querySelector('.relative.bg-white');

        content.classList.remove('scale-100');
        content.classList.add('scale-90');

        setTimeout(() => {
            modal.classList.add('opacity-0', 'pointer-events-none');
            modal.classList.remove('opacity-100');
        }, 300);
    }

    function toggleConsent(btn) {
        const dot = btn.querySelector('div');
        const isCurrentlyActive = btn.classList.contains('bg-secondary');
        const newState = !isCurrentlyActive;

        const actionText = newState ?
            "By enabling this, your health records and clinical documents will be accessible to practitioners during consultations." :
            "By disabling this, practitioners will no longer have access to your health records and clinical documents.";

        document.getElementById('gdpr-modal-text').innerText = actionText;

        openGdprModal(() => {
            // Optimistic UI update
            if (newState) {
                btn.classList.remove('bg-gray-300');
                btn.classList.add('bg-secondary');
                dot.classList.add('translate-x-5');
            } else {
                btn.classList.remove('bg-secondary');
                btn.classList.add('bg-gray-300');
                dot.classList.remove('translate-x-5');
            }

            fetch("{{ route('profile.updateConsent') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        consent: newState
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                })
                .catch((error) => {
                    console.error('Error:', error);
                    // Revert UI on error
                    if (isCurrentlyActive) {
                        btn.classList.remove('bg-gray-300');
                        btn.classList.add('bg-secondary');
                        dot.classList.add('translate-x-5');
                    } else {
                        btn.classList.remove('bg-secondary');
                        btn.classList.add('bg-gray-300');
                        dot.classList.remove('translate-x-5');
                    }
                });
        });
    }
</script>
@endsection