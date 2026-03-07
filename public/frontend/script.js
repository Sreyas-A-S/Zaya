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
            const slidesCount = document.querySelectorAll('.testimonial-slider .swiper-slide').length;
            new SwiperLib('.testimonial-slider', {
                slidesPerView: '1', 
                spaceBetween: 40,
                centeredSlides: true,
                loop: slidesCount >= 2,
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
                        loop: slidesCount >= 4,
                    },
                    768: {
                        slidesPerView: '3',
                        spaceBetween: 50,
                        centeredSlides: true,
                        loop: slidesCount >= 6,
                    },
                    1024: {
                        slidesPerView: '4',
                        spaceBetween: 50,
                        centeredSlides: false,
                        loop: slidesCount >= 8,
                    }
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
    document.addEventListener('click', async function(e) {
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
        newsletterBtn.addEventListener('click', async function(e) {
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
