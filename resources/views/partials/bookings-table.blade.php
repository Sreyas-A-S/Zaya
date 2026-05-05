<div id="bookings-container">
    <div class="bg-white rounded-3xl border border-[#2E4B3D]/12 mb-8 overflow-hidden">
        <div class="p-4 md:p-6 border-b border-[#2E4B3D]/12 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                @if($user->role === 'client' || $user->role === 'patient' || $user->role === 'translator')
                <h2 class="text-xl font-medium text-secondary">{{ $user->role === 'translator' ? 'Translation Sessions' : 'My Bookings' }}</h2>
                @else
                <h2 class="text-xl font-medium text-secondary">Sessions</h2>
                @endif
            </div>

            <!-- Search Bar -->
            <div class="relative w-full md:w-80 group">
                <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-secondary transition-colors"></i>
                <input type="text" id="bookings-search" 
                    value="{{ $search ?? '' }}"
                    placeholder="Search by ID, Practitioner or Client..." 
                    class="w-full pl-11 pr-12 py-3 bg-[#F9FBF9] border border-[#2E4B3D]/12 rounded-2xl text-sm outline-none focus:border-secondary focus:bg-white transition-all shadow-sm"
                    autocomplete="off">
                <div id="search-loader" class="absolute right-4 top-1/2 -translate-y-1/2 hidden">
                    <div class="animate-spin rounded-full h-4 w-4 border-2 border-secondary/20 border-b-secondary"></div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto scrollbar-hide">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-[#F9F9F9]">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">SL No.</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">{{ ($user->role === 'client' || $user->role === 'patient') ? 'Practitioner' : 'Client' }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Services</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Date & Time</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Mode</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Amount</th>
                        @if(in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']))
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Your Share</th>
                        @endif
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider whitespace-nowrap">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2E4B3D]/12">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-[#FDFDFD] transition-colors">
                        @php
                            $pendingRefRequest = $booking->referralRequests->where('status', 'pending')
                                ->where('recipient_id', $user->id)
                                ->first();
                        @endphp
                        <td class="px-6 py-4 text-sm font-medium text-secondary">
                            <div class="flex items-center gap-3">
                                <span>{{ $loop->iteration + ($bookings->currentPage() - 1) * $bookings->perPage() }}</span>
                                @if($pendingRefRequest)
                                    <div class="relative flex" title="Referral Requested: {{ \Illuminate\Support\Str::headline($pendingRefRequest->expert_type) }}. Note: {{ $pendingRefRequest->note }}">
                                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-amber-400 opacity-75"></span>
                                        <i class="ri-alert-fill text-amber-500 text-lg relative"></i>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-secondary">
                            <div class="flex items-center gap-2">
                                <span class="flex items-center gap-2">
                                    {{ $booking->invoice_no }}
                                    @if(in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']) && $booking->profile_id !== $user->profile_id)
                                        <span class="px-1.5 py-0.5 bg-orange-50 text-orange-600 text-[9px] font-black uppercase tracking-tighter rounded border border-orange-100">Referred</span>
                                    @endif
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($user->role === 'client' || $user->role === 'patient')
                                    <img class="h-10 w-10 rounded-full object-cover border border-[#2E4B3D]/12" 
                                         src="{{ $booking->practitioner->profile_photo_path ? asset('storage/' . $booking->practitioner->profile_photo_path) : asset('frontend/assets/profile-dummy-img.png') }}" 
                                         alt="">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-secondary">
                                            {{ $booking->practitioner->user->name ?? 'Practitioner' }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            @if(is_array($booking->practitioner->specialization))
                                                {{ implode(', ', array_map(fn($s) => str_replace('_', ' ', ucfirst($s)), $booking->practitioner->specialization)) }}
                                            @else
                                                {{ $booking->practitioner->specialization ?? 'Specialist' }}
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <img class="h-10 w-10 rounded-full object-cover border border-[#2E4B3D]/12" 
                                         src="{{ $booking->user->profile_pic ? (str_starts_with($booking->user->profile_pic, 'http') ? $booking->user->profile_pic : asset('storage/' . $booking->user->profile_pic)) : asset('frontend/assets/profile-dummy-img.png') }}" 
                                         alt="">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-secondary">
                                            {{ $booking->user->name ?? 'Patient' }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            ID: {{ $booking->user->patient->client_id ?? 'N/A' }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1 max-w-[200px]">
                                @php
                                    $s_ids = is_array($booking->service_ids) ? $booking->service_ids : [];
                                @endphp
                                @forelse($s_ids as $sid)
                                    @if(isset($allServices[$sid]))
                                        <span class="px-2 py-0.5 bg-secondary/5 text-secondary text-[10px] font-bold rounded-md border border-secondary/10 whitespace-nowrap">
                                            {{ $allServices[$sid]->title }}
                                        </span>
                                    @endif
                                @empty
                                    <span class="text-xs text-gray-400">No services</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $sessions = $booking->additional_info['sessions'] ?? [];
                                $uniqueTimes = collect($sessions)->pluck('time')->unique()->filter(fn($t) => !empty($t) && $t !== 'Time');
                                $uniqueDates = collect($sessions)->pluck('day')->unique()->filter(fn($d) => !empty($d) && $d !== 'Day');
                                $hasMultiple = $uniqueTimes->count() > 1 || $uniqueDates->count() > 1;
                            @endphp
                            @if($booking->original_booking_date)
                                <div class="text-[10px] text-amber-600 font-black uppercase tracking-widest line-through opacity-40 mb-0.5">{{ $booking->original_booking_date->format('M d, Y') }}</div>
                            @endif
                            <div class="text-sm text-secondary font-bold">{{ $booking->booking_date->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-400 flex items-center gap-1">
                                @if($booking->original_booking_time)
                                    <span class="line-through opacity-40 mr-1 font-medium">{{ $booking->original_booking_time }}</span>
                                @endif
                                {{ $booking->booking_time }}
                                @if($hasMultiple)
                                    <span class="bg-amber-50 text-amber-600 px-1.5 py-0.5 rounded text-[9px] font-bold border border-amber-100">+ More</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-bold rounded-full uppercase {{ $booking->mode === 'online' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                                {{ $booking->mode }}
                            </span>
                            @if($booking->need_translator)
                                <div class="mt-1">
                                    @if($booking->translator_id)
                                        <span class="px-2 py-0.5 inline-flex text-[9px] leading-4 font-bold rounded-md bg-emerald-50 text-emerald-600 uppercase border border-emerald-100" title="Translator: {{ $booking->translator->full_name }}">
                                            <i class="ri-translate mr-1"></i> {{ $booking->translator->full_name }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 inline-flex text-[9px] leading-4 font-bold rounded-md bg-amber-50 text-amber-600 uppercase border border-amber-100">
                                            <i class="ri-translate mr-1"></i> Needed
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-secondary font-medium">
                            {{ get_currency_symbol($booking->currency) }} {{ number_format($booking->total_price, 2) }}
                        </td>
                        @if(in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']))
                        <td class="px-6 py-4 text-sm font-bold text-emerald-600">
                            @php
                                // Find if user is practitioner or referrer in any transaction linked to this booking
                                $userTransaction = $booking->transactions->first(function($t) use ($user) {
                                    return $t->practitioner_id === $user->id || $t->referrer_id === $user->id;
                                });

                                $shareAmount = 0;
                                if ($userTransaction) {
                                    if ($userTransaction->practitioner_id === $user->id) {
                                        $shareAmount = $userTransaction->practitioner_share;
                                    } elseif ($userTransaction->referrer_id === $user->id) {
                                        $shareAmount = $userTransaction->referrer_share;
                                    }
                                }
                            @endphp
                            @if($userTransaction && $shareAmount > 0)
                                {{ get_currency_symbol($userTransaction->currency) }} {{ number_format($shareAmount, 2) }}
                                @if($userTransaction->referrer_id === $user->id)
                                    <div class="text-[9px] font-normal text-gray-400 mt-0.5">Referral Fee</div>
                                @endif
                            @else
                                <span class="text-gray-300 font-normal">--</span>
                            @endif
                        </td>
                        @endif
                        <td class="px-6 py-4">
                            @php
                                $status = $booking->effective_status;
                                $statusClasses = [
                                    'pending' => 'bg-yellow-50 text-yellow-600',
                                    'confirmed' => 'bg-green-50 text-green-600',
                                    'cancelled' => 'bg-red-50 text-red-600',
                                    'paid' => 'bg-green-50 text-green-600',
                                    'completed' => 'bg-blue-50 text-blue-600',
                                    'missed' => 'bg-red-50 text-red-600 border border-red-100',
                                ];
                                $class = $statusClasses[$status] ?? 'bg-gray-50 text-gray-600';
                            @endphp
                            <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-bold rounded-full uppercase {{ $class }}">
                                {{ $status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="relative inline-block text-left action-dropdown">
                                <button type="button" class="text-gray-400 hover:text-secondary focus:outline-none dropdown-trigger p-2">
                                    <i class="ri-more-2-fill text-xl"></i>
                                </button>

                                <div class="dropdown-menu absolute right-0 mt-2 w-56 rounded-xl shadow-xl bg-white border border-[#2E4B3D]/12 divide-y divide-gray-50 focus:outline-none z-[999] hidden">
                                    <div class="py-1">
                                        @if($booking->practitioner_type !== 'registration_fee' && in_array($booking->status, ['pending', 'confirmed', 'paid']) && $booking->mode === 'online')
                                        <a href="{{ route('conference.join', ['channel' => $booking->invoice_no ?? 'session-' . $booking->id, 'provider' => 'jaas']) }}" class="group flex items-center w-full px-4 py-3 text-sm text-blue-700 hover:bg-blue-50 transition-colors text-left font-bold">
                                            <i class="ri-vidicon-line mr-3 text-lg text-blue-600"></i>
                                            Join Session
                                        </a>
                                        @endif

                                        @if(in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']) && $booking->profile_id === $user->profile_id)
                                        <a href="{{ route('bookings.consultation-form.show', $booking->id) }}" class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                            <i class="ri-file-list-3-line mr-3 text-lg text-emerald-600"></i>
                                            Consultation Form
                                        </a>
                                        @endif
                                        
                                        <a href="{{ route('bookings.details-view', $booking->id) }}" class="group flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-secondary transition-colors text-left">
                                            <i class="ri-eye-line mr-3 text-lg text-secondary"></i>
                                            View Details
                                        </a>

                                        @if(in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']) && $booking->profile_id === $user->profile_id)
                                        <button onclick="openRescheduleModal({{ $booking->id }}, '{{ $booking->booking_date->toDateString() }}', '{{ $booking->booking_time }}', {{ $booking->profile_id }})" class="group flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-600 transition-colors text-left">
                                            <i class="ri-calendar-event-line mr-3 text-lg text-amber-500"></i>
                                            Reschedule
                                        </button>

                                        @if($user->role === 'practitioner')
                                        <button onclick="openReferModal({{ $booking->id }}, {{ $booking->user_id }}, '{{ $pendingRefRequest ? $pendingRefRequest->expert_type : '' }}')" class="group flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors text-left">
                                            <i class="ri-user-shared-line mr-3 text-lg text-orange-500"></i>
                                            Refer{{ $pendingRefRequest ? ' Expert' : '' }}
                                        </button>
                                        @else
                                        <button onclick="openRequestReferralModal({{ $booking->id }})" class="group flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors text-left">
                                            <i class="ri-user-received-2-line mr-3 text-lg text-orange-500"></i>
                                            Request Referral
                                        </button>
                                        @endif

                                        @if(!$booking->translator_id)
                                        <button onclick="openTranslatorModal({{ $booking->id }}, '{{ $booking->from_language ?: 'English' }}', '{{ $booking->to_language ?: 'Any' }}')" class="group flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors text-left">
                                            <i class="ri-translate mr-3 text-lg text-blue-500"></i>
                                            {{ $booking->need_translator ? 'Assign Translator' : 'Request Translator' }}
                                        </button>
                                        @endif
                                        @endif

                                        @if($booking->razorpay_payment_url && $booking->status === 'pending')
                                        <a href="{{ $booking->razorpay_payment_url }}" target="_blank" class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                            <i class="ri-bank-card-line mr-3 text-lg text-blue-600"></i>
                                            Pay Now
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 md:px-6 py-12 md:py-20 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="ri-calendar-line text-3xl text-gray-300"></i>
                                </div>
                                <p class="text-lg font-medium text-secondary mb-1">No bookings found</p>
                                <p class="text-sm text-gray-400 {{ ($user->role === 'client' || $user->role === 'patient') ? 'mb-6' : '' }}">You haven't booked any sessions yet.</p>
                                @if($user->role === 'client' || $user->role === 'patient')
                                <a href="{{ route('find-practitioner') }}" class="bg-secondary text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-primary transition-colors">Book Your First Session</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($bookings->hasPages())
        <div class="px-6 py-4 border-t border-[#2E4B3D]/12 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Showing <span class="font-medium text-secondary">{{ $bookings->firstItem() }}</span> to <span class="font-medium text-secondary">{{ $bookings->lastItem() }}</span> of <span class="font-medium text-secondary">{{ $bookings->total() }}</span> bookings
            </div>
            <div class="flex space-x-2 pagination-links">
                @if($bookings->onFirstPage())
                    <span class="px-4 py-2 bg-gray-50 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed border border-gray-100">Previous</span>
                @else
                    <a href="{{ $bookings->previousPageUrl() }}" class="px-4 py-2 bg-white text-secondary hover:bg-gray-50 rounded-lg text-sm font-medium border border-gray-200 transition-colors">Previous</a>
                @endif

                <div class="flex items-center space-x-1">
                    @foreach ($bookings->getUrlRange(max(1, $bookings->currentPage() - 2), min($bookings->lastPage(), $bookings->currentPage() + 2)) as $page => $url)
                        @if ($page == $bookings->currentPage())
                            <span class="w-10 h-10 flex items-center justify-center bg-secondary text-white rounded-lg text-sm font-medium">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center bg-white text-secondary hover:bg-gray-50 rounded-lg text-sm font-medium border border-gray-200 transition-colors">{{ $page }}</a>
                        @endif
                    @endforeach
                </div>

                @if($bookings->hasMorePages())
                    <a href="{{ $bookings->nextPageUrl() }}" class="px-4 py-2 bg-white text-secondary hover:bg-gray-50 rounded-lg text-sm font-medium border border-gray-200 transition-colors">Next</a>
                @else
                    <span class="px-4 py-2 bg-gray-50 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed border border-gray-100">Next</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
