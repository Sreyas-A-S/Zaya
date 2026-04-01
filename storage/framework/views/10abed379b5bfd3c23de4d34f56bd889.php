<?php $__env->startSection('title', 'Client Panel Settings'); ?>

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
                <h3>Client Panel Settings</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active">Client Panel Settings</li>
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
                    <h3>Manage Client Panel Content</h3>
                    <div class="current-lang-badge">
                        <span class="badge badge-light-primary text-uppercase" style="font-size: 14px; padding: 8px 15px; border-radius: 5px; border: 1px solid var(--theme-default);">
                            <i class="fa-solid fa-language me-2"></i> Editing: <strong id="current-editing-lang"><?php echo e(session('locale', env('APP_LOCALE', 'en'))); ?></strong>
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    <form id="clientPanelSettingsForm" action="<?php echo e(route('admin.client-pannel-settings.update')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <ul class="nav nav-pills flex-column h-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link active text-start mb-2" id="general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button" role="tab" aria-controls="v-pills-general" aria-selected="true">
                                        <i class="fa-solid fa-circle-info me-2"></i> General
                                    </button>
                                    <button class="nav-link text-start mb-2" id="identity-tab" data-bs-toggle="pill" data-bs-target="#v-pills-identity" type="button" role="tab" aria-controls="v-pills-identity" aria-selected="false">
                                        <i class="fa-solid fa-user-hub me-2"></i> Identity Hub
                                    </button>
                                    <button class="nav-link text-start mb-2" id="consultations-tab" data-bs-toggle="pill" data-bs-target="#v-pills-consultations" type="button" role="tab" aria-controls="v-pills-consultations" aria-selected="false">
                                        <i class="fa-solid fa-calendar-check me-2"></i> Consultations
                                    </button>
                                    <button class="nav-link text-start mb-2" id="documents-tab" data-bs-toggle="pill" data-bs-target="#v-pills-documents" type="button" role="tab" aria-controls="v-pills-documents" aria-selected="false">
                                        <i class="fa-solid fa-file-medical me-2"></i> Document Portal
                                    </button>
                                    <button class="nav-link text-start mb-2" id="transactions-tab" data-bs-toggle="pill" data-bs-target="#v-pills-transactions" type="button" role="tab" aria-controls="v-pills-transactions" aria-selected="false">
                                        <i class="fa-solid fa-wallet me-2"></i> Transactions
                                    </button>
                                    <button class="nav-link text-start mb-2" id="reviews-tab" data-bs-toggle="pill" data-bs-target="#v-pills-reviews" type="button" role="tab" aria-controls="v-pills-reviews" aria-selected="false">
                                        <i class="fa-solid fa-star me-2"></i> Reviews
                                    </button>
                                    <button class="nav-link text-start mb-2" id="gdpr-tab" data-bs-toggle="pill" data-bs-target="#v-pills-gdpr" type="button" role="tab" aria-controls="v-pills-gdpr" aria-selected="false">
                                        <i class="fa-solid fa-shield-halved me-2"></i> Privacy/GDPR
                                    </button>
                                    <button class="nav-link text-start mb-2" id="sidebar-tab" data-bs-toggle="pill" data-bs-target="#v-pills-sidebar" type="button" role="tab" aria-controls="v-pills-sidebar" aria-selected="false">
                                        <i class="fa-solid fa-list-ul me-2"></i> Sidebar
                                    </button>
                                </ul>
                            </div>
                            <div class="col-md-9 border-start">
                                <div class="tab-content" id="v-pills-tabContent">
                                    
                                    <?php
                                        $tabKeys = [
                                            'client_panel_general' => 'general',
                                            'client_panel_identity' => 'identity',
                                            'client_panel_consultations' => 'consultations',
                                            'client_panel_documents' => 'documents',
                                            'client_panel_transactions' => 'transactions',
                                            'client_panel_reviews' => 'reviews',
                                            'client_panel_gdpr' => 'gdpr',
                                            'client_panel_sidebar' => 'sidebar'
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

        $('#clientPanelSettingsForm').on('submit', function(e) {
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

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\admin\client-pannel-settings\index.blade.php ENDPATH**/ ?>