<div id="bookings-container">
    <div class="bg-white rounded-2xl border border-[#2E4B3D]/12 overflow-hidden mb-8">
        <div class="p-6 border-b border-[#2E4B3D]/12">
            <h2 class="text-xl font-medium text-secondary">My Bookings</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9F9F9]">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">SL No.</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">{{ ($user->role === 'client' || $user->role === 'patient') ? 'Practitioner' : 'Client' }}</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Mode</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2E4B3D]/12">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-[#FDFDFD] transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-secondary">
                            {{ $loop->iteration + ($bookings->currentPage() - 1) * $bookings->perPage() }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-secondary">
                            {{ $booking->invoice_no }}
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
                                            {{ $booking->practitioner->specialization ?? 'Specialist' }}
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
                            <div class="text-sm text-secondary">{{ $booking->booking_date->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $booking->booking_time }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-bold rounded-full uppercase {{ $booking->mode === 'online' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600' }}">
                                {{ $booking->mode }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-secondary font-medium">
                            € {{ number_format($booking->total_price, 2) }}
                        </td>
                        <td class="px-6 py-4">
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
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-3">
                                <button onclick="viewBookingDetails({{ $booking->id }})" class="text-secondary hover:text-primary transition-colors flex items-center gap-1">
                                    <i class="ri-eye-line text-lg"></i>
                                    <span>View</span>
                                </button>
                                @if($booking->razorpay_payment_url && $booking->status === 'pending')
                                    <a href="{{ $booking->razorpay_payment_url }}" target="_blank" class="text-secondary hover:underline">Pay Now</a>
                                @endif
                                <button class="text-gray-400 hover:text-secondary"><i class="ri-more-2-fill text-lg"></i></button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="ri-calendar-line text-3xl text-gray-300"></i>
                                </div>
                                <p class="text-lg font-medium text-secondary mb-1">No bookings found</p>
                                <p class="text-sm text-gray-400 mb-6">You haven't booked any sessions yet.</p>
                                <a href="{{ route('find-practitioner') }}" class="bg-secondary text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-primary transition-colors">Book Your First Session</a>
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