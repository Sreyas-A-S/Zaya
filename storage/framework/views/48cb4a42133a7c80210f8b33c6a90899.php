<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Professional Details')); ?></h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Yoga Therapist Type')); ?> <span class="text-red-500">*</span></label>
        <select name="yoga_therapist_type" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <option value=""><?php echo e(__('Select Type')); ?></option>
            <?php $__currentLoopData = ['Certified Yoga Therapist','Yoga Instructor','Yoga Therapist (Clinical)','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($type); ?>" <?php if(old('yoga_therapist_type') === $type): echo 'selected'; endif; ?>><?php echo e($type); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Years of Experience (Optional)')); ?></label>
        <input type="number" name="years_of_experience" value="<?php echo e(old('years_of_experience')); ?>" min="0" max="70"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Current Organization (Optional)')); ?></label>
        <input type="text" name="current_organization" value="<?php echo e(old('current_organization')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Workplace Address (Optional)')); ?></label>
        <input type="text" name="workplace_address" value="<?php echo e(old('workplace_address')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Website (Optional)')); ?></label>
        <input type="url" name="website_social_links[website]" value="<?php echo e(old('website_social_links.website')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('LinkedIn (Optional)')); ?></label>
        <input type="url" name="website_social_links[linkedin]" value="<?php echo e(old('website_social_links.linkedin')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Instagram (Optional)')); ?></label>
        <input type="url" name="website_social_links[instagram]" value="<?php echo e(old('website_social_links.instagram')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Facebook (Optional)')); ?></label>
        <input type="url" name="website_social_links[facebook]" value="<?php echo e(old('website_social_links.facebook')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('YouTube (Optional)')); ?></label>
        <input type="url" name="website_social_links[youtube]" value="<?php echo e(old('website_social_links.youtube')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Certifications')); ?></h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Certification Details (Optional)')); ?></label>
        <textarea name="certification_details" rows="3"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700"><?php echo e(old('certification_details')); ?></textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Additional Certifications (Optional)')); ?></label>
        <textarea name="additional_certifications" rows="3"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700"><?php echo e(old('additional_certifications')); ?></textarea>
    </div>
</div>

<div class="mb-12">
    <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Certificates (Optional)')); ?></label>
    <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
        <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
            <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
            <p class="text-gray-500 text-sm leading-none"><?php echo e(__('Upload')); ?></p>
        </div>
        <p class="text-gray-400 text-sm file-name-display"><?php echo e(__('Multiple files allowed (Max 2MB each)')); ?></p>
        <input type="file" name="certificates[]" class="hidden file-input" multiple>
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Registration (Optional)')); ?></h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Registration Number (Optional)')); ?></label>
        <input type="text" name="registration_number" value="<?php echo e(old('registration_number')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Affiliated Body (Optional)')); ?></label>
        <input type="text" name="affiliated_body" value="<?php echo e(old('affiliated_body')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Registration Proof (Optional)')); ?></label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none"><?php echo e(__('Upload')); ?></p>
            </div>
            <p class="text-gray-400 text-sm file-name-display"><?php echo e(__('(Max 2MB)')); ?></p>
            <input type="file" name="registration_proof" class="hidden file-input">
        </div>
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Expertise & Setup')); ?></h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Areas of Expertise')); ?> <span class="text-red-500">*</span></label>
        <select name="areas_of_expertise[]" multiple data-tomselect required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($areasOfExpertise ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($area->name); ?>" <?php if(in_array($area->name, (array) old('areas_of_expertise', []), true)): echo 'selected'; endif; ?>><?php echo e($area->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Consultation Modes (Optional)')); ?></label>
        <select name="consultation_modes[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($consultationModes ?? ['Video','Audio','Chat','Group Session']); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($mode); ?>" <?php if(in_array($mode, (array) old('consultation_modes', []), true)): echo 'selected'; endif; ?>><?php echo e($mode); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Languages Spoken (Optional)')); ?></label>
        <select name="languages_spoken[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($languages ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($lang->name); ?>" <?php if(in_array($lang->name, (array) old('languages_spoken', []), true)): echo 'selected'; endif; ?>><?php echo e($lang->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Profile (Optional)')); ?></h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Short Bio (Optional)')); ?></label>
        <textarea name="short_bio" rows="4"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700"><?php echo e(old('short_bio')); ?></textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Therapy Approach (Optional)')); ?></label>
        <textarea name="therapy_approach" rows="3"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700"><?php echo e(old('therapy_approach')); ?></textarea>
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('KYC & Payment Details (Optional)')); ?></h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Government ID Type (Optional)')); ?></label>
        <input type="text" name="gov_id_type" value="<?php echo e(old('gov_id_type')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('PAN Number (Optional)')); ?></label>
        <input type="text" name="pan_number" value="<?php echo e(old('pan_number')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Gov ID Upload (Optional)')); ?></label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none"><?php echo e(__('Upload')); ?></p>
            </div>
            <p class="text-gray-400 text-sm file-name-display"><?php echo e(__('(Max 2MB)')); ?></p>
            <input type="file" name="gov_id_upload" class="hidden file-input">
        </div>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Cancelled Cheque (Optional)')); ?></label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none"><?php echo e(__('Upload')); ?></p>
            </div>
            <p class="text-gray-400 text-sm file-name-display"><?php echo e(__('(Max 2MB)')); ?></p>
            <input type="file" name="cancelled_cheque" class="hidden file-input">
        </div>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('UPI ID (Optional)')); ?></label>
        <input type="text" name="upi_id" value="<?php echo e(old('upi_id')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
        <label class="block text-gray-700 font-normal mb-4 text-lg mt-6"><?php echo e(__('Bank Holder Name (Optional)')); ?></label>
        <input type="text" name="bank_holder_name" value="<?php echo e(old('bank_holder_name')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Bank Name (Optional)')); ?></label>
        <input type="text" name="bank_name" value="<?php echo e(old('bank_name')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Account Number (Optional)')); ?></label>
        <input type="text" name="account_number" value="<?php echo e(old('account_number')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('IFSC Code (Optional)')); ?></label>
        <input type="text" name="ifsc_code" value="<?php echo e(old('ifsc_code')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
</div>

<?php /**PATH C:\wamp64\www\zaya\resources\views\team-register\roles\yoga.blade.php ENDPATH**/ ?>