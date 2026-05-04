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
    .ts-dropdown {
        z-index: 100001 !important;
    }
    /* Ensure the modal doesn't clip the dropdown if not using dropdownParent */
    #specialitiesModal .overflow-hidden, 
    #conditionsModal .overflow-hidden {
        overflow: visible !important;
    }
    
    input[type="date"] {
        min-height: 50px;
        -webkit-appearance: none;
        appearance: none;
        display: flex;
        align-items: center;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    input[type="date"]::-webkit-inner-spin-button,
    input[type="date"]::-webkit-calendar-picker-indicator {
        opacity: 0;
        -webkit-appearance: none;
        display: none;
    }
</style>
@endpush

@section('content')
@php
    $age = $profile && $profile->dob ? \Carbon\Carbon::parse($profile->dob)->age . ' Years' : 'Not set';
    $gender = $profile && $profile->gender ? ucfirst($profile->gender) : ($user->gender ? ucfirst($user->gender) : 'Not set');
    $dob = $profile && $profile->dob ? \Carbon\Carbon::parse($profile->dob)->format('M d, Y') : 'Not set';
    $nationality = $profile->nationality ?? ($profile->country ?? ($user->nationality ? $user->nationality->name : 'Not set'));
    $phone = ($profile->mobile_country_code ? $profile->mobile_country_code . '-' : '') . ($profile->phone ?? ($user->phone ?? 'Not set'));
    $email = $user->email;
    $patient = $user->patient ?? null;
    
    // Address logic
    $addressParts = array_filter([
        $profile->residential_address ?? null,
        $profile->address_line_1 ?? null,
        $profile->address_line_2 ?? null,
        $profile->city ?? null,
        $profile->state ?? null,
        $profile->zip_code ?? null,
        $profile->country ?? null
    ]);
    $address = !empty($addressParts) ? implode(', ', $addressParts) : ($profile->city_state ?? 'Not set');

    // Specialities & Conditions
    $specialities = [];
    $conditions = [];
    $modalities = [];

    switch($user->role) {
        case 'practitioner':
            $specialities = (array)($profile->specialization ?? []);
            $conditions = (array)($profile->health_conditions_treated ?? []);
            $modalities = (array)($profile->other_modalities ?? []);
            break;
        case 'doctor':
            $specialities = (array)($profile->specialization ?? []);
            $conditions = (array)($profile->health_conditions_treated ?? []);
            $modalities = (array)($profile->other_modalities ?? []);
            break;
        case 'mindfulness_practitioner':
            $specialities = (array)($profile->practitioner_type ?? []);
            $conditions = (array)($profile->client_concerns ?? []);
            $modalities = (array)($profile->other_modalities ?? []);
            break;
        case 'yoga_therapist':
            $specialities = (array)($profile->yoga_therapist_type ?? []);
            $conditions = (array)($profile->areas_of_expertise ?? []);
            $modalities = (array)($profile->other_modalities ?? []);
            break;
        case 'translator':
            $specialities = (array)($profile->fields_of_specialization ?? []);
            $conditions = (array)($profile->services_offered ?? []);
            $modalities = (array)($profile->other_modalities ?? []);
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
        <div class="bg-white rounded-3xl px-5 pt-12 pb-5 flex flex-col items-center border border-[#2E4B3D]/12">
            <div class="relative mb-6">
                @php
                    $avatar = $user->profile_pic_url;
                @endphp
                <img id="user-profile-img" src="{{ $avatar }}"
                    alt="{{ $user->name }}" class="w-38 h-38 rounded-full object-cover"
                    onerror="this.src='{{ asset('frontend/assets/profile-dummy-img.png') }}'">
                <label for="profile_pic_input"
                    class="absolute -bottom-1 right-0 w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-secondary cursor-pointer hover:bg-gray-200 transition-colors border-2 border-white shadow-sm">
                    <i class="ri-pencil-line text-lg"></i>
                    <input type="file" id="profile_pic_input" class="hidden" accept="image/*">
                </label>
                <button type="button" onclick="openRemovePicModal()" class="absolute -bottom-1 left-0 w-10 h-10 bg-white rounded-full flex items-center justify-center text-red-400 hover:text-red-600 hover:bg-red-50 transition-colors border-2 border-white shadow-sm">
                    <i class="ri-delete-bin-line text-lg"></i>
                </button>
            </div>

            <h2 class="text-2xl font-bold font-sans! text-secondary mb-1">{{ $user->name }}</h2>
            <p class="text-lg text-gray-400 font-normal mb-10 text-capitalize">{{ str_replace('_', ' ', $user->role) }}</p>

            <div class="w-full px-4 mb-6">
                @if($user->isProfileIncomplete())
                <a href="{{ route('profile.complete') }}"
                    class="flex items-center justify-center w-full py-3 bg-amber-50 text-amber-700 rounded-xl border border-amber-100 hover:bg-amber-100 transition-colors text-sm font-bold gap-2">
                    <i class="ri-information-line text-lg"></i>
                    <span>{{ __('Complete Your Profile') }}</span>
                </a>
                @else
                <a href="{{ route('profile.complete') }}"
                    class="flex items-center justify-center w-full py-3 bg-gray-50 text-gray-700 rounded-xl border border-gray-100 hover:bg-gray-100 transition-colors text-sm font-bold gap-2">
                    <i class="ri-edit-line text-lg"></i>
                    <span>{{ __('Edit Profile') }}</span>
                </a>
                @endif
            </div>

            <div class="w-full px-4 pt-4">
                <a href="javascript:void(0)" onclick="openPasswordModal()"
                    class="flex items-center text-gray-400 hover:text-gray-700 transition-colors text-lg">
                    <i class="ri-lock-line mr-3 text-lg"></i>
                    <span class="font-normal">{{ __('Change Password') }}</span>
                </a>
            </div>

          
        </div>



    </div>

    <!-- Right Column: Details & Stats -->
    <div class="lg:col-span-8 xl:col-span-9 flex flex-col gap-8">

        <!-- Personal Details Card -->
        <div class="bg-white rounded-3xl p-5 md:p-8 lg:p-12 border border-[#2E4B3D]/12 relative">
            <button type="button" onclick="openPersonalEditModal()" class="absolute top-8 right-8 text-gray-400 hover:text-secondary transition-colors">
                <i class="ri-pencil-line text-2xl"></i>
            </button>

            <h2 class="text-2xl font-medium font-sans! text-secondary mb-8">Personal Details</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-y-8 gap-x-6">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Age</p>
                    <p class="text-lg font-normal text-gray-800">{{ $age }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Gender</p>
                    <p class="text-lg font-normal text-gray-800">{{ $gender }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">DOB</p>
                    <p class="text-lg font-normal text-gray-800">{{ $dob }}</p>
                </div>

                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Nationality</p>
                    <p class="text-lg font-normal text-gray-800">{{ $nationality }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Phone</p>
                    <p class="text-lg font-normal text-gray-800">{{ $phone }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Email</p>
                    <p class="text-lg font-normal text-gray-800 break-all">{{ $email }}</p>
                </div>

                <div class="md:col-span-3">
                    <p class="text-[11px] font-bold uppercase tracking-wider text-gray-400 mb-1">Address</p>
                    <p class="text-lg font-normal text-gray-800 leading-relaxed">{{ $address }}</p>
                </div>
            </div>
        </div>

        @if(!in_array($user->role, ['client', 'patient']))
        <!-- Specialities & Conditions Card -->
        <div class="bg-white rounded-3xl p-5 md:p-8 lg:p-12 border border-[#2E4B3D]/12">
            <div id="profile-visibility-warning" class="{{ (!empty($specialities) || !empty($conditions)) ? 'hidden' : '' }} mb-8 p-4 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl flex items-start shadow-sm">
                <i class="ri-information-line mr-3 text-xl mt-0.5"></i>
                <p class="text-sm font-medium">
                    {{ __('Your profile will only be displayed to the public once you have added either your Specialities or the Conditions you support.') }}
                </p>
            </div>

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

            <hr class="border-[#C5C5C5] my-8">

            <!-- Other Modalities -->
            <div class="relative">
                <button onclick="openModalitiesModal()" class="absolute top-0 right-0 text-gray-400 hover:text-[#2B4C3B] transition-colors">
                    <i class="ri-pencil-line text-2xl"></i>
                </button>

                <h2 class="text-2xl font-medium font-sans! text-secondary mb-8">Other Modalities</h2>
                <div class="flex flex-wrap gap-2.5" id="modalities-display-container">
                    @forelse($modalities as $modality)
                        <span class="px-6 py-2 bg-[#F6F6F6] text-gray-700 text-lg rounded-full">{{ is_array($modality) ? implode(', ', $modality) : $modality }}</span>
                    @empty
                        <span class="text-gray-400 text-lg no-items-msg">No modalities listed.</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Documents & KYC Card -->
        <div class="bg-white rounded-3xl p-5 md:p-8 lg:p-12 border border-[#2E4B3D]/12">
            <h2 class="text-2xl font-medium font-sans! text-secondary mb-8">Documents & Certifications</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @php
                    $roleDocs = [
                        'doctor' => [
                            'reg_certificate_path' => 'Registration Certificate',
                            'aadhaar_upload_path' => 'Aadhaar Card Copy',
                            'pan_upload_path' => 'PAN Card Copy',
                            'digital_signature_path' => 'Digital Signature',
                            'cancelled_cheque_path' => 'Cancelled Cheque',
                            'degree_certificates_path' => 'Degree Certificates'
                        ],
                        'practitioner' => [
                            'doc_id_proof' => 'ID Proof (Passport/Aadhar)',
                            'doc_certificates' => 'Educational Certificates',
                            'doc_experience' => 'Experience Certificate',
                            'doc_registration' => 'Signed Registration Form',
                            'doc_ethics' => 'Signed Code of Ethics',
                            'doc_contract' => 'Signed ZAYA Contract',
                        ],
                        'mindfulness_practitioner' => [
                            'certificates_path' => 'Professional Certificates',
                            'registration_proof_path' => 'Registration Proof',
                            'gov_id_upload_path' => 'Government ID Proof',
                            'cancelled_cheque_path' => 'Cancelled Cheque',
                        ],
                        'yoga_therapist' => [
                            'registration_proof_path' => 'Registration Proof',
                            'certificates_path' => 'Yoga Therapy Certificates',
                            'gov_id_upload_path' => 'Government ID Proof',
                            'cancelled_cheque_path' => 'Cancelled Cheque',
                        ],
                        'translator' => [
                            'gov_id_upload_path' => 'Government ID Proof',
                            'cancelled_cheque_path' => 'Cancelled Cheque',
                        ],
                    ];

                    // Handle hyphenated roles
                    $normalizedRole = str_replace('-', '_', $user->role);
                    $currentDocs = $roleDocs[$normalizedRole] ?? [];
                @endphp

                @forelse($currentDocs as $field => $label)
                    <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100 flex flex-col justify-between">
                        <div class="mb-4">
                            <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">{{ $label }}</p>
                            @if($profile && $profile->$field)
                                @if(is_array($profile->$field))
                                    <div class="text-xs text-green-600 font-bold flex items-center gap-1">
                                        <i class="ri-checkbox-circle-fill"></i>
                                        {{ count($profile->$field) }} {{ __('Files Uploaded') }}
                                    </div>
                                @else
                                    <div class="text-xs text-green-600 font-bold flex items-center gap-1">
                                        <i class="ri-checkbox-circle-fill"></i>
                                        {{ __('Document Uploaded') }}
                                    </div>
                                @endif
                            @else
                                <div class="text-xs text-amber-500 font-bold flex items-center gap-1">
                                    <i class="ri-error-warning-fill"></i>
                                    {{ __('Not Provided') }}
                                </div>
                            @endif
                        </div>

                        @if($profile && $profile->$field)
                            <div class="flex flex-wrap gap-2">
                                @if(is_array($profile->$field))
                                    @foreach($profile->$field as $idx => $path)
                                        <div class="flex items-center gap-2 bg-white px-3 py-1.5 rounded-lg border border-gray-100 shadow-sm">
                                            <span class="text-[10px] font-bold text-gray-500">File {{ $idx + 1 }}</span>
                                            <a href="{{ asset('storage/' . $path) }}" target="_blank" class="text-secondary hover:text-opacity-80 transition-colors">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                            <a href="{{ asset('storage/' . $path) }}" download class="text-secondary hover:text-opacity-80 transition-colors">
                                                <i class="ri-download-line"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="flex items-center gap-3 bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                                        <a href="{{ asset('storage/' . $profile->$field) }}" target="_blank" class="flex items-center gap-2 text-sm font-bold text-secondary hover:underline">
                                            <i class="ri-eye-line text-lg"></i>
                                            View
                                        </a>
                                        <div class="w-px h-4 bg-gray-200"></div>
                                        <a href="{{ asset('storage/' . $profile->$field) }}" download class="flex items-center gap-2 text-sm font-bold text-secondary hover:underline">
                                            <i class="ri-download-line text-lg"></i>
                                            Download
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @else
                            <a href="{{ route('profile.complete') }}?tab=documents" class="text-xs font-bold text-secondary hover:underline flex items-center gap-1">
                                <i class="ri-add-circle-line"></i>
                                {{ __('Upload Now') }}
                            </a>
                        @endif
                    </div>
                @empty
                    <div class="col-span-2 py-8 text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                        <i class="ri-information-line text-3xl text-gray-300 mb-2 block"></i>
                        <p class="text-gray-400 font-medium">{{ __('No specific documents required.') }}</p>
                    </div>
                @endforelse
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
                    <img src="{{ $booking->user->profile_pic_url }}" class="w-13 h-13 rounded-full object-cover">
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
                    <img src="{{ $booking->user->profile_pic_url }}" class="w-13 h-13 rounded-full object-cover">
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
    <div class="bg-white rounded-3xl w-full max-w-lg max-h-[90vh] overflow-y-auto scrollbar-hide shadow-2xl">
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
    <div class="bg-white rounded-3xl w-full max-w-lg max-h-[90vh] overflow-y-auto scrollbar-hide shadow-2xl">
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

<!-- Other Modalities Modal -->
<div id="modalitiesModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl w-full max-w-lg max-h-[90vh] overflow-y-auto scrollbar-hide shadow-2xl">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-2xl font-bold text-secondary">Edit Other Modalities</h3>
            <button onclick="closeModalitiesModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ri-close-line text-3xl"></i>
            </button>
        </div>
        <form id="modalitiesForm" action="{{ route('profile.updateProfessional') }}" method="POST" class="px-8 py-8 space-y-6">
            @csrf
            <input type="hidden" name="update_type" value="modalities">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Select Modalities</label>
                <select id="modalities-select" name="modalities[]" multiple placeholder="Select or type modalities...">
                    @foreach($allModalities as $item)
                        <option value="{{ $item }}" {{ in_array($item, $modalities) ? 'selected' : '' }}>{{ $item }}</option>
                    @endforeach
                    @foreach($modalities as $item)
                        @if(!$allModalities->contains($item))
                            <option value="{{ $item }}" selected>{{ $item }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeModalitiesModal()" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-secondary text-white font-bold rounded-xl hover:bg-opacity-90 transition-all shadow-lg">Save Modalities</button>
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
                                <div class="dropdown-menu absolute z-[100002] left-0 right-0 top-[calc(100%+4px)] bg-white border border-gray-100 rounded-xl shadow-xl py-2 opacity-0 invisible transition-all duration-300 transform origin-top translate-y-[-10px]">
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
    <div class="bg-white rounded-3xl w-full max-w-lg max-h-[90vh] overflow-y-auto scrollbar-hide shadow-2xl">
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
                <div class="relative">
                    <input type="password" id="current_password" name="current_password" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent transition-all outline-none pr-12">
                    <button type="button" onclick="togglePasswordVisibility('current_password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-secondary">
                        <i class="ri-eye-line" id="current_password_eye"></i>
                    </button>
                </div>
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
                <div class="relative">
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent transition-all outline-none pr-12">
                    <button type="button" onclick="togglePasswordVisibility('new_password_confirmation')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-secondary">
                        <i class="ri-eye-line" id="new_password_confirmation_eye"></i>
                    </button>
                </div>
            </div>
            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closePasswordModal()" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" class="flex-1 px-6 py-3 bg-secondary text-white font-bold rounded-xl hover:bg-opacity-90 transition-all shadow-lg">Update Password</button>
            </div>
        </form>
    </div>
</div>

<!-- Personal Details Edit Modal -->
<div id="personal-edit-modal" class="fixed inset-0 bg-[#1A1A1A]/40 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl md:rounded-[32px] w-full max-w-[520px] max-h-[90vh] overflow-y-auto scrollbar-hide shadow-2xl scale-95 opacity-0 transition-all duration-200" id="personalEditContent">
        <div class="p-5 md:p-8">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-bold text-secondary">{{ __('Edit Personal Details') }}</h3>
                <button type="button" onclick="closePersonalEditModal()" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400">
                    <i class="ri-close-line text-2xl"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('profile.updatePersonal') }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5 mb-6">
                    <div class="col-span-1 md:col-span-2">
                        <label class="text-[11px] uppercase tracking-wider text-gray-500 font-bold mb-2 block">{{ __('Phone') }}</label>
                        <input type="text" name="phone" value="{{ old('phone', $profile->phone ?? ($user->phone ?? '')) }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:border-secondary outline-none">
                    </div>
                    <div>
                        <label class="text-[11px] uppercase tracking-wider text-gray-500 font-bold mb-2 block">{{ __('Gender') }}</label>
                        <select name="gender" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-white focus:border-secondary outline-none">
                            <option value="">{{ __('Select gender') }}</option>
                            @foreach (['male' => 'Male', 'female' => 'Female', 'transgender' => 'Transgender', 'other' => 'Other'] as $value => $label)
                                <option value="{{ $value }}" {{ old('gender', $profile->gender ?? $user->gender ?? '') === $value ? 'selected' : '' }}>{{ __($label) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] uppercase tracking-wider text-gray-500 font-bold mb-2 block">{{ __('DOB') }}</label>
                        <div class="relative">
                            <input type="date" name="dob" value="{{ old('dob', $profile && $profile->dob ? \Illuminate\Support\Carbon::parse($profile->dob)->format('Y-m-d') : '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:border-secondary outline-none pr-10 appearance-none">
                            <i class="ri-calendar-line absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                    <div>
                        <label class="text-[11px] uppercase tracking-wider text-gray-500 font-bold mb-2 block">{{ __('Email') }}</label>
                        <input type="email" value="{{ $email }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-gray-100 text-gray-500 cursor-not-allowed" readonly>
                    </div>
                    <div>
                        <label class="text-[11px] uppercase tracking-wider text-gray-500 font-bold mb-2 block">{{ __('Nationality') }}</label>
                        <select name="nationality" class="w-full border border-gray-200 rounded-xl px-4 py-3 bg-white focus:border-secondary outline-none">
                            <option value="">{{ __('Select nationality') }}</option>
                            @foreach($countries as $country)
                                <option value="{{ $country->name }}" {{ old('nationality', $nationality !== 'Not set' ? $nationality : '') === $country->name ? 'selected' : '' }}>
                                    {{ $country->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-[11px] uppercase tracking-wider text-gray-500 font-bold mb-2 block">{{ __('Address Line 1') }}</label>
                        <input type="text" name="address_line_1" value="{{ old('address_line_1', $profile->address_line_1 ?? $profile->residential_address ?? '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:border-secondary outline-none">
                    </div>
                    <div>
                        <label class="text-[11px] uppercase tracking-wider text-gray-500 font-bold mb-2 block">{{ __('Address Line 2') }}</label>
                        <input type="text" name="address_line_2" value="{{ old('address_line_2', $profile->address_line_2 ?? '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:border-secondary outline-none">
                    </div>
                    <div>
                        <label class="text-[11px] uppercase tracking-wider text-gray-500 font-bold mb-2 block">{{ __('City') }}</label>
                        <input type="text" name="city" value="{{ old('city', $profile->city ?? '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:border-secondary outline-none">
                    </div>
                    <div>
                        <label class="text-[11px] uppercase tracking-wider text-gray-500 font-bold mb-2 block">{{ __('State') }}</label>
                        <input type="text" name="state" value="{{ old('state', $profile->state ?? '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:border-secondary outline-none">
                    </div>
                    <div>
                        <label class="text-[11px] uppercase tracking-wider text-gray-500 font-bold mb-2 block">{{ __('Zip Code') }}</label>
                        <input type="text" name="zip_code" value="{{ old('zip_code', $profile->zip_code ?? '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:border-secondary outline-none">
                    </div>
                    <div>
                        <label class="text-[11px] uppercase tracking-wider text-gray-500 font-bold mb-2 block">{{ __('Country') }}</label>
                        <input type="text" name="country" value="{{ old('country', $profile->country ?? '') }}" class="w-full border border-gray-200 rounded-xl px-4 py-3 focus:border-secondary outline-none">
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closePersonalEditModal()" class="px-5 py-3 rounded-xl border border-gray-200 text-gray-600">{{ __('Cancel') }}</button>
                    <button type="submit" class="px-6 py-3 rounded-xl bg-secondary text-white hover:bg-opacity-90">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Remove Profile Pic Modal -->
<div id="removePicModal" class="fixed inset-0 bg-[#1A1A1A]/40 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-[32px] w-full max-w-[340px] overflow-hidden shadow-2xl scale-95 opacity-0 transition-all duration-200" id="removePicModalContent">
        <div class="p-8 text-center">
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm border border-red-100">
                <i class="ri-delete-bin-7-line text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-secondary mb-2">{{ __('Remove profile picture?') }}</h3>
            <p class="text-gray-500 mb-6 leading-relaxed text-sm">{{ __('This will revert your avatar to the default illustration.') }}</p>
            
            <div class="flex flex-col gap-3">
                <button type="button" onclick="confirmRemovePic()" class="w-full py-3 bg-red-500 text-white font-semibold rounded-2xl hover:bg-red-600 transition-all text-base shadow-md shadow-red-100">{{ __('Yes, remove it') }}</button>
                <button type="button" onclick="closeRemovePicModal()" class="w-full py-3 bg-gray-50 text-gray-600 font-semibold rounded-2xl hover:bg-gray-100 transition-all text-base">{{ __('Cancel') }}</button>
            </div>
        </div>
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
    let specialitiesSelect, conditionsSelect, modalitiesSelect;

    document.addEventListener('DOMContentLoaded', function() {
        // Common Tom Select config
        const commonConfig = {
            plugins: ['remove_button'],
            create: true,
            persist: false,
            maxItems: 15,
            maxOptions: 100, // Show more options by default
            dropdownParent: 'body', // Layer on top of everything
        };

        // Initialize Tom Select for Specialities
        const specEl = document.getElementById('specialities-select');
        if (specEl) {
            specialitiesSelect = new TomSelect('#specialities-select', {
                ...commonConfig,
                placeholder: 'Select or type specialities...',
            });
        }

        // Initialize Tom Select for Conditions
        const condEl = document.getElementById('conditions-select');
        if (condEl) {
            conditionsSelect = new TomSelect('#conditions-select', {
                ...commonConfig,
                placeholder: 'Select or type conditions...',
            });
        }

        // Initialize Tom Select for Modalities
        const modEl = document.getElementById('modalities-select');
        if (modEl) {
            modalitiesSelect = new TomSelect('#modalities-select', {
                ...commonConfig,
                placeholder: 'Select or type modalities...',
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

    function openModalitiesModal() {
        document.getElementById('modalitiesModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModalitiesModal() {
        document.getElementById('modalitiesModal').classList.add('hidden');
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
                            if (formId === 'specialitiesForm') emptySpan.innerText = 'No specialities listed.';
                            else if (formId === 'conditionsForm') emptySpan.innerText = 'No conditions listed.';
                            else emptySpan.innerText = 'No modalities listed.';
                            container.appendChild(emptySpan);
                        }
                    }
                    
                    if (modalId === 'specialitiesModal') closeSpecialitiesModal();
                    else if (modalId === 'conditionsModal') closeConditionsModal();
                    else closeModalitiesModal();

                    // Dynamic Warning Check
                    const warning = document.getElementById('profile-visibility-warning');
                    if (warning) {
                        const specContainer = document.getElementById('specialities-display-container');
                        const condContainer = document.getElementById('conditions-display-container');
                        const modContainer = document.getElementById('modalities-display-container');
                        
                        const hasSpecs = specContainer && specContainer.querySelectorAll('span:not(.no-items-msg)').length > 0;
                        const hasConds = condContainer && condContainer.querySelectorAll('span:not(.no-items-msg)').length > 0;
                        const hasMods = modContainer && modContainer.querySelectorAll('span:not(.no-items-msg)').length > 0;

                        if (hasSpecs || hasConds || hasMods) {
                            warning.classList.add('hidden');
                        } else {
                            warning.classList.remove('hidden');
                        }
                    }
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
        handleProfessionalFormSubmit('modalitiesForm', 'modalitiesModal', 'modalities-display-container');

        // Auto-open based on redirect from banner
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('edit') === 'professional') {
            openSpecialitiesModal();
        } else if (urlParams.get('edit') === 'personal') {
            openPersonalEditModal();
        }
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
        if (window.showZayaToast) {
            window.showZayaToast(message, type, 'Profile');
        } else {
            // Fallback if not loaded
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: type === 'success' ? "#2E4B3D" : "#F3324C",
                stopOnFocus: true,
            }).showToast();
        }
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

    function openRemovePicModal() {
        const modal = document.getElementById('removePicModal');
        const content = document.getElementById('removePicModalContent');
        if (!modal || !content) return;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeRemovePicModal() {
        const modal = document.getElementById('removePicModal');
        const content = document.getElementById('removePicModalContent');
        if (!modal || !content) return;
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 200);
    }

    function confirmRemovePic() {
        fetch('{{ route("profile.updatePic") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ remove: true })
        })
        .then(response => response.json())
        .then(res => {
            if (res.success) {
                showToast(res.message || '{{ __("Profile picture removed.") }}');
                document.getElementById('user-profile-img').src = '{{ asset('frontend/assets/profile-dummy-img.png') }}';
            } else {
                showToast(res.message || 'Remove failed', 'error');
            }
        })
        .catch(() => showToast('Network error occurred.', 'error'))
        .finally(() => closeRemovePicModal());
    }

    function openPersonalEditModal() {
        const modal = document.getElementById('personal-edit-modal');
        const content = document.getElementById('personalEditContent');
        if (!modal || !content) return;
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closePersonalEditModal() {
        const modal = document.getElementById('personal-edit-modal');
        const content = document.getElementById('personalEditContent');
        if (!modal || !content) return;
        content.classList.add('scale-95', 'opacity-0');
        content.classList.remove('scale-100', 'opacity-100');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 200);
    }

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

    // Close remove-pic modal when clicking backdrop
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('removePicModal');
        if (modal && !modal.classList.contains('hidden') && e.target === modal) {
            closeRemovePicModal();
        }
    });

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

        ['current_password', 'new_password', 'new_password_confirmation'].forEach((id) => {
            const input = document.getElementById(id);
            if (input) {
                input.type = 'password';
            }
            const eye = document.getElementById(id + '_eye');
            if (eye) {
                eye.classList.remove('ri-eye-off-line');
                eye.classList.add('ri-eye-line');
            }
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

    if (profilePicInput && profileCropImage) profilePicInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                if (profileCropper) {
                    profileCropper.destroy();
                    profileCropper = null;
                }

                // Cropper must be initialized after the image element has finished loading,
                // otherwise it can keep showing the previously-cropped image.
                profileCropImage.onload = function() {
                    profileCropImage.onload = null;
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

                profileCropImage.src = event.target.result;
                openProfilePicModal();
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
        if (profilePicInput) profilePicInput.value = '';
        if (profileCropImage) {
            profileCropImage.onload = null;
            profileCropImage.src = '';
        }
        if (profileCropper) {
            profileCropper.destroy();
            profileCropper = null;
        }
    }

    function uploadProfilePic() {
        if (!profileCropper) {
            showToast('Please select an image and crop it first.', 'error');
            return;
        }

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

<!-- Remove Profile Pic Modal -->
<div id="removePicModal" class="fixed inset-0 bg-[#1A1A1A]/40 backdrop-blur-sm hidden z-[100] flex items-center justify-center p-4">
    <div class="bg-white rounded-[32px] w-full max-w-[340px] overflow-hidden shadow-2xl scale-95 opacity-0 transition-all duration-200" id="removePicModalContent">
        <div class="p-8 text-center">
            <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm border border-red-100">
                <i class="ri-delete-bin-7-line text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-secondary mb-2">{{ __('Remove profile picture?') }}</h3>
            <p class="text-gray-500 mb-6 leading-relaxed text-sm">{{ __('This will revert your avatar to the default illustration.') }}</p>
            
            <div class="flex flex-col gap-3">
                <button type="button" onclick="confirmRemovePic()" class="w-full py-3 bg-red-500 text-white font-semibold rounded-2xl hover:bg-red-600 transition-all text-base shadow-md shadow-red-100">{{ __('Yes, remove it') }}</button>
                <button type="button" onclick="closeRemovePicModal()" class="w-full py-3 bg-gray-50 text-gray-600 font-semibold rounded-2xl hover:bg-gray-100 transition-all text-base">{{ __('Cancel') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection
