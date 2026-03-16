<div id="zaya-toast" class="fixed top-28 right-8 z-[100001] opacity-0 pointer-events-none transition-all duration-300 translate-y-[-20px]">
    <div class="bg-[#A8D7C2]/90 backdrop-blur-sm border border-[#95C3AE] rounded-[18px] px-8 py-5 flex items-center gap-6 shadow-[0_12px_40px_rgb(0,0,0,0.15)] min-w-[420px] max-w-[95vw]">
        <!-- Icon Wrapper -->
        <div class="shrink-0 w-16 h-16 rounded-full border-[4px] border-white bg-[#4ADE80]/80 flex items-center justify-center">
            <i class="ri-check-line text-white text-3xl font-bold"></i>
        </div>

        <!-- Content -->
        <div class="flex-1 pr-6 whitespace-nowrap">
            <h4 id="toast-title" class="text-[#2D3748] font-sans! font-bold text-lg md:text-xl leading-tight">
                demo.zayawellness.com says
            </h4>
            <p id="toast-message" class="text-[#4A5568] font-medium text-sm md:text-base mt-1">
                Thank you for subscribing our newsletter!
            </p>
        </div>

        <!-- Close Button -->
        <button onclick="hideZayaToast()" class="shrink-0 w-10 h-10 rounded-full bg-white flex items-center justify-center text-gray-400 hover:text-gray-600 shadow-sm transition-colors">
            <i class="ri-close-line text-2xl"></i>
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