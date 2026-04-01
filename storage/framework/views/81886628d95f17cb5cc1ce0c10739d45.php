<?php $__env->startSection('title', 'Finance Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Finance Settings</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Finance</li>
                    <li class="breadcrumb-item active">Other Fees</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border">
                    <h3>Fees Configuration</h3>
                    <p>Update registration fees and other financial parameters.</p>
                </div>
                <div class="card-body">
                    <form id="financeSettingsForm" action="<?php echo e(route('admin.other-fees.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php $settingsByKey = $settings->keyBy('key'); ?>

                        <?php
                            $feePairs = [
                                ['fee' => 'client_registration_fee', 'enable' => 'client_registration_fee_enabled', 'currency' => 'client_registration_fee_currency'],
                                ['fee' => 'practitioner_registration_fee', 'enable' => 'practitioner_registration_fee_enabled', 'currency' => 'practitioner_registration_fee_currency'],
                                ['fee' => 'doctor_registration_fee', 'enable' => 'doctor_registration_fee_enabled', 'currency' => 'doctor_registration_fee_currency'],
                                ['fee' => 'mindfulness_registration_fee', 'enable' => 'mindfulness_registration_fee_enabled', 'currency' => 'mindfulness_registration_fee_currency'],
                                ['fee' => 'yoga_registration_fee', 'enable' => 'yoga_registration_fee_enabled', 'currency' => 'yoga_registration_fee_currency'],
                                ['fee' => 'translator_registration_fee', 'enable' => 'translator_registration_fee_enabled', 'currency' => 'translator_registration_fee_currency'],
                            ];
                            $currencyOptions = ['EUR','USD','INR','GBP','AED'];
                        ?>

                        <div class="row g-4">
                            <?php $__currentLoopData = $feePairs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pair): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $feeKey = $pair['fee'];
                                    $enableKey = $pair['enable'];
                                    $currencyKey = $pair['currency'];
                                ?>
                                <?php if(isset($settingsByKey[$feeKey])): ?>
                                    <?php
                                        $feeSetting = $settingsByKey[$feeKey];
                                        $enableSetting = $settingsByKey[$enableKey] ?? null;
                                        $currencySetting = $settingsByKey[$currencyKey] ?? null;
                                        $feeId = $feeKey . '-input';
                                        $enableId = $enableKey . '-input';
                                        $currencyId = $currencyKey . '-input';
                                        $enabled = $enableSetting ? filter_var($enableSetting->value, FILTER_VALIDATE_BOOLEAN) : false;
                                        $currencyValue = $currencySetting->value ?? 'EUR';
                                    ?>
                                    <div class="col-md-6 d-flex align-items-start justify-content-between gap-3 flex-wrap">
                                        <div class="flex-grow-1">
                                            <label class="form-label fw-bold" for="<?php echo e($feeId); ?>"><?php echo e(ucwords(str_replace('_', ' ', $feeKey))); ?></label>
                                            <div class="input-group">
                                                <select class="form-select w-auto" style="max-width:110px" name="<?php echo e($currencyKey); ?>" id="<?php echo e($currencyId); ?>">
                                                    <?php $__currentLoopData = $currencyOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($opt); ?>" <?php echo e($currencyValue === $opt ? 'selected' : ''); ?>><?php echo e($opt); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                                <input type="number" step="0.01"
                                                    name="<?php echo e($feeKey); ?>"
                                                    id="<?php echo e($feeId); ?>"
                                                    value="<?php echo e($feeSetting->value); ?>"
                                                    class="form-control"
                                                    placeholder="Amount">
                                            </div>
                                        </div>
                                        <?php if($enableSetting): ?>
                                        <div class="ms-auto pt-4">
                                            <div class="form-check form-switch">
                                                <input type="hidden" name="<?php echo e($enableKey); ?>" value="0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    id="<?php echo e($enableId); ?>"
                                                    name="<?php echo e($enableKey); ?>"
                                                    value="1"
                                                    <?php echo e($enabled ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="<?php echo e($enableId); ?>"><?php echo e(__('Enabled')); ?></label>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <hr class="my-5">

                        <div class="row g-4">
                            <div class="col-12">
                                <h4 class="mb-0">Commission Configuration</h4>
                                <p class="text-muted">Set percentage shares for bookings and referrals.</p>
                            </div>

                            <?php
                                $commissionSettings = [
                                    'company_booking_commission' => 'Company Booking Commission (%)',
                                    'company_referral_commission' => 'Company Referral Commission (%)',
                                    'practitioner_referral_commission' => 'Practitioner Referral Commission (%)'
                                ];
                            ?>

                            <?php $__currentLoopData = $commissionSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if(isset($settingsByKey[$key])): ?>
                                    <?php 
                                        $setting = $settingsByKey[$key]; 
                                        $inputId = $key . '-input';
                                    ?>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold" for="<?php echo e($inputId); ?>"><?php echo e($label); ?></label>
                                        <div class="input-group">
                                            <input type="number" step="0.01" min="0" max="100"
                                                name="<?php echo e($key); ?>"
                                                id="<?php echo e($inputId); ?>"
                                                value="<?php echo e($setting->value); ?>"
                                                class="form-control"
                                                placeholder="Enter percentage (0-100)...">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="row g-4 mt-2">
                            <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $skip = false;
                                    foreach($feePairs as $pair){
                                        if(in_array($setting->key, [$pair['fee'], $pair['enable'], $pair['currency']], true)){
                                            $skip = true; break;
                                        }
                                    }
                                    if(array_key_exists($setting->key, $commissionSettings)) $skip = true;
                                ?>
                                <?php if($skip): ?> <?php continue; ?> <?php endif; ?>
                                <?php
                                    $fieldLabel = ucwords(str_replace('_', ' ', $setting->key));
                                    $placeholder = str_replace('_', ' ', $setting->key);
                                    $inputId = $setting->key . '-input';
                                    $isChecked = filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
                                ?>
                                <div class="col-md-6 d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <label class="form-label fw-bold" for="<?php echo e($inputId); ?>"><?php echo e($fieldLabel); ?></label>

                                    <?php if($setting->type === 'number' || $setting->type === 'text'): ?>
                                        <input type="<?php echo e($setting->type === 'number' ? 'number' : 'text'); ?>"
                                            step="0.01"
                                            name="<?php echo e($setting->key); ?>"
                                            id="<?php echo e($inputId); ?>"
                                            value="<?php echo e($setting->value); ?>"
                                            class="form-control"
                                            placeholder="Enter <?php echo e($placeholder); ?>...">
                                    <?php elseif($setting->type === 'boolean'): ?>
                                        <div class="ms-auto">
                                            <div class="form-check form-switch mt-0">
                                                <input type="hidden" name="<?php echo e($setting->key); ?>" value="0">
                                                <input class="form-check-input" type="checkbox"
                                                    role="switch"
                                                    id="<?php echo e($inputId); ?>"
                                                    name="<?php echo e($setting->key); ?>"
                                                    value="1"
                                                    <?php echo e($isChecked ? 'checked' : ''); ?>>
                                                <label class="form-check-label" for="<?php echo e($inputId); ?>">
                                                    <?php echo e(__('Enabled')); ?>

                                                </label>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="card-footer text-end mt-4">
                            <button type="submit" id="saveSettingsBtn" class="btn btn-primary px-5">
                                <i class="fa-solid fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#financeSettingsForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let btn = $('#saveSettingsBtn');
            let formData = form.serialize();

            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        showToast(response.message);
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'An error occurred while saving.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    showToast(errorMsg, 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-save me-2"></i> Save Changes');
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\admin\finance-settings\index.blade.php ENDPATH**/ ?>