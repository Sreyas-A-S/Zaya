<?php $__env->startSection('title', 'About Us Settings'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .nav-pills .nav-link {
        color: #555;
        border-radius: 8px;
        transition: all 0.3s ease;
        padding: 12px 20px;
        margin-bottom: 5px;
    }

    .nav-pills .nav-link.active {
        background-color: var(--theme-default) !important;
        color: #fff !important;
    }

    .nav-pills .nav-link:hover:not(.active) {
        background-color: var(--bs-gray-100);
    }

    .btn-primary {
        background-color: var(--theme-default) !important;
        border-color: var(--theme-default) !important;
    }

    .btn-primary:hover {
        opacity: 0.9;
        background-color: var(--theme-default) !important;
        border-color: var(--theme-default) !important;
    }

    .tab-content {
        border-left: 1px solid #eee;
        min-height: 400px;
    }
</style>
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>About Us Settings</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active">About Us Settings</li>
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
                    <h3>Manage About Us Content</h3>
                    <p>Update content for the About Us page, including the banner and team sections.</p>
                </div>
                <div class="card-body">
                    <form id="aboutSettingsForm" action="<?php echo e(route('admin.about-settings.update')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <ul class="nav nav-pills flex-column h-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link active text-start mb-2" id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button" role="tab" aria-controls="v-pills-general" aria-selected="true">
                                        <i class="fa-solid fa-circle-info me-2"></i> General
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-banner-tab" data-bs-toggle="pill" data-bs-target="#v-pills-banner" type="button" role="tab" aria-controls="v-pills-banner" aria-selected="false">
                                        <i class="fa-solid fa-image me-2"></i> Banner
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-team-tab" data-bs-toggle="pill" data-bs-target="#v-pills-team" type="button" role="tab" aria-controls="v-pills-team" aria-selected="false">
                                        <i class="fa-solid fa-users me-2"></i> Team
                                    </button>
                                </ul>
                            </div>
                            <div class="col-md-9 border-start">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <?php
                                    $bannerSettings = $settings->filter(fn($s) => Str::contains($s->key, 'banner'));
                                    $teamSettings = $settings->filter(fn($s) => Str::contains($s->key, 'team'));
                                    $generalSettings = $settings->filter(fn($s) => !Str::contains($s->key, 'banner') && !Str::contains($s->key, 'team'));
                                    ?>

                                    <!-- General Tab -->
                                    <div class="tab-pane fade show active p-3" id="v-pills-general" role="tabpanel" aria-labelledby="v-pills-general-tab">
                                        <div class="row g-4">
                                            <?php $__currentLoopData = $generalSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-12">
                                                <label class="form-label fw-bold"><?php echo e(str_replace('_', ' ', ucfirst($setting->key))); ?></label>

                                                <?php if($setting->type === 'text'): ?>
                                                <input type="text" id="<?php echo e($setting->key); ?>" name="<?php echo e($setting->key); ?>" value="<?php echo e($setting->value); ?>" class="form-control" placeholder="Enter content..." <?php echo e($setting->max_length ? 'maxlength='.$setting->max_length : ''); ?>>
                                                <?php if($setting->max_length): ?>
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: <?php echo e($setting->max_length); ?></div>
                                                <?php endif; ?>

                                                <?php elseif($setting->type === 'textarea'): ?>
                                                <textarea id="<?php echo e($setting->key); ?>" name="<?php echo e($setting->key); ?>" class="form-control" rows="4" placeholder="Enter long text..." <?php echo e($setting->max_length ? 'maxlength='.$setting->max_length : ''); ?>><?php echo e($setting->value); ?></textarea>
                                                <?php if($setting->max_length): ?>
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: <?php echo e($setting->max_length); ?></div>
                                                <?php endif; ?>

                                                <?php elseif($setting->type === 'image'): ?>
                                                <div class="d-flex align-items-center gap-3">
                                                    <?php if($setting->value): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(Str::startsWith($setting->value, 'frontend/') ? asset($setting->value) : asset('storage/' . $setting->value)); ?>" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="flex-grow-1">
                                                        <input type="file" name="<?php echo e($setting->key); ?>" class="form-control">
                                                        <small class="text-muted">Current: <?php echo e($setting->value); ?></small>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>

                                    <!-- Banner Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-banner" role="tabpanel" aria-labelledby="v-pills-banner-tab">
                                        <div class="row g-4">
                                            <?php $__currentLoopData = $bannerSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-12">
                                                <label class="form-label fw-bold"><?php echo e(str_replace('_', ' ', ucfirst($setting->key))); ?></label>

                                                <?php if($setting->type === 'text'): ?>
                                                <input type="text" id="<?php echo e($setting->key); ?>" name="<?php echo e($setting->key); ?>" value="<?php echo e($setting->value); ?>" class="form-control" placeholder="Enter content..." <?php echo e($setting->max_length ? 'maxlength='.$setting->max_length : ''); ?>>
                                                <?php if($setting->max_length): ?>
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: <?php echo e($setting->max_length); ?></div>
                                                <?php endif; ?>

                                                <?php elseif($setting->type === 'textarea'): ?>
                                                <textarea id="<?php echo e($setting->key); ?>" name="<?php echo e($setting->key); ?>" class="form-control" rows="4" placeholder="Enter long text..." <?php echo e($setting->max_length ? 'maxlength='.$setting->max_length : ''); ?>><?php echo e($setting->value); ?></textarea>
                                                <?php if($setting->max_length): ?>
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: <?php echo e($setting->max_length); ?></div>
                                                <?php endif; ?>

                                                <?php elseif($setting->type === 'image'): ?>
                                                <div class="d-flex align-items-center gap-3">
                                                    <?php if($setting->value): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(Str::startsWith($setting->value, 'frontend/') ? asset($setting->value) : asset('storage/' . $setting->value)); ?>" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="flex-grow-1">
                                                        <input type="file" name="<?php echo e($setting->key); ?>" class="form-control">
                                                        <small class="text-muted">Current: <?php echo e($setting->value); ?></small>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>

                                    <!-- Team Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-team" role="tabpanel" aria-labelledby="v-pills-team-tab">
                                        <div class="row g-4">
                                            <?php $__currentLoopData = $teamSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-12">
                                                <label class="form-label fw-bold"><?php echo e(str_replace('_', ' ', ucfirst($setting->key))); ?></label>

                                                <?php if($setting->type === 'text'): ?>
                                                <input type="text" id="<?php echo e($setting->key); ?>" name="<?php echo e($setting->key); ?>" value="<?php echo e($setting->value); ?>" class="form-control" placeholder="Enter content..." <?php echo e($setting->max_length ? 'maxlength='.$setting->max_length : ''); ?>>
                                                <?php if($setting->max_length): ?>
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: <?php echo e($setting->max_length); ?></div>
                                                <?php endif; ?>

                                                <?php elseif($setting->type === 'textarea'): ?>
                                                <textarea id="<?php echo e($setting->key); ?>" name="<?php echo e($setting->key); ?>" class="form-control" rows="4" placeholder="Enter long text..." <?php echo e($setting->max_length ? 'maxlength='.$setting->max_length : ''); ?>><?php echo e($setting->value); ?></textarea>
                                                <?php if($setting->max_length): ?>
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: <?php echo e($setting->max_length); ?></div>
                                                <?php endif; ?>

                                                <?php elseif($setting->type === 'image'): ?>
                                                <div class="d-flex align-items-center gap-3">
                                                    <?php if($setting->value): ?>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(Str::startsWith($setting->value, 'frontend/') ? asset($setting->value) : asset('storage/' . $setting->value)); ?>" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                                                    </div>
                                                    <?php endif; ?>
                                                    <div class="flex-grow-1">
                                                        <input type="file" name="<?php echo e($setting->key); ?>" class="form-control">
                                                        <small class="text-muted">Current: <?php echo e($setting->value); ?></small>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end mt-4">
                            <button type="submit" id="saveSettingsBtn" class="btn btn-primary px-5">Save All Settings</button>
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
        // Handle hash navigation
        function activateTabFromHash() {
            let hash = window.location.hash;
            if (hash) {
                let tabBtn = $(`button[data-bs-target="${hash}"]`);
                if (tabBtn.length) {
                    tabBtn.trigger('click');
                }
            }
        }

        activateTabFromHash();
        $(window).on('hashchange', function() {
            activateTabFromHash();
        });

        $('#aboutSettingsForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let btn = $('#saveSettingsBtn');
            let formData = new FormData(this);

            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        window.showToast(response.message, 'success');
                    } else {
                        window.showToast('Something went wrong.', 'error');
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        window.showToast(xhr.responseJSON.message, 'error');
                    } else {
                        window.showToast('An error occurred. Please try again.', 'error');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html('Save All Settings');
                }
            });
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\admin\about-settings\index.blade.php ENDPATH**/ ?>