<div id="zaya-toast" class="fixed top-28 right-8 z-[100001] opacity-0 pointer-events-none transition-all duration-300 translate-y-[-20px]">
    <div id="toast-container" class="bg-[#A8D7C2]/95 backdrop-blur-sm border border-[#95C3AE] rounded-[15px] px-6 py-3 flex items-center gap-4 shadow-[0_12px_40px_rgba(0,0,0,0.12)] min-w-[450px] max-w-[95vw]">
        <!-- Icon Wrapper -->
        <div id="toast-icon-bg" class="shrink-0 w-10 h-10 rounded-full border-[3px] border-white bg-[#4ADE80]/80 flex items-center justify-center">
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
    function showZayaToast(message, typeOrTitle = 'success', title = 'demo.zayawellness.com says') {
        const toast = document.getElementById('zaya-toast');
        const container = document.getElementById('toast-container');
        const iconBg = document.getElementById('toast-icon-bg');
        const icon = document.getElementById('toast-icon');
        const titleEl = document.getElementById('toast-title');
        const messageEl = document.getElementById('toast-message');

        if (!toast || !messageEl) return;

        let type = 'success';
        const validTypes = ['success', 'error', 'warning'];
        
        if (validTypes.includes(typeOrTitle)) {
            type = typeOrTitle;
        } else if (typeOrTitle !== 'success') {
            // If it's not a standard type and not default 'success', treat it as a title
            title = typeOrTitle;
        }

        titleEl.textContent = title;
        messageEl.textContent = message;

        // Reset and set styles based on type
        if (type === 'error') {
            container.className = 'bg-[#FCA5A5]/95 backdrop-blur-sm border border-[#F87171] rounded-[15px] px-6 py-3 flex items-center gap-4 shadow-[0_12px_40px_rgba(0,0,0,0.12)] min-w-[450px] max-w-[95vw]';
            iconBg.className = 'shrink-0 w-10 h-10 rounded-full border-[3px] border-white bg-[#EF4444]/80 flex items-center justify-center';
            icon.className = 'ri-error-warning-line text-white text-xl font-bold';
        } else if (type === 'warning') {
            container.className = 'bg-[#FDE68A]/95 backdrop-blur-sm border border-[#FBBF24] rounded-[15px] px-6 py-3 flex items-center gap-4 shadow-[0_12px_40px_rgba(0,0,0,0.12)] min-w-[450px] max-w-[95vw]';
            iconBg.className = 'shrink-0 w-10 h-10 rounded-full border-[3px] border-white bg-[#F59E0B]/80 flex items-center justify-center';
            icon.className = 'ri-alert-line text-white text-xl font-bold';
        } else { // success
            container.className = 'bg-[#A8D7C2]/95 backdrop-blur-sm border border-[#95C3AE] rounded-[15px] px-6 py-3 flex items-center gap-4 shadow-[0_12px_40px_rgba(0,0,0,0.12)] min-w-[450px] max-w-[95vw]';
            iconBg.className = 'shrink-0 w-10 h-10 rounded-full border-[3px] border-white bg-[#4ADE80]/80 flex items-center justify-center';
            icon.className = 'ri-check-line text-white text-xl font-bold';
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
