<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Language & Services') }}</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Native Language (Optional)') }}</label>
        <input type="text" name="native_language" value="{{ old('native_language') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Translator Type') }} <span class="text-red-500">*</span></label>
        <select name="translator_type" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <option value="">{{ __('Select') }}</option>
            @foreach(['Freelance','Agency','In-house','Other'] as $type)
                <option value="{{ $type }}" @selected(old('translator_type') === $type)>{{ $type }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Source Languages') }} <span class="text-red-500">*</span></label>
        <select name="source_languages[]" multiple data-tomselect required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            @foreach(($languages ?? []) as $lang)
                <option value="{{ $lang->name }}" @selected(in_array($lang->name, (array) old('source_languages', []), true))>{{ $lang->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Target Languages') }} <span class="text-red-500">*</span></label>
        <select name="target_languages[]" multiple data-tomselect required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            @foreach(($languages ?? []) as $lang)
                <option value="{{ $lang->name }}" @selected(in_array($lang->name, (array) old('target_languages', []), true))>{{ $lang->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Additional Languages (Optional)') }}</label>
        <select name="additional_languages[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            @foreach(($languages ?? []) as $lang)
                <option value="{{ $lang->name }}" @selected(in_array($lang->name, (array) old('additional_languages', []), true))>{{ $lang->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Services Offered (Optional)') }}</label>
        <select name="services_offered[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            @foreach(($translatorServices ?? []) as $svc)
                <option value="{{ $svc->name }}" @selected(in_array($svc->name, (array) old('services_offered', []), true))>{{ $svc->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Fields of Specialization (Optional)') }}</label>
        <select name="fields_of_specialization[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            @foreach(($translatorSpecializations ?? []) as $spec)
                <option value="{{ $spec->name }}" @selected(in_array($spec->name, (array) old('fields_of_specialization', []), true))>{{ $spec->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Experience & Portfolio (Optional)') }}</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Years of Experience (Optional)') }}</label>
        <input type="number" name="years_of_experience" value="{{ old('years_of_experience') }}" min="0" max="70"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Portfolio Link (Optional)') }}</label>
        <input type="url" name="portfolio_link" value="{{ old('portfolio_link') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Previous Clients / Projects (Optional)') }}</label>
        <textarea name="previous_clients_projects" rows="3"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700">{{ old('previous_clients_projects') }}</textarea>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Highest Education (Optional)') }}</label>
        <input type="text" name="highest_education" value="{{ old('highest_education') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Certification Details (Optional)') }}</label>
        <input type="text" name="certification_details" value="{{ old('certification_details') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Certificates (Optional)') }}</label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
            </div>
            <p class="text-gray-400 text-sm file-name-display">{{ __('Multiple files allowed (Max 2MB each)') }}</p>
            <input type="file" name="certificates[]" class="hidden file-input" multiple>
        </div>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Sample Work (Optional)') }}</label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
            </div>
            <p class="text-gray-400 text-sm file-name-display">{{ __('Multiple files allowed') }}</p>
            <input type="file" name="sample_work[]" class="hidden file-input" multiple>
        </div>
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('KYC & Payment Details (Optional)') }}</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Government ID Type (Optional)') }}</label>
        <input type="text" name="gov_id_type" value="{{ old('gov_id_type') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('PAN Number (Optional)') }}</label>
        <input type="text" name="pan_number" value="{{ old('pan_number') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Gov ID Upload (Optional)') }}</label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
            </div>
            <p class="text-gray-400 text-sm file-name-display">{{ __('(Max 2MB)') }}</p>
            <input type="file" name="gov_id_upload" class="hidden file-input">
        </div>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Cancelled Cheque (Optional)') }}</label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none">{{ __('Upload') }}</p>
            </div>
            <p class="text-gray-400 text-sm file-name-display">{{ __('(Max 2MB)') }}</p>
            <input type="file" name="cancelled_cheque" class="hidden file-input">
        </div>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('UPI ID (Optional)') }}</label>
        <input type="text" name="upi_id" value="{{ old('upi_id') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
        <label class="block text-gray-700 font-normal mb-4 text-lg mt-6">{{ __('Bank Holder Name (Optional)') }}</label>
        <input type="text" name="bank_holder_name" value="{{ old('bank_holder_name') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Bank Name (Optional)') }}</label>
        <input type="text" name="bank_name" value="{{ old('bank_name') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Account Number (Optional)') }}</label>
        <input type="text" name="account_number" value="{{ old('account_number') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('IFSC Code (Optional)') }}</label>
        <input type="text" name="ifsc_code" value="{{ old('ifsc_code') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('SWIFT Code (Optional)') }}</label>
        <input type="text" name="swift_code" value="{{ old('swift_code') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
</div>

