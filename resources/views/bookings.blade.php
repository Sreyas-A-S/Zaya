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
</div>

<!-- Bookings Content -->
<div id="bookings-wrapper">
    @include('partials.bookings-table')
</div>

<div class="h-10"></div>
@endsection

@section('scripts')
<script>
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
