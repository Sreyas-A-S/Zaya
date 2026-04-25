<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Professional Details') }}</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Practitioner Type') }} <span class="text-red-500">*</span></label>
        <select name="practitioner_type[]" multiple data-tomselect required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
            @foreach([
                'Mindfulness Coach',
                'Meditation Teacher',
                'Breathwork Facilitator',
                'Yoga + Mindfulness Instructor',
                'Stress Management Coach',
                'Other',
            ] as $type)
                <option value="{{ $type }}" @selected(in_array($type, (array) old('practitioner_type', []), true))>{{ $type }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Years of Experience (Optional)') }}</label>
        <input type="number" name="years_of_experience" value="{{ old('years_of_experience') }}" min="0" max="70"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Current Workplace / Organization (Optional)') }}</label>
        <input type="text" name="current_workplace" value="{{ old('current_workplace') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700" autocomplete="off">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Website (Optional)') }}</label>
        <input type="url" name="website_social_links[website]" value="{{ old('website_social_links.website') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('LinkedIn (Optional)') }}</label>
        <input type="url" name="website_social_links[linkedin]" value="{{ old('website_social_links.linkedin') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Instagram (Optional)') }}</label>
        <input type="url" name="website_social_links[instagram]" value="{{ old('website_social_links.instagram') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Facebook (Optional)') }}</label>
        <input type="url" name="website_social_links[facebook]" value="{{ old('website_social_links.facebook') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('YouTube (Optional)') }}</label>
        <input type="url" name="website_social_links[youtube]" value="{{ old('website_social_links.youtube') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Qualification Details') }}</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Highest Education') }} <span class="text-red-500">*</span></label>
        <input type="text" name="highest_education" value="{{ old('highest_education') }}" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700" autocomplete="off">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Institute/University') }} <span class="text-red-500">*</span></label>
        <input type="text" name="institute_university" value="{{ old('institute_university') }}" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700" autocomplete="off">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Year of Passing') }} <span class="text-red-500">*</span></label>
        <select name="year_of_passing" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
            <option value="">{{ __('Select Year') }}</option>
            @for($year = date('Y'); $year >= 1950; $year--)
                <option value="{{ $year }}" @selected(old('year_of_passing') == $year)>{{ $year }}</option>
            @endfor
        </select>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Mindfulness Training Details') }}</label>
        <input type="text" name="mindfulness_training_details" value="{{ old('mindfulness_training_details') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700" autocomplete="off">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Additional Certifications (Optional)') }}</label>
        <input type="text" name="additional_certifications" value="{{ old('additional_certifications') }}"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700" autocomplete="off">
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Upload Certificates') }}</label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none">{{ __('Upload Certificates') }}</p>
            </div>
            <p class="text-gray-400 text-sm file-name-display">{{ __('PDF or JPG (Max 2MB)') }}</p>
            <input type="file" name="certificates[]" multiple class="hidden file-input">
        </div>
    </div>
</div>


<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Services') }}</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Services Offered (Optional)') }}</label>
        <select name="services_offered[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
            @foreach(($mindfulnessServices ?? []) as $svc)
                <option value="{{ $svc->name }}" @selected(in_array($svc->name, (array) old('services_offered', []), true))>{{ $svc->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Client Concerns (Optional)') }}</label>
        <select name="client_concerns[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
            @foreach(($clientConcerns ?? []) as $concern)
                <option value="{{ $concern->name }}" @selected(in_array($concern->name, (array) old('client_concerns', []), true))>{{ $concern->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Consultation Modes (Optional)') }}</label>
        <select name="consultation_modes[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
            @foreach(($consultationModes ?? ['Video','Audio','Chat','Group Session']) as $mode)
                <option value="{{ $mode }}" @selected(in_array($mode, (array) old('consultation_modes', []), true))>{{ $mode }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Languages Spoken (Optional)') }}</label>
        <select name="languages_spoken[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
            @foreach(($languages ?? []) as $lang)
                <option value="{{ $lang->name }}" @selected(in_array($lang->name, (array) old('languages_spoken', []), true))>{{ $lang->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8">{{ __('Profile') }}</h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Short Bio (Optional)') }}</label>
        <textarea name="short_bio" rows="4"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">{{ old('short_bio') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Coaching Style (Optional)') }}</label>
        <textarea name="coaching_style" rows="3"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">{{ old('coaching_style') }}</textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg">{{ __('Target Audience (Optional)') }}</label>
        <textarea name="target_audience" rows="3"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">{{ old('target_audience') }}</textarea>
    </div>
</div>
