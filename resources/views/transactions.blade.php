@extends('layouts.client')

@section('title', 'Transaction Vault')

@section('content')

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
