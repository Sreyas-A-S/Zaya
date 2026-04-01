<?php $__env->startSection('title', 'Site Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Site Settings</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Master Settings</li>
                    <li class="breadcrumb-item active">Site Settings</li>
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
                    <h3>General Configuration</h3>
                    <p>Update main administrative details and contact information.</p>
                </div>
                <div class="card-body">
                    <form id="generalSettingsForm" action="<?php echo e(route('admin.general-settings.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="row g-4">
                            <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="col-md-6">
                                <label class="form-label fw-bold"><?php echo e(str_replace('_', ' ', ucfirst($setting->key))); ?></label>

                                <?php if($setting->type === 'text'): ?>
                                <input type="text" name="<?php echo e($setting->key); ?>" value="<?php echo e($setting->value); ?>" class="form-control" placeholder="Enter <?php echo e(str_replace('_', ' ', $setting->key)); ?>..." <?php echo e($setting->max_length ? 'maxlength='.$setting->max_length : ''); ?>>

                                <?php elseif($setting->type === 'number'): ?>
                                <input type="number" name="<?php echo e($setting->key); ?>" value="<?php echo e($setting->value); ?>" class="form-control" placeholder="Enter <?php echo e(str_replace('_', ' ', $setting->key)); ?>...">

                                <?php elseif($setting->type === 'url'): ?>
                                <input type="url" name="<?php echo e($setting->key); ?>" value="<?php echo e($setting->value); ?>" class="form-control" placeholder="Enter <?php echo e(str_replace('_', ' ', $setting->key)); ?>...">

                                <?php elseif($setting->type === 'textarea'): ?>
                                <textarea name="<?php echo e($setting->key); ?>" class="form-control" rows="3" placeholder="Enter <?php echo e(str_replace('_', ' ', $setting->key)); ?>..." <?php echo e($setting->max_length ? 'maxlength='.$setting->max_length : ''); ?>><?php echo e($setting->value); ?></textarea>

                                <?php endif; ?>

                                <?php if($setting->max_length): ?>
                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max Characters: <?php echo e($setting->max_length); ?></div>
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
        $('#generalSettingsForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let btn = $('#saveSettingsBtn');
            let formData = form.serialize();

            // Disable button and show loading
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        if (typeof showToast === 'function') {
                            showToast(response.message);
                        } else {
                            alert(response.message);
                        }
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'An error occurred while saving.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    if (typeof showToast === 'function') {
                        showToast(errorMsg, 'error');
                    } else {
                        alert(errorMsg);
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-save me-2"></i> Save Changes');
                }
            });
        });

        <?php if(session('success')): ?>
        if (typeof showToast === 'function') {
            showToast("<?php echo e(session('success')); ?>");
        }
        <?php endif; ?>
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\admin\general-settings\index.blade.php ENDPATH**/ ?>