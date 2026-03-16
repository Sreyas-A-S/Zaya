<div id="success-popup" class="fixed inset-0 z-[100002] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="hideSuccessPopup()"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-[40px] p-10 md:p-16 max-w-[550px] w-[90%] text-center shadow-[0_20px_50px_rgba(0,0,0,0.15)] transform transition-all duration-300 scale-90">
        <!-- Close Button -->
        <button onclick="hideSuccessPopup()" class="absolute top-6 right-8 text-gray-400 hover:text-gray-600 transition-colors">
            <i class="ri-close-line text-3xl"></i>
        </button>

        <!-- Icon with Sparkles -->
        <div class="relative inline-block mb-10">
            <!-- Sparkles -->
            <i class="ri-sparkle-fill absolute -top-2 -right-6 text-[#FABD4D] text-3xl"></i>
            <i class="ri-sparkle-fill absolute top-12 -left-10 text-[#FABD4D] text-2xl"></i>

            <!-- Main Check Circle -->
            <div class="w-32 h-32 rounded-full border-[6px] border-white bg-[#4DB286] flex items-center justify-center shadow-[0_15px_35px_rgba(77,178,134,0.25)]">
                <i class="ri-check-line text-white text-7xl font-bold"></i>
            </div>
        </div>

        <!-- Text Content -->
        <div class="space-y-6">
            <h3 class="text-[#4DB286] font-sans! font-semibold text-3xl md:text-4xl tracking-tight">
                Thank you!
            </h3>
            <h2 class="text-[#2D3748] font-sans! font-bold text-2xl md:text-3xl leading-tight">
                Your Application Submitted!
            </h2>
            <p class="text-[#A0AEC0] font-normal text-lg md:text-xl leading-relaxed mt-4">
                We will get back to you. Stay connect with us!
            </p>
        </div>
    </div>
</div>

<script>
    function showSuccessPopup() {
        const popup = document.getElementById('success-popup');
        const content = popup.querySelector('.relative.bg-white');

        popup.classList.remove('opacity-0', 'pointer-events-none');
        popup.classList.add('opacity-100');

        setTimeout(() => {
            content.classList.remove('scale-90');
            content.classList.add('scale-100');
        }, 10);
    }

    function hideSuccessPopup() {
        const popup = document.getElementById('success-popup');
        const content = popup.querySelector('.relative.bg-white');

        content.classList.remove('scale-100');
        content.classList.add('scale-90');

        setTimeout(() => {
            popup.classList.add('opacity-0', 'pointer-events-none');
            popup.classList.remove('opacity-100');
        }, 300);
    }
</script>