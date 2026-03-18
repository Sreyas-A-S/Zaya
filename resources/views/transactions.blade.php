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
<div id="transactions-container">
    <div class="bg-white rounded-2xl border border-[#2E4B3D]/12 overflow-hidden mb-8">
        <div class="p-6 border-b border-[#2E4B3D]/12">
            <h2 class="text-xl font-medium text-secondary">Transaction Vault</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9F9F9]">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Invoice ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2E4B3D]/12">
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-[#FDFDFD] transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-secondary">
                            {{ $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-secondary">
                            #{{ $invoice->id + 10000 }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $invoice->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            Session Payment - Order #{{ $invoice->razorpay_order_id ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-secondary font-medium">
                            € {{ number_format($invoice->total_price, 2) }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-bold rounded-full uppercase bg-green-50 text-green-600">
                                Paid
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            @if($invoice->razorpay_payment_url)
                                <a href="{{ $invoice->razorpay_payment_url }}" target="_blank" class="text-secondary hover:underline">View Receipt</a>
                            @else
                                <span class="text-gray-300">N/A</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="ri-wallet-line text-3xl text-gray-300"></i>
                                </div>
                                <p class="text-lg font-medium text-secondary mb-1">No transactions found</p>
                                <p class="text-sm text-gray-400">You haven't made any payments yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($invoices->hasPages())
        <div class="px-6 py-4 border-t border-[#2E4B3D]/12 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Showing <span class="font-medium text-secondary">{{ $invoices->firstItem() }}</span> to <span class="font-medium text-secondary">{{ $invoices->lastItem() }}</span> of <span class="font-medium text-secondary">{{ $invoices->total() }}</span> transactions
            </div>
            <div class="flex space-x-2 pagination-links">
                {{ $invoices->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<div class="h-10"></div>
@endsection
