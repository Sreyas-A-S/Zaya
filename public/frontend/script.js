document.addEventListener('DOMContentLoaded', () => {
    const SwiperLib = window.Swiper;
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const header = document.querySelector('header');

    // Toggle Mobile Menu
    mobileMenuBtn.addEventListener('click', () => {
        // Toggle animation classes
        if (mobileMenu.classList.contains('max-h-0')) {
            // Open
            mobileMenu.classList.remove('max-h-0', 'opacity-0', 'invisible', '-translate-y-4');
            mobileMenu.classList.add('max-h-[80vh]', 'opacity-100', 'visible', 'translate-y-0');

            // Wait for transition to finish before allowing scroll
            setTimeout(() => {
                mobileMenu.classList.remove('overflow-hidden');
                mobileMenu.classList.add('overflow-y-auto');
            }, 300);
        } else {
            // Close
            mobileMenu.classList.remove('overflow-y-auto');
            mobileMenu.classList.add('overflow-hidden');

            mobileMenu.classList.add('max-h-0', 'opacity-0', 'invisible', '-translate-y-4');
            mobileMenu.classList.remove('max-h-[80vh]', 'opacity-100', 'visible', 'translate-y-0');
        }
    });

    // Close mobile menu when clicking a link
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.remove('overflow-y-auto');
            mobileMenu.classList.add('overflow-hidden');

            mobileMenu.classList.add('max-h-0', 'opacity-0', 'invisible', '-translate-y-4');
            mobileMenu.classList.remove('max-h-[80vh]', 'opacity-100', 'visible', 'translate-y-0');
        });
    });

    // Sticky Header Effect
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('glass-nav');
            // header.classList.remove('bg-white/60');
        } else {
            header.classList.remove('glass-nav');
            // header.classList.add('bg-white/60');
        }
    });

    // Simple fade-in animation for elements on scroll
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('opacity-100', 'translate-y-0');
                entry.target.classList.remove('opacity-0', 'translate-y-10');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);

    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        el.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-10');
        observer.observe(el);
    });

    if (SwiperLib) {
        // Hero Background Image Slider
        if (document.querySelector('.heroSlider')) {
            new SwiperLib('.heroSlider', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: true,
                speed: 1000,
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                },
            });
        }

        if (document.querySelector('.practitioner-slider')) {
            new SwiperLib('.practitioner-slider', {
                slidesPerView: 1.5,
                spaceBetween: 20,
                loop: true,
                centeredSlides: true,
                autoplay: {
                    delay: 3500,
                    pauseOnMouseEnter: true,
                    pauseOnFocus: true,
                },
                navigation: {
                    nextEl: '.next-practitioner',
                    prevEl: '.prev-practitioner',
                },
                breakpoints: {
                    512: {
                        slidesPerView: 2.3,
                        centeredSlides: true,
                    },
                    768: {
                        slidesPerView: 3,
                        centeredSlides: false,
                    },
                    1152: {
                        slidesPerView: 4,
                        centeredSlides: false,
                    },
                    1440: {
                        slidesPerView: 4.4,
                        spaceBetween: 40,
                        centeredSlides: false,
                    },
                    1920: {
                        slidesPerView: 5,
                        spaceBetween: 80,
                        centeredSlides: false,
                    },
                },
            });
        }

        // Testimonial Slider
        if (document.querySelector('.testimonial-slider')) {
            new SwiperLib('.testimonial-slider', {
                slidesPerView: '1', // Fluid width to match design
                spaceBetween: 40,
                centeredSlides: true,
                loop: true,
                speed: 800,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                allowTouchMove: true,
                bgWhite: false,
                navigation: {
                    nextEl: '.next-testimonial',
                    prevEl: '.prev-testimonial',
                },
                breakpoints: {
                    640: {
                        slidesPerView: '2',
                        spaceBetween: 50,
                        centeredSlides: true,
                    },
                    768: {
                        slidesPerView: '3',
                        spaceBetween: 50,
                        centeredSlides: true,
                    },
                    1024: {
                        slidesPerView: '4',
                        spaceBetween: 50,
                        centeredSlides: false,
                    }
                }
            });
        }

        // Service Detail Image Slider
        if (document.querySelector('.serviceImageSwiper')) {
            new SwiperLib('.serviceImageSwiper', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: true,
                speed: 600,
                grabCursor: true,
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
            });
        }
    }



});

function shareService() {
    if (navigator.share) {
        navigator.share({
            title: document.title || 'ZAYA Wellness',
            text: 'Check out this wellness service at ZAYA Wellness',
            url: window.location.href
        });
    } else {
        // Fallback: Copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link copied to clipboard!');
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    // Condition section toggle logic
    const actionBtn = document.getElementById('condition-action-btn');
    const selectedTagsContainer = document.getElementById('condition-selected-tags');
    const availableTagsContainer = document.getElementById('condition-available-tags');
    const availableOptions = document.querySelectorAll('.condition-tag-option');

    if (actionBtn) {
        // Classes for buttons
        const classAmber = ['bg-[#FFE5B4]', 'hover:bg-[#F5D0A9]', 'text-[#594B4B]', 'border-transparent'];
        const classCancel = ['bg-white', 'border-[#D0D0D0]', 'text-gray-600', 'hover:bg-gray-50'];

        let mode = 'view'; // 'view' or 'edit'
        let savedTags = ['Identifying Imbalances', 'Preventative Lifestyle Guidance', 'Holistic Restoration', 'Natural Healing'];
        let currentEditTags = [];

        function renderSelectedTags(tagsArray) {
            selectedTagsContainer.innerHTML = '';
            tagsArray.forEach(tag => {
                const span = document.createElement('span');
                // Use light gray background for the tag inside the top box
                span.className = 'bg-[#F2F2F2] text-[#423131] px-4 py-2 rounded-[6px] text-sm font-normal transition-colors cursor-default';
                span.textContent = tag;

                selectedTagsContainer.appendChild(span);
            });
        }

        function updateEditState() {
            renderSelectedTags(currentEditTags);

            actionBtn.classList.remove(...classAmber, ...classCancel);

            if (currentEditTags.length > 0) {
                actionBtn.textContent = 'Add';
                actionBtn.classList.add(...classAmber);
            } else {
                actionBtn.textContent = 'Cancel';
                actionBtn.classList.add(...classCancel);
            }

            // Update bottom tags visual active state to match the amber hover color
            availableOptions.forEach(opt => {
                const val = opt.getAttribute('data-val');
                if (currentEditTags.includes(val)) {
                    opt.classList.remove('bg-white', 'text-gray-700', 'border-gray-300');
                    opt.classList.add('bg-[#FABD4D]', 'border-[#FABD4D]', 'text-[#423131]');
                } else {
                    opt.classList.add('bg-white', 'text-gray-700', 'border-gray-300');
                    opt.classList.remove('bg-[#FABD4D]', 'border-[#FABD4D]', 'text-[#423131]');
                }
            });
        }

        // Initial setup
        renderSelectedTags(savedTags);

        actionBtn.addEventListener('click', () => {
            if (mode === 'view') {
                // Enter Edit Mode
                mode = 'edit';
                currentEditTags = []; // Blank out as requested
                updateEditState();
                availableTagsContainer.classList.remove('hidden');
                actionBtn.classList.remove('px-6');
                actionBtn.classList.add('px-5'); // slightly smaller padding for cancel
            } else if (mode === 'edit') {
                if (currentEditTags.length > 0) {
                    // Save and return to view mode
                    savedTags = [...currentEditTags];
                }
                // If 0 length, it's 'Cancel', so we just revert to old savedTags

                mode = 'view';
                renderSelectedTags(savedTags);

                actionBtn.textContent = 'Change';
                actionBtn.classList.remove(...classCancel);
                actionBtn.classList.add(...classAmber);
                actionBtn.classList.add('px-6');
                actionBtn.classList.remove('px-5');

                availableTagsContainer.classList.add('hidden');
            }
        });

        // Add clicking listener to options
        availableOptions.forEach(opt => {
            opt.addEventListener('click', () => {
                if (mode === 'edit') {
                    const val = opt.getAttribute('data-val');
                    if (currentEditTags.includes(val)) {
                        // If already active, repress to remove
                        currentEditTags = currentEditTags.filter(t => t !== val);
                    } else {
                        // Otherwise add
                        currentEditTags.push(val);
                    }
                    updateEditState();
                }
            });
        });
    }
});


// Practitioner Modal Logic
function openPractitionerModal() {
    const modal = document.getElementById('practitionerModal');
    if(!modal) return;
    
    // Initialize swiper if not already done
    if (!modal.classList.contains('swiper-initialized-custom')) {
        modal.classList.add('swiper-initialized-custom');
        new Swiper('.practitioner-modal-slider', {
            slidesPerView: 'auto',
            spaceBetween: 28,
            grabCursor: true,
            freeMode: true,
            slidesOffsetBefore:40,           

        });
    }

    const backdrop = modal.querySelector('.popup-backdrop');
    const content = modal.querySelector('.popup-content');
    
    modal.classList.remove('hidden');
    
    // Trigger animations
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
        content.classList.remove('opacity-0', 'scale-95');
        content.classList.add('opacity-100', 'scale-100');
    }, 10);
}

function closePractitionerModal() {
    const modal = document.getElementById('practitionerModal');
    if(!modal) return;
    
    const backdrop = modal.querySelector('.popup-backdrop');
    const content = modal.querySelector('.popup-content');
    
    // Trigger closing animations
    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0');
    content.classList.remove('opacity-100', 'scale-100');
    content.classList.add('opacity-0', 'scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}


// Payment Success Modal Logic
function openSuccessModal() {
    const modal = document.getElementById('successModal');
    if(!modal) return;
    
    const backdrop = modal.querySelector('.popup-backdrop');
    const content = modal.querySelector('.popup-content');
    
    modal.classList.remove('hidden');
    
    // Trigger animations
    setTimeout(() => {
        backdrop.classList.remove('opacity-0');
        backdrop.classList.add('opacity-100');
        content.classList.remove('opacity-0', 'scale-95');
        content.classList.add('opacity-100', 'scale-100');
    }, 10);
}

function closeSuccessModal() {
    const modal = document.getElementById('successModal');
    if(!modal) return;
    
    const backdrop = modal.querySelector('.popup-backdrop');
    const content = modal.querySelector('.popup-content');
    
    // Trigger closing animations
    backdrop.classList.remove('opacity-100');
    backdrop.classList.add('opacity-0');
    content.classList.remove('opacity-100', 'scale-100');
    content.classList.add('opacity-0', 'scale-95');
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}
