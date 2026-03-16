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
                    <p class="text-base font-normal text-gray-800">28 Years</p>
                </div>
                <div>
                    <p class="text-base text-gray-400 mb-1">Gender</p>
                    <p class="text-base font-normal text-gray-800">Female</p>
                </div>
                <div class="col-span-2">
                    <p class="text-base text-gray-400 mb-1">DOB</p>
                    <p class="text-base font-normal text-gray-800">May 01, 1998</p>
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
                    <p class="text-base font-normal text-gray-800">{{ $user->phone ?? '+44 7700 800077' }}</p>
                </div>
                <div>
                    <p class="text-base text-gray-400 mb-1">Address</p>
                    <p class="text-base font-normal text-gray-800 leading-snug">{{ $user->city ?? 'London' }}, UK.</p>
                </div>
            </div>
        </div>

        <!-- Transaction Vault Snippet -->
        <div id="section-transactions" class="bg-white rounded-2xl p-5 md:p-6 border border-[#2E4B3D]/12">
            <h2 class="text-xl font-sans! font-medium text-secondary mb-6">Transaction Vault</h2>
            <div class="space-y-5">
                <!-- Invoice 1 -->
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-normal text-gray-800 mb-0.5">Invoice #88751</p>
                        <p class="text-xs text-gray-400">Dec 7, 2025</p>
                    </div>
                    <span
                        class="px-3 py-1 bg-[#EEF2EF] text-[#2B4C3B] text-sm font-normal rounded-full">Open</span>
                </div>
                <!-- Invoice 2 -->
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-normal text-gray-800 mb-0.5">Invoice #13742</p>
                        <p class="text-xs text-gray-400">Nov 28, 2025</p>
                    </div>
                    <span
                        class="px-3 py-1 bg-[#EEF2EF] text-[#2B4C3B] text-sm font-normal rounded-full">Open</span>
                </div>
                <!-- Invoice 3 -->
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-normal text-gray-800 mb-0.5">Invoice #70159</p>
                        <p class="text-xs text-gray-400">Feb 17, 2025</p>
                    </div>
                    <span
                        class="px-3 py-1 bg-[#EEF2EF] text-[#2B4C3B] text-sm font-normal rounded-full">Open</span>
                </div>
            </div>
            <div class="mt-6 text-center">
                <a href="#" class="text-xs text-gray-400 hover:text-gray-800 font-normal tracking-wide">See
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
                <!-- Session 1 -->
                <div class="flex gap-2 justify-between items-center">
                    <div>
                        <div class="flex flex-wrap items-center space-x-2 mb-1">
                            <p class="text-base font-normal text-gray-800">Life Coach</p>
                            <span class="text-gray-800 text-base">•</span>
                            <p class="text-xs text-gray-600 font-normal">(Session with Dr. Evelyn Reed)</p>
                        </div>
                        <p class="text-xs text-gray-400">Mar 07, 2026 - 11:30 AM</p>
                    </div>
                    <button
                        class="px-5 py-2 bg-[#D1EBE1] text-[#2B4C3B] hover:bg-[#bce0d2] rounded-full text-xs font-normal transition-colors">Reschedule</button>
                </div>
                <!-- Session 2 -->
                <div class="flex gap-2 justify-between items-center">
                    <div>
                        <div class="flex flex-wrap items-center space-x-2 mb-1">
                            <p class="text-base font-normal text-gray-800">Naturopathy</p>
                            <span class="text-gray-800 text-base">•</span>
                            <p class="text-xs text-gray-600 font-normal">(Session with Dr. Nahala Nazim)</p>
                        </div>
                        <p class="text-xs text-gray-400">Mar 28, 2026 - 5:30 PM</p>
                    </div>
                    <button
                        class="px-5 py-2 bg-[#D1EBE1] text-[#2B4C3B] hover:bg-[#bce0d2] rounded-full text-xs font-normal transition-colors">Reschedule</button>
                </div>
            </div>

            <!-- Completed Sessions -->
            <div id="content-completed" class="space-y-6 hidden">
                <p class="text-center text-gray-500 text-sm py-10">No completed sessions recently.</p>
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
        <!-- Review 1 -->
        <div class="border-b border-[#DDDDDD] pb-6">
            <div class="flex items-center space-x-3 mb-3">
                <h3 class="font-sans! text-base font-medium text-gray-800">Art Therapy</h3>
                <span class="text-xs md:text-sm text-gray-400">Just now</span>
                <div class="flex items-center gap-3 ml-auto shrink-0">
                    <button
                        class="md:w-10 md:h-10 w-8 h-8 md:text-lg text-sm rounded-full flex items-center justify-center text-[#2B4C3B] hover:bg-gray-50 transition-colors cursor-pointer"><i
                            class="ri-pencil-line"></i></button>
                    <button
                        class="md:w-10 md:h-10 w-8 h-8 md:text-lg text-sm rounded-full flex items-center justify-center bg-red-50 text-red-400 hover:bg-red-100 transition-colors cursor-pointer"><i
                            class="ri-delete-bin-line"></i></button>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 justify-between items-start">
                <div>
                    <p class="text-sm text-gray-600 mb-2.5 leading-relaxed">Comment: "Dr. Bennett was
                        incredibly attentive and provided excellent care."</p>
                    <div class="flex items-center">
                        <span class="text-sm text-gray-500 mr-3">Rating:</span>
                        <div class="flex text-[#FFD166] space-x-0.5">
                            <i class="ri-star-fill text-sm"></i>
                            <i class="ri-star-fill text-sm"></i>
                            <i class="ri-star-fill text-sm"></i>
                            <i class="ri-star-fill text-sm"></i>
                            <i class="ri-star-half-fill text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
            onclick="this.classList.toggle('bg-secondary'); this.classList.toggle('bg-gray-300'); this.children[0].classList.toggle('translate-x-5')"
            class="w-10 h-5 bg-gray-300 rounded-full relative flex items-center transition-colors cursor-pointer">
            <div
                class="w-4 h-4 bg-white rounded-full absolute left-0.5 shadow-sm transition-transform duration-300">
            </div>
        </button>
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
                     btn.className = (tab === selectedTab)
                        ? "leading-none text-lg text-secondary font-normal whitespace-nowrap cursor-pointer transition-colors border-b-2 border-secondary pb-1"
                        : "leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors";
                } else {
                    btn.className = (tab === selectedTab)
                        ? "leading-none text-lg text-secondary font-normal whitespace-nowrap cursor-pointer transition-colors border-b-2 border-secondary pb-1"
                        : "leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors";
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

    document.addEventListener('DOMContentLoaded', function () {
        if (typeof Swiper !== 'undefined') {
            new Swiper('.document-swiper', {
                slidesPerView: 'auto',
                spaceBetween: 16,
                grabCursor: true,
                freeMode: true,
            });
        }
    });
</script>
@endsection
