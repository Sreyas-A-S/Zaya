<div id="zaya-toast" class="fixed top-6 left-1/2 -translate-x-1/2 z-[100001] opacity-0 pointer-events-none transition-all duration-300 translate-y-[-20px]">
    <div id="toast-container" class="bg-[#A8D7C2]/95 backdrop-blur-sm border border-[#95C3AE] rounded-[15px] px-6 py-3 flex items-center gap-4 shadow-[0_12px_40px_rgba(0,0,0,0.12)] min-w-[450px] max-w-[95vw]">
        <!-- Icon Wrapper -->
        <div id="toast-icon-wrapper" class="shrink-0 w-10 h-10 rounded-full border-[3px] border-white bg-[#4ADE80]/80 flex items-center justify-center">
            <i id="toast-icon" class="ri-check-line text-white text-xl font-bold"></i>
        </div>

        <!-- Content -->
        <div class="flex-1 whitespace-nowrap overflow-hidden">
            <h4 id="toast-title" class="text-[#2D3748] font-sans! font-bold text-sm leading-tight">
                demo.zayawellness.com says
            </h4>
            <p id="toast-message" class="text-[#4A5568] font-medium text-xs mt-0.5">
                Thank you for subscribing our newsletter!
            </p>
        </div>

        <!-- Close Button -->
        <button onclick="hideZayaToast()" class="shrink-0 w-8 h-8 rounded-full bg-white/50 flex items-center justify-center text-gray-500 hover:text-gray-700 shadow-sm transition-colors">
            <i class="ri-close-line text-xl"></i>
        </button>
    </div>
</div>

<script>
    function showZayaToast(message, type = 'success', title = 'demo.zayawellness.com says') {
        const toast = document.getElementById('zaya-toast');
        const container = document.getElementById('toast-container');
        const iconWrapper = document.getElementById('toast-icon-wrapper');
        const icon = document.getElementById('toast-icon');
        const titleEl = document.getElementById('toast-title');
        const messageEl = document.getElementById('toast-message');

        if (!toast || !messageEl) return;

        titleEl.textContent = title;
        messageEl.textContent = message;

        // Reset classes
        container.className = 'backdrop-blur-sm border rounded-[15px] px-6 py-3 flex items-center gap-4 shadow-[0_12px_40px_rgba(0,0,0,0.12)] min-w-[450px] max-w-[95vw]';
        iconWrapper.className = 'shrink-0 w-10 h-10 rounded-full border-[3px] border-white flex items-center justify-center';
        icon.className = 'text-white text-xl font-bold';

        if (type === 'error') {
            container.classList.add('bg-red-100/95', 'border-red-200');
            iconWrapper.classList.add('bg-red-500/80');
            icon.classList.add('ri-error-warning-line');
        } else {
            container.classList.add('bg-[#A8D7C2]/95', 'border-[#95C3AE]');
            iconWrapper.classList.add('bg-[#4ADE80]/80');
            icon.classList.add('ri-check-line');
        }

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
