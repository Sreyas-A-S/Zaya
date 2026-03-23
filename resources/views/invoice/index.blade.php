<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Invoice') }} - {{ $booking->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f9fa;
            color: #333;
        }

        .qr-code {
            display: inline-block;
            width: 44px;
            height: 44px;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 24 24' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 3H9V9H3V3ZM5 5V7H7V5H5ZM15 3H21V9H15V3ZM17 5V7H19V5H17ZM3 15H9V21H3V15ZM5 17V19H7V17H5ZM21 21H11V15H21V21ZM13 17H19V19H13V17ZM11 11H13V13H11V11ZM13 9H15V11H13V9ZM9 13H11V15H9V13ZM15 13H17V15H15V13ZM17 11H21V13H17V11ZM9 11H7V13H9V11Z' fill='%231f2937'/%3E%3C/svg%3E");
            background-size: contain;
            background-repeat: no-repeat;
        }
    </style>
</head>

<body class="min-h-screen py-10 flex flex-col items-center">

    <!-- Top Status Icon & Header -->
    <div class="text-center mb-8 mt-4">
        <div class="w-[72px] h-[72px] rounded-full bg-green-100 flex items-center justify-center mx-auto mb-5">
            <div class="w-12 h-12 rounded-full bg-[#52e28b] flex items-center justify-center shadow-sm">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>
        <h1 class="text-[20px] text-gray-800 font-semibold mb-2">{{ __($site_settings['invoice_main_title'] ?? 'Your Session has been Booked!') }}</h1>
        <p class="text-[13px] text-gray-500 max-w-[260px] mx-auto leading-relaxed">
            {{ __($site_settings['invoice_subtitle'] ?? 'Please check your email for confirmation and further instruction.') }}
        </p>
    </div>

    <!-- Main Receipt Card -->
    <div class="bg-white rounded-[24px] shadow-[0_8px_40px_-15px_rgba(0,0,0,0.08)] w-full max-w-[560px] p-7 mb-8">

        <!-- Header: Client Info & QR Code -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-4">
                @php
                    $user = $booking->user;
                    $patient = $user->patient ?? null;
                    $avatar = $user->profile_photo_url ?? 'https://i.pravatar.cc/150?u=' . $user->id;
                @endphp
                <img src="{{ $avatar }}" alt="{{ $user->name }}"
                    class="w-[52px] h-[52px] rounded-full object-cover">
                <div>
                    <p class="text-[12px] text-gray-400 mb-0.5">{{ __($site_settings['invoice_client_name'] ?? 'Client Name') }}</p>
                    <p class="text-[15px] font-semibold text-gray-800">{{ $user->name }}</p>
                </div>
            </div>
            <div>
                <div class="qr-code"></div>
            </div>
        </div>

        <hr class="border-gray-100 mb-5">

        <!-- Client Details Container -->
        <div class="flex justify-between mb-5 px-1 text-center md:text-left">
            <div class="w-full md:w-1/3 mb-4 md:mb-0">
                <p class="text-[12px] text-gray-400 mb-1">{{ __($site_settings['invoice_client_id'] ?? 'Client ID') }}</p>
                <p class="text-[14px] font-semibold text-gray-800">Z-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="w-full md:w-1/3 mb-4 md:mb-0">
                <p class="text-[12px] text-gray-400 mb-1">{{ __($site_settings['invoice_client_dob'] ?? 'DOB') }}</p>
                <p class="text-[14px] font-semibold text-gray-800">{{ $patient && $patient->dob ? $patient->dob->format('M d, Y') : 'N/A' }}</p>
            </div>
            <div class="w-full md:w-1/3 text-right">
                <p class="text-[12px] text-gray-400 mb-1">{{ __($site_settings['invoice_client_location'] ?? 'Location') }}</p>
                <p class="text-[14px] font-semibold text-gray-800">{{ $patient ? $patient->city_state : 'N/A' }}</p>
            </div>
        </div>

        <hr class="border-gray-100 mb-6">

        <!-- Sessions List -->
        <h2 class="text-[15px] font-bold text-gray-800 mb-4">{{ __($site_settings['invoice_sessions_title'] ?? 'Sessions') }}</h2>

        <div class="flex flex-col gap-3 mb-6">
            @php
                $serviceIds = is_array($booking->service_ids) ? $booking->service_ids : [];
                $services = \App\Models\Service::whereIn('id', $serviceIds)->get();
            @endphp
            
            @forelse($services as $service)
            <div class="bg-[#f5f6f8] rounded-2xl p-4 flex justify-between items-center text-center md:text-left">
                <div class="w-1/3">
                    <p class="text-[12px] text-gray-400 mb-1">{{ __($site_settings['invoice_service_col'] ?? 'Service') }}</p>
                    <p class="text-[14px] font-semibold text-gray-800">{{ $service->name }}</p>
                </div>
                <div class="w-1/3">
                    <p class="text-[12px] text-gray-400 mb-1">{{ __($site_settings['invoice_date_col'] ?? 'Date') }}</p>
                    <p class="text-[14px] font-semibold text-gray-800">{{ $booking->booking_date ? $booking->booking_date->format('M d, Y') : 'N/A' }}</p>
                </div>
                <div class="w-1/3 text-right">
                    <p class="text-[12px] text-gray-400 mb-1">{{ __($site_settings['invoice_time_col'] ?? 'Time') }}</p>
                    <p class="text-[14px] font-semibold text-gray-800">{{ $booking->booking_time ?? 'N/A' }}</p>
                </div>
            </div>
            @empty
            <p class="text-sm text-gray-500 italic">{{ __($site_settings['invoice_no_services'] ?? 'No services listed.') }}</p>
            @endforelse
        </div>

        <hr class="border-gray-100 mb-6">

        <!-- Total Amount -->
        <div class="flex justify-between items-center mb-6 px-1">
            <span class="text-[13px] text-gray-400">{{ __($site_settings['invoice_total_amount'] ?? 'Total Amount') }}</span>
            <div class="flex items-center gap-4">
                <span class="bg-[#eef1f6] text-gray-600 text-[11px] font-medium px-3 py-1 rounded-full">{{ ucfirst($booking->status ?? 'Paid') }}</span>
                <span class="text-[22px] font-bold text-gray-800">€ {{ number_format($booking->total_price, 2) }}</span>
            </div>
        </div>

        <hr class="border-gray-100 mb-6">

        <!-- Practitioners List -->
        <h2 class="text-[15px] font-bold text-gray-800 mb-5">{{ __($site_settings['invoice_practitioner_title'] ?? 'Sessions with Practitioner') }}</h2>

        <div class="flex flex-col gap-5 mb-7 px-1">
            @php
                $practitioner = $booking->practitioner;
                $pName = trim(($practitioner->first_name ?? '') . ' ' . ($practitioner->last_name ?? ''));
                if ($pName === '') $pName = $practitioner->user->name ?? 'Practitioner';
                $pImage = $practitioner->profile_photo_path ? asset('storage/' . $practitioner->profile_photo_path) : 'https://i.pravatar.cc/150?u=' . $practitioner->id;
                $pRole = $practitioner->other_modalities[0] ?? 'Specialist';
            @endphp
            <div class="flex items-start justify-between gap-2">
                <div class="flex items-center gap-4">
                    <img src="{{ $pImage }}" alt="{{ $pName }}"
                        class="w-[42px] h-[42px] rounded-full object-cover">
                    <div class="flex flex-col justify-center">
                        <p class="text-[14px] font-semibold text-gray-800 leading-tight mb-0.5">{{ $pName }}</p>
                        <p class="text-[12px] text-gray-400">{{ $pRole }}</p>
                    </div>
                </div>
                <div class="flex items-start gap-1.5 max-w-[160px]">
                    <svg class="w-3.5 h-3.5 text-gray-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <p class="text-[10px] text-gray-500 leading-relaxed font-medium">{{ $practitioner->city_state ?? 'Location not set' }}</p>
                </div>
            </div>
        </div>

        <hr class="border-gray-100 mb-6">

        <!-- Footer -->
        <div class="text-center pt-1 mb-2">
            <p class="text-[16px] font-semibold text-gray-800 mb-1.5">{{ __($site_settings['invoice_footer_thanks'] ?? 'Thanks for Booking!') }}</p>
            <p class="text-[11px] text-gray-500 font-medium">{{ __($site_settings['invoice_footer_queries'] ?? 'For more queries') }} &middot; <a href="https://www.zayawellness.com"
                    class="text-blue-500 hover:underline">www.zayawellness.com</a></p>
        </div>
    </div>

    <!-- Bottom Action Buttons -->
    <div class="flex justify-between items-center w-full max-w-[420px] px-2 mb-10">
        <!-- Print Button -->
        <button onclick="window.print()"
            class="w-10 h-10 rounded-full border border-red-200 bg-white flex items-center justify-center text-red-400 hover:bg-red-50 transition-colors shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                </path>
            </svg>
        </button>

        <!-- Right Buttons -->
        <div class="flex gap-3">
            <button
                class="px-[26px] py-2.5 bg-[#daf1fe] text-[#0ea5e9] font-medium text-[14px] rounded-full hover:bg-blue-100 transition-colors shadow-sm">
                {{ __($site_settings['invoice_btn_share'] ?? 'Share') }}
            </button>
            <button
                class="px-[26px] py-2.5 bg-[#1e2024] text-white font-medium text-[14px] rounded-full hover:bg-black transition-colors shadow-sm">
                {{ __($site_settings['invoice_btn_download'] ?? 'Download') }}
            </button>
        </div>
    </div>

</body>

</html>
