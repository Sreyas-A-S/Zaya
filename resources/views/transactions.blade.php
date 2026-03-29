@extends('layouts.client')

@section('title', 'Transaction Vault')

@section('content')

<!-- Summary Card -->
<div class="mb-8">
    <div class="bg-white rounded-[2rem] border border-[#2E4B3D]/12 p-8 shadow-sm relative overflow-hidden group">
        <div class="flex items-center justify-between relative z-10">
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Wallet Balance</p>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-black text-secondary tracking-tight">₹ {{ number_format($totalBalance, 2) }}</span>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">INR</span>
                </div>
            </div>
            <div class="w-16 h-16 bg-[#FFD166] rounded-2xl flex items-center justify-center text-white shadow-xl shadow-yellow-200/50 group-hover:scale-110 transition-transform duration-500">
                <i class="ri-wallet-3-fill text-3xl"></i>
            </div>
        </div>
        <!-- Decorative bg -->
        <div class="absolute -right-4 -bottom-4 w-32 h-32 bg-[#F9FBF9] rounded-full opacity-50 group-hover:scale-125 transition-transform duration-700"></div>
    </div>
</div>

<!-- Transactions Content -->
<div id="table-wrapper" class="transition-opacity duration-300">
    @include('partials.transactions-table')
</div>

<!-- Transaction Details Modal -->
<div id="transaction-modal" class="fixed inset-0 z-[999] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 transition-opacity" onclick="closeTransactionModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
            <div class="relative inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-[2.5rem] shadow-2xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-[#2E4B3D]/12">
                <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div>
                        <h3 class="text-xl font-black text-secondary tracking-tight">Transaction Details</h3>
                        <p id="modal-trx-no" class="text-[10px] text-gray-400 uppercase font-black tracking-widest mt-1">TRX-XXXXXXXX</p>
                    </div>
                    <button onclick="closeTransactionModal()" class="w-10 h-10 rounded-full bg-white border border-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition-all shadow-sm">
                        <i class="ri-close-line text-2xl"></i>
                    </button>
                </div>
                <div class="px-8 py-8" id="modal-content">
                    <!-- Dynamic content will be injected here -->
                </div>
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 text-center">
                    <button onclick="closeTransactionModal()" class="w-full py-4 bg-white border border-[#2E4B3D]/12 text-secondary rounded-2xl font-black text-sm hover:bg-[#F9FBF9] transition-all uppercase tracking-[0.2em] shadow-sm">
                        Close Details
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="h-10"></div>
@endsection

@section('scripts')
<script>
    function openTransactionModal(data) {
        document.getElementById('modal-trx-no').innerText = data.transaction_no;
        const content = document.getElementById('modal-content');
        
        let typeLabel = data.type === 'booking' ? 'Session Booking' : 'Specialist Referral';
        let shareLabel = '';
        let shareValue = 0;

        if (data.practitioner_id == {{ auth()->id() }}) {
            shareLabel = 'Your Practitioner Share';
            shareValue = data.practitioner_share;
        } else if (data.referrer_id == {{ auth()->id() }}) {
            shareLabel = 'Your Referrer Share';
            shareValue = data.referrer_share;
        } else {
            shareLabel = 'Total Amount Paid';
            shareValue = data.total_amount;
        }

        content.innerHTML = `
            <div class="space-y-6">
                <div class="flex items-center justify-between p-4 bg-[#F9FBF9] rounded-2xl border border-[#2E4B3D]/5">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Type</span>
                    <span class="text-xs font-black text-secondary uppercase">${typeLabel}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-[#F9FBF9] rounded-2xl border border-[#2E4B3D]/5">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Date</span>
                    <span class="text-xs font-bold text-gray-600">${new Date(data.created_at).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' })}</span>
                </div>
                <div class="flex items-center justify-between p-4 bg-[#F9FBF9] rounded-2xl border border-[#2E4B3D]/5">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Gross Amount</span>
                    <span class="text-xs font-bold text-gray-600">₹ ${parseFloat(data.total_amount).toFixed(2)}</span>
                </div>
                <div class="p-6 bg-secondary rounded-[2rem] text-center shadow-xl shadow-secondary/20">
                    <p class="text-[10px] font-black text-white/60 uppercase tracking-[0.2em] mb-2">${shareLabel}</p>
                    <p class="text-3xl font-black text-white tracking-tight">₹ ${parseFloat(shareValue).toFixed(2)}</p>
                </div>
                <div class="flex items-center justify-between p-4 border-t border-gray-100 pt-6">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Payment ID</span>
                    <span class="text-[10px] font-mono text-gray-500">${data.payment_id || 'N/A'}</span>
                </div>
            </div>
        `;

        document.getElementById('transaction-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeTransactionModal() {
        document.getElementById('transaction-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

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
