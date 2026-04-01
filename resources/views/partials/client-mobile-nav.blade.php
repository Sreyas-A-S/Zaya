@php
    $user = Auth::user();
    $practitionerRoles = ['practitioner', 'doctor', 'mindfulness_practitioner', 'mindfulness-practitioner', 'yoga_therapist', 'yoga-therapist', 'translator'];
    $isPractitioner = in_array($user->role, $practitionerRoles);
@endphp

<div class="lg:hidden flex space-x-6 overflow-x-auto scrollbar-hide mb-5 pb-2">
    <!-- Dashboard -->
    @if(request()->routeIs('dashboard'))
        <button onclick="if(typeof switchMobileTab === 'function') switchMobileTab('dashboard')" 
            class="leading-none text-base text-secondary font-bold whitespace-nowrap cursor-pointer transition-colors border-b-2 border-secondary pb-1 flex items-center shrink-0">
            {{ __($site_settings['client_panel_sidebar_dashboard'] ?? 'Dashboard') }}
        </button>
    @else
        <a href="{{ route('dashboard') }}" 
            class="leading-none text-base text-[#8F8F8F] font-medium whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
            {{ __($site_settings['client_panel_sidebar_dashboard'] ?? 'Dashboard') }}
        </a>
    @endif

    <!-- Profile -->
    <a href="{{ route('profile') }}" 
        class="leading-none text-base {{ request()->routeIs('profile') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium' }} whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        <img src="{{ $user->profile_pic ? (str_starts_with($user->profile_pic, 'http') ? $user->profile_pic : asset('storage/' . $user->profile_pic)) : asset('frontend/assets/profile-dummy-img.png') }}" class="w-5 h-5 rounded-full mr-2 object-cover">
        {{ __('Profile') }}
    </a>

    <!-- Health Journey -->
    @if(in_array($user->role, ['client', 'patient']))
    <a href="{{ route('health-journey.index') }}" 
        class="leading-none text-base {{ request()->routeIs('health-journey.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium' }} whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        {{ __($site_settings['client_panel_sidebar_health_journey'] ?? 'Health Journey') }}
    </a>
    @endif

    <!-- Consultation -->
    @if(in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist', 'translator']))
    <a href="{{ route('consultations.index') }}"
        class="leading-none text-base {{ request()->routeIs('consultations.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium' }} whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        {{ __($site_settings['client_panel_sidebar_consultation'] ?? 'Consultation') }}
    </a>
    @endif

    <!-- Bookings -->
    @if(in_array($user->role, ['client', 'patient']))
    <a href="{{ route('bookings.index') }}" 
        class="leading-none text-base {{ request()->routeIs('bookings.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium' }} whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        {{ __($site_settings['client_panel_sidebar_bookings'] ?? 'Bookings') }}
    </a>
    @endif

    <!-- Conference History -->
    @if(in_array($user->role, ['client', 'patient']))
    <a href="{{ route('conferences.index') }}" 
        class="leading-none text-base {{ request()->routeIs('conferences.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium' }} whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        {{ __('My Conferences') }}
    </a>
    @else
    <a href="{{ route('conferences.index') }}" 
        class="leading-none text-base {{ request()->routeIs('conferences.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium' }} whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        {{ __($site_settings['client_panel_sidebar_conference_history'] ?? 'Conferences') }}
    </a>
    @endif

    <!-- Transaction Vault -->
    @if(request()->routeIs('dashboard'))
        <button onclick="if(typeof switchMobileTab === 'function') switchMobileTab('transactions')" 
            class="leading-none text-base text-[#8F8F8F] font-medium whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
            {{ __($site_settings['client_panel_sidebar_transaction_vault'] ?? 'Transaction Vault') }}
        </button>
    @else
        <a href="{{ route('transactions.index') }}" 
            class="leading-none text-base {{ request()->routeIs('transactions.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium' }} whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
            {{ __($site_settings['client_panel_sidebar_transaction_vault'] ?? 'Transaction Vault') }}
        </a>
    @endif

    <!-- Reviews -->
    <a href="{{ route('reviews.index') }}" 
        class="leading-none text-base {{ request()->routeIs('reviews.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium' }} whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        {{ __('Reviews') }}
    </a>

    @if($isPractitioner)
        <!-- Time Slots -->
        <a href="{{ route('time-slots.index') }}" 
            class="leading-none text-base {{ request()->routeIs('time-slots.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium' }} whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
            {{ __($site_settings['client_panel_sidebar_time_slots'] ?? 'Time Slots') }}
        </a>

        <!-- My Services -->
        <a href="{{ route('my-services.index') }}" 
            class="leading-none text-base {{ request()->routeIs('my-services.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium' }} whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
            {{ __('My Services') }}
        </a>
    @endif

    <!-- Logout -->
    <a href="javascript:void(0)" 
        onclick="openLogoutModal()"
        class="leading-none text-base text-red-500 font-bold whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        {{ __($site_settings['client_panel_sidebar_logout'] ?? 'Logout') }}
    </a>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
