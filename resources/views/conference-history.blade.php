@extends('layouts.client')

@section('title', 'Conference History')

@section('content')
<!-- Mobile Tab Navigation -->
<div class="lg:hidden flex space-x-6 overflow-x-auto scrollbar-hide mb-5">
    <a href="{{ route('dashboard') }}"
        class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Dashboard</a>
    <a href="{{ route('bookings.index') }}"
        class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Bookings</a>
    <a href="{{ route('conferences.index') }}"
        class="leading-none text-lg text-secondary font-normal whitespace-nowrap cursor-pointer transition-colors border-b-2 border-secondary pb-1">Conferences</a>
</div>

<!-- Conference History Content -->
<div id="conferences-container">
    <div class="bg-white rounded-2xl border border-[#2E4B3D]/12 overflow-hidden mb-8">
        <div class="p-6 border-b border-[#2E4B3D]/12 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="text-xl font-medium text-secondary">Conference History</h2>
            <a href="{{ route('conference.join', ['channel' => 'zaya-' . strtolower(Str::random(10))]) }}" 
               target="_blank" 
               class="px-6 py-3 bg-secondary text-white rounded-full font-medium flex items-center gap-2 hover:bg-opacity-90 shadow-lg shadow-secondary/10 transition-all">
                <i class="ri-vidicon-fill text-lg"></i>
                Start Instant Meeting
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9F9F9]">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Practitioner</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Mode</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2E4B3D]/12">
                    @forelse($conferences as $conference)
                    <tr class="hover:bg-[#FDFDFD] transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-secondary">
                            {{ $loop->iteration + ($conferences->currentPage() - 1) * $conferences->perPage() }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-100 mr-3 flex items-center justify-center overflow-hidden">
                                    <i class="ri-user-line text-gray-400"></i>
                                </div>
                                <span class="text-sm font-medium text-gray-900">{{ $conference->practitioner->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            {{ $conference->booking_date->format('M d, Y') }} at {{ $conference->booking_time }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-bold rounded-full uppercase bg-blue-50 text-blue-600">
                                {{ ucfirst($conference->mode) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            @if($conference->recording_url)
                                <a href="{{ route('recordings.show', ['id' => $conference->id]) }}" target="_blank" class="text-secondary hover:underline flex items-center justify-end">
                                    <i class="ri-play-circle-line mr-1 text-lg"></i> Watch Recording
                                </a>
                            @else
                                <span class="text-gray-300 italic">No recording available</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="ri-vidicon-line text-3xl text-gray-300"></i>
                                </div>
                                <p class="text-lg font-medium text-secondary mb-1">No conferences found</p>
                                <p class="text-sm text-gray-400">You haven't attended any online sessions yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($conferences->hasPages())
        <div class="px-6 py-4 border-t border-[#2E4B3D]/12 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Showing <span class="font-medium text-secondary">{{ $conferences->firstItem() }}</span> to <span class="font-medium text-secondary">{{ $conferences->lastItem() }}</span> of <span class="font-medium text-secondary">{{ $conferences->total() }}</span> conferences
            </div>
            <div class="flex space-x-2 pagination-links">
                {{ $conferences->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<div class="h-10"></div>
@endsection

@section('scripts')
@endsection
