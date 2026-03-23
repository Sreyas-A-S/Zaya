<div class="space-y-6">
    <!-- Status & Basic Info -->
    <div class="flex justify-between items-start border-b border-gray-100 pb-4">
        <div>
            <h3 class="text-lg font-bold text-secondary">{{ $booking->invoice_no }}</h3>
            <p class="text-sm text-gray-400">{{ $booking->booking_date->format('M d, Y') }} at {{ $booking->booking_time }}</p>
        </div>
        @php
            $statusClasses = [
                'pending' => 'bg-yellow-50 text-yellow-600',
                'confirmed' => 'bg-green-50 text-green-600',
                'cancelled' => 'bg-red-50 text-red-600',
                'paid' => 'bg-green-50 text-green-600',
            ];
            $class = $statusClasses[$booking->status] ?? 'bg-gray-50 text-gray-600';
        @endphp
        <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-bold rounded-full uppercase {{ $class }}">
            {{ $booking->status }}
        </span>
    </div>

    <!-- Participants -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
            <div class="flex justify-between items-center bg-white border border-gray-100 p-3 rounded-lg">
                <span class="text-sm font-medium text-gray-700">{{ $service->title }}</span>
                <span class="text-xs text-gray-400 uppercase font-bold">{{ $booking->mode }}</span>
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
    <div class="bg-[#2E4B3D] p-4 rounded-xl flex justify-between items-center text-white mt-6">
        <div>
            <p class="text-[10px] uppercase tracking-widest opacity-70 font-bold">Total Amount</p>
            @if($booking->razorpay_payment_id)
            <p class="text-[10px] opacity-50">Ref: {{ $booking->razorpay_payment_id }}</p>
            @endif
        </div>
        <p class="text-xl font-bold">€ {{ number_format($booking->total_price, 2) }}</p>
    </div>

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

        <button onclick="showReferralForm()" id="refer-btn" class="w-full py-3 border-2 border-secondary text-secondary font-bold rounded-xl hover:bg-secondary hover:text-white transition-all flex items-center justify-center gap-2">
            <i class="ri-user-shared-line"></i>
            Refer to another Practitioner
        </button>

        <div id="referral-form" class="hidden mt-4 bg-gray-50 p-5 rounded-2xl border border-gray-100">
            <h4 class="text-sm font-bold text-secondary mb-4">Refer this Session</h4>
            <div class="space-y-4">
                <div>
                    <label class="text-[10px] text-gray-400 font-bold uppercase block mb-1">Select Professional</label>
                    <select id="refer-to-id" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-secondary outline-none">
                        <option value="">-- Choose Practitioner/Doctor --</option>
                        {{-- This will be populated or handled via JS --}}
                    </select>
                </div>
                <div>
                    <label class="text-[10px] text-gray-400 font-bold uppercase block mb-1">Session Fee (€)</label>
                    <input type="number" id="refer-amount" value="{{ $booking->total_price }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-secondary outline-none">
                </div>
                <button onclick="submitReferral({{ $booking->id }})" id="submit-referral-btn" class="w-full py-3 bg-secondary text-white font-bold rounded-xl shadow-lg shadow-secondary/10">
                    Send Referral Invitation
                </button>
            </div>
        </div>
    </div>

    <script>
        async function requestDataAccess(clientId) {
            const btn = document.getElementById('request-otp-btn');
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
            const otp = document.getElementById('access-otp').value;
            const btn = document.getElementById('verify-otp-btn');

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
                    viewBookingDetails({{ $booking->id }});
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

        function showReferralForm() {
            const form = document.getElementById('referral-form');
            form.classList.toggle('hidden');
            
            // Load practitioners if not already loaded
            const select = document.getElementById('refer-to-id');
            if (select.options.length <= 1) {
                fetchPractitionersForReferral();
            }
        }

        async function fetchPractitionersForReferral() {
            try {
                const response = await fetch('/api/referrable-practitioners');
                const data = await response.json();
                const select = document.getElementById('refer-to-id');
                
                data.forEach(p => {
                    if (p.id !== {{ $user->id }}) {
                        const opt = document.createElement('option');
                        opt.value = p.id;
                        opt.textContent = `${p.name} (${p.role})`;
                        select.appendChild(opt);
                    }
                });
            } catch (err) {
                console.error('Error fetching practitioners:', err);
            }
        }

        async function submitReferral(bookingId) {
            const referredToId = document.getElementById('refer-to-id').value;
            const amount = document.getElementById('refer-amount').value;
            const btn = document.getElementById('submit-referral-btn');

            if (!referredToId) return alert('Please select a professional.');

            btn.disabled = true;
            btn.innerText = 'Sending...';

            try {
                const response = await fetch(`/bookings/${bookingId}/refer`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ referred_to_id: referredToId, amount: amount })
                });

                const result = await response.json();
                if (result.success) {
                    alert(result.success);
                    document.getElementById('referral-form').classList.add('hidden');
                } else {
                    alert(result.error || 'Something went wrong.');
                }
            } catch (err) {
                console.error(err);
                alert('Failed to send referral.');
            } finally {
                btn.disabled = false;
                btn.innerText = 'Send Referral Invitation';
            }
        }
    </script>
    @endif
</div>
