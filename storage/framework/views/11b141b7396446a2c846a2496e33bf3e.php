<div id="conferences-container">
    <div class="bg-white rounded-2xl border border-[#2E4B3D]/12 overflow-hidden mb-8">
        <div class="p-6 border-b border-[#2E4B3D]/12 flex flex-col sm:flex-row justify-between items-center gap-4">
            <h2 class="text-xl font-medium text-secondary"><?php echo e($user->role === 'translator' ? 'Assigned Conferences' : 'Conference History'); ?></h2>
            <div class="flex flex-wrap gap-3">
                <a href="<?php echo e(route('conference.join', ['channel' => 'zaya-' . strtolower(Str::random(10)), 'provider' => 'jaas'])); ?>" 
                   target="_blank" 
                   class="px-5 py-3 bg-[#F3F6F4] text-secondary rounded-full font-medium flex items-center gap-2 hover:bg-[#E8EFEA] transition-all border border-[#2E4B3D]/10">
                    <i class="ri-team-line text-lg"></i>
                    Start Video Meet
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-[#F9F9F9]">
                        <th class="px-6 py-4 text-xs font-bold text-[#5F6A63] uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-4 text-xs font-bold text-[#5F6A63] uppercase tracking-wider"><?php echo e(($user->role === 'client' || $user->role === 'patient') ? 'Practitioner' : 'Client'); ?></th>
                        <th class="px-6 py-4 text-xs font-bold text-[#5F6A63] uppercase tracking-wider">Duration</th>
                        <th class="px-6 py-4 text-xs font-bold text-[#5F6A63] uppercase tracking-wider">Provider</th>
                        <th class="px-6 py-4 text-xs font-bold text-[#5F6A63] uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#2E4B3D]/12">
                    <?php $__empty_1 = true; $__currentLoopData = $conferences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conference): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-[#FDFDFD] transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-secondary"><?php echo e($conference->start_time?->format('M d, Y')); ?></div>
                            <div class="text-[11px] text-gray-400 mt-0.5"><?php echo e($conference->start_time?->format('h:i A')); ?></div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gray-100 mr-3 flex items-center justify-center overflow-hidden border border-gray-200">
                                    <?php
                                        $targetUser = ($user->role === 'client' || $user->role === 'patient') 
                                            ? ($conference->booking->practitioner->user ?? null)
                                            : ($conference->booking->user ?? null);
                                    ?>
                                    <img src="<?php echo e(($targetUser && $targetUser->profile_pic) ? (str_starts_with($targetUser->profile_pic, 'http') ? $targetUser->profile_pic : asset('storage/' . $targetUser->profile_pic)) : asset('frontend/assets/profile-dummy-img.png')); ?>" class="w-full h-full object-cover">
                                </div>
                                <span class="text-sm font-medium text-gray-900">
                                    <?php echo e($targetUser->name ?? 'N/A'); ?>

                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-black uppercase tracking-widest rounded-full bg-emerald-50 text-emerald-600">
                                <?php echo e($conference->duration_minutes); ?> mins
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-[10px] text-gray-500 uppercase font-black"><?php echo e($conference->provider); ?></span>
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-3">
                                <?php if($conference->recording_url): ?>
                                    <a href="<?php echo e($conference->recording_url); ?>" target="_blank" class="px-4 py-2 rounded-full bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-all flex items-center gap-2 text-xs font-bold">
                                        <i class="ri-play-circle-line text-lg"></i>
                                        Watch Recording
                                    </a>
                                <?php endif; ?>
                                
                                <button onclick="viewBookingDetails(<?php echo e($conference->booking_id); ?>)" class="px-4 py-2 rounded-full bg-gray-50 text-secondary hover:bg-gray-100 transition-all flex items-center gap-2 text-xs font-bold border border-gray-100">
                                    <i class="ri-eye-line text-lg"></i>
                                    Details
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="ri-vidicon-line text-3xl text-gray-300"></i>
                                </div>
                                <p class="text-lg font-medium text-secondary mb-1">No conferences found</p>
                                <p class="text-sm text-gray-400">You haven't attended any online sessions yet.</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if($conferences->hasPages()): ?>
        <div class="px-6 py-4 border-t border-[#2E4B3D]/12 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Showing <span class="font-medium text-secondary"><?php echo e($conferences->firstItem()); ?></span> to <span class="font-medium text-secondary"><?php echo e($conferences->lastItem()); ?></span> of <span class="font-medium text-secondary"><?php echo e($conferences->total()); ?></span> conferences
            </div>
            <div class="flex space-x-2 pagination-links">
                <?php echo e($conferences->links()); ?>

            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\wamp64\www\zaya\resources\views\partials\conferences-table.blade.php ENDPATH**/ ?>