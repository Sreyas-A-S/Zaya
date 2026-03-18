@extends('layouts.client')

@section('title', 'Conference History')

@section('content')
<!-- Mobile Tab Navigation -->
<div class="lg:hidden flex space-x-6 overflow-x-auto scrollbar-hide mb-5">
    <a href="{{ route('dashboard') }}"
        class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Dashboard</a>
    <a href="{{ route('bookings.index') }}"
        class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Bookings</a>
    <a href="{{ route('conferences.index') }}"
        class="leading-none text-lg text-secondary font-normal whitespace-nowrap cursor-pointer transition-colors border-b-2 border-secondary pb-1">Conferences</a>
</div>

<!-- Conference History Content -->
<div id="table-wrapper" class="transition-opacity duration-300">
    @include('partials.conferences-table')
</div>

<div class="h-10"></div>
@endsection

@section('scripts')
<script>
    document.addEventListener('click', function(e) {
        const link = e.target.closest('.pagination-links a');
        if (link) {
            e.preventDefault();
            const url = link.href;
            fetchConferences(url);
            window.history.pushState({}, '', url);
        }
    });

    async function fetchConferences(url) {
        const wrapper = document.getElementById('table-wrapper');
        wrapper.style.opacity = '0.5';
        wrapper.style.pointerEvents = 'none';

        try {
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const html = await response.text();
            wrapper.innerHTML = html;
            wrapper.style.opacity = '1';
            wrapper.style.pointerEvents = 'auto';
            
            // Scroll to top of table
            document.getElementById('conferences-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch (error) {
            console.error('Error fetching conferences:', error);
            wrapper.style.opacity = '1';
            wrapper.style.pointerEvents = 'auto';
        }
    }

    window.addEventListener('popstate', function() {
        fetchConferences(window.location.href);
    });
</script>
@endsection
