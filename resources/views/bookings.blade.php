@extends('layouts.client')

@section('title', 'My Bookings')

@section('styles')
<style>
    /* Time Picker Dropdown Styles */
    .time-picker-dropdown {
        position: fixed;
        z-index: 1100;
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 10px 50px rgba(0, 0, 0, 0.2);
        padding: 0;
        width: 320px;
        max-height: 400px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05);
    }
    /* Loading state for the time‑picker dropdown */
    .time-picker-dropdown.loading {
        opacity: 0;
        pointer-events: none;
    }
    .time-picker-dropdown:not(.loading) {
        opacity: 1;
        transition: opacity 0.2s ease-in-out;
    }
    .time-picker-content {
        padding: 24px;
        overflow-y: auto;
        flex: 1;
        scrollbar-width: thin;
    }
    .time-picker-header { text-align: center; margin-bottom: 20px; }
    .time-picker-title { font-size: 14px; font-weight: 800; color: #2E4B3D; text-transform: uppercase; tracking: 0.05em; }
    .time-slots-grid { 
        display: grid; 
        grid-template-columns: repeat(3, 1fr); 
        gap: 10px; 
        margin-bottom: 10px; 
    }
    .time-slot {
        padding: 12px 4px;
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        color: #4B5563;
        background: #F9FAFB;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid #F3F4F6;
    }
    .time-slot:hover { background-color: #F3F4F6; color: #111; border-color: #E5E7EB; }
    .time-slot.selected {
        background-color: #2E4B3D;
        color: #fff;
        border-color: #2E4B3D;
        box-shadow: 0 4px 12px rgba(46, 75, 61, 0.2);
    }
    .time-slot.booked {
        background-color: #fee2e2 !important;
        color: #991b1b !important;
        border: 1px solid #fecaca !important;
        cursor: not-allowed !important;
        opacity: 0.6;
    }
    .time-picker-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 24px;
        background: #F9FAFB;
        border-top: 1px solid #F3F4F6;
    }
    .time-btn-clear { background: none; border: none; color: #9CA3AF; font-size: 11px; font-weight: 800; text-transform: uppercase; cursor: pointer; }
    .time-btn-set {
        background-color: #2E4B3D;
        color: white;
        border: none;
        border-radius: 12px;
        padding: 8px 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.2s;
    }
    .time-btn-set:hover { background-color: #1a2e25; transform: translateY(-1px); }

    @keyframes calFadeInDown {
        from { opacity: 0; transform: translateY(-6px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes calFadeInUp {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection


@section('content')

<!-- Bookings Content -->
<div id="bookings-wrapper">
    @include('partials.bookings-table')
</div>

<!-- Reschedule Modal -->
<div id="reschedule-modal" class="fixed inset-0 z-[999] hidden" aria-labelledby="reschedule-modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeRescheduleModal()"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
            <div class="relative inline-block text-left align-bottom transition-all transform bg-white rounded-[2rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-md sm:w-full border border-[#2E4B3D]/12">
                <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50 rounded-t-[2rem]">
                    <div>
                        <h3 class="text-xl font-black text-secondary leading-tight" id="reschedule-modal-title">Reschedule Consultation</h3>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Select new date and time</p>
                    </div>
                    <button onclick="closeRescheduleModal()" class="w-10 h-10 flex items-center justify-center rounded-full bg-white border border-gray-100 text-gray-400 hover:text-secondary hover:border-secondary/20 transition-all shadow-sm">
                        <i class="ri-close-line text-xl"></i>
                    </button>
                </div>
                <form id="reschedule-form" class="px-8 py-8">
                    @csrf
                    <input type="hidden" id="reschedule-booking-id">
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-secondary uppercase tracking-widest mb-3 opacity-60">New Booking Date</label>
                            <div class="relative group">
                                <i class="ri-calendar-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-colors"></i>
                                <input type="date" id="reschedule-date" name="booking_date" required
                                    class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium outline-none focus:border-secondary focus:bg-white transition-all shadow-sm min-h-[52px]"
                                    min="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-secondary uppercase tracking-widest mb-3 opacity-60">New Booking Time</label>
                            <div class="relative group">
                                <div id="reschedule-time-trigger" class="time-picker-trigger w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-100 rounded-2xl text-sm font-medium outline-none focus:border-secondary focus:bg-white transition-all shadow-sm min-h-[52px] cursor-pointer flex items-center"
                                    onclick="toggleRescheduleTimePicker(this)">
                                    <i class="ri-time-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-colors z-10 pointer-events-none"></i>
                                    <span class="text-sm text-gray-400 time-label">Select a time slot</span>
                                </div>
                                <input type="hidden" id="reschedule-time" name="booking_time" required class="time-value">
                                
                                <div class="time-picker-dropdown hidden">
                                    <div class="time-picker-content"></div>
                                </div>
                            </div>
                            <p class="text-[9px] text-gray-400 mt-2 italic font-medium">Please select a new time slot from the available options.</p>
                        </div>
                    </div>

                    <div class="mt-10 flex flex-col gap-3">
                        <button type="submit" id="reschedule-submit-btn" 
                            class="w-full py-4 bg-secondary text-white rounded-2xl text-sm font-black uppercase tracking-widest hover:bg-primary shadow-lg shadow-secondary/20 transition-all flex items-center justify-center gap-2 group">
                            <span>Confirm Reschedule</span>
                            <i class="ri-arrow-right-line group-hover:translate-x-1 transition-transform"></i>
                        </button>
                        <button type="button" onclick="closeRescheduleModal()" 
                            class="w-full py-4 bg-white text-gray-400 border border-gray-100 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:text-secondary hover:border-secondary/20 transition-all">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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

@include('partials.refer-modal-scripts')

<div class="h-10"></div>
@endsection

@section('scripts')
<script>
    // Search & AJAX logic
    (function() {
        let searchTimer;
        const searchInput = document.getElementById('bookings-search');
        const loader = document.getElementById('search-loader');
        const container = document.getElementById('bookings-wrapper');

        function fetchBookings(url) {
            if (loader) loader.classList.remove('hidden');
            
            fetch(url, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.text())
            .then(html => {
                container.innerHTML = html;
                // Re-bind search input after table replacement
                rebindSearch();
            })
            .finally(() => {
                if (loader) loader.classList.add('hidden');
            });
        }

        function rebindSearch() {
            const newSearch = document.getElementById('bookings-search');
            if (newSearch) {
                newSearch.addEventListener('input', function() {
                    clearTimeout(searchTimer);
                    searchTimer = setTimeout(() => {
                        const val = this.value;
                        const url = new URL(window.location.href);
                        url.searchParams.set('search', val);
                        url.searchParams.delete('page'); // Reset to page 1
                        
                        window.history.pushState({}, '', url);
                        fetchBookings(url.toString());
                    }, 500);
                });
                // Focus at end of text
                newSearch.focus();
                const val = newSearch.value;
                newSearch.value = '';
                newSearch.value = val;
            }
            
            // Handle pagination clicks
            document.querySelectorAll('.pagination-links a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    window.history.pushState({}, '', this.href);
                    fetchBookings(this.href);
                });
            });

            // (Removed redundant manual binding, layout uses event delegation on document)
        }



        // Initialize
        rebindSearch();
    })();

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
        const allMenus = document.querySelectorAll('.action-dropdown .dropdown-menu');
        
        function closeAllMenus() {
            document.querySelectorAll('.action-dropdown .dropdown-menu').forEach(m => {
                m.classList.add('hidden');
                m.style.position = '';
                m.style.top = '';
                m.style.left = '';
                m.style.right = '';
                m.style.bottom = '';
            });
        }

        window.addEventListener('scroll', closeAllMenus, true);
        window.addEventListener('resize', closeAllMenus);

        document.addEventListener('click', function(e) {
            const trigger = e.target.closest('.dropdown-trigger');
            
            if (trigger) {
                const menu = trigger.nextElementSibling;
                const isOpen = !menu.classList.contains('hidden');
                
                closeAllMenus();
                
                if (!isOpen) {
                    menu.classList.remove('hidden');
                    
                    // Use fixed positioning to escape overflow-hidden container
                    const triggerRect = trigger.getBoundingClientRect();
                    menu.style.position = 'fixed';
                    menu.style.right = (window.innerWidth - triggerRect.right) + 'px';
                    
                    // Collision check
                    menu.style.top = 'auto';
                    menu.style.bottom = 'auto';
                    
                    // We need layout context to check height
                    const menuRect = menu.getBoundingClientRect();
                    if ((triggerRect.bottom + menuRect.height + 10) > window.innerHeight && triggerRect.top > menuRect.height) {
                        // Pop upwards
                        menu.style.bottom = (window.innerHeight - triggerRect.top + 8) + 'px';
                    } else {
                        // Pop downwards
                        menu.style.top = (triggerRect.bottom + 8) + 'px';
                    }
                }
                e.stopPropagation();
            } else if (!e.target.closest('.dropdown-menu')) {
                closeAllMenus();
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

        // Pagination AJAX for bookings table
    });

    let CURRENT_RESCHEDULE_PRACTITIONER_ID = null;
    const SHORT_MONTH_NAMES = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    // Reschedule Logic
    function openRescheduleModal(id, currentDate, currentTime, profileId) {
        CURRENT_RESCHEDULE_PRACTITIONER_ID = profileId;
        document.getElementById('reschedule-booking-id').value = id;
        document.getElementById('reschedule-date').value = currentDate;
        
        const trigger = document.getElementById('reschedule-time-trigger');
        const label = trigger.querySelector('.time-label');
        const hiddenInput = document.getElementById('reschedule-time');
        
        let timeToSet = currentTime;
        if (timeToSet && timeToSet.includes('-')) {
            timeToSet = timeToSet.split('-')[0].trim();
        }
        
        if (timeToSet) {
            label.textContent = timeToSet;
            label.classList.remove('text-gray-400');
            label.classList.add('text-gray-700');
            hiddenInput.value = timeToSet;
        } else {
            label.textContent = "Select a time slot";
            label.classList.add('text-gray-400');
            label.classList.remove('text-gray-700');
            hiddenInput.value = "";
        }
        
        document.getElementById('reschedule-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    async function toggleRescheduleTimePicker(trigger) {
        const container = trigger.closest('.relative');
        const dropdown = container.querySelector('.time-picker-dropdown');
        const content = container.querySelector('.time-picker-content');
        const dateInput = document.getElementById('reschedule-date');

        if (!dateInput || !dateInput.value) {
            if (typeof showToast === 'function') showToast('Please select a date first.', 'warning');
            else alert('Please select a date first.');
            return;
        }

        // Show loading state while fetching slots
        dropdown.classList.add('loading');

        if (dropdown.classList.contains('hidden')) {
            const parts = dateInput.value.split('-');
            const d = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, parseInt(parts[2]));
            const mon = SHORT_MONTH_NAMES[d.getMonth()];
            const dateLabel = `${mon} ${d.getDate()}, ${d.getFullYear()}`;

            const dStart = new Date(d);
            dStart.setHours(0, 0, 0, 0);
            const todayStart = new Date();
            todayStart.setHours(0, 0, 0, 0);
            const isToday = dStart.getTime() === todayStart.getTime();

            const timeInput = document.getElementById('reschedule-time');
            const selectedTime = timeInput.value;

            await renderRescheduleTimePicker(content, dateInput.value, selectedTime, isToday, dateLabel);
            dropdown.classList.remove('loading');
            dropdown.classList.remove('hidden');
            smartPosition(trigger, dropdown);
        } else {
            dropdown.classList.add('hidden');
            dropdown.classList.remove('cal-open-top', 'cal-open-bottom');
            dropdown.classList.remove('loading');
        }
    }

    async function renderRescheduleTimePicker(wrapper, dateValue, selectedTime, isToday, displayLabel) {
        wrapper.innerHTML = `
            <div class="flex flex-col items-center justify-center py-10 px-4">
                <div class="w-8 h-8 border-4 border-[#F5A623] border-t-transparent rounded-full animate-spin mb-3"></div>
                <p class="text-sm text-gray-500 font-medium">Fetching available slots...</p>
            </div>
        `;

        const now = new Date();
        const currentMinutes = now.getHours() * 60 + now.getMinutes();
        
        const slots = await fetchSlots(dateValue);
        const bookedSlots = await fetchBookedSlots(dateValue);

        if (!slots || slots.length === 0) {
            const isTodaySelected = new Date(dateValue).toDateString() === new Date().toDateString();
            const msg = isTodaySelected 
                ? "All slots for today have already passed. Please select another date."
                : "No available slots for this practitioner on the selected date.";
            wrapper.innerHTML = `<div class="text-center py-6 px-4 text-sm text-gray-500">${msg}</div>`;
            return;
        }

        let html = `
            <div class="time-picker-header">
                <div class="time-picker-title">Available Slots on ${displayLabel}</div>
            </div>
            <div class="time-slots-grid">
        `;

        slots.forEach(slot => {
            const slotMinutes = parseTimeToMinutes(slot);
            const isPast = isToday && (slotMinutes < currentMinutes);
            const isBooked = bookedSlots.includes(slot);

            if (isPast) {
                html += `<div class="time-slot disabled" title="Time has passed" style="opacity: 0.3; cursor: not-allowed; pointer-events: none;">${slot}</div>`;
            } else if (isBooked) {
                html += `<div class="time-slot booked" title="Already booked">${slot}</div>`;
            } else {
                const isSel = (slot === selectedTime) ? 'selected' : '';
                html += `<div class="time-slot ${isSel}" onclick="selectRescheduleTimeSlot(this)">${slot}</div>`;
            }
        });

        html += `
            </div>
            <div class="time-picker-footer">
                <button type="button" class="time-btn-clear" onclick="clearRescheduleTime(this)">Clear</button>
                <button type="button" class="time-btn-set" onclick="event.stopPropagation(); setRescheduleTime(this)">Set</button>
            </div>
        `;
        wrapper.innerHTML = html;
    }

    async function fetchSlots(dateStr) {
        if (!CURRENT_RESCHEDULE_PRACTITIONER_ID || !dateStr) return [];
        try {
            const res = await fetch(`/api/available-slots/${CURRENT_RESCHEDULE_PRACTITIONER_ID}/${dateStr}`);
            const data = await res.json();
            if (data && Array.isArray(data.slots)) {
                return data.slots.map(s => s.time).filter(Boolean);
            }
        } catch (e) { console.error('Slot fetch error', e); }
        return [];
    }

    async function fetchBookedSlots(dateStr) {
        if (!CURRENT_RESCHEDULE_PRACTITIONER_ID || !dateStr) return [];
        try {
            const res = await fetch(`/api/booked-slots/${CURRENT_RESCHEDULE_PRACTITIONER_ID}/${dateStr}`);
            const data = await res.json();
            return data.booked_slots || [];
        } catch (e) { console.error('Booked slot fetch error', e); return []; }
    }

    function selectRescheduleTimeSlot(slot) {
        const grid = slot.closest('.time-slots-grid');
        grid.querySelectorAll('.time-slot').forEach(s => s.classList.remove('selected'));
        slot.classList.add('selected');
    }

    function setRescheduleTime(btn) {
        const container = btn.closest('.relative');
        const dropdown = container.querySelector('.time-picker-dropdown');
        const trigger = document.getElementById('reschedule-time-trigger');
        const label = trigger.querySelector('.time-label');
        const hiddenInput = document.getElementById('reschedule-time');

        const selectedSlot = container.querySelector('.time-slot.selected');
        if (selectedSlot) {
            const val = selectedSlot.textContent.trim();
            label.textContent = val;
            label.classList.remove('text-gray-400');
            label.classList.add('text-gray-700');
            hiddenInput.value = val;
        }

        dropdown.classList.add('hidden');
        dropdown.classList.remove('cal-open-top', 'cal-open-bottom');
    }

    function clearRescheduleTime(btn) {
        const container = btn.closest('.relative');
        const dropdown = container.querySelector('.time-picker-dropdown');
        const trigger = document.getElementById('reschedule-time-trigger');
        const label = trigger.querySelector('.time-label');
        const hiddenInput = document.getElementById('reschedule-time');

        label.textContent = "Select a time slot";
        label.classList.remove('text-gray-700');
        label.classList.add('text-gray-400');
        hiddenInput.value = "";

        dropdown.classList.add('hidden');
        dropdown.classList.remove('cal-open-top', 'cal-open-bottom');
    }

    function smartPosition(trigger, dropdown) {
        const triggerRect = trigger.getBoundingClientRect();
        const dropdownWidth = 320;
        const dropdownHeight = dropdown.offsetHeight || 400;
        const margin = 8;

        // Calculate horizontal position (aligned to trigger left, but staying within viewport)
        let left = triggerRect.left;
        if (left + dropdownWidth > window.innerWidth) {
            left = window.innerWidth - dropdownWidth - 20;
        }
        if (left < 20) left = 20;

        // Calculate vertical position (prefer below, flip if no space)
        let top = triggerRect.bottom + margin;
        const spaceBelow = window.innerHeight - triggerRect.bottom;
        
        if (spaceBelow < dropdownHeight && triggerRect.top > dropdownHeight) {
            top = triggerRect.top - dropdownHeight - margin;
            dropdown.style.transformOrigin = 'bottom center';
            dropdown.style.animationName = 'calFadeInUp';
        } else {
            dropdown.style.transformOrigin = 'top center';
            dropdown.style.animationName = 'calFadeInDown';
        }

        dropdown.style.top = top + 'px';
        dropdown.style.left = left + 'px';
        dropdown.style.position = 'fixed';
    }

    function parseTimeToMinutes(timeStr) {
        if (!timeStr) return 0;
        const parts = timeStr.split(' ');
        const time = parts[0].split(':');
        let hours = parseInt(time[0]);
        const minutes = parseInt(time[1]);
        const period = parts[1];
        if (period === 'PM' && hours < 12) hours += 12;
        if (period === 'AM' && hours === 12) hours = 0;
        return hours * 60 + minutes;
    }

    // Close picker on outside click
    document.addEventListener('click', function(e) {
        const picker = document.querySelector('.time-picker-dropdown');
        const trigger = document.getElementById('reschedule-time-trigger');
        if (picker && !picker.classList.contains('hidden')) {
            if (!picker.contains(e.target) && !trigger.contains(e.target)) {
                picker.classList.add('hidden');
                picker.classList.remove('cal-open-top', 'cal-open-bottom');
            }
        }
    });

    function closeRescheduleModal() {
        document.getElementById('reschedule-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.getElementById('reschedule-form').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const id = document.getElementById('reschedule-booking-id').value;
        const submitBtn = document.getElementById('reschedule-submit-btn');
        const formData = new FormData(this);
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ri-loader-4-line animate-spin text-xl"></i> <span>Processing...</span>';
        
        try {
            const response = await fetch(`/bookings/${id}/reschedule`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                if (typeof showToast === 'function') showToast(result.message);
                else alert(result.message);
                
                closeRescheduleModal();
                // Refresh the table
                const url = new URL(window.location.href);
                fetchBookings(url.toString()); 
            } else {
                alert(result.message || 'Rescheduling failed.');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred while rescheduling.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span>Confirm Reschedule</span> <i class="ri-arrow-right-line"></i>';
        }
    });

    async function fetchBookings(url) {
        const wrapper = document.getElementById('bookings-wrapper');
        try {
            wrapper.style.opacity = '0.5';
            const response = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
            const html = await response.text();
            wrapper.innerHTML = html;
            wrapper.style.opacity = '1';
        } catch (e) {
            console.error(e);
            wrapper.style.opacity = '1';
        }
    }
</script>
@endsection
