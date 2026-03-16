<div id="success-popup" class="fixed inset-0 z-[100002] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="hideSuccessPopup()"></div>

    <!-- Modal Content -->
    <div class="relative bg-white rounded-[40px] p-10 md:p-14 max-w-[500px] w-[90%] text-center shadow-[0_20px_50px_rgba(0,0,0,0.1)] transform transition-all duration-300 scale-90">
        <!-- Close Button -->
        <button onclick="hideSuccessPopup()" class="absolute top-6 right-8 text-gray-300 hover:text-gray-500 transition-colors">
            <i class="ri-close-line text-2xl"></i>
        </button>

        <!-- Icon with Sparkles -->
        <div class="relative inline-block mb-10">
            <!-- Top Right Sparkle Cluster -->
            <div class="absolute -top-6 -right-10">
                <!-- Large Star -->
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-[#FABD4D]">
                    <path d="M12 0C12 7 12 12 24 12C12 12 12 17 12 24C12 17 12 12 0 12C12 12 12 7 12 0Z" fill="currentColor" />
                </svg>
                <!-- Medium Star -->
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-[#FABD4D] absolute -top-4 -left-2">
                    <path d="M12 0C12 7 12 12 24 12C12 12 12 17 12 24C12 17 12 12 0 12C12 12 12 7 12 0Z" fill="currentColor" />
                </svg>
                <!-- Small Star -->
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-[#FABD4D] absolute -top-6 -left-6">
                    <path d="M12 0C12 7 12 12 24 12C12 12 12 17 12 24C12 17 12 12 0 12C12 12 12 7 12 0Z" fill="currentColor" />
                </svg>
            </div>

            <!-- Bottom Left Sparkle Cluster -->
            <div class="absolute -bottom-2 -left-12">
                <!-- Large Star -->
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-[#FABD4D] rotate-[-15deg]">
                    <path d="M12 0C12 7 12 12 24 12C12 12 12 17 12 24C12 17 12 12 0 12C12 12 12 7 12 0Z" fill="currentColor" />
                </svg>
                <!-- Small Star -->
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-[#FABD4D] absolute -top-3 -right-2 rotate-[15deg]">
                    <path d="M12 0C12 7 12 12 24 12C12 12 12 17 12 24C12 17 12 12 0 12C12 12 12 7 12 0Z" fill="currentColor" />
                </svg>
            </div>

            <!-- Main Check Circle with Outer Ring -->
            <div class="relative flex items-center justify-center">
                <!-- Outer Ring (Thicker and Closer) -->
                <div class="absolute w-[116px] h-[116px] rounded-full border-[3px] border-[#4DB192]"></div>

                <!-- Inner Gradient Circle with Thick White Border -->
                <div class="w-24 h-24 rounded-full border-[6px] border-white flex items-center justify-center relative z-10 shadow-[0_10px_25px_rgba(0,0,0,0.05)]"
                    style="background: linear-gradient(135deg, #6EF0B9 0%, #4DB192 100%);">
                    <!-- Custom Thick Rounded Checkmark SVG -->
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5 12L10 17L20 7" stroke="white" stroke-width="3.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Text Content -->
        <div class="space-y-4">
            <h3 class="text-[#40916C] font-sans! font-medium text-lg md:text-xl lg:text-2xl">
                Thank you!
            </h3>
            <h2 class="text-[#252525] font-sans! font-bold text-2xl md:text-3xl lg:text-[32px] leading-tight whitespace-nowrap">
                Your Application Submitted!
            </h2>
            <p class="text-[#666666] font-normal text-sm md:text-base lg:text-lg max-w-sm mx-auto">
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