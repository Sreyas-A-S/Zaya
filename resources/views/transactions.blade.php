@extends('layouts.client')

@section('title', 'Transaction Vault')

@section('content')
<!-- Mobile Tab Navigation -->
<div class="lg:hidden flex space-x-6 overflow-x-auto scrollbar-hide mb-5">
    <a href="{{ route('dashboard') }}"
        class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Dashboard</a>
    <button class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Health
        Journey</button>
    <a href="{{ route('bookings.index') }}"
        class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Bookings</a>
    <a href="{{ route('transactions.index') }}"
        class="leading-none text-lg text-secondary font-normal whitespace-nowrap cursor-pointer transition-colors border-b-2 border-secondary pb-1">Transaction
        Vault</a>
</div>

<!-- Transactions Content -->
<div id="table-wrapper" class="transition-opacity duration-300">
    @include('partials.transactions-table')
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
            fetchTransactions(url);
            window.history.pushState({}, '', url);
        }
    });

    async function fetchTransactions(url) {
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
            document.getElementById('transactions-container').scrollIntoView({ behavior: 'smooth', block: 'start' });
        } catch (error) {
            console.error('Error fetching transactions:', error);
            wrapper.style.opacity = '1';
            wrapper.style.pointerEvents = 'auto';
        }
    }

    window.addEventListener('popstate', function() {
        fetchTransactions(window.location.href);
    });
</script>
@endsection
