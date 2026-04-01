<?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
<div class="w-full sm:w-1/2 lg:w-1/3 px-4 mb-8">
    <!-- Service Card -->
    <a href="<?php echo e($service->slug ? route('service-detail', $service->slug) : '#'); ?>" class="block h-full">
        <div class="bg-white rounded-[20px] shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group h-full cursor-pointer">
            <!-- Image -->
            <div class="h-64 overflow-hidden">
                <img src="<?php echo e($service->image ? (Str::startsWith($service->image, 'frontend/') ? asset($service->image) : asset('storage/' . $service->image)) : asset('frontend/assets/ayurveda-and-panchakarma.png')); ?>"
                    alt="<?php echo e($service->title); ?>"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
            </div>
            <!-- Content -->
            <div class="p-8">
                <h3 class="text-2xl font-serif text-[#C5896B] mb-3"><?php echo e($service->title); ?></h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                    <?php echo Str::limit(strip_tags($service->description), 100); ?>

                </p>
                <div class="flex items-center justify-between mt-auto">
                    <span class="text-secondary text-sm font-medium hover:underline">Read More...</span>
                    <span class="bg-secondary text-white px-6 py-2.5 rounded-full text-sm font-medium hover:bg-primary transition-all">Book a Session</span>
                </div>
            </div>
        </div>
    </a>
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
<div class="col-12 text-center py-5 w-full">
    <p class="text-muted">No services found.</p>
</div>
<?php endif; ?><?php /**PATH C:\wamp64\www\zaya\resources\views\partials\frontend\services-grid.blade.php ENDPATH**/ ?>