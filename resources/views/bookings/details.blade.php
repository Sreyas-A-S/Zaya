@extends('layouts.client')

@section('title', 'Booking Details')

@section('content')
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
                            <div class="flex items-center justify-between p-4 bg-[#F9FBF9] border border-[#2E4B3D]/12 rounded-2xl hover:border-secondary/20 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-secondary"></div>
                                    <span class="text-sm font-bold text-secondary">{{ $service->title }}</span>
                                </div>
                                <span class="text-[11px] font-black text-secondary/60">€{{ number_format($service->price ?? 0, 2) }}</span>
                            </div>
                            @empty
                            <p class="text-sm text-gray-400 italic">No specific services listed.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

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
            @if(in_array($user->role, ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist']) && $booking->profile_id === $user->profile_id)
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
                    <a href="{{ route('bookings.consultation-form.show', ['id' => $booking->id, 'form_id' => $form->id]) }}" 
                       class="flex items-center justify-between p-5 rounded-2xl border border-gray-50 bg-[#F9FBF9] hover:border-secondary/20 hover:bg-white transition-all group shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-secondary group-hover:scale-110 transition-transform">
                                <i class="ri-file-list-3-line"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-secondary">{{ $form->title ?: 'Consultation Form' }}</p>
                                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mt-0.5">{{ $form->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                        <i class="ri-arrow-right-s-line text-gray-300 group-hover:text-secondary transition-colors"></i>
                    </a>
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
                        <p class="font-black text-secondary leading-tight">{{ $booking->user->name }}</p>
                        <p class="text-xs font-medium text-gray-500 mt-1">{{ $booking->user->email }}</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="p-4 bg-white rounded-2xl border border-[#2E4B3D]/5 shadow-sm">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">Languages</p>
                        <div class="flex flex-wrap gap-1 mt-2">
                            @php $langs = is_array($booking->user->languages) ? $booking->user->languages : []; @endphp
                            @forelse($langs as $langId)
                                @php $l = \App\Models\Language::find($langId); @endphp
                                @if($l)
                                <span class="px-2 py-0.5 bg-gray-50 text-gray-600 text-[9px] font-black uppercase tracking-tighter rounded-full border border-gray-100">{{ $l->name }}</span>
                                @endif
                            @empty
                                <span class="text-xs text-gray-400">Not specified</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Consent Status -->
            <div class="bg-white rounded-[2.5rem] border border-[#2E4B3D]/12 p-5 md:p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Consent & Security</h3>
                    @if($hasConsent)
                        <div class="w-8 h-8 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center">
                            <i class="ri-checkbox-circle-line text-lg"></i>
                        </div>
                    @else
                        <div class="w-8 h-8 bg-orange-50 text-orange-500 rounded-full flex items-center justify-center">
                            <i class="ri-time-line text-lg"></i>
                        </div>
                    @endif
                </div>
                
                <div class="p-5 {{ $hasConsent ? 'bg-emerald-50/30 border-emerald-100' : 'bg-gray-50 border-gray-100' }} border rounded-[2rem]">
                    <div class="flex gap-3 mb-3">
                        <i class="ri-shield-keyhole-line {{ $hasConsent ? 'text-emerald-600' : 'text-gray-400' }} text-xl"></i>
                        <p class="text-xs font-black text-secondary uppercase tracking-tight">Data Sharing</p>
                    </div>
                    <p class="text-[11px] leading-relaxed {{ $hasConsent ? 'text-emerald-700' : 'text-gray-500' }} font-medium">
                        @if($hasConsent)
                            The client has granted explicit consent via OTP to share their health data and profile details with you.
                        @else
                            Consent for data sharing has not been verified yet. Data access may be restricted.
                        @endif
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="space-y-3">
                @if($booking->status === 'confirmed' && $booking->mode === 'online')
                    <a href="{{ route('conference.join', 'session-' . $booking->id) }}" class="w-full py-5 bg-secondary text-white rounded-[1.5rem] font-black text-sm hover:bg-primary transition-all shadow-2xl shadow-secondary/30 uppercase tracking-[0.2em] flex items-center justify-center gap-3">
                        <i class="ri-vidicon-line text-lg"></i>
                        Join Session
                    </a>
                @endif

                @if(in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']) && $booking->profile_id === $user->profile_id)
                    <button onclick="openReferModal({{ $booking->id }}, {{ $booking->user_id }})" class="w-full py-5 bg-white text-secondary border border-[#2E4B3D]/12 rounded-[1.5rem] font-black text-sm hover:bg-[#F9FBF9] transition-all uppercase tracking-[0.2em] flex items-center justify-center gap-3 shadow-sm">
                        <i class="ri-user-shared-line text-lg text-orange-500"></i>
                        Refer to Peer
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

@include('partials.refer-modal-scripts')

@endsection
