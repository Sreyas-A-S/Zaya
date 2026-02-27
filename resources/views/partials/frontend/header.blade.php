<!-- Header -->
<header class="fixed w-full top-0 z-50 transition-all duration-300 py-8 bg-white">
    <div class="container mx-auto px-4 lg:px-6 flex justify-between items-center relative">

        <!-- Mobile Toggle (Visible on Mobile) -->
        <button id="mobile-menu-btn" class="lg:hidden text-2xl text-secondary focus:outline-none">
            <i class="ri-menu-line"></i>
        </button>

        <!-- Left Nav (Desktop) -->
        <nav
            class="hidden lg:flex items-center gap-6 lg:gap-8 text-base lg:text-lg font-medium flex-1 justify-start text-gray-700">
            <a id="nav-home" href="{{ route('index') }}" class="hover:text-primary transition-colors">{{ __('Home') }}</a>

            <!-- About Us Dropdown -->
            <div class="relative group">
                <button id="nav-about" class="flex items-center gap-1 hover:text-primary transition-colors focus:outline-none py-4">
                    {{ __('About Us') }} <i
                        class="ri-arrow-down-s-line transition-transform duration-300 group-hover:-rotate-180"></i>
                </button>
                <div
                    class="absolute top-full left-0 w-48 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 text-left overflow-hidden">
                    <a id="nav-who-we-are" href="{{ route('about-us') }}#who-we-are"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50">{{ __('Who we are?') }}</a>
                    <a id="nav-what-we-do" href="{{ route('about-us') }}#what-we-do"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50">{{ __('What we do?') }}</a>
                    <a id="nav-our-team" href="{{ route('about-us') }}#our-team"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors">{{ __('Our Team') }}</a>
                    <a id="nav-gallery" href="#" class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors">{{ __('Gallery') }}</a>
                    <a id="nav-blog" href="{{ route('blogs') }}"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors">{{ __('Blog') }}</a>
                </div>
            </div>

            <!-- Services Dropdown (Desktop)-->
            <div class="relative group">
                <button id="nav-services" class="flex items-center gap-1 hover:text-primary transition-colors focus:outline-none py-4">
                    {{ __('Services') }} <i
                        class="ri-arrow-down-s-line transition-transform duration-300 group-hover:-rotate-180"></i>
                </button>
                <div
                    class="absolute top-full left-0 w-56 bg-white rounded-xl shadow-xl border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 text-left overflow-hidden">
                    <a id="nav-ayurveda" href="{{ route('services', ['category' => 'Ayurveda']) }}#services-listing"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50">{{ __('Ayurveda') }}</a>
                    <a id="nav-yoga" href="{{ route('services', ['category' => 'Yoga']) }}#services-listing"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50">{{ __('Yoga') }}</a>
                    <a id="nav-counselling" href="{{ route('services', ['category' => 'Counselling']) }}#services-listing"
                        class="block px-5 py-3 text-sm text-gray-600 hover:bg-surface hover:text-primary transition-colors border-b border-gray-50">{{ __('Counselling') }}</a>
                </div>
            </div>

            <a id="nav-contact" href="{{ route('contact-us') }}" class="hover:text-primary transition-colors">{{ __('Contact Us') }}</a>
        </nav>

        <!-- Logo (Centered) -->
        <a href="{{ route('index') }}"
            class="flex items-center justify-center mx-auto absolute left-1/2 transform -translate-x-1/2">
            <img src="{{ asset('frontend/assets/zaya-logo.svg') }}" alt="Zaya Wellness"
                class="w-20 h-20 lg:w-24 lg:h-24 object-contain">
        </a>

        <!-- Right Actions (Desktop) -->
        <div class="flex items-center gap-6 lg:gap-8 justify-end flex-1">
            <a id="nav-login" href="{{ route('zaya-login') }}"
                class="hidden lg:inline-block text-base lg:text-lg text-gray-700 hover:text-primary font-medium transition-colors">{{ __('Login') }}</a>

            <a id="nav-find-practitioner" href="#" class="hidden lg:inline-block bg-secondary text-white px-6 py-2.5 rounded-full text-base font-medium hover:bg-opacity-90 transition-all shadow-md hover:shadow-lg whitespace-nowrap">{{ __('Find Practitioner') }}</a>

            <!-- Language Toggle -->
            <div class="flex items-center bg-gray-100 rounded-full p-1 border border-gray-200" id="frontend-lang-toggle">
                @php $currentLocale = app()->getLocale(); @endphp
                <button
                    onclick="switchLanguage('en')"
                    class="{{ $currentLocale == 'en' ? 'bg-primary text-white shadow-sm' : 'text-gray-500 hover:bg-gray-200' }} text-xs font-bold px-3 py-1.5 rounded-full transition-all uppercase">
                    En
                </button>
                <button
                    onclick="switchLanguage('fr')"
                    class="{{ $currentLocale == 'fr' ? 'bg-primary text-white shadow-sm' : 'text-gray-500 hover:bg-gray-200' }} text-xs font-bold px-3 py-1.5 rounded-full transition-all uppercase">
                    Fr
                </button>
            </div>
        </div>
    </div>

    <!-- Script for Dynamic Toggling -->
    <script>
    function switchLanguage(langCode) {
        // Immediate visual feedback (swap classes)
        const toggle = document.getElementById('frontend-lang-toggle');
        const buttons = toggle.querySelectorAll('button');
        
        buttons.forEach(btn => {
            if (btn.innerText.toLowerCase() === langCode.toLowerCase()) {
                btn.className = 'bg-primary text-white shadow-sm text-xs font-bold px-3 py-1.5 rounded-full transition-all uppercase';
            } else {
                btn.className = 'text-gray-500 hover:bg-gray-200 text-xs font-bold px-3 py-1.5 rounded-full transition-all uppercase';
            }
        });

        // Backend switch
        fetch(`/change-language/${langCode}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status) {
                // Update Homepage Settings dynamically
                if (data.data) {
                    Object.keys(data.data).forEach(key => {
                        const element = document.getElementById(key);
                        if (element) {
                            // Check if it's an image, input, or text
                            if (element.tagName === 'IMG') {
                                let src = data.data[key];
                                if (src && !src.startsWith('http')) {
                                    src = src.startsWith('frontend/') ? `/${src}` : `/storage/${src}`;
                                }
                                element.src = src;
                            } else if (element.tagName === 'INPUT' || element.tagName === 'TEXTAREA') {
                                // Update placeholder for inputs
                                element.placeholder = data.data[key];
                            } else {
                                // For general text, handle potential HTML/newlines
                                element.innerHTML = data.data[key].replace(/\n/g, '<br>');
                            }
                        }
                    });
                }

                // Update Navigation / Static translations if they were returned
                // Note: The 'change' method currently only returns HomepageSetting data.
                // For full "no-reload" translations of __('Home') etc, 
                // we'd need to fetch the JSON lang file or have the controller return them.
                
                // If you want static menu items to also change without reload, 
                // we can add a small map here for the main ones:
                const translations = {
                    'en': {
                        'nav-home': 'Home', 'nav-about': 'About Us', 'nav-who-we-are': 'Who we are?', 
                        'nav-what-we-do': 'What we do?', 'nav-our-team': 'Our Team', 'nav-gallery': 'Gallery', 
                        'nav-blog': 'Blog', 'nav-services': 'Services', 'nav-ayurveda': 'Ayurveda', 
                        'nav-yoga': 'Yoga', 'nav-counselling': 'Counselling', 'nav-contact': 'Contact Us',
                        'nav-login': 'Login', 'nav-find-practitioner': 'Find Practitioner',
                        'footer-newsletter-title': 'Join our newsletter for weekly wellness tips.',
                        'footer-newsletter-input': 'Your email...',
                        'footer-tagline': 'Empowering your wellness journey through ancient wisdom and modern science.',
                        'footer-quick-links-title': 'Quick Links',
                        'footer-home': 'Home', 'footer-who-we-are': 'Who we are', 'footer-what-we-do': 'What we do',
                        'footer-our-team': 'Our Team', 'footer-blog': 'Blog', 'footer-contact-us': 'Contact Us',
                        'footer-conditions-title': 'Conditions We Support',
                        'footer-life-transitions': 'Life Transitions', 'footer-mental-imbalance': 'Mental Imbalance',
                        'footer-stress-reduction': 'Stress Reduction', 'footer-toxin-removal': 'Toxin Removal',
                        'footer-chronic-pain': 'Chronic Pain', 'footer-immune-support': 'Immune Support',
                        'footer-pincode-title': 'Save your pincode & find nearby care.',
                        'footer-pincode-input': 'Enter Pincode', 'footer-pincode-save': 'Save',
                        'footer-privacy': 'Privacy Policy', 'footer-cookie': 'Cookie Policy',
                        'footer-terms': 'Terms & Conditions', 'footer-gdpr': 'GDPR & Data Protection',
                        'footer-all-rights': 'All rights reserved.',
                        'about-what-we-do-title': 'What we do?', 'about-vision-title': 'Our Vision',
                        'about-vision-desc': 'Our vision is to create a global, practitioner-led wellness ecosystem rooted in Ayurveda and holistic care. Zaya aims to make authentic, ethical, and personalized wellness accessible across borders through trusted collaboration and thoughtful use of technology. We envision a future where practitioners are empowered, specialists work together seamlessly, and you experience care that truly supports long-term well-being.',
                        'about-mission-title': 'Our Mission',
                        'about-mission-desc': "We're on a mission to support the practitioners and simplify the wellness process for everyone across the globe. Driven by a passion for holistic health, our team empowers practitioners to provide the best care that you deserve.",
                        'about-core-values-title': 'Core Values',
                        'about-value-1-title': 'Trust & Ethics', 'about-value-1-desc': 'We respect the practitioner-client relationship and uphold transparency in every interaction.',
                        'about-value-2-title': 'Holistic Care', 'about-value-2-desc': 'We believe in treating the whole person through Ayurveda, yoga, and mindfulness.',
                        'about-value-3-title': 'Collaboration', 'about-value-3-desc': 'We foster meaningful partnerships between practitioners and specialists.',
                        'about-value-4-title': 'Human-Centered Technology', 'about-value-4-desc': 'We use technology to support care, not to replace it.',
                        'about-value-5-title': 'Accessibility', 'about-value-5-desc': 'We strive to make quality wellness services available across borders and languages.',
                        'about-services-btn': 'Go to Our Services',
                        'about-cta-1-title': 'Ready to start your wellness journey?', 'about-cta-1-btn': 'Book a Practitioner',
                        'about-cta-2-title': 'Join our community of holistic experts.', 'about-cta-2-btn': 'Apply to Join',
                        'about-help-title': "Have questions? We're here to help.", 'about-help-btn': 'Contact Us',
                        'about-testimonials-title': 'What people say about Zaya?',
                        'contact-info-location-label': 'Location', 'contact-info-phone-label': 'Contact',
                        'contact-info-email-label': 'Email', 'contact-info-working-hours-label': 'Working Hours',
                        'contact-label-first-name': 'First Name', 'contact-label-last-name': 'Last Name',
                        'contact-label-email': 'Email', 'contact-label-phone': 'Phone No',
                        'contact-label-user-type': 'I am a', 'contact-user-type-client': 'Client',
                        'contact-user-type-practitioner': 'Practitioner', 'contact-label-message': 'Message',
                        'contact-label-consent': 'I give consent to Zaya for processing my personal data in accordance with GDPR',
                        'contact-btn-submit': 'Submit', 'contact-btn-join-practitioner': 'Join as Practitioner',
                        'contact-btn-view-faqs': 'View FAQs',
                        'first_name': 'Your First Name', 'last_name': 'Your Last Name', 'email': 'Your Email',
                        'phone': 'Your Phone No.', 'message': 'Your Message',
                        'cat-all': 'All Services', 'cat-ayurveda': 'Ayurveda', 'cat-yoga': 'Yoga',
                        'cat-counselling': 'Counselling', 'cat-mindfulness': 'Mindfulness',
                        'cat-spiritual': 'Spiritual Guidance', 'services-search-input': 'Search services...',
                        'selected-category': 'Select Category'
                    },
                    'fr': {
                        'nav-home': 'Accueil', 'nav-about': 'À propos', 'nav-who-we-are': 'Qui sommes-nous ?', 
                        'nav-what-we-do': 'Que faisons-nous ?', 'nav-our-team': 'Notre équipe', 'nav-gallery': 'Galerie', 
                        'nav-blog': 'Blog', 'nav-services': 'Services', 'nav-ayurveda': 'Ayurvéda', 
                        'nav-yoga': 'Yoga', 'nav-counselling': 'Conseils', 'nav-contact': 'Contactez-nous',
                        'nav-login': 'Connexion', 'nav-find-practitioner': 'Trouver un praticien',
                        'footer-newsletter-title': 'Rejoignez notre newsletter pour des conseils hebdomadaires sur le bien-être.',
                        'footer-newsletter-input': 'Votre e-mail...',
                        'footer-tagline': "Donner de l'importance à votre parcours de bien-être grâce à la sagesse ancienne et à la science moderne.",
                        'footer-quick-links-title': 'Liens rapides',
                        'footer-home': 'Accueil', 'footer-who-we-are': 'Qui sommes-nous ?', 'footer-what-we-do': 'Ce que nous faisons',
                        'footer-our-team': 'Notre équipe', 'footer-blog': 'Blog', 'footer-contact-us': 'Contactez-nous',
                        'footer-conditions-title': 'Pathologies que nous traitons',
                        'footer-life-transitions': 'Transitions de vie', 'footer-mental-imbalance': 'Déséquilibre mental',
                        'footer-stress-reduction': 'Réduction du stress', 'footer-toxin-removal': 'Élimination des toxines',
                        'footer-chronic-pain': 'Douleur chronique', 'footer-immune-support': 'Soutien immunitaire',
                        'footer-pincode-title': 'Enregistrez votre code postal et trouvez des soins à proximité.',
                        'footer-pincode-input': 'Entrez le code postal', 'footer-pincode-save': 'Enregistrer',
                        'footer-privacy': 'Politique de confidentialité', 'footer-cookie': 'Politique relative aux cookies',
                        'footer-terms': 'Conditions générales', 'footer-gdpr': 'RGPD et protection des données',
                        'footer-all-rights': 'Tous droits réservés.',
                        'about-what-we-do-title': 'Que faisons-nous ?', 'about-vision-title': 'Notre vision',
                        'about-vision-description': "Notre vision est de créer un écosystème de bien-être mondial, dirigé par des praticiens, enraciné dans l'Ayurveda et les soins holistiques. Zaya vise à rendre le bien-être authentique, éthique et personnalisé accessible au-delà des frontières grâce à une collaboration de confiance et à l'utilisation réfléchie de la technologie. Nous envisageons un avenir où les praticiens sont responsabilisés, où les spécialistes travaillent ensemble de manière transparente et où vous bénéficiez de soins qui soutiennent véritablement le bien-être à long terme.",
                        'about-mission-title': 'Notre mission',
                        'about-mission-description': "Nous avons pour mission de soutenir les praticiens et de simplifier le processus de bien-être pour tous à travers le monde. Animée par une passion pour la santé holistique, notre équipe donne aux praticiens les moyens de fournir les meilleurs soins que vous méritez.",
                        'about-core-values-title': 'Valeurs fondamentales',
                        'about-value-1-title': 'Confiance et éthique', 'about-value-1-desc': 'Nous respectons la relation praticien-client et maintenons la transparence dans chaque interaction.',
                        'about-value-2-title': 'Soins holistiques', 'about-value-2-desc': "Nous croyons au traitement de la personne dans sa globalité par l'Ayurveda, le yoga et la pleine conscience.",
                        'about-value-3-title': 'Collaboration', 'about-value-3-desc': 'Nous favorisons des partenariats significatifs entre praticiens et spécialistes.',
                        'about-value-4-title': "Technologie centrée sur l'humain", 'about-value-4-desc': "Nous utilisons la technologie pour soutenir les soins, et non pour les remplacer.",
                        'about-value-5-title': 'Accessibilité', 'about-value-5-desc': 'Nous nous efforçons de rendre des services de bien-être de qualité accessibles au-delà des frontières et des langues.',
                        'about-services-btn': 'Voir nos services',
                        'about-cta-1-title': 'Prêt à commencer votre parcours de bien-être ?', 'about-cta-1-btn': 'Réserver un praticien',
                        'about-cta-2-title': "Rejoignez notre communauté d'experts holistiques.", 'about-cta-2-btn': 'Postuler pour nous rejoindre',
                        'about-help-title': 'Vous avez des questions ? Nous sommes là pour vous aider.', 'about-help-btn': 'Contactez-nous',
                        'about-testimonials-title': 'Ce que les gens disent de Zaya ?',
                        'contact-info-location-label': 'Emplacement', 'contact-info-phone-label': 'Contact',
                        'contact-info-email-label': 'E-mail', 'contact-info-working-hours-label': 'Heures de travail',
                        'contact-label-first-name': 'Prénom', 'contact-label-last-name': 'Nom',
                        'contact-label-email': 'E-mail', 'contact-label-phone': 'N° de téléphone',
                        'contact-label-user-type': 'Je suis un', 'contact-user-type-client': 'Client',
                        'contact-user-type-practitioner': 'Praticien', 'contact-label-message': 'Message',
                        'contact-label-consent': "J'autorise Zaya à traiter mes données personnelles conformément au RGPD",
                        'contact-btn-submit': 'Envoyer', 'contact-btn-join-practitioner': 'Rejoindre en tant que praticien',
                        'contact-btn-view-faqs': 'Voir la FAQ',
                        'first_name': 'Votre prénom', 'last_name': 'Votre nom', 'email': 'Votre e-mail',
                        'phone': 'Votre n° de téléphone', 'message': 'Votre message',
                        'cat-all': 'Tous les services', 'cat-ayurveda': 'Ayurvéda', 'cat-yoga': 'Yoga',
                        'cat-counselling': 'Conseils', 'cat-mindfulness': 'Pleine conscience',
                        'cat-spiritual': 'Guidance spirituelle', 'services-search-input': 'Rechercher des services...',
                        'selected-category': 'Choisir une catégorie'
                    }
                };

                // Update navigation and footer labels
                Object.keys(translations[langCode]).forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        if (el.tagName === 'INPUT') {
                            el.placeholder = translations[langCode][id];
                        } else if (el.querySelector('i')) {
                            // Preserve icons
                            const icon = el.querySelector('i').outerHTML;
                            // Extract only the new text part to avoid duplicating icons if they were at the end
                            el.innerHTML = `${translations[langCode][id]} ${icon}`;
                        } else {
                            // Special case for copyright to preserve date
                            if (id === 'footer-all-rights') {
                                const year = new Date().getFullYear();
                                el.innerHTML = `${translations[langCode][id]} &copy; ${year} Zaya Wellness`;
                            } else {
                                el.innerText = translations[langCode][id];
                            }
                        }
                    }
                });
            }
        });
    }
    </script>
    
    <!-- Mobile Menu -->
    <div id="mobile-menu"
        class="absolute top-full left-0 w-full bg-white shadow-xl border-t border-gray-100 flex flex-col p-6 gap-4 lg:hidden max-h-0 opacity-0 invisible transform -translate-y-4 transition-all duration-300 ease-in-out overflow-hidden">
        <a href="{{ route('index') }}" class="text-lg font-medium text-secondary border-b border-gray-50 pb-2">Home</a>

        <div class="flex flex-col gap-2">
            <span class="text-lg font-medium text-secondary">About Us</span>
            <div class="pl-4 flex flex-col gap-2 border-l-2 border-primary/20">
                <a href="{{ route('about-us') }}" class="text-gray-600 text-sm">Who we are?</a>
                <a href="#" class="text-gray-600 text-sm">What we do?</a>
                <a href="#" class="text-gray-600 text-sm">Our Team</a>
            </div>
        </div>

        <div class="flex flex-col gap-2">
            <span class="text-lg font-medium text-secondary">Services</span>
            <div class="pl-4 flex flex-col gap-2 border-l-2 border-primary/20">
                <a href="{{ route('services', ['category' => 'Ayurveda']) }}#services-listing"
                    class="text-gray-600 text-sm">Ayurveda</a>
                <a href="{{ route('services', ['category' => 'Yoga']) }}#services-listing"
                    class="text-gray-600 text-sm">Yoga</a>
                <a href="{{ route('services', ['category' => 'Counselling']) }}#services-listing"
                    class="text-gray-600 text-sm">Counselling</a>
            </div>
        </div>

        <a href="{{ route('contact-us') }}"
            class="text-lg font-medium text-secondary border-b border-gray-50 pb-2">Contact Us</a>
        <a href="{{ route('zaya-login') }}" class="text-lg font-medium text-secondary pb-2">Login</a>

        <!-- Language Toggle (Mobile) -->
        <div class="flex items-center bg-gray-100 rounded-full p-1 border border-gray-200 w-fit">
            @php $currentLocale = app()->getLocale(); @endphp
            <button
                onclick="switchLanguage('en')"
                class="{{ $currentLocale == 'en' ? 'bg-primary text-white shadow-sm' : 'text-gray-500 hover:bg-gray-200' }} text-xs font-bold px-4 py-2 rounded-full transition-all uppercase">
                En
            </button>
            <button
                onclick="switchLanguage('fr')"
                class="{{ $currentLocale == 'fr' ? 'bg-primary text-white shadow-sm' : 'text-gray-500 hover:bg-gray-200' }} text-xs font-bold px-4 py-2 rounded-full transition-all uppercase">
                Fr
            </button>
        </div>

        <div class="pt-2">
            <a href="#"
                class="block w-full bg-secondary text-white px-6 py-3 rounded-full text-center hover:bg-opacity-90">Book
                a Practitioner</a>
        </div>
    </div>
</header>