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
            background: rgba(255, 255, 255, 0.95);
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
            width: 800px; /* Consistent width for PDF generation */
        }
    </style>
</head>

<body class="bg-gray-50">

    @include('partials.frontend.success-popup')

    <div class="downloading-overlay">
        <div id="loader-section" class="flex flex-col items-center">
            <div class="mb-6">
                <div class="w-16 h-16 border-4 border-t-[#1e3a2f] border-gray-200 rounded-full animate-spin"></div>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ __('Preparing your invoice...') }}</h1>
            <p class="text-gray-500 mb-8">{{ __('Your download will start automatically.') }}</p>
        </div>
        
        <div id="success-section" class="hidden text-center animate-[fadeIn_0.5s_ease-out]">
            <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm">
                <i class="ri-checkbox-circle-fill text-5xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ __('Payment Successful!') }}</h1>
            <p class="text-gray-600 mb-8 max-w-md">{{ __('Thank you for your payment. Your invoice has been generated and should have started downloading.') }}</p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <button onclick="downloadInvoiceManually()" class="px-8 py-3 bg-white border border-[#1e3a2f] text-[#1e3a2f] font-semibold rounded-full hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                    <i class="ri-download-2-line"></i> {{ __('Download Again') }}
                </button>
                <a href="{{ route('zaya-login') }}?success=Payment successful! You can now login to your account." class="px-8 py-3 bg-[#1e3a2f] text-white font-semibold rounded-full hover:bg-[#16261f] transition-all flex items-center justify-center">
                    {{ __('Continue to Login') }}
                </a>
            </div>
        </div>
    </div>

    <!-- The actual invoice content, hidden from view but used for PDF generation -->
    <div id="invoice-to-download">
        @include('invoice.index', ['booking' => $booking])
    </div>

    <!-- html2pdf Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
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

        function downloadInvoiceManually() {
            const element = document.querySelector('#invoice-card');
            if (!element) return;
            html2pdf().set(opt).from(element).save();
        }

        window.addEventListener('load', function() {
            // Show the success popup if the window object has the function
            if (window.showSuccessPopup) {
                window.showSuccessPopup();
            }

            const element = document.querySelector('#invoice-card');
            if (!element) {
                console.error('Invoice card not found');
                document.getElementById('loader-section').classList.add('hidden');
                document.getElementById('success-section').classList.remove('hidden');
                return;
            }

            // Generate and save PDF
            html2pdf().set(opt).from(element).save().then(() => {
                // Change UI to success after download starts
                setTimeout(() => {
                    document.getElementById('loader-section').classList.add('hidden');
                    document.getElementById('success-section').classList.remove('hidden');
                }, 1000);

                // Auto redirect after 8 seconds of showing success
                setTimeout(() => {
                    window.location.href = "{{ route('zaya-login') }}?success=Payment successful! Welcome to Zaya Wellness.";
                }, 8000);
            }).catch(err => {
                console.error('PDF Generation Error:', err);
                document.getElementById('loader-section').classList.add('hidden');
                document.getElementById('success-section').classList.remove('hidden');
            });
        });
    </script>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>
