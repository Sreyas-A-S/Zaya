import './bootstrap';

import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

window.Swiper = Swiper;
window.flatpickr = flatpickr;

function initFlatpickrDate(input, options = {}) {
    if (!input) return null;
    if (input.dataset.noFlatpickr === '1') return null;
    if (input.dataset.flatpickrInitialized === '1') return null;

    input.dataset.flatpickrInitialized = '1';

    // Ensure consistent behavior across browsers by not using native date input UI
    // (Flatpickr expects text-like inputs)
    if (input.type === 'date') input.type = 'text';

    // Prevent our autofill guard from breaking the datepicker UX
    input.dataset.noAutofillGuard = '1';
    input.removeAttribute('readonly');

    const minDate = input.getAttribute('min') || undefined;
    const maxDate = input.getAttribute('max') || undefined;

    return flatpickr(input, {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd-m-Y',
        allowInput: true,
        monthSelectorType: 'dropdown',
        disableMobile: true,
        minDate,
        maxDate,
        ...options,
    });
}

function applyFlatpickrAll(root = document) {
    root.querySelectorAll('input[type="date"], input[data-flatpickr="date"]').forEach((input) => {
        initFlatpickrDate(input);
    });

    // DOB: keep maxDate=today even if markup doesn't set max
    const dobInput = document.getElementById('dob-input');
    const dobInstance = initFlatpickrDate(dobInput, { maxDate: 'today' });

    // Make calendar icon clickable (if present)
    if (dobInput && dobInstance) {
        const icon = dobInput.closest('.date-input-wrapper')?.querySelector('.calendar-icon');
        if (icon && !icon.dataset.flatpickrBound) {
            icon.dataset.flatpickrBound = '1';
            icon.addEventListener('click', () => dobInstance.open());
        }
    }
}

// Flatpickr: global date fields (works even if loaded after DOMContentLoaded)
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => applyFlatpickrAll());
} else {
    applyFlatpickrAll();
}

// Also catch dynamically injected date inputs
new MutationObserver((mutations) => {
    mutations.forEach((mutation) => {
        mutation.addedNodes.forEach((node) => {
            if (node.nodeType !== 1) return;
            if (node.matches?.('input[type=\"date\"], input[data-flatpickr=\"date\"]')) {
                initFlatpickrDate(node);
                return;
            }
            if (node.querySelectorAll) applyFlatpickrAll(node);
        });
    });
}).observe(document.documentElement, { childList: true, subtree: true });

// Global Autofill Guard - Prevents pre-fill on load but allows suggestions on focus
(function() {
    const setupGuard = (el) => {
        if ((el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') && 
            ['text', 'email', 'password', 'tel', 'number'].includes(el.type) &&
            !el.hasAttribute('data-autofill-guarded') &&
            el.getAttribute('data-no-autofill-guard') !== '1') {
            
            // Start as readonly to stop pre-fill
            el.setAttribute('readonly', 'readonly');
            el.setAttribute('data-autofill-guarded', 'true');
            
            const activate = function() {
                if (this.hasAttribute('readonly')) {
                    this.removeAttribute('readonly');
                    // Sometimes browsers need a tiny nudge to show the dropdown
                    if (this.value === '') {
                        this.focus();
                    }
                }
            };
            
            el.addEventListener('focus', activate);
            el.addEventListener('mousedown', activate);
            el.addEventListener('touchstart', activate);
        }
    };

    const applyToAll = () => {
        document.querySelectorAll('input, textarea').forEach(setupGuard);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', applyToAll);
    } else {
        applyToAll();
    }

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            mutation.addedNodes.forEach((node) => {
                if (node.nodeType === 1) {
                    if (node.tagName === 'INPUT' || node.tagName === 'TEXTAREA') {
                        setupGuard(node);
                    } else {
                        node.querySelectorAll('input, textarea').forEach(setupGuard);
                    }
                }
            });
        });
    });

    observer.observe(document.body, { childList: true, subtree: true });
})();
