<?php $__env->startSection('content'); ?>
    <?php
        $firstName = $practitioner->first_name ?? $practitioner->user->first_name ?? 'Professional';
        $lastName = $practitioner->last_name ?? $practitioner->user->last_name ?? '';
        $bio = $practitioner->profile_bio ?? $practitioner->short_doctor_bio ?? $practitioner->short_bio ?? '';
        $photo = $practitioner->profile_photo_path ?? $practitioner->profile_pic ?? '';
        
        // Dynamic Lists
        $consultations = $practitioner->consultations ?? $practitioner->specialization ?? $practitioner->practitioner_type ?? $practitioner->yoga_therapist_type ?? [];
        if (!is_array($consultations)) $consultations = [$consultations];

        $therapies = $practitioner->body_therapies ?? $practitioner->health_conditions_treated ?? $practitioner->services_offered ?? [];
        if (!is_array($therapies)) $therapies = [$therapies];

        $modalities = $practitioner->other_modalities ?? $practitioner->areas_of_expertise ?? $practitioner->consultation_expertise ?? [];
        if (!is_array($modalities)) $modalities = [$modalities];

        // Subtitle logic
        $subtitle = $modalities[0] ?? ($consultations[0] ?? ($practitioner->user->role ?? 'Professional'));
        $subtitle = str_replace('_', ' ', ucfirst($subtitle));

        $avgRating = $practitioner->average_rating ?? 5.0;
        $reviewCount = $practitioner->reviews ? $practitioner->reviews->count() : 0;
        $bookingUrl = route('book-session', ['practitioner' => $practitioner->slug, 'service' => request('service')]);
    ?>

    <!-- Practitioner Hero Section -->
    <section class="pt-[144px] md:pt-[150px] pb-20 px-4 md:px-6 bg-white">
        <div class="container mx-auto">
            <div
                class="bg-[#E8E8E8] rounded-[30px] px-8 md:px-12 flex flex-col md:flex-row items-center relative gap-8 md:gap-12 overflow-hidden shadow-sm">

                <!-- Left Image (Practitioner) -->
                <div class="w-full md:w-5/12 relative pt-10 flex items-end justify-center">
                    <img src="<?php echo e($photo ? asset('storage/' . $photo) : asset('frontend/assets/lilly-profile-pic.png')); ?>" alt="<?php echo e($firstName); ?>" class="h-full">
                </div>

                <!-- Right Content -->
                <div class="w-full md:w-7/12 py-12 md:pl-4">
                    <h1 class="text-4xl md:text-5xl font-serif font-medium text-black mb-5">I’m <?php echo e($firstName); ?> <?php echo e($lastName); ?>,</h1>
                    <h2 class="text-3xl md:text-4xl font-sans! font-medium text-primary mb-7 leading-tight">
                        <?php echo e($subtitle); ?>

                    </h2>
                    <p class="text-[#404040] mb-10 max-w-xl leading-relaxed text-base opacity-80">
                        <?php echo e($bio); ?>

                    </p>

                    <div class="flex flex-col items-start gap-10">
                        <?php
                            $user = auth()->user();
                            $isClient = $user && ($user->role === 'client' || $user->role === 'patient');
                        ?>

                        <?php if(!$user): ?>
                            <a href="<?php echo e(route('zaya-login', ['redirect' => $bookingUrl])); ?>"
                                class="bg-secondary text-white px-8 py-3.5 rounded-full font-normal shadow-lg hover:bg-primary transition-colors text-lg">
                                <?php echo e($site_settings['practitioner_book_session_btn'] ?? 'Book a Session'); ?>

                            </a>
                        <?php elseif($isClient): ?>
                            <a href="<?php echo e($bookingUrl); ?>"
                                class="bg-secondary text-white px-8 py-3.5 rounded-full font-normal shadow-lg hover:bg-primary transition-colors text-lg">
                                <?php echo e($site_settings['practitioner_book_session_btn'] ?? 'Book a Session'); ?>

                            </a>
                        <?php else: ?>
                            <button type="button" 
                                onclick="showZayaToast('Booking is only available for client accounts. Please log in with a client account to proceed.', 'error', 'Access Restricted')"
                                class="bg-secondary text-white px-8 py-3.5 rounded-full font-normal shadow-lg hover:bg-primary transition-colors text-lg">
                                <?php echo e($site_settings['practitioner_book_session_btn'] ?? 'Book a Session'); ?>

                            </button>
                        <?php endif; ?>

                        <!-- Rating Block -->
                        <div class="flex flex-wrap items-center gap-9 xl:gap-18">
                            <div class="flex flex-col items-start align-center">
                                <div class="flex items-center gap-3">
                                    <span class="text-4xl font-bold text-[#1D1D1D] leading-none"><?php echo e(number_format($avgRating, 1)); ?></span>
                                    <div class="flex flex-col gap-1 align-center">
                                        <div class="flex text-[#37B46B] text-lg gap-2">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if($i <= round($avgRating)): ?>
                                                    <i class="ri-star-fill"></i>
                                                <?php else: ?>
                                                    <i class="ri-star-line"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                        <span class="text-xs text-[#404040] opacity-80"><?php echo e($site_settings['practitioner_based_on'] ?? 'Based on'); ?> <?php echo e($reviewCount); ?> <?php echo e($site_settings['practitioner_reviewers'] ?? 'Reviewers'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Avatars -->
                            <div class="flex items-center gap-3">
                                <div class="flex -space-x-4">
                                    <img src="https://ui-avatars.com/api/?name=User+1&background=random" class="w-10 h-10 rounded-full border-1 border-black object-cover">
                                    <img src="https://ui-avatars.com/api/?name=User+2&background=random" class="w-10 h-10 rounded-full border-1 border-black object-cover">
                                    <img src="https://ui-avatars.com/api/?name=User+3&background=random" class="w-10 h-10 rounded-full border-1 border-black object-cover">
                                    <div class="w-10 h-10 rounded-full border-1 border-black bg-[#4DD385] text-black text-[10px] flex items-center justify-center font-bold z-10">+<?php echo e($reviewCount); ?></div>
                                </div>
                                <span class="text-sm font-medium text-gray-600 block leading-tight"><?php echo e($site_settings['practitioner_reviews_label'] ?? "Client's Reviews"); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="pb-16 bg-white px-4 md:px-6">
        <div class="container mx-auto text-center">
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                <div class="bg-white rounded-2xl shadow-[0_0px_72px_rgba(186,186,186,0.45)] border border-gray-100 px-16 py-10 text-center w-full xl:w-auto xl:min-w-[500px]">
                    <h3 class="text-5xl md:text-6xl font-sans! font-medium text-gray-800 mb-4">100+</h3>
                    <p class="text-gray-500 text-xl"><?php echo e($site_settings['practitioner_total_sessions'] ?? 'Total No.of Sessions'); ?></p>
                </div>
                <div class="bg-white rounded-2xl shadow-[0_0px_72px_rgba(186,186,186,0.45)] border border-gray-100 px-16 py-10 text-center w-full xl:w-auto xl:min-w-[500px]">
                    <h3 class="text-5xl md:text-6xl font-sans! font-medium text-gray-800 mb-4">80+</h3>
                    <p class="text-gray-500 text-xl"><?php echo e($site_settings['practitioner_total_clients'] ?? 'Total No.of Clients'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Expertise Section -->
    <section class="pb-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-[#A66E58] mb-4"><?php echo e($site_settings['practitioner_legacy_title'] ?? 'A Legacy of Expertise'); ?></h2>
                <h3 class="text-2xl md:text-3xl font-serif text-[#4A7060]"><?php echo e($site_settings['practitioner_legacy_subtitle'] ?? 'Precision and Passion Across Every Field'); ?></h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 text-center md:text-left">
                <!-- Column 1 -->
                <div class="flex flex-col items-center md:items-start pl-0 md:pl-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-[#56B280] flex items-center justify-center text-white text-3xl shadow-md"><i class="ri-briefcase-4-line"></i></div>
                        <h4 class="text-xl font-sans! font-bold text-black"><?php echo e($site_settings['practitioner_consultations_title'] ?? 'Consultations'); ?></h4>
                    </div>
                    <ul class="text-gray-500 space-y-3 text-lg leading-relaxed">
                        <?php $__currentLoopData = $consultations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <li><?php echo e($item); ?></li> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <!-- Column 2 -->
                <div class="flex flex-col items-center md:items-start pl-0 md:pl-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-[#56B280] flex items-center justify-center text-white text-3xl shadow-md"><i class="ri-shield-check-line"></i></div>
                        <h4 class="text-xl font-sans! font-bold text-black"><?php echo e($site_settings['practitioner_therapies_title'] ?? 'Therapies'); ?></h4>
                    </div>
                    <ul class="text-gray-500 space-y-3 text-lg leading-relaxed">
                        <?php $__currentLoopData = $therapies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <li><?php echo e($item); ?></li> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <!-- Column 3 -->
                <div class="flex flex-col items-center md:items-start pl-0 md:pl-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-full bg-[#56B280] flex items-center justify-center text-white text-3xl shadow-md"><i class="ri-user-heart-line"></i></div>
                        <h4 class="text-xl font-sans! font-bold text-black"><?php echo e($site_settings['practitioner_modalities_title'] ?? 'Other Modalities'); ?></h4>
                    </div>
                    <ul class="text-gray-500 space-y-3 text-lg leading-relaxed">
                        <?php $__currentLoopData = $modalities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <li><?php echo e($item); ?></li> <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6 relative">
            <div class="text-center mb-16 max-w-4xl mx-auto">
                <h2 class="text-4xl md:text-5xl font-serif font-bold text-[#A66E58] mb-6"><?php echo e($site_settings['practitioner_stories_title'] ?? 'Stories of Transformation'); ?></h2>
                <p class="text-gray-500 leading-relaxed text-lg"><?php echo e($site_settings['practitioner_stories_description'] ?? 'The true measure of ZAYA Wellness lies in the journeys of our members.'); ?></p>
            </div>

            <div class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6">
                <?php if($practitioner->reviews): ?>
                    <?php $__empty_1 = true; $__currentLoopData = $practitioner->reviews->where('status', true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="break-inside-avoid bg-white p-8 rounded-2xl shadow-[0_8px_48px_rgba(134,134,134,0.25)] border border-gray-100 transition-shadow">
                        <div class="flex items-center gap-4 mb-6">
                            <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($review->user->name ?? 'User')); ?>&background=random" class="w-12 h-12 rounded-full object-cover">
                            <div>
                                <h4 class="font-bold text-black text-lg"><?php echo e($review->user->name ?? 'Anonymous'); ?></h4>
                                <p class="text-gray-400 text-xs uppercase"><?php echo e($review->created_at->format('M d, Y')); ?></p>
                            </div>
                        </div>
                        <p class="text-gray-600 text-sm leading-relaxed mb-6">"<?php echo e($review->comment); ?>"</p>
                        <div class="flex text-[#DEDD66] gap-1 text-lg">
                            <?php for($i = 1; $i <= 5; $i++): ?> <?php if($i <= $review->rating): ?> <i class="ri-star-fill"></i> <?php else: ?> <i class="ri-star-line"></i> <?php endif; ?> <?php endfor; ?>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full text-center py-10"><p class="text-gray-500 italic"><?php echo e($site_settings['practitioner_no_reviews'] ?? 'No reviews yet.'); ?></p></div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Bottom CTA -->
    <section class="py-4 mb-20">
        <div class="container-fluid mx-auto">
            <div class="bg-[#F9EBD6] px-8 md:px-12 py-5 flex flex-col md:flex-row items-center justify-center gap-4 md:gap-8">
                <p class="text-gray-700 text-base md:text-lg text-center md:text-left">
                    <?php echo e($site_settings['practitioner_cta_text'] ?? 'Ready to start your wellness journey with'); ?> <?php echo e($firstName); ?> <?php echo e($lastName); ?>?
                </p>
                <a href="<?php echo e($bookingUrl); ?>" class="bg-secondary text-white px-8 py-3 rounded-full font-normal hover:bg-primary transition-colors text-sm md:text-base whitespace-nowrap">
                    <?php echo e($site_settings['practitioner_book_session_btn'] ?? 'Book a Session'); ?>

                </a>
            </div>
        </div>
    </section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views/practitioner-detail.blade.php ENDPATH**/ ?>