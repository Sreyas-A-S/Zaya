@extends('layouts.client')

@section('title', 'My Bookings')

@section('content')

<!-- Tabs -->
<div class="bg-white rounded-2xl border border-[#2E4B3D]/12 mb-6">
    <div class="flex items-center gap-3 px-6 py-4">
        <button
            data-tab-target="tab-bookings"
            class="tab-trigger tab-active px-4 py-2 rounded-full text-sm font-medium transition-colors bg-secondary text-white shadow-sm">
            My Bookings
        </button>
        <button
            data-tab-target="tab-consultation-form"
            class="tab-trigger px-4 py-2 rounded-full text-sm font-medium transition-colors border border-secondary text-secondary hover:bg-secondary hover:text-white">
            Consultation Form
        </button>
    </div>
</div>

<!-- Bookings Content -->
<div id="tab-bookings" data-tab-panel>
    <div id="bookings-wrapper">
        @include('partials.bookings-table')
    </div>
</div>

<!-- Consultation Form Content -->
<div id="tab-consultation-form" data-tab-panel class="hidden">
    <div class="bg-white rounded-2xl border border-[#2E4B3D]/12 overflow-hidden">
        <div class="p-6 border-b border-[#2E4B3D]/12 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div>
                <h2 class="text-xl font-medium text-secondary">Consultation Form</h2>
                <p class="text-sm text-gray-500">Share a few details to start a new consultation request.</p>
            </div>
            <a href="{{ route('book-session') }}"
               class="inline-flex items-center justify-center px-4 py-2 rounded-full text-sm font-medium bg-secondary text-white hover:bg-primary transition-colors">
               Open Full Booking Page
            </a>
        </div>
        <div class="p-6">
            <form action="{{ route('book-session') }}" method="GET" class="space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" placeholder="Your name"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-secondary/40" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" placeholder="you@example.com"
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-secondary/40" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Preferred Mode</label>
                        <select name="mode" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-secondary/40">
                            <option value="online">Online (video / audio)</option>
                            <option value="onsite">In-person / clinic visit</option>
                            <option value="hybrid">Hybrid / to be decided</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">Preferred Date</label>
                            <input type="date" name="preferred_date" min="{{ now()->toDateString() }}"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-secondary/40">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-secondary mb-2">Preferred Time</label>
                            <input type="time" name="preferred_time"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-secondary/40">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Primary Concern</label>
                    <input type="text" name="concern" placeholder="e.g., Stress management, chronic pain, sleep issues"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-secondary/40">
                </div>

                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Additional Notes</label>
                    <textarea name="notes" rows="4" placeholder="Share any symptoms, goals, or practitioner preferences."
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-secondary/40"></textarea>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-3 rounded-full text-sm font-medium bg-secondary text-white hover:bg-primary transition-colors">
                        Continue
                    </button>
                    <span class="text-sm text-gray-500">You can adjust details on the next step.</span>
                </div>
            </form>
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

<div class="h-10"></div>
@endsection

@section('scripts')
<script>
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
