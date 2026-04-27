@extends('layouts.client')

@section('title', 'Booking Details')

@section('content')
@php
    $isExpert = in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']);
@endphp
<div class="py-6 px-1 md:py-8 md:px-0">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
        <div>
            <nav class="flex mb-4 overflow-x-auto scrollbar-hide whitespace-nowrap" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('bookings.index') }}" class="text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-secondary transition-colors">Consultations</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="ri-arrow-right-s-line text-gray-300 mx-1"></i>
                            <span class="text-[10px] font-black uppercase tracking-widest text-secondary">Details</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <h1 class="text-2xl md:text-3xl font-black text-secondary tracking-tight">Session #{{ $booking->invoice_no }}</h1>
        </div>
        <div class="flex items-center gap-3">
            @if($booking->status === 'confirmed')
                <span class="px-4 py-2 bg-emerald-50 text-emerald-600 text-[11px] font-black uppercase tracking-widest rounded-full border border-emerald-100">Confirmed</span>
            @elseif($booking->status === 'completed')
                <span class="px-4 py-2 bg-blue-50 text-blue-600 text-[11px] font-black uppercase tracking-widest rounded-full border border-blue-100">Completed</span>
            @else
                <span class="px-4 py-2 bg-gray-50 text-gray-500 text-[11px] font-black uppercase tracking-widest rounded-full border border-gray-100">{{ ucfirst($booking->status) }}</span>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Details -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Summary Card -->
            <div class="bg-white rounded-[2.5rem] border border-[#2E4B3D]/12 overflow-hidden shadow-sm">
                <div class="p-5 md:p-8">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Booking Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#F9FBF9] border border-[#2E4B3D]/5 flex items-center justify-center text-secondary shadow-sm">
                                <i class="ri-calendar-event-line text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Date & Time</p>
                                <p class="text-sm font-black text-secondary">{{ $booking->booking_date->format('l, M d, Y') }}</p>
                                <p class="text-xs font-medium text-gray-500 mt-0.5">{{ $booking->booking_time }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-[#F9FBF9] border border-[#2E4B3D]/5 flex items-center justify-center text-secondary shadow-sm">
                                <i class="ri-vidicon-line text-xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Session Mode</p>
                                <p class="text-sm font-black text-secondary uppercase">{{ $booking->mode }}</p>
                                <p class="text-xs font-medium text-gray-500 mt-0.5">Secure Video Session</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-10 pt-8 border-t border-gray-50">
                        <h4 class="text-[10px] text-gray-400 font-black uppercase tracking-widest mb-4">Selected Services</h4>
                        <div class="space-y-3">
                            @forelse($services as $service)
                            @php
                                $isAssignedToMe = !empty($referredServiceIds) && in_array($service->id, $referredServiceIds);
                            @endphp
                            <div class="flex items-center justify-between p-4 {{ $isAssignedToMe ? 'bg-primary/5 border-primary/20 ring-1 ring-primary/10' : 'bg-[#F9FBF9] border-[#2E4B3D]/12' }} border rounded-2xl hover:border-secondary/20 transition-all group">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full {{ $isAssignedToMe ? 'bg-primary animate-pulse' : 'bg-secondary' }}"></div>
                                    <span class="text-sm font-bold {{ $isAssignedToMe ? 'text-primary' : 'text-secondary' }}">{{ $service->title }}</span>
                                    @if($isAssignedToMe)
                                        <span class="ml-2 text-[8px] font-black uppercase tracking-widest bg-primary text-white px-2 py-0.5 rounded-full">Your Assignment</span>
                                    @endif
                                </div>
                                @if(in_array($user->role, ['client', 'patient']))
                                <span class="text-[11px] font-black text-secondary/60">{{ get_currency_symbol($booking->currency) }}{{ number_format($service->price ?? 0, 2) }}</span>
                                @endif
                            </div>
                            @empty
                            <p class="text-sm text-gray-400 italic">No specific services listed.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>



            @if($isExpert && $userTransaction)
            <!-- Earnings Summary for Expert -->
            <div class="bg-secondary rounded-[2.5rem] border border-[#2E4B3D]/12 overflow-hidden shadow-2xl shadow-secondary/20 text-white p-8 relative group">
                <div class="flex justify-between items-center relative z-10">
                    <div>
                        <p class="text-[10px] font-black text-white/50 uppercase tracking-[0.2em] mb-2">
                            {{ $userTransaction->referrer_id === $user->id ? 'Your Referral Share' : 'Your Earned Share' }}
                        </p>
                        <p class="text-4xl font-black tracking-tight">{{ get_currency_symbol($userTransaction->currency) }} {{ number_format($shareAmount, 2) }}</p>
                    </div>
                    <button onclick="togglePageDistribution()" class="group/btn flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 transition-all border border-white/10 duration-300">
                        <i class="ri-pie-chart-line text-lg"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest">Breakdown</span>
                    </button>
                </div>

                <div id="page-distribution-info" class="hidden mt-8 pt-8 border-t border-white/10 space-y-6 animate-in fade-in slide-in-from-top-4 duration-500 relative z-10">
                    {{-- Breakdown of Client Payment --}}
                    <div>
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-4">Payment Breakdown</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                                <p class="text-[8px] font-black text-white/40 uppercase tracking-widest mb-1">Service Base</p>
                                <p class="text-sm font-black">{{ get_currency_symbol($booking->currency) }} {{ number_format($booking->subtotal, 2) }}</p>
                            </div>
                            
                            @if($booking->discount_amount > 0)
                            <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                                <p class="text-[8px] font-black text-white/40 uppercase tracking-widest mb-1">Promo ({{ $booking->promo_code }})</p>
                                <p class="text-sm font-black text-red-300">- {{ get_currency_symbol($booking->currency) }} {{ number_format($booking->discount_amount, 2) }}</p>
                            </div>
                            @endif

                            @if($booking->coin_discount > 0)
                            <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                                <p class="text-[8px] font-black text-white/40 uppercase tracking-widest mb-1">Coin Discount</p>
                                <p class="text-sm font-black text-red-300">- {{ get_currency_symbol($booking->currency) }} {{ number_format($booking->coin_discount, 2) }}</p>
                            </div>
                            @endif

                            <div class="p-4 bg-white/10 rounded-2xl border border-white/10 ring-1 ring-white/5">
                                <p class="text-[8px] font-black text-emerald-300 uppercase tracking-widest mb-1">Net Paid</p>
                                <p class="text-sm font-black text-emerald-400">{{ get_currency_symbol($booking->currency) }} {{ number_format($booking->total_price, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Distribution of Net Amount --}}
                    <div>
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-widest mb-4">Share Distribution</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @if($userTransaction->practitioner_id === $user->id)
                                <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                                    <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1">Zaya Platform Fee ({{ number_format($userTransaction->company_commission_percent, 1) }}%)</p>
                                    <p class="text-lg font-black text-red-300">- {{ get_currency_symbol($userTransaction->currency) }} {{ number_format($userTransaction->company_share, 2) }}</p>
                                </div>
                                @if($userTransaction->referrer_share > 0)
                                <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                                    <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1">Referral Payout ({{ number_format($userTransaction->referrer_commission_percent, 1) }}%)</p>
                                    <p class="text-lg font-black text-orange-300">- {{ get_currency_symbol($userTransaction->currency) }} {{ number_format($userTransaction->referrer_share, 2) }}</p>
                                </div>
                                @endif
                                <div class="p-4 bg-white/10 rounded-2xl border border-emerald-500/30">
                                    <p class="text-[9px] font-black text-emerald-400 uppercase tracking-widest mb-1">Your Earned Share</p>
                                    <p class="text-lg font-black text-white">{{ get_currency_symbol($userTransaction->currency) }} {{ number_format($userTransaction->practitioner_share, 2) }}</p>
                                </div>
                            @else
                                {{-- Referrer View --}}
                                <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                                    <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1">Referral Rate</p>
                                    <p class="text-lg font-black text-emerald-300">{{ number_format($userTransaction->referrer_commission_percent, 1) }}%</p>
                                </div>
                                <div class="p-4 bg-white/10 rounded-2xl border border-emerald-500/30">
                                    <p class="text-[9px] font-black text-emerald-400 uppercase tracking-widest mb-1">Your Referral Earning</p>
                                    <p class="text-lg font-black text-white">{{ get_currency_symbol($userTransaction->currency) }} {{ number_format($userTransaction->referrer_share, 2) }}</p>
                                </div>
                                <div class="p-4 bg-white/5 rounded-2xl border border-white/5">
                                    <p class="text-[9px] font-black text-white/40 uppercase tracking-widest mb-1">Status</p>
                                    <p class="text-lg font-black uppercase text-emerald-300">{{ $userTransaction->status }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Decorative elements -->
                <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full blur-3xl group-hover:bg-white/10 transition-colors duration-700"></div>
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
            </div>

            <script>
                function togglePageDistribution() {
                    const info = document.getElementById('page-distribution-info');
                    if (info) {
                        info.classList.toggle('hidden');
                    }
                }
            </script>
            @endif

            <!-- Referral History -->
            <div class="bg-white rounded-[2.5rem] border border-[#2E4B3D]/12 overflow-hidden shadow-sm p-5 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div>
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Referral History</h3>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                <span class="text-secondary/40">Origin:</span> 
                                <span class="text-secondary">{{ $firstPractitioner }}</span>
                            </p>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">
                                <span class="text-secondary/40">Viewer:</span> 
                                <span class="text-secondary">{{ $user->name }}</span>
                            </p>
                        </div>
                    </div>
                    <i class="ri-history-line text-gray-300 text-xl hidden md:block"></i>
                </div>

                <div class="relative space-y-8 before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-100 before:to-transparent">
                    @foreach($referralChain as $node)
                    @php
                        $isCurrentUserNode = false;
                        if (isset($node['booking']) && $node['booking']) {
                            $isCurrentUserNode = ($node['booking']->practitioner->user_id ?? 0) === $user->id;
                        } elseif (isset($node['referral']) && $node['referral']) {
                            $isCurrentUserNode = ($node['referral']->referred_to_id ?? 0) === $user->id;
                        }
                    @endphp
                    <div class="relative flex items-center justify-between md:justify-center group is-active">
                        <!-- Content Left (for even items on md) -->
                        <div class="hidden md:block md:w-[45%] md:pr-8 text-right">
                            @if($loop->even)
                                <div class="p-5 rounded-3xl border {{ $node['type'] === 'current' ? 'border-secondary/20 bg-secondary/5' : ($isCurrentUserNode ? 'border-primary/20 bg-primary/5' : 'border-gray-50 bg-[#F9FBF9]') }} shadow-sm transition-all">
                                    <div class="flex items-center justify-end gap-2 mb-1">
                                        @if(isset($node['date']))
                                        <time class="text-[9px] font-bold text-gray-400">{{ $node['date']->format('M d, Y') }}</time>
                                        @endif
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                            {{ $node['type'] === 'parent' ? 'Referred From' : ($node['type'] === 'current' ? 'Current Session' : 'Referred To') }}
                                        </p>
                                    </div>
                                    <div class="flex items-center justify-end gap-3">
                                        @if($node['type'] === 'child' && $node['status'] === 'paid')
                                            <i class="ri-checkbox-circle-fill text-emerald-500" title="Booking Confirmed"></i>
                                        @endif
                                        <p class="text-sm font-black text-secondary">
                                            {{ $node['practitioner'] }}
                                            @if($isCurrentUserNode)
                                                <span class="ml-1 text-[9px] bg-primary text-white px-1.5 py-0.5 rounded-full uppercase tracking-tighter">You</span>
                                            @endif
                                        </p>
                                    </div>
                                    @if(isset($node['booking']) && $node['booking'])
                                        <p class="text-[10px] text-gray-400 mt-1">Ref: #{{ $node['booking']->invoice_no }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <!-- Dot -->
                        <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white shadow-sm shrink-0 {{ $node['type'] === 'current' ? 'bg-secondary text-white' : ($isCurrentUserNode ? 'bg-primary text-white' : 'bg-gray-50 text-gray-400') }} z-10 transition-all">
                            @if($node['type'] === 'parent')
                                <i class="ri-arrow-up-line"></i>
                            @elseif($node['type'] === 'current')
                                <i class="ri-focus-2-line"></i>
                            @else
                                <i class="ri-arrow-down-line"></i>
                            @endif
                        </div>

                        <!-- Content Right (for mobile AND odd items on md) -->
                        <div class="w-[calc(100%-3.5rem)] md:w-[45%] md:pl-8">
                            @if($loop->odd || request()->is('mobile*'))
                                <div class="p-4 md:p-5 rounded-3xl border {{ $node['type'] === 'current' ? 'border-secondary/20 bg-secondary/5' : ($isCurrentUserNode ? 'border-primary/20 bg-primary/5' : 'border-gray-50 bg-[#F9FBF9]') }} shadow-sm transition-all">
                                    <div class="flex items-center justify-between gap-2 mb-1">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                            {{ $node['type'] === 'parent' ? 'Referred From' : ($node['type'] === 'current' ? 'Current Session' : 'Referred To') }}
                                        </p>
                                        @if(isset($node['date']))
                                        <time class="text-[9px] font-bold text-gray-400">{{ $node['date']->format('M d, Y') }}</time>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <p class="text-sm font-black text-secondary">
                                            {{ $node['practitioner'] }}
                                            @if($isCurrentUserNode)
                                                <span class="ml-1 text-[9px] bg-primary text-white px-1.5 py-0.5 rounded-full uppercase tracking-tighter">You</span>
                                            @endif
                                        </p>
                                        @if($node['type'] === 'child' && $node['status'] === 'paid')
                                            <i class="ri-checkbox-circle-fill text-emerald-500" title="Booking Confirmed"></i>
                                        @endif
                                    </div>
                                    @if(isset($node['booking']) && $node['booking'])
                                        <p class="text-[10px] text-gray-400 mt-1">Ref: #{{ $node['booking']->invoice_no }}</p>
                                    @endif
                                </div>
                            @endif
                            {{-- On mobile, we always want the content here --}}
                            <div class="md:hidden">
                                @if($loop->even)
                                    <div class="p-4 rounded-3xl border {{ $node['type'] === 'current' ? 'border-secondary/20 bg-secondary/5' : ($isCurrentUserNode ? 'border-primary/20 bg-primary/5' : 'border-gray-50 bg-[#F9FBF9]') }} shadow-sm transition-all">
                                        <div class="flex items-center justify-between gap-2 mb-1">
                                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">
                                                {{ $node['type'] === 'parent' ? 'Referred From' : ($node['type'] === 'current' ? 'Current Session' : 'Referred To') }}
                                            </p>
                                            @if(isset($node['date']))
                                            <time class="text-[9px] font-bold text-gray-400">{{ $node['date']->format('M d, Y') }}</time>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <p class="text-sm font-black text-secondary">
                                                {{ $node['practitioner'] }}
                                                @if($isCurrentUserNode)
                                                    <span class="ml-1 text-[9px] bg-primary text-white px-1.5 py-0.5 rounded-full uppercase tracking-tighter">You</span>
                                                @endif
                                            </p>
                                            @if($node['type'] === 'child' && $node['status'] === 'paid')
                                                <i class="ri-checkbox-circle-fill text-emerald-500" title="Booking Confirmed"></i>
                                            @endif
                                        </div>
                                        @if(isset($node['booking']) && $node['booking'])
                                            <p class="text-[10px] text-gray-400 mt-1">Ref: #{{ $node['booking']->invoice_no }}</p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Consultation Forms Section -->
            @if($isExpert && ($hasConsent || $booking->profile_id === $user->profile_id))
            <div class="bg-white rounded-[2.5rem] border border-[#2E4B3D]/12 overflow-hidden shadow-sm p-5 md:p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Consultation Records</h3>
                        <p class="text-[10px] text-gray-400 font-bold mt-1">Clinical notes and follow-up records</p>
                    </div>
                    <a href="{{ route('bookings.consultation-form.show', ['id' => $booking->id, 'new' => 1]) }}" class="w-10 h-10 rounded-full bg-secondary/5 text-secondary flex items-center justify-center hover:bg-secondary hover:text-white transition-all">
                        <i class="ri-add-line text-xl"></i>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @forelse($booking->consultationForms as $form)
                    <div class="flex flex-col p-5 rounded-2xl border border-gray-50 bg-[#F9FBF9] hover:border-secondary/20 hover:bg-white transition-all group shadow-sm">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-secondary group-hover:scale-110 transition-transform">
                                    <i class="ri-file-list-3-line"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-secondary">{{ $form->title ?: 'Consultation Form' }}</p>
                                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $form->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2 mt-auto">
                            <a href="{{ route('bookings.consultation-form.show', ['id' => $booking->id, 'form_id' => $form->id, 'view' => 1]) }}" 
                               class="flex-1 py-2 px-3 bg-white border border-gray-100 rounded-xl text-[10px] font-black text-secondary uppercase tracking-widest hover:bg-secondary hover:text-white transition-all text-center">
                                <i class="ri-eye-line mr-1"></i> View
                            </a>
                            @if($isExpert)
                            <a href="{{ route('bookings.consultation-form.show', ['id' => $booking->id, 'form_id' => $form->id]) }}" 
                               class="flex-1 py-2 px-3 bg-white border border-gray-100 rounded-xl text-[10px] font-black text-secondary uppercase tracking-widest hover:bg-secondary hover:text-white transition-all text-center">
                                <i class="ri-edit-line mr-1"></i> Edit
                            </a>
                            <form action="{{ route('bookings.consultation-form.destroy', ['id' => $booking->id, 'form_id' => $form->id]) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this record?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-red-400 hover:text-red-600 transition-colors">
                                    <i class="ri-delete-bin-line"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-8 text-center bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">No consultation forms recorded yet</p>
                        <a href="{{ route('bookings.consultation-form.show', $booking->id) }}" class="inline-block mt-4 text-xs font-black text-secondary hover:underline">Create First Record →</a>
                    </div>
                    @endforelse
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-8">
            <!-- Client Card -->
            <div class="bg-[#F9FBF9] rounded-[2.5rem] border border-[#2E4B3D]/12 p-5 md:p-8 shadow-sm">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Client Profile</h3>
                <div class="flex items-center gap-4 mb-6">
                    <img src="{{ $booking->user->profile_pic ? (str_starts_with($booking->user->profile_pic, 'http') ? $booking->user->profile_pic : asset('storage/' . $booking->user->profile_pic)) : asset('frontend/assets/profile-dummy-img.png') }}" 
                         class="w-16 h-16 rounded-2xl object-cover border-2 border-white shadow-sm">
                    <div>
                        @if($hasConsent || $isDirectParticipant)
                            <p class="font-black text-secondary leading-tight">{{ $booking->user->name }}</p>
                            <p class="text-xs font-medium text-gray-500 mt-1">{{ $booking->user->email }}</p>
                        @else
                            <p class="font-black text-secondary leading-tight">Patient #{{ $booking->user->id }}</p>
                            <p class="text-xs font-medium text-gray-400 mt-1">Verify OTP to view details</p>
                        @endif
                    </div>
                </div>
                <div class="space-y-4">
                    @if($hasConsent || $isDirectParticipant)
                    <div class="p-4 bg-white rounded-2xl border border-[#2E4B3D]/5 shadow-sm">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Languages</p>
                        <div class="flex flex-wrap gap-1 mt-2">
                            @php 
                                $langs = is_array($booking->user->languages) ? $booking->user->languages : [];
                                if (empty($langs) && $booking->user->patient) {
                                    $langs = is_array($booking->user->patient->languages_spoken) ? $booking->user->patient->languages_spoken : [];
                                }
                            @endphp
                            @forelse($langs as $lang)
                                @php 
                                    $displayValue = null;
                                    if (is_numeric($lang)) {
                                        $l = \App\Models\Language::find($lang);
                                        $displayValue = $l ? $l->name : null;
                                    } elseif (is_string($lang)) {
                                        $displayValue = $lang;
                                    } elseif (is_array($lang)) {
                                        $displayValue = $lang['name'] ?? $lang['title'] ?? null;
                                    }
                                @endphp
                                @if($displayValue)
                                    <span class="px-2 py-0.5 bg-gray-50 text-gray-600 text-[9px] font-black uppercase tracking-tighter rounded-full border border-gray-100">{{ $displayValue }}</span>
                                @endif
                            @empty
                                <span class="text-xs text-gray-400">Not specified</span>
                            @endforelse
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- GDPR Data Sharing Access -->


            @if($isExpert && Auth::id() !== $booking->user_id)
            <div class="bg-white rounded-[2.5rem] border border-[#2E4B3D]/12 p-5 md:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">GDPR Control Center</h3>
                    @if($hasConsent)
                        <div class="w-8 h-8 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center">
                            <i class="ri-shield-check-line text-lg"></i>
                        </div>
                    @else
                        <div class="w-8 h-8 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center animate-pulse">
                            <i class="ri-shield-flash-line text-lg"></i>
                        </div>
                    @endif
                </div>
                
                <div class="space-y-4">
                    <div class="p-5 {{ $hasConsent ? 'bg-emerald-50/30 border-emerald-100' : 'bg-gray-50 border-gray-100' }} border rounded-[2rem] transition-all duration-500">
                        <div class="flex gap-3 mb-3">
                            <i class="ri-shield-keyhole-line {{ $hasConsent ? 'text-emerald-600' : 'text-gray-400' }} text-xl"></i>
                            <p class="text-xs font-black text-secondary uppercase tracking-tight">Data Sharing Access</p>
                        </div>
                        <p class="text-[11px] leading-relaxed {{ $hasConsent ? 'text-emerald-700' : 'text-gray-500' }} font-medium">
                            @if($hasConsent)
                                Access Verified. You can now view the client's comprehensive health journey, including history and recordings.
                            @else
                                Data Sharing is currently restricted. You need the client's explicit OTP consent to view their clinical history.
                            @endif
                        </p>
                    </div>

                    @if($hasConsent)
                        <a href="{{ route('client.profile.view', $booking->user_id) }}" class="w-full py-4 bg-[#F9FBF9] text-secondary border border-[#2E4B3D]/12 rounded-2xl font-black text-[10px] hover:bg-secondary hover:text-white transition-all uppercase tracking-[0.2em] flex items-center justify-center gap-2 group">
                            View Health Journey
                            <i class="ri-arrow-right-line group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    @else
                        <button onclick="requestDataAccess({{ $booking->user_id }})" class="w-full py-4 bg-orange-500 text-white rounded-2xl font-black text-[10px] hover:bg-orange-600 transition-all uppercase tracking-[0.2em] flex items-center justify-center gap-2 shadow-lg shadow-orange-200">
                            <i class="ri-lock-unlock-line"></i>
                            Unlock Data Access
                        </button>
                    @endif
                </div>
            </div>
            @endif

            <!-- Consent Status (Client View) -->
            @if(Auth::id() === $booking->user_id)
            <div class="bg-white rounded-[2.5rem] border border-[#2E4B3D]/12 p-5 md:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Privacy Settings</h3>
                    <div class="w-8 h-8 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center">
                        <i class="ri-user-settings-line text-lg"></i>
                    </div>
                </div>
                <div class="p-5 bg-blue-50/30 border border-blue-100 rounded-[2rem]">
                    <p class="text-[11px] leading-relaxed text-blue-700 font-medium mb-4">
                        Manage how your health data is shared with the practitioner for this consultation.
                    </p>
                    <a href="{{ route('health-journey.index') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline flex items-center gap-1">
                        Go to Privacy Dashboard <i class="ri-arrow-right-line"></i>
                    </a>
                </div>
            </div>
            @endif

            <!-- Action Buttons -->
            <div class="space-y-3">
                @if(in_array($booking->status, ['pending', 'confirmed', 'paid']) && $booking->mode === 'online')
                    <a href="{{ route('conference.join', ['channel' => $booking->invoice_no ?? 'session-' . $booking->id, 'provider' => 'jaas']) }}" class="w-full py-5 bg-secondary text-white rounded-[1.5rem] font-black text-sm hover:bg-primary transition-all shadow-2xl shadow-secondary/30 uppercase tracking-[0.2em] flex items-center justify-center gap-3">
                        <i class="ri-vidicon-line text-lg"></i>
                        Join Session
                    </a>
                @endif

                @if(in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']) && $booking->profile_id === $user->profile_id)
                    @if(!$booking->translator_id)
                        <button onclick="openTranslatorModal({{ $booking->id }}, '{{ $booking->from_language ?: 'English' }}', '{{ $booking->to_language ?: 'Any' }}')" class="w-full py-5 bg-blue-600 text-white rounded-[1.5rem] font-black text-sm hover:bg-blue-700 transition-all uppercase tracking-[0.2em] flex items-center justify-center gap-3 shadow-lg shadow-blue-200">
                            <i class="ri-translate text-lg"></i>
                            {{ $booking->need_translator ? 'Assign Translator' : 'Request Translator' }}
                        </button>
                    @endif
                    
                    <button onclick="openReferModal({{ $booking->id }}, {{ $booking->user_id }})" class="w-full py-5 bg-white text-secondary border border-[#2E4B3D]/12 rounded-[1.5rem] font-black text-sm hover:bg-[#F9FBF9] transition-all uppercase tracking-[0.2em] flex items-center justify-center gap-3 shadow-sm">
                        <i class="ri-user-shared-line text-lg text-orange-500"></i>
                        Refer to Peer
                    </button>
                @endif
            </div>

            <!-- Translator Info Card -->
            @if($booking->translator_id)
            <div class="bg-white rounded-[2.5rem] border border-[#2E4B3D]/12 p-5 md:p-8 shadow-sm">
                <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-6">Assigned Translator</h3>
                <div class="flex items-center gap-4">
                    <img src="{{ $booking->translator->user->profile_pic ? (str_starts_with($booking->translator->user->profile_pic, 'http') ? $booking->translator->user->profile_pic : asset('storage/' . $booking->translator->user->profile_pic)) : asset('frontend/assets/profile-dummy-img.png') }}" 
                         class="w-14 h-14 rounded-2xl object-cover border-2 border-white shadow-sm">
                    <div>
                        <p class="font-black text-secondary leading-tight">{{ $booking->translator->full_name }}</p>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-1">Experience: {{ $booking->translator->years_of_experience }} Years</p>
                    </div>
                </div>
                @if(in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']) && $booking->profile_id === $user->profile_id)
                <button onclick="openTranslatorModal({{ $booking->id }}, '{{ $booking->from_language }}', '{{ $booking->to_language }}')" class="mt-6 text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Change Translator →</button>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>

@include('partials.refer-modal-scripts')

<script>
    function requestDataAccess(clientId) {
        openDataAccessRequestModal(clientId);
    }
</script>

@endsection
