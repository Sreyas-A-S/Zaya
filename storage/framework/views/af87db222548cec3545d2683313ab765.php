<?php $__env->startSection('title', 'Open Register Link'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Open Register Link</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.forms.index')); ?>">Forms</a></li>
                    <li class="breadcrumb-item active">#<?php echo e($link->id); ?></li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border d-flex justify-content-between align-items-center">
                    <h3>Link #<?php echo e($link->id); ?></h3>
                    <a class="btn btn-secondary" href="<?php echo e(route('admin.forms.index')); ?>">Back</a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-2"><strong>User Type:</strong> <?php echo e(ucfirst(str_replace('-', ' ', $link->role))); ?></div>
                            <div class="mb-2"><strong>Created By:</strong> <?php echo e($link->creator?->name ?? $link->creator?->email ?? '—'); ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2"><strong>Expires At:</strong> <?php echo e(optional($link->expires_at)->format('Y-m-d H:i:s') ?? '—'); ?></div>
                            <div class="mb-2"><strong>Created:</strong> <?php echo e(optional($link->created_at)->format('Y-m-d H:i:s')); ?></div>
                        </div>
                    </div>

                    <hr>

                    <h5 class="mb-2">URL</h5>
                    <div class="input-group">
                        <input type="text" class="form-control" value="<?php echo e($link->url); ?>" readonly>
                        <a class="btn btn-primary" href="<?php echo e($link->url); ?>" target="_blank" rel="noopener">Open</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views/admin/forms/show.blade.php ENDPATH**/ ?>