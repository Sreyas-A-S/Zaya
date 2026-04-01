<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Professional Details')); ?></h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('AYUSH Registration Number')); ?> <span class="text-red-500">*</span></label>
        <input type="text" name="ayush_reg_no" value="<?php echo e(old('ayush_reg_no')); ?>" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
            placeholder="<?php echo e(__('Enter AYUSH Registration Number')); ?>">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('State Ayurveda Council')); ?> <span class="text-red-500">*</span></label>
        <input type="text" name="state_council" value="<?php echo e(old('state_council')); ?>" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
            placeholder="<?php echo e(__('Enter Council Name')); ?>">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Registration Certificate')); ?> <span class="text-red-500">*</span></label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none"><?php echo e(__('Upload')); ?></p>
            </div>
            <p class="text-gray-400 text-sm file-name-display"><?php echo e(__('PDF/JPG/PNG (Max 2MB)')); ?></p>
            <input type="file" name="reg_certificate" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png" required>
        </div>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Digital Signature (Optional)')); ?></label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none"><?php echo e(__('Upload')); ?></p>
            </div>
            <p class="text-gray-400 text-sm file-name-display"><?php echo e(__('JPG/PNG (Max 2MB)')); ?></p>
            <input type="file" name="digital_signature" class="hidden file-input" accept=".jpg,.jpeg,.png">
        </div>
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Qualifications & Experience')); ?></h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Primary Qualification')); ?> <span class="text-red-500">*</span></label>
        <select name="primary_qualification" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]">
            <option value=""><?php echo e(__('Select')); ?></option>
            <option value="bams" <?php if(old('primary_qualification') === 'bams'): echo 'selected'; endif; ?>><?php echo e(__('BAMS')); ?></option>
            <option value="other" <?php if(old('primary_qualification') === 'other'): echo 'selected'; endif; ?>><?php echo e(__('Other')); ?></option>
        </select>
        <input type="text" name="primary_qualification_other" value="<?php echo e(old('primary_qualification_other')); ?>"
            class="mt-3 w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
            placeholder="<?php echo e(__('If Other, specify')); ?>">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Post Graduation (Optional)')); ?></label>
        <select name="post_graduation"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]">
            <option value=""><?php echo e(__('Select')); ?></option>
            <option value="md_ayurveda" <?php if(old('post_graduation') === 'md_ayurveda'): echo 'selected'; endif; ?>><?php echo e(__('MD Ayurveda')); ?></option>
            <option value="ms_ayurveda" <?php if(old('post_graduation') === 'ms_ayurveda'): echo 'selected'; endif; ?>><?php echo e(__('MS Ayurveda')); ?></option>
            <option value="other" <?php if(old('post_graduation') === 'other'): echo 'selected'; endif; ?>><?php echo e(__('Other')); ?></option>
        </select>
        <input type="text" name="post_graduation_other" value="<?php echo e(old('post_graduation_other')); ?>"
            class="mt-3 w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700 transition-all duration-300 focus:border-[#97563D] focus:shadow-[0_0_0_3px_rgba(151,86,61,0.1)]"
            placeholder="<?php echo e(__('If Other, specify')); ?>">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Specialization (Optional)')); ?></label>
        <select name="specialization[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($specializations ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($spec->name); ?>" <?php if(in_array($spec->name, (array) old('specialization', []), true)): echo 'selected'; endif; ?>><?php echo e($spec->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Degree Certificates')); ?> <span class="text-red-500">*</span></label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none"><?php echo e(__('Upload')); ?></p>
            </div>
            <p class="text-gray-400 text-sm file-name-display"><?php echo e(__('Multiple files allowed (Max 2MB each)')); ?></p>
            <input type="file" name="degree_certificates[]" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png" multiple required>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Years of Experience')); ?> <span class="text-red-500">*</span></label>
        <input type="number" name="years_of_experience" value="<?php echo e(old('years_of_experience')); ?>" min="0" max="70" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Current Workplace / Clinic Name')); ?> <span class="text-red-500">*</span></label>
        <input type="text" name="current_workplace" value="<?php echo e(old('current_workplace')); ?>" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="<?php echo e(__('Enter Workplace / Clinic')); ?>">
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Consultation Expertise')); ?></h2>
<div class="mb-12">
    <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Ayurveda Consultation Expertise (Optional)')); ?></label>
    <select name="consultation_expertise[]" multiple data-tomselect
        class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
        <?php $__currentLoopData = ($consultationExpertise ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $exp): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($exp->name); ?>" <?php if(in_array($exp->name, (array) old('consultation_expertise', []), true)): echo 'selected'; endif; ?>><?php echo e($exp->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Health Conditions Treated')); ?></h2>
<div class="mb-12">
    <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Select Health Conditions (Optional)')); ?></label>
    <select name="health_conditions[]" multiple data-tomselect
        class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
        <?php $__currentLoopData = ($healthConditions ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cond): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($cond->name); ?>" <?php if(in_array($cond->name, (array) old('health_conditions', []), true)): echo 'selected'; endif; ?>><?php echo e($cond->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Therapy Skills')); ?></h2>
<div class="bg-white rounded-2xl p-6 mb-12">
    <label class="inline-flex items-center gap-3 text-gray-700 mb-5">
        <input type="checkbox" name="panchakarma_consultation" value="1" class="h-5 w-5 rounded border-gray-300" <?php if(old('panchakarma_consultation')): echo 'checked'; endif; ?>>
        <span><?php echo e(__('I am trained to perform/supervise Panchakarma Procedures')); ?></span>
    </label>

    <div class="mb-6">
        <p class="text-gray-700 font-normal mb-3 text-lg"><?php echo e(__('Panchakarma Procedures Expertise (Optional)')); ?></p>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <?php $__currentLoopData = ['Vamana', 'Virechana', 'Basti', 'Nasya', 'Raktamokshana']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label class="inline-flex items-center gap-2 text-gray-700">
                    <input type="checkbox" name="panchakarma_procedures[]" value="<?php echo e($proc); ?>" class="h-5 w-5 rounded border-gray-300"
                        <?php if(in_array($proc, (array) old('panchakarma_procedures', []), true)): echo 'checked'; endif; ?>>
                    <span class="text-sm"><?php echo e($proc); ?></span>
                </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    <div>
        <p class="text-gray-700 font-normal mb-3 text-lg"><?php echo e(__('External Therapies (Optional)')); ?></p>
        <select name="external_therapies[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-[#F8F8F8] rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($externalTherapies ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ther): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($ther->name); ?>" <?php if(in_array($ther->name, (array) old('external_therapies', []), true)): echo 'selected'; endif; ?>><?php echo e($ther->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Consultation Setup')); ?></h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div class="bg-white rounded-2xl p-6">
        <p class="text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Consultation Modes (Optional)')); ?></p>
        <div class="space-y-3">
            <?php $__currentLoopData = ['Video', 'Audio', 'Chat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mode): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <label class="inline-flex items-center gap-2 text-gray-700">
                    <input type="checkbox" name="consultation_modes[]" value="<?php echo e($mode); ?>" class="h-5 w-5 rounded border-gray-300"
                        <?php if(in_array($mode, (array) old('consultation_modes', []), true)): echo 'checked'; endif; ?>>
                    <span><?php echo e(__('Consultation Mode')); ?> (<?php echo e($mode); ?>)</span>
                </label>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
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

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('KYC & Payment Details')); ?></h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('PAN Number')); ?> <span class="text-red-500">*</span></label>
        <input type="text" name="pan_number" value="<?php echo e(old('pan_number')); ?>" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="<?php echo e(__('Enter PAN Number')); ?>">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('UPI ID (Optional)')); ?></label>
        <input type="text" name="upi_id" value="<?php echo e(old('upi_id')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="<?php echo e(__('Enter UPI ID')); ?>">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('PAN Upload')); ?> <span class="text-red-500">*</span></label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none"><?php echo e(__('Upload')); ?></p>
            </div>
            <p class="text-gray-400 text-sm file-name-display"><?php echo e(__('PDF/JPG/PNG (Max 2MB)')); ?></p>
            <input type="file" name="pan_upload" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png" required>
        </div>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Aadhaar Upload (Optional)')); ?></label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none"><?php echo e(__('Upload')); ?></p>
            </div>
            <p class="text-gray-400 text-sm file-name-display"><?php echo e(__('PDF/JPG/PNG (Max 2MB)')); ?></p>
            <input type="file" name="aadhaar_upload" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png">
        </div>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Cancelled Cheque')); ?> <span class="text-red-500">*</span></label>
        <div class="upload-box rounded-xl p-5 text-center cursor-pointer transition-all duration-300 bg-white hover:bg-[#FFECC8]">
            <div class="inline-flex justify-center items-center gap-2 border border-[#D8D8D8] rounded-[6px] px-4 py-2 mb-3">
                <i class="ri-upload-2-line text-gray-400 text-sm leading-none"></i>
                <p class="text-gray-500 text-sm leading-none"><?php echo e(__('Upload')); ?></p>
            </div>
            <p class="text-gray-400 text-sm file-name-display"><?php echo e(__('PDF/JPG/PNG (Max 2MB)')); ?></p>
            <input type="file" name="cancelled_cheque" class="hidden file-input" accept=".pdf,.jpg,.jpeg,.png" required>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Bank Account Holder Name')); ?> <span class="text-red-500">*</span></label>
        <input type="text" name="bank_account_holder" value="<?php echo e(old('bank_account_holder')); ?>" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Bank Name')); ?> <span class="text-red-500">*</span></label>
        <input type="text" name="bank_name" value="<?php echo e(old('bank_name')); ?>" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Account Number')); ?> <span class="text-red-500">*</span></label>
        <input type="text" name="account_number" value="<?php echo e(old('account_number')); ?>" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('IFSC Code')); ?> <span class="text-red-500">*</span></label>
        <input type="text" name="ifsc_code" value="<?php echo e(old('ifsc_code')); ?>" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700">
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Platform Profile')); ?></h2>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Short Bio')); ?> <span class="text-red-500">*</span></label>
        <textarea name="short_bio" rows="4" required
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700"><?php echo e(old('short_bio')); ?></textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Key Expertise')); ?> <span class="text-red-500">*</span></label>
        <textarea name="key_expertise" rows="4" required
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700"><?php echo e(old('key_expertise')); ?></textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Services Offered')); ?> <span class="text-red-500">*</span></label>
        <textarea name="services_offered" rows="4" required
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700"><?php echo e(old('services_offered')); ?></textarea>
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Awards & Recognitions (Optional)')); ?></label>
        <textarea name="awards_recognitions" rows="3"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-transparent outline-none text-[0.95rem] text-gray-700"><?php echo e(old('awards_recognitions')); ?></textarea>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Website (Optional)')); ?></label>
        <input type="url" name="website" value="<?php echo e(old('website')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Facebook (Optional)')); ?></label>
        <input type="url" name="facebook" value="<?php echo e(old('facebook')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Instagram (Optional)')); ?></label>
        <input type="url" name="instagram" value="<?php echo e(old('instagram')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('YouTube (Optional)')); ?></label>
        <input type="url" name="youtube" value="<?php echo e(old('youtube')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('LinkedIn (Optional)')); ?></label>
        <input type="url" name="linkedin" value="<?php echo e(old('linkedin')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-transparent outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
</div>

<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Declaration & Consent')); ?></h2>
<div class="bg-white rounded-2xl p-6 mb-12 space-y-4">
    <label class="flex items-start gap-3 text-gray-700">
        <input type="checkbox" name="ayush_confirmation" value="1" class="mt-1 h-5 w-5 rounded border-gray-300" required <?php if(old('ayush_confirmation')): echo 'checked'; endif; ?>>
        <span><?php echo e(__('I confirm I am a registered AYUSH Practitioner.')); ?></span>
    </label>
    <label class="flex items-start gap-3 text-gray-700">
        <input type="checkbox" name="guidelines_agreement" value="1" class="mt-1 h-5 w-5 rounded border-gray-300" required <?php if(old('guidelines_agreement')): echo 'checked'; endif; ?>>
        <span><?php echo e(__('I agree to follow AYUSH and platform guidelines.')); ?></span>
    </label>
    <label class="flex items-start gap-3 text-gray-700">
        <input type="checkbox" name="document_consent" value="1" class="mt-1 h-5 w-5 rounded border-gray-300" required <?php if(old('document_consent')): echo 'checked'; endif; ?>>
        <span><?php echo e(__('I consent to document verification for onboarding.')); ?></span>
    </label>
    <label class="flex items-start gap-3 text-gray-700">
        <input type="checkbox" name="policies_agreement" value="1" class="mt-1 h-5 w-5 rounded border-gray-300" required <?php if(old('policies_agreement')): echo 'checked'; endif; ?>>
        <span><?php echo e(__('I agree to platform policies and terms.')); ?></span>
    </label>
    <label class="flex items-start gap-3 text-gray-700">
        <input type="checkbox" name="prescription_understanding" value="1" class="mt-1 h-5 w-5 rounded border-gray-300" required <?php if(old('prescription_understanding')): echo 'checked'; endif; ?>>
        <span><?php echo e(__('I understand prescription and consultation responsibilities.')); ?></span>
    </label>
    <label class="flex items-start gap-3 text-gray-700">
        <input type="checkbox" name="confidentiality_consent" value="1" class="mt-1 h-5 w-5 rounded border-gray-300" required <?php if(old('confidentiality_consent')): echo 'checked'; endif; ?>>
        <span><?php echo e(__('I agree to maintain client confidentiality.')); ?></span>
    </label>
</div>
<?php /**PATH C:\wamp64\www\zaya\resources\views\team-register\roles\doctor.blade.php ENDPATH**/ ?>