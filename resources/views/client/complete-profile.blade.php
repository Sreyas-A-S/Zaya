@extends('layouts.client')

@section('title', 'Edit Profile')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    .section-card { transition: all 0.3s ease; }
    .ts-control { border-radius: 0.75rem !important; padding: 0.75rem !important; border-color: #e5e7eb !important; }
    
    .tab-btn {
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
    }
    .tab-btn.active {
        color: #2E4B3D;
        border-bottom: 3px solid #2E4B3D;
        font-weight: 900;
    }
    .tab-content {
        display: none;
        opacity: 0;
    }
    @keyframes tabFadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .tab-content.active {
        display: block;
        animation: tabFadeIn 0.4s ease-out forwards;
    }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

@section('content')
<div class="w-full">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-black text-secondary">{{ __('Edit Profile') }}</h1>
            <p class="text-xs text-gray-400 font-medium">{{ __('Profile settings for ' . ucfirst(str_replace('_', ' ', $user->role))) }}</p>
        </div>
        <div class="bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-100 flex items-center gap-3">
            <div class="w-24 bg-amber-200/30 rounded-full h-1 overflow-hidden">
                <div class="bg-amber-500 h-full transition-all duration-1000" style="width: {{ $user->profile_status['percentage'] }}%"></div>
            </div>
            <span class="text-xs font-black text-amber-600">{{ $user->profile_status['percentage'] }}%</span>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="flex items-center gap-8 border-b border-gray-100 mb-8 overflow-x-auto no-scrollbar">
        <button type="button" onclick="switchTab('personal')" class="tab-btn active pb-4 text-sm font-bold text-gray-400 whitespace-nowrap transition-all" id="tab-btn-personal">
            01. {{ __('Personal Details') }}
        </button>
        @if(!in_array($user->role, ['client', 'patient']))
            <button type="button" onclick="switchTab('professional')" class="tab-btn pb-4 text-sm font-bold text-gray-400 whitespace-nowrap transition-all" id="tab-btn-professional">
                02. {{ __('Professional') }}
            </button>
            <button type="button" onclick="switchTab('financial')" class="tab-btn pb-4 text-sm font-bold text-gray-400 whitespace-nowrap transition-all" id="tab-btn-financial">
                03. {{ __('KYC & Bank') }}
            </button>
            <button type="button" onclick="switchTab('documents')" class="tab-btn pb-4 text-sm font-bold text-gray-400 whitespace-nowrap transition-all" id="tab-btn-documents">
                04. {{ __('Documents') }}
            </button>
        @else
            <button type="button" onclick="switchTab('preferences')" class="tab-btn pb-4 text-sm font-bold text-gray-400 whitespace-nowrap transition-all" id="tab-btn-preferences">
                02. {{ __('Preferences & Referral') }}
            </button>
        @endif
    </div>

    <form action="{{ route('profile.complete.store') }}" method="POST" enctype="multipart/form-data" id="complete-profile-form">
        @csrf

        <!-- 1. Personal Tab (All Roles) -->
        <div id="tab-personal" class="tab-content active animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm space-y-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary/5 flex items-center justify-center text-secondary">
                        <i class="ri-user-smile-line text-xl"></i>
                    </div>
                    <h2 class="text-xl font-black text-secondary">{{ __('Personal & Contact Details') }}</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Email Address') }}</label>
                        <div class="relative flex items-center">
                            <input type="email" name="email" id="email-input" value="{{ $user->email }}" class="w-full pl-4 pr-32 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                            <div class="absolute right-2 flex items-center gap-2">
                                <span id="email-verified-badge" class="{{ $user->email_verified_at ? '' : 'hidden' }} px-3 py-1 bg-green-50 text-green-600 text-[10px] font-black uppercase tracking-wider rounded-lg border border-green-100 flex items-center gap-1">
                                    <i class="ri-checkbox-circle-fill"></i> {{ __('Verified') }}
                                </span>
                                <button type="button" id="send-otp-btn" onclick="sendOTP()" class="{{ $user->email_verified_at ? 'hidden' : '' }} px-4 py-1.5 bg-secondary text-white text-[10px] font-black uppercase tracking-wider rounded-lg hover:bg-opacity-90 transition-all">
                                    {{ __('Verify Now') }}
                                </button>
                            </div>
                        </div>
                        <p id="email-change-warning" class="hidden mt-2 text-[10px] text-amber-600 font-bold uppercase tracking-wider">
                            <i class="ri-error-warning-line"></i> {{ __('Email changed. Re-verification required.') }}
                        </p>
                    </div>

                    <!-- OTP Input Group -->
                    <div id="otp-group" class="hidden md:col-span-2 p-6 bg-amber-50 rounded-2xl border border-amber-100 animate-in fade-in slide-in-from-top-2 duration-300">
                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <h4 class="text-sm font-bold text-secondary mb-1">{{ __('Enter verification code') }}</h4>
                                <p class="text-xs text-gray-500 font-medium">{{ __('We have sent a 6-digit code to your email address.') }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="text" id="otp-input" maxlength="6" placeholder="000000" class="w-32 px-4 py-2 rounded-xl border border-amber-200 text-center font-black tracking-[0.5em] focus:ring-2 focus:ring-amber-500 outline-none">
                                <button type="button" onclick="verifyOTP()" id="verify-otp-btn" class="px-6 py-2 bg-amber-500 text-white text-xs font-black uppercase rounded-xl hover:bg-amber-600 transition-all">
                                    {{ __('Verify') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2 grid grid-cols-4 gap-4">
                        <div class="col-span-1">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Code') }}</label>
                            <select name="mobile_country_code" id="country-code-select" class="w-full">
                                <option value="">Code</option>
                                @foreach($countryCodes as $cc)
                                    <option value="{{ $cc['code'] }}" {{ ($profile->mobile_country_code ?? '') == $cc['code'] ? 'selected' : '' }}>
                                        {{ $cc['code'] }} ({{ $cc['name'] }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-3">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Phone Number') }}</label>
                            <input type="text" name="phone" value="{{ $profile->phone ?? $user->phone ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Gender') }}</label>
                        <select name="gender" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none bg-white">
                            <option value="">{{ __('Select Gender') }}</option>
                            <option value="male" {{ (strtolower($profile->gender ?? $user->gender ?? '') == 'male') ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ (strtolower($profile->gender ?? $user->gender ?? '') == 'female') ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ (strtolower($profile->gender ?? $user->gender ?? '') == 'other') ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Date of Birth') }}</label>
                        <input type="date" name="dob" value="{{ $profile->dob ? \Carbon\Carbon::parse($profile->dob)->format('Y-m-d') : '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                    </div>

                    @if(in_array($user->role, ['practitioner', 'client', 'patient']))
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Nationality') }}</label>
                        <select name="nationality" id="nationality-select" class="w-full">
                            <option value="">{{ __('Select Nationality') }}</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->name }}" {{ ($profile->nationality ?? '') == $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    @if(in_array($user->role, ['client', 'patient']))
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Occupation / Lifestyle') }}</label>
                        <input type="text" name="occupation" value="{{ $profile->occupation ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                    </div>
                    @endif

                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Address Line 1') }}</label>
                        <input type="text" name="address_line_1" value="{{ $profile->address_line_1 ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('City') }}</label>
                        <input type="text" name="city" value="{{ $profile->city ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('State') }}</label>
                        <input type="text" name="state" value="{{ $profile->state ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Zip Code') }}</label>
                        <input type="text" name="zip_code" value="{{ $profile->zip_code ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Country') }}</label>
                        <select name="country" id="country-select" class="w-full">
                            <option value="">{{ __('Select Country') }}</option>
                            @foreach($countries as $c)
                                <option value="{{ $c->name }}" {{ ($profile->country ?? '') == $c->name ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="button" onclick="switchTab('{{ in_array($user->role, ['client', 'patient']) ? 'preferences' : 'professional' }}')" class="px-8 py-3 bg-secondary text-white font-black rounded-2xl hover:bg-opacity-90 transition-all flex items-center gap-2">
                        {{ __('Next Step') }}
                        <i class="ri-arrow-right-line"></i>
                    </button>
                </div>
            </div>
        </div>

        @if(in_array($user->role, ['client', 'patient']))
        <!-- 2. Preferences & Referral Tab (Client Only) -->
        <div id="tab-preferences" class="tab-content animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm space-y-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary/5 flex items-center justify-center text-secondary">
                        <i class="ri-heart-line text-xl"></i>
                    </div>
                    <h2 class="text-xl font-black text-secondary">{{ __('Preferences & Referral') }}</h2>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Preferred Speciality of Consultation') }}</label>
                        <select id="specialities-select" name="consultation_preferences[]" multiple>
                            @foreach($allSpecialities as $item)
                                <option value="{{ $item }}" {{ in_array($item, (array)($profile->consultation_preferences ?? [])) ? 'selected' : '' }}>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Languages Spoken') }}</label>
                        <select id="languages-spoken-select" name="languages_spoken[]" multiple>
                            @foreach($allLanguages as $lang)
                                <option value="{{ $lang }}" {{ in_array($lang, (array)($profile->languages_spoken ?? [])) ? 'selected' : '' }}>{{ $lang }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-gray-50">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Referral Type') }}</label>
                            <select name="referral_type" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none bg-white">
                                <option value="">{{ __('Select Type') }}</option>
                                @foreach(['Direct', 'Practitioner', 'Client', 'Social Media', 'Other'] as $type)
                                    <option value="{{ $type }}" {{ ($profile->referral_type ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Referring Name (If any)') }}</label>
                            <input type="text" name="referrer_name" value="{{ $profile->referrer_name ?? '' }}" placeholder="Enter name" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                    </div>
                </div>

                <div class="flex justify-between pt-4">
                    <button type="button" onclick="switchTab('personal')" class="px-8 py-3 border border-gray-200 text-gray-600 font-bold rounded-2xl hover:bg-gray-50 transition-all">
                        {{ __('Previous') }}
                    </button>
                    <button type="submit" class="px-12 py-4 bg-secondary text-white font-black rounded-2xl shadow-xl shadow-secondary/20 hover:bg-opacity-90 transform hover:-translate-y-1 transition-all text-lg">
                        {{ __('Save Profile') }}
                    </button>
                </div>
            </div>
        </div>
        @else
        <!-- 2. Professional Tab (Practitioners/Doctors/Translators) -->
        <div id="tab-professional" class="tab-content animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm space-y-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary/5 flex items-center justify-center text-secondary">
                        <i class="ri-briefcase-line text-xl"></i>
                    </div>
                    <h2 class="text-xl font-black text-secondary">{{ __('Professional Expertise') }}</h2>
                </div>

                <div class="space-y-6">
                    @if($user->role == 'doctor')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('AYUSH Registration Number') }}</label>
                            <input type="text" name="ayush_registration_number" value="{{ $profile->ayush_registration_number ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('State Council Name') }}</label>
                            <input type="text" name="state_ayurveda_council_name" value="{{ $profile->state_ayurveda_council_name ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Primary Qualification') }}</label>
                            <input type="text" name="primary_qualification" value="{{ $profile->primary_qualification ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Years of Experience') }}</label>
                            <input type="number" name="years_of_experience" value="{{ $profile->years_of_experience ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                    </div>
                    @endif

                    @if($user->role == 'practitioner')
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Passing Year') }}</label>
                            <input type="text" name="passing_year" value="{{ $profile->passing_year ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Institute Name') }}</label>
                            <input type="text" name="institute_name" value="{{ $profile->institute_name ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Training Title') }}</label>
                            <input type="text" name="training_title" value="{{ $profile->training_title ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                    </div>
                    @endif

                    @if($user->role == 'mindfulness_practitioner')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Highest Education') }}</label>
                            <input type="text" name="highest_education" value="{{ $profile->highest_education ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Years of Experience') }}</label>
                            <input type="number" name="years_of_experience" value="{{ $profile->years_of_experience ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Mindfulness Training Details') }}</label>
                            <textarea name="mindfulness_training_details" rows="3" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">{{ $profile->mindfulness_training_details ?? '' }}</textarea>
                        </div>
                    </div>
                    @endif

                    @if($user->role == 'yoga_therapist')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Yoga Therapist Type') }}</label>
                            <input type="text" name="yoga_therapist_type" value="{{ $profile->yoga_therapist_type ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Years of Experience') }}</label>
                            <input type="number" name="years_of_experience" value="{{ $profile->years_of_experience ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Current Organization') }}</label>
                            <input type="text" name="current_organization" value="{{ $profile->current_organization ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Registration Number') }}</label>
                            <input type="text" name="registration_number" value="{{ $profile->registration_number ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                    </div>
                    @endif

                    @if($user->role == 'translator')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Native Language') }}</label>
                            <select name="native_language" id="native-language-select" class="w-full">
                                <option value="">{{ __('Select Language') }}</option>
                                @foreach($allLanguages as $lang)
                                    <option value="{{ $lang }}" {{ ($profile->native_language ?? '') == $lang ? 'selected' : '' }}>{{ $lang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Years of Experience') }}</label>
                            <input type="number" name="years_of_experience" value="{{ $profile->years_of_experience ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Previous Clients/Projects') }}</label>
                            <textarea name="previous_clients_projects" rows="2" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">{{ $profile->previous_clients_projects ?? '' }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Portfolio/Sample Work Link') }}</label>
                            <input type="url" name="portfolio_link" value="{{ $profile->portfolio_link ?? '' }}" placeholder="https://..." class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                        </div>
                    </div>
                    @endif

                    <!-- Multi-Select Specialities/Services -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($user->role == 'translator')
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Source Languages') }}</label>
                            <select id="source-languages-select" name="source_languages[]" multiple>
                                @foreach($allLanguages as $lang)
                                    <option value="{{ $lang }}" {{ in_array($lang, (array)($profile->source_languages ?? [])) ? 'selected' : '' }}>{{ $lang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Target Languages') }}</label>
                            <select id="target-languages-select" name="target_languages[]" multiple>
                                @foreach($allLanguages as $lang)
                                    <option value="{{ $lang }}" {{ in_array($lang, (array)($profile->target_languages ?? [])) ? 'selected' : '' }}>{{ $lang }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Specialities') }}</label>
                            <select id="specialities-select" name="{{ in_array($user->role, ['doctor', 'practitioner']) ? 'specialization' : ($user->role == 'mindfulness_practitioner' ? 'practitioner_type' : ($user->role == 'yoga_therapist' ? 'areas_of_expertise' : 'fields_of_specialization')) }}[]" multiple>
                                @foreach($allSpecialities as $item)
                                    <option value="{{ $item }}" {{ in_array($item, (array)($profile->specialization ?? $profile->practitioner_type ?? $profile->fields_of_specialization ?? $profile->areas_of_expertise ?? [])) ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Conditions I Support') }}</label>
                            <select id="conditions-select" name="{{ in_array($user->role, ['doctor', 'practitioner']) ? 'health_conditions_treated' : ($user->role == 'mindfulness_practitioner' ? 'client_concerns' : ($user->role == 'yoga_therapist' ? 'consultation_modes' : 'services_offered')) }}[]" multiple>
                                @foreach($allConditions as $item)
                                    <option value="{{ $item }}" {{ in_array($item, (array)($profile->health_conditions_treated ?? $profile->client_concerns ?? $profile->services_offered ?? $profile->consultation_modes ?? [])) ? 'selected' : '' }}>{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if(in_array($user->role, ['mindfulness_practitioner', 'yoga_therapist', 'practitioner', 'doctor']))
                        <div class="{{ $user->role == 'doctor' ? 'md:col-span-2' : '' }}">
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Languages Spoken') }}</label>
                            <select id="languages-spoken-select" name="languages_spoken[]" multiple>
                                @foreach($allLanguages as $lang)
                                    <option value="{{ $lang }}" {{ in_array($lang, (array)($profile->languages_spoken ?? [])) ? 'selected' : '' }}>{{ $lang }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>

                    @if(in_array($user->role, ['mindfulness_practitioner', 'yoga_therapist', 'practitioner', 'doctor']))
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Short Bio') }}</label>
                        <textarea name="{{ $user->role == 'doctor' ? 'short_doctor_bio' : ($user->role == 'practitioner' ? 'profile_bio' : 'short_bio') }}" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">{{ $profile->short_doctor_bio ?? $profile->short_bio ?? $profile->profile_bio ?? '' }}</textarea>
                    </div>
                    @endif

                    @if($user->role == 'doctor')
                    <div class="bg-amber-50 rounded-2xl p-6 border border-amber-100 space-y-4">
                        <h4 class="text-sm font-black text-amber-600 uppercase tracking-widest">{{ __('Confirmations & Consents') }}</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach([
                                'ayush_registration_confirmed' => 'I confirm my AYUSH registration is valid.',
                                'ayush_guidelines_agreed' => 'I agree to the practitioner guidelines.',
                                'document_verification_consented' => 'I certify all documents are accurate.',
                                'policies_agreed' => 'I agree to the platform policies.',
                                'prescription_understanding_agreed' => 'I understand prescription regulations.',
                                'confidentiality_consented' => 'I agree to maintain client confidentiality.'
                            ] as $field => $label)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="{{ $field }}" value="1" {{ ($profile->$field ?? false) ? 'checked' : '' }} class="w-5 h-5 rounded border-amber-300 text-amber-500 focus:ring-amber-500">
                                <span class="text-sm text-gray-600 font-medium group-hover:text-secondary transition-colors">{{ __($label) }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>

                <div class="flex justify-between pt-4">
                    <button type="button" onclick="switchTab('personal')" class="px-8 py-3 border border-gray-200 text-gray-600 font-bold rounded-2xl hover:bg-gray-50 transition-all">
                        {{ __('Previous') }}
                    </button>
                    <button type="button" onclick="switchTab('financial')" class="px-8 py-3 bg-secondary text-white font-black rounded-2xl hover:bg-opacity-90 transition-all flex items-center gap-2">
                        {{ __('Next Step') }}
                        <i class="ri-arrow-right-line"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- 3. Financial Tab -->
        <div id="tab-financial" class="tab-content animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm space-y-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary/5 flex items-center justify-center text-secondary">
                        <i class="ri-bank-card-line text-xl"></i>
                    </div>
                    <h2 class="text-xl font-black text-secondary">{{ __('Financial & KYC Details') }}</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('PAN Number') }}</label>
                        <input type="text" name="pan_number" value="{{ $profile->pan_number ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Bank Holder Name') }}</label>
                        <input type="text" name="{{ $user->role == 'doctor' ? 'bank_account_holder_name' : 'bank_holder_name' }}" value="{{ $profile->bank_account_holder_name ?? $profile->bank_holder_name ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Bank Name') }}</label>
                        <input type="text" name="bank_name" value="{{ $profile->bank_name ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Account Number') }}</label>
                        <input type="text" name="account_number" value="{{ $profile->account_number ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('IFSC Code') }}</label>
                        <input type="text" name="ifsc_code" value="{{ $profile->ifsc_code ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('Payout Currency') }}</label>
                        @php
                            $selectedCurrency = $profile->payout_currency ?? 'INR';
                            $selectedSymbol = $currencies[$selectedCurrency] ?? '';
                        @endphp
                        <div class="w-full px-4 py-3 bg-[#f3f4f6] rounded-xl border border-gray-200 text-gray-500 font-bold cursor-not-allowed flex items-center">
                            {{ $selectedCurrency }} {{ $selectedSymbol ? '('.$selectedSymbol.')' : '' }}
                            <i class="ri-lock-line ml-autoright ml-auto text-gray-400"></i>
                        </div>
                        <input type="hidden" name="payout_currency" value="{{ $selectedCurrency }}">
                    </div>
                    @if($user->role == 'translator')
                    <div>
                        <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">{{ __('SWIFT Code') }}</label>
                        <input type="text" name="swift_code" value="{{ $profile->swift_code ?? '' }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-secondary focus:border-transparent outline-none">
                    </div>
                    @endif
                </div>

                <div class="flex justify-between pt-4">
                    <button type="button" onclick="switchTab('professional')" class="px-8 py-3 border border-gray-200 text-gray-600 font-bold rounded-2xl hover:bg-gray-50 transition-all">
                        {{ __('Previous') }}
                    </button>
                    <button type="button" onclick="switchTab('documents')" class="px-8 py-3 bg-secondary text-white font-black rounded-2xl hover:bg-opacity-90 transition-all flex items-center gap-2">
                        {{ __('Next Step') }}
                        <i class="ri-arrow-right-line"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- 4. Documents Tab -->
        <div id="tab-documents" class="tab-content animate-in fade-in slide-in-from-bottom-4 duration-500">
            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm space-y-8">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-secondary/5 flex items-center justify-center text-secondary">
                        <i class="ri-file-upload-line text-xl"></i>
                    </div>
                    <h2 class="text-xl font-black text-secondary">{{ __('Identity & Certification Documents') }}</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @php
                        $roleDocs = [
                            'doctor' => [
                                'registration_certificate_path' => 'Registration Certificate',
                                'digital_signature_path' => 'Digital Signature',
                                'pan_upload_path' => 'PAN Card Copy',
                                'cancelled_cheque_path' => 'Cancelled Cheque',
                            ],
                            'practitioner' => [
                                'doc_id_proof' => 'ID Proof (Passport/Aadhar)',
                                'doc_certificates' => 'Educational Certificates',
                                'doc_experience' => 'Experience Certificate',
                                'doc_cover_letter' => 'Cover Letter',
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

                        $currentDocs = $roleDocs[$user->role] ?? [];
                    @endphp

                    @forelse($currentDocs as $field => $label)
                    <div class="p-6 bg-gray-50 rounded-2xl border border-gray-100">
                        <label class="block text-sm font-bold text-secondary mb-3">{{ $label }}</label>
                        <div class="flex flex-col gap-4">
                            @if(!empty($profile->$field))
                            <div class="flex items-center justify-between p-3 bg-white rounded-xl border border-secondary/10">
                                <div class="flex items-center gap-2 text-xs text-green-600 font-bold">
                                    <i class="ri-checkbox-circle-fill text-lg"></i>
                                    {{ __('Uploaded') }}
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ asset('storage/' . $profile->$field) }}" target="_blank" class="p-2 bg-secondary/5 text-secondary rounded-lg hover:bg-secondary/10 transition-all" title="Preview">
                                        <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ asset('storage/' . $profile->$field) }}" download class="p-2 bg-secondary/5 text-secondary rounded-lg hover:bg-secondary/10 transition-all" title="Download">
                                        <i class="ri-download-line"></i>
                                    </a>
                                </div>
                            </div>
                            @endif
                            <input type="file" name="{{ $field }}" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-secondary file:text-white hover:file:bg-opacity-90">
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 py-12 text-center">
                        <i class="ri-information-line text-4xl text-gray-300 mb-4 block"></i>
                        <p class="text-gray-400 font-medium">{{ __('No specific document uploads required for your role.') }}</p>
                    </div>
                    @endforelse
                </div>

                <div class="flex justify-between pt-8">
                    <button type="button" onclick="switchTab('financial')" class="px-8 py-3 border border-gray-200 text-gray-600 font-bold rounded-2xl hover:bg-gray-50 transition-all">
                        {{ __('Previous') }}
                    </button>
                    <button type="submit" class="px-12 py-4 bg-secondary text-white font-black rounded-2xl shadow-xl shadow-secondary/20 hover:bg-opacity-90 transform hover:-translate-y-1 transition-all text-lg">
                        {{ __('Submit Profile for Review') }}
                    </button>
                </div>
            </div>
        </div>
        @endif
    </form>
</div>

<!-- Confirmation Modal -->
<div id="confirmation-modal" class="fixed inset-0 z-[100002] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300 px-4">
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="toggleModal(false)"></div>
    <div class="relative bg-white rounded-[2rem] p-8 md:p-10 max-w-[450px] w-full text-center shadow-2xl transform transition-all duration-300 scale-90">
        <button onclick="toggleModal(false)" class="absolute top-6 right-8 text-gray-300 hover:text-gray-500 transition-colors">
            <i class="ri-close-line text-2xl"></i>
        </button>
        <div class="mb-6">
            <div class="w-16 h-16 bg-[#EEF2EF] rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-checkbox-circle-line text-secondary text-3xl"></i>
            </div>
            <h3 class="text-xl font-bold text-secondary mb-2">{{ __('Submit Profile for Review?') }}</h3>
            <p class="text-gray-500 text-sm leading-relaxed">
                {{ __('Please ensure all your details and documents are accurate. Once submitted, our team will verify your information to unlock all features.') }}
            </p>
        </div>
        <div class="flex gap-4">
            <button onclick="toggleModal(false)" class="flex-1 px-6 py-3 border border-gray-200 text-gray-600 rounded-full font-bold hover:bg-gray-50 transition-all">
                {{ __('Review Again') }}
            </button>
            <button onclick="submitFinalForm()" class="flex-1 px-6 py-3 bg-secondary text-white rounded-full font-bold hover:bg-opacity-90 transition-all">
                {{ __('Confirm & Submit') }}
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        document.getElementById('tab-' + tabId).classList.add('active');
        document.getElementById('tab-btn-' + tabId).classList.add('active');
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function toggleModal(show) {
        const modal = document.getElementById('confirmation-modal');
        const content = modal.querySelector('.relative');
        if (show) {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            setTimeout(() => content.classList.replace('scale-90', 'scale-100'), 10);
        } else {
            content.classList.replace('scale-100', 'scale-90');
            setTimeout(() => modal.classList.add('opacity-0', 'pointer-events-none'), 300);
        }
    }

    function submitFinalForm() {
        const form = document.getElementById('complete-profile-form');
        const submitBtn = document.querySelector('#confirmation-modal button[onclick="submitFinalForm()"]');
        const originalText = submitBtn.innerHTML;
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = `<i class="ri-loader-4-line animate-spin mr-2"></i> {{ __('Submitting...') }}`;
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toggleModal(false);
                if (window.showZayaToast) {
                    showZayaToast(data.message || 'Profile updated successfully!', 'success', 'Profile');
                } else {
                    alert(data.message || 'Profile updated successfully!');
                }
                
                if (data.redirect) {
                    setTimeout(() => window.location.href = data.redirect, 1500);
                }
            } else {
                if (window.showZayaToast) {
                    showZayaToast(data.message || 'Something went wrong.', 'error', 'Error');
                } else {
                    alert(data.message || 'Something went wrong.');
                }
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (window.showZayaToast) {
                showZayaToast('An unexpected error occurred.', 'error', 'Error');
            } else {
                alert('An unexpected error occurred.');
            }
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
    }

    function sendOTP() {
        const emailInput = document.getElementById('email-input');
        const sendBtn = document.getElementById('send-otp-btn');
        const email = emailInput.value;

        if (!email || !email.includes('@')) {
            if (window.showZayaToast) showZayaToast('Please enter a valid email address.', 'error', 'Email');
            else alert('Please enter a valid email address.');
            return;
        }

        sendBtn.disabled = true;
        sendBtn.innerHTML = `<i class="ri-loader-4-line animate-spin"></i>`;

        fetch('{{ route("profile.sendOtp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('otp-group').classList.remove('hidden');
                if (window.showZayaToast) showZayaToast(data.message, 'success', 'OTP Sent');
                else alert(data.message);
            } else {
                if (window.showZayaToast) showZayaToast(data.message, 'error', 'Error');
                else alert(data.message);
            }
        })
        .catch(error => {
            if (window.showZayaToast) showZayaToast('Error sending OTP.', 'error', 'Error');
            else alert('Error sending OTP.');
        })
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.innerHTML = '{{ __("Verify Now") }}';
        });
    }

    function verifyOTP() {
        const email = document.getElementById('email-input').value;
        const otp = document.getElementById('otp-input').value;
        const verifyBtn = document.getElementById('verify-otp-btn');

        if (!otp || otp.length !== 6) {
            if (window.showZayaToast) showZayaToast('Please enter a 6-digit OTP.', 'error', 'Verification');
            else alert('Please enter a 6-digit OTP.');
            return;
        }

        verifyBtn.disabled = true;
        verifyBtn.innerHTML = `<i class="ri-loader-4-line animate-spin"></i>`;

        fetch('{{ route("profile.verifyOtp") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ email: email, otp: otp })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('otp-group').classList.add('hidden');
                document.getElementById('email-verified-badge').classList.remove('hidden');
                document.getElementById('send-otp-btn').classList.add('hidden');
                document.getElementById('email-change-warning').classList.add('hidden');
                if (window.showZayaToast) showZayaToast(data.message, 'success', 'Success');
                else alert(data.message);
            } else {
                if (window.showZayaToast) showZayaToast(data.message, 'error', 'Invalid OTP');
                else alert(data.message);
            }
        })
        .catch(error => {
            if (window.showZayaToast) showZayaToast('Error verifying OTP.', 'error', 'Error');
            else alert('Error verifying OTP.');
        })
        .finally(() => {
            verifyBtn.disabled = false;
            verifyBtn.innerHTML = '{{ __("Verify") }}';
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('complete-profile-form');
        const emailInput = document.getElementById('email-input');
        const originalEmail = emailInput.value;
        const isVerifiedAtStart = !document.getElementById('email-verified-badge').classList.contains('hidden');
        let checkEmailTimeout;

        emailInput.addEventListener('input', function() {
            clearTimeout(checkEmailTimeout);
            const currentEmail = this.value;

            if (currentEmail !== originalEmail) {
                document.getElementById('email-verified-badge').classList.add('hidden');
                document.getElementById('send-otp-btn').classList.remove('hidden');
                document.getElementById('email-change-warning').classList.remove('hidden');

                if (currentEmail.includes('@') && currentEmail.includes('.')) {
                    checkEmailTimeout = setTimeout(() => {
                        fetch('{{ route("profile.checkEmail") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                            },
                            body: JSON.stringify({ email: currentEmail })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                emailInput.classList.add('border-red-500', 'text-red-600');
                                emailInput.classList.remove('border-gray-200');
                                if (window.showZayaToast) showZayaToast('This email is already in use.', 'error', 'Duplicate Email');
                                document.getElementById('send-otp-btn').disabled = true;
                                document.getElementById('send-otp-btn').classList.add('opacity-50', 'cursor-not-allowed');
                            } else {
                                emailInput.classList.remove('border-red-500', 'text-red-600');
                                emailInput.classList.add('border-gray-200');
                                document.getElementById('send-otp-btn').disabled = false;
                                document.getElementById('send-otp-btn').classList.remove('opacity-50', 'cursor-not-allowed');
                            }
                        })
                        .catch(err => console.error('Error checking email:', err));
                    }, 500);
                }
            } else if (isVerifiedAtStart) {
                document.getElementById('email-verified-badge').classList.remove('hidden');
                document.getElementById('send-otp-btn').classList.add('hidden');
                document.getElementById('email-change-warning').classList.add('hidden');
                emailInput.classList.remove('border-red-500', 'text-red-600');
                emailInput.classList.add('border-gray-200');
                document.getElementById('send-otp-btn').disabled = false;
                document.getElementById('send-otp-btn').classList.remove('opacity-50', 'cursor-not-allowed');
            }
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const isVerified = !document.getElementById('email-verified-badge').classList.contains('hidden');
            if (!isVerified) {
                if (window.showZayaToast) showZayaToast('Please verify your email address before submitting.', 'error', 'Verification');
                else alert('Please verify your email address before submitting.');
                return;
            }
            
            toggleModal(true);
        });

        const selectIds = [
            '#specialities-select', 
            '#conditions-select', 
            '#languages-spoken-select',
            '#source-languages-select',
            '#target-languages-select',
            '#country-code-select',
            '#nationality-select',
            '#country-select',
            '#native-language-select'
        ];
        
        selectIds.forEach(selector => {
            if (document.querySelector(selector)) {
                new TomSelect(selector, {
                    plugins: selector.includes('select') && !selector.includes('code') && !selector.includes('native') && !selector.includes('country-select') && !selector.includes('nationality') ? ['remove_button'] : [],
                    create: false,
                    persist: false,
                    placeholder: 'Select...',
                });
            }
        });
    });
</script>
@endpush
