<?php $__env->startSection('title', 'Invoice Settings'); ?>

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
                <h3>Invoice Settings</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active">Invoice Settings</li>
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
                    <h3>Manage Invoice Content</h3>
                    <div class="d-flex align-items-center gap-3">
                        <a href="<?php echo e(route('admin.invoice.preview')); ?>" target="_blank" class="btn btn-secondary">
                            <i class="fa-solid fa-eye me-2"></i>Preview Invoice
                        </a>
                        <div class="current-lang-badge">
                            <span class="badge badge-light-primary text-uppercase" style="font-size: 14px; padding: 8px 15px; border-radius: 5px; border: 1px solid var(--theme-default);">
                                <i class="fa-solid fa-language me-2"></i> Editing: <strong id="current-editing-lang"><?php echo e(session('locale', env('APP_LOCALE', 'en'))); ?></strong>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <form id="invoiceSettingsForm" action="<?php echo e(route('admin.invoice-settings.update')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <ul class="nav nav-pills flex-column h-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link active text-start mb-2" id="general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button" role="tab" aria-controls="v-pills-general" aria-selected="true">
                                        <i class="fa-solid fa-file-invoice me-2"></i> General Invoice
                                    </button>
                                </ul>
                            </div>
                            <div class="col-md-9 border-start">
                                <div class="tab-content" id="v-pills-tabContent">
                                    
                                    <?php
                                        $tabKeys = [
                                            'invoice_settings' => 'general',
                                        ];
                                    ?>

                                    <?php $__currentLoopData = $tabKeys; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section => $id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="tab-pane fade <?php echo e($loop->first ? 'show active' : ''); ?> p-3" id="v-pills-<?php echo e($id); ?>" role="tabpanel">
                                        <div class="row g-4">
                                            <?php if(isset($settings[$section])): ?>
                                                <?php $__currentLoopData = $settings[$section]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $setting): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php echo $__env->make('admin.services-settings.partials.field', ['setting' => $setting], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endif; ?>
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

        $('#invoiceSettingsForm').on('submit', function(e) {
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
                        if (typeof showToast === 'function') {
                            showToast(response.message, 'success');
                        } else {
                            alert(response.message);
                        }

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
                    if (typeof showToast === 'function') {
                        showToast(errorMsg, 'error');
                    } else {
                        alert(errorMsg);
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).text('Save All Settings');
                }
            });
        });

        $('.image-ajax-input').on('change', function() {
            const input = this;
            const key = $(this).data('key');
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('.preview-' + key).attr('src', e.target.result).parent().removeClass('d-none');      
                }
                reader.readAsDataURL(file);
            }
        });

        <?php if(session('success')): ?>
        if (typeof showToast === 'function') {
            showToast("<?php echo e(session('success')); ?>", 'success');
        } else {
            alert("<?php echo e(session('success')); ?>");
        }
        <?php endif; ?>
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\admin\invoice-settings\index.blade.php ENDPATH**/ ?>