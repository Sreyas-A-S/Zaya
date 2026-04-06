<h2 class="text-xl md:text-2xl font-sans! font-medium text-gray-900 mb-8"><?php echo e(__('Language & Services')); ?></h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Native Language (Optional)')); ?></label>
        <input type="text" name="native_language" value="<?php echo e(old('native_language')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Translator Type')); ?> <span class="text-red-500">*</span></label>
        <select name="translator_type" required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
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
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($languages ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($lang->name); ?>" <?php if(in_array($lang->name, (array) old('source_languages', []), true)): echo 'selected'; endif; ?>><?php echo e($lang->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Target Languages')); ?> <span class="text-red-500">*</span></label>
        <select name="target_languages[]" multiple data-tomselect required
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($languages ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($lang->name); ?>" <?php if(in_array($lang->name, (array) old('target_languages', []), true)): echo 'selected'; endif; ?>><?php echo e($lang->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Additional Languages (Optional)')); ?></label>
        <select name="additional_languages[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
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
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
            <?php $__currentLoopData = ($translatorServices ?? []); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $svc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($svc->name); ?>" <?php if(in_array($svc->name, (array) old('services_offered', []), true)): echo 'selected'; endif; ?>><?php echo e($svc->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Fields of Specialization (Optional)')); ?></label>
        <select name="fields_of_specialization[]" multiple data-tomselect
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
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
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700">
    </div>
    <div>
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Portfolio Link (Optional)')); ?></label>
        <input type="url" name="portfolio_link" value="<?php echo e(old('portfolio_link')); ?>"
            class="w-full py-3.5 px-6 bg-white rounded-full border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700"
            placeholder="https://">
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
    <div class="md:col-span-2">
        <label class="block text-gray-700 font-normal mb-4 text-lg"><?php echo e(__('Previous Clients / Projects (Optional)')); ?></label>
        <textarea name="previous_clients_projects" rows="3"
            class="w-full py-4 px-6 bg-white rounded-3xl border border-[#D1D5DB] outline-none text-[0.95rem] text-gray-700"><?php echo e(old('previous_clients_projects')); ?></textarea>
    </div>
</div>

<?php /**PATH C:\wamp64\www\zaya\resources\views/team-register/roles/translator.blade.php ENDPATH**/ ?>