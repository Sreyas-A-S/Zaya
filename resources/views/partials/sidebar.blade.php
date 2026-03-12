<aside class="page-sidebar">



    <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
    <div class="main-sidebar" id="main-sidebar">
        <ul class="sidebar-menu" id="simple-bar">
            <li class="pin-title sidebar-main-title">
                <div>
                    <h5 class="sidebar-title f-w-700">Pinned</h5>
                </div>
            </li>
            @if(auth()->user()->hasPermission('dashboard-view') || auth()->user()->hasPermission('doctors-view') || auth()->user()->hasPermission('practitioners-view') || auth()->user()->hasPermission('mindfulness-practitioners-view') || auth()->user()->hasPermission('yoga-therapists-view') || auth()->user()->hasPermission('clients-view') || auth()->user()->hasPermission('translators-view') || auth()->user()->hasPermission('testimonials-view') || auth()->user()->hasPermission('services-view') || auth()->user()->hasPermission('practitioner-reviews-view'))
            <li class="sidebar-main-title">
                <div>
                    <h5 class="lan-1 f-w-700 sidebar-title">General</h5>
                </div>
            </li>
            @endif
            @if(auth()->user()->hasPermission('dashboard-view'))
            <li class="sidebar-list"><a class="sidebar-link" href="{{ route('admin.dashboard') }}">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Home-dashboard') }}"></use>
                    </svg>
                    <h6>Dashboard</h6>
                </a>
            </li>
            <li class="sidebar-list"><a class="sidebar-link" href="{{ route('admin.profile') }}">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Profile') }}"></use>
                    </svg>
                    <h6>My Profile</h6>
                </a>
            </li>
            @endif

            @if(auth()->user()->hasPermission('doctors-view') || auth()->user()->hasPermission('practitioners-view') || auth()->user()->hasPermission('mindfulness-practitioners-view') || auth()->user()->hasPermission('yoga-therapists-view') || auth()->user()->hasPermission('clients-view') || auth()->user()->hasPermission('translators-view'))
            <li class="sidebar-list"> <a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Profile') }}"></use>
                    </svg>
                    <h6 class="f-w-600">Users</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    @if(auth()->user()->hasPermission('doctors-view'))
                    <li> <a href="{{ route('admin.doctors.index') }}">Doctors</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('practitioners-view'))
                    <li> <a href="{{ route('admin.practitioners.index') }}">Practitioners</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('mindfulness-practitioners-view'))
                    <li> <a href="{{ route('admin.mindfulness-practitioners.index') }}">Mindfulness Practitioners</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('yoga-therapists-view'))
                    <li> <a href="{{ route('admin.yoga-therapists.index') }}">Yoga Therapists</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('clients-view'))
                    <li> <a href="{{ route('admin.clients.index') }}">Clients</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('translators-view'))
                    <li> <a href="{{ route('admin.translators.index') }}">Translators</a></li>
                    @endif
                </ul>
            </li>
            @endif
            <li class="sidebar-list"> <a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Profile') }}"></use>
                    </svg>
                    <h6 class="f-w-600">Backend Users</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    @if(auth()->user()->hasPermission('admins-view'))
                    <li> <a href="{{ route('admin.admins.index') }}">Admins</a></li>
                    <li> <a href="{{ route('admin.finance-managers.index') }}">Finance Manager</a></li>
                    <li> <a href="{{ route('admin.content-managers.index') }}">Content Manager</a></li>
                    <li> <a href="{{ route('admin.user-managers.index') }}">User Manager</a></li>


                    @endif
                </ul>
            </li>

            @if(auth()->user()->hasPermission('credentials-view'))
            <li class="sidebar-list d-none"> <a class="sidebar-link" href="{{ route('admin.credentials.index') }}">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Password') }}"></use>
                    </svg>
                    <h6 class="f-w-600">Credentials</h6>
                </a>
            </li>
            @endif

            @if(auth()->user()->hasPermission('testimonials-view'))
            <li class="sidebar-list"> <a class="sidebar-link" href="{{ route('admin.testimonials.index') }}">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Chat') }}"></use>
                    </svg>
                    <h6 class="f-w-600">Testimonials</h6>
                </a>
            </li>
            @endif

            @if(auth()->user()->hasPermission('services-view'))
            <li class="sidebar-list"> <a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Category') }}"></use>
                    </svg>
                    <h6 class="f-w-600">Services</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    <li> <a href="{{ route('admin.services.index') }}">All Services</a></li>
                    @if(auth()->user()->hasPermission('master-data-view'))
                    <li> <a href="{{ route('admin.master-data.index', 'service_categories') }}">Service Categories</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if(auth()->user()->hasPermission('packages-view') || auth()->user()->hasPermission('other-fees-view'))
            <li class="sidebar-list"> <a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Wallet') }}"></use>
                    </svg>
                    <h6 class="f-w-600">Finance</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    @if(auth()->user()->hasPermission('packages-view'))
                    <li> <a href="{{ route('admin.packages.index') }}">Packages</a></li>
                    @endif
                    @if(auth()->user()->hasPermission('other-fees-view'))
                    <li> <a href="{{ route('admin.other-fees.index') }}">Other Fees</a></li>
                    @endif
                </ul>
            </li>
            @endif

            @if(auth()->user()->hasPermission('practitioner-reviews-view'))
            <li class="sidebar-list"> <a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#star') }}"></use>
                    </svg>
                    <h6 class="f-w-600">Reviews</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    <li> <a href="{{ route('admin.reviews.practitioners.index') }}">Practitioner</a></li>
                </ul>
            </li>
            @endif

            @if(auth()->user()->hasPermission('roles-view') || auth()->user()->hasPermission('settings-view') || auth()->user()->hasPermission('master-data-view'))
            <li class="sidebar-list"> <a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Setting') }}"></use>
                    </svg>
                    <h6 class="f-w-600">Master Settings</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    @if(auth()->user()->hasPermission('roles-view'))
                    <li> <a href="{{ route('admin.roles.index') }}">Roles</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('settings-view'))
                    <li> <a href="{{ route('admin.general-settings.index') }}">Site Settings</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('master-data-view'))
                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)"
                            style="letter-spacing: 0.5px;">
                            <span>Doctor Settings</span>
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'specializations') }}">Specializations</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'expertises') }}">Ayurveda Expertises</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'conditions') }}">Health Conditions</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'therapies') }}">External Therapies</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)"
                            style="letter-spacing: 0.5px;">
                            <span>Practitioner Settings</span>
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'wellness_consultations') }}">Wellness Consultations</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'body_therapies') }}">Massage & Body Therapies</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'practitioner_modalities') }}">Other Modalities</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)"
                            style="letter-spacing: 0.5px;">
                            <span>Client Settings</span>
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'client_consultation_preferences') }}">Consultation Preferences</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)"
                            style="letter-spacing: 0.5px;">
                            <span>Mindfulness Settings</span>
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'mindfulness_services') }}">Services Offered</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'client_concerns') }}">Client Concerns</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)"
                            style="letter-spacing: 0.5px;">
                            <span>Translator Settings</span>
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'translator_services') }}">Services Offered</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'translator_specializations') }}">Specializations</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)"
                            style="letter-spacing: 0.5px;">
                            <span>Yoga Therapist Settings</span>
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'yoga_expertises') }}">Expertise</a></li>
                        </ul>
                    </li>

                    <li>
                        <a href="{{ route('admin.countries.index') }}">Countries</a>
                    </li>
                    <li>
                        <a href="{{ route('admin.languages.index') }}">Languages</a>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if(auth()->user()->hasPermission('home-page-view') || auth()->user()->hasPermission('about-page-view') || auth()->user()->hasPermission('services-page-view') || auth()->user()->hasPermission('settings-view'))
            <li class="sidebar-list"> <a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Document') }}"></use>
                    </svg>
                    <h6 class="f-w-600">Page Settings</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    @if(auth()->user()->hasPermission('home-page-view'))
                    <li> <a href="{{ route('admin.homepage-settings.index') }}">Homepage Settings</a></li>
                    @endif
                    @if(auth()->user()->hasPermission('about-page-view'))
                    <li> <a href="{{ route('admin.about-settings.index') }}">About Us Settings</a></li>
                    @endif
                    @if(auth()->user()->hasPermission('services-page-view'))
                    <li> <a href="{{ route('admin.services-settings.index') }}">Services Page Settings</a></li>
                    @endif

                    @if(auth()->user()->hasPermission('settings-view'))
                    <li> <a href="{{ route('admin.contact-us.index') }}">Contact Us Settings</a></li>
                    @endif
                    <li> <a href="{{ route('admin.profile') }}">Admin Pannel Settings</a></li>
                    
                    <li> <a href="{{ route('admin.footer-settings.index') }}">Footer Page Settings</a></li>
                </ul>
            </li>
            @endif
            

            @if(auth()->user()->hasPermission('contact-messages-view'))
            <li class="sidebar-list">
                <a class="sidebar-link" href="{{ route('admin.contact-us.messages') }}">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Message') }}"></use>
                    </svg>
                    <h6 class="f-w-600">Contact Messages</h6>
                </a>
            </li>
            <li class="sidebar-list">
                <a class="sidebar-link" href="{{ route('admin.newsletters.index') }}">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Message') }}"></use>
                    </svg>
                    <h6 class="f-w-600">Newsletters</h6>
                </a>
            </li>
            @endif
        </ul>
    </div>
    <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
    <div class="sidebar-footer-image">
        <img src="{{ asset('assets/leaves/admin-leaf-img.webp') }}" alt="leafs">
    </div>
</aside>
