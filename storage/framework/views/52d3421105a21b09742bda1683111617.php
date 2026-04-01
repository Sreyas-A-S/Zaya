<?php $__env->startSection('title', 'Patient Registration'); ?>

<?php $__env->startSection('content'); ?>
<?php

return null;
?>
<div class="container-fluid p-0">
    <div class="row m-0">
        <div class="col-12 p-0">
            <div class="login-card login-dark" style="background: url('<?php echo e(asset('admiro/assets/images/login/3.jpg')); ?>'); background-size: cover;">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-xl-10 col-lg-11">
                            <div class="card shadow-lg border-0 rounded-4">
                                <div class="card-body p-4 p-md-5">
                                    <div class="text-center mb-4">
                                        <a class="logo" href="<?php echo e(route('login')); ?>">
                                            <img class="img-fluid for-light m-auto mb-3 d-block" src="<?php echo e(asset('admiro/assets/images/logo/zaya wellness logo white.svg')); ?>" alt="logo" style="max-height: 60px;">
                                            <img class="img-fluid for-dark m-auto mb-3 d-block" src="<?php echo e(asset('admiro/assets/images/logo/zaya wellness logo white.svg')); ?>" alt="logo" style="max-height: 60px;">
                                        </a>
                                        <h3 class="fw-bold">Patient Registration</h3>
                                        <p class="text-muted">Register to start your wellness journey</p>
                                    </div>

                                    <!-- Stepper Indicator -->
                                    <div class="stepper-wrapper mb-5">
                                        <div class="stepper-item active" id="step-ind-1">
                                            <div class="step-counter">1</div>
                                            <div class="step-name">Personal Info</div>
                                        </div>
                                        <div class="stepper-item" id="step-ind-2">
                                            <div class="step-counter">2</div>
                                            <div class="step-name">Preferences</div>
                                        </div>
                                        <div class="stepper-item" id="step-ind-3">
                                            <div class="step-counter">3</div>
                                            <div class="step-name">Referral</div>
                                        </div>
                                    </div>

                                    <form method="POST" action="<?php echo e(route('register')); ?>" id="patientRegForm" class="theme-form">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="role" value="patient">

                                        <!-- Step 1: Personal Info -->
                                        <div class="step-content" id="step1">
                                            <h5 class="mb-3 border-bottom pb-2">A. Personal Information</h5>
                                            <div class="row g-3">
                                                <div class="col-12">
                                                    <label class="form-label">Full Name</label>
                                                    <input type="text" class="form-control" name="name" required placeholder="Full Name">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Date of Birth</label>
                                                    <input type="date" class="form-control" name="dob" id="dob" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Age</label>
                                                    <input type="number" class="form-control" name="age" id="age" readonly placeholder="Auto-calculated">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Gender</label>
                                                    <select class="form-select" name="gender" required>
                                                        <option value="">Select Gender</option>
                                                        <option value="Male">Male</option>
                                                        <option value="Female">Female</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Occupation / Lifestyle</label>
                                                    <input type="text" class="form-control" name="occupation" placeholder="Occupation">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Location / Address</label>
                                                    <textarea class="form-control" name="address" rows="2" placeholder="Full Address"></textarea>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Country Code</label>
                                                    <input type="text" class="form-control" name="mobile_country_code" placeholder="+1">
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label">Mobile Number</label>
                                                    <input type="tel" class="form-control" name="mobile_number" placeholder="1234567890">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Email Address</label>
                                                    <input type="email" class="form-control" name="email" required placeholder="name@example.com">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Password</label>
                                                    <input type="password" class="form-control" name="password" required placeholder="********">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Confirm Password</label>
                                                    <input type="password" class="form-control" name="password_confirmation" required placeholder="********">
                                                </div>
                                                <div class="col-12 mt-4">
                                                    <button type="button" class="btn btn-primary btn-lg w-100 next-step" data-next="2">Next: Preferences <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Step 2: Consultation Preferences -->
                                        <div class="step-content d-none" id="step2">
                                            <h5 class="mb-3 border-bottom pb-2">B. Consultation Preferences</h5>
                                            <div class="mb-4">
                                                <label class="form-label mb-3">Preferred Speciality of Consultation</label>
                                                <div class="row g-2">
                                                    <?php $__currentLoopData = ['Digestive Health', 'Women’s Wellness', 'Stress Management', 'Skin & Hair', 'Musculoskeletal']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="col-md-6">
                                                            <div class="form-check checkbox-primary">
                                                                <input class="form-check-input" type="checkbox" name="consultation_preferences[]" value="<?php echo e($spec); ?>" id="spec_<?php echo e(Str::slug($spec)); ?>">
                                                                <label class="form-check-label" for="spec_<?php echo e(Str::slug($spec)); ?>"><?php echo e($spec); ?></label>
                                                            </div>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>

                                            <div class="d-flex gap-3 mt-4">
                                                <button type="button" class="btn btn-outline-secondary btn-lg flex-grow-1 prev-step" data-prev="1">Back</button>
                                                <button type="button" class="btn btn-primary btn-lg flex-grow-2 next-step" data-next="3" style="flex: 2;">Next: Referral <i class="fa-solid fa-arrow-right ms-2"></i></button>
                                            </div>
                                        </div>

                                        <!-- Step 3: Language & Referral -->
                                        <div class="step-content d-none" id="step3">
                                            <h5 class="mb-3 border-bottom pb-2">C. Language & Referral Details</h5>
                                            
                                            <div class="mb-4">
                                                <label class="form-label">Languages Spoken</label>
                                                <select class="form-select" name="languages_spoken[]" multiple aria-label="Select languages" style="height: 120px;">
                                                    <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($lang->name); ?>"><?php echo e($lang->flag); ?> <?php echo e($lang->name); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <small class="text-muted">Hold Ctrl/Cmd to select multiple.</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Referral Type</label>
                                                <select class="form-select" name="referral_type" id="referral_type">
                                                    <option value="">Select Referral Type</option>
                                                    <option value="Self">Self</option>
                                                    <option value="Practitioner">Practitioner</option>
                                                    <option value="Client">Client</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>

                                            <div class="mb-4 d-none" id="referrer_name_div">
                                                <label class="form-label">Name of Referring Practitioner or Client</label>
                                                <input type="text" class="form-control" name="referrer_name">
                                            </div>

                                            <div class="d-flex gap-3 mt-4">
                                                <button type="button" class="btn btn-outline-secondary btn-lg flex-grow-1 prev-step" data-prev="2">Back</button>
                                                <button type="submit" class="btn btn-success btn-lg flex-grow-2" style="flex: 2;">Register <i class="fa-solid fa-check-circle ms-2"></i></button>
                                            </div>
                                        </div>

                                    </form>
                                    
                                    <div class="text-center mt-5">
                                        <p class="mb-0">Already have an account? <a class="fw-bold text-primary" href="<?php echo e(route('login')); ?>">Sign in here</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .login-card {
        min-height: 100vh;
        display: flex;
        align-items: center;
        padding: 50px 0;
    }
    .stepper-wrapper {
        display: flex;
        justify-content: space-between;
        position: relative;
    }
    .stepper-wrapper::before {
        content: "";
        position: absolute;
        top: 25px;
        left: 0;
        right: 0;
        height: 2px;
        background: #eee;
        z-index: 0;
    }
    .stepper-item {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        cursor: pointer;
    }
    .step-counter {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #eee;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: bold;
        margin-bottom: 10px;
        transition: all 0.3s;
    }
    .step-name {
        font-size: 13px;
        font-weight: 600;
        color: #999;
        transition: all 0.3s;
    }
    .stepper-item.active .step-counter {
        background: var(--theme-default);
        border-color: var(--theme-default);
        color: #fff;
        box-shadow: 0 0 0 5px rgba(var(--theme-default-rgb), 0.1);
    }
    .stepper-item.active .step-name {
        color: var(--theme-default);
    }
    .stepper-item.completed .step-counter {
        background: #51bb25;
        border-color: #51bb25;
        color: #fff;
    }
    .bg-info-light {
        background-color: rgba(var(--theme-default-rgb), 0.05);
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--theme-default);
        box-shadow: 0 0 0 0.25rem rgba(var(--theme-default-rgb), 0.1);
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    $(document).ready(function() {
        const steps = $('.step-content');
        const stepperItems = $('.stepper-item');

        function updateStep(step) {
            steps.addClass('d-none');
            $('#step' + step).removeClass('d-none');

            stepperItems.each(function() {
                const item = $(this);
                const s = parseInt(item.attr('id').replace('step-ind-', ''));
                if (s < step) {
                    item.addClass('completed').removeClass('active');
                } else if (s === step) {
                    item.addClass('active').removeClass('completed');
                } else {
                    item.removeClass('active completed');
                }
            });
            
            window.scrollTo(0, 0);
        }

        $('.next-step').on('click', function() {
            const next = parseInt($(this).data('next'));
            updateStep(next);
        });

        $('.prev-step').on('click', function() {
            const prev = parseInt($(this).data('prev'));
            updateStep(prev);
        });

        stepperItems.on('click', function() {
            const step = parseInt($(this).attr('id').replace('step-ind-', ''));
            updateStep(step);
        });

        // Auto-calculate Age
        $('#dob').on('change', function() {
            const dob = new Date($(this).val());
            const today = new Date();
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            $('#age').val(age);
        });

        // Toggle Referrer Name
        $('#referral_type').on('change', function() {
            const type = $(this).val();
            if (type === 'Practitioner' || type === 'Client') {
                $('#referrer_name_div').removeClass('d-none');
            } else {
                $('#referrer_name_div').addClass('d-none');
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\auth\register_patient.blade.php ENDPATH**/ ?>