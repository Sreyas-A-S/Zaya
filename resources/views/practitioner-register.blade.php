<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('frontend/assets/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('frontend/assets/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('frontend/assets/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/assets/apple-touch-icon.png') }}" />
    <meta name="apple-mobile-web-app-title" content="Zaya Wellness" />
    <link rel="manifest" href="{{ asset('frontend/assets/site.webmanifest') }}">
    <title>Practitioner Registration - Zaya Wellness</title>
    @vite(['resources/css/app.css', 'resources/css/practitioner-register.css', 'resources/js/app.js', 'resources/js/country-selector.js'])
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/gh/lipis/flag-icons@7.0.0/css/flag-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.9.1/fonts/remixicon.css" rel="stylesheet">
</head>

<body class="bg-white min-h-screen flex flex-col">
    <!-- Main Content -->
    <div class="flex-1 relative">
        <div class="container mx-auto px-4 py-8 md:py-12 lg:py-16">
            <!-- Header -->
            <div class="text-center mb-8 md:mb-12">
                <h1 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold text-primary mb-6">Elevate Your
                    Practice. Join the ZAYA Collective</h1>
                <p class="text-gray-500 text-sm md:text-base max-w-2xl mx-auto">
                    Become a part of a specialized ecosystem where tradition meets technology.
                    Complete your registration to showcase your expertise, manage your global
                    clientele and help us redefine holistic wellness.
                </p>
            </div>

            <!-- Form Title -->
            <h2 class="text-xl md:text-2xl font-sans! font-medium text-center text-gray-900 mb-8">Practitioner
                Registration Form</h2>

            <!-- Step Indicator -->
            <div class="sticky top-0 z-50 bg-white flex justify-center pb-6 pt-8 mb-20 border-b border-[#D0D0D0]">
                <div class="flex items-start justify-center gap-0" id="step-indicator">
                    <div class="flex flex-col items-center relative z-[2]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm lg:text-base transition-all duration-300 bg-[#60E48C] text-white"
                            id="step-circle-1">1</div>
                        <span class="text-sm lg:text-base text-gray-700 mt-2.5 font-normal whitespace-nowrap"
                            id="step-label-1">Basic Details</span>
                    </div>
                    <div class="w-[60px] md:w-[100px] xl:w-[140px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center -mt-7 relative"
                        id="step-line-1"></div>
                    <div class="flex flex-col items-center relative z-[2]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm lg:text-base transition-all duration-300 bg-[#E6E6E6] text-[#8B8B8B]"
                            id="step-circle-2">2</div>
                        <span class="text-sm lg:text-base text-gray-400 mt-2.5 font-normal whitespace-nowrap"
                            id="step-label-2">Qualifications</span>
                    </div>
                    <div class="w-[60px] md:w-[100px] xl:w-[140px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center -mt-7 relative"
                        id="step-line-2"></div>
                    <div class="flex flex-col items-center relative z-[2]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-sm lg:text-base transition-all duration-300 bg-[#E6E6E6] text-[#8B8B8B]"
                            id="step-circle-3">3</div>
                        <span class="text-sm lg:text-base text-gray-400 mt-2.5 font-normal whitespace-nowrap"
                            id="step-label-3">Verification</span>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="max-w-5xl mx-auto"
                id="practitioner-form">
                @csrf
                <input type="hidden" name="type" value="practitioner">

                <!-- Tab 1: Basic Details -->
                <div class="block" id="tab-1">
                    <h3 class="text-2xl font-sans! font-normal text-gray-900 mb-10">Basic Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Fullname & Photo Row -->
                        <div class="order-last md:order-first">
                            <label class="block text-gray-700 font-normal mb-4 text-lg">Fullname</label>
                            <input type="text" name="fullname" value="{{ old('fullname') }}"
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="Enter Fullname" required>
                        </div>
                        <div class="flex flex-col items-center order-first md:order-last">
                            <label
                                class="w-20 h-20 rounded-full bg-[#F5A623] flex items-center justify-center cursor-pointer transition-all duration-300 hover:bg-[#E09518] hover:scale-105"
                                for="profile-photo">
                                <i class="ri-camera-4-fill text-white text-2xl"></i>
                            </label>
                            <input type="file" id="profile-photo" name="profile_photo" accept="image/*" class="hidden">
                            <span class="text-gray-500 text-sm mt-2">Add Photo</span>
                        </div>
                    </div>

                    <!-- Gender -->
                    <div class="mb-8">
                        <label class="block text-gray-700 font-normal mb-4 text-lg">Gender</label>
                        <div class="flex flex-wrap gap-6">
                            <label class="gender-radio flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="male">
                                <span class="text-gray-700">Male</span>
                            </label>
                            <label class="gender-radio flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="female" checked>
                                <span class="text-gray-700">Female</span>
                            </label>
                            <label class="gender-radio flex items-center gap-2 cursor-pointer">
                                <input type="radio" name="gender" value="others">
                                <span class="text-gray-700">Others</span>
                            </label>
                        </div>
                    </div>

                    <!-- Email & Mobile -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="Enter Email" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">Mobile No.</label>
                            <input type="tel" name="mobile" value="{{ old('mobile') }}"
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="Enter Mobile No." required>
                        </div>
                    </div>

                    <!-- DOB & Nationality -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">DOB</label>
                            <input type="date" name="dob" value="{{ old('dob') }}"
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="DD/MM/YYYY" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">Nationality</label>
                            <select id="nationality-select" name="nationality"
                                data-default="{{ old('nationality', 'IN') }}" required>
                                <option value="">Select Country</option>
                            </select>
                        </div>
                    </div>

                    <!-- Address & Website -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">Residential Address</label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="Address with Zipcode" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">Website <span
                                    class="text-gray-400 italic">(if any)</span></label>
                            <input type="url" name="website" value="{{ old('website') }}"
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="Enter URL">
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Qualifications -->
                <div class="hidden" id="tab-2">
                    <!-- Education Section -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-sans! font-medium text-gray-900">Education</h3>
                        <button type="button"
                            class="text-secondary text-lg font-medium hover:text-primary flex items-center gap-1 cursor-pointer bg-transparent border-none"
                            onclick="addEducation()">
                            <i class="ri-add-line text-lg"></i> Add Another Education
                        </button>
                    </div>

                    <div class="bg-[#F6F6F6] rounded-[24px] py-10 px-6 md:px-12 mb-8" id="education-section">
                        <!-- Row 1: Education Type, Institution Name, Duration -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                            <div>
                                <label class="block text-[#525252] text-lg font-normal mb-3">Education
                                    Type</label>
                                <div class="custom-select-wrapper w-full">
                                    <div class="custom-select" id="education-type-select-0">
                                        <div class="custom-select-trigger cursor-pointer">
                                            <span id="education-type-selected-0">Select</span>
                                            <i class="ri-arrow-down-s-line arrow text-gray-400"></i>
                                        </div>
                                        <div class="custom-options">
                                            <div class="custom-option" data-value="degree">Degree</div>
                                            <div class="custom-option" data-value="diploma">Diploma</div>
                                            <div class="custom-option" data-value="certification">Certification</div>
                                        </div>
                                    </div>
                                    <input type="hidden" name="education[0][type]" id="education-type-input-0" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-[#525252] text-lg font-normal mb-3">Institution
                                    Name</label>
                                <input type="text" name="education[0][institution]"
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="Enter Institution Name">
                            </div>
                            <div>
                                <label class="block text-[#525252] text-lg font-normal mb-3">Duration <span
                                        class="italic text-[#737373] text-[1rem] font-normal">(Hours/Years)</span></label>
                                <input type="text" name="education[0][duration]"
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="Enter Duration">
                            </div>
                        </div>

                        <!-- Row 2: Address Line 1, Address Line 2 -->
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-[#525252] text-lg font-normal mb-3">Address Line
                                    1</label>
                                <input type="text" name="education[0][address_line_1]"
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="Address Line 1">
                            </div>
                            <div>
                                <label class="block text-[#525252] text-lg font-normal mb-3">Address Line
                                    2</label>
                                <input type="text" name="education[0][address_line_2]"
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="Address Line 2">
                            </div>
                        </div>

                        <!-- Row 3: City, State, Country -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-12">
                            <div>
                                <label class="block text-[#525252] text-lg font-normal mb-3">City</label>
                                <input type="text" name="education[0][city]"
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="Enter City">
                            </div>
                            <div>
                                <label class="block text-[#525252] text-lg font-normal mb-3">State</label>
                                <input type="text" name="education[0][state]"
                                    class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="Enter State">
                            </div>
                            <div class="education-country-wrapper">
                                <label class="block text-[#525252] text-lg font-normal mb-3">Country</label>
                                <select id="education-country-select-0" name="education[0][country]"
                                    class="education-country-select tom-select-white" data-default="" required>
                                    <option value="">Select Country</option>
                                </select>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end items-center gap-6 mt-4">
                            <button type="button"
                                class="text-[#525252] text-[1rem] font-normal cursor-pointer bg-transparent border-none py-2 px-2 hover:text-black transition-colors">Cancel</button>
                            <button type="button"
                                class="bg-[#FABC41] text-[#423131] py-3.5 px-10 rounded-full text-[1rem] font-medium transition-all duration-300 cursor-pointer border-none hover:bg-[#E8AA32] hover:shadow-md tracking-wide">Save</button>
                        </div>
                    </div>

                    <!-- Professional Bio -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-medium text-gray-900 mb-4">Professional Bio</h3>
                        <textarea name="professional_bio"
                            class="w-full py-4 px-5 bg-[#F5F5F5] rounded-2xl outline-none text-[0.95rem] text-gray-700 transition-all duration-300 min-h-[200px] resize-y placeholder:text-gray-400 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                            placeholder="Write your Professional Bio..."></textarea>
                    </div>

                    <!-- Professional Practice Details -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-medium text-gray-900 mb-6">Professional Practice Details</h3>

                        <!-- Ayurvedic Wellness Consultation -->
                        <div class="mb-12 practice-group" data-input="ayurvedic-input">
                            <h4 class="font-medium text-gray-900 mb-4 text-xl">Ayurvedic Wellness Consultation:</h4>
                            <p class="text-gray-700 text-lg mb-4">Focuses on nutritional and lifestyle guidance rooted
                                in Ayurvedic principles:</p>
                            <input type="text" name="ayurvedic_practices_custom" id="ayurvedic-input" readonly
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)] mb-4 cursor-default"
                                placeholder="Choose your practice areas">
                            <div class="flex flex-wrap gap-4">
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="ayurvedic_practices[]" value="nutrition_advice"
                                        class="sr-only"> Ayurvedic Nutrition Advice</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="ayurvedic_practices[]" value="educator" class="sr-only">
                                    Ayurvedic Educator</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="ayurvedic_practices[]" value="constitution_advice"
                                        class="sr-only"> Ayurvedic Constitution Advice</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="ayurvedic_practices[]" value="lifestyle_advice"
                                        class="sr-only"> Lifestyle Advice</label>
                            </div>
                        </div>

                        <!-- Massage & Body Therapists -->
                        <div class="mb-12 practice-group" data-input="massage-input">
                            <h4 class="text-xl font-medium text-gray-900 mb-4">Massage & Body Therapists:</h4>
                            <p class="text-gray-700 text-lg mb-4">Includes specific traditional physical treatments and
                                specialized care:</p>
                            <input type="text" name="massage_practices_custom" id="massage-input" readonly
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)] mb-4 cursor-default"
                                placeholder="Choose your practice areas">
                            <div class="flex flex-wrap gap-4">
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="abhyanga" class="sr-only">
                                    Abhyanga</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="panchakarma" class="sr-only">
                                    Panchakarma</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="shirodhara" class="sr-only">
                                    Shirodhara</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="swedana" class="sr-only">
                                    Swedana</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="udvarthana" class="sr-only">
                                    Udvarthana</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="agnikarma" class="sr-only">
                                    Agnikarma</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="pain_management"
                                        class="sr-only"> Pain Management</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="face_scalp_care"
                                        class="sr-only"> Face & Scalp Care</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="marma_therapy"
                                        class="sr-only">
                                    Marma Therapy</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="shikhara" class="sr-only">
                                    Shikhara</label>
                            </div>
                        </div>

                        <!-- Other Modalities -->
                        <div class="mb-12 practice-group" data-input="modalities-input">
                            <h4 class="text-xl font-medium text-gray-900 mb-4">Other Modalities:</h4>
                            <input type="text" name="other_modalities_custom" id="modalities-input" readonly
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)] mb-4 cursor-default"
                                placeholder="Choose your practice areas">
                            <div class="flex flex-wrap gap-4">
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="other_modalities[]" value="yoga_sessions" class="sr-only">
                                    Yoga Sessions</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="other_modalities[]" value="yoga_therapy" class="sr-only">
                                    Yoga Therapy</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-base text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="other_modalities[]" value="ayurvedic_cooking"
                                        class="sr-only"> Ayurvedic Cooking</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Verification -->
                <div class="hidden" id="tab-3">
                    <!-- Add Summary -->
                    <div class="mb-8">
                        <h3 class="text-2xl font-medium text-gray-900 mb-4">Add Summary</h3>
                        <textarea name="summary"
                            class="w-full py-4 px-5 bg-[#F5F5F5] rounded-2xl outline-none text-[0.95rem] text-gray-700 transition-all duration-300 min-h-[200px] resize-y placeholder:text-gray-400 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                            placeholder="E.g Outline your background in Ayurveda, yoga, sports or holistic wellness"></textarea>
                    </div>

                    <!-- Certifications -->
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-2xl font-medium text-gray-900">Certifications

                                <span class="text-gray-500 text-lg font-normal italic">(Kindly include hours and dates.
                                    It
                                    should be self-attested)</span>
                            </h3>
                        </div>
                        <button type="button"
                            class="text-secondary text-lg font-medium hover:text-primary cursor-pointer"
                            onclick="addCertification()">
                            + Add More Certificates
                        </button>
                    </div>

                    <div class="bg-[#F5F5F5] rounded-xl mb-6" id="certification-section">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-10">
                            <div>
                                <label class="block text-gray-600 text-lg mb-4">Institution / School</label>
                                <div
                                    class="rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                    <div
                                        class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                        <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                        <p class="text-gray-500 text-sm leading-none">Upload</p>
                                    </div>
                                    <p class="text-gray-400 text-sm">(Max 2MB)</p>
                                    <input type="file" name="cert_institution" class="hidden"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-600 text-lg mb-4">Training / Diploma</label>
                                <div
                                    class="rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                    <div
                                        class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                        <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                        <p class="text-gray-500 text-sm leading-none">Upload</p>
                                    </div>
                                    <p class="text-gray-400 text-sm">(Max 2MB)</p>
                                    <input type="file" name="cert_training" class="hidden"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-600 text-lg mb-4">Experience <span
                                        class="text-gray-400">(if any)</span></label>
                                <div
                                    class="rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                    <div
                                        class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                        <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                        <p class="text-gray-500 text-sm leading-none">Upload</p>
                                    </div>
                                    <p class="text-gray-400 text-sm">(Max 2MB)</p>
                                    <input type="file" name="cert_experience" class="hidden"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                        </div>
                        <div class="bg-[#FFECC8] rounded-lg py-6 px-10 flex justify-between items-center mb-4">
                            <div>
                                <span class="italic text-[#423131] text-sm">*Incomplete applications will not be
                                    reviewed. Please ensure all documents are legible.</span>
                            </div>
                            <div class="flex justify-end gap-3">
                                <button type="button"
                                    class="text-gray-500 text-base cursor-pointer bg-transparent border-none py-2 px-4 hover:text-gray-700">Cancel</button>
                                <button type="button"
                                    class="bg-[#FABD4D] text-[#423131] py-2 px-5 rounded-full text-base transition-all duration-300 cursor-pointer border-none hover:bg-[#d3992d]">Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- Document Uploads -->
                    <div class="bg-[#F5F5F5] rounded-xl mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 px-10 pt-10">
                            <div>
                                <label class="block text-gray-600 mb-4 text-lg">Registration Form</label>
                                <div
                                    class="rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                    <div
                                        class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                        <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                        <p class="text-gray-500 text-sm leading-none">Upload</p>
                                    </div>
                                    <p class="text-gray-400 text-sm">(Max 2MB)</p>
                                    <input type="file" name="registration_form" class="hidden"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-700  mb-4 text-lg">Code of Ethics</label>
                                <div
                                    class="rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                    <div
                                        class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                        <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                        <p class="text-gray-500 text-sm leading-none">Upload</p>
                                    </div>
                                    <p class="text-gray-400 text-sm">(Max 2MB)</p>
                                    <input type="file" name="code_of_ethics" class="hidden"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-700  mb-4 text-lg">Wellness Contract</label>
                                <div
                                    class="rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                    <div
                                        class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                                        <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                                        <p class="text-gray-500 text-sm leading-none">Upload</p>
                                    </div>
                                    <p class="text-gray-400 text-sm">(Max 2MB)</p>
                                    <input type="file" name="wellness_contract" class="hidden"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                        </div>
                        <!-- Upload Cover Letter -->
                        <div class="p-10">
                            <label class="block text-gray-700  mb-4 text-lg">Upload Cover Letter</label>
                            <div
                                class="rounded-xl py-8 px-4 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
                                <div class="inline-block border border-[#BEBEBE] rounded-[16px] px-4 py-2 mb-3">
                                    <i class="ri-upload-cloud-2-line text-[#FABD4D] text-3xl"></i>
                                </div>
                                <p class="text-gray-500 text-sm">Choose Images or documents</p>
                                <p class="text-gray-400 text-xs">JPG, JPEG, PNG, WEBP, DOC & PDF (Max.20MB)</p>
                                <input type="file" name="cover_letter" class="hidden"
                                    accept=".pdf,.jpg,.jpeg,.png,.ai,.svg,.xls,.xlsx">
                            </div>
                        </div>
                        <div class="bg-[#FFECC8] rounded-lg py-6 px-10 flex justify-end items-center mb-4">
                            <div class="flex justify-end gap-3">
                                <button type="button"
                                    class="text-gray-500 text-base cursor-pointer bg-transparent border-none py-2 px-4 hover:text-gray-700">Cancel</button>
                                <button type="button"
                                    class="bg-[#FABD4D] text-[#423131] py-2 px-5 rounded-full text-base transition-all duration-300 cursor-pointer border-none hover:bg-[#d3992d]">Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- Languages Known -->
                    <div class="mb-12">
                        <h3 class="text-2xl font-medium text-gray-900 mb-8">Languages Known</h3>
                        <div class="flex flex-col lg:flex-row gap-8 lg:gap-10">
                            <!-- Left Side: Add Language -->
                            <div class="w-full lg:w-[45%]">
                                <div class="relative mb-6">
                                    <input type="text" id="lang-input"
                                        class="w-full py-3.5 pl-6 pr-14 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                        placeholder="Enter Language">
                                    <button type="button" id="lang-add-btn"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 w-[34px] h-[34px] rounded-full flex justify-center items-center transition-all duration-300 bg-[#E5E5E5] text-white cursor-not-allowed pointer-events-none">
                                        <i class="ri-check-line text-xl font-bold"></i>
                                    </button>
                                </div>
                                <div class="flex flex-wrap gap-8 pl-4">
                                    <label class="custom-radio-checkbox flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" value="Read" class="sr-only lang-skill-checkbox">
                                        <div class="w-[22px] h-[22px] rounded-full border-2 border-[#E5E5E5] flex justify-center items-center transition-all duration-200 checkbox-circle group-hover:border-[#FABC41]"
                                            style="background-color: transparent;">
                                            <div
                                                class="w-[10px] h-[10px] rounded-full bg-transparent transition-all inner-dot">
                                            </div>
                                        </div>
                                        <span
                                            class="text-[#A3A3A3] text-base transition-colors duration-200 label-text font-normal">Read</span>
                                    </label>
                                    <label class="custom-radio-checkbox flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" value="Write" class="sr-only lang-skill-checkbox">
                                        <div class="w-[22px] h-[22px] rounded-full border-2 border-[#E5E5E5] flex justify-center items-center transition-all duration-200 checkbox-circle group-hover:border-[#FABC41]"
                                            style="background-color: transparent;">
                                            <div
                                                class="w-[10px] h-[10px] rounded-full bg-transparent transition-all inner-dot">
                                            </div>
                                        </div>
                                        <span
                                            class="text-[#A3A3A3] text-base transition-colors duration-200 label-text font-normal">Write</span>
                                    </label>
                                    <label class="custom-radio-checkbox flex items-center gap-2 cursor-pointer group">
                                        <input type="checkbox" value="Speak" class="sr-only lang-skill-checkbox">
                                        <div class="w-[22px] h-[22px] rounded-full border-2 border-[#E5E5E5] flex justify-center items-center transition-all duration-200 checkbox-circle group-hover:border-[#FABC41]"
                                            style="background-color: transparent;">
                                            <div
                                                class="w-[10px] h-[10px] rounded-full bg-transparent transition-all inner-dot">
                                            </div>
                                        </div>
                                        <span
                                            class="text-[#A3A3A3] text-base transition-colors duration-200 label-text font-normal">Speak</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Vertical Divider -->
                            <div class="hidden lg:block w-px bg-[#E5E5E5] h-[46px] self-start"></div>

                            <!-- Right Side: Display Languages -->
                            <div class="w-full lg:w-[50%] flex flex-wrap gap-3 content-start pt-1"
                                id="lang-tags-container">
                                <!-- Generated Dynamic Language pills will append here -->
                            </div>
                        </div>
                    </div>

                    <!-- Payment & Captcha Section -->
                    <div class="mb-12 border-t border-[#E5E5E5] pt-12">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 mb-12">
                            <!-- Registration Fee Amount -->
                            <div>
                                <label class="block text-gray-800 text-lg font-medium mb-4">Registration Fee
                                    Amount</label>
                                <div class="relative w-full">
                                    <div class="w-full h-[58px] bg-[#F5F5F5] rounded-full flex items-center pl-8 pr-2">
                                        <span class="text-gray-900 text-[1.05rem] font-medium">€ 10.00</span>
                                        <input type="hidden" name="registration_fee" value="10.00">
                                        <button type="button"
                                            class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#FABC41] text-[#423131] px-8 py-2.5 rounded-full text-[0.95rem] transition-all duration-300 hover:bg-[#E8AA32] border-none cursor-pointer">
                                            Pay
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Promocode -->
                            <div>
                                <label class="block text-gray-800 text-lg font-medium mb-4">Promocode</label>
                                <div class="relative w-full">
                                    <input type="text" name="promocode" placeholder="CODE1234"
                                        class="w-full h-[58px] pl-6 pr-28 bg-white rounded-full border border-dashed border-[#CFCFCF] outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#FABC41] focus:shadow-[0_0_0_3px_rgba(250,188,65,0.1)]">
                                    <button type="button"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 bg-[#FABC41] text-[#423131] px-7 py-2.5 rounded-full text-[0.95rem] transition-all duration-300 hover:bg-[#E8AA32] border-none cursor-pointer">
                                        Apply
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Captcha Verification -->
                        <div
                            class="bg-gradient-to-r from-[#FFFFFF] via-[#F0F0F0] to-[#FFFFFF] p-12 flex flex-col items-center justify-center">
                            <h3 class="text-xl font-medium text-gray-800 mb-6">Captcha Verification</h3>
                            <div class="flex items-center justify-center gap-3">
                                <div
                                    class="bg-white border border-[#D1D5DB] rounded-lg overflow-hidden h-[48px] w-[140px] flex items-center justify-center p-1">
                                    <!-- Replace with dynamic captcha image -->
                                    <img src="{{ asset('frontend/assets/captcha-placeholder.png') }}"
                                        onerror="this.src='https://dummyimage.com/130x48/f3f4f6/000.png&text=98RW6'"
                                        alt="Captcha"
                                        class="w-full h-full object-contain filter contrast-125 mix-blend-multiply">
                                </div>
                                <button type="button"
                                    class="w-[48px] h-[48px] bg-[#1B5CB8] rounded-lg flex items-center justify-center text-white transition-all hover:bg-[#154a96] border-none cursor-pointer shadow-sm">
                                    <i class="ri-refresh-line text-2xl"></i>
                                </button>
                                <input type="text" name="captcha" placeholder="Enter Code"
                                    class="h-[48px] w-[140px] px-4 bg-white rounded-lg border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-[#A3A3A3] focus:border-[#1B5CB8] focus:shadow-[0_0_0_3px_rgba(27,92,184,0.1)]">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer with Buttons -->
    <footer class="bg-[#FFF3D4] py-6 mt-auto">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto flex items-center justify-end gap-4 md:gap-8">
                <button type="button"
                    class="text-[#594B4B] font-normal text-base transition-all duration-200 cursor-pointer bg-transparent border-none py-3.5 px-6 hover:text-gray-700"
                    id="back-btn" onclick="previousTab()">
                    <span id="back-btn-text">← Back to Website</span>
                </button>
                <button type="button"
                    class="bg-[#F5A623] text-[#423131] py-3.5 px-8 rounded-full font-normal text-base transition-all duration-300 cursor-pointer border-none hover:bg-[#A87139] hover:text-white hover:-translate-y-0.5"
                    id="next-btn" onclick="nextTab()">
                    <span id="next-btn-text">Save & Continue</span>
                </button>
            </div>
        </div>
    </footer>
    <!-- Thank You Popup Modal -->
    <div id="thank-you-popup" class="fixed inset-0 bg-black/40 z-[100] hidden items-center justify-center backdrop-blur-sm px-4">
        <div class="bg-white rounded-[24px] shadow-2xl w-full max-w-[450px] p-10 text-center relative animate-[popIn_0.3s_ease-out_forwards]">
            <!-- Checkmark Illustration -->
            <div class="relative w-24 h-24 mx-auto mb-6">
                <!-- Outer floating dots container -->
                <div class="absolute inset-0 select-none pointer-events-none">
                    <div class="absolute -top-1 right-2 w-4 h-4 rounded-full bg-[#60E48C]"></div>
                    <div class="absolute top-8 -right-3 w-2 h-2 rounded-full bg-[#60E48C]"></div>
                    <div class="absolute bottom-4 -right-1 w-1.5 h-1.5 rounded-full bg-[#60E48C]"></div>
                    <div class="absolute top-2 -left-2 w-3 h-3 rounded-full bg-[#60E48C]"></div>
                    <div class="absolute -top-3 left-6 w-1 h-1 rounded-full bg-[#60E48C]"></div>
                </div>
                <!-- Main Check Circle -->
                <div class="w-full h-full bg-[#60E48C] rounded-full flex items-center justify-center relative z-10 shadow-lg shadow-[#60E48C]/30">
                    <svg width="40" height="30" viewBox="0 0 40 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 15L15 26L36 4" stroke="white" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>

            <!-- Text Content -->
            <h3 class="text-[#209F59] text-[28px] font-medium mb-4">Thank you!</h3>
            <h4 class="text-[#333333] text-[20px] font-semibold mb-3">Your Application Submitted!</h4>
            <p class="text-[#737373] text-[15px] leading-relaxed mb-6 font-normal">
                Your application will be reviewed within 20 days.<br>
                Stay connect with us!
            </p>
            
            <button onclick="closeThankYouPopup()" class="hidden"></button>
        </div>
    </div>

    <script>
        let currentTab = 1;
        const totalTabs = 3;

        function updateStepIndicator() {
            for (let i = 1; i <= totalTabs; i++) {
                const circle = document.getElementById(`step-circle-${i}`);
                const label = document.getElementById(`step-label-${i}`);
                const line = document.getElementById(`step-line-${i}`);

                if (i < currentTab) {
                    // Completed step
                    circle.className = 'w-10 h-10 rounded-full flex items-center justify-center font-semibold text-base transition-all duration-300 bg-[#22C55E] text-white';
                    circle.innerHTML = '<i class="ri-check-line"></i>';
                    label.className = 'text-base text-gray-700 mt-2.5 font-normal whitespace-nowrap';
                    if (line) {
                        line.className = 'w-[140px] h-0 border-t-2 border-dashed border-[#22C55E] self-center -mt-7 relative';
                    }
                } else if (i === currentTab) {
                    // Active step
                    circle.className = 'w-10 h-10 rounded-full flex items-center justify-center font-semibold text-base transition-all duration-300 bg-[#60E48C] text-white';
                    circle.textContent = i;
                    label.className = 'text-base text-gray-700 mt-2.5 font-normal whitespace-nowrap';
                    if (line) {
                        line.className = 'w-[140px] h-0 border-t-2 border-dashed border-[#60E48C] self-center -mt-7 relative';
                    }
                } else {
                    // Inactive step
                    circle.className = 'w-10 h-10 rounded-full flex items-center justify-center font-semibold text-base transition-all duration-300 bg-[#E6E6E6] text-[#8B8B8B]';
                    circle.textContent = i;
                    label.className = 'text-base text-gray-400 mt-2.5 font-normal whitespace-nowrap';
                    if (line) {
                        line.className = 'w-[140px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center -mt-7 relative';
                    }
                }
            }

            // Update button text
            const backBtnText = document.getElementById('back-btn-text');
            const nextBtnText = document.getElementById('next-btn-text');

            if (currentTab === 1) {
                backBtnText.textContent = '← Back to Website';
            } else {
                backBtnText.textContent = 'Back';
            }

            if (currentTab === totalTabs) {
                nextBtnText.textContent = 'Submit';
            } else {
                nextBtnText.textContent = 'Save & Continue';
            }
        }

        function showTab(tabNumber) {
            for (let i = 1; i <= totalTabs; i++) {
                const tab = document.getElementById(`tab-${i}`);
                if (i === tabNumber) {
                    tab.classList.remove('hidden');
                    tab.classList.add('block');
                } else {
                    tab.classList.remove('block');
                    tab.classList.add('hidden');
                }
            }
            currentTab = tabNumber;
            updateStepIndicator();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function nextTab() {
            if (currentTab < totalTabs) {
                showTab(currentTab + 1);
            } else {
                // Show Thank You Popup instead of actual submit
                const popup = document.getElementById('thank-you-popup');
                if (popup) {
                    popup.classList.remove('hidden');
                    popup.classList.add('flex');
                }
            }
        }

        function closeThankYouPopup() {
            const popup = document.getElementById('thank-you-popup');
            if (popup) {
                popup.classList.remove('flex');
                popup.classList.add('hidden');
                // Optional: redirect to home after closing
                window.location.href = "{{ route('index') }}";
            }
        }

        function previousTab() {
            if (currentTab > 1) {
                showTab(currentTab - 1);
            } else {
                // Go back to website
                window.location.href = "{{ route('index') }}";
            }
        }

        // Practice tag toggle and update input field
        function updatePracticeInput(group) {
            const inputId = group.dataset.input;
            const input = document.getElementById(inputId);
            if (!input) return;

            const checkedBoxes = group.querySelectorAll('.practice-tag input[type="checkbox"]:checked');
            const values = Array.from(checkedBoxes).map(checkbox => {
                // Get the label text (parent's text content minus the checkbox)
                const label = checkbox.closest('.practice-tag');
                return label.textContent.trim();
            });
            input.value = values.join(', ');
        }

        document.querySelectorAll('.practice-tag').forEach(tag => {
            tag.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default label behavior

                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;

                if (checkbox.checked) {
                    this.classList.remove('border-gray-200', 'bg-white', 'text-gray-700');
                    this.classList.add('selected', 'border-[#FABD4D]', 'bg-[#FABD4D]', 'text-[#423131]');
                } else {
                    this.classList.remove('selected', 'border-[#FABD4D]', 'bg-[#FABD4D]', 'text-[#423131]');
                    this.classList.add('border-gray-200', 'bg-white', 'text-gray-700');
                }

                // Update the input field with comma-separated values
                const group = this.closest('.practice-group');
                if (group) {
                    updatePracticeInput(group);
                }
            });
        });

        // Upload box click handlers
        document.querySelectorAll('.border-dashed').forEach(box => {
            box.addEventListener('click', function () {
                const input = this.querySelector('input[type="file"]');
                if (input) input.click();
            });
        });

        // Photo upload preview
        document.getElementById('profile-photo').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const label = document.querySelector('label[for="profile-photo"]');
                    label.style.backgroundImage = `url(${e.target.result})`;
                    label.style.backgroundSize = 'cover';
                    label.style.backgroundPosition = 'center';
                    label.innerHTML = '';
                };
                reader.readAsDataURL(file);
            }
        });

        function addEducation() {
            // Add education form logic here
            alert('Add another education form');
        }

        function addCertification() {
            // Add certification form logic here
            alert('Add more certificates');
        }

        // Language Section Interactive Logic
        const langInput = document.getElementById('lang-input');
        const langAddBtn = document.getElementById('lang-add-btn');
        const langCheckboxes = document.querySelectorAll('.lang-skill-checkbox');
        const langContainer = document.getElementById('lang-tags-container');

        function checkLangFormState() {
            if (!langInput) return;
            const hasText = langInput.value.trim().length > 0;
            const hasCheckbox = Array.from(langCheckboxes).some(cb => cb.checked);

            if (hasText && hasCheckbox) {
                // Enable button
                langAddBtn.classList.remove('bg-[#E5E5E5]', 'text-white', 'cursor-not-allowed', 'pointer-events-none');
                langAddBtn.classList.add('bg-[#FABC41]', 'text-white', 'cursor-pointer', 'hover:bg-[#E8AA32]', 'shadow-sm');
            } else {
                // Disable button
                langAddBtn.classList.add('bg-[#E5E5E5]', 'text-white', 'cursor-not-allowed', 'pointer-events-none');
                langAddBtn.classList.remove('bg-[#FABC41]', 'text-white', 'cursor-pointer', 'hover:bg-[#E8AA32]', 'shadow-sm');
            }
        }

        if (langInput) {
            langInput.addEventListener('input', checkLangFormState);
            langCheckboxes.forEach(cb => cb.addEventListener('change', checkLangFormState));

            langAddBtn.addEventListener('click', function () {
                const langName = langInput.value.trim();
                const selectedSkills = Array.from(langCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);

                if (!langName || selectedSkills.length === 0) return;

                // Create visual UI tag
                const tagId = 'lang-' + Date.now();
                const tagHTML = `
                    <div class="bg-[#FABC41] text-[#423131] px-5 py-2.5 rounded-[99px] flex items-center gap-3 shadow-none transition-all duration-300" 
                         style="animation: popIn 0.3s forwards;" 
                         id="${tagId}">
                        <span class="font-normal text-[1rem]">${langName}</span>
                        <i class="ri-close-line cursor-pointer text-xl font-normal opacity-70 hover:opacity-100 transition-opacity" onclick="removeLang('${tagId}')"></i>
                        <input type="hidden" name="languages_known[${langName}]" value="${selectedSkills.join(', ')}">
                    </div>
                `;

                // Append
                langContainer.insertAdjacentHTML('beforeend', tagHTML);

                // Reset Form Layer
                langInput.value = '';
                langCheckboxes.forEach(cb => cb.checked = false);
                checkLangFormState();
            });

            window.removeLang = function (id) {
                const tag = document.getElementById(id);
                if (tag) {
                    tag.style.animation = "popOut 0.25s forwards";
                    setTimeout(() => tag.remove(), 250);
                }
            }
        }

        // Custom Select Global Constructor
        function setupCustomSelect(selectId, inputId, selectedId) {
            const select = document.getElementById(selectId);
            const input = document.getElementById(inputId);
            const selectedText = document.getElementById(selectedId);

            if (!select) return;

            const trigger = select.querySelector('.custom-select-trigger');
            const options = select.querySelectorAll('.custom-option');

            // Toggle dropdown
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                // Close other open selects
                document.querySelectorAll('.custom-select').forEach(s => {
                    if (s !== select) s.classList.remove('open');
                });
                select.classList.toggle('open');
            });

            // Handle option clicks
            options.forEach(option => {
                option.addEventListener('click', function () {
                    const value = this.getAttribute('data-value');
                    const text = this.textContent.trim();

                    input.value = value;
                    selectedText.textContent = text;
                    trigger.classList.add('has-value');

                    options.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                    select.classList.remove('open');
                });
            });

            // Click outside closes dropdown
            document.addEventListener('click', function (e) {
                if (!select.contains(e.target)) {
                    select.classList.remove('open');
                }
            });
        }

        // Initialize Education Type format Custom Select
        setupCustomSelect('education-type-select-0', 'education-type-input-0', 'education-type-selected-0');

    </script>
</body>

</html>