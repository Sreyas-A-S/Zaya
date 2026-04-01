<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Language & Services')); ?></h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Native Language (Optional)')); ?></label>
        <input type="text" name="native_language" value="<?php echo e(old('native_language')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Translator Type')); ?> <span class="text-red-500">*</span></label>
        <select name="translator_type" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <option value=""><?php echo e(__('Select')); ?></option>
            <?php $__currentLoopData = ['Freelance','Agency','In-house','Other']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($type); ?>" <?php if(old('translator_type') === $type): echo 'selected'; endif; ?>><?php echo e($type); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Source Languages')); ?> <span class="text-red-500">*</span></label>
        <select name="source_languages[]" multiple data-tomselect required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($languages ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($lang->name); ?>" <?php if(in_array($lang->name, (array) old('source_languages', []), true)): echo 'selected'; endif; ?>><?php echo e($lang->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Target Languages')); ?> <span class="text-red-500">*</span></label>
        <select name="target_languages[]" multiple data-tomselect required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($languages ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($lang->name); ?>" <?php if(in_array($lang->name, (array) old('target_languages', []), true)): echo 'selected'; endif; ?>><?php echo e($lang->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Additional Languages (Optional)')); ?></label>
        <select name="additional_languages[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($languages ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($lang->name); ?>" <?php if(in_array($lang->name, (array) old('additional_languages', []), true)): echo 'selected'; endif; ?>><?php echo e($lang->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Services Offered (Optional)')); ?></label>
        <select name="services_offered[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($translatorServices ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($svc->name); ?>" <?php if(in_array($svc->name, (array) old('services_offered', []), true)): echo 'selected'; endif; ?>><?php echo e($svc->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Fields of Specialization (Optional)')); ?></label>
        <select name="fields_of_specialization[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($translatorSpecializations ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($spec->name); ?>" <?php if(in_array($spec->name, (array) old('fields_of_specialization', []), true)): echo 'selected'; endif; ?>><?php echo e($spec->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Experience & Portfolio (Optional)')); ?></h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Years of Experience (Optional)')); ?></label>
        <input type="number" name="years_of_experience" value="<?php echo e(old('years_of_experience')); ?>" min="0" max="70"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Portfolio Link (Optional)')); ?></label>
        <input type="url" name="portfolio_link" value="<?php echo e(old('portfolio_link')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Previous Clients / Projects (Optional)')); ?></label>
        <textarea name="previous_clients_projects" rows="3"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700"><?php echo e(old('previous_clients_projects')); ?></textarea>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Highest Education (Optional)')); ?></label>
        <input type="text" name="highest_education" value="<?php echo e(old('highest_education')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Certification Details (Optional)')); ?></label>
        <input type="text" name="certification_details" value="<?php echo e(old('certification_details')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
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
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Sample Work (Optional)')); ?></label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none"><?php echo e(__('Upload')); ?></p>
            </div>
            <p class="text-gray-400 text-sm file-name-display"><?php echo e(__('Multiple files allowed')); ?></p>
            <input type="file" name="sample_work[]" class="hidden file-input" multiple>
        </div>
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
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('IFSC Code (Optional)')); ?></label>
        <input type="text" name="ifsc_code" value="<?php echo e(old('ifsc_code')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('SWIFT Code (Optional)')); ?></label>
        <input type="text" name="swift_code" value="<?php echo e(old('swift_code')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
</div>

<?php /**PATH C:\wamp64\www\zaya\resources\views\team-register\roles\translator.blade.php ENDPATH**/ ?>