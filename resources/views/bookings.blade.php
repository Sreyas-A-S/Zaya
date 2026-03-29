@extends('layouts.client')

@section('title', 'My Bookings')

@section('content')

<!-- Bookings Content -->
<div id="bookings-wrapper">
    @include('partials.bookings-table')
</div>

<!-- Booking Details Modal -->
<div id="booking-modal" class="fixed inset-0 z-[999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background backdrop -->
    <div id="modal-backdrop" class="fixed inset-0 bg-black/50 transition-opacity"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div id="booking-modal-container" class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
            <!-- Modal panel -->
            <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-2xl shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-medium text-secondary" id="modal-title">Booking Details</h3>
                    <button onclick="closeBookingModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <div id="modal-body" class="px-6 py-6 max-h-[70vh] overflow-y-auto">
                    <!-- Content will be loaded here -->
                    <div class="flex justify-center items-center py-10">
                        <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-secondary"></div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50 text-right">
                    <button onclick="closeBookingModal()" class="px-6 py-2 bg-white border border-gray-200 text-gray-600 rounded-full text-sm font-medium hover:bg-gray-50 transition-colors">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Referral Modal -->
<div id="refer-modal" class="fixed inset-0 z-[999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeReferModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
            <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-[#2E4B3D]/12">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-xl font-black text-secondary tracking-tight">Refer Consultation</h3>
                        <p class="text-[10px] text-gray-400 uppercase font-black tracking-widest mt-1">Share health data with professionals</p>
                    </div>
                    <button onclick="closeReferModal()" class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-all shadow-sm">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <div class="px-8 py-8">
                    <form id="refer-form">
                        <input type="hidden" id="refer-booking-id">
                        <input type="hidden" id="refer-client-id">
                        
                        <!-- Step 1: Select Recipient & Fee -->
                        <div id="refer-step-1">
                            <!-- Role Selection (Tabs) -->
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-secondary mb-3 uppercase tracking-wider text-[10px] opacity-60">1. Select Category</label>
                                <div class="flex overflow-x-auto gap-2 pb-2 scrollbar-hide" id="refer-tabs">
                                    @php
                                        $roles = [
                                            'practitioner' => ['label' => 'Practitioner', 'icon' => 'ri-user-heart-line'],
                                            'doctor' => ['label' => 'Doctor', 'icon' => 'ri-stethoscope-line'],
                                            'mindfulness_practitioner' => ['label' => 'Mindfulness', 'icon' => 'ri-mental-health-line'],
                                            'yoga_therapist' => ['label' => 'Yoga', 'icon' => 'ri-infinity-line']
                                        ];
                                    @endphp
                                    @foreach($roles as $val => $info)
                                    <button type="button" 
                                        onclick="switchReferTab('{{ $val }}')"
                                        data-role-tab="{{ $val }}"
                                        class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-[#2E4B3D]/12 text-[11px] font-bold text-gray-500 whitespace-nowrap hover:bg-gray-50 transition-all [&.active]:bg-secondary [&.active]:text-white [&.active]:border-secondary">
                                        <i class="{{ $info['icon'] }}"></i>
                                        {{ $info['label'] }}
                                    </button>
                                    @endforeach
                                    <input type="hidden" id="selected-role" value="practitioner">
                                </div>
                            </div>

                            <!-- Search -->
                            <div class="mb-6">
                                <label class="block text-sm font-bold text-secondary mb-3 uppercase tracking-wider text-[10px] opacity-60">2. Find Professional</label>
                                <div class="relative">
                                    <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    <input type="text" id="refer-search" placeholder="Search by name..." 
                                        oninput="debouncedFetchProfessionals()"
                                        class="w-full pl-11 pr-4 py-4 rounded-2xl border-[#2E4B3D]/12 focus:border-secondary focus:ring-0 text-sm transition-all shadow-sm">
                                </div>
                            </div>

                            <!-- Professional List -->
                            <div class="mb-6">
                                <div id="professionals-loader" class="hidden py-10 text-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-secondary mx-auto"></div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold mt-3 tracking-widest">Searching Professionals...</p>
                                </div>
                                <div id="professionals-list" class="space-y-3 max-h-[280px] overflow-y-auto pr-2 custom-sidebar-scrollbar">
                                    <!-- Dynamic list items -->
                                </div>
                            </div>

                            <!-- Selected Professionals Summary -->
                            <div class="mb-8">
                                <label class="block text-sm font-bold text-secondary mb-3 uppercase tracking-wider text-[10px] opacity-60">Selected Professionals (1 from each category)</label>
                                <div id="selected-professionals-summary" class="space-y-2">
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest text-center py-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">No professionals selected yet</p>
                                </div>
                            </div>

                            <button type="button" id="refer-submit-btn" onclick="submitReferral()" disabled class="w-full py-5 bg-secondary text-white rounded-[1.5rem] font-black text-sm hover:bg-primary transition-all shadow-2xl shadow-secondary/30 uppercase tracking-[0.2em] disabled:opacity-40 disabled:cursor-not-allowed">
                                Send Referral Requests
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="h-10"></div>
@endsection

@section('scripts')
<script>
    // Referral Modal Logic
    let fetchTimeout = null;
    let selectedProfessionals = {}; // { 'practitioner': {id, name, fee, pic}, ... }

    function switchReferTab(role) {
        document.getElementById('selected-role').value = role;
        document.querySelectorAll('[data-role-tab]').forEach(btn => {
            if (btn.dataset.roleTab === role) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
        fetchProfessionals();
    }

    function debouncedFetchProfessionals() {
        clearTimeout(fetchTimeout);
        fetchTimeout = setTimeout(fetchProfessionals, 400);
    }

    async function fetchProfessionals() {
        const bookingId = document.getElementById('refer-booking-id').value;
        const query = document.getElementById('refer-search').value;
        const role = document.getElementById('selected-role').value;
        const list = document.getElementById('professionals-list');
        const loader = document.getElementById('professionals-loader');

        loader.classList.remove('hidden');
        list.classList.add('hidden');

        try {
            const url = new URL("{{ route('api.referrable-practitioners') }}", window.location.origin);
            url.searchParams.append('booking_id', bookingId);
            if (query) url.searchParams.append('query', query);
            url.searchParams.append('roles[]', role);

            const response = await fetch(url);
            const professionals = await response.json();

            loader.classList.add('hidden');
            list.classList.remove('hidden');

            if (professionals.length === 0) {
                list.innerHTML = '<p class="text-center text-gray-400 text-xs py-10">No professionals found in this category.</p>';
                return;
            }

            list.innerHTML = '';
            professionals.forEach(p => {
                const isSelected = selectedProfessionals[role] && selectedProfessionals[role].id === p.id;
                const item = document.createElement('div');
                item.className = `p-4 rounded-2xl border ${isSelected ? 'border-secondary bg-secondary/5' : 'border-gray-100'} flex items-center justify-between group hover:border-secondary/20 hover:bg-gray-50/50 transition-all cursor-pointer professional-item`;
                item.onclick = () => selectProfessional(p.id, p.name, p.service_fee, p.profile_pic, role);
                
                item.innerHTML = `
                    <div class="flex items-center gap-4">
                        <img src="${p.profile_pic}" class="w-12 h-12 rounded-xl object-cover border border-gray-100 shadow-sm">
                        <div>
                            <p class="text-sm font-bold text-secondary leading-tight">${p.name}</p>
                            <div class="flex flex-wrap items-center gap-2 mt-1">
                                <span class="text-[9px] px-2 py-0.5 rounded-full font-black uppercase tracking-widest ${p.handles_service ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-50 text-gray-400'}">
                                    ${p.handles_service ? 'Handles Service' : 'Consultation Only'}
                                </span>
                                ${p.handles_service && p.service_fee > 0 ? `
                                    <span class="text-[9px] px-2 py-0.5 rounded-full bg-secondary/5 text-secondary font-black uppercase tracking-widest border border-secondary/10">
                                        Fee: €${parseFloat(p.service_fee).toFixed(2)}
                                    </span>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                    <div class="w-7 h-7 rounded-full border-2 ${isSelected ? 'bg-secondary border-secondary' : 'border-gray-100 bg-white'} flex items-center justify-center transition-all group-hover:border-secondary check-indicator">
                        <i class="ri-check-line text-white ${isSelected ? 'opacity-100' : 'opacity-0'} transition-all text-lg"></i>
                    </div>
                `;
                list.appendChild(item);
            });
        } catch (error) {
            console.error('Fetch professionals error:', error);
            loader.classList.add('hidden');
            list.classList.remove('hidden');
            list.innerHTML = '<p class="text-center text-red-400 text-xs py-10">Error loading professionals.</p>';
        }
    }

    function selectProfessional(id, name, fee, pic, role) {
        // Toggle selection: if clicking the same one, unselect
        if (selectedProfessionals[role] && selectedProfessionals[role].id === id) {
            delete selectedProfessionals[role];
        } else {
            selectedProfessionals[role] = { id, name, fee, pic, role };
        }
        
        renderSelectedSummary();
        fetchProfessionals(); // Refresh list to show correct checkmarks
    }

    function renderSelectedSummary() {
        const summary = document.getElementById('selected-professionals-summary');
        const submitBtn = document.getElementById('refer-submit-btn');
        const roles = Object.keys(selectedProfessionals);

        if (roles.length === 0) {
            summary.innerHTML = '<p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest text-center py-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">No professionals selected yet</p>';
            submitBtn.disabled = true;
            return;
        }

        submitBtn.disabled = false;
        summary.innerHTML = '';
        roles.forEach(roleKey => {
            const p = selectedProfessionals[roleKey];
            const roleLabel = roleKey.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase());
            const card = document.createElement('div');
            card.className = 'flex items-center justify-between p-3 bg-white border border-[#2E4B3D]/12 rounded-xl shadow-sm';
            card.innerHTML = `
                <div class="flex items-center gap-3">
                    <img src="${p.pic}" class="w-8 h-8 rounded-lg object-cover">
                    <div>
                        <p class="text-xs font-bold text-secondary">${p.name}</p>
                        <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest">${roleLabel}</p>
                    </div>
                </div>
                <button type="button" onclick="removeSelectedProfessional('${roleKey}')" class="text-red-400 hover:text-red-600 p-1">
                    <i class="ri-delete-bin-line text-lg"></i>
                </button>
            `;
            summary.appendChild(card);
        });
    }

    function removeSelectedProfessional(role) {
        delete selectedProfessionals[role];
        renderSelectedSummary();
        fetchProfessionals();
    }

    async function openReferModal(bookingId, clientId) {
        document.getElementById('refer-booking-id').value = bookingId;
        document.getElementById('refer-client-id').value = clientId;
        document.getElementById('refer-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Reset form & state
        document.getElementById('refer-search').value = '';
        selectedProfessionals = {};
        renderSelectedSummary();
        
        // Default to practitioner tab
        switchReferTab('practitioner');
    }

    function closeReferModal() {
        document.getElementById('refer-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    async function submitReferral() {
        const bookingId = document.getElementById('refer-booking-id').value;
        const submitBtn = document.getElementById('refer-submit-btn');

        if (Object.keys(selectedProfessionals).length === 0) {
            alert('Please select at least one professional to refer to.');
            return;
        }

        submitBtn.disabled = true;
        const originalText = submitBtn.innerText;
        submitBtn.innerText = 'Sending Requests...';

        const referrals = Object.values(selectedProfessionals).map(p => ({
            id: p.id,
            amount: p.fee
        }));

        try {
            const response = await fetch(`/bookings/${bookingId}/refer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    referrals: referrals
                })
            });
            const data = await response.json();
            if (data.success) {
                if (window.showZayaToast) showZayaToast(data.success, 'Referral');
                closeReferModal();
                setTimeout(() => location.reload(), 1500);
            } else {
                alert(data.error || 'Referral failed.');
            }
        } catch (error) {
            console.error('Referral Error:', error);
            alert('An error occurred.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerText = originalText;
        }
    }

    function openBookingModal() {
        document.getElementById('booking-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeBookingModal() {
        document.getElementById('booking-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close on outside click
    document.getElementById('modal-backdrop').addEventListener('click', closeBookingModal);
    document.getElementById('booking-modal-container').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBookingModal();
        }
    });

    async function viewBookingDetails(id) {
        openBookingModal();
        const body = document.getElementById('modal-body');
        body.innerHTML = '<div class="flex justify-center items-center py-10"><div class="animate-spin rounded-full h-10 w-10 border-b-2 border-secondary"></div></div>';

        try {
            const response = await fetch(`/bookings/${id}/details`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) throw new Error('Failed to fetch details');

            const html = await response.text();
            body.innerHTML = html;
        } catch (error) {
            console.error('Error:', error);
            body.innerHTML = '<div class="text-center py-10 text-red-500">Failed to load booking details. Please try again.</div>';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Action Dropdown Logic
        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('.dropdown-trigger');
            const allMenus = document.querySelectorAll('.action-dropdown .dropdown-menu');
            
            if (trigger) {
                const menu = trigger.nextElementSibling;
                const isOpen = !menu.classList.contains('hidden');
                
                // Close all other menus
                allMenus.forEach(m => m.classList.add('hidden'));
                
                if (!isOpen) {
                    menu.classList.remove('hidden');
                }
                e.stopPropagation();
            } else {
                // Close all menus when clicking outside
                allMenus.forEach(m => m.classList.add('hidden'));
            }
        });

        // Tab toggle
        const tabButtons = document.querySelectorAll('.tab-trigger');
        const tabPanels = document.querySelectorAll('[data-tab-panel]');

        function activateTab(targetId) {
            tabButtons.forEach(btn => {
                const isActive = btn.dataset.tabTarget === targetId;
                btn.classList.toggle('tab-active', isActive);
                btn.classList.toggle('bg-secondary', isActive);
                btn.classList.toggle('text-white', isActive);
                btn.classList.toggle('shadow-sm', isActive);
                btn.classList.toggle('border', !isActive);
                btn.classList.toggle('border-secondary', !isActive);
                btn.classList.toggle('text-secondary', !isActive);
            });

            tabPanels.forEach(panel => {
                panel.classList.toggle('hidden', panel.id !== targetId);
            });
        }

        tabButtons.forEach(btn => {
            btn.addEventListener('click', () => activateTab(btn.dataset.tabTarget));
        });

        activateTab(document.querySelector('.tab-trigger.tab-active')?.dataset.tabTarget || 'tab-bookings');

        // Pagination AJAX for bookings table
        const wrapper = document.getElementById('bookings-wrapper');

        if (wrapper) {
            wrapper.addEventListener('click', function(e) {
                const link = e.target.closest('.pagination-links a');
                if (link) {
                    e.preventDefault();
                    const url = link.getAttribute('href');
                    fetchBookings(url);
                }
            });
        }

        async function fetchBookings(url) {
            try {
                // Optional: Add a loading state
                wrapper.style.opacity = '0.5';
                wrapper.style.pointerEvents = 'none';

                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const html = await response.text();
                wrapper.innerHTML = html;
                
                // Update URL in browser without reload
                window.history.pushState({}, '', url);

                // Restore state
                wrapper.style.opacity = '1';
                wrapper.style.pointerEvents = 'auto';
                
                // Scroll to top of table
                wrapper.scrollIntoView({ behavior: 'smooth', block: 'start' });

            } catch (error) {
                console.error('Error fetching bookings:', error);
                wrapper.style.opacity = '1';
                wrapper.style.pointerEvents = 'auto';
                alert('Failed to load bookings. Please try again.');
            }
        }
    });
</script>
@endsection
