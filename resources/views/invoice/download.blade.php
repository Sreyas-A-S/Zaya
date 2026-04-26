<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Downloading Invoice') }} - {{ $booking->invoice_no }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .downloading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        #invoice-to-download {
            position: absolute;
            left: -9999px;
            top: 0;
        }
    </style>
</head>

<body class="bg-gray-50">

    <div class="downloading-overlay">
        <div class="mb-6">
            <div class="w-16 h-16 border-4 border-t-[#1e3a2f] border-gray-200 rounded-full animate-spin"></div>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ __('Preparing your invoice...') }}</h1>
        <p class="text-gray-500 mb-8">{{ __('Your download will start automatically.') }}</p>
        
        <div class="text-center">
            <p class="text-sm text-gray-400 mb-4">{{ __('Redirecting you to login in 5 seconds...') }}</p>
            <a href="{{ route('zaya-login') }}" class="px-8 py-3 bg-[#1e3a2f] text-white font-semibold rounded-full hover:bg-[#16261f] transition-all">
                {{ __('Go to Login') }}
            </a>
        </div>
    </div>

    <!-- The actual invoice content, hidden from view but used for PDF generation -->
    <div id="invoice-to-download">
        @include('invoice.index', ['booking' => $booking])
    </div>

    <!-- html2pdf Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        window.addEventListener('load', function() {
            const element = document.querySelector('#invoice-card');
            if (!element) {
                console.error('Invoice card not found');
                return;
            }

            const opt = {
                margin: 0.3,
                filename: 'Invoice_{{ $booking->invoice_no }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { 
                    scale: 2, 
                    useCORS: true,
                    logging: false,
                    letterRendering: true
                },
                jsPDF: { unit: 'in', format: 'letter', orientation: 'portrait' }
            };

            // Generate and save PDF
            html2pdf().set(opt).from(element).save().then(() => {
                // Wait 2 seconds after download starts, then redirect
                setTimeout(() => {
                    window.location.href = "{{ route('zaya-login') }}?success=Payment successful! Your invoice has been downloaded.";
                }, 2000);
            }).catch(err => {
                console.error('PDF Generation Error:', err);
                // Fallback redirect if PDF fails
                setTimeout(() => {
                    window.location.href = "{{ route('zaya-login') }}";
                }, 3000);
            });
        });
    </script>
</body>
</html>
