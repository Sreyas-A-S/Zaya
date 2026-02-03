<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practitioner Registration - Zaya Wellness</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <style>
        /* Step Indicator Styles */
        .step-indicator {
            display: flex;
            align-items: flex-start;
            justify-content: center;
            gap: 0;
        }

        .step-indicator>.flex {
            position: relative;
            z-index: 2;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #E6E6E6;
            color: #8B8B8B;
        }

        .step-circle.active {
            background: #60E48C;
            color: white;
        }

        .step-circle.completed {
            background: #22C55E;
            border-color: #22C55E;
            color: white;
        }

        .step-line {
            width: 140px;
            height: 0;
            border: none;
            border-top: 2px dashed #C0C0C0;
            margin: 0;
            align-self: center;
            margin-top: -28px;
            /* Half of circle height + label offset to center with circles */
            position: relative;
        }

        .step-line.active {
            border-top: 2px dashed #60E48C;
        }

        .step-line.completed {
            border-color: #22C55E;
        }

        .step-label {
            font-size: 0.85rem;
            color: #9CA3AF;
            margin-top: 10px;
            font-weight: 500;
            white-space: nowrap;
        }

        .step-label.active {
            color: #374151;
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

        /* Textarea Styles */
        .reg-textarea {
            width: 100%;
            padding: 16px 20px;
            background: white;
            border-radius: 16px;
            border: 1px solid #E5E7EB;
            outline: none;
            font-size: 0.95rem;
            color: #374151;
            transition: all 0.3s ease;
            min-height: 120px;
            resize: vertical;
        }

        .reg-textarea::placeholder {
            color: #9CA3AF;
        }

        .reg-textarea:focus {
            border-color: #97563D;
            box-shadow: 0 0 0 3px rgba(151, 86, 61, 0.1);
        }

        /* Photo Upload */
        .photo-upload {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            background: #F5A623;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .photo-upload:hover {
            background: #E09518;
            transform: scale(1.05);
        }

        /* Radio Button Styles */
        .gender-radio {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .gender-radio input[type="radio"] {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #D1D5DB;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .gender-radio input[type="radio"]:checked {
            border-color: #F5A623;
            background: radial-gradient(circle, #F5A623 40%, transparent 45%);
        }

        /* Section Card */
        .section-card {
            background: white;
            border: 1px solid #E5E7EB;
            border-radius: 16px;
            padding: 24px;
        }

        /* Tag/Chip Styles */
        .practice-tag {
            display: inline-flex;
            align-items: center;
            padding: 8px 16px;
            border: 1px solid #E5E7EB;
            border-radius: 9999px;
            font-size: 0.85rem;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s ease;
            background: white;
        }

        .practice-tag:hover {
            border-color: #97563D;
            background: #FFF7EF;
        }

        .practice-tag.selected {
            border-color: #97563D;
            background: #FFF7EF;
            color: #97563D;
        }

        /* Upload Box */
        .upload-box {
            border: 2px dashed #E5E7EB;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }

        .upload-box:hover {
            border-color: #97563D;
            background: #FFF7EF;
        }

        .upload-box-large {
            border: 2px dashed #E5E7EB;
            border-radius: 16px;
            padding: 48px 24px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #FAFAFA;
        }

        .upload-box-large:hover {
            border-color: #97563D;
            background: #FFF7EF;
        }

        /* Button Styles */
        .btn-primary {
            background: #F5A623;
            color: #423131;
            padding: 14px 32px;
            border-radius: 9999px;
            font-weight: normal;
            font-size: 1rem;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-primary:hover {
            background: #A87139;
            color: white;
            transform: translateY(-2px);
        }

        .btn-secondary {
            color: #594B4B;
            font-weight: normal;
            font-size: 1rem;
            transition: all 0.2s ease;
            cursor: pointer;
            background: transparent;
            border: none;
            padding: 14px 24px;
        }

        .btn-secondary:hover {
            color: #374151;
        }

        .btn-save {
            background: #22C55E;
            color: white;
            padding: 8px 20px;
            border-radius: 9999px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .btn-save:hover {
            background: #16A34A;
        }

        .btn-cancel-small {
            color: #6B7280;
            font-size: 0.9rem;
            cursor: pointer;
            background: transparent;
            border: none;
            padding: 8px 16px;
        }

        .btn-cancel-small:hover {
            color: #374151;
        }

        /* Country Select */
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

        /* Tab Content */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Language checkbox */
        .lang-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .lang-checkbox input[type="checkbox"] {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #D1D5DB;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .lang-checkbox input[type="checkbox"]:checked {
            border-color: #F5A623;
            background: #F5A623;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3E%3C/svg%3E");
        }

        /* Warning box */
        .warning-box {
            background: #FEF3C7;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 0.85rem;
            color: #92400E;
        }
    </style>
</head>

<body class="bg-white min-h-screen flex flex-col">
    <!-- Main Content -->
    <div class="flex-1 relative overflow-x-hidden">
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
            <div class="flex justify-center pb-7 mb-20 border-b border-[#D0D0D0]">
                <div class="step-indicator">
                    <div class="flex flex-col items-center">
                        <div class="step-circle active" id="step-circle-1">1</div>
                        <span class="step-label active" id="step-label-1">Basic Details</span>
                    </div>
                    <div class="step-line" id="step-line-1"></div>
                    <div class="flex flex-col items-center">
                        <div class="step-circle" id="step-circle-2">2</div>
                        <span class="step-label" id="step-label-2">Qualifications</span>
                    </div>
                    <div class="step-line" id="step-line-2"></div>
                    <div class="flex flex-col items-center">
                        <div class="step-circle" id="step-circle-3">3</div>
                        <span class="step-label" id="step-label-3">Verification</span>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <form action="{{ route('register') }}" method="POST" enctype="multipart/form-data" class="max-w-5xl mx-auto"
                id="practitioner-form">
                @csrf
                <input type="hidden" name="type" value="practitioner">

                <!-- Tab 1: Basic Details -->
                <div class="tab-content active" id="tab-1">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Basic Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Fullname & Photo Row -->
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 font-medium mb-3 text-sm">Fullname</label>
                            <input type="text" name="fullname" value="{{ old('fullname') }}" class="reg-input"
                                placeholder="Enter Fullname" required>
                        </div>
                        <div class="flex flex-col items-center">
                            <label class="photo-upload" for="profile-photo">
                                <i class="ri-camera-line text-white text-2xl"></i>
                            </label>
                            <input type="file" id="profile-photo" name="profile_photo" accept="image/*" class="hidden">
                            <span class="text-gray-500 text-sm mt-2">Add Photo</span>
                        </div>
                    </div>

                    <!-- Gender -->
                    <div class="mb-8">
                        <label class="block text-gray-700 font-medium mb-3 text-sm">Gender</label>
                        <div class="flex flex-wrap gap-6">
                            <label class="gender-radio">
                                <input type="radio" name="gender" value="male">
                                <span class="text-gray-700">Male</span>
                            </label>
                            <label class="gender-radio">
                                <input type="radio" name="gender" value="female" checked>
                                <span class="text-gray-700">Female</span>
                            </label>
                            <label class="gender-radio">
                                <input type="radio" name="gender" value="others">
                                <span class="text-gray-700">Others</span>
                            </label>
                        </div>
                    </div>

                    <!-- Email & Mobile -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-medium mb-3 text-sm">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="reg-input"
                                placeholder="Enter Email" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-3 text-sm">Mobile No.</label>
                            <input type="tel" name="mobile" value="{{ old('mobile') }}" class="reg-input"
                                placeholder="Enter Mobile No." required>
                        </div>
                    </div>

                    <!-- DOB & Nationality -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-medium mb-3 text-sm">DOB</label>
                            <input type="date" name="dob" value="{{ old('dob') }}" class="reg-input"
                                placeholder="DD/MM/YYYY" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-3 text-sm">Nationality</label>
                            <div class="country-select-wrapper">
                                <span class="country-flag">
                                    <span class="text-lg">🇮🇳</span>
                                    <i class="ri-arrow-down-s-line text-gray-400 text-sm"></i>
                                </span>
                                <input type="text" name="nationality" value="{{ old('nationality', 'India') }}"
                                    class="reg-input country-select" placeholder="Select Country" required>
                            </div>
                        </div>
                    </div>

                    <!-- Address & Website -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-medium mb-3 text-sm">Residential Address</label>
                            <input type="text" name="address" value="{{ old('address') }}" class="reg-input"
                                placeholder="Address with Zipcode" required>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-3 text-sm">Website <span
                                    class="text-gray-400 italic">(if any)</span></label>
                            <input type="url" name="website" value="{{ old('website') }}" class="reg-input"
                                placeholder="Enter URL">
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Qualifications -->
                <div class="tab-content" id="tab-2">
                    <!-- Education Section -->
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Education</h3>
                        <button type="button" class="text-primary text-sm font-medium hover:underline"
                            onclick="addEducation()">
                            + Add Another Education
                        </button>
                    </div>

                    <div class="section-card mb-8" id="education-section">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Institution / School</label>
                                <input type="text" name="education[0][institution]" class="reg-input"
                                    placeholder="Enter Institution / School">
                            </div>
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Batch (Years/Years)</label>
                                <input type="text" name="education[0][batch]" class="reg-input"
                                    placeholder="Batch Years">
                            </div>
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Postal Address</label>
                                <input type="text" name="education[0][postal_address]" class="reg-input"
                                    placeholder="Enter Postal Address">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Training / Diploma</label>
                                <input type="text" name="education[0][training]" class="reg-input"
                                    placeholder="Enter Training / Diploma">
                            </div>
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Duration (Years/Years)</label>
                                <input type="text" name="education[0][duration]" class="reg-input"
                                    placeholder="Enter Duration">
                            </div>
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Hostel Address</label>
                                <input type="text" name="education[0][hostel_address]" class="reg-input"
                                    placeholder="Enter Hostel Address">
                            </div>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" class="btn-cancel-small">Cancel</button>
                            <button type="button" class="btn-save">Save</button>
                        </div>
                    </div>

                    <!-- Professional Bio -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Professional Bio</h3>
                        <textarea name="professional_bio" class="reg-textarea"
                            placeholder="Write your Professional Bio..."></textarea>
                    </div>

                    <!-- Professional Practice Details -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Professional Practice Details</h3>

                        <!-- Ayurvedic Wellness Consultation -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-1">Ayurvedic Wellness Consultation:</h4>
                            <p class="text-gray-500 text-sm mb-3">Focuses on nutritional and lifestyle guidance rooted
                                in Ayurvedic principles:</p>
                            <input type="text" name="ayurvedic_practices_custom" class="reg-input mb-3"
                                placeholder="Choose your practice areas">
                            <div class="flex flex-wrap gap-2">
                                <label class="practice-tag"><input type="checkbox" name="ayurvedic_practices[]"
                                        value="nutrition_advice" class="hidden"> Ayurvedic Nutrition Advice</label>
                                <label class="practice-tag"><input type="checkbox" name="ayurvedic_practices[]"
                                        value="educator" class="hidden"> Ayurvedic Educator</label>
                                <label class="practice-tag"><input type="checkbox" name="ayurvedic_practices[]"
                                        value="constitution_advice" class="hidden"> Ayurvedic Constitution
                                    Advice</label>
                                <label class="practice-tag"><input type="checkbox" name="ayurvedic_practices[]"
                                        value="lifestyle_advice" class="hidden"> Lifestyle Advice</label>
                            </div>
                        </div>

                        <!-- Massage & Body Therapists -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-1">Massage & Body Therapists:</h4>
                            <p class="text-gray-500 text-sm mb-3">Includes specific traditional physical treatments and
                                specialized care:</p>
                            <input type="text" name="massage_practices_custom" class="reg-input mb-3"
                                placeholder="Choose your practice areas">
                            <div class="flex flex-wrap gap-2">
                                <label class="practice-tag"><input type="checkbox" name="massage_practices[]"
                                        value="abhyanga" class="hidden"> Abhyanga</label>
                                <label class="practice-tag"><input type="checkbox" name="massage_practices[]"
                                        value="panchakarma" class="hidden"> Panchakarma</label>
                                <label class="practice-tag"><input type="checkbox" name="massage_practices[]"
                                        value="shirodhara" class="hidden"> Shirodhara</label>
                                <label class="practice-tag"><input type="checkbox" name="massage_practices[]"
                                        value="swedana" class="hidden"> Swedana</label>
                                <label class="practice-tag"><input type="checkbox" name="massage_practices[]"
                                        value="udvarthana" class="hidden"> Udvarthana</label>
                                <label class="practice-tag"><input type="checkbox" name="massage_practices[]"
                                        value="agnikarma" class="hidden"> Agnikarma</label>
                                <label class="practice-tag"><input type="checkbox" name="massage_practices[]"
                                        value="pain_management" class="hidden"> Pain Management</label>
                                <label class="practice-tag"><input type="checkbox" name="massage_practices[]"
                                        value="face_scalp_care" class="hidden"> Face & Scalp Care</label>
                                <label class="practice-tag"><input type="checkbox" name="massage_practices[]"
                                        value="marma_therapy" class="hidden"> Marma Therapy</label>
                                <label class="practice-tag"><input type="checkbox" name="massage_practices[]"
                                        value="shikhara" class="hidden"> Shikhara</label>
                            </div>
                        </div>

                        <!-- Other Modalities -->
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-1">Other Modalities:</h4>
                            <input type="text" name="other_modalities_custom" class="reg-input mb-3"
                                placeholder="Choose your practice areas">
                            <div class="flex flex-wrap gap-2">
                                <label class="practice-tag"><input type="checkbox" name="other_modalities[]"
                                        value="yoga_sessions" class="hidden"> Yoga Sessions</label>
                                <label class="practice-tag"><input type="checkbox" name="other_modalities[]"
                                        value="yoga_therapy" class="hidden"> Yoga Therapy</label>
                                <label class="practice-tag"><input type="checkbox" name="other_modalities[]"
                                        value="ayurvedic_cooking" class="hidden"> Ayurvedic Cooking</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Verification -->
                <div class="tab-content" id="tab-3">
                    <!-- Add Summary -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Add Summary</h3>
                        <textarea name="summary" class="reg-textarea"
                            placeholder="E.g Outline your background in Ayurveda, yoga, sports or holistic wellness"></textarea>
                    </div>

                    <!-- Certifications -->
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Certifications</h3>
                            <p class="text-gray-500 text-sm">(Kindly include hours and dates. It should be
                                self-attested)</p>
                        </div>
                        <button type="button" class="text-primary text-sm font-medium hover:underline"
                            onclick="addCertification()">
                            + Add More Certificates
                        </button>
                    </div>

                    <div class="section-card mb-6" id="certification-section">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Institution / School</label>
                                <div class="upload-box">
                                    <i class="ri-upload-2-line text-gray-400 text-xl mb-1"></i>
                                    <p class="text-gray-500 text-xs">Upload</p>
                                    <p class="text-gray-400 text-xs">(Max 2MB)</p>
                                    <input type="file" name="cert_institution" class="hidden"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                            <div>
                                <label class="block text-gray-600 text-sm mb-2">Training / Diploma</label>
                                <div class="upload-box">
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
                                <div class="upload-box">
                                    <i class="ri-upload-2-line text-gray-400 text-xl mb-1"></i>
                                    <p class="text-gray-500 text-xs">Upload</p>
                                    <p class="text-gray-400 text-xs">(Max 2MB)</p>
                                    <input type="file" name="cert_experience" class="hidden"
                                        accept=".pdf,.jpg,.jpeg,.png">
                                </div>
                            </div>
                        </div>
                        <div class="warning-box mb-4">
                            <i class="ri-error-warning-line mr-1"></i>
                            Incomplete applications will not be reviewed. Please ensure all documents are legible.
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" class="btn-cancel-small">Cancel</button>
                            <button type="button" class="btn-save">Save</button>
                        </div>
                    </div>

                    <!-- Document Uploads -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div>
                            <label class="block text-gray-700 font-medium mb-3 text-sm">Registration Form</label>
                            <div class="upload-box">
                                <i class="ri-upload-2-line text-gray-400 text-xl mb-1"></i>
                                <p class="text-gray-500 text-xs">Upload</p>
                                <p class="text-gray-400 text-xs">(Max 2MB)</p>
                                <input type="file" name="registration_form" class="hidden"
                                    accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-3 text-sm">Code of Ethics</label>
                            <div class="upload-box">
                                <i class="ri-upload-2-line text-gray-400 text-xl mb-1"></i>
                                <p class="text-gray-500 text-xs">Upload</p>
                                <p class="text-gray-400 text-xs">(Max 2MB)</p>
                                <input type="file" name="code_of_ethics" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                            </div>
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-3 text-sm">Wellness Contract</label>
                            <div class="upload-box">
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
                        <div class="upload-box-large">
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
                        <input type="text" name="languages" class="reg-input mb-4" placeholder="Enter Language">
                        <div class="flex flex-wrap gap-6">
                            <label class="lang-checkbox">
                                <input type="checkbox" name="language_skills[]" value="read">
                                <span class="text-gray-600 text-sm">Read</span>
                            </label>
                            <label class="lang-checkbox">
                                <input type="checkbox" name="language_skills[]" value="write">
                                <span class="text-gray-600 text-sm">Write</span>
                            </label>
                            <label class="lang-checkbox">
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
                <button type="button" class="btn-secondary" id="back-btn" onclick="previousTab()">
                    <span id="back-btn-text">← Back to Website</span>
                </button>
                <button type="button" class="btn-primary" id="next-btn" onclick="nextTab()">
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
                    circle.classList.remove('active');
                    circle.classList.add('completed');
                    circle.innerHTML = '<i class="ri-check-line"></i>';
                    label.classList.add('active');
                    if (line) {
                        line.classList.remove('active');
                        line.classList.add('completed');
                    }
                } else if (i === currentTab) {
                    circle.classList.remove('completed');
                    circle.classList.add('active');
                    circle.textContent = i;
                    label.classList.add('active');
                    if (line) {
                        line.classList.add('active');
                        line.classList.remove('completed');
                    }
                } else {
                    circle.classList.remove('active', 'completed');
                    circle.textContent = i;
                    label.classList.remove('active');
                    if (line) {
                        line.classList.remove('active', 'completed');
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
                    tab.classList.add('active');
                } else {
                    tab.classList.remove('active');
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

        // Practice tag toggle
        document.querySelectorAll('.practice-tag').forEach(tag => {
            tag.addEventListener('click', function () {
                this.classList.toggle('selected');
                const checkbox = this.querySelector('input[type="checkbox"]');
                checkbox.checked = !checkbox.checked;
            });
        });

        // Upload box click handlers
        document.querySelectorAll('.upload-box, .upload-box-large').forEach(box => {
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
                    const label = document.querySelector('.photo-upload');
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