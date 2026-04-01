<?php $__env->startSection('title', 'Homepage Settings'); ?>

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
            <div class="col-sm-6">
                <h3>Homepage Settings</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Master Settings</li>
                    <li class="breadcrumb-item active">Homepage Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border d-flex justify-content-between align-items-center">
                    <div>
                        <h3>Manage Homepage Content</h3>
                        <p>Update titles, subtitles, and images for different sections of the landing page.</p>
                    </div>
                    <div class="current-lang-badge">
                        <span class="badge badge-light-primary text-uppercase" style="font-size: 14px; padding: 8px 15px; border-radius: 5px; border: 1px solid var(--theme-default);">
                            <i class="fa-solid fa-language me-2"></i> Editing: <strong id="current-editing-lang"><?php echo e(session('locale', env('APP_LOCALE', 'en'))); ?></strong>
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <form id="homepageSettingsForm" action="<?php echo e(route('admin.homepage-settings.update')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <ul class="nav nav-pills flex-column h-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button class="nav-link <?php echo e($loop->first ? 'active' : ''); ?> text-start mb-2" id="v-pills-<?php echo e($section); ?>-tab" data-bs-toggle="pill" data-bs-target="#v-pills-<?php echo e($section); ?>" type="button" role="tab" aria-controls="v-pills-<?php echo e($section); ?>" aria-selected="<?php echo e($loop->first ? 'true' : 'false'); ?>">
                                        <i class="fa-solid fa-layer-group me-2"></i> <?php echo e(ucwords(str_replace('_', ' ', $section))); ?>

                                    </button>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </div>
                            <div class="col-md-9 border-start">
                                <div class="tab-content" id="v-pills-tabContent">
                                    <?php $__currentLoopData = $settings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="tab-pane fade <?php echo e($loop->first ? 'show active' : ''); ?> p-3" id="v-pills-<?php echo e($section); ?>" role="tabpanel" aria-labelledby="v-pills-<?php echo e($section); ?>-tab">
                                        <div class="row g-4">
                                            <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-12">
                                                <label class="form-label fw-bold">
                                                    <?php echo e(str_replace('_', ' ', ucfirst($setting->key))); ?>

                                                    <?php if(isset($setting->is_global) && $setting->is_global): ?>
                                                        <span class="badge badge-light-success ms-2" style="font-size: 10px;">Global</span>
                                                    <?php endif; ?>
                                                </label>

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
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
        // Function to activate tab based on hash
        function activateTabFromHash() {
            let hash = window.location.hash;
            if (hash) {
                // Find button that targets this hash
                let tabBtn = $(`button[data-bs-target="${hash}"]`);
                if (tabBtn.length) {
                    tabBtn.trigger('click');
                }
            }
        }

        // Run on load
        activateTabFromHash();

        // Run on hash change
        $(window).on('hashchange', function() {
            activateTabFromHash();
        });

        $('#homepageSettingsForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let btn = $('#saveSettingsBtn');
            let formData = new FormData(this);

            // Disable button and show loading
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        if (typeof showToast === 'function') {
                            showToast(response.message);
                        } else {
                            alert(response.message);
                        }

                        // Reload after a short delay to allow toast to be seen
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'An error occurred while saving.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert(errorMsg);
                },
                complete: function() {
                    btn.prop('disabled', false).text('Save All Settings');
                }
            });
        });

        <?php if(session('success')): ?>
        if (typeof showToast === 'function') {
            showToast("<?php echo e(session('success')); ?>");
        } else {
            alert("<?php echo e(session('success')); ?>");
        }
        <?php endif; ?>
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\admin\homepage-settings\index.blade.php ENDPATH**/ ?>