document.addEventListener('DOMContentLoaded', () => {
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

    new Swiper('.practitioner-slider', {
        slidesPerView: 1.5,
        spaceBetween: 40,
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
                slidesPerView: 3.3,
                centeredSlides: false,
            },
            1152: {
                slidesPerView: 4.3,
                centeredSlides: false,
            },
            1440: {
                slidesPerView: 4.6,
                centeredSlides: false,
            },
            1920: {
                slidesPerView: 6.3,
                centeredSlides: false,
            },
        },
    });

    // Testimonial Slider
    new Swiper('.testimonial-slider', {
        slidesPerView: 'auto', // Fluid width to match design
        spaceBetween: 40,
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
                spaceBetween: 60,
            }
        }
    });
});
