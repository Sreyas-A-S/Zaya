import './bootstrap';

import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

window.Swiper = Swiper;

// Global Autofill Guard - Prevents pre-fill on load but allows suggestions on focus
(function() {
    const setupGuard = (el) => {
        if ((el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') && 
            ['text', 'email', 'password', 'tel', 'number'].includes(el.type) &&
            !el.hasAttribute('data-autofill-guarded')) {
            
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