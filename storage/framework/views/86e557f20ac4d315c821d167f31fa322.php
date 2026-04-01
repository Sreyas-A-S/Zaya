<?php $__env->startSection('title', 'Find Practitioner Settings'); ?>

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
                <h3>Find Practitioner Settings</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active">Find Practitioner Settings</li>
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
                    <h3>Manage Find Practitioner Content</h3>
                    <p>Update content for the Find Practitioner page, including header text and placeholders.</p>
                </div>
                <div class="card-body">
                    <form id="findPractitionerSettingsForm" action="<?php echo e(route('admin.find-practitioner-settings.update')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <ul class="nav nav-pills flex-column h-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link active text-start mb-2" id="v-pills-hero-tab" data-bs-toggle="pill" data-bs-target="#v-pills-hero" type="button" role="tab" aria-controls="v-pills-hero" aria-selected="true">
                                        <i class="fa-solid fa-circle-info me-2"></i> Hero Section
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-search-tab" data-bs-toggle="pill" data-bs-target="#v-pills-search" type="button" role="tab" aria-controls="v-pills-search" aria-selected="false">
                                        <i class="fa-solid fa-magnifying-glass me-2"></i> Search & Filters
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-results-tab" data-bs-toggle="pill" data-bs-target="#v-pills-results" type="button" role="tab" aria-controls="v-pills-results" aria-selected="false">
                                        <i class="fa-solid fa-list me-2"></i> Results
                                    </button>
                                </ul>
                            </div>
                            <div class="col-md-9 border-start">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <?php
                                    $heroSettings = $settings->filter(fn($s) => Str::contains($s->key, ['title', 'subtitle', 'description']));
                                    $searchSettings = $settings->filter(fn($s) => Str::contains($s->key, 'placeholder'));
                                    $resultSettings = $settings->filter(fn($s) => Str::contains($s->key, ['results', 'load_more']));
                                    ?>

                                    <!-- Hero Section Tab -->
                                    <div class="tab-pane fade show active p-3" id="v-pills-hero" role="tabpanel" aria-labelledby="v-pills-hero-tab">
                                        <div class="row g-4">
                                            <?php $__currentLoopData = $heroSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-12">
                                                <label class="form-label fw-bold"><?php echo e(str_replace('_', ' ', ucfirst(str_replace('find_practitioner_', '', $setting->key)))); ?></label>

                                                <?php if($setting->type === 'text'): ?>
                                                <input type="text" name="<?php echo e($setting->key); ?>" value="<?php echo e($setting->value); ?>" class="form-control" placeholder="Enter content..." <?php echo e($setting->max_length ? 'maxlength='.$setting->max_length : ''); ?>>
                                                <?php if($setting->max_length): ?>
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: <?php echo e($setting->max_length); ?></div>
                                                <?php endif; ?>

                                                <?php elseif($setting->type === 'textarea'): ?>
                                                <textarea name="<?php echo e($setting->key); ?>" class="form-control" rows="4" placeholder="Enter content..." <?php echo e($setting->max_length ? 'maxlength='.$setting->max_length : ''); ?>><?php echo e($setting->value); ?></textarea>
                                                <?php if($setting->max_length): ?>
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: <?php echo e($setting->max_length); ?></div>
                                                <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>

                                    <!-- Search & Filters Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-search" role="tabpanel" aria-labelledby="v-pills-search-tab">
                                        <div class="row g-4">
                                            <?php $__currentLoopData = $searchSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-12">
                                                <label class="form-label fw-bold"><?php echo e(str_replace('_', ' ', ucfirst(str_replace('find_practitioner_', '', $setting->key)))); ?></label>
                                                <input type="text" name="<?php echo e($setting->key); ?>" value="<?php echo e($setting->value); ?>" class="form-control" placeholder="Enter placeholder text..." <?php echo e($setting->max_length ? 'maxlength='.$setting->max_length : ''); ?>>
                                                <?php if($setting->max_length): ?>
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: <?php echo e($setting->max_length); ?></div>
                                                <?php endif; ?>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>

                                    <!-- Results Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-results" role="tabpanel" aria-labelledby="v-pills-results-tab">
                                        <div class="row g-4">
                                            <?php $__currentLoopData = $resultSettings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-12">
                                                <label class="form-label fw-bold"><?php echo e(str_replace('_', ' ', ucfirst(str_replace('find_practitioner_', '', $setting->key)))); ?></label>
                                                <input type="text" name="<?php echo e($setting->key); ?>" value="<?php echo e($setting->value); ?>" class="form-control" placeholder="Enter text..." <?php echo e($setting->max_length ? 'maxlength='.$setting->max_length : ''); ?>>
                                                <?php if($setting->max_length): ?>
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: <?php echo e($setting->max_length); ?></div>
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
        $('#findPractitionerSettingsForm').on('submit', function(e) {
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\admin\find-practitioner-settings\index.blade.php ENDPATH**/ ?>