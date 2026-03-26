@extends('layouts.client')

@section('title', 'My Services')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .tab-pill { padding: 10px 18px; border-radius: 9999px; font-weight: 800; font-size: 14px; border: 1px solid transparent; transition: all .2s ease; }
    .tab-pill.active { background: #1e3a2f; color: #fff; border-color: #1e3a2f; box-shadow: 0 12px 30px -18px #1e3a2f; }
    .tab-pill.inactive { background: #F4F6F5; color: #6B7280; border-color: #e5e7eb; }

    /* Select2 Custom Styling */
    .select2-container--default .select2-selection--single {
        height: 52px;
        border: 1px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 12px 14px;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 52px;
        right: 12px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        padding-left: 4px;
        color: #111827;
        font-weight: 700;
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--focus .select2-selection--single {
        outline: none;
        border-color: #2E4B3D;
        box-shadow: 0 0 0 2px rgba(46, 75, 61, 0.15);
    }
    .select2-dropdown {
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        box-shadow: 0 14px 30px -18px rgba(0,0,0,0.35);
        overflow: hidden;
        background: #ffffff;
        padding: 4px 0;
    }
    .select2-results__option {
        padding: 10px 14px;
        font-weight: 700;
        color: #1f2937;
    }
    .select2-results__option--highlighted {
        background: #F3F6F4;
        color: #1f2937;
    }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    /* Inline loader for select fetch */
    .inline-loader {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        border: 2px solid #d1d5db;
        border-top-color: #2E4B3D;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }
    .select-wrapper {
        position: relative;
    }
    @keyframes spin {
        to { transform: translateY(-50%) rotate(360deg); }
    }

    /* Custom visual tweaks for better contrast */
    .service-card-header {
        background: linear-gradient(180deg, rgba(46, 75, 61, 0.05) 0%, rgba(46, 75, 61, 0) 100%);
    }
</style>
@endpush

@section('content')
@php
    $practitionerRoles = ['practitioner', 'doctor', 'mindfulness_practitioner', 'mindfulness-practitioner', 'yoga_therapist', 'yoga-therapist', 'translator'];
    $isPractitioner = in_array(auth()->user()->role, $practitionerRoles);
@endphp

@if(!$isPractitioner)
<div class="flex flex-col items-center justify-center py-24 bg-white rounded-[40px] border-2 border-dashed border-[#2E4B3D]/10 text-center px-8 shadow-sm">
    <div class="w-28 h-28 bg-[#F8FAF9] rounded-full flex items-center justify-center mb-8 shadow-inner">
        <i class="ri-shield-user-line text-6xl text-[#2E4B3D]/20"></i>
    </div>
    <h3 class="text-3xl font-black text-secondary mb-3 tracking-tight">Access Restricted</h3>
    <p class="text-gray-500 font-medium max-w-md mb-10 leading-relaxed text-lg">
        This page is exclusively for Practitioners and Therapists to manage their professional offerings. 
        It appears you are not registered with a practitioner role.
    </p>
    <a href="{{ route('dashboard') }}" class="bg-secondary text-white px-10 py-4 rounded-2xl font-black hover:bg-opacity-95 transform hover:-translate-y-1 transition-all shadow-xl shadow-secondary/20">
        Return to Dashboard
    </a>
</div>
@else
<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-white rounded-2xl px-6 py-8 sm:px-8 sm:py-10 border border-[#2E4B3D]/12 shadow-sm gap-6">
        <div>
            <h2 class="text-2xl sm:text-3xl font-black text-secondary mb-1 sm:mb-2 tracking-tight">My Services</h2>
            <p class="text-gray-500 text-sm sm:text-base font-medium">Manage your professional offerings and tiered pricing.</p>
        </div>
        <button onclick="openAddServiceModal()" class="w-full sm:w-auto bg-secondary text-white px-6 py-3.5 sm:px-8 sm:py-4 rounded-xl sm:rounded-2xl font-bold hover:bg-opacity-95 transform hover:-translate-y-0.5 transition-all flex items-center justify-center gap-3 shadow-xl shadow-secondary/25">
            <i class="ri-add-fill text-xl sm:text-2xl"></i>
            <span class="text-base sm:text-lg">Add New Service</span>
        </button>
    </div>

    @if(session('status'))
        <div id="status-alert" class="px-6 py-4 bg-[#F0FDF4] border border-green-200 text-[#166534] rounded-2xl flex items-center shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
            <div class="w-10 h-10 bg-green-500/10 rounded-full flex items-center justify-center mr-4">
                <i class="ri-checkbox-circle-fill text-2xl text-green-600"></i>
            </div>
            <span class="font-bold">{{ session('status') }}</span>
        </div>
        <script>
            setTimeout(() => {
                const alert = document.getElementById('status-alert');
                if (alert) {
                    alert.classList.add('opacity-0', 'translate-y-2');
                    setTimeout(() => alert.remove(), 300);
                }
            }, 3000);
        </script>
    @endif

    <div class="flex gap-3 mt-2 mb-2">
        <button id="tab-services-btn" class="tab-pill active" onclick="switchMyServicesTab('services')">Services</button>
        <button id="tab-video-btn" class="tab-pill inactive" onclick="switchMyServicesTab('video')">Video Link & Reminder</button>
    </div>

    <div id="services-tab">
    <!-- Services Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        @forelse($myServices as $serviceId => $rates)
            @php $firstRate = $rates->first(); @endphp
            <div class="bg-white rounded-[32px] border border-[#2E4B3D]/12 overflow-hidden hover:shadow-2xl hover:shadow-secondary/5 transition-all duration-500 group flex flex-col h-full">
                <!-- Card Header -->
                @php
                    $serviceImage = $firstRate->service->image ? asset('storage/' . $firstRate->service->image) : asset('frontend/assets/service-placeholder.png');
                    $placeholderImage = asset('frontend/assets/service-placeholder.png');
                @endphp
                <div class="h-44 relative flex-shrink-0">
                    <img src="{{ $serviceImage }}" 
                         alt="{{ $firstRate->service->title }}" 
                         class="w-full h-full object-cover"
                         onerror="this.onerror=null;this.src='{{ $placeholderImage }}';">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#1A1A1A]/90 via-[#1A1A1A]/30 to-transparent"></div>
                    <div class="absolute bottom-5 left-7 right-14">
                        <h3 class="text-white text-2xl font-black leading-tight tracking-tight">{{ $firstRate->service->title }}</h3>
                    </div>
                    <button onclick="confirmDeleteService({{ $serviceId }}, '{{ addslashes($firstRate->service->title) }}')" 
                            class="absolute top-5 right-5 w-11 h-11 bg-white/10 backdrop-blur-xl text-white rounded-2xl flex items-center justify-center hover:bg-red-500 hover:scale-110 transition-all duration-300 shadow-lg border border-white/20" 
                            title="Remove Entire Service">
                        <i class="ri-delete-bin-fill text-xl"></i>
                    </button>
                </div>
                
                <!-- Card Body -->
                <div class="p-7 flex-grow flex flex-col">
                    <div class="space-y-4 flex-grow">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-1 h-4 bg-secondary rounded-full"></div>
                            <p class="text-[11px] font-black text-secondary/40 uppercase tracking-[0.2em]">Pricing Tiers</p>
                        </div>
                        
                        @foreach($rates as $rate)
                            <div class="flex items-center justify-between bg-[#F8FAF9] p-4 rounded-2xl border border-[#2E4B3D]/5 group/rate hover:bg-white hover:border-secondary/30 hover:shadow-md transition-all duration-300">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center text-secondary">
                                        <i class="ri-time-fill text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider mb-0.5">{{ $rate->duration }} Minutes</p>
                                        <p class="text-lg font-black text-secondary tracking-tight">₹{{ number_format($rate->rate, 2) }}</p>
                                    </div>
                                </div>
                                <button onclick="confirmDeleteRate({{ $rate->id }}, '{{ addslashes($firstRate->service->title) }}', {{ $rate->duration }})" 
                                        class="w-9 h-9 rounded-xl text-gray-300 hover:text-red-500 hover:bg-red-50 transition-all opacity-0 group-hover/rate:opacity-100 transform group-hover/rate:translate-x-0 translate-x-2">
                                    <i class="ri-close-circle-fill text-2xl"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Card Footer -->
                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-center">
                        <div class="flex items-center gap-2.5 px-4 py-2 bg-secondary/5 rounded-2xl border border-secondary/10">
                            <div class="w-2 h-2 rounded-full bg-secondary animate-pulse"></div>
                            <span class="text-[11px] font-black text-secondary uppercase tracking-wider">
                                {{ $rates->count() }} {{ Str::plural('Rate', $rates->count()) }} Active
                            </span>
                        </div>
                        <button onclick="manageService({{ $serviceId }})" class="text-secondary font-black text-sm hover:underline flex items-center gap-2 transition-all">
                            <i class="ri-settings-3-fill"></i> Manage Details
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 bg-white rounded-[40px] border-2 border-dashed border-[#2E4B3D]/10 flex flex-col items-center justify-center text-center px-8 shadow-sm">
                <div class="w-28 h-28 bg-[#F8FAF9] rounded-full flex items-center justify-center mb-8 shadow-inner">
                    <i class="ri-service-fill text-6xl text-[#2E4B3D]/20"></i>
                </div>
                <h3 class="text-3xl font-black text-secondary mb-3 tracking-tight">No Services Yet</h3>
                <p class="text-gray-500 font-medium max-w-md mb-10 leading-relaxed text-lg">Your service catalogue is empty. Start by adding the services you offer to clients.</p>
                <button onclick="openAddServiceModal()" class="bg-secondary text-white px-10 py-4 rounded-2xl font-black hover:bg-opacity-95 transform hover:-translate-y-1 transition-all shadow-xl shadow-secondary/20">
                    Add Your First Service
                </button>
            </div>
        @endforelse
    </div>
    </div>

    <div id="video-tab" class="hidden space-y-6">
        <div class="bg-white rounded-[28px] border border-[#2E4B3D]/12 shadow-sm p-6">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-[11px] font-black text-secondary/50 uppercase tracking-[0.2em] mb-1">Next Online Session</p>
                    @php
                        $agoraLink = $nextOnlineBooking ? route('conference.join', ['channel' => $nextOnlineBooking->invoice_no]) : null;
                    @endphp
                    @if($nextOnlineBooking)
                        <h3 class="text-xl font-black text-secondary mb-1">{{ $nextOnlineBooking->booking_date?->format('M d, Y') }} &middot; {{ $nextOnlineBooking->booking_time }}</h3>
                        <p class="text-sm text-gray-500 mb-3">Client: {{ $nextOnlineBooking->user->name ?? 'Client' }} · Invoice: {{ $nextOnlineBooking->invoice_no }}</p>
                        <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
                            <span class="px-4 py-2 rounded-full bg-[#F3F6F4] text-secondary text-sm font-semibold break-all">{{ $agoraLink }}</span>
                            <button type="button" onclick="copyAgoraLink('{{ $agoraLink }}')" class="px-4 py-2 bg-secondary text-white rounded-full text-sm font-bold hover:bg-opacity-95 shadow-md flex items-center gap-2">
                                <i class="ri-file-copy-line"></i> Copy Link
                            </button>
                        </div>
                    @else
                        <h3 class="text-xl font-black text-secondary mb-1">No upcoming online sessions</h3>
                        <p class="text-sm text-gray-500">Links are generated per confirmed online booking and emailed automatically.</p>
                    @endif
                </div>
                <div class="px-4 py-2 rounded-xl bg-amber-50 text-amber-700 text-xs font-bold uppercase tracking-wide">
                    Auto email sends 60 min before
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[28px] border border-[#2E4B3D]/12 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-[11px] font-black text-secondary/50 uppercase tracking-[0.2em] mb-1">Reminder Settings</p>
                    <h3 class="text-xl font-black text-secondary">Video link reminder timing</h3>
                    <p class="text-sm text-gray-500 mt-1">The system always emails the Agora session link to you and the client 60 minutes before an online booking.</p>
                </div>
            </div>
            <div class="max-w-xl">
                <div class="flex flex-col sm:flex-row gap-4 items-center">
                    <div class="relative w-full sm:w-60">
                        <input type="number" value="60" disabled
                               class="w-full border border-gray-200 rounded-2xl px-4 py-3 font-bold text-secondary bg-gray-50 cursor-not-allowed outline-none">
                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-bold uppercase">Minutes</span>
                    </div>
                    <span class="text-sm text-gray-500 font-medium">Fixed default reminder time</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Add Service Modal -->
<div id="addServiceModal" class="fixed inset-0 bg-[#1A1A1A]/60 backdrop-blur-md hidden z-50 flex items-center justify-center p-2 sm:p-4">
    <div class="bg-white rounded-3xl sm:rounded-[40px] w-full max-w-3xl overflow-hidden shadow-2xl scale-95 opacity-0 transition-all duration-300" id="addModalContent">
        <div class="px-6 py-6 sm:px-10 sm:py-8 border-b border-gray-100 flex justify-between items-center bg-[#F8FAF9]">
            <div>
                <h3 class="text-xl sm:text-2xl font-black text-secondary tracking-tight" id="modal-title">Configure Services</h3>
                <p class="text-gray-500 text-xs sm:text-sm font-medium">Add multiple services and pricing tiers</p>
            </div>
            <button onclick="closeAddServiceModal()" class="w-10 h-10 sm:w-12 sm:h-12 bg-white rounded-xl sm:rounded-2xl text-gray-400 hover:text-secondary hover:shadow-md transition-all flex items-center justify-center border border-gray-100">
                <i class="ri-close-line text-2xl sm:text-3xl"></i>
            </button>
        </div>
        
        <form action="{{ route('my-services.store') }}" method="POST" class="px-6 py-6 sm:px-10 sm:py-10">
            @csrf
            <div id="services-container" class="space-y-6 sm:space-y-10 max-h-[65vh] overflow-y-auto pr-2 sm:pr-4 scrollbar-hide">
                <!-- Initial Row -->
                <div class="service-row bg-[#F0F4F2] p-5 sm:p-8 rounded-2xl sm:rounded-[32px] border border-[#2E4B3D]/10 relative group shadow-sm">
                    <div class="space-y-6 sm:space-y-8">
                        <div>
                            <label class="flex items-center gap-2 text-xs sm:text-sm font-black text-secondary mb-2 sm:mb-3 uppercase tracking-wider">
                                <i class="ri-apps-2-fill text-secondary"></i>
                                Select Service
                            </label>
                            <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-200 select-wrapper">
                                <select id="service-select-0" name="services[0][service_id]" required class="service-selector w-full">
                                    <option value="" disabled selected>Loading services...</option>
                                </select>
                            </div>
                        </div>
                        
                        <!-- Rates Container -->
                        <div class="space-y-4 sm:space-y-5">
                            <label class="flex items-center gap-2 text-[10px] sm:text-[11px] font-black text-secondary/50 uppercase tracking-[0.2em]">
                                <i class="ri-price-tag-3-fill"></i>
                                Pricing Tiers
                            </label>
                            <div class="rates-container space-y-3 sm:space-y-4">
                                <div class="rate-row grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 items-end bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl border border-[#2E4B3D]/5 shadow-sm">
                                    <div>
                                        <label class="block text-[10px] sm:text-xs font-bold text-gray-500 mb-1 sm:mb-2 uppercase tracking-wide">Duration</label>
                                        <div class="relative">
                                            <i class="ri-time-fill absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-secondary/40 text-base sm:text-lg"></i>
                                            <input type="number" name="services[0][rates][0][duration]" required placeholder="60" class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3.5 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-4 focus:ring-secondary/5 focus:border-secondary transition-all outline-none font-bold text-secondary text-sm sm:text-base">
                                            <span class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] sm:text-xs font-bold uppercase">Min</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] sm:text-xs font-bold text-gray-500 mb-1 sm:mb-2 uppercase tracking-wide">Rate</label>
                                        <div class="flex gap-2 sm:gap-3">
                                            <select name="services[0][currency]" class="currency-select h-[54px] sm:h-[58px] bg-gray-100 border border-gray-200 rounded-lg px-3 text-sm font-semibold text-gray-700 focus:border-secondary focus:ring-2 focus:ring-secondary/10" disabled>
                                                @php $curr = $defaultCurrency ?? 'INR'; @endphp
                                                <option value="INR" {{ $curr === 'INR' ? 'selected' : '' }}>INR</option>
                                                <option value="USD" {{ $curr === 'USD' ? 'selected' : '' }}>USD</option>
                                                <option value="EUR" {{ $curr === 'EUR' ? 'selected' : '' }}>EUR</option>
                                                <option value="GBP" {{ $curr === 'GBP' ? 'selected' : '' }}>GBP</option>
                                                <option value="AED" {{ $curr === 'AED' ? 'selected' : '' }}>AED</option>
                                            </select>
                                            <div class="relative flex-1">
                                                <span class="currency-symbol absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-secondary/40 font-black text-base sm:text-lg">₹</span>
                                                <input type="number" name="services[0][rates][0][rate]" step="0.01" required placeholder="0.00" class="w-full pl-8 sm:pl-10 pr-4 py-2.5 sm:py-3.5 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-4 focus:ring-secondary/5 focus:border-secondary transition-all outline-none font-bold text-secondary text-base sm:text-lg">
                                            </div>
                                            <button type="button" class="w-12 h-11 sm:w-14 sm:h-[54px] rounded-lg sm:rounded-xl bg-gray-50 text-gray-300 flex items-center justify-center cursor-not-allowed border border-gray-100" disabled>
                                                <i class="ri-delete-bin-fill text-lg sm:text-xl"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="addRateRow(0, this)" class="w-full sm:w-auto bg-white border-2 border-dashed border-secondary/20 text-secondary px-4 py-2.5 sm:px-6 sm:py-3 rounded-lg sm:rounded-xl font-black text-xs sm:text-sm flex items-center justify-center gap-2 hover:bg-secondary hover:text-white hover:border-secondary transition-all duration-300 shadow-sm">
                                <i class="ri-add-line text-lg"></i>
                                Add Another Duration
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="mt-6 sm:mt-10 flex flex-col sm:flex-row gap-3 sm:gap-4 pt-6 sm:pt-8 border-t border-gray-100">
                <button type="button" onclick="addServiceRow()" class="px-6 py-3.5 sm:py-4 bg-white border-2 border-secondary text-secondary font-black rounded-xl sm:rounded-2xl hover:bg-secondary hover:text-white transition-all flex items-center justify-center gap-2 sm:gap-3 shadow-lg shadow-secondary/5 text-sm sm:text-base">
                    <i class="ri-add-circle-fill text-xl"></i>
                    Another Service
                </button>
                <button type="submit" class="px-8 py-3.5 sm:py-4 bg-secondary text-white font-black rounded-xl sm:rounded-2xl hover:bg-opacity-95 transform hover:-translate-y-0.5 transition-all shadow-2xl shadow-secondary/30 text-base sm:text-lg tracking-tight">
                    Save All Services
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="fixed inset-0 bg-[#1A1A1A]/40 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-[32px] w-full max-w-[320px] overflow-hidden shadow-2xl scale-95 opacity-0 transition-all duration-200" id="confirmModalContent">
        <div class="p-6 text-center">
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm border border-red-100">
                <i class="ri-error-warning-fill text-3xl" id="confirmIcon"></i>
            </div>
            <h3 class="text-xl font-black text-secondary mb-2 tracking-tight" id="confirmTitle">Remove?</h3>
            <p class="text-gray-500 mb-6 leading-tight font-medium text-sm px-2" id="confirmMessage">Are you sure you want to remove this item?</p>
            
            <form id="deleteForm" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
            
            <div class="flex flex-col gap-2">
                <button type="button" id="confirmActionButton" class="w-full py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition-all text-sm shadow-lg shadow-red-200">Confirm Removal</button>
                <button type="button" onclick="closeDeleteModal()" class="w-full py-3 bg-gray-50 text-gray-500 font-bold rounded-xl hover:bg-gray-100 transition-all text-sm">Cancel</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const DEFAULT_CURRENCY = "{{ $defaultCurrency ?? 'INR' }}";
</script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    let serviceRowIndex = 1;
    let select2Instances = {};
    const CURRENCY_SYMBOLS = {INR: '₹', USD: '$', EUR: '€', GBP: '£', AED: 'د.إ'};
    let cachedAvailableServices = null;
    let isFetchingServices = false;
    let fetchPromise = null;

async function fetchAvailableServices() {
        if (cachedAvailableServices) return cachedAvailableServices;
        if (isFetchingServices) return fetchPromise;
        
        isFetchingServices = true;
        fetchPromise = (async () => {
            try {
                const response = await fetch("{{ route('api.available-services') }}");
                const res = await response.json();
                if (res.status) {
                    cachedAvailableServices = res.data;
                    const modalTitle = document.getElementById('modal-title');
                    if (modalTitle) {
                        modalTitle.innerText = `Configure Services`;
                    }
                    return cachedAvailableServices;
                }
            } catch (error) {
                console.error('Error fetching services:', error);
            } finally {
                isFetchingServices = false;
            }
            return [];
        })();
        
        return fetchPromise;
    }

    function toggleSelectLoader(el, show) {
        const wrapper = el.closest('.select-wrapper');
        if (!wrapper) return;
        let spinner = wrapper.querySelector('.inline-loader');
        if (show) {
            if (!spinner) {
                spinner = document.createElement('span');
                spinner.className = 'inline-loader';
                wrapper.appendChild(spinner);
            }
        } else if (spinner) {
            spinner.remove();
        }
    }

    function getSelectedServiceIds(excludeId = null) {
        const selects = document.querySelectorAll('.service-selector');
        const ids = [];
        selects.forEach(sel => {
            if (excludeId && sel.id === excludeId) return;
            const val = $(sel).val();
            if (val) ids.push(String(val));
        });
        return ids;
    }

    async function initSelect2(elementId) {
        const el = document.getElementById(elementId);
        if (!el) return;

        toggleSelectLoader(el, true);
        const services = await fetchAvailableServices();

        // destroy existing instance if any
        if (select2Instances[elementId]) {
            $(el).select2('destroy');
        }

        const selectedIds = getSelectedServiceIds(elementId);
        const data = services
            .filter(s => !selectedIds.includes(String(s.id)) || String(s.id) === String(el.value))
            .map(s => ({ id: s.id, text: s.title }));

        select2Instances[elementId] = $(el).select2({
            data,
            placeholder: 'Choose a service...',
            width: '100%',
            dropdownParent: $('#addServiceModal'),
        });
        $(el).on('change', () => refreshAllSelects(elementId));
        toggleSelectLoader(el, false);
    }

    async function refreshAllSelects(changedId = null) {
        const selects = document.querySelectorAll('.service-selector');
        for (const sel of selects) {
            const id = sel.id;
            await initSelect2(id);
        }
    }

    function attachCurrencyListeners(context = document) {
        const selects = context.querySelectorAll('.currency-select');
        selects.forEach(select => {
            const handler = () => {
                const sym = CURRENCY_SYMBOLS[select.value] || select.value;
                const row = select.closest('.rate-row') || select.closest('.service-row');
                const span = row ? row.querySelector('.currency-symbol') : null;
                if (span) span.textContent = sym;
            };
            select.addEventListener('change', handler);
            // set default selection when creating new rows
            if (!select.dataset.boundDefault) {
                select.value = DEFAULT_CURRENCY;
                select.dataset.boundDefault = '1';
            }
            handler();
        });
    }

    async function addServiceRow() {
        const container = document.getElementById('services-container');
        const id = `service-select-${serviceRowIndex}`;
        const currentIdx = serviceRowIndex;
        
        const row = document.createElement('div');
        row.className = 'service-row bg-[#F0F4F2] p-5 sm:p-8 rounded-2xl sm:rounded-[32px] border border-[#2E4B3D]/10 relative group animate-in fade-in slide-in-from-top-6 duration-500 shadow-sm mb-6 sm:mb-10';
        row.innerHTML = `
            <button type="button" onclick="confirmRemoveServiceRow(this)" class="absolute -top-3 -right-3 sm:-top-4 sm:-right-4 w-10 h-10 sm:w-12 sm:h-12 bg-white text-red-500 rounded-xl sm:rounded-2xl flex items-center justify-center hover:bg-red-500 hover:text-white transition-all duration-300 shadow-xl border border-gray-100 group-hover:scale-110">
                <i class="ri-delete-bin-fill text-lg sm:text-xl"></i>
            </button>
            <div class="space-y-6 sm:space-y-8">
                <div>
                    <label class="flex items-center gap-2 text-xs sm:text-sm font-black text-secondary mb-2 sm:mb-3 uppercase tracking-wider">
                        <i class="ri-apps-2-fill text-secondary"></i>
                        Select Service
                    </label>
                    <div class="bg-white rounded-xl sm:rounded-2xl border border-gray-200 select-wrapper">
                        <select id="${id}" name="services[${currentIdx}][service_id]" required class="service-selector w-full">
                            <option value="" disabled selected>Loading services...</option>
                        </select>
                    </div>
                </div>
                
                <div class="space-y-4 sm:space-y-5">
                    <label class="flex items-center gap-2 text-[10px] sm:text-[11px] font-black text-secondary/50 uppercase tracking-[0.2em]">
                        <i class="ri-price-tag-3-fill"></i>
                        Pricing Tiers
                    </label>
                    <div class="rates-container space-y-3 sm:space-y-4">
                        <div class="rate-row grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 items-end bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl border border-[#2E4B3D]/5 shadow-sm">
                            <div>
                                <label class="block text-[10px] sm:text-xs font-bold text-gray-500 mb-1 sm:mb-2 uppercase tracking-wide">Duration</label>
                                <div class="relative">
                                    <i class="ri-time-fill absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-secondary/40 text-base sm:text-lg"></i>
                                    <input type="number" name="services[${currentIdx}][rates][0][duration]" required placeholder="60" class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3.5 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-4 focus:ring-secondary/5 focus:border-secondary transition-all outline-none font-bold text-secondary text-sm sm:text-base">
                                    <span class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] sm:text-xs font-bold uppercase">Min</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[10px] sm:text-xs font-bold text-gray-500 mb-1 sm:mb-2 uppercase tracking-wide">Rate</label>
                                        <div class="flex gap-2 sm:gap-3">
                                            <select name="services[${currentIdx}][currency]" class="currency-select h-[54px] sm:h-[58px] bg-gray-100 border border-gray-200 rounded-lg px-3 text-sm font-semibold text-gray-700 focus:border-secondary focus:ring-2 focus:ring-secondary/10" disabled>
                                                <option value="INR">INR</option>
                                                <option value="USD">USD</option>
                                                <option value="EUR">EUR</option>
                                                <option value="GBP">GBP</option>
                                                <option value="AED">AED</option>
                                            </select>
                                            <div class="relative flex-1">
                                                <span class="currency-symbol absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-secondary/40 font-black text-base sm:text-lg">₹</span>
                                                <input type="number" name="services[${currentIdx}][rates][0][rate]" step="0.01" required placeholder="0.00" class="w-full pl-8 sm:pl-10 pr-4 py-2.5 sm:py-3.5 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-4 focus:ring-secondary/5 focus:border-secondary transition-all outline-none font-bold text-secondary text-base sm:text-lg">
                                            </div>
                                            <button type="button" class="w-12 h-11 sm:w-14 sm:h-[54px] rounded-lg sm:rounded-xl bg-gray-50 text-gray-300 flex items-center justify-center cursor-not-allowed border border-gray-100" disabled>
                                                <i class="ri-delete-bin-fill text-lg sm:text-xl"></i>
                                            </button>
                                        </div>
                                    </div>
                        </div>
                    </div>
                    <button type="button" onclick="addRateRow(${currentIdx}, this)" class="w-full sm:w-auto bg-white border-2 border-dashed border-secondary/20 text-secondary px-4 py-2.5 sm:px-6 sm:py-3 rounded-lg sm:rounded-xl font-black text-xs sm:text-sm flex items-center justify-center gap-2 hover:bg-secondary hover:text-white hover:border-secondary transition-all duration-300 shadow-sm">
                        <i class="ri-add-line text-lg"></i>
                        Add Another Duration
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(row);
        initSelect2(id);
        attachCurrencyListeners(row);
        serviceRowIndex++;
        container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
    }

    function addRateRow(serviceIdx, button) {
        const container = button.previousElementSibling;
        const rateIdx = container.querySelectorAll('.rate-row').length;
        
        const row = document.createElement('div');
        row.className = 'rate-row grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 items-end bg-white p-4 sm:p-6 rounded-xl sm:rounded-2xl border border-[#2E4B3D]/5 animate-in fade-in slide-in-from-top-4 duration-300 shadow-sm';
        row.innerHTML = `
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-gray-500 mb-1 sm:mb-2 uppercase tracking-wide">Duration</label>
                <div class="relative">
                    <i class="ri-time-fill absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-secondary/40 text-base sm:text-lg"></i>
                    <input type="number" name="services[${serviceIdx}][rates][${rateIdx}][duration]" required placeholder="60" class="w-full pl-10 sm:pl-12 pr-4 py-2.5 sm:py-3.5 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-4 focus:ring-secondary/5 focus:border-secondary transition-all outline-none font-bold text-secondary text-sm sm:text-base">
                    <span class="absolute right-3 sm:right-4 top-1/2 -translate-y-1/2 text-gray-400 text-[10px] sm:text-xs font-bold uppercase">Min</span>
                </div>
            </div>
            <div>
                <label class="block text-[10px] sm:text-xs font-bold text-gray-500 mb-1 sm:mb-2 uppercase tracking-wide">Rate</label>
                <div class="flex gap-2 sm:gap-3">
                    <div class="relative flex-1">
                        <span class="absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-secondary/40 font-black text-base sm:text-lg">₹</span>
                        <input type="number" name="services[${serviceIdx}][rates][${rateIdx}][rate]" step="0.01" required placeholder="0.00" class="w-full pl-8 sm:pl-10 pr-4 py-2.5 sm:py-3.5 rounded-lg sm:rounded-xl border border-gray-200 focus:ring-4 focus:ring-secondary/5 focus:border-secondary transition-all outline-none font-bold text-secondary text-base sm:text-lg">
                    </div>
                    <button type="button" onclick="confirmRemoveRateRow(this)" class="w-12 h-11 sm:w-14 sm:h-[54px] rounded-lg sm:rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all border border-red-100">
                        <i class="ri-delete-bin-fill text-lg sm:text-xl"></i>
                    </button>
                </div>
            </div>
        `;
        container.appendChild(row);
    }

    function showConfirmModal(title, message, onConfirm) {
        const modal = document.getElementById('deleteConfirmModal');
        const content = document.getElementById('confirmModalContent');
        
        document.getElementById('confirmTitle').innerText = title;
        document.getElementById('confirmMessage').innerText = message;
        
        const btn = document.getElementById('confirmActionButton');
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        
        newBtn.onclick = () => {
            onConfirm();
            closeDeleteModal();
        };
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function confirmRemoveRateRow(button) {
        showConfirmModal(
            'Remove Tier?', 
            'Are you sure you want to remove this pricing tier?', 
            () => {
                const row = button.closest('.rate-row');
                row.classList.add('fade-out', 'scale-95');
                setTimeout(() => row.remove(), 200);
            }
        );
    }

    function confirmRemoveServiceRow(button) {
        showConfirmModal(
            'Remove Service?', 
            'Discard this service and all its configured rates?', 
            () => {
                const row = button.closest('.service-row');
                const selectId = row.querySelector('.service-selector').id;
                if (select2Instances[selectId]) {
                    $(row).find('.service-selector').select2('destroy');
                    delete select2Instances[selectId];
                }
                row.classList.add('fade-out', 'slide-out-to-top-4');
                setTimeout(() => row.remove(), 300);
            }
        );
    }

    function confirmDeleteRate(id, serviceTitle, duration) {
        showConfirmModal(
            'Remove Rate?', 
            `Permanently delete the ${duration} Min rate for "${serviceTitle}"?`, 
            () => {
                const form = document.getElementById('deleteForm');
                form.action = `/my-services/${id}`;
                form.submit();
            }
        );
    }

    function confirmDeleteService(serviceId, serviceTitle) {
        showConfirmModal(
            'Remove Service?', 
            `Are you sure you want to remove "${serviceTitle}" and ALL associated pricing tiers?`, 
            () => {
                const form = document.getElementById('deleteForm');
                form.action = `/my-services/group/${serviceId}`;
                form.submit();
            }
        );
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteConfirmModal');
        const content = document.getElementById('confirmModalContent');
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => modal.classList.add('hidden'), 300);
    }

    function openAddServiceModal() {
        const modal = document.getElementById('addServiceModal');
        const content = document.getElementById('addModalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
            
            // Initialize Select2 for the first row if not already done
            const firstSelectId = 'service-select-0';
            if (document.getElementById(firstSelectId) && !select2Instances[firstSelectId]) {
                initSelect2(firstSelectId);
            }
            attachCurrencyListeners();
        }, 10);
        
        document.body.style.overflow = 'hidden';
    }
    function manageService(serviceId) {
        openAddServiceModal();
        setTimeout(() => {
            const select = document.getElementById('service-select-0');
            if (select) {
                $(select).val(String(serviceId)).trigger('change');
            }
        }, 250);
    }

    function closeAddServiceModal() {
        const modal = document.getElementById('addServiceModal');
        const content = document.getElementById('addModalContent');
        
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
            
            // Reset state
            const container = document.getElementById('services-container');
            const rows = container.querySelectorAll('.service-row');
            for (let i = 1; i < rows.length; i++) {
                const selectId = rows[i].querySelector('.service-selector').id;
                if (select2Instances[selectId]) {
                    $(rows[i]).find('.service-selector').select2('destroy');
                    delete select2Instances[selectId];
                }
                rows[i].remove();
            }
            
            const firstRatesContainer = rows[0].querySelector('.rates-container');
            const firstRates = firstRatesContainer.querySelectorAll('.rate-row');
            for (let i = 1; i < firstRates.length; i++) {
                firstRates[i].remove();
            }
            
            if (select2Instances['service-select-0']) {
                $('#service-select-0').val(null).trigger('change');
            }
            serviceRowIndex = 1;
            cachedAvailableServices = null; // Reset cache so it refreshes next time
        }, 300);
    }

    window.onclick = function(event) {
        const addModal = document.getElementById('addServiceModal');
        const deleteModal = document.getElementById('deleteConfirmModal');
        if (event.target == addModal) closeAddServiceModal();
        if (event.target == deleteModal) closeDeleteModal();
    }

    function switchMyServicesTab(tab) {
        const servicesTab = document.getElementById('services-tab');
        const videoTab = document.getElementById('video-tab');
        const btnServices = document.getElementById('tab-services-btn');
        const btnVideo = document.getElementById('tab-video-btn');

        if (tab === 'services') {
            servicesTab.classList.remove('hidden');
            videoTab.classList.add('hidden');
            btnServices.classList.add('active');
            btnServices.classList.remove('inactive');
            btnVideo.classList.add('inactive');
            btnVideo.classList.remove('active');
        } else {
            servicesTab.classList.add('hidden');
            videoTab.classList.remove('hidden');
            btnVideo.classList.add('active');
            btnVideo.classList.remove('inactive');
            btnServices.classList.add('inactive');
            btnServices.classList.remove('active');
        }
    }

    function copyAgoraLink(link) {
        navigator.clipboard.writeText(link).then(() => {
            alert('Link copied to clipboard');
        }).catch(() => {
            const temp = document.createElement('input');
            temp.value = link;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            alert('Link copied to clipboard');
        });
    }
</script>
@endpush
@endsection
