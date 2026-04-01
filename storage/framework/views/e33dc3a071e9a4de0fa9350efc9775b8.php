<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Professional Details')); ?></h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Practitioner Type')); ?> <span class="text-red-500">*</span></label>
        <select name="practitioner_type[]" multiple data-tomselect required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = [
                'Mindfulness Coach',
                'Meditation Teacher',
                'Breathwork Facilitator',
                'Yoga + Mindfulness Instructor',
                'Stress Management Coach',
                'Other',
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($type); ?>" <?php if(in_array($type, (array) old('practitioner_type', []), true)): echo 'selected'; endif; ?>><?php echo e($type); ?></option>
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
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Current Workplace / Organization (Optional)')); ?></label>
        <input type="text" name="current_workplace" value="<?php echo e(old('current_workplace')); ?>"
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

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Education & Certifications')); ?></h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Highest Education (Optional)')); ?></label>
        <input type="text" name="highest_education" value="<?php echo e(old('highest_education')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Mindfulness Training Details (Optional)')); ?></label>
        <input type="text" name="mindfulness_training_details" value="<?php echo e(old('mindfulness_training_details')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
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

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Services')); ?></h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Services Offered (Optional)')); ?></label>
        <select name="services_offered[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($mindfulnessServices ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($svc->name); ?>" <?php if(in_array($svc->name, (array) old('services_offered', []), true)): echo 'selected'; endif; ?>><?php echo e($svc->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Client Concerns (Optional)')); ?></label>
        <select name="client_concerns[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($clientConcerns ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $concern): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($concern->name); ?>" <?php if(in_array($concern->name, (array) old('client_concerns', []), true)): echo 'selected'; endif; ?>><?php echo e($concern->name); ?></option>
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
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Languages Spoken (Optional)')); ?></label>
        <select name="languages_spoken[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($languages ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($lang->name); ?>" <?php if(in_array($lang->name, (array) old('languages_spoken', []), true)): echo 'selected'; endif; ?>><?php echo e($lang->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Profile')); ?></h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Short Bio (Optional)')); ?></label>
        <textarea name="short_bio" rows="4"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700"><?php echo e(old('short_bio')); ?></textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Coaching Style (Optional)')); ?></label>
        <textarea name="coaching_style" rows="3"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700"><?php echo e(old('coaching_style')); ?></textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Target Audience (Optional)')); ?></label>
        <textarea name="target_audience" rows="3"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700"><?php echo e(old('target_audience')); ?></textarea>
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

<?php /**PATH C:\wamp64\www\zaya\resources\views\team-register\roles\mindfulness.blade.php ENDPATH**/ ?>