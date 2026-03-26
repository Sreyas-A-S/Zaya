@extends('layouts.client')

@section('title', 'Profile')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    .cropper-container { max-height: 400px; width: 100%; }
    #cropper-image { max-width: 100%; display: block; }
    .progress-bar-fill { transition: width 0.3s ease; }
    /* Tom Select Custom Styling */
    .ts-control {
        border-radius: 0.75rem !important;
        padding: 0.5rem 0.75rem !important;
        border: 1px solid #e5e7eb !important;
        background-color: #fff !important;
        font-size: 1rem !important;
        line-height: 1.5rem !important;
        transition: all 0.2s !important;
        display: flex !important;
        flex-wrap: wrap !important;
        gap: 4px !important;
    }
    .ts-wrapper.focus .ts-control {
        border-color: #2E4B3D !important;
        box-shadow: 0 0 0 2px rgba(46, 75, 61, 0.1) !important;
    }
    /* Tom Select Pill Styling */
    .ts-control .item {
        background: rgba(46, 75, 61, 0.1) !important;
        color: #2E4B3D !important;
        border-radius: 9999px !important;
        padding: 3px 12px !important;
        border: 1px solid rgba(46, 75, 61, 0.1) !important;
        display: inline-flex !important;
        align-items: center !important;
        font-weight: 600 !important;
        margin: 2px !important;
        font-size: 0.9rem !important;
    }
    .ts-control .item .remove {
        border-left: none !important;
        margin-left: 6px !important;
        padding: 0 4px !important;
        border-radius: 0 9999px 9999px 0 !important;
        color: rgba(46, 75, 61, 0.4) !important;
        font-size: 1.1em !important;
    }
    .ts-control .item .remove:hover {
        background: transparent !important;
        color: #F3324C !important;
    }
</style>
@endpush

@section('content')
@php
    $age = $profile && $profile->dob ? \Carbon\Carbon::parse($profile->dob)->age . ' Years' : 'Not set';
    $gender = $profile && $profile->gender ? ucfirst($profile->gender) : ($user->gender ? ucfirst($user->gender) : 'Not set');
    $dob = $profile && $profile->dob ? \Carbon\Carbon::parse($profile->dob)->format('M d, Y') : 'Not set';
    $nationality = $user->nationality ? $user->nationality->name : ($profile->nationality ?? 'Not set');
    $phone = $profile->phone ?? ($user->phone ?? 'Not set');
    $email = $user->email;
    
    // Address logic
    if (in_array($user->role, ['doctor', 'yoga_therapist'])) {
        $address = $profile->city_state ?? ($profile->city ? $profile->city . ', ' . $profile->state : 'Not set');
    } else {
        $addressParts = array_filter([
            $profile->residential_address ?? null,
            $profile->address_line_1 ?? null,
            $profile->address_line_2 ?? null,
            $profile->city ?? null,
            $profile->state ?? null,
            $profile->zip_code ?? null,
            $profile->country ?? null
        ]);
        $address = !empty($addressParts) ? implode(', ', $addressParts) : 'Not set';
    }

    // Specialities & Conditions
    $specialities = [];
    $conditions = [];

    switch($user->role) {
        case 'practitioner':
            $specialities = array_merge(
                (array)($profile->consultations ?? []),
                (array)($profile->other_modalities ?? [])
            );
            $conditions = (array)($profile->body_therapies ?? []);
            break;
        case 'doctor':
            $specialities = (array)($profile->specialization ?? []);
            $conditions = (array)($profile->health_conditions_treated ?? []);
            break;
        case 'mindfulness_practitioner':
            $specialities = (array)($profile->practitioner_type ?? []);
            $conditions = (array)($profile->client_concerns ?? []);
            break;
        case 'yoga_therapist':
            $specialities = (array)($profile->yoga_therapist_type ?? []);
            $conditions = (array)($profile->areas_of_expertise ?? []);
            break;
        case 'translator':
            $specialities = (array)($profile->fields_of_specialization ?? []);
            $conditions = (array)($profile->services_offered ?? []);
            break;
    }

    $sanctuaryImages = $gallery['sanctuary'] ?? collect();
@endphp

@if(session('status'))
    <div class="mb-6 px-6 py-4 bg-green-50 border border-green-200 text-green-600 rounded-xl flex items-center shadow-sm">
        <i class="ri-checkbox-circle-line mr-3 text-xl"></i>
        {{ session('status') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-6 px-6 py-4 bg-red-50 border border-red-200 text-red-600 rounded-xl flex items-center shadow-sm">
        <i class="ri-error-warning-line mr-3 text-xl"></i>
        {{ session('error') }}
    </div>
@endif

<!-- Mobile Tab Navigation -->

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

    <!-- Left Column: Profile Card & Gallery -->
    <div class="lg:col-span-4 xl:col-span-3 flex flex-col gap-8">

        <!-- Profile Card -->
        <div class="bg-white rounded-xl px-5 pt-12 pb-5 flex flex-col items-center border border-[#2E4B3D]/12">
            <div class="relative mb-6">
                <img id="user-profile-img" src="{{ $user->profile_pic ? (str_starts_with($user->profile_pic, 'http') ? $user->profile_pic : asset('storage/' . $user->profile_pic)) : asset('frontend/assets/profile-dummy-img.png') }}"
                    alt="{{ $user->name }}" class="w-38 h-38 rounded-full object-cover">
                <label for="profile_pic_input"
                    class="absolute -bottom-1 right-0 w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-secondary cursor-pointer hover:bg-gray-200 transition-colors border-2 border-white shadow-sm">
                    <i class="ri-pencil-line text-lg"></i>
                    <input type="file" id="profile_pic_input" class="hidden" accept="image/*">
                </label>
            </div>

            <h2 class="text-2xl font-bold font-sans! text-secondary mb-1">{{ $user->name }}</h2>
            <p class="text-lg text-gray-400 font-normal mb-10 text-capitalize">{{ str_replace('_', ' ', $user->role) }}</p>

            <div class="w-full px-4 space-y-4">
                <a href="javascript:void(0)" onclick="openPasswordModal()"
                    class="flex items-center text-gray-400 hover:text-gray-700 transition-colors text-lg">
                    <i class="ri-lock-line mr-3 text-lg"></i>
                    <span class="font-normal">Change Password</span>
                </a>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="flex items-center text-gray-400 hover:text-gray-700 transition-colors text-lg">
                    <i class="ri-logout-box-line mr-3 text-lg"></i>
                    <span class="font-normal">Logout</span>
                </a>
            </div>

            <!-- Healing Sanctuary -->
            <div class="w-full mt-20 relative">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-medium font-sans! text-gray-800">Healing Sanctuary</h3>
                    <button onclick="openGalleryModal()" class="text-gray-400 hover:text-secondary transition-colors">
                        <i class="ri-pencil-line text-xl"></i>
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    @forelse($sanctuaryImages->take(3) as $index => $img)
                        @if($index === 2 && $sanctuaryImages->count() > 3)
                            <div class="relative cursor-pointer group" onclick="openGalleryModal()">
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                    alt="Sanctuary {{ $index + 1 }}"
                                    class="h-[110px] w-full object-cover rounded-xl shadow-sm">
                                <div class="absolute inset-0 bg-secondary/30 backdrop-blur-[2px] rounded-xl flex items-center justify-center border border-white/20 shadow-inner group-hover:bg-secondary/40 transition-all">
                                    <span class="text-white text-xl font-bold tracking-wider">+{{ $sanctuaryImages->count() - 2 }}</span>
                                </div>
                            </div>
                        @else
                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                alt="Sanctuary {{ $index + 1 }}"
                                class="{{ $index === 0 ? 'col-span-2 h-[140px]' : 'h-[110px]' }} w-full object-cover rounded-xl shadow-sm">
                        @endif
                    @empty
                        <div class="col-span-2 py-8 bg-gray-50 rounded-xl border border-dashed border-gray-200 flex flex-col items-center justify-center text-gray-400">
                            <i class="ri-image-add-line text-3xl mb-2"></i>
                            <p class="text-sm">No sanctuary images</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>



    </div>

    <!-- Right Column: Details & Stats -->
    <div class="lg:col-span-8 xl:col-span-9 flex flex-col gap-8">

        <!-- Personal Details Card -->
        <div class="bg-white rounded-xl px-5 py-8 lg:p-12 border border-[#2E4B3D]/12 relative">
            <button class="absolute top-8 right-8 text-gray-400 hover:text-secondary transition-colors">
                <i class="ri-pencil-line text-2xl"></i>
            </button>

            <h2 class="text-2xl font-medium font-sans! text-secondary mb-8">Personal Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-8 gap-x-6">
                <div>
                    <p class="text-lg text-gray-400 mb-1">Age</p>
                    <p class="text-lg font-normal text-gray-800">{{ $age }}</p>
                </div>
                <div>
                    <p class="text-lg text-gray-400 mb-1">Gender</p>
                    <p class="text-lg font-normal text-gray-800">{{ $gender }}</p>
                </div>
                <div>
                    <p class="text-lg text-gray-400 mb-1">DOB</p>
                    <p class="text-lg font-normal text-gray-800">{{ $dob }}</p>
                </div>

                <div>
                    <p class="text-lg text-gray-400 mb-1">Nationality</p>
                    <p class="text-lg font-normal text-gray-800">{{ $nationality }}</p>
                </div>
                <div>
                    <p class="text-lg text-gray-400 mb-1">Phone</p>
                    <p class="text-lg font-normal text-gray-800">{{ $phone }}</p>
                </div>
                <div>
                    <p class="text-lg text-gray-400 mb-1">Email</p>
                    <p class="text-lg font-normal text-gray-800">{{ $email }}</p>
                </div>

                <div class="md:col-span-3">
                    <p class="text-lg text-gray-400 mb-1">Address</p>
                    <p class="text-lg font-normal text-gray-800 leading-relaxed">{{ $address }}</p>
                </div>
            </div>
        </div>

        @if(!in_array($user->role, ['client', 'patient']))
        <!-- Specialities & Conditions Card -->
        <div class="bg-white rounded-xl px-5 py-8 lg:p-12 border border-[#2E4B3D]/12">
            <!-- Specialities -->
            <div class="relative mb-8">
                <button onclick="openSpecialitiesModal()" class="absolute top-0 right-0 text-gray-400 hover:text-secondary transition-colors">
                    <i class="ri-pencil-line text-2xl"></i>
                </button>

                <h2 class="text-2xl font-medium font-sans! text-secondary mb-8">Specialities</h2>
                <div class="flex flex-wrap gap-2.5" id="specialities-display-container">
                    @forelse($specialities as $speciality)
                        <span class="px-6 py-2 bg-[#F6F6F6] text-gray-700 text-lg rounded-full">{{ is_array($speciality) ? implode(', ', $speciality) : $speciality }}</span>
                    @empty
                        <span class="text-gray-400 text-lg no-items-msg">No specialities listed.</span>
                    @endforelse
                </div>
            </div>

            <hr class="border-[#C5C5C5] mb-8">

            <!-- Conditions I support -->
            <div class="relative">
                <button onclick="openConditionsModal()" class="absolute top-0 right-0 text-gray-400 hover:text-[#2B4C3B] transition-colors">
                    <i class="ri-pencil-line text-2xl"></i>
                </button>

                <h2 class="text-2xl font-medium font-sans! text-secondary mb-8">Conditions I support</h2>
                <div class="flex flex-wrap gap-2.5" id="conditions-display-container">
                    @forelse($conditions as $condition)
                        <span class="px-6 py-2 bg-[#F6F6F6] text-gray-700 text-lg rounded-full">{{ is_array($condition) ? implode(', ', $condition) : $condition }}</span>
                    @empty
                        <span class="text-gray-400 text-lg no-items-msg">No conditions listed.</span>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

    </div>
</div>

@if(!in_array($user->role, ['client', 'patient']))
<!-- 4 Stats Banner -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 lg:gap-6 mt-8">
    <div
        class="bg-white rounded-xl px-4 py-8 flex flex-col items-center justify-center text-center border border-[#2E4B3D]/12">
        <h3 class="text-4xl lg:text-5xl font-medium font-sans! text-[#1A1A1A] mb-4">{{ $totalSessions }}</h3>
        <p class="text-base lg:text-xl text-gray-400 font-normal">Total Sessions</p>
    </div>
    <div
        class="bg-white rounded-xl px-4 py-8 flex flex-col items-center justify-center text-center border border-[#2E4B3D]/12">
        <h3 class="text-4xl lg:text-5xl font-medium font-sans! text-[#1A1A1A] mb-4">{{ $totalClients }}</h3>
        <p class="text-base lg:text-xl text-gray-400 font-normal">Total No.of Clients</p>
    </div>
    <div
        class="bg-white rounded-xl px-4 py-8 flex flex-col items-center justify-center text-center border border-[#2E4B3D]/12">
        <h3 class="text-4xl lg:text-5xl font-medium font-sans! text-[#1A1A1A] mb-4">{{ $todaySessions }}</h3>
        <p class="text-base lg:text-xl text-gray-400 font-normal">Today's Session</p>
    </div>
    <div
        class="bg-white rounded-xl px-4 py-8 flex flex-col items-center justify-center text-center border border-[#2E4B3D]/12">
        <h3 class="text-4xl lg:text-5xl font-medium font-sans! text-[#1A1A1A] mb-4">{{ $upcomingSessions }}</h3>
        <p class="text-base lg:text-xl text-gray-400 font-normal">Upcoming Sessions</p>
    </div>
</div>

<!-- History Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">

    <!-- Services History -->
    <div class="bg-white rounded-xl px-5 py-8 lg:p-8 border border-[#2E4B3D]/12 flex flex-col">
        <h2 class="text-2xl font-medium font-sans! text-[#2B4C3B] mb-8">Services History</h2>

        <div class="flex-1 space-y-6">
            @forelse($servicesHistory as $booking)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4 w-[198px]">
                    <img src="{{ $booking->user->profile_pic ? asset('storage/' . $booking->user->profile_pic) : asset('frontend/assets/profile-dummy-img.png') }}" class="w-13 h-13 rounded-full object-cover">
                    <div>
                        <p class="text-base font-medium text-gray-800">{{ $booking->user->name }}</p>
                        <p class="text-sm text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($booking->booking_date)->isToday() ? 'Today' : (\Carbon\Carbon::parse($booking->booking_date)->isYesterday() ? 'Yesterday' : \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y')) }}</p>
                    </div>
                </div>
                <div class="text-sm text-gray-400 hidden sm:block">
                    {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->booking_time)->addMinutes($booking->duration ?? 60)->format('h:i A') }}
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-2">
                    <div class="text-[10px] sm:text-sm text-gray-400 sm:hidden">
                        {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->booking_time)->addMinutes($booking->duration ?? 60)->format('h:i A') }}
                    </div>
                    <div class="w-[100px] sm:w-[120px] text-right">
                        <span class="bg-[#38C683] text-white text-xs sm:text-sm font-normal p-2 sm:px-4 sm:py-1.5 rounded-full inline-block text-center w-full">Completed</span>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-center py-4">No completed sessions found.</p>
            @endforelse
        </div>

        @if($servicesHistory->count() > 0)
        <div class="mt-8 text-center pt-2">
            <a href="{{ route('bookings.index') }}" class="text-lg text-gray-400 hover:text-gray-600 transition-colors font-normal">See all...</a>
        </div>
        @endif
    </div>

    <!-- Upcoming Services -->
    <div class="bg-white rounded-xl px-5 py-8 lg:p-8 border border-[#2E4B3D]/12 flex flex-col">
        <h2 class="text-2xl font-medium font-sans! text-secondary mb-8">Upcoming Services</h2>

        <div class="flex-1 space-y-6">
            @forelse($upcomingServices as $booking)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4 w-[198px]">
                    <img src="{{ $booking->user->profile_pic ? asset('storage/' . $booking->user->profile_pic) : asset('frontend/assets/profile-dummy-img.png') }}" class="w-13 h-13 rounded-full object-cover">
                    <div>
                        <p class="text-base font-medium text-gray-800">{{ $booking->user->name }}</p>
                        <p class="text-sm text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($booking->booking_date)->isToday() ? 'Today' : \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</p>

                    </div>
                </div>
                <div class="text-sm text-gray-400 hidden sm:block">
                    {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->booking_time)->addMinutes($booking->duration ?? 60)->format('h:i A') }}
                </div>
                <div class="flex flex-col sm:flex-row items-center gap-2">
                    <div class="text-[10px] sm:text-sm text-gray-400 sm:hidden">
                        {{ \Carbon\Carbon::parse($booking->booking_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($booking->booking_time)->addMinutes($booking->duration ?? 60)->format('h:i A') }}
                    </div>
                    <div class="w-[100px] sm:w-[120px] text-right">
                        @php
                            $statusColor = match($booking->status) {
                                'confirmed', 'pending' => 'bg-blue-400',
                                'cancelled' => 'bg-red-400',
                                default => 'bg-gray-400'
                            };
                        @endphp
                        <span class="{{ $statusColor }} text-white text-xs sm:text-sm font-normal p-2 sm:px-4 sm:py-1.5 rounded-full inline-block text-center w-full text-capitalize">{{ $booking->status }}</span>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-gray-400 text-center py-4">No upcoming sessions found.</p>
            @endforelse
        </div>

        @if($upcomingServices->count() > 0)
        <div class="mt-8 text-center pt-2">
            <a href="{{ route('bookings.index') }}" class="text-lg text-gray-400 hover:text-gray-600 transition-colors font-normal">See all...</a>
        </div>
        @endif
    </div>

</div>
@endif

<!-- Specialities Modal -->
<div id="specialitiesModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-secondary">Edit Specialities</h3>
            <button onclick="closeSpecialitiesModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ri-close-line text-3xl"></i>
            </button>
        </div>
        <form id="specialitiesForm" action="{{ route('profile.updateProfessional') }}" method="POST" class="px-8 py-8 space-y-6">
            @csrf
            <input type="hidden" name="update_type" value="specialities">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Select Specialities</label>
                <select id="specialities-select" name="specialities[]" multiple placeholder="Select or type specialities...">
                    @foreach($allSpecialities as $item)
                        <option value="{{ $item }}" {{ in_array($item, $specialities) ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                    @foreach($specialities as $item)
                        @if(!$allSpecialities->contains($item))
                            <option value="{{ $item }}" selected>{{ $item }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeSpecialitiesModal()" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-secondary text-white font-bold rounded-xl hover:bg-opacity-90 transition-all shadow-lg">Save Specialities</button>
            </div>
        </form>
    </div>
</div>

<!-- Conditions Modal -->
<div id="conditionsModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-secondary">Edit Conditions I Support</h3>
            <button onclick="closeConditionsModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ri-close-line text-3xl"></i>
            </button>
        </div>
        <form id="conditionsForm" action="{{ route('profile.updateProfessional') }}" method="POST" class="px-8 py-8 space-y-6">
            @csrf
            <input type="hidden" name="update_type" value="conditions">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Select Conditions</label>
                <select id="conditions-select" name="conditions[]" multiple placeholder="Select or type conditions...">
                    @foreach($allConditions as $item)
                        <option value="{{ $item }}" {{ in_array($item, $conditions) ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                    @foreach($conditions as $item)
                        @if(!$allConditions->contains($item))
                            <option value="{{ $item }}" selected>{{ $item }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeConditionsModal()" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-secondary text-white font-bold rounded-xl hover:bg-opacity-90 transition-all shadow-lg">Save Conditions</button>
            </div>
        </form>
    </div>
</div>

<!-- Manage Gallery Modal -->
<div id="galleryModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl max-h-[90vh] flex flex-col">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center shrink-0">
            <h3 class="text-2xl font-bold text-secondary">Manage Gallery</h3>
            <button onclick="closeGalleryModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ri-close-line text-3xl"></i>
            </button>
        </div>
        
        <div class="overflow-y-auto p-8 space-y-8">
            <!-- Upload Area -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-100">
                <h4 class="text-lg font-bold text-secondary mb-4">Add New Image</h4>
                
                <!-- Step 1: File Selection & Category -->
                <div id="upload-step-1" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Category</label>
                            <div class="relative custom-dropdown" id="gallery-category-dropdown">
                                <button type="button" class="dropdown-button w-full px-4 py-2 bg-white border border-gray-200 rounded-lg outline-none flex justify-between items-center transition-all hover:border-gray-300">
                                    <span class="dropdown-selected text-sm text-gray-700">Healing Sanctuary</span>
                                    <i class="ri-arrow-down-s-line text-gray-400 text-lg transition-transform duration-300 dropdown-icon"></i>
                                </button>
                                <div class="dropdown-menu absolute z-10 left-0 right-0 top-[calc(100%+4px)] bg-white border border-gray-100 rounded-xl shadow-xl py-2 opacity-0 invisible transition-all duration-300 transform origin-top translate-y-[-10px]">
                                    <button type="button" class="dropdown-item w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-secondary transition-colors" data-value="sanctuary">Healing Sanctuary</button>
                                    <button type="button" class="dropdown-item w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-secondary transition-colors" data-value="rituals">Expressive Rituals</button>
                                    <button type="button" class="dropdown-item w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-secondary transition-colors" data-value="soul">Medium of the Soul</button>
                                    <button type="button" class="dropdown-item w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 hover:text-secondary transition-colors" data-value="moments">Moments of Clarity</button>
                                </div>
                                <input type="hidden" id="gallery-category-select" value="sanctuary">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Image File(s)</label>
                            <input type="file" id="gallery-image-input" accept="image/*" multiple class="w-full px-4 py-2 bg-white border border-gray-200 rounded-lg outline-none">
                        </div>
                    </div>
                    <p class="text-xs text-gray-400">Recommended size: 800x600px. Max 5MB per image. Select multiple to batch upload.</p>
                </div>

                <!-- Step 2: Cropping Area -->
                <div id="upload-step-2" class="hidden space-y-4">
                    <div class="flex justify-between items-center px-1">
                        <span class="text-xs font-bold text-secondary uppercase">Cropping Image <span id="current-crop-index">1</span> of <span id="total-crop-count">1</span></span>
                    </div>
                    <div class="cropper-container rounded-lg overflow-hidden border border-gray-200">
                        <img id="cropper-image" src="">
                    </div>
                    <div class="flex gap-3">
                        <button onclick="cancelCrop()" class="flex-1 px-4 py-2 border border-gray-200 text-gray-600 font-bold rounded-lg hover:bg-gray-100 transition-colors">Cancel</button>
                        <button onclick="cropAndNext()" id="next-crop-btn" class="flex-2 px-6 py-2 bg-secondary text-white font-bold rounded-lg hover:bg-opacity-90 transition-all flex items-center justify-center gap-2">
                            <i class="ri-arrow-right-line"></i>
                            <span id="next-btn-text">Crop & Next</span>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Progress Bar -->
                <div id="upload-step-3" class="hidden space-y-2">
                    <div class="flex justify-between text-xs font-bold text-gray-500 mb-1">
                        <span>Uploading...</span>
                        <span id="upload-percentage">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                        <div id="upload-progress-bar" class="bg-secondary h-full w-0 progress-bar-fill"></div>
                    </div>
                </div>
            </div>

            <!-- View/Manage Area -->
            <div>
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <h4 class="text-lg font-bold text-secondary">Current Images</h4>
                    <div class="flex bg-gray-100 p-1 rounded-lg">
                        <button onclick="switchViewCategory('sanctuary')" class="cat-tab active px-3 py-1 text-xs font-bold rounded-md transition-all bg-white shadow-sm">Sanctuary</button>
                        <button onclick="switchViewCategory('rituals')" class="cat-tab px-3 py-1 text-xs font-bold rounded-md transition-all text-gray-500">Rituals</button>
                        <button onclick="switchViewCategory('soul')" class="cat-tab px-3 py-1 text-xs font-bold rounded-md transition-all text-gray-500">Soul</button>
                        <button onclick="switchViewCategory('moments')" class="cat-tab px-3 py-1 text-xs font-bold rounded-md transition-all text-gray-500">Moments</button>
                    </div>
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4" id="gallery-items-container">
                    @php
                        $allImages = $gallery->flatten();
                    @endphp
                    @forelse($allImages as $img)
                        <div class="relative group aspect-video rounded-xl overflow-hidden shadow-sm gallery-item" data-id="{{ $img->id }}" data-category="{{ $img->category }}" style="display: {{ $img->category === 'sanctuary' ? 'block' : 'none' }}">
                            <img src="{{ asset('storage/' . $img->image_path) }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                                <button onclick="previewImage('{{ asset('storage/' . $img->image_path) }}')" class="w-10 h-10 bg-white text-secondary rounded-full flex items-center justify-center hover:bg-gray-100 transition-colors shadow-lg">
                                    <i class="ri-fullscreen-line text-lg"></i>
                                </button>
                                <button onclick="deleteGalleryImage({{ $img->id }})" class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors shadow-lg">
                                    <i class="ri-delete-bin-line text-lg"></i>
                                </button>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-400 col-span-full text-center py-8 no-images-msg">No images found.</p>
                    @endforelse
                    <p class="text-gray-400 col-span-full text-center py-8 hidden empty-cat-msg">No images in this category yet.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-[60] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl">
        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-red-50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <i class="ri-delete-bin-line text-4xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-secondary mb-2">Delete Image?</h3>
            <p class="text-gray-500 mb-8 leading-relaxed">Are you sure you want to remove this image from your gallery? This action cannot be undone.</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                <button id="confirm-delete-btn" class="flex-1 px-6 py-3 bg-red-500 text-white font-bold rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-red-200">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Image Preview Modal -->
<div id="imagePreviewModal" class="fixed inset-0 bg-black/90 backdrop-blur-md hidden z-[70] flex items-center justify-center p-4">
    <button onclick="closePreviewModal()" class="absolute top-6 right-6 text-white hover:text-gray-300 transition-colors z-[71]">
        <i class="ri-close-line text-4xl"></i>
    </button>
    <img id="full-preview-image" src="" class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl">
</div>

<!-- Profile Picture Cropping Modal -->
<div id="profilePicModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-[80] flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-secondary">Crop Profile Picture</h3>
            <button onclick="closeProfilePicModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ri-close-line text-3xl"></i>
            </button>
        </div>
        <div class="p-8">
            <div class="cropper-container rounded-lg overflow-hidden border border-gray-200 mb-6">
                <img id="profile-crop-image" src="">
            </div>
            <div class="flex gap-4">
                <button type="button" onclick="closeProfilePicModal()" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="button" onclick="uploadProfilePic()" id="save-profile-pic-btn" class="flex-1 px-6 py-3 bg-secondary text-white font-bold rounded-xl hover:bg-opacity-90 transition-all shadow-lg">Save & Upload</button>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="passwordModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-secondary">Change Password</h3>
            <button onclick="closePasswordModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ri-close-line text-3xl"></i>
            </button>
        </div>
        <form id="changePasswordForm" action="{{ route('profile.updatePassword') }}" method="POST" class="px-8 py-8 space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Current Password</label>
                <input type="password" name="current_password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent transition-all outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">New Password</label>
                <div class="relative">
                    <input type="password" id="new_password" name="new_password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent transition-all outline-none pr-12">
                    <button type="button" onclick="togglePasswordVisibility('new_password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-secondary">
                        <i class="ri-eye-line" id="new_password_eye"></i>
                    </button>
                </div>
                
                <!-- Password Strength Checklist -->
                <div id="password-requirements" class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-100 hidden">
                    <p class="text-xs font-bold text-gray-500 uppercase mb-3 tracking-wider">Password Requirements</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 gap-x-4">
                        <div class="flex items-center gap-2 text-sm" id="req-length">
                            <i class="ri-checkbox-circle-fill text-gray-300 transition-colors"></i>
                            <span class="text-gray-500 transition-colors">8+ Characters</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm" id="req-upper">
                            <i class="ri-checkbox-circle-fill text-gray-300 transition-colors"></i>
                            <span class="text-gray-500 transition-colors">Uppercase Letter</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm" id="req-lower">
                            <i class="ri-checkbox-circle-fill text-gray-300 transition-colors"></i>
                            <span class="text-gray-500 transition-colors">Lowercase Letter</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm" id="req-number">
                            <i class="ri-checkbox-circle-fill text-gray-300 transition-colors"></i>
                            <span class="text-gray-500 transition-colors">At least one number</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm" id="req-special">
                            <i class="ri-checkbox-circle-fill text-gray-300 transition-colors"></i>
                            <span class="text-gray-500 transition-colors">Special character</span>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent transition-all outline-none">
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closePasswordModal()" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-secondary text-white font-bold rounded-xl hover:bg-opacity-90 transition-all shadow-lg">Update Password</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    let cropper = null;
    let currentViewCategory = 'sanctuary';
    const imageInput = document.getElementById('gallery-image-input');
    const categorySelect = document.getElementById('gallery-category-select');
    const cropperImage = document.getElementById('cropper-image');
    const step1 = document.getElementById('upload-step-1');
    const step2 = document.getElementById('upload-step-2');
    const step3 = document.getElementById('upload-step-3');
    const progressBar = document.getElementById('upload-progress-bar');
    const progressText = document.getElementById('upload-percentage');
    const categoryNameSpan = document.getElementById('target-category-name');

    // Multi-Select Instances
    let specialitiesSelect, conditionsSelect;

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Tom Select for Specialities
        const specEl = document.getElementById('specialities-select');
        if (specEl) {
            specialitiesSelect = new TomSelect('#specialities-select', {
                plugins: ['remove_button'],
                create: true,
                persist: false,
                placeholder: 'Select or type specialities...',
                maxItems: 15,
            });
        }

        // Initialize Tom Select for Conditions
        const condEl = document.getElementById('conditions-select');
        if (condEl) {
            conditionsSelect = new TomSelect('#conditions-select', {
                plugins: ['remove_button'],
                create: true,
                persist: false,
                placeholder: 'Select or type conditions...',
                maxItems: 15,
            });
        }
    });

    function openSpecialitiesModal() {
        document.getElementById('specialitiesModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeSpecialitiesModal() {
        document.getElementById('specialitiesModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openConditionsModal() {
        document.getElementById('conditionsModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeConditionsModal() {
        document.getElementById('conditionsModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Dynamic Updates for Specialities and Conditions
    function handleProfessionalFormSubmit(formId, modalId, displayContainerId) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerText;
            
            submitBtn.innerText = 'Saving...';
            submitBtn.disabled = true;

            const formData = new FormData(form);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    showToast(res.message);
                    
                    // Update the display container
                    const container = document.getElementById(displayContainerId);
                    if (container && res.items) {
                        container.innerHTML = '';
                        if (res.items.length > 0) {
                            res.items.forEach(item => {
                                const span = document.createElement('span');
                                span.className = 'px-6 py-2 bg-[#F6F6F6] text-gray-700 text-lg rounded-full';
                                span.innerText = Array.isArray(item) ? item.join(', ') : item;
                                container.appendChild(span);
                            });
                        } else {
                            const emptySpan = document.createElement('span');
                            emptySpan.className = 'text-gray-400 text-lg no-items-msg';
                            emptySpan.innerText = formId === 'specialitiesForm' ? 'No specialities listed.' : 'No conditions listed.';
                            container.appendChild(emptySpan);
                        }
                    }
                    
                    if (modalId === 'specialitiesModal') closeSpecialitiesModal();
                    else closeConditionsModal();
                } else {
                    showToast(res.message || 'Update failed', 'error');
                }
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            })
            .catch(err => {
                showToast('An error occurred.', 'error');
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        handleProfessionalFormSubmit('specialitiesForm', 'specialitiesModal', 'specialities-display-container');
        handleProfessionalFormSubmit('conditionsForm', 'conditionsModal', 'conditions-display-container');
    });

    function openGalleryModal() {
        document.getElementById('galleryModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        switchViewCategory(currentViewCategory);
    }

    function closeGalleryModal() {
        document.getElementById('galleryModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        cancelCrop();
    }

    function switchViewCategory(cat) {
        currentViewCategory = cat;
        // Update Tabs
        document.querySelectorAll('.cat-tab').forEach(tab => {
            if (tab.innerText.toLowerCase().includes(cat)) {
                tab.classList.add('active', 'bg-white', 'shadow-sm');
                tab.classList.remove('text-gray-500');
            } else {
                tab.classList.remove('active', 'bg-white', 'shadow-sm');
                tab.classList.add('text-gray-500');
            }
        });

        // Filter Images
        const items = document.querySelectorAll('.gallery-item');
        let count = 0;
        items.forEach(item => {
            if (item.getAttribute('data-category') === cat) {
                item.style.display = 'block';
                count++;
            } else {
                item.style.display = 'none';
            }
        });

        // Show/Hide empty message
        const emptyMsg = document.querySelector('.empty-cat-msg');
        const initialNoImages = document.querySelector('.no-images-msg');
        
        if (emptyMsg) {
            emptyMsg.classList.toggle('hidden', count > 0 || (initialNoImages && !initialNoImages.classList.contains('hidden')));
        }
    }

    // Custom Dropdown Logic
    const galleryDropdown = document.getElementById('gallery-category-dropdown');
    if (galleryDropdown) {
        const btn = galleryDropdown.querySelector('.dropdown-button');
        const menu = galleryDropdown.querySelector('.dropdown-menu');
        const items = galleryDropdown.querySelectorAll('.dropdown-item');
        const selectedText = galleryDropdown.querySelector('.dropdown-selected');
        const hiddenInput = document.getElementById('gallery-category-select');

        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = !menu.classList.contains('invisible');
            
            // Close others if any (none in this modal yet)
            
            if (isOpen) {
                menu.classList.add('opacity-0', 'invisible', 'translate-y-[-10px]');
                btn.querySelector('.dropdown-icon').classList.remove('rotate-180');
            } else {
                menu.classList.remove('opacity-0', 'invisible', 'translate-y-[-10px]');
                btn.querySelector('.dropdown-icon').classList.add('rotate-180');
            }
        });

        items.forEach(item => {
            item.addEventListener('click', () => {
                const val = item.getAttribute('data-value');
                const text = item.innerText;
                selectedText.innerText = text;
                hiddenInput.value = val;
                
                // Update target category name in upload button
                if (categoryNameSpan) categoryNameSpan.innerText = text;

                menu.classList.add('opacity-0', 'invisible', 'translate-y-[-10px]');
                btn.querySelector('.dropdown-icon').classList.remove('rotate-180');
            });
        });

        document.addEventListener('click', (e) => {
            if (!galleryDropdown.contains(e.target)) {
                menu.classList.add('opacity-0', 'invisible', 'translate-y-[-10px]');
                btn.querySelector('.dropdown-icon').classList.remove('rotate-180');
            }
        });
    }

    // Multi-Image Logic
    let imageQueue = [];
    let currentImageIndex = 0;
    let croppedImagesData = [];

    // Cropping Logic
    imageInput.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        if (files.length > 0) {
            imageQueue = files;
            currentImageIndex = 0;
            croppedImagesData = [];
            
            document.getElementById('total-crop-count').innerText = imageQueue.length;
            startCroppingSession();
        }
    });

    function startCroppingSession() {
        if (currentImageIndex >= imageQueue.length) {
            performBatchUpload();
            return;
        }

        const file = imageQueue[currentImageIndex];
        const reader = new FileReader();
        reader.onload = function(event) {
            if (cropper) cropper.destroy();
            cropperImage.src = event.target.result;
            
            document.getElementById('current-crop-index').innerText = currentImageIndex + 1;
            document.getElementById('next-btn-text').innerText = (currentImageIndex === imageQueue.length - 1) ? 'Finish & Upload' : 'Crop & Next';
            
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
            
            cropper = new Cropper(cropperImage, {
                aspectRatio: NaN,
                viewMode: 2,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        };
        reader.readAsDataURL(file);
    }

    function cropAndNext() {
        if (!cropper) return;

        const canvas = cropper.getCroppedCanvas({
            width: 800,
            height: 600,
        });

        const base64data = canvas.toDataURL('image/jpeg', 0.8);
        croppedImagesData.push(base64data);

        currentImageIndex++;
        if (currentImageIndex < imageQueue.length) {
            startCroppingSession();
        } else {
            performBatchUpload();
        }
    }

    function cancelCrop() {
        if (cropper) cropper.destroy();
        cropper = null;
        imageInput.value = '';
        imageQueue = [];
        croppedImagesData = [];
        step1.classList.remove('hidden');
        step2.classList.add('hidden');
        step3.classList.add('hidden');
    }

    function showToast(message, type = 'success') {
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: type === 'success' ? "#2E4B3D" : "#F3324C",
            stopOnFocus: true,
        }).showToast();
    }

    function performBatchUpload() {
        if (croppedImagesData.length === 0) return;

        if (cropper) {
            cropper.destroy();
            cropper = null;
        }

        step2.classList.add('hidden');
        step3.classList.remove('hidden');
        
        const selectedCat = categorySelect.value;
        const formData = new FormData();
        
        croppedImagesData.forEach((base64, index) => {
            formData.append(`cropped_images[${index}]`, base64);
        });
        
        formData.append('category', selectedCat);
        formData.append('_token', '{{ csrf_token() }}');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route("profile.gallery.upload") }}', true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.upload.onprogress = function(e) {
            if (e.lengthComputable) {
                const percent = Math.round((e.loaded / e.total) * 100);
                progressBar.style.width = percent + '%';
                progressText.innerText = percent + '%';
            }
        };

        xhr.onload = function() {
            if (xhr.status === 200) {
                const res = JSON.parse(xhr.responseText);
                showToast(res.message || 'Gallery updated successfully!');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                showToast('Upload failed. Please try again.', 'error');
                step3.classList.add('hidden');
                step1.classList.remove('hidden');
            }
        };

        xhr.onerror = function() {
            showToast('Network error occurred.', 'error');
            step3.classList.add('hidden');
            step1.classList.remove('hidden');
        };

        xhr.send(formData);
    }

    let imageIdToDelete = null;

    function openDeleteModal(id) {
        imageIdToDelete = id;
        document.getElementById('deleteConfirmModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        imageIdToDelete = null;
        document.getElementById('deleteConfirmModal').classList.add('hidden');
        if (!document.getElementById('galleryModal').classList.contains('hidden')) {
            // Keep hidden if gallery modal is still open
        } else {
            document.body.style.overflow = 'auto';
        }
    }

    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
        if (!imageIdToDelete) return;
        
        const id = imageIdToDelete;
        closeDeleteModal();

        fetch(`/profile/gallery/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(res => {
            showToast('Image removed.');
            const item = document.querySelector(`.gallery-item[data-id="${id}"]`);
            if (item) item.remove();
            switchViewCategory(currentViewCategory);
        })
        .catch(err => {
            showToast('Failed to delete image.', 'error');
        });
    });

    function deleteGalleryImage(id) {
        openDeleteModal(id);
    }

    function previewImage(url) {
        document.getElementById('full-preview-image').src = url;
        document.getElementById('imagePreviewModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closePreviewModal() {
        document.getElementById('imagePreviewModal').classList.add('hidden');
        if (document.getElementById('galleryModal').classList.contains('hidden') && 
            document.getElementById('professionalModal').classList.contains('hidden') &&
            document.getElementById('deleteConfirmModal').classList.contains('hidden')) {
            document.body.style.overflow = 'auto';
        }
    }

    window.onclick = function(event) {
        if (event.target == document.getElementById('professionalModal')) {
            closeProfessionalModal();
        }
        if (event.target == document.getElementById('galleryModal')) {
            closeGalleryModal();
        }
        if (event.target == document.getElementById('deleteConfirmModal')) {
            closeDeleteModal();
        }
        if (event.target == document.getElementById('imagePreviewModal')) {
            closePreviewModal();
        }
        if (event.target == document.getElementById('profilePicModal')) {
            closeProfilePicModal();
        }
        if (event.target == document.getElementById('passwordModal')) {
            closePasswordModal();
        }
    }

    // Password Change Logic
    function openPasswordModal() {
        document.getElementById('passwordModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        resetPasswordValidation();
    }

    function closePasswordModal() {
        document.getElementById('passwordModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('changePasswordForm').reset();
        resetPasswordValidation();
    }

    function togglePasswordVisibility(inputId) {
        const input = document.getElementById(inputId);
        const eye = document.getElementById(inputId + '_eye');
        if (input.type === 'password') {
            input.type = 'text';
            eye.classList.remove('ri-eye-line');
            eye.classList.add('ri-eye-off-line');
        } else {
            input.type = 'password';
            eye.classList.remove('ri-eye-off-line');
            eye.classList.add('ri-eye-line');
        }
    }

    const newPasswordInput = document.getElementById('new_password');
    const requirementsBox = document.getElementById('password-requirements');
    
    function resetPasswordValidation() {
        requirementsBox.classList.add('hidden');
        const reqs = ['req-length', 'req-upper', 'req-lower', 'req-number', 'req-special'];
        reqs.forEach(id => {
            const el = document.getElementById(id);
            el.querySelector('i').className = 'ri-checkbox-circle-fill text-gray-300 transition-colors';
            el.querySelector('span').className = 'text-gray-500 transition-colors';
        });
    }

    if (newPasswordInput) {
        newPasswordInput.addEventListener('input', function() {
            const val = this.value;
            if (val.length > 0) {
                requirementsBox.classList.remove('hidden');
            } else {
                requirementsBox.classList.add('hidden');
            }

            const checks = {
                'req-length': val.length >= 8,
                'req-upper': /[A-Z]/.test(val),
                'req-lower': /[a-z]/.test(val),
                'req-number': /[0-9]/.test(val),
                'req-special': /[!@#$%^&*(),.?":{}|<>]/.test(val)
            };

            let allPassed = true;
            Object.keys(checks).forEach(id => {
                const passed = checks[id];
                const el = document.getElementById(id);
                const icon = el.querySelector('i');
                const text = el.querySelector('span');

                if (passed) {
                    icon.className = 'ri-checkbox-circle-fill text-green-500 transition-colors';
                    text.className = 'text-green-600 font-bold transition-colors';
                } else {
                    icon.className = 'ri-checkbox-circle-fill text-gray-300 transition-colors';
                    text.className = 'text-gray-500 transition-colors';
                    allPassed = false;
                }
            });

            // Optional: You can disable the submit button until allPassed is true
            const submitBtn = document.getElementById('changePasswordForm').querySelector('button[type="submit"]');
            submitBtn.disabled = !allPassed;
            submitBtn.style.opacity = allPassed ? '1' : '0.5';
            submitBtn.style.cursor = allPassed ? 'pointer' : 'not-allowed';
        });
    }

    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerText;
        
        submitBtn.innerText = 'Updating...';
        submitBtn.disabled = true;

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                showToast(res.message);
                closePasswordModal();
            } else {
                let errorMsg = res.message || 'Update failed';
                if (res.errors) {
                    errorMsg = Object.values(res.errors).flat().join('\n');
                }
                showToast(errorMsg, 'error');
                submitBtn.innerText = originalText;
                submitBtn.disabled = false;
            }
        })
        .catch(err => {
            showToast('An error occurred. Please try again.', 'error');
            submitBtn.innerText = originalText;
            submitBtn.disabled = false;
        });
    });

    // Profile Picture Update Logic
    let profileCropper = null;
    const profilePicInput = document.getElementById('profile_pic_input');
    const profileCropImage = document.getElementById('profile-crop-image');

    profilePicInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                if (profileCropper) profileCropper.destroy();
                profileCropImage.src = event.target.result;
                openProfilePicModal();
                
                profileCropper = new Cropper(profileCropImage, {
                    aspectRatio: 1,
                    viewMode: 2,
                    guides: true,
                    center: true,
                    highlight: false,
                    cropBoxMovable: true,
                    cropBoxResizable: true,
                    toggleDragModeOnDblclick: false,
                });
            };
            reader.readAsDataURL(file);
        }
    });

    function openProfilePicModal() {
        document.getElementById('profilePicModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeProfilePicModal() {
        document.getElementById('profilePicModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
        profilePicInput.value = '';
        if (profileCropper) {
            profileCropper.destroy();
            profileCropper = null;
        }
    }

    function uploadProfilePic() {
        if (!profileCropper) return;

        const canvas = profileCropper.getCroppedCanvas({
            width: 400,
            height: 400,
        });

        const base64data = canvas.toDataURL('image/jpeg', 0.9);
        const saveBtn = document.getElementById('save-profile-pic-btn');
        const originalText = saveBtn.innerText;
        
        saveBtn.innerText = 'Uploading...';
        saveBtn.disabled = true;

        fetch('{{ route("profile.updatePic") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                cropped_image: base64data
            })
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                showToast(res.message);
                document.getElementById('user-profile-img').src = res.path;
                closeProfilePicModal();
            } else {
                showToast(res.message || 'Update failed', 'error');
                saveBtn.innerText = originalText;
                saveBtn.disabled = false;
            }
        })
        .catch(err => {
            showToast('Network error occurred.', 'error');
            saveBtn.innerText = originalText;
            saveBtn.disabled = false;
        });
    }
</script>
@endpush
@endsection
