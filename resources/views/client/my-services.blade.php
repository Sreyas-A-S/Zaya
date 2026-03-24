@extends('layouts.client')

@section('title', 'My Services')

@section('content')
<!-- Mobile Tab Navigation -->
<div class="lg:hidden flex space-x-6 overflow-x-auto scrollbar-hide mb-5">
    <a href="{{ route('dashboard') }}"
        class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Dashboard</a>
    <button class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Health
        Journey</button>
    <a href="{{ route('bookings.index') }}"
        class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Bookings</a>
    <button class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">Transaction
        Vault</button>
    <a href="{{ route('my-services.index') }}" class="leading-none text-lg text-secondary font-normal whitespace-nowrap cursor-pointer transition-colors border-b-2 border-secondary pb-1">My Services</a>
    <a href="{{ route('profile') }}"
        class="leading-none text-lg text-[#8F8F8F] font-normal whitespace-nowrap cursor-pointer transition-colors">{{ __('Profile') }}</a>
</div>

<div class="flex flex-col gap-8">
    <!-- Header Section -->
    <div class="flex justify-between items-center bg-white rounded-xl px-6 py-8 border border-[#2E4B3D]/12">
        <div>
            <h2 class="text-2xl font-bold text-secondary mb-1">My Services</h2>
            <p class="text-gray-400">Manage your offered services, rates, and durations.</p>
        </div>
        <button onclick="openAddServiceModal()" class="bg-secondary text-white px-6 py-3 rounded-xl font-bold hover:bg-opacity-90 transition-all flex items-center gap-2 shadow-lg shadow-secondary/20">
            <i class="ri-add-line text-xl"></i>
            <span>Add New Service</span>
        </button>
    </div>

    @if(session('status'))
        <div class="px-6 py-4 bg-green-50 border border-green-200 text-green-600 rounded-xl flex items-center shadow-sm">
            <i class="ri-checkbox-circle-line mr-3 text-xl"></i>
            {{ session('status') }}
        </div>
    @endif

    <!-- Services Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($myServices as $userService)
            <div class="bg-white rounded-2xl border border-[#2E4B3D]/12 overflow-hidden hover:shadow-xl transition-all group">
                <div class="h-48 relative">
                    <img src="{{ $userService->service->image ? asset('storage/' . $userService->service->image) : asset('frontend/assets/service-placeholder.png') }}" 
                         alt="{{ $userService->service->title }}" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute bottom-4 left-6">
                        <h3 class="text-white text-xl font-bold">{{ $userService->service->title }}</h3>
                    </div>
                    <button onclick="confirmDelete({{ $userService->id }})" class="absolute top-4 right-4 w-10 h-10 bg-white/20 backdrop-blur-md text-white rounded-full flex items-center justify-center hover:bg-red-500 transition-colors">
                        <i class="ri-delete-bin-line text-lg"></i>
                    </button>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Rate</p>
                            <p class="text-xl font-bold text-secondary">₹{{ number_format($userService->rate, 2) }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                            <p class="text-xs font-bold text-gray-400 uppercase mb-1">Duration</p>
                            <p class="text-xl font-bold text-secondary">{{ $userService->duration }} Min</p>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-gray-100 flex justify-between items-center">
                        <span class="flex items-center gap-2 text-sm {{ $userService->status === 'active' ? 'text-green-500' : 'text-gray-400' }}">
                            <span class="w-2 h-2 rounded-full {{ $userService->status === 'active' ? 'bg-green-500 animate-pulse' : 'bg-gray-400' }}"></span>
                            {{ ucfirst($userService->status) }}
                        </span>
                        <button class="text-secondary font-bold text-sm hover:underline">Edit Details</button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-white rounded-2xl border border-dashed border-gray-300 flex flex-col items-center justify-center text-center px-6">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
                    <i class="ri-shake-hands-line text-5xl text-gray-300"></i>
                </div>
                <h3 class="text-2xl font-bold text-secondary mb-2">No Services Added Yet</h3>
                <p class="text-gray-400 max-w-md mb-8">Start offering your expertise by adding services from our available catalogue.</p>
                <button onclick="openAddServiceModal()" class="bg-secondary text-white px-8 py-3 rounded-xl font-bold hover:bg-opacity-90 transition-all">
                    Add Your First Service
                </button>
            </div>
        @endforelse
    </div>
</div>

<!-- Add Service Modal -->
<div id="addServiceModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-secondary">Add New Service</h3>
            <button onclick="closeAddServiceModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ri-close-line text-3xl"></i>
            </button>
        </div>
        <form action="{{ route('my-services.store') }}" method="POST" class="px-8 py-8 space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Select Service</label>
                <select name="service_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent transition-all outline-none">
                    <option value="" disabled selected>Choose a service...</option>
                    @foreach($availableServices as $service)
                        <option value="{{ $service->id }}">{{ $service->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Rate (₹)</label>
                    <input type="number" name="rate" step="0.01" required placeholder="0.00" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent transition-all outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Duration (Min)</label>
                    <input type="number" name="duration" required placeholder="60" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent transition-all outline-none">
                </div>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeAddServiceModal()" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-secondary text-white font-bold rounded-xl hover:bg-opacity-90 transition-all shadow-lg shadow-secondary/20">Add Service</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl">
        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="ri-delete-bin-line text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-secondary mb-2">Remove Service?</h3>
            <p class="text-gray-500 mb-8 leading-relaxed">Are you sure you want to remove this service? This will not affect existing bookings but you will no longer be listed for this service.</p>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                    <button type="submit" class="flex-1 px-6 py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-red-200">Remove</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openAddServiceModal() {
        document.getElementById('addServiceModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAddServiceModal() {
        document.getElementById('addServiceModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function confirmDelete(id) {
        const modal = document.getElementById('deleteConfirmModal');
        const form = document.getElementById('deleteForm');
        form.action = `/my-services/${id}`;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteConfirmModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('addServiceModal')) {
            closeAddServiceModal();
        }
        if (event.target == document.getElementById('deleteConfirmModal')) {
            closeDeleteModal();
        }
    }
</script>
@endpush
@endsection
