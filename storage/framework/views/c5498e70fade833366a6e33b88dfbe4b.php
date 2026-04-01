<?php $__env->startSection('content'); ?>

    <!-- Announcement Detail Content -->
    <section class="pt-[144px] md:pt-[150px] px-4 md:px-6 pb-16 bg-white">
        <div class="container mx-auto max-w-4xl">
            <!-- Breadcrumb -->
            <nav class="mb-8">
                <ol class="flex items-center gap-2 text-sm">
                    <li><a href="<?php echo e(route('home')); ?>" class="text-gray-400 hover:text-secondary transition-colors">Home</a></li>
                    <li class="text-gray-300">/</li>
                    <li><a href="<?php echo e(route('announcements')); ?>" class="text-gray-400 hover:text-secondary transition-colors">Announcements</a></li>
                    <li class="text-gray-300">/</li>
                    <li class="text-secondary line-clamp-1"><?php echo e(Str::limit($announcement['title'], 40)); ?></li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="mb-10 text-center">
                <span class="bg-accent text-secondary px-4 py-1.5 rounded-full text-sm font-medium inline-block mb-4">
                    Announcement
                </span>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-primary leading-tight mb-6">
                    <?php echo e($announcement['title']); ?>

                </h1>
                
                <div class="flex items-center justify-center gap-2 text-gray-500 text-sm">
                    <i class="ri-calendar-line text-lg"></i>
                    <span class="font-medium text-base"><?php echo e($announcement['date']); ?></span>
                </div>
            </div>

            <!-- Featured Image -->
            <?php if($announcement['featured_image']): ?>
                <div class="w-full overflow-hidden rounded-[20px] mb-12 shadow-lg">
                    <img src="<?php echo e($announcement['featured_image']); ?>" 
                        <?php if(!empty($announcement['featured_image_srcset'])): ?> 
                        srcset="<?php echo e($announcement['featured_image_srcset']); ?>" 
                        sizes="<?php echo e($announcement['featured_image_sizes']); ?>" 
                        <?php endif; ?>
                        alt="<?php echo e($announcement['title']); ?>"
                        class="w-full h-auto object-cover">
                </div>
            <?php endif; ?>

            <!-- Content -->
            <article class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
                <?php echo $announcement['content']; ?>

            </article>

            <!-- Share Section -->
            <div class="mt-12 pt-8 border-t border-gray-100 flex flex-col items-center gap-4">
                <span class="text-gray-400 text-sm font-medium uppercase tracking-wide">Share this announcement</span>
                <div class="flex items-center gap-3">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(request()->url())); ?>"
                        target="_blank"
                        class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-secondary hover:text-white transition-all">
                        <i class="ri-facebook-fill text-lg"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo e(urlencode(request()->url())); ?>&text=<?php echo e(urlencode($announcement['title'])); ?>"
                        target="_blank"
                        class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-secondary hover:text-white transition-all">
                        <i class="ri-twitter-x-fill text-lg"></i>
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo e(urlencode(request()->url())); ?>&title=<?php echo e(urlencode($announcement['title'])); ?>"
                        target="_blank"
                        class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-secondary hover:text-white transition-all">
                        <i class="ri-linkedin-fill text-lg"></i>
                    </a>
                    <a href="https://api.whatsapp.com/send?text=<?php echo e(urlencode($announcement['title'] . ' ' . request()->url())); ?>"
                        target="_blank"
                        class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 hover:bg-secondary hover:text-white transition-all">
                        <i class="ri-whatsapp-fill text-lg"></i>
                    </a>
                </div>
            </div>

        </div>
    </section>

    <!-- Other Announcements -->
    <?php if(isset($relatedPosts) && count($relatedPosts) > 0): ?>
    <section class="py-16 bg-gray-50 px-4 md:px-6">
        <div class="container mx-auto max-w-7xl">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-2xl font-serif font-bold text-primary">Other Announcements</h3>
                <a href="<?php echo e(route('announcements')); ?>" class="text-secondary font-medium hover:text-primary transition-colors flex items-center gap-1">
                    View All <i class="ri-arrow-right-line"></i>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php $__currentLoopData = $relatedPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('announcement-detail', $post['slug'])); ?>" class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 block h-full">
                        <div class="aspect-video overflow-hidden relative">
                            <?php if($post['featured_image']): ?>
                                <img src="<?php echo e($post['featured_image']); ?>" 
                                     <?php if(!empty($post['featured_image_srcset'])): ?> 
                                     srcset="<?php echo e($post['featured_image_srcset']); ?>" 
                                     sizes="<?php echo e($post['featured_image_sizes']); ?>" 
                                     <?php endif; ?>
                                     alt="<?php echo e($post['title']); ?>" 
                                     loading="lazy"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <?php else: ?>
                                <div class="w-full h-full bg-linear-to-br from-accent/30 to-secondary/20 flex items-center justify-center">
                                    <i class="ri-notification-3-line text-3xl text-secondary/40"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="p-5">
                            <span class="text-xs text-gray-400 block mb-2"><?php echo e($post['date']); ?></span>
                            <h4 class="font-serif font-bold text-primary text-lg leading-tight group-hover:text-secondary transition-colors line-clamp-2 mb-2">
                                <?php echo e($post['title']); ?>

                            </h4>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\announcement-detail.blade.php ENDPATH**/ ?>