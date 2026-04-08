<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Language & Services') }}</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Native Language (Optional)') }}</label>
        <input type="text" name="native_language" value="{{ old('native_language') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Translator Type') }} <span class="text-red-500">*</span></label>
        <select name="translator_type" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
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
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
            @foreach(($languages ?? []) as $lang)
                <option value="{{ $lang->name }}" @selected(in_array($lang->name, (array) old('source_languages', []), true))>{{ $lang->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Target Languages') }} <span class="text-red-500">*</span></label>
        <select name="target_languages[]" multiple data-tomselect required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
            @foreach(($languages ?? []) as $lang)
                <option value="{{ $lang->name }}" @selected(in_array($lang->name, (array) old('target_languages', []), true))>{{ $lang->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Additional Languages (Optional)') }}</label>
        <select name="additional_languages[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
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
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
            @foreach(($translatorServices ?? []) as $svc)
                <option value="{{ $svc->name }}" @selected(in_array($svc->name, (array) old('services_offered', []), true))>{{ $svc->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Fields of Specialization (Optional)') }}</label>
        <select name="fields_of_specialization[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
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
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Portfolio Link (Optional)') }}</label>
        <input type="url" name="portfolio_link" value="{{ old('portfolio_link') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Previous Clients / Projects (Optional)') }}</label>
        <textarea name="previous_clients_projects" rows="3"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">{{ old('previous_clients_projects') }}</textarea>
    </div>
</div>

