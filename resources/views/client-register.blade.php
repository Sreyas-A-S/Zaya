<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Registration - Zaya Wellness</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        /* Custom Gender Select Dropdown Styles */
        .custom-select-wrapper {
            position: relative;
            width: 100%;
        }

        .custom-select {
            cursor: pointer;
            position: relative;
        }

        .custom-select-trigger {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 24px;
            background: #F5F5F5;
            border-radius: 9999px;
            border: 1px solid transparent;
            transition: all 0.3s ease;
            color: #9CA3AF;
            font-size: 0.95rem;
        }

        .custom-select-trigger.has-value {
            color: #374151;
        }

        .custom-select-trigger:hover {
            border-color: #E5E7EB;
        }

        .custom-select-trigger .arrow {
            transition: transform 0.3s ease;
        }

        .custom-select.open .custom-select-trigger .arrow {
            transform: rotate(180deg);
        }

        .custom-options {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            z-index: 100;
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 8px;
        }

        .custom-select.open .custom-options {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .custom-option {
            padding: 14px 24px;
            cursor: pointer;
            transition: background 0.2s ease;
            color: #374151;
            font-size: 0.95rem;
        }

        .custom-option:hover {
            background: #F9FAFB;
        }

        .custom-option.selected {
            background: #FFF7EF;
            color: #97563D;
        }

        /* Floating Leaves Animation */
        .floating-leaf {
            position: absolute;
            pointer-events: none;
            z-index: 10;
        }

        .floating-leaf-1 {
            animation: float1 6s ease-in-out infinite;
        }

        .floating-leaf-2 {
            animation: float2 7s ease-in-out infinite;
        }

        .floating-leaf-3 {
            animation: float3 5s ease-in-out infinite;
        }

        @keyframes float1 {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-15px) rotate(5deg);
            }
        }

        @keyframes float2 {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-20px) rotate(-5deg);
            }
        }

        @keyframes float3 {

            0%,
            100% {
                transform: translateY(0) rotate(0deg);
            }

            50% {
                transform: translateY(-12px) rotate(3deg);
            }
        }

        /* Input Styles */
        .reg-input {
            width: 100%;
            padding: 14px 24px;
            background: #F5F5F5;
            border-radius: 9999px;
            border: 1px solid transparent;
            outline: none;
            font-size: 0.95rem;
            color: #374151;
            transition: all 0.3s ease;
        }

        .reg-input::placeholder {
            color: #9CA3AF;
        }

        .reg-input:focus {
            border-color: #97563D;
            background: white;
            box-shadow: 0 0 0 3px rgba(151, 86, 61, 0.1);
        }

        /* Date Input Custom Styles */
        .date-input-wrapper {
            position: relative;
        }

        .date-input-wrapper input[type="date"] {
            -webkit-appearance: none;
            appearance: none;
        }

        .date-input-wrapper input[type="date"]::-webkit-calendar-picker-indicator {
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.2s;
        }

        .date-input-wrapper input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }

        /* Country Select Styles */
        .country-select-wrapper {
            position: relative;
        }

        .country-select {
            padding-left: 60px !important;
        }

        .country-flag {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            gap: 6px;
            pointer-events: none;
        }

        /* Button Styles */
        .btn-create {
            background: #F5A623;
            color: #423131;
            padding: 14px 48px;
            border-radius: 9999px;
            font-weight: normal;
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-create:hover {
            background: #A87139;
            color: white;
            transform: translateY(-2px);
        }

        .btn-cancel {
            color: #594B4B;
            font-weight: normal;
            font-size: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
            background: transparent;
            border: none;
            padding: 14px 24px;
        }

        .btn-cancel:hover {
            color: #374151;
        }

        /* Toast Styles */
        .toast {
            visibility: hidden;
            min-width: 250px;
            margin-left: -125px;
            background-color: #333;
            color: #fff;
            text-align: center;
            border-radius: 9999px;
            padding: 16px;
            position: fixed;
            z-index: 9999;
            left: 50%;
            bottom: 30px;
            font-size: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transform: translateY(20px);
            opacity: 0;
            transition: all 0.3s ease-in-out;
        }

        .toast.show {
            visibility: visible;
            transform: translateY(0);
            opacity: 1;
        }

        .toast.success {
            background-color: #48BB78;
        }

        .toast.error {
            background-color: #F56565;
        }
    </style>
</head>

<body class="bg-white min-h-screen flex flex-col">
    <!-- Main Content -->
    <div class="flex-1 relative overflow-x-hidden">
        <!-- Floating Leaves -->
        <img src="{{ asset('frontend/assets/reg-floating-img-01.png') }}"
            alt="Decorative Leaf"
            class="floating-leaf floating-leaf-2 w-14 md:w-16 lg:w-20 right-4 md:right-12 lg:right-20 top-16 md:top-20">

        <img src="{{ asset('frontend/assets/reg-floating-img-02.png') }}"
            alt="Decorative Leaf"
            class="floating-leaf floating-leaf-1 w-16 md:w-20 lg:w-24 -left-2 md:left-0 top-40 md:top-52">

        <img src="{{ asset('frontend/assets/reg-floating-img-03.png') }}"
            alt="Decorative Leaf"
            class="floating-leaf floating-leaf-3 w-20 md:w-28 lg:w-36 right-0 bottom-32 md:bottom-40">

        <div class="container mx-auto px-4 py-8 md:py-12 lg:py-16">
            <!-- Header -->
            <div class="text-center mb-8 md:mb-16">
                <p class="text-[#424F93] font-regular text-base md:text-lg mb-2">Create Account</p>
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-sans! font-medium text-gray-900">Client Registration Form</h1>
            </div>

            <!-- Toast Container -->
            <div id="toast-container"></div>

            <form action="{{ route('register') }}" method="POST" id="registration-form" class="max-w-5xl mx-auto">
                @csrf
                <input type="hidden" name="role" value="client">

                <!-- Row 1: Name Fields -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">First Name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                            class="reg-input @error('first_name') border-red-500! @enderror"
                            placeholder="Enter First Name" required>
                        @error('first_name')
                        <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Middle Name</label>
                        <input type="text" name="middle_name" value="{{ old('middle_name') }}"
                            class="reg-input"
                            placeholder="Enter Middle Name">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Last Name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                            class="reg-input @error('last_name') border-red-500! @enderror"
                            placeholder="Enter Last Name" required>
                        @error('last_name')
                        <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Row 2: DOB, Age, Gender -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Date of Birth</label>
                        <div class="date-input-wrapper">
                            <input type="date" name="dob" value="{{ old('dob') }}"
                                id="dob-input"
                                class="reg-input @error('dob') border-red-500! @enderror"
                                placeholder="DOB" required>
                        </div>
                        @error('dob')
                        <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Age</label>
                        <input type="number" name="age" id="age-input" value="{{ old('age') }}"
                            class="reg-input bg-gray-100 cursor-not-allowed"
                            placeholder="Age" readonly>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Gender</label>
                        <div class="custom-select-wrapper">
                            <div class="custom-select" id="gender-select">
                                <div class="custom-select-trigger">
                                    <span id="gender-selected">Select Gender</span>
                                    <i class="ri-arrow-down-s-line arrow text-gray-400"></i>
                                </div>
                                <div class="custom-options">
                                    <div class="custom-option" data-value="male">Male</div>
                                    <div class="custom-option" data-value="female">Female</div>
                                    <div class="custom-option" data-value="transgender">Transgender</div>
                                </div>
                            </div>
                            <input type="hidden" name="gender" id="gender-input" value="{{ old('gender') }}" required>
                        </div>
                        @error('gender')
                        <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Row 3: Email, Mobile -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="reg-input @error('email') border-red-500! @enderror"
                            placeholder="Enter Email" required>
                        @error('email')
                        <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Mobile No.</label>
                        <input type="tel" name="mobile" value="{{ old('mobile') }}"
                            class="reg-input @error('mobile') border-red-500! @enderror"
                            placeholder="Enter Mobile No." required>
                        @error('mobile')
                        <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Row 4: Address Lines -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Address line 1</label>
                        <input type="text" name="address_line_1" value="{{ old('address_line_1') }}"
                            class="reg-input @error('address_line_1') border-red-500! @enderror"
                            placeholder="Enter Address line 1" required>
                        @error('address_line_1')
                        <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Address line 2</label>
                        <input type="text" name="address_line_2" value="{{ old('address_line_2') }}"
                            class="reg-input"
                            placeholder="Enter Address line 2">
                    </div>
                </div>

                <!-- Row 5: City, State, Country -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">City</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                            class="reg-input @error('city') border-red-500! @enderror"
                            placeholder="Enter City" required>
                        @error('city')
                        <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">State</label>
                        <input type="text" name="state" value="{{ old('state') }}"
                            class="reg-input @error('state') border-red-500! @enderror"
                            placeholder="Enter State" required>
                        @error('state')
                        <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Country</label>
                        <div class="country-select-wrapper custom-select relative" id="country-select-container">
                            <span class="country-flag">
                                <span id="current-country-flag" class="text-lg">🇮🇳</span>
                                <i class="ri-arrow-down-s-line text-gray-400 text-sm"></i>
                            </span>
                            <input type="text" name="country" id="country-input" value="{{ old('country', 'India') }}"
                                class="reg-input country-select cursor-pointer"
                                placeholder="Select Country" required autocomplete="off" onkeyup="filterCountries()">

                            <div class="custom-options" id="country-options" style="max-height: 250px; overflow-y: auto;">
                                @foreach(config('countries') as $code => $name)
                                @php
                                $flag = implode('', array_map(fn($char) => mb_chr(ord($char) + 127397), str_split($code)));
                                @endphp
                                <div class="custom-option country-option" data-value="{{ $name }}" data-flag="{{ $flag }}">
                                    <span class="mr-2">{{ $flag }}</span> {{ $name }}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @error('country')
                        <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- Password Fields (Hidden but required for registration) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-10 mb-10">
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password"
                                class="reg-input @error('password') border-red-500! @enderror"
                                placeholder="Enter Password" required>
                            <button type="button" onclick="togglePassword('password')"
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="ri-eye-line" id="password-icon"></i>
                            </button>
                        </div>
                        @error('password')
                        <span class="text-red-500 text-xs mt-1 pl-4 block">{{ $message }}</span>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-medium mb-5 text-sm md:text-base">Confirm Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="reg-input"
                                placeholder="Confirm Password" required>
                            <button type="button" onclick="togglePassword('password_confirmation')"
                                class="absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="ri-eye-line" id="password_confirmation-icon"></i>
                            </button>
                        </div>
                        <span id="password-match-error" class="text-red-500 text-xs mt-1 pl-4 block h-4"></span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer with Buttons -->
    <footer class="bg-[#FFF3D4] py-6 mt-auto">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto flex items-center justify-end gap-4 md:gap-8">
                <a href="{{ route('zaya-login') }}" class="btn-cancel">Cancel</a>
                <button type="submit" form="registration-form" class="btn-create">Create Account</button>
            </div>
        </div>
    </footer>

    <script>
        // Custom Select Logic (Generic)
        document.addEventListener('DOMContentLoaded', function() {
            function setupCustomSelect(selectId, inputId, selectedId, flagId = null) {
                const select = document.getElementById(selectId);
                const input = document.getElementById(inputId);
                const selectedText = document.getElementById(selectedId);
                const flagIcon = flagId ? document.getElementById(flagId) : null;

                if (!select) return;

                const trigger = select.querySelector('.custom-select-trigger');
                const options = select.querySelectorAll('.custom-option');

                // Toggle dropdown
                trigger.addEventListener('click', function() {
                    // Close other open selects first
                    document.querySelectorAll('.custom-select').forEach(s => {
                        if (s !== select) s.classList.remove('open');
                    });
                    select.classList.toggle('open');
                });

                // Select option
                options.forEach(option => {
                    option.addEventListener('click', function() {
                        const value = this.getAttribute('data-value');
                        const text = this.innerText; // Gets text including flag if present inline, but we want structured
                        // Actually for Country, text includes flag. For Gender it doesn't.
                        // Let's rely on data attributes if possible, or clean text.

                        // For display in trigger:
                        const displayFlag = this.getAttribute('data-flag');
                        // Clean text (remove flag if it was in innerText) - simpler to just use textContent and trim?
                        // The loop has <span class="mr-2">{{ $flag }}</span> {{ $name }}
                        // So textContent has both. 
                        // For the input value, use data-value.

                        const displayText = this.childNodes[this.childNodes.length - 1].textContent.trim();

                        input.value = value;
                        selectedText.textContent = displayText || value; // Fallback

                        if (flagIcon && displayFlag) {
                            flagIcon.textContent = displayFlag;
                            selectedText.textContent = value; // Show name only in trigger (flag is in overlay)
                        } else {
                            // Normal behavior (e.g. Gender)
                            input.value = value;
                            selectedText.textContent = displayText || value;
                        }

                        trigger.classList.add('has-value');

                        // Remove selected from all, add to current
                        options.forEach(opt => opt.classList.remove('selected'));
                        this.classList.add('selected');

                        select.classList.remove('open');
                    });
                });

                // Set initial value if exists
                const initialValue = input.value;
                if (initialValue) {
                    const matchingOption = Array.from(options).find(opt => opt.getAttribute('data-value') === initialValue);
                    if (matchingOption) {
                        matchingOption.click();
                    }
                }
            }

            // Initialize Gender Select (Still uses generic Logic)
            setupCustomSelect('gender-select', 'gender-input', 'gender-selected');

            // Country Select Logic (Specific for new structure)
            const countryInput = document.getElementById('country-input');
            const countryContainer = document.getElementById('country-select-container');
            const countryOptionsContainer = document.getElementById('country-options');
            const currentCountryFlag = document.getElementById('current-country-flag');
            const countryOptions = document.querySelectorAll('.country-option');

            // Toggle Dropdown
            function toggleCountryDropdown(e) {
                e.stopPropagation();
                // Close others
                document.querySelectorAll('.custom-select').forEach(s => s.classList.remove('open'));

                countryContainer.classList.toggle('open');
            }

            countryInput.addEventListener('click', toggleCountryDropdown);
            countryContainer.querySelector('.country-flag').addEventListener('click', toggleCountryDropdown);

            // Handle Option Click
            countryOptions.forEach(option => {
                option.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const value = this.getAttribute('data-value');
                    const flag = this.getAttribute('data-flag');

                    countryInput.value = value;
                    currentCountryFlag.textContent = flag;

                    countryContainer.classList.remove('open');
                });
            });

            // Close on outside click
            document.addEventListener('click', function(e) {
                if (!e.target.closest('#country-select-container')) {
                    countryContainer.classList.remove('open');
                }
                if (!e.target.closest('.custom-select')) {
                    document.querySelectorAll('.custom-select').forEach(s => s.classList.remove('open'));
                }
            });
        });

        // Filter Countries Function
        function filterCountries() {
            const input = document.getElementById('country-input');
            const filter = input.value.toUpperCase();
            const container = document.getElementById('country-select-container');
            const options = container.getElementsByClassName('country-option');

            // Open dropdown when typing
            if (!container.classList.contains('open')) {
                container.classList.add('open');
            }

            for (let i = 0; i < options.length; i++) {
                const txtValue = options[i].textContent || options[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    options[i].style.display = "";
                } else {
                    options[i].style.display = "none";
                }
            }
        }

        // Calculate Age from DOB
        document.getElementById('dob-input').addEventListener('change', function() {
            const dob = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const monthDiff = today.getMonth() - dob.getMonth();

            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            document.getElementById('age-input').value = age > 0 ? age : '';
        });

        // Toggle Password Visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            } else {
                field.type = 'password';
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            }
        }

        // Password Match Validation
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('password_confirmation');
        const matchError = document.getElementById('password-match-error');

        function checkPasswordMatch() {
            if (confirmPasswordInput.value === '') {
                matchError.textContent = '';
                confirmPasswordInput.classList.remove('border-red-500!');
                return;
            }

            if (passwordInput.value !== confirmPasswordInput.value) {
                matchError.textContent = 'Passwords do not match';
                confirmPasswordInput.classList.add('border-red-500!');
            } else {
                matchError.textContent = '';
                confirmPasswordInput.classList.remove('border-red-500!');
            }
        }

        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        // Form Submit Validation
        const registrationForm = document.getElementById('registration-form');
        registrationForm.addEventListener('submit', function(e) {
            if (passwordInput.value !== confirmPasswordInput.value) {
                e.preventDefault();
                checkPasswordMatch();
                confirmPasswordInput.focus();
            }
        });
    </script>
</body>

</html>