@extends('layouts.client')

@section('title', 'My Bookings')

@section('content')
<!-- Mobile Tab Navigation -->
<div class="lg:hidden flex space-x-6 overflow-x-auto scrollbar-hide mb-5">
    <a href="{{ route('dashboard') }}"
        class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Dashboard</a>
    <button class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Health
        Journey</button>
    <a href="{{ route('bookings.index') }}"
        class="leading-none text-lg text-secondary font-normal whitespace-nowrap cursor-pointer transition-colors border-b-2 border-secondary pb-1">Bookings</a>
    <button class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Transaction
        Vault</button>
    <a href="{{ route('my-services.index') }}" class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">My Services</a>
    <a href="{{ route('profile') }}"
        class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">{{ __('Profile') }}</a>
</div>

<!-- Bookings Content -->
<div id="bookings-wrapper">
    @include('partials.bookings-table')
</div>

<!-- Booking Details Modal -->
<div id="booking-modal" class="fixed inset-0 z-[999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Background backdrop -->
    <div id="modal-backdrop" class="fixed inset-0 bg-black/50 transition-opacity"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
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

    // Close on backdrop click
    document.getElementById('modal-backdrop').addEventListener('click', closeBookingModal);

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
        const wrapper = document.getElementById('bookings-wrapper');

        wrapper.addEventListener('click', function(e) {
            const link = e.target.closest('.pagination-links a');
            if (link) {
                e.preventDefault();
                const url = link.getAttribute('href');
                fetchBookings(url);
            }
        });

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
