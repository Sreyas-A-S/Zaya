<?php $__env->startSection('title', 'Newsletter Management'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Newsletter Subscriptions</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Marketing</li>
                    <li class="breadcrumb-item active">Newsletters</li>
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
                    <h3>Active Subscriptions</h3>
                    <a href="<?php echo e(route('admin.newsletters.export')); ?>" class="btn btn-primary">
                        <i class="fa fa-download me-2"></i> Export to CSV
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="newsletter-table">
                            <thead>
                                <tr>
                                    <th>SL No</th>
                                    <th>Date Subscribed</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $subscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($sub->created_at->format('d M Y H:i')); ?></td>
                                    <td><?php echo e($sub->email); ?></td>
                                    <td>
                                        <button class="badge <?php echo e($sub->is_active ? 'badge-success' : 'badge-danger'); ?> border-0 toggle-status" data-id="<?php echo e($sub->id); ?>">
                                            <?php echo e($sub->is_active ? 'Active' : 'Unsubscribed'); ?>

                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-danger deleteSubscription" data-id="<?php echo e($sub->id); ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#newsletter-table').DataTable({
            order: [[1, 'desc']]
        });

        $('body').on('click', '.toggle-status', function() {
            const btn = $(this);
            const id = btn.data('id');
            
            $.ajax({
                url: "<?php echo e(url('admin/newsletters')); ?>/" + id + "/status",
                type: 'POST',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function(response) {
                    if (response.is_active) {
                        btn.removeClass('badge-danger').addClass('badge-success').text('Active');
                    } else {
                        btn.removeClass('badge-success').addClass('badge-danger').text('Unsubscribed');
                    }
                    showToast(response.success);
                },
                error: function() {
                    showToast('Error updating status', 'error');
                }
            });
        });

        $('body').on('click', '.deleteSubscription', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this subscription?')) {
                $.ajax({
                    url: "<?php echo e(url('admin/newsletters')); ?>/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        showToast('Error deleting subscription', 'error');
                    }
                });
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\admin\newsletters\index.blade.php ENDPATH**/ ?>