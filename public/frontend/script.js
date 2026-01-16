document.addEventListener('DOMContentLoaded', () => {
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const header = document.querySelector('header');

    // Toggle Mobile Menu
    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        // Simple animation logic if needed, or rely on CSS transitions
    });

    // Close mobile menu when clicking a link
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });
    });

    // Sticky Header Effect
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            header.classList.add('glass-nav');
            header.classList.remove('bg-white', 'py-6');
            header.classList.add('bg-white/90', 'py-3');
        } else {
            header.classList.remove('glass-nav', 'bg-white/90', 'py-3');
            header.classList.add('bg-white', 'py-6');
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
            delay: 1500,
            pauseOnMouseEnter: true,
            pauseOnFocus: true,
        },
        breakpoints: {
            512: {
                slidesPerView: 2.3,
                centeredSlides: false,
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
                slidesPerView: 5.3,
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
