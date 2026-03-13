document.addEventListener('DOMContentLoaded', () => {
    const SwiperLib = window.Swiper;
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const header = document.querySelector('header');

    // Toggle Mobile Menu
    if (mobileMenuBtn && mobileMenu) {
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
    }

    // Close mobile menu when clicking a link
    if (mobileMenu) {
        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.remove('overflow-y-auto');
                mobileMenu.classList.add('overflow-hidden');

                mobileMenu.classList.add('max-h-0', 'opacity-0', 'invisible', '-translate-y-4');
                mobileMenu.classList.remove('max-h-[80vh]', 'opacity-100', 'visible', 'translate-y-0');
            });
        });
    }

    // Sticky Header Effect
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                header.classList.add('glass-nav');
                // header.classList.remove('bg-white/60');
            } else {
                header.classList.remove('glass-nav');
                // header.classList.add('bg-white/60');
            }
        });
    }

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
            const slidesCount = document.querySelectorAll('.heroSlider .swiper-slide').length;
            new SwiperLib('.heroSlider', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: slidesCount > 1,
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
            const slidesCount = document.querySelectorAll('.practitioner-slider .swiper-slide').length;
            new SwiperLib('.practitioner-slider', {
                slidesPerView: 1.5,
                spaceBetween: 20,
                loop: slidesCount >= 6, // Need enough slides for looping especially on larger screens
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
                        loop: slidesCount >= 6,
                    },
                    768: {
                        slidesPerView: 3,
                        centeredSlides: false,
                        loop: slidesCount >= 6,
                    },
                    1152: {
                        slidesPerView: 4,
                        centeredSlides: false,
                        loop: slidesCount >= 8,
                    },
                    1440: {
                        slidesPerView: 4.4,
                        spaceBetween: 40,
                        centeredSlides: false,
                        loop: slidesCount >= 10,
                    },
                    1920: {
                        slidesPerView: 5,
                        spaceBetween: 80,
                        centeredSlides: false,
                        loop: slidesCount >= 10,
                    },
                },
            });
        }

        // Testimonial Slider
        if (document.querySelector('.testimonial-slider')) {
            // const slidesCount = document.querySelectorAll('.testimonial-slider .swiper-slide').length;
            new SwiperLib('.testimonial-slider', {
                slidesPerView: '1',
                spaceBetween: 40,
                centeredSlides: false,
                // loop: slidesCount >= 2,
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
                        centeredSlides: false,
                        // loop: slidesCount >= 4,
                    },
                    768: {
                        slidesPerView: '2',
                        spaceBetween: 30,
                        centeredSlides: false,
                        // loop: slidesCount >= 6,
                    },
                    1024: {
                        slidesPerView: '3',
                        spaceBetween: 50,
                        centeredSlides: false,
                        // loop: slidesCount >= 8,
                    },
                    1280: {
                        slidesPerView: '4',
                        spaceBetween: 50,
                        centeredSlides: false,
                        // loop: slidesCount >= 8,
                    },
                    1536: {
                        slidesPerView: '5',
                        spaceBetween: 50,
                        centeredSlides: false,
                        // loop: slidesCount >= 8,
                    },
                }
            });
        }

        // Service Detail Image Slider
        if (document.querySelector('.serviceImageSwiper')) {
            const slidesCount = document.querySelectorAll('.serviceImageSwiper .swiper-slide').length;
            new SwiperLib('.serviceImageSwiper', {
                slidesPerView: 1,
                spaceBetween: 0,
                loop: slidesCount > 1,
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

    // Testimonial Like Functionality (using event delegation for Swiper compatibility)
    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('.testimonial-like-btn');
        if (!btn) return;

        e.preventDefault();
        const id = btn.getAttribute('data-id');
        const countSpan = btn.querySelector('span');
        const icon = btn.querySelector('i');

        try {
            const response = await fetch(`/testimonial/${id}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.status === 'success') {
                // Update all instances of this testimonial's like count (for Swiper clones)
                document.querySelectorAll(`.testimonial-like-btn[data-id="${id}"]`).forEach(instance => {
                    const s = instance.querySelector('span');
                    const i = instance.querySelector('i');
                    if (s) s.textContent = data.likes_count;
                    if (i) {
                        if (data.action === 'liked') {
                            i.classList.remove('ri-thumb-up-line');
                            i.classList.add('ri-thumb-up-fill', 'text-primary');
                        } else {
                            i.classList.remove('ri-thumb-up-fill', 'text-primary');
                            i.classList.add('ri-thumb-up-line');
                        }
                    }
                });
            }
        } catch (error) {
            console.error('Error liking testimonial:', error);
        }
    });

    // Newsletter Subscription Handler
    const newsletterInput = document.getElementById('footer-newsletter-input');
    const newsletterBtn = document.getElementById('footer-newsletter-btn');

    if (newsletterBtn && newsletterInput) {
        newsletterBtn.addEventListener('click', async function (e) {
            e.preventDefault();
            const email = newsletterInput.value;

            if (!email) {
                alert('Please enter an email address.');
                return;
            }

            newsletterBtn.disabled = true;
            const originalIcon = newsletterBtn.innerHTML;
            newsletterBtn.innerHTML = '<i class="ri-loader-4-line animate-spin text-xl"></i>';

            try {
                const response = await fetch('/newsletter/subscribe', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ email: email })
                });

                const data = await response.json();
                alert(data.message);

                if (response.ok && data.success !== false) {
                    newsletterInput.value = '';
                }
            } catch (error) {
                console.error('Newsletter error:', error);
                alert('Something went wrong. Please try again.');
            } finally {
                newsletterBtn.disabled = false;
                newsletterBtn.innerHTML = originalIcon;
            }
        });
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
    if (!modal) return;

    // Initialize swiper if not already done
    if (!modal.classList.contains('swiper-initialized-custom')) {
        modal.classList.add('swiper-initialized-custom');
        new Swiper('.practitioner-modal-slider', {
            slidesPerView: 'auto',
            spaceBetween: 28,
            grabCursor: true,
            freeMode: true,
            slidesOffsetBefore: 40,
        });

        // Add selection logic once
        modal.querySelectorAll('.practitioner-select-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Don't select if clicking 'See more' link
                if (e.target.tagName === 'A') return;

                const id = this.dataset.id;
                const name = this.dataset.name;
                const image = this.dataset.image;
                const role = this.dataset.role;
                const rating = this.dataset.rating;
                const location = this.dataset.location;

                // Update all practitioner cards on the page (Step 2 and Step 3)
                document.querySelectorAll('img[alt$="Practitioner"], img[alt$="Profile Pic"]').forEach(img => {
                    img.src = image;
                    img.alt = name;
                });

                document.querySelectorAll('h3.font-medium.font-sans\\!').forEach(h3 => {
                    if (h3.innerText.trim() !== 'ZAYA' && !h3.closest('.practitioner-select-card')) {
                        h3.innerHTML = `${name}`;
                    }
                });

                document.querySelectorAll('.ri-star-fill + span').forEach(span => {
                    span.innerText = rating;
                });

                document.querySelectorAll('.text-\\[\\#252525\\].text-base').forEach(p => {
                    if (!p.closest('.practitioner-select-card')) {
                        p.innerText = role;
                    }
                });

                document.querySelectorAll('.ri-map-pin-line').forEach(icon => {
                    const p = icon.parentElement;
                    if (p) {
                        p.innerHTML = `<i class="ri-map-pin-line"></i> ${location}`;
                    }
                });

                // Update hidden input for form submission
                let hiddenInput = document.getElementById('selected-practitioner-id');
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.id = 'selected-practitioner-id';
                    hiddenInput.name = 'practitioner_id';
                    document.body.appendChild(hiddenInput);
                }
                hiddenInput.value = id;

                closePractitionerModal();
            });
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
    if (!modal) return;

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
    if (!modal) return;

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
    if (!modal) return;

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
