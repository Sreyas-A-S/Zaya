<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-base transition-all duration-300 bg-[#60E48C] text-white"
                            id="step-circle-1">1</div>
                        <span class="text-base text-gray-700 mt-2.5 font-normal whitespace-nowrap"
                            id="step-label-1">Basic Details</span>
                    </div>
                    <div class="w-[140px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center -mt-7 relative"
                        id="step-line-1"></div>
                    <div class="flex flex-col items-center relative z-[2]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-base transition-all duration-300 bg-[#E6E6E6] text-[#8B8B8B]"
                            id="step-circle-2">2</div>
                        <span class="text-base text-gray-400 mt-2.5 font-normal whitespace-nowrap"
                            id="step-label-2">Qualifications</span>
                    </div>
                    <div class="w-[140px] h-0 border-t-2 border-dashed border-[#C0C0C0] self-center -mt-7 relative"
                        id="step-line-2"></div>
                    <div class="flex flex-col items-center relative z-[2]">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-base transition-all duration-300 bg-[#E6E6E6] text-[#8B8B8B]"
                            id="step-circle-3">3</div>
                        <span class="text-base text-gray-400 mt-2.5 font-normal whitespace-nowrap"
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
                        <div>
                            <label class="block text-gray-700 font-normal mb-4 text-lg">Fullname</label>
                            <input type="text" name="fullname" value="{{ old('fullname') }}"
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                placeholder="Enter Fullname" required>
                        </div>
                        <div class="flex flex-col items-center">
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
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-lg font-sans! font-medium text-gray-900">Education</h3>
                        <button type="button" class="text-[#E2980F] text-lg font-normal hover:underline"
                            onclick="addEducation()">
                            + Add Another Education
                        </button>
                    </div>

                    <div class="bg-[#F5F5F5] rounded-2xl py-10 px-12 mb-8"
                        id="education-section">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
                            <div>
                                <label class="block text-gray-600 text-lg mb-4">Institution / School</label>
                                <input type="text" name="education[0][institution]"
                                    class="w-full py-3.5 px-6 bg-[#FEFEFE] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="Enter Institution / School">
                            </div>
                            <div>
                                <label class="block text-gray-600 text-lg mb-4">Batch (Years/Years)</label>
                                <input type="text" name="education[0][batch]"
                                    class="w-full py-3.5 px-6 bg-[#FEFEFE] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="Batch Years">
                            </div>
                            <div>
                                <label class="block text-gray-600 text-lg mb-4">Postal Address</label>
                                <input type="text" name="education[0][postal_address]"
                                    class="w-full py-3.5 px-6 bg-[#FEFEFE] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="Enter Postal Address">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
                            <div>
                                <label class="block text-gray-600 text-lg mb-4">Training / Diploma</label>
                                <input type="text" name="education[0][training]"
                                    class="w-full py-3.5 px-6 bg-[#FEFEFE] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="Enter Training / Diploma">
                            </div>
                            <div>
                                <label class="block text-gray-600 text-lg mb-4">Duration (Years/Years)</label>
                                <input type="text" name="education[0][duration]"
                                    class="w-full py-3.5 px-6 bg-[#FEFEFE] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="Enter Duration">
                            </div>
                            <div>
                                <label class="block text-gray-600 text-lg mb-4">Postal Address</label>
                                <input type="text" name="education[0][postal_address]"
                                    class="w-full py-3.5 px-6 bg-[#FEFEFE] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                                    placeholder="Enter Postal Address">
                            </div>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button"
                                class="text-gray-500 text-[0.9rem] cursor-pointer bg-transparent border-none py-2 px-4 hover:text-gray-700">Cancel</button>
                            <button type="button"
                                class="bg-[#FABD4D] text-[#423131] py-2 px-5 rounded-full text-[0.9rem] transition-all duration-300 cursor-pointer border-none hover:bg-[#d3992d]">Save</button>
                        </div>
                    </div>

                    <!-- Professional Bio -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Professional Bio</h3>
                        <textarea name="professional_bio"
                            class="w-full py-4 px-5 bg-[#F5F5F5] rounded-2xl outline-none text-[0.95rem] text-gray-700 transition-all duration-300 min-h-[200px] resize-y placeholder:text-gray-400 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                            placeholder="Write your Professional Bio..."></textarea>
                    </div>

                    <!-- Professional Practice Details -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Professional Practice Details</h3>

                        <!-- Ayurvedic Wellness Consultation -->
                        <div class="mb-12 practice-group" data-input="ayurvedic-input">
                            <h4 class="font-medium text-gray-900 mb-4">Ayurvedic Wellness Consultation:</h4>
                            <p class="text-gray-500 text-sm mb-4">Focuses on nutritional and lifestyle guidance rooted
                                in Ayurvedic principles:</p>
                            <input type="text" name="ayurvedic_practices_custom" id="ayurvedic-input" readonly
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)] mb-4 cursor-default"
                                placeholder="Choose your practice areas">
                            <div class="flex flex-wrap gap-2">
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="ayurvedic_practices[]" value="nutrition_advice"
                                        class="sr-only"> Ayurvedic Nutrition Advice</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="ayurvedic_practices[]" value="educator" class="sr-only">
                                    Ayurvedic Educator</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="ayurvedic_practices[]" value="constitution_advice"
                                        class="sr-only"> Ayurvedic Constitution Advice</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="ayurvedic_practices[]" value="lifestyle_advice"
                                        class="sr-only"> Lifestyle Advice</label>
                            </div>
                        </div>

                        <!-- Massage & Body Therapists -->
                        <div class="mb-12 practice-group" data-input="massage-input">
                            <h4 class="font-medium text-gray-900 mb-4">Massage & Body Therapists:</h4>
                            <p class="text-gray-500 text-sm mb-4">Includes specific traditional physical treatments and
                                specialized care:</p>
                            <input type="text" name="massage_practices_custom" id="massage-input" readonly
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)] mb-4 cursor-default"
                                placeholder="Choose your practice areas">
                            <div class="flex flex-wrap gap-2">
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="abhyanga" class="sr-only">
                                    Abhyanga</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="panchakarma" class="sr-only">
                                    Panchakarma</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="shirodhara" class="sr-only">
                                    Shirodhara</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="swedana" class="sr-only">
                                    Swedana</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="udvarthana" class="sr-only">
                                    Udvarthana</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="agnikarma" class="sr-only">
                                    Agnikarma</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="pain_management"
                                        class="sr-only"> Pain Management</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="face_scalp_care"
                                        class="sr-only"> Face & Scalp Care</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="marma_therapy"
                                        class="sr-only">
                                    Marma Therapy</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="massage_practices[]" value="shikhara" class="sr-only">
                                    Shikhara</label>
                            </div>
                        </div>

                        <!-- Other Modalities -->
                        <div class="mb-12 practice-group" data-input="modalities-input">
                            <h4 class="font-medium text-gray-900 mb-4">Other Modalities:</h4>
                            <input type="text" name="other_modalities_custom" id="modalities-input" readonly
                                class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)] mb-4 cursor-default"
                                placeholder="Choose your practice areas">
                            <div class="flex flex-wrap gap-2">
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="other_modalities[]" value="yoga_sessions" class="sr-only">
                                    Yoga Sessions</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
                                        type="checkbox" name="other_modalities[]" value="yoga_therapy" class="sr-only">
                                    Yoga Therapy</label>
                                <label
                                    class="practice-tag select-none inline-flex items-center py-2 px-4 border border-gray-200 rounded-full text-[0.85rem] text-gray-700 cursor-pointer transition-all duration-200 bg-white hover:border-[#FABD4D] hover:bg-[#FABD4D] hover:text-[#423131] focus-within:ring-2 focus-within:ring-[#FABD4D] focus-within:ring-offset-1"><input
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
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Add Summary</h3>
                        <textarea name="summary"
                            class="w-full py-4 px-5 bg-[#F5F5F5] rounded-2xl outline-none text-[0.95rem] text-gray-700 transition-all duration-300 min-h-[200px] resize-y placeholder:text-gray-400 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
                            placeholder="E.g Outline your background in Ayurveda, yoga, sports or holistic wellness"></textarea>
                    </div>

                    <!-- Certifications -->
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Certifications

                                <span class="text-gray-500 text-sm text-italic">(Kindly include hours and dates. It
                                    should be self-attested)</span>
                            </h3>
                        </div>
                        <button type="button" class="text-[#E2980F] text-sm font-medium hover:underline"
                            onclick="addCertification()">
                            + Add More Certificates
                        </button>
                    </div>

                    <div class="bg-[#F5F5F5] rounded-xl mb-6" id="certification-section">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 p-10">
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Institution / School</label>
                                <div
                                    class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center cursor-pointer transition-all duration-300 bg-white hover:border-[#97563D] hover:bg-[#FFF7EF]">
                                    <i class="ri-upload-2-line text-gray-400 text-xl mb-1"></i>
                                    <p class="text-gray-500 text-xs">Upload</p>
                                    <p class="text-gray-400 text-xs">(Max 2MB)</p>
                                    <input type="file" name="cert_institution" class="hidden"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Training / Diploma</label>
                                <div
                                    class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center cursor-pointer transition-all duration-300 bg-white hover:border-[#97563D] hover:bg-[#FFF7EF]">
                                    <i class="ri-upload-2-line text-gray-400 text-xl mb-1"></i>
                                    <p class="text-gray-500 text-xs">Upload</p>
                                    <p class="text-gray-400 text-xs">(Max 2MB)</p>
                                    <input type="file" name="cert_training" class="hidden"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Experience <span
                                        class="text-gray-400">(if any)</span></label>
                                <div
                                    class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center cursor-pointer transition-all duration-300 bg-white hover:border-[#97563D] hover:bg-[#FFF7EF]">
                                    <i class="ri-upload-2-line text-gray-400 text-xl mb-1"></i>
                                    <p class="text-gray-500 text-xs">Upload</p>
                                    <p class="text-gray-400 text-xs">(Max 2MB)</p>
                                    <input type="file" name="cert_experience" class="hidden"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                        </div>
                        <div
                            class="bg-[#FEF3C7] rounded-lg py-6 px-10 text-[#92400E] flex justify-between items-center mb-4">
                            <div>
                                <i class="ri-error-warning-line mr-1"></i>
                                <span class="italic text-[#423131] text-sm">Incomplete applications will not be
                                    reviewed.
                                    Please ensure all documents are legible.</span>
                            </div>
                            <div class="flex justify-end gap-3">
                                <button type="button"
                                    class="text-gray-500 text-[0.9rem] cursor-pointer bg-transparent border-none py-2 px-4 hover:text-gray-700">Cancel</button>
                                <button type="button"
                                    class="bg-[#FABD4D] text-[#423131] py-2 px-5 rounded-full text-[0.9rem] transition-all duration-300 cursor-pointer border-none hover:bg-[#d3992d]">Save</button>
                            </div>
                        </div>
                    </div>

                    <!-- Document Uploads -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-medium mb-3 text-sm">Registration Form</label>
                            <div
                                class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center cursor-pointer transition-all duration-300 bg-white hover:border-[#97563D] hover:bg-[#FFF7EF]">
                                <i class="ri-upload-2-line text-gray-400 text-xl mb-1"></i>
                                <p class="text-gray-500 text-xs">Upload</p>
                                <p class="text-gray-400 text-xs">(Max 2MB)</p>
                                <input type="file" name="registration_form" class="hidden"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-3 text-sm">Code of Ethics</label>
                            <div
                                class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center cursor-pointer transition-all duration-300 bg-white hover:border-[#97563D] hover:bg-[#FFF7EF]">
                                <i class="ri-upload-2-line text-gray-400 text-xl mb-1"></i>
                                <p class="text-gray-500 text-xs">Upload</p>
                                <p class="text-gray-400 text-xs">(Max 2MB)</p>
                                <input type="file" name="code_of_ethics" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-3 text-sm">Wellness Contract</label>
                            <div
                                class="border-2 border-dashed border-gray-200 rounded-xl p-4 text-center cursor-pointer transition-all duration-300 bg-white hover:border-[#97563D] hover:bg-[#FFF7EF]">
                                <i class="ri-upload-2-line text-gray-400 text-xl mb-1"></i>
                                <p class="text-gray-500 text-xs">Upload</p>
                                <p class="text-gray-400 text-xs">(Max 2MB)</p>
                                <input type="file" name="wellness_contract" class="hidden"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                    </div>

                    <!-- Upload Cover Letter -->
                    <div class="mb-8">
                        <label class="block text-gray-700 font-medium mb-3 text-sm">Upload Cover Letter</label>
                        <div
                            class="border-2 border-dashed border-gray-200 rounded-2xl py-12 px-6 text-center cursor-pointer transition-all duration-300 bg-[#FAFAFA] hover:border-[#97563D] hover:bg-[#FFF7EF]">
                            <i class="ri-image-add-line text-gray-300 text-4xl mb-2"></i>
                            <p class="text-gray-500 text-sm">Choose Images or documents</p>
                            <p class="text-gray-400 text-xs">(JPG, PDF, AI, SVG. PDF, XCEL) (Max.20MB)</p>
                            <input type="file" name="cover_letter" class="hidden"
                                accept=".pdf,.jpg,.jpeg,.png,.ai,.svg,.xls,.xlsx">
                        </div>
                    </div>

                    <!-- Languages Known -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Languages Known</h3>
                        <input type="text" name="languages"
                            class="w-full py-3.5 px-6 bg-[#F5F5F5] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 placeholder:text-gray-400 focus:border-[#97563D] focus:bg-white focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)] mb-4"
                            placeholder="Enter Language">
                        <div class="flex flex-wrap gap-6">
                            <label class="lang-checkbox flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="language_skills[]" value="read">
                                <span class="text-gray-600 text-sm">Read</span>
                            </label>
                            <label class="lang-checkbox flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="language_skills[]" value="write">
                                <span class="text-gray-600 text-sm">Write</span>
                            </label>
                            <label class="lang-checkbox flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="language_skills[]" value="speak">
                                <span class="text-gray-600 text-sm">Speak</span>
                            </label>
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
                // Submit form
                document.getElementById('practitioner-form').submit();
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
    </script>
</body>

</html>