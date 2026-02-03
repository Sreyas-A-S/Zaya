<aside class="page-sidebar">
    <div class="logo-wrapper d-flex align-items-center gap-2 p-3 d-lg-none">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none">
            <img src="{{ asset('admiro/assets/images/logo/zaya-logo-admin.svg') }}" alt="logo" style="width: 40px;">
            <h4 class="mb-0 d-none text-white f-w-700">ZAYA</h4>
        </a>
        <a class="close-btn toggle-sidebar ms-auto" href="javascript:void(0)">
            <svg class="svg-color">
                <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Category') }}"></use>
            </svg>
        </a>
    </div>


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
            <li class="sidebar-list"> <a class="sidebar-link" href="{{ route('admin.services.index') }}">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Category') }}"></use>
                    </svg>
                    <h6 class="f-w-600">Services</h6>
                </a>
            </li>
            @endif

            @if(auth()->user()->hasPermission('practitioner-reviews-view'))
            <li class="sidebar-list"> <a class="sidebar-link" href="javascript:void(0)">
                    <svg class="stroke-icon">
                        <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Star') }}"></use>
                    </svg>
                    <h6 class="f-w-600">Reviews</h6><i class="iconly-Arrow-Right-2 icli"> </i>
                </a>
                <ul class="sidebar-submenu">
                    <li> <a href="{{ route('admin.reviews.practitioners.index') }}">Practitioner</a></li>
                </ul>
            </li>
            @endif

            @if(auth()->user()->hasPermission('roles-view') || auth()->user()->hasPermission('master-data-view'))
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

                    @if(auth()->user()->hasPermission('master-data-view'))
                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)" style="letter-spacing: 0.5px;">
                            Doctor Settings
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
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)" style="letter-spacing: 0.5px;">
                            Practitioner Settings
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'wellness_consultations') }}">Wellness Consultations</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'body_therapies') }}">Massage & Body Therapies</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'practitioner_modalities') }}">Other Modalities</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)" style="letter-spacing: 0.5px;">
                            Client Settings
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'client_consultation_preferences') }}">Consultation Preferences</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)" style="letter-spacing: 0.5px;">
                            Mindfulness Settings
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'mindfulness_services') }}">Services Offered</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'client_concerns') }}">Client Concerns</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)" style="letter-spacing: 0.5px;">
                            Translator Settings
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'translator_services') }}">Services Offered</a></li>
                            <li><a href="{{ route('admin.master-data.index', 'translator_specializations') }}">Specializations</a></li>
                        </ul>
                    </li>

                    <li>
                        <a class="submenu-title mt-2 d-flex justify-content-between" href="javascript:void(0)" style="letter-spacing: 0.5px;">
                            Yoga Therapist Settings
                            <div class="according-menu"><i class="fa fa-angle-right"></i></div>
                        </a>
                        <ul class="according-submenu ps-3" style="display: none;">
                            <li><a href="{{ route('admin.master-data.index', 'yoga_expertises') }}">Expertise</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </li>
            @endif

            @if(auth()->user()->hasPermission('home-page-view') || auth()->user()->hasPermission('about-page-view') || auth()->user()->hasPermission('services-page-view'))
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
                    <li class="d-none"> <a href="{{ route('admin.about-settings.index') }}">About Us Settings</a></li>
                    @endif
                    @if(auth()->user()->hasPermission('services-page-view'))
                    <li> <a href="{{ route('admin.services-settings.index') }}">Services Page Settings</a></li>
                    @endif
                </ul>
            </li>
            @endif
        </ul>
    </div>
    <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
</aside>