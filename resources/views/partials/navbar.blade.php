<header class="page-header row">
    <style>
        .language-menu-list .lang {
            padding: 8px 12px !important;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .language-menu-list .lang:hover {
            background-color: #f4f4f4 !important;
            color: #97563D !important;
        }
        .language-menu-list .lang.active {
            background-color: #f0f0f0;
        }
    </style>
    <div class="logo-wrapper d-flex align-items-center col-auto">
        <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none me-3">
            <img src="{{ asset('admiro/assets/images/logo/zaya-logo-admin.svg') }}" alt="logo" style="width: 60px;">
        </a>
        <a class="close-btn toggle-sidebar" href="javascript:void(0)">
            <svg class="svg-color">
                <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Category') }}"></use>
            </svg>
        </a>
    </div>
    <div class="logo-icon-wrapper col-auto px-0 d-none">
    </div>
    <div class="page-main-header col">
        <div class="header-left">
        </div>
        <div class="nav-right">
            <ul class="header-right">
                <li class="custom-dropdown">
                    @php
                    $allLanguages = \App\Models\Language::all();
                    $user = auth()->user();
                    $role = $user->roleData();
                    
                    if ($role && $role->name === 'Super Admin') {
                        $languages = $allLanguages;
                    } else {
                        $assignedIds = $user->languages;
                        if (is_array($assignedIds)) {
                            $languages = $allLanguages->whereIn('id', $assignedIds);
                        } elseif ($assignedIds) {
                            $languages = $allLanguages->where('id', $assignedIds);
                        } else {
                            $languages = collect();
                        }
                    }

                    // Filter unique base languages by Name (e.g., only one "English" even if regional variants exist)
                    $languages = $languages->groupBy('name')->map(function($group) {
                        // Prefer the shortest code (e.g., 'en' over 'en-GB')
                        return $group->sortBy(fn($l) => strlen($l->code))->first();
                    });

                    if (!function_exists('getCountryCode')) {
                        function getCountryCode($lang) {
                            if (!$lang) return 'us';
                            
                            // Map language codes to country codes for flags
                            $mapping = [
                                'en' => 'us', 'ar' => 'sa', 'zh' => 'cn', 'ja' => 'jp',
                                'ko' => 'kr', 'hi' => 'in', 'bn' => 'bd', 'uk' => 'ua',
                                'en-GB' => 'gb', 'en-US' => 'us', 'en-AU' => 'au', 'en-CA' => 'ca',
                                'fr-FR' => 'fr', 'fr-CA' => 'ca', 'es-ES' => 'es', 'es-US' => 'us',
                                'pt-PT' => 'pt', 'pt-BR' => 'br',
                                'el' => 'gr', // Greek -> Greece
                                'hy' => 'am', // Armenian -> Armenia
                                'cs' => 'cz', // Czech -> Czech Republic
                                'sq' => 'al', // Albanian -> Albania
                                'af' => 'za', // Afrikaans -> South Africa
                                'am' => 'et', // Amharic -> Ethiopia
                                'sv' => 'se', // Swedish -> Sweden
                                'da' => 'dk', // Danish -> Denmark
                                'nb' => 'no', // Norwegian -> Norway
                                'nn' => 'no', // Norwegian -> Norway
                                'no' => 'no', // Norwegian -> Norway
                                'he' => 'il', // Hebrew -> Israel
                                'fa' => 'ir', // Persian -> Iran
                                'ms' => 'my', // Malay -> Malaysia
                                'vi' => 'vn', // Vietnamese -> Vietnam
                                'th' => 'th', // Thailand
                            ];

                            $code = $lang->code;
                            if (isset($mapping[$code])) return $mapping[$code];
                            
                            $base = explode('-', $code)[0];
                            if (isset($mapping[$base])) return $mapping[$base];

                            // If mapping fails, try converting emoji if available
                            if ($lang->flag) {
                                $emojiIso = emojiToISO($lang->flag);
                                if ($emojiIso && $emojiIso !== 'us') return $emojiIso;
                            }

                            return strtolower($base);
                        }
                    }

                    if (!function_exists('emojiToISO')) {
                        function emojiToISO($emoji) {
                            if (!$emoji) return 'us';
                            $chars = preg_split('//u', $emoji, -1, PREG_SPLIT_NO_EMPTY);
                            $iso = '';
                            foreach ($chars as $char) {
                                $code = mb_ord($char, 'UTF-8') - 127397;
                                if ($code >= 65 && $code <= 90) {
                                    $iso .= chr($code);
                                }
                            }
                            return strtolower($iso) ?: 'us';
                        }
                    }
                    $currentLocale = session('locale', config('app.locale', 'en'));
                    $currentLanguage = $allLanguages->where('code', $currentLocale)->first();
                    @endphp

                    <a class="lang lang-dropdown-trigger" href="javascript:void(0)" style="min-width: 80px; display: flex; align-items: center; text-decoration: none; padding: 10px 0;">
                        <img src="{{ asset('admiro/assets/fonts/flag-icon/' . ($currentLanguage ? getCountryCode($currentLanguage) : 'us') . '.svg') }}" style="width: 20px; height: 15px; margin-right: 8px; border: 1px solid #eee;" alt="flag">
                        <h6 class="lang-txt f-w-700 mb-0" style="color: #2b2b2b;">{{ $currentLanguage ? strtoupper($currentLanguage->code) : 'ENG' }}</h6>
                    </a>

                    <div class="custom-menu overflow-hidden">
                        <ul class="profile-body language-menu-list" style="max-height: 350px; overflow-y: auto; padding: 10px;">
                            @foreach($languages as $lang)
                            <li class="d-flex align-items-center last-0" style="cursor: pointer;">
                                <a href="javascript:void(0)" 
                                   class="lang d-flex align-items-center w-100 {{ $currentLocale == $lang->code ? 'active text-primary' : '' }}" 
                                   data-value="{{ $lang->code }}"
                                   data-flag="{{ asset('admiro/assets/fonts/flag-icon/' . getCountryCode($lang) . '.svg') }}"
                                   onclick="changeLanguage(this)"
                                   style="text-decoration: none; color: inherit;">
                                    <img src="{{ asset('admiro/assets/fonts/flag-icon/' . getCountryCode($lang) . '.svg') }}" style="width: 18px; height: 13px; margin-right: 10px; border: 1px solid #f0f0f0;" alt="flag">
                                    <span class="f-w-600">{{ strtoupper($lang->name) }}</span>
                                    @if($currentLocale == $lang->code)
                                        <i class="fa fa-check ms-auto text-primary" style="font-size: 10px;"></i>
                                    @endif
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
                <li class="custom-dropdown"><a href="javascript:void(0)">
                        <svg>
                            <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#notification') }}"></use>
                        </svg></a><span class="badge rounded-pill badge-primary">4</span>
                    <div class="custom-menu notification-dropdown py-0 overflow-hidden">
                        <h3 class="title bg-primary-light dropdown-title">Notification <span class="font-primary">View all</span></h3>
                        <ul class="activity-timeline">
                            <li class="d-flex align-items-start">
                                <div class="activity-line"></div>
                                <div class="activity-dot-primary"></div>
                                <div class="flex-grow-1">
                                    <h6 class="f-w-600 font-primary">30-04-2024<span>Today</span><span class="circle-dot-primary float-end">
                                            <svg class="circle-color">
                                                <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#circle') }}"></use>
                                            </svg></span></h6>
                                    <h5>Alice Goodwin</h5>
                                    <p class="mb-0">Fashion should be fun. It shouldn't be labelled intellectual.</p>
                                </div>
                            </li>
                            <li class="d-flex align-items-start">
                                <div class="activity-dot-secondary"></div>
                                <div class="flex-grow-1">
                                    <h6 class="f-w-600 font-secondary">28-06-2024<span>1 hour ago</span><span class="float-end circle-dot-secondary">
                                            <svg class="circle-color">
                                                <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#circle') }}"></use>
                                            </svg></span></h6>
                                    <h5>Herry Venter</h5>
                                    <p>I am convinced that there can be luxury in simplicity.</p>
                                </div>
                            </li>
                            <li class="d-flex align-items-start">
                                <div class="activity-dot-primary"></div>
                                <div class="flex-grow-1">
                                    <h6 class="f-w-600 font-primary">04-08-2024<span>Today</span><span class="float-end circle-dot-primary">
                                            <svg class="circle-color">
                                                <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#circle') }}"></use>
                                            </svg></span></h6>
                                    <h5>Loain Deo</h5>
                                    <p>I feel that things happen for open new opportunities.</p>
                                </div>
                            </li>
                            <li class="d-flex align-items-start">
                                <div class="activity-dot-secondary"></div>
                                <div class="flex-grow-1">
                                    <h6 class="f-w-600 font-secondary">12-11-2024<span>Yesterday</span><span class="float-end circle-dot-secondary">
                                            <svg class="circle-color">
                                                <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#circle') }}"></use>
                                            </svg></span></h6>
                                    <h5>Fenter Jessy</h5>
                                    <p>Sometimes the simplest things are the most profound.</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="profile-nav custom-dropdown">
                    <div class="user-wrap">
                        <div class="user-img"><img src="{{ asset('admiro/assets/images/profile.png') }}" alt="user" /></div>
                        <div class="user-content">
                            <h6>{{ auth()->user()->name ?? 'Guest' }}</h6>
                            <p class="mb-0 text-capitalize">{{ auth()->user()->role ?? 'User' }}<i class="fa-solid fa-chevron-down"></i></p>
                        </div>
                    </div>
                    <div class="custom-menu overflow-hidden">
                        <ul class="profile-body">
                            <li class="d-flex">
                                <svg class="svg-color">
                                    <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Profile') }}"></use>
                                </svg><a class="ms-2" href="{{ route('admin.dashboard') }}">Profile</a>
                            </li>
                            <li class="d-flex">
                                <svg class="svg-color">
                                    <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Login') }}"></use>
                                </svg>
                                <a class="ms-2" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Log Out</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>
<script>
    // Sync localStorage with current session locale
    localStorage.setItem('selectedLanguage', '{{ $currentLocale }}');

    function changeLanguage(element) {
        let id = element.getAttribute("data-value");
        let flagUrl = element.getAttribute("data-flag");
        if (!id) return;

        const langName = id.toUpperCase();

        fetch(`{{ url('admin/change-language') }}/${id}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    // 1. Update Navbar UI Immediately
                    const currentLangImg = document.querySelector('.lang-dropdown-trigger img');
                    const currentLangText = document.querySelector('.lang-dropdown-trigger .lang-txt');
                    
                    if (currentLangImg && flagUrl) currentLangImg.src = flagUrl;
                    if (currentLangText) currentLangText.textContent = langName;

                    // 2. Update localStorage
                    localStorage.setItem('selectedLanguage', id);

                    // 3. Update active state in dropdown
                    document.querySelectorAll('.language-menu-list .lang').forEach(el => {
                        el.classList.remove('active', 'text-primary');
                        const checkIcon = el.querySelector('.fa-check');
                        if (checkIcon) checkIcon.remove();
                    });
                    
                    element.classList.add('active', 'text-primary');
                    element.insertAdjacentHTML('beforeend', '<i class="fa fa-check ms-auto text-primary" style="font-size: 10px;"></i>');

                    // 4. DYNAMIC FIELD UPDATES:
                    // If we are on a settings page, clear all relevant inputs first
                    // then populate with available data.
                    const settingsForm = document.querySelector('form[id*="SettingsForm"]');
                    if (settingsForm) {
                        const inputs = settingsForm.querySelectorAll('input[type="text"], textarea');
                        inputs.forEach(input => {
                            // Clear existing values
                            input.value = '';
                            
                            // If data exists for this key, fill it
                            if (data.data && data.data[input.name] !== undefined) {
                                input.value = data.data[input.name];
                            }
                        });

                        // Special handling for images: hide previews or update them
                        const imagePreviews = settingsForm.querySelectorAll('.img-thumbnail');
                        imagePreviews.forEach(preview => {
                            // Find corresponding data (this is trickier as image keys vary)
                            // For now, let's keep images as they are or mark them as "Needs Update"
                            // If data has the key, we could try to update SRC but usually images 
                            // need proper path handling.
                        });
                        
                        if (typeof showToast === 'function') {
                            showToast(`Switched to ${langName} successfully.`);
                        }
                    } else {
                        // If not on a settings page, a reload might still be needed 
                        // to translate the whole UI (sidebar, etc.)
                        location.reload();
                    }
                    
                    console.log("Language changed dynamically to:", id);
                } else {
                    console.warn(data.message);
                }
            })
            .catch(error => console.error('Error changing language:', error));
    }

    document.querySelectorAll(".lang").forEach(function(element) {
        element.addEventListener("click", function(e) {
            if (this.hasAttribute("data-value")) {
                e.preventDefault();
                changeLanguage(this);
            }
        });
    });
</script>
