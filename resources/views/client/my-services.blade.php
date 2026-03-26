@extends('layouts.client')

@section('title', 'My Services')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    /* Tom Select Custom Styling */
    .ts-control {
        border-radius: 0.75rem !important;
        padding: 0.75rem 1rem !important;
        border: 1px solid #e5e7eb !important;
        background-color: #fff !important;
        font-size: 1rem !important;
        line-height: 1.5rem !important;
        transition: all 0.2s !important;
    }
    .ts-wrapper.focus .ts-control {
        border-color: #2E4B3D !important;
        box-shadow: 0 0 0 2px rgba(46, 75, 61, 0.1) !important;
    }
    .ts-dropdown {
        border-radius: 0.75rem !important;
        margin-top: 0.5rem !important;
        border: 1px solid #f3f4f6 !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        overflow: hidden !important;
    }
    .ts-dropdown .active {
        background-color: #F8FAF9 !important;
        color: #2E4B3D !important;
    }
    .ts-dropdown .option {
        padding: 0.75rem 1rem !important;
    }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }

    /* Custom visual tweaks for better contrast */
    .service-card-header {
        background: linear-gradient(180deg, rgba(46, 75, 61, 0.05) 0%, rgba(46, 75, 61, 0) 100%);
    }
</style>
@endpush

@section('content')
@php
    $practitionerRoles = ['practitioner', 'doctor', 'mindfulness_practitioner', 'mindfulness-practitioner', 'yoga_therapist', 'yoga-therapist'];
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
    <div class="flex justify-between items-center bg-white rounded-2xl px-8 py-10 border border-[#2E4B3D]/12 shadow-sm">
        <div>
            <h2 class="text-3xl font-black text-secondary mb-2 tracking-tight">My Services</h2>
            <p class="text-gray-500 font-medium">Manage your professional offerings and tiered pricing.</p>
        </div>
        <button onclick="openAddServiceModal()" class="bg-secondary text-white px-8 py-4 rounded-2xl font-bold hover:bg-opacity-95 transform hover:-translate-y-0.5 transition-all flex items-center gap-3 shadow-xl shadow-secondary/25">
            <i class="ri-add-fill text-2xl"></i>
            <span class="text-lg">Add New Service</span>
        </button>
    </div>

    @if(session('status'))
        <div class="px-6 py-4 bg-[#F0FDF4] border border-green-200 text-[#166534] rounded-2xl flex items-center shadow-sm animate-in fade-in slide-in-from-top-4 duration-300">
            <div class="w-10 h-10 bg-green-500/10 rounded-full flex items-center justify-center mr-4">
                <i class="ri-checkbox-circle-fill text-2xl text-green-600"></i>
            </div>
            <span class="font-bold">{{ session('status') }}</span>
        </div>
    @endif

    <!-- Services Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
        @forelse($myServices as $serviceId => $rates)
            @php $firstRate = $rates->first(); @endphp
            <div class="bg-white rounded-[32px] border border-[#2E4B3D]/12 overflow-hidden hover:shadow-2xl hover:shadow-secondary/5 transition-all duration-500 group flex flex-col h-full">
                <!-- Card Header -->
                <div class="h-44 relative flex-shrink-0">
                    <img src="{{ $firstRate->service->image ? asset('storage/' . $firstRate->service->image) : asset('frontend/assets/service-placeholder.png') }}" 
                         alt="{{ $firstRate->service->title }}" 
                         class="w-full h-full object-cover">
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
                        <button class="text-secondary font-black text-sm hover:underline flex items-center gap-2 transition-all">
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
@endif

<!-- Add Service Modal -->
<div id="addServiceModal" class="fixed inset-0 bg-[#1A1A1A]/60 backdrop-blur-md hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[40px] w-full max-w-3xl overflow-hidden shadow-2xl scale-95 opacity-0 transition-all duration-300" id="addModalContent">
        <div class="px-10 py-8 border-b border-gray-100 flex justify-between items-center bg-[#F8FAF9]">
        <div>
        <h3 class="text-2xl font-black text-secondary tracking-tight">Configure Services ({{ $availableServices->count() }} available)</h3>
        <p class="text-gray-500 text-sm font-medium">Add multiple services and pricing tiers</p>
        </div>            <button onclick="closeAddServiceModal()" class="w-12 h-12 bg-white rounded-2xl text-gray-400 hover:text-secondary hover:shadow-md transition-all flex items-center justify-center border border-gray-100">
                <i class="ri-close-line text-3xl"></i>
            </button>
        </div>
        
        <form action="{{ route('my-services.store') }}" method="POST" class="px-10 py-10">
            @csrf
            <div id="services-container" class="space-y-10 max-h-[55vh] overflow-y-auto pr-4 scrollbar-hide">
                <!-- Initial Row -->
                <div class="service-row bg-[#F0F4F2] p-8 rounded-[32px] border border-[#2E4B3D]/10 relative group shadow-sm">
                    <div class="space-y-8">
                        <div>
                            <label class="flex items-center gap-2 text-sm font-black text-secondary mb-3 uppercase tracking-wider">
                                <i class="ri-apps-2-fill text-secondary"></i>
                                Select Service
                            </label>
                            <div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
                                <select id="service-select-0" name="services[0][service_id]" required class="service-selector w-full">
                                    @if($availableServices->isEmpty())
                                        <option value="" disabled selected>No more services available</option>
                                    @else
                                        <option value="" disabled selected>Choose a service...</option>
                                        @foreach($availableServices as $service)
                                            <option value="{{ $service->id }}">{{ $service->title }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        
                        <!-- Rates Container -->
                        <div class="space-y-5">
                            <label class="flex items-center gap-2 text-[11px] font-black text-secondary/50 uppercase tracking-[0.2em]">
                                <i class="ri-price-tag-3-fill"></i>
                                Pricing Tiers
                            </label>
                            <div class="rates-container space-y-4">
                                <div class="rate-row grid grid-cols-1 sm:grid-cols-2 gap-6 items-end bg-white p-6 rounded-2xl border border-[#2E4B3D]/5 shadow-sm">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Duration</label>
                                        <div class="relative">
                                            <i class="ri-time-fill absolute left-4 top-1/2 -translate-y-1/2 text-secondary/40 text-lg"></i>
                                            <input type="number" name="services[0][rates][0][duration]" required placeholder="60" class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-200 focus:ring-4 focus:ring-secondary/5 focus:border-secondary transition-all outline-none font-bold text-secondary">
                                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-bold uppercase">Min</span>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Rate</label>
                                        <div class="flex gap-3">
                                            <div class="relative flex-1">
                                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary/40 font-black text-lg">₹</span>
                                                <input type="number" name="services[0][rates][0][rate]" step="0.01" required placeholder="0.00" class="w-full pl-10 pr-4 py-3.5 rounded-xl border border-gray-200 focus:ring-4 focus:ring-secondary/5 focus:border-secondary transition-all outline-none font-bold text-secondary text-lg">
                                            </div>
                                            <button type="button" class="w-14 h-[54px] rounded-xl bg-gray-50 text-gray-300 flex items-center justify-center cursor-not-allowed border border-gray-100" disabled>
                                                <i class="ri-delete-bin-fill text-xl"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" onclick="addRateRow(0, this)" class="bg-white border-2 border-dashed border-secondary/20 text-secondary px-6 py-3 rounded-xl font-black text-sm flex items-center gap-2 hover:bg-secondary hover:text-white hover:border-secondary transition-all duration-300 shadow-sm">
                                <i class="ri-add-line text-lg"></i>
                                Add Another Duration
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="mt-10 flex flex-col sm:flex-row gap-4 pt-8 border-t border-gray-100">
                <button type="button" onclick="addServiceRow()" class="flex-[0.4] px-6 py-4 bg-white border-2 border-secondary text-secondary font-black rounded-2xl hover:bg-secondary hover:text-white transition-all flex items-center justify-center gap-3 shadow-lg shadow-secondary/5">
                    <i class="ri-add-circle-fill text-xl"></i>
                    Another Service
                </button>
                <button type="submit" class="flex-1 px-8 py-4 bg-secondary text-white font-black rounded-2xl hover:bg-opacity-95 transform hover:-translate-y-0.5 transition-all shadow-2xl shadow-secondary/30 text-lg tracking-tight">
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
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    let serviceRowIndex = 1;
    let tomSelectInstances = {};

    function initTomSelect(elementId) {
        const el = document.getElementById(elementId);
        if (!el) return;
        
        if (tomSelectInstances[elementId]) {
            tomSelectInstances[elementId].destroy();
        }
        
        tomSelectInstances[elementId] = new TomSelect(el, {
            create: false,
            sortField: { field: "text", direction: "asc" },
            placeholder: 'Choose a service...',
            allowEmptyOption: true,
            maxOptions: 100,
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const firstSelect = document.querySelector('.service-selector');
        if (firstSelect) {
            const id = `service-select-0`;
            firstSelect.id = id;
            initTomSelect(id);
        }
    });

    function addServiceRow() {
        const container = document.getElementById('services-container');
        const id = `service-select-${serviceRowIndex}`;
        const currentIdx = serviceRowIndex;
        
        const row = document.createElement('div');
        row.className = 'service-row bg-[#F0F4F2] p-8 rounded-[32px] border border-[#2E4B3D]/10 relative group animate-in fade-in slide-in-from-top-6 duration-500 shadow-sm mb-10';
        row.innerHTML = `
            <button type="button" onclick="confirmRemoveServiceRow(this)" class="absolute -top-4 -right-4 w-12 h-12 bg-white text-red-500 rounded-2xl flex items-center justify-center hover:bg-red-500 hover:text-white transition-all duration-300 shadow-xl border border-gray-100 group-hover:scale-110">
                <i class="ri-delete-bin-fill text-xl"></i>
            </button>
            <div class="space-y-8">
                <div>
                    <label class="flex items-center gap-2 text-sm font-black text-secondary mb-3 uppercase tracking-wider">
                        <i class="ri-apps-2-fill text-secondary"></i>
                        Select Service
                    </label>
                    <div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
                        <select id="${id}" name="services[${currentIdx}][service_id]" required class="service-selector w-full">
                            <option value="" disabled selected>Choose a service...</option>
                            @foreach($availableServices as $service)
                                <option value="{{ $service->id }}">{{ $service->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="space-y-5">
                    <label class="flex items-center gap-2 text-[11px] font-black text-secondary/50 uppercase tracking-[0.2em]">
                        <i class="ri-price-tag-3-fill"></i>
                        Pricing Tiers
                    </label>
                    <div class="rates-container space-y-4">
                        <div class="rate-row grid grid-cols-1 sm:grid-cols-2 gap-6 items-end bg-white p-6 rounded-2xl border border-[#2E4B3D]/5 shadow-sm">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Duration</label>
                                <div class="relative">
                                    <i class="ri-time-fill absolute left-4 top-1/2 -translate-y-1/2 text-secondary/40 text-lg"></i>
                                    <input type="number" name="services[${currentIdx}][rates][0][duration]" required placeholder="60" class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-200 focus:ring-4 focus:ring-secondary/5 focus:border-secondary transition-all outline-none font-bold text-secondary">
                                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-bold uppercase">Min</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Rate</label>
                                <div class="flex gap-3">
                                    <div class="relative flex-1">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary/40 font-black text-lg">₹</span>
                                        <input type="number" name="services[${currentIdx}][rates][0][rate]" step="0.01" required placeholder="0.00" class="w-full pl-10 pr-4 py-3.5 rounded-xl border border-gray-200 focus:ring-4 focus:ring-secondary/5 focus:border-secondary transition-all outline-none font-bold text-secondary text-lg">
                                    </div>
                                    <button type="button" class="w-14 h-[54px] rounded-xl bg-gray-50 text-gray-300 flex items-center justify-center cursor-not-allowed border border-gray-100" disabled>
                                        <i class="ri-delete-bin-fill text-xl"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addRateRow(${currentIdx}, this)" class="bg-white border-2 border-dashed border-secondary/20 text-secondary px-6 py-3 rounded-xl font-black text-sm flex items-center gap-2 hover:bg-secondary hover:text-white hover:border-secondary transition-all duration-300 shadow-sm">
                        <i class="ri-add-line text-lg"></i>
                        Add Another Duration
                    </button>
                </div>
            </div>
        `;
        
        container.appendChild(row);
        initTomSelect(id);
        serviceRowIndex++;
        container.scrollTo({ top: container.scrollHeight, behavior: 'smooth' });
    }

    function addRateRow(serviceIdx, button) {
        const container = button.previousElementSibling;
        const rateIdx = container.querySelectorAll('.rate-row').length;
        
        const row = document.createElement('div');
        row.className = 'rate-row grid grid-cols-1 sm:grid-cols-2 gap-6 items-end bg-white p-6 rounded-2xl border border-[#2E4B3D]/5 animate-in fade-in slide-in-from-top-4 duration-300 shadow-sm';
        row.innerHTML = `
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Duration</label>
                <div class="relative">
                    <i class="ri-time-fill absolute left-4 top-1/2 -translate-y-1/2 text-secondary/40 text-lg"></i>
                    <input type="number" name="services[${serviceIdx}][rates][${rateIdx}][duration]" required placeholder="60" class="w-full pl-12 pr-4 py-3.5 rounded-xl border border-gray-200 focus:ring-4 focus:ring-secondary/5 focus:border-secondary transition-all outline-none font-bold text-secondary">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs font-bold uppercase">Min</span>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 mb-2 uppercase tracking-wide">Rate</label>
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-secondary/40 font-black text-lg">₹</span>
                        <input type="number" name="services[${serviceIdx}][rates][${rateIdx}][rate]" step="0.01" required placeholder="0.00" class="w-full pl-10 pr-4 py-3.5 rounded-xl border border-gray-200 focus:ring-4 focus:ring-secondary/5 focus:border-secondary transition-all outline-none font-bold text-secondary text-lg">
                    </div>
                    <button type="button" onclick="confirmRemoveRateRow(this)" class="w-14 h-[54px] rounded-xl bg-red-50 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all border border-red-100">
                        <i class="ri-delete-bin-fill text-xl"></i>
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
                if (tomSelectInstances[selectId]) {
                    tomSelectInstances[selectId].destroy();
                    delete tomSelectInstances[selectId];
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
        }, 10);
        
        document.body.style.overflow = 'hidden';
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
                if (tomSelectInstances[selectId]) {
                    tomSelectInstances[selectId].destroy();
                    delete tomSelectInstances[selectId];
                }
                rows[i].remove();
            }
            
            const firstRatesContainer = rows[0].querySelector('.rates-container');
            const firstRates = firstRatesContainer.querySelectorAll('.rate-row');
            for (let i = 1; i < firstRates.length; i++) {
                firstRates[i].remove();
            }
            
            if (tomSelectInstances['service-select-0']) {
                tomSelectInstances['service-select-0'].clear();
            }
            serviceRowIndex = 1;
        }, 300);
    }

    window.onclick = function(event) {
        const addModal = document.getElementById('addServiceModal');
        const deleteModal = document.getElementById('deleteConfirmModal');
        if (event.target == addModal) closeAddServiceModal();
        if (event.target == deleteModal) closeDeleteModal();
    }
</script>
@endpush
@endsection
