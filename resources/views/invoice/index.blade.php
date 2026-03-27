<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('admiro/assets/images/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('admiro/assets/images/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('admiro/assets/images/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('admiro/assets/images/apple-touch-icon.png') }}" />
    <meta name="apple-mobile-web-app-title" content="Zaya Wellness" />
    <link rel="manifest" href="{{ asset('admiro/assets/images/site.webmanifest') }}">
    
    <title>{{ __('Invoice') }} - {{ $booking->invoice_no ?? $booking->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7f9fa;
            color: #333;
        }

        /* html2canvas fixes */
        #invoice-card * {
            -webkit-font-smoothing: antialiased;
        }

        /* Printing/PDF specific overrides */
        @media print {
            body { background: white; }
            .no-print { display: none; }
            #invoice-card { 
                box-shadow: none !important; 
                margin: 0 !important;
                width: 100% !important;
                max-width: 100% !important;
            }
        }

        /* Ensure centered layout for screen and PDF */
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        #invoice-card {
            margin: 0 auto;
        }
    </style>
</head>

<body class="min-h-screen py-10 flex flex-col items-center">
    @php
        $site_settings = $site_settings ?? [];
        $currencyCode = strtoupper($booking->currency ?? config('app.currency', 'INR'));
        $symbols = config('currencies.symbols', []);
        $currencySymbol = $symbols[$currencyCode] ?? $currencyCode;
    @endphp

    <!-- Top Status Icon & Header -->
    <div class="text-center mb-8 mt-4 no-print">
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
    <div id="invoice-card" class="bg-white rounded-[24px] shadow-[0_8px_40px_-15px_rgba(0,0,0,0.08)] w-full max-w-[560px] p-7 mb-8">

        <!-- Header: Client Info & QR Code -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-4">
                @php
                    $user = $booking->user;
                    $patient = $user->patient ?? null;
                    $clientName = $user->name ?? 'Client';
                    $avatar = $user->profile_photo_url ?: asset('frontend/assets/profile-dummy-img.png');
                @endphp
                <img src="{{ $avatar }}" alt="{{ $clientName }}" crossorigin="anonymous"
                    onerror="this.src='{{ asset('frontend/assets/profile-dummy-img.png') }}'"
                    class="w-[52px] h-[52px] rounded-full object-cover">
                <div>
                    <p class="text-[12px] text-gray-400 mb-0.5">{{ __($site_settings['invoice_client_name'] ?? 'Client Name') }}</p>
                    <p class="text-[15px] font-semibold text-gray-800">{{ $clientName }}</p>
                </div>
            </div>
            <div>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(request()->fullUrl()) }}" crossorigin="anonymous"
                    alt="Invoice QR Code" class="w-[72px] h-[72px] shadow-sm border border-gray-100">
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
                    <p class="text-[14px] font-semibold text-gray-800">{{ $service->title ?? ($service->name ?? 'N/A') }}</p>
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
            <div>
                <span class="text-[13px] text-gray-400 block mb-0.5">{{ __($site_settings['invoice_total_amount'] ?? 'Total Amount') }}</span>
                @if($booking->razorpay_payment_id)
                <span class="text-[10px] text-gray-400 font-medium">Ref: {{ $booking->razorpay_payment_id }}</span>
                @endif
            </div>
            <div class="flex items-center gap-4">
                <span class="bg-[#eef1f6] text-gray-600 text-[11px] font-medium px-3 py-1 rounded-full">{{ ucfirst($booking->status ?? 'Paid') }}</span>
                <span class="text-[22px] font-bold text-gray-800">{{ $currencySymbol }} {{ number_format($booking->total_price, 2) }}</span>
            </div>
        </div>

        <hr class="border-gray-100 mb-6">

        <!-- Practitioners & Options List -->
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-[15px] font-bold text-gray-800">{{ __($site_settings['invoice_practitioner_title'] ?? 'Sessions with Practitioner') }}</h2>
            <div class="flex gap-2">
                <span class="bg-blue-50 text-blue-600 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wide">
                    {{ $booking->mode ?? 'Online' }}
                </span>
                @if($booking->need_translator && $booking->translator)
                <span class="bg-purple-50 text-purple-600 text-[10px] font-bold px-2.5 py-1 rounded-md uppercase tracking-wide">
                    + Translator ({{ $booking->from_language }} - {{ $booking->to_language }})
                </span>
                @endif
            </div>
        </div>

        <div class="flex flex-col gap-5 mb-7 px-1">
            @php
                $practitioner = $booking->practitioner;
                $pName = trim(($practitioner->first_name ?? '') . ' ' . ($practitioner->last_name ?? ''));
                if ($pName === '') $pName = $practitioner->user->name ?? 'Practitioner';
                $pImage = $practitioner->profile_photo_path ? asset('storage/' . $practitioner->profile_photo_path) : asset('frontend/assets/profile-dummy-img.png');
                $pRole = $practitioner->other_modalities[0] ?? 'Specialist';
            @endphp
            <div class="flex items-start justify-between gap-2">
                <div class="flex items-center gap-4">
                    <img src="{{ $pImage }}" alt="{{ $pName }}" crossorigin="anonymous"
                        onerror="this.src='{{ asset('frontend/assets/profile-dummy-img.png') }}'"
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

            @if($booking->need_translator && $booking->translator)
            <div class="flex items-center justify-between border-t border-gray-50 pt-4 mt-2">
                <div class="flex items-center gap-4">
                    @php
                        $translator = $booking->translator;
                        $tName = $translator->user->name ?? 'Translator';
                        $tImage = $translator->profile_photo_path ? asset('storage/' . $translator->profile_photo_path) : asset('frontend/assets/profile-dummy-img.png');
                    @endphp
                    <img src="{{ $tImage }}" alt="{{ $tName }}" crossorigin="anonymous" 
                        onerror="this.src='{{ asset('frontend/assets/profile-dummy-img.png') }}'"
                        class="w-[36px] h-[36px] rounded-full object-cover grayscale opacity-70">                    <div>
                        <p class="text-[13px] font-medium text-gray-700 leading-tight mb-0.5">{{ $tName }}</p>
                        <p class="text-[11px] text-gray-400 uppercase tracking-tighter">{{ __('Assigned Translator') }}</p>
                    </div>
                </div>
                <span class="text-[11px] font-bold text-gray-400 bg-gray-50 px-2 py-1 rounded">
                    {{ $booking->from_language }} &rarr; {{ $booking->to_language }}
                </span>
            </div>
            @endif
        </div>

        <hr class="border-gray-100 mb-6">

        <!-- Footer -->
        <div class="text-center pt-1 mb-2">
            <p class="text-[16px] font-semibold text-gray-800 mb-1.5">{{ __($site_settings['invoice_footer_thanks'] ?? 'Thanks for Booking!') }}</p>
            <p class="text-[11px] text-gray-500 font-medium">{{ __($site_settings['invoice_footer_queries'] ?? 'For more queries') }} &middot; <a href="https://www.zayawellness.com"
                    class="text-blue-500 hover:underline">www.zayawellness.com</a></p>
        </div>
    </div>

    <!-- Book Another Session CTA -->
    <div class="mb-6 no-print mt-6">
        <a href="{{ route('book-session', ['practitioner' => $booking->practitioner->slug]) }}" 
           class="inline-flex items-center gap-2 px-8 py-3 bg-[#1e3a2f] text-white font-semibold text-[15px] rounded-full hover:bg-[#16261f] transition-all shadow-md">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            {{ __('Book Another Session') }}
        </a>
    </div>

    <!-- Bottom Action Buttons -->
    <div class="flex justify-between items-center w-full max-w-[420px] px-2 mb-10 no-print">
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
            <button onclick="shareInvoice()"
                class="px-[26px] py-2.5 bg-[#daf1fe] text-[#0ea5e9] font-medium text-[14px] rounded-full hover:bg-blue-100 transition-colors shadow-sm">
                {{ __($site_settings['invoice_btn_share'] ?? 'Share') }}
            </button>
            <button id="download-btn"
                class="px-[26px] py-2.5 bg-[#1e2024] text-white font-medium text-[14px] rounded-full hover:bg-black transition-colors shadow-sm">
                {{ __($site_settings['invoice_btn_download'] ?? 'Download') }}
            </button>
        </div>
    </div>

    <!-- html2pdf Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        // Share Functionality
        async function shareInvoice() {
            const shareData = {
                title: 'Zaya Wellness Invoice',
                text: 'Check my session invoice from Zaya Wellness: {{ $booking->invoice_no }}',
                url: window.location.href
            };

            try {
                if (navigator.share) {
                    // Abort if a share is already in progress
                    if (shareInvoice.isSharing) return;
                    shareInvoice.isSharing = true;
                    await navigator.share(shareData);
                } else {
                    // Fallback to clipboard
                    await navigator.clipboard.writeText(window.location.href);
                    if (window.showZayaToast) {
                    showZayaToast('Invoice link copied to clipboard!', 'Success', 'Billing');
                } else {
                    alert('Invoice link copied to clipboard!');
                }
                }
            } catch (err) {
                console.error('Error sharing:', err);
            } finally {
                shareInvoice.isSharing = false;
            }
        }
        shareInvoice.isSharing = false;

        // Download Functionality
        document.getElementById('download-btn').addEventListener('click', function() {
            const element = document.getElementById('invoice-card');
            const btn = this;
            const originalText = btn.innerText;
            
            // Show loading state
            btn.innerText = 'Generating...';
            btn.disabled = true;

            const opt = {
                margin: 0.3,
                filename: 'Invoice_{{ $booking->invoice_no }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { 
                    scale: 2, 
                    useCORS: true,
                    logging: false,
                    letterRendering: true,
                    allowTaint: false,
                    scrollX: 0,
                    scrollY: 0,
                    windowWidth: document.documentElement.offsetWidth
                },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            // Generate PDF
            html2pdf().set(opt).from(element).save().then(() => {
                btn.innerText = originalText;
                btn.disabled = false;
            }).catch(err => {
                console.error('PDF Generation Error:', err);
                btn.innerText = originalText;
                btn.disabled = false;
                alert('Could not generate PDF. Please try again or use the Print button.');
            });
        });
    </script>

</body>

</html>
