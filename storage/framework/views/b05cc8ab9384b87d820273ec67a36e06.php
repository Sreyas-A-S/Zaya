<?php
    $user = Auth::user();
    $practitionerRoles = ['practitioner', 'doctor', 'mindfulness_practitioner', 'mindfulness-practitioner', 'yoga_therapist', 'yoga-therapist', 'translator'];
    $isPractitioner = in_array($user->role, $practitionerRoles);
?>

<div class="lg:hidden flex space-x-6 overflow-x-auto scrollbar-hide mb-5 pb-2">
    <!-- Dashboard -->
    <?php if(request()->routeIs('dashboard')): ?>
        <button onclick="if(typeof switchMobileTab === 'function') switchMobileTab('dashboard')" 
            class="leading-none text-base text-secondary font-bold whitespace-nowrap cursor-pointer transition-colors border-b-2 border-secondary pb-1 flex items-center shrink-0">
            <?php echo e(__($site_settings['client_panel_sidebar_dashboard'] ?? 'Dashboard')); ?>

        </button>
    <?php else: ?>
        <a href="<?php echo e(route('dashboard')); ?>" 
            class="leading-none text-base text-[#8F8F8F] font-medium whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
            <?php echo e(__($site_settings['client_panel_sidebar_dashboard'] ?? 'Dashboard')); ?>

        </a>
    <?php endif; ?>

    <!-- Profile -->
    <a href="<?php echo e(route('profile')); ?>" 
        class="leading-none text-base <?php echo e(request()->routeIs('profile') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium'); ?> whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        <img src="<?php echo e($user->profile_pic ? (str_starts_with($user->profile_pic, 'http') ? $user->profile_pic : asset('storage/' . $user->profile_pic)) : asset('frontend/assets/profile-dummy-img.png')); ?>" class="w-5 h-5 rounded-full mr-2 object-cover">
        <?php echo e(__('Profile')); ?>

    </a>

    <!-- Health Journey -->
    <?php if(in_array($user->role, ['client', 'patient'])): ?>
    <a href="<?php echo e(route('health-journey.index')); ?>" 
        class="leading-none text-base <?php echo e(request()->routeIs('health-journey.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium'); ?> whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        <?php echo e(__($site_settings['client_panel_sidebar_health_journey'] ?? 'Health Journey')); ?>

    </a>
    <?php endif; ?>

    <!-- Consultation -->
    <?php if(in_array($user->role, ['doctor', 'practitioner', 'mindfulness_practitioner', 'yoga_therapist', 'translator'])): ?>
    <a href="<?php echo e(route('consultations.index')); ?>"
        class="leading-none text-base <?php echo e(request()->routeIs('consultations.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium'); ?> whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        <?php echo e(__($site_settings['client_panel_sidebar_consultation'] ?? 'Consultation')); ?>

    </a>
    <?php endif; ?>

    <!-- Bookings -->
    <?php if(in_array($user->role, ['client', 'patient'])): ?>
    <a href="<?php echo e(route('bookings.index')); ?>" 
        class="leading-none text-base <?php echo e(request()->routeIs('bookings.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium'); ?> whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        <?php echo e(__($site_settings['client_panel_sidebar_bookings'] ?? 'Bookings')); ?>

    </a>
    <?php endif; ?>

    <!-- Conference History -->
    <?php if(in_array($user->role, ['client', 'patient'])): ?>
    <a href="<?php echo e(route('conferences.index')); ?>" 
        class="leading-none text-base <?php echo e(request()->routeIs('conferences.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium'); ?> whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        <?php echo e(__('My Conferences')); ?>

    </a>
    <?php else: ?>
    <a href="<?php echo e(route('conferences.index')); ?>" 
        class="leading-none text-base <?php echo e(request()->routeIs('conferences.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium'); ?> whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        <?php echo e(__($site_settings['client_panel_sidebar_conference_history'] ?? 'Conferences')); ?>

    </a>
    <?php endif; ?>

    <!-- Transaction Vault -->
    <?php if(request()->routeIs('dashboard')): ?>
        <button onclick="if(typeof switchMobileTab === 'function') switchMobileTab('transactions')" 
            class="leading-none text-base text-[#8F8F8F] font-medium whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
            <?php echo e(__($site_settings['client_panel_sidebar_transaction_vault'] ?? 'Transaction Vault')); ?>

        </button>
    <?php else: ?>
        <a href="<?php echo e(route('transactions.index')); ?>" 
            class="leading-none text-base <?php echo e(request()->routeIs('transactions.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium'); ?> whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
            <?php echo e(__($site_settings['client_panel_sidebar_transaction_vault'] ?? 'Transaction Vault')); ?>

        </a>
    <?php endif; ?>

    <!-- Reviews -->
    <a href="<?php echo e(route('reviews.index')); ?>" 
        class="leading-none text-base <?php echo e(request()->routeIs('reviews.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium'); ?> whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        <?php echo e(__('Reviews')); ?>

    </a>

    <?php if($isPractitioner): ?>
        <!-- Time Slots -->
        <a href="<?php echo e(route('time-slots.index')); ?>" 
            class="leading-none text-base <?php echo e(request()->routeIs('time-slots.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium'); ?> whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
            <?php echo e(__($site_settings['client_panel_sidebar_time_slots'] ?? 'Time Slots')); ?>

        </a>

        <!-- My Services -->
        <a href="<?php echo e(route('my-services.index')); ?>" 
            class="leading-none text-base <?php echo e(request()->routeIs('my-services.index') ? 'text-secondary border-b-2 border-secondary pb-1 font-bold' : 'text-[#8F8F8F] font-medium'); ?> whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
            <?php echo e(__('My Services')); ?>

        </a>
    <?php endif; ?>

    <!-- Logout -->
    <a href="javascript:void(0)" 
        onclick="openLogoutModal()"
        class="leading-none text-base text-red-500 font-bold whitespace-nowrap cursor-pointer transition-colors flex items-center shrink-0">
        <?php echo e(__($site_settings['client_panel_sidebar_logout'] ?? 'Logout')); ?>

    </a>
</div>

<style>
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>
<?php /**PATH C:\wamp64\www\zaya\resources\views\partials\client-mobile-nav.blade.php ENDPATH**/ ?>