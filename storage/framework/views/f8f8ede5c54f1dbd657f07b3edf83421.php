<div id="bookings-container">
    <div class="bg-white rounded-2xl border border-[#2E4B3D]/12 overflow-hidden mb-8">
        <div class="p-6 border-b border-[#2E4B3D]/12">
            <h2 class="text-xl font-medium text-secondary"><?php echo e($user->role === 'translator' ? 'Translation Sessions' : 'My Bookings'); ?></h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9F9F9]">
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">SL No.</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider"><?php echo e(($user->role === 'client' || $user->role === 'patient') ? 'Practitioner' : 'Client'); ?></th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Mode</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2E4B3D]/12">
                    <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-[#FDFDFD] transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-secondary">
                            <?php echo e($loop->iteration + ($bookings->currentPage() - 1) * $bookings->perPage()); ?>

                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-secondary">
                            <?php echo e($booking->invoice_no); ?>

                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <?php if($user->role === 'client' || $user->role === 'patient'): ?>
                                    <img class="h-10 w-10 rounded-full object-cover border border-[#2E4B3D]/12" 
                                         src="<?php echo e($booking->practitioner->profile_photo_path ? asset('storage/' . $booking->practitioner->profile_photo_path) : asset('frontend/assets/profile-dummy-img.png')); ?>" 
                                         alt="">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-secondary">
                                            <?php echo e($booking->practitioner->user->name ?? 'Practitioner'); ?>

                                        </div>
                                        <div class="text-xs text-gray-400">
                                            <?php echo e($booking->practitioner->specialization ?? 'Specialist'); ?>

                                        </div>
                                    </div>
                                <?php else: ?>
                                    <img class="h-10 w-10 rounded-full object-cover border border-[#2E4B3D]/12" 
                                         src="<?php echo e($booking->user->profile_pic ? (str_starts_with($booking->user->profile_pic, 'http') ? $booking->user->profile_pic : asset('storage/' . $booking->user->profile_pic)) : asset('frontend/assets/profile-dummy-img.png')); ?>" 
                                         alt="">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-secondary">
                                            <?php echo e($booking->user->name ?? 'Patient'); ?>

                                        </div>
                                        <div class="text-xs text-gray-400">
                                            ID: <?php echo e($booking->user->patient->client_id ?? 'N/A'); ?>

                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-secondary"><?php echo e($booking->booking_date->format('M d, Y')); ?></div>
                            <div class="text-xs text-gray-400"><?php echo e($booking->booking_time); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-bold rounded-full uppercase <?php echo e($booking->mode === 'online' ? 'bg-blue-50 text-blue-600' : 'bg-purple-50 text-purple-600'); ?>">
                                <?php echo e($booking->mode); ?>

                            </span>
                            <?php if($booking->need_translator): ?>
                                <div class="mt-1">
                                    <?php if($booking->translator_id): ?>
                                        <span class="px-2 py-0.5 inline-flex text-[9px] leading-4 font-bold rounded-md bg-emerald-50 text-emerald-600 uppercase border border-emerald-100" title="Translator: <?php echo e($booking->translator->full_name); ?>">
                                            <i class="ri-translate mr-1"></i> <?php echo e($booking->translator->full_name); ?>

                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 py-0.5 inline-flex text-[9px] leading-4 font-bold rounded-md bg-amber-50 text-amber-600 uppercase border border-amber-100">
                                            <i class="ri-translate mr-1"></i> Needed
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm text-secondary font-medium">
                            € <?php echo e(number_format($booking->total_price, 2)); ?>

                        </td>
                        <td class="px-6 py-4">
                            <?php
                                $statusClasses = [
                                    'pending' => 'bg-yellow-50 text-yellow-600',
                                    'confirmed' => 'bg-green-50 text-green-600',
                                    'cancelled' => 'bg-red-50 text-red-600',
                                    'paid' => 'bg-green-50 text-green-600',
                                ];
                                $class = $statusClasses[$booking->status] ?? 'bg-gray-50 text-gray-600';
                            ?>
                            <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-bold rounded-full uppercase <?php echo e($class); ?>">
                                <?php echo e($booking->status); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="relative inline-block text-left action-dropdown">
                                <button type="button" class="text-gray-400 hover:text-secondary focus:outline-none dropdown-trigger p-2">
                                    <i class="ri-more-2-fill text-xl"></i>
                                </button>

                                <div class="dropdown-menu absolute right-0 mt-2 w-56 rounded-xl shadow-xl bg-white border border-[#2E4B3D]/12 divide-y divide-gray-50 focus:outline-none z-[100] hidden">
                                    <div class="py-1">
                                        <?php if(in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']) && $booking->practitioner_id === $user->profile_id): ?>
                                        <a href="<?php echo e(route('bookings.consultation-form.show', $booking->id)); ?>" class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors">
                                            <i class="ri-file-list-3-line mr-3 text-lg text-emerald-600"></i>
                                            Consultation Form
                                        </a>
                                        <?php endif; ?>
                                        
                                        <a href="<?php echo e(route('bookings.details-view', $booking->id)); ?>" class="group flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 hover:text-secondary transition-colors text-left">
                                            <i class="ri-eye-line mr-3 text-lg text-secondary"></i>
                                            View Details
                                        </a>

                                        <?php if(in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist']) && $booking->practitioner_id === $user->profile_id): ?>
                                        <button onclick="openReferModal(<?php echo e($booking->id); ?>, <?php echo e($booking->user_id); ?>)" class="group flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition-colors text-left">
                                            <i class="ri-user-shared-line mr-3 text-lg text-orange-500"></i>
                                            Refer
                                        </button>

                                        <?php if($booking->need_translator && !$booking->translator_id): ?>
                                        <button onclick="openTranslatorModal(<?php echo e($booking->id); ?>, '<?php echo e($booking->from_language); ?>', '<?php echo e($booking->to_language); ?>')" class="group flex items-center w-full px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors text-left">
                                            <i class="ri-translate mr-3 text-lg text-blue-500"></i>
                                            Assign Translator
                                        </button>
                                        <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if($booking->razorpay_payment_url && $booking->status === 'pending'): ?>
                                        <a href="<?php echo e($booking->razorpay_payment_url); ?>" target="_blank" class="group flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                                            <i class="ri-bank-card-line mr-3 text-lg text-blue-600"></i>
                                            Pay Now
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-20 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="ri-calendar-line text-3xl text-gray-300"></i>
                                </div>
                                <p class="text-lg font-medium text-secondary mb-1">No bookings found</p>
                                <p class="text-sm text-gray-400 mb-6">You haven't booked any sessions yet.</p>
                                <a href="<?php echo e(route('find-practitioner')); ?>" class="bg-secondary text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-primary transition-colors">Book Your First Session</a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($bookings->hasPages()): ?>
        <div class="px-6 py-4 border-t border-[#2E4B3D]/12 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Showing <span class="font-medium text-secondary"><?php echo e($bookings->firstItem()); ?></span> to <span class="font-medium text-secondary"><?php echo e($bookings->lastItem()); ?></span> of <span class="font-medium text-secondary"><?php echo e($bookings->total()); ?></span> bookings
            </div>
            <div class="flex space-x-2 pagination-links">
                <?php if($bookings->onFirstPage()): ?>
                    <span class="px-4 py-2 bg-gray-50 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed border border-gray-100">Previous</span>
                <?php else: ?>
                    <a href="<?php echo e($bookings->previousPageUrl()); ?>" class="px-4 py-2 bg-white text-secondary hover:bg-gray-50 rounded-lg text-sm font-medium border border-gray-200 transition-colors">Previous</a>
                <?php endif; ?>

                <div class="flex items-center space-x-1">
                    <?php $__currentLoopData = $bookings->getUrlRange(max(1, $bookings->currentPage() - 2), min($bookings->lastPage(), $bookings->currentPage() + 2)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($page == $bookings->currentPage()): ?>
                            <span class="w-10 h-10 flex items-center justify-center bg-secondary text-white rounded-lg text-sm font-medium"><?php echo e($page); ?></span>
                        <?php else: ?>
                            <a href="<?php echo e($url); ?>" class="w-10 h-10 flex items-center justify-center bg-white text-secondary hover:bg-gray-50 rounded-lg text-sm font-medium border border-gray-200 transition-colors"><?php echo e($page); ?></a>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <?php if($bookings->hasMorePages()): ?>
                    <a href="<?php echo e($bookings->nextPageUrl()); ?>" class="px-4 py-2 bg-white text-secondary hover:bg-gray-50 rounded-lg text-sm font-medium border border-gray-200 transition-colors">Next</a>
                <?php else: ?>
                    <span class="px-4 py-2 bg-gray-50 text-gray-400 rounded-lg text-sm font-medium cursor-not-allowed border border-gray-100">Next</span>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\wamp64\www\zaya\resources\views/partials/bookings-table.blade.php ENDPATH**/ ?>