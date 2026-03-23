@extends('layouts.client')

@section('title', 'Client Profile - ' . $client->name)

@section('content')
<div class="container-fluid py-4">
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('bookings.index') }}" class="w-10 h-10 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-600 hover:bg-gray-50 transition-all">
            <i class="ri-arrow-left-line"></i>
        </a>
        <h1 class="text-2xl font-bold text-secondary">Client Profile</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar: Personal Info -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white rounded-[32px] p-8 border border-[#2E4B3D]/12 shadow-sm">
                <div class="text-center mb-6">
                    <img src="{{ $client->profile_pic ? (str_starts_with($client->profile_pic, 'http') ? $client->profile_pic : asset('storage/' . $client->profile_pic)) : asset('frontend/assets/profile-dummy-img.png') }}" 
                         class="w-32 h-32 rounded-full object-cover mx-auto p-1 bg-white border-4 border-gray-50 mb-4">
                    <h2 class="text-xl font-bold text-secondary">{{ $client->name }}</h2>
                    <p class="text-gray-400 text-sm">Client ID: {{ $client->patient->client_id ?? 'Z-' . (10000 + $client->id) }}</p>
                </div>

                <div class="space-y-4">
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400 text-xs font-bold uppercase">Email</span>
                        <span class="text-secondary text-sm font-semibold">{{ $client->email }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400 text-xs font-bold uppercase">Gender</span>
                        <span class="text-secondary text-sm font-semibold">{{ ucfirst($client->patient->gender ?? 'N/A') }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400 text-xs font-bold uppercase">Age</span>
                        <span class="text-secondary text-sm font-semibold">{{ $client->patient->age ?? 'N/A' }} yrs</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-50">
                        <span class="text-gray-400 text-xs font-bold uppercase">Occupation</span>
                        <span class="text-secondary text-sm font-semibold">{{ $client->patient->occupation ?? 'N/A' }}</span>
                    </div>
                    <div class="py-2">
                        <span class="text-gray-400 text-xs font-bold uppercase block mb-1">Address</span>
                        <p class="text-secondary text-sm font-medium leading-relaxed">{{ $client->patient->address ?? 'No address provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Health Information -->
            <div class="bg-[#F8FAF9] rounded-[32px] p-8 border border-[#2E4B3D]/12">
                <h3 class="text-lg font-bold text-secondary mb-6 flex items-center gap-2">
                    <i class="ri-heart-pulse-line text-primary"></i>
                    Health Information
                </h3>
                <div class="space-y-6">
                    <div>
                        <span class="text-gray-400 text-xs font-bold uppercase block mb-2">Conditions History</span>
                        <div class="bg-white p-4 rounded-2xl border border-gray-100">
                            <p class="text-sm text-gray-600 italic">No specific conditions recorded by client.</p>
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs font-bold uppercase block mb-2">Preferences</span>
                        <div class="flex flex-wrap gap-2">
                            @if(isset($client->patient->consultation_preferences))
                                @foreach($client->patient->consultation_preferences as $pref)
                                    <span class="px-3 py-1 bg-white text-secondary text-[10px] font-bold rounded-full border border-gray-100 uppercase">{{ $pref }}</span>
                                @endforeach
                            @else
                                <span class="text-xs text-gray-400">None specified</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main: Recordings & History -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Session Recordings -->
            <div class="bg-white rounded-[32px] p-8 border border-[#2E4B3D]/12 shadow-sm">
                <h3 class="text-xl font-bold text-secondary mb-6 flex items-center gap-2">
                    <i class="ri-video-chat-line text-primary"></i>
                    Session Recordings
                </h3>
                
                @if($recordings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($recordings as $recording)
                    <div class="group relative bg-[#F9F9F9] rounded-2xl overflow-hidden border border-gray-100 hover:border-primary transition-all">
                        <div class="p-5">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary shadow-sm">
                                    <i class="ri-play-fill text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-secondary">{{ $recording->invoice_no }}</p>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold tracking-wider">{{ $recording->booking_date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="flex justify-between items-center mt-4">
                                <span class="text-xs text-gray-500">With: {{ $recording->practitioner->user->name }}</span>
                                <a href="{{ route('recordings.show', $recording->id) }}" class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                                    Watch <i class="ri-arrow-right-s-line"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="py-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center mx-auto mb-4">
                        <i class="ri-video-off-line text-2xl text-gray-300"></i>
                    </div>
                    <p class="text-gray-400 font-medium">No session recordings available for this client.</p>
                </div>
                @endif
            </div>

            <!-- Booking History -->
            <div class="bg-white rounded-[32px] p-8 border border-[#2E4B3D]/12 shadow-sm">
                <h3 class="text-xl font-bold text-secondary mb-6 flex items-center gap-2">
                    <i class="ri-history-line text-primary"></i>
                    Session History
                </h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] font-bold text-gray-400 uppercase tracking-widest border-b border-gray-50">
                                <th class="pb-4">Invoice</th>
                                <th class="pb-4">Practitioner</th>
                                <th class="pb-4">Date</th>
                                <th class="pb-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($bookings as $hist)
                            <tr class="group hover:bg-gray-50 transition-all">
                                <td class="py-4 text-sm font-bold text-secondary">{{ $hist->invoice_no }}</td>
                                <td class="py-4 text-sm text-gray-600">{{ $hist->practitioner->user->name }}</td>
                                <td class="py-4 text-sm text-gray-500">{{ $hist->booking_date->format('M d, Y') }}</td>
                                <td class="py-4">
                                    <span class="px-3 py-1 text-[10px] font-bold rounded-full uppercase {{ $hist->status === 'confirmed' || $hist->status === 'paid' ? 'bg-green-50 text-green-600' : 'bg-gray-50 text-gray-400' }}">
                                        {{ $hist->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
