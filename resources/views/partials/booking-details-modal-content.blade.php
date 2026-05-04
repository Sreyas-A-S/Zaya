<div class="space-y-6">
    <!-- Status & Basic Info -->
    <div class="flex justify-between items-start border-b border-gray-100 pb-4">
        <div>
            <h3 class="text-lg font-bold text-secondary">{{ $booking->invoice_no }}</h3>
            <p class="text-sm text-gray-400">{{ $booking->booking_date->format('M d, Y') }} at {{ $booking->booking_time }}</p>
        </div>
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
    </div>

    <!-- Participants -->
    <div class="grid grid-cols-1 {{ in_array($user->role, ['client', 'patient']) ? '' : 'md:grid-cols-2' }} gap-6">
        @unless(in_array($user->role, ['client', 'patient']))
        <div class="bg-gray-50 p-4 rounded-xl">
            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-3">Client Information</p>
            <div class="flex items-center gap-3">
                <img src="{{ $booking->user->profile_pic ? (str_starts_with($booking->user->profile_pic, 'http') ? $booking->user->profile_pic : asset('storage/' . $booking->user->profile_pic)) : asset('frontend/assets/profile-dummy-img.png') }}" 
                     class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="text-sm font-bold text-gray-800">{{ $booking->user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $booking->user->email }}</p>
                </div>
            </div>
        </div>
        @endunless
        <div class="bg-gray-50 p-4 rounded-xl">
            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-3">Practitioner Information</p>
            <div class="flex items-center gap-3">
                <img src="{{ $booking->practitioner->profile_photo_path ? asset('storage/' . $booking->practitioner->profile_photo_path) : asset('frontend/assets/profile-dummy-img.png') }}" 
                     class="w-10 h-10 rounded-full object-cover">
                <div>
                    <p class="text-sm font-bold text-gray-800">{{ $booking->practitioner->user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $booking->practitioner->specialization ?? 'Specialist' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Services -->
    <div>
        <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-3">Services Booked</p>
        <div class="space-y-2">
            @foreach($services as $service)
            @php
                $sessions = $booking->additional_info['sessions'] ?? [];
                $sessionInfo = collect($sessions)->firstWhere('service_id', (string)$service->id) 
                               ?? collect($sessions)->firstWhere('service_id', (int)$service->id);
                
                $displayDate = $booking->booking_date ? $booking->booking_date->format('M d, Y') : 'N/A';
                $displayTime = $booking->booking_time ?? 'N/A';

                if ($sessionInfo) {
                    if (!empty($sessionInfo['day']) && $sessionInfo['day'] !== 'Day') {
                        $displayDate = $sessionInfo['day'];
                    }
                    if (!empty($sessionInfo['time']) && $sessionInfo['time'] !== 'Time') {
                        $displayTime = $sessionInfo['time'];
                    }
                }

                $isAssignedToMe = !empty($referredServiceIds) && in_array($service->id, $referredServiceIds);
            @endphp
            <div class="{{ $isAssignedToMe ? 'bg-primary/5 border-primary/30' : 'bg-white border-gray-100' }} border p-3 rounded-lg transition-all">
                <div class="flex justify-between items-center mb-1">
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-bold {{ $isAssignedToMe ? 'text-primary' : 'text-gray-700' }}">{{ $service->title }}</span>
                        @if($isAssignedToMe)
                            <span class="text-[8px] font-black uppercase tracking-widest bg-primary text-white px-1.5 py-0.5 rounded">Assigned</span>
                        @endif
                        @php
                            $isSessPassed = $booking->isSessionPassed($sessionInfo, derive_timezone_from_user($booking->practitioner->user ?? null));
                        @endphp
                        @if($isSessPassed && $booking->status !== 'completed' && $booking->status !== 'cancelled')
                            <span class="text-[8px] font-black uppercase tracking-widest bg-red-100 text-red-600 px-1.5 py-0.5 rounded border border-red-200">Missed</span>
                        @endif
                    </div>
                    <span class="text-[10px] text-gray-400 uppercase font-black tracking-widest">{{ $booking->mode }}</span>
                </div>
                <div class="flex items-center gap-2 text-xs {{ $isAssignedToMe ? 'text-primary/70' : 'text-secondary' }} font-medium">
                    <i class="ri-calendar-line opacity-50"></i>
                    <span>{{ $displayDate }}</span>
                    <span class="opacity-20">|</span>
                    <i class="ri-time-line opacity-50"></i>
                    <span>{{ $displayTime }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Additional Details -->
    <div class="space-y-4">
        @if($booking->need_translator)
        <div class="border-t border-gray-100 pt-4">
            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Translator Details</p>
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-gray-700">{{ $booking->translator->user->name ?? 'Assigned' }}</span>
                    <span class="text-[10px] bg-purple-50 text-purple-600 px-2 py-0.5 rounded">{{ $booking->from_language }} &rarr; {{ $booking->to_language }}</span>
                </div>
            </div>
        </div>
        @endif

        @if($booking->conditions)
        <div class="border-t border-gray-100 pt-4">
            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-1">Health Conditions</p>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $booking->conditions }}</p>
        </div>
        @endif

        @if($booking->situation)
        <div class="border-t border-gray-100 pt-4">
            <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-1">Current Situation</p>
            <p class="text-sm text-gray-600 leading-relaxed">{{ $booking->situation }}</p>
        </div>
        @endif
    </div>

    <!-- Payment -->
    @php
        $transaction = $booking->transactions->first();
        $isPractitioner = in_array($user->role, ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist']) && $booking->practitioner->user_id === $user->id;
        $isTranslator = ($booking->translator && $booking->translator->user_id === $user->id);
    @endphp

    <div class="bg-[#2E4B3D] p-5 rounded-[2rem] text-white mt-6 shadow-xl shadow-secondary/10">
        <div class="flex justify-between items-center">
            <div>
                @if($isPractitioner)
                    <p class="text-[10px] uppercase tracking-[0.2em] opacity-70 font-black mb-1">Your Earned Share</p>
                    <p class="text-2xl font-black">{{ get_currency_symbol($booking->currency) }} {{ number_format($transaction ? $transaction->practitioner_share : 0, 2) }}</p>
                @elseif($isTranslator)
                    <p class="text-[10px] uppercase tracking-[0.2em] opacity-70 font-black mb-1">Total Amount</p>
                    <p class="text-2xl font-black">{{ get_currency_symbol($booking->currency) }} {{ number_format($booking->total_price, 2) }}</p>
                @else
                    <p class="text-[10px] uppercase tracking-[0.2em] opacity-70 font-black mb-1">Total Amount Paid</p>
                    <p class="text-2xl font-black">{{ get_currency_symbol($booking->currency) }} {{ number_format($booking->total_price, 2) }}</p>
                @endif
                
                @if($booking->razorpay_payment_id)
                <p class="text-[9px] opacity-40 font-bold mt-2 uppercase tracking-tighter">Ref: {{ $booking->razorpay_payment_id }}</p>
                @endif
            </div>
            
            @if($isPractitioner && $transaction)
            <button onclick="toggleDistribution()" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all border border-white/10">
                <i class="ri-information-line text-xl"></i>
            </button>
            @endif
        </div>

        @if($isPractitioner && $transaction)
        <div id="distribution-info" class="hidden mt-6 pt-6 border-t border-white/10 space-y-4 transition-all">
            <div class="flex justify-between items-center text-[11px]">
                <span class="opacity-60 font-bold uppercase tracking-widest">Gross Booking Value</span>
                <span class="font-black text-white">{{ get_currency_symbol($booking->currency) }} {{ number_format($transaction->subtotal, 2) }}</span>
            </div>
            
            @php
                $totalDiscounts = (float)$booking->discount_amount + (float)$booking->coin_discount;
            @endphp
            @if($totalDiscounts > 0)
            <div class="flex justify-between items-center text-[11px] text-red-300">
                <span class="opacity-60 font-bold uppercase tracking-widest">Total Discounts Applied</span>
                <span class="font-black">- {{ get_currency_symbol($booking->currency) }} {{ number_format($totalDiscounts, 2) }}</span>
            </div>
            @endif

            <div class="flex justify-between items-center text-[11px] pt-1 border-t border-white/5">
                <span class="opacity-60 font-bold uppercase tracking-widest text-emerald-400">Net Paid by Client</span>
                <span class="font-black text-emerald-400">{{ get_currency_symbol($booking->currency) }} {{ number_format($transaction->total_amount, 2) }}</span>
            </div>

            <div class="flex justify-between items-center text-[11px]">
                <span class="opacity-60 font-bold uppercase tracking-widest">Platform Fee ({{ number_format($transaction->company_commission_percent, 1) }}%)</span>
                <span class="font-black text-red-300">- {{ get_currency_symbol($booking->currency) }} {{ number_format($transaction->company_share, 2) }}</span>
            </div>
            @if($transaction->referrer_share > 0)
            <div class="flex justify-between items-center text-[11px]">
                <span class="opacity-60 font-bold uppercase tracking-widest">Referral Fee ({{ number_format($transaction->referrer_commission_percent, 1) }}%)</span>
                <span class="font-black text-orange-300">- {{ get_currency_symbol($booking->currency) }} {{ number_format($transaction->referrer_share, 2) }}</span>
            </div>
            @endif
            <div class="flex justify-between items-center pt-2 border-t border-white/5 text-sm font-black">
                <span class="uppercase tracking-widest text-[10px] text-emerald-400">Your Net Earnings</span>
                <span class="text-white">{{ get_currency_symbol($booking->currency) }} {{ number_format($transaction->practitioner_share, 2) }}</span>
            </div>
        </div>
        @endif
    </div>

    <script>
        function toggleDistribution() {
            const info = document.getElementById('distribution-info');
            if (info) {
                info.classList.toggle('hidden');
            }
        }
    </script>

    <!-- Referral Option (Practitioner Only) -->
    @if(in_array($user->role, ['practitioner', 'doctor', 'mindfulness_practitioner', 'yoga_therapist']) && $booking->practitioner->user_id === $user->id)
    <div class="mt-8 border-t border-gray-100 pt-6 space-y-4">
        @php
            $hasDataAccess = \App\Http\Controllers\DataAccessController::hasAccess($user->id, $booking->user_id);
        @endphp

        @if(!$hasDataAccess)
        <div id="data-access-section" class="bg-blue-50 p-5 rounded-2xl border border-blue-100">
            <div class="flex items-center gap-3 mb-3">
                <i class="ri-shield-user-line text-blue-600 text-xl"></i>
                <h4 class="text-sm font-bold text-blue-900">Client Data Access</h4>
            </div>
            <p class="text-xs text-blue-700 mb-4 leading-relaxed">To view this client's full profile, health history, and previous recordings, you need their permission via OTP verification.</p>
            
            <div id="otp-request-box">
                <button onclick="requestDataAccess({{ $booking->user_id }})" id="request-otp-btn" class="w-full py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-200">
                    Request Access via OTP
                </button>
            </div>

            <div id="otp-verify-box" class="hidden space-y-3">
                <label class="text-[10px] text-blue-900 font-bold uppercase">Enter OTP sent to client</label>
                <div class="flex gap-2">
                    <input type="text" id="access-otp" placeholder="6-digit code" class="flex-1 border border-blue-200 rounded-xl px-4 py-2.5 text-sm focus:border-blue-600 outline-none">
                    <button onclick="verifyDataAccess({{ $booking->user_id }})" id="verify-otp-btn" class="px-6 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl shadow-md">
                        Verify
                    </button>
                </div>
                <button onclick="requestDataAccess({{ $booking->user_id }})" class="text-[10px] text-blue-600 font-bold uppercase hover:underline">Resend OTP</button>
            </div>
        </div>
        @else
        <div class="bg-green-50 p-4 rounded-2xl border border-green-100 flex items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <i class="ri-checkbox-circle-line text-green-600 text-xl"></i>
                <div>
                    <p class="text-sm font-bold text-green-900">Access Granted</p>
                    <p class="text-xs text-green-700">You have permission to view this client's full data.</p>
                </div>
            </div>
            <a href="{{ route('client.profile.view', $booking->user_id) }}" class="px-4 py-2 bg-secondary text-white text-xs font-bold rounded-lg shadow-md hover:bg-opacity-90 transition-all">
                View Full Profile
            </a>
        </div>
        @endif

        <button onclick="openReferModal({{ $booking->id }}, {{ $booking->user_id }})" class="w-full py-3 border-2 border-secondary text-secondary font-bold rounded-xl hover:bg-secondary hover:text-white transition-all flex items-center justify-center gap-2">
            <i class="ri-user-shared-line"></i>
            Refer to Peer
        </button>
    </div>

    <script>
        async function requestDataAccess(clientId) {
            const btn = document.getElementById('request-otp-btn');
            if (!btn) return;
            btn.disabled = true;
            btn.innerText = 'Sending OTP...';

            try {
                const response = await fetch('{{ route("data-access.request") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ client_id: clientId })
                });

                const result = await response.json();
                if (result.success) {
                    alert(result.success);
                    document.getElementById('otp-request-box').classList.add('hidden');
                    document.getElementById('otp-verify-box').classList.remove('hidden');
                } else {
                    alert(result.error || 'Failed to send OTP.');
                }
            } catch (err) {
                console.error(err);
                alert('Error requesting access.');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Request Access via OTP';
            }
        }

        async function verifyDataAccess(clientId) {
            const otpEl = document.getElementById('access-otp');
            const btn = document.getElementById('verify-otp-btn');
            if (!otpEl || !btn) return;

            const otp = otpEl.value;
            if (!otp) return alert('Please enter the OTP.');

            btn.disabled = true;
            btn.innerText = '...';

            try {
                const response = await fetch('{{ route("data-access.verify") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ client_id: clientId, otp: otp })
                });

                const result = await response.json();
                if (result.success) {
                    alert(result.success);
                    // Refresh modal content to show "Access Granted"
                    if (typeof viewBookingDetails === 'function') {
                        viewBookingDetails({{ $booking->id }});
                    } else {
                        location.reload();
                    }
                } else {
                    alert(result.error || 'Verification failed.');
                }
            } catch (err) {
                console.error(err);
                alert('Error verifying OTP.');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Verify';
            }
        }
    </script>
    @endif
</div>
