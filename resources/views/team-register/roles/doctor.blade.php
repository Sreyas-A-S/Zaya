<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Professional Details') }}</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('AYUSH Registration Number') }} <span class="text-red-500">*</span></label>
        <input type="text" name="ayush_reg_no" value="{{ old('ayush_reg_no') }}" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
            placeholder="{{ __('Enter AYUSH Registration Number') }}">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('State Ayurveda Council') }} <span class="text-red-500">*</span></label>
        <input type="text" name="state_council" value="{{ old('state_council') }}" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
            placeholder="{{ __('Enter Council Name') }}">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Registration Certificate') }} <span class="text-red-500">*</span></label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
            </div>
            <p class="text-gray-400 text-sm file-name-display">{{ __('PDF/JPG/PNG (Max 2MB)') }}</p>
            <input type="file" name="reg_certificate" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png" required>
        </div>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Digital Signature (Optional)') }}</label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
            </div>
            <p class="text-gray-400 text-sm file-name-display">{{ __('JPG/PNG (Max 2MB)') }}</p>
            <input type="file" name="digital_signature" class="hidden file-input" accept=".jpg,.jpeg,.png">
        </div>
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Qualifications & Experience') }}</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Primary Qualification') }} <span class="text-red-500">*</span></label>
        <select name="primary_qualification" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]">
            <option value="">{{ __('Select') }}</option>
            <option value="bams" @selected(old('primary_qualification') === 'bams')>{{ __('BAMS') }}</option>
            <option value="other" @selected(old('primary_qualification') === 'other')>{{ __('Other') }}</option>
        </select>
        <input type="text" name="primary_qualification_other" value="{{ old('primary_qualification_other') }}"
            class="mt-3 w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
            placeholder="{{ __('If Other, specify') }}">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Post Graduation (Optional)') }}</label>
        <select name="post_graduation"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]">
            <option value="">{{ __('Select') }}</option>
            <option value="md_ayurveda" @selected(old('post_graduation') === 'md_ayurveda')>{{ __('MD Ayurveda') }}</option>
            <option value="ms_ayurveda" @selected(old('post_graduation') === 'ms_ayurveda')>{{ __('MS Ayurveda') }}</option>
            <option value="other" @selected(old('post_graduation') === 'other')>{{ __('Other') }}</option>
        </select>
        <input type="text" name="post_graduation_other" value="{{ old('post_graduation_other') }}"
            class="mt-3 w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
            placeholder="{{ __('If Other, specify') }}">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Specialization (Optional)') }}</label>
        <select name="specialization[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            @foreach(($specializations ?? []) as $spec)
                <option value="{{ $spec->name }}" @selected(in_array($spec->name, (array) old('specialization', []), true))>{{ $spec->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Degree Certificates') }} <span class="text-red-500">*</span></label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
            </div>
            <p class="text-gray-400 text-sm file-name-display">{{ __('Multiple files allowed (Max 2MB each)') }}</p>
            <input type="file" name="degree_certificates[]" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png" multiple required>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Years of Experience') }} <span class="text-red-500">*</span></label>
        <input type="number" name="years_of_experience" value="{{ old('years_of_experience') }}" min="0" max="70" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Current Workplace / Clinic Name') }} <span class="text-red-500">*</span></label>
        <input type="text" name="current_workplace" value="{{ old('current_workplace') }}" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="{{ __('Enter Workplace / Clinic') }}">
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Consultation Expertise') }}</h2>
<div class="mb-12">
    <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Ayurveda Consultation Expertise (Optional)') }}</label>
    <select name="consultation_expertise[]" multiple data-tomselect
        class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
        @foreach(($consultationExpertise ?? []) as $exp)
            <option value="{{ $exp->name }}" @selected(in_array($exp->name, (array) old('consultation_expertise', []), true))>{{ $exp->name }}</option>
        @endforeach
    </select>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Health Conditions Treated') }}</h2>
<div class="mb-12">
    <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Select Health Conditions (Optional)') }}</label>
    <select name="health_conditions[]" multiple data-tomselect
        class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
        @foreach(($healthConditions ?? []) as $cond)
            <option value="{{ $cond->name }}" @selected(in_array($cond->name, (array) old('health_conditions', []), true))>{{ $cond->name }}</option>
        @endforeach
    </select>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Therapy Skills') }}</h2>
<div class="bg-white rounded-2xl p-6 mb-12">
    <label class="inline-flex items-center gap-3 text-gray-700 mb-5">
        <input type="checkbox" name="panchakarma_consultation" value="1" class="h-5 w-5 rounded border-gray-300" @checked(old('panchakarma_consultation'))>
        <span>{{ __('I am trained to perform/supervise Panchakarma Procedures') }}</span>
    </label>

    <div class="mb-6">
        <p class="text-gray-700 font-normal mb-3 text-lg">{{ __('Panchakarma Procedures Expertise (Optional)') }}</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            @foreach(['Vamana', 'Virechana', 'Basti', 'Nasya', 'Raktamokshana'] as $proc)
                <label class="inline-flex items-center gap-2 text-gray-700">
                    <input type="checkbox" name="panchakarma_procedures[]" value="{{ $proc }}" class="h-5 w-5 rounded border-gray-300"
                        @checked(in_array($proc, (array) old('panchakarma_procedures', []), true))>
                    <span class="text-sm">{{ $proc }}</span>
                </label>
            @endforeach
        </div>
    </div>

    <div>
        <p class="text-gray-700 font-normal mb-3 text-lg">{{ __('External Therapies (Optional)') }}</p>
        <select name="external_therapies[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-[#F8F8F8] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            @foreach(($externalTherapies ?? []) as $ther)
                <option value="{{ $ther->name }}" @selected(in_array($ther->name, (array) old('external_therapies', []), true))>{{ $ther->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Consultation Setup') }}</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div class="bg-white rounded-2xl p-6">
        <p class="text-gray-700 font-normal mb-4 text-lg">{{ __('Consultation Modes (Optional)') }}</p>
        <div class="space-y-3">
            @foreach(['Video', 'Audio', 'Chat'] as $mode)
                <label class="inline-flex items-center gap-2 text-gray-700">
                    <input type="checkbox" name="consultation_modes[]" value="{{ $mode }}" class="h-5 w-5 rounded border-gray-300"
                        @checked(in_array($mode, (array) old('consultation_modes', []), true))>
                    <span>{{ __('Consultation Mode') }} ({{ $mode }})</span>
                </label>
            @endforeach
        </div>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Languages Spoken (Optional)') }}</label>
        <select name="languages_spoken[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            @foreach(($languages ?? []) as $lang)
                <option value="{{ $lang->name }}" @selected(in_array($lang->name, (array) old('languages_spoken', []), true))>{{ $lang->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('KYC & Payment Details') }}</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('PAN Number') }} <span class="text-red-500">*</span></label>
        <input type="text" name="pan_number" value="{{ old('pan_number') }}" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="{{ __('Enter PAN Number') }}">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('UPI ID (Optional)') }}</label>
        <input type="text" name="upi_id" value="{{ old('upi_id') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="{{ __('Enter UPI ID') }}">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('PAN Upload') }} <span class="text-red-500">*</span></label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
            </div>
            <p class="text-gray-400 text-sm file-name-display">{{ __('PDF/JPG/PNG (Max 2MB)') }}</p>
            <input type="file" name="pan_upload" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png" required>
        </div>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Aadhaar Upload (Optional)') }}</label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
            </div>
            <p class="text-gray-400 text-sm file-name-display">{{ __('PDF/JPG/PNG (Max 2MB)') }}</p>
            <input type="file" name="aadhaar_upload" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png">
        </div>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Cancelled Cheque') }} <span class="text-red-500">*</span></label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
            </div>
            <p class="text-gray-400 text-sm file-name-display">{{ __('PDF/JPG/PNG (Max 2MB)') }}</p>
            <input type="file" name="cancelled_cheque" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png" required>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Bank Account Holder Name') }} <span class="text-red-500">*</span></label>
        <input type="text" name="bank_account_holder" value="{{ old('bank_account_holder') }}" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Bank Name') }} <span class="text-red-500">*</span></label>
        <input type="text" name="bank_name" value="{{ old('bank_name') }}" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Account Number') }} <span class="text-red-500">*</span></label>
        <input type="text" name="account_number" value="{{ old('account_number') }}" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('IFSC Code') }} <span class="text-red-500">*</span></label>
        <input type="text" name="ifsc_code" value="{{ old('ifsc_code') }}" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Platform Profile') }}</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Short Bio') }} <span class="text-red-500">*</span></label>
        <textarea name="short_bio" rows="4" required
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700">{{ old('short_bio') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Key Expertise') }} <span class="text-red-500">*</span></label>
        <textarea name="key_expertise" rows="4" required
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700">{{ old('key_expertise') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Services Offered') }} <span class="text-red-500">*</span></label>
        <textarea name="services_offered" rows="4" required
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700">{{ old('services_offered') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Awards & Recognitions (Optional)') }}</label>
        <textarea name="awards_recognitions" rows="3"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700">{{ old('awards_recognitions') }}</textarea>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Website (Optional)') }}</label>
        <input type="url" name="website" value="{{ old('website') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Facebook (Optional)') }}</label>
        <input type="url" name="facebook" value="{{ old('facebook') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Instagram (Optional)') }}</label>
        <input type="url" name="instagram" value="{{ old('instagram') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('YouTube (Optional)') }}</label>
        <input type="url" name="youtube" value="{{ old('youtube') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('LinkedIn (Optional)') }}</label>
        <input type="url" name="linkedin" value="{{ old('linkedin') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Declaration & Consent') }}</h2>
<div class="bg-white rounded-2xl p-6 mb-12 space-y-4">
    <label class="flex items-start gap-3 text-gray-700">
        <input type="checkbox" name="ayush_confirmation" value="1" class="mt-1 h-5 w-5 rounded border-gray-300" required @checked(old('ayush_confirmation'))>
        <span>{{ __('I confirm I am a registered AYUSH Practitioner.') }}</span>
    </label>
    <label class="flex items-start gap-3 text-gray-700">
        <input type="checkbox" name="guidelines_agreement" value="1" class="mt-1 h-5 w-5 rounded border-gray-300" required @checked(old('guidelines_agreement'))>
        <span>{{ __('I agree to follow AYUSH and platform guidelines.') }}</span>
    </label>
    <label class="flex items-start gap-3 text-gray-700">
        <input type="checkbox" name="document_consent" value="1" class="mt-1 h-5 w-5 rounded border-gray-300" required @checked(old('document_consent'))>
        <span>{{ __('I consent to document verification for onboarding.') }}</span>
    </label>
    <label class="flex items-start gap-3 text-gray-700">
        <input type="checkbox" name="policies_agreement" value="1" class="mt-1 h-5 w-5 rounded border-gray-300" required @checked(old('policies_agreement'))>
        <span>{{ __('I agree to platform policies and terms.') }}</span>
    </label>
    <label class="flex items-start gap-3 text-gray-700">
        <input type="checkbox" name="prescription_understanding" value="1" class="mt-1 h-5 w-5 rounded border-gray-300" required @checked(old('prescription_understanding'))>
        <span>{{ __('I understand prescription and consultation responsibilities.') }}</span>
    </label>
    <label class="flex items-start gap-3 text-gray-700">
        <input type="checkbox" name="confidentiality_consent" value="1" class="mt-1 h-5 w-5 rounded border-gray-300" required @checked(old('confidentiality_consent'))>
        <span>{{ __('I agree to maintain client confidentiality.') }}</span>
    </label>
</div>
