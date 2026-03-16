<div id="zaya-toast" class="fixed top-8 left-1/2 -translate-x-1/2 z-[9999] opacity-0 pointer-events-none transition-all duration-300 translate-y-[-20px]">
    <div class="bg-[#A8D7C2]/90 backdrop-blur-sm border border-[#95C3AE] rounded-[14px] px-5 py-4 flex items-center gap-4 shadow-[0_8px_30px_rgb(0,0,0,0.12)] min-w-[320px] max-w-[90vw]">
        <!-- Icon Wrapper -->
        <div class="shrink-0 w-12 h-12 rounded-full border-[3px] border-white bg-[#4ADE80]/80 flex items-center justify-center">
            <i class="ri-check-line text-white text-2xl font-bold"></i>
        </div>

        <!-- Content -->
        <div class="flex-1 pr-4">
            <h4 id="toast-title" class="text-[#2D3748] font-sans! font-semibold text-sm md:text-base leading-tight">
                demo.zayawellness.com says
            </h4>
            <p id="toast-message" class="text-[#4A5568] font-normal text-xs md:text-sm mt-0.5">
                Thank you for subscribing our newsletter!
            </p>
        </div>

        <!-- Close Button -->
        <button onclick="hideZayaToast()" class="shrink-0 w-8 h-8 rounded-full bg-white flex items-center justify-center text-gray-400 hover:text-gray-600 transition-colors">
            <i class="ri-close-line text-xl"></i>
        </button>
    </div>
</div>

<script>
    function showZayaToast(message, title = 'demo.zayawellness.com says') {
        const toast = document.getElementById('zaya-toast');
        const titleEl = document.getElementById('toast-title');
        const messageEl = document.getElementById('toast-message');

        if (!toast || !messageEl) return;

        titleEl.textContent = title;
        messageEl.textContent = message;

        // Show toast
        toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-[-20px]');
        toast.classList.add('opacity-100', 'translate-y-0');

        // Auto hide after 5 seconds
        if (window.zayaToastTimeout) clearTimeout(window.zayaToastTimeout);
        window.zayaToastTimeout = setTimeout(hideZayaToast, 5000);
    }

    function hideZayaToast() {
        const toast = document.getElementById('zaya-toast');
        if (!toast) return;

        toast.classList.add('opacity-0', 'pointer-events-none', 'translate-y-[-20px]');
        toast.classList.remove('opacity-100', 'translate-y-0');
    }
</script>