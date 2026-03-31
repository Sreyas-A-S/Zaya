<!-- Results Heading -->
<div class="text-center mb-10 md:mb-16">
    <h2 class="text-lg md:text-3xl font-semibold text-primary font-sans! mb-2">
        <?php if(isset($pincode) && $pincode): ?>
            Search Results Based on <span class="font-bold text-gray-900">'<?php echo e($pincode); ?>'</span>
        <?php else: ?>
            All Practitioners
        <?php endif; ?>
    </h2>
</div>

<!-- Practitioner Items -->
<div class="container mx-auto">
    <div id="practitioner-grid" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-2 gap-y-8 md:gap-x-6 md:gap-y-12">
    <?php $__empty_1 = true; $__currentLoopData = $practitioners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <a href="<?php echo e($p->slug ? route('practitioner-detail', request('service') ? ['slug' => $p->slug, 'service' => request('service')] : ['slug' => $p->slug]) : '#'); ?>"
            class="flex flex-col items-center text-center group cursor-pointer animate-on-scroll">
            <!-- Avatar -->
            <div
                class="w-32 h-32 md:w-[150px] md:h-[150px] mb-4 overflow-hidden rounded-full border border-gray-100">
                <img src="<?php echo e($p->profile_photo_path ? asset('storage/' . $p->profile_photo_path) : asset('frontend/assets/lilly-profile-pic.png')); ?>"
                    alt="<?php echo e($p->first_name); ?>"
                    class="w-full h-full object-cover rounded-full transition-transform duration-500 group-hover:scale-110">
            </div>

            <!-- Name -->
            <h3
                class="font-sans! text-base md:text-lg lg:text-xl font-medium text-primary group-hover:opacity-80 transition-opacity duration-300">
                <?php echo e($p->first_name); ?> <?php echo e($p->last_name); ?>

            </h3>

            <!-- Role -->
            <p class="font-serif text-sm md:text-base lg:text-lg italic text-secondary mt-0.5">
                <?php echo e(optional($selectedService)->title ?: ($p->other_modalities[0] ?? ($p->consultations[0] ?? 'Holistic Practitioner'))); ?>

            </p>

            <!-- Location -->
            <div class="mt-2 text-xs lg:text-sm text-gray-500">
                <i class="ri-map-pin-line text-gray-800"></i>
                <span><?php echo e($p->city_state); ?></span>
            </div>
        </a>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="col-span-full py-20 text-center">
            <div class="mb-4">
                <i class="ri-search-line text-5xl text-gray-300"></i>
            </div>
            <h3 class="text-xl font-medium text-gray-600 mb-2">No practitioners found</h3>
            <p class="text-gray-400">Try adjusting your filters or searching in a different area.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Pagination -->
<?php if($practitioners->total() > $practitioners->perPage()): ?>
    <div class="mt-16 flex justify-center custom-pagination min-h-[50px]">
        <?php echo e($practitioners->links()); ?>

    </div>
<?php endif; ?>
</div>
<?php /**PATH C:\wamp64\www\zaya\resources\views/partials/frontend/practitioner-grid.blade.php ENDPATH**/ ?>