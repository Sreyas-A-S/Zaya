<?php $__env->startSection('title', 'Notifications'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Notifications</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item active">Notifications</li>
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
                    <h3>Your Notifications</h3>
                    <div>
                        <?php if(auth()->user()->unreadNotifications->count() > 0): ?>
                        <form action="<?php echo e(route('admin.notifications.mark-all-as-read')); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-secondary me-2">
                                <i class="fa fa-check-double me-2"></i> Mark all as read
                            </button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="notifications-table">
                            <thead>
                                <tr>
                                    <th>SL No</th>
                                    <th>Date</th>
                                    <th>Message</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="<?php echo e($notification->unread() ? 'table-light fw-bold' : ''); ?>">
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($notification->created_at->format('d M Y H:i')); ?></td>
                                    <td>
                                        <?php
                                            $data = $notification->data;
                                            $message = $data['message'] ?? ($data['title'] ?? 'New Notification');
                                        ?>
                                        <?php echo e($message); ?>

                                    </td>
                                    <td>
                                        <?php if($notification->unread()): ?>
                                            <span class="badge badge-warning">Unread</span>
                                        <?php else: ?>
                                            <span class="badge badge-light text-dark">Read</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <?php if($notification->unread()): ?>
                                            <button class="btn btn-sm btn-info markAsRead" data-id="<?php echo e($notification->id); ?>">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <?php endif; ?>
                                            <button class="btn btn-sm btn-danger deleteNotification" data-id="<?php echo e($notification->id); ?>">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        <?php echo e($notifications->links()); ?>

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
        $('#notifications-table').DataTable({
            order: [[1, 'desc']],
            paging: false,
            info: false
        });

        $('body').on('click', '.markAsRead', function() {
            const btn = $(this);
            const id = btn.data('id');
            
            $.ajax({
                url: "<?php echo e(url('admin/notifications')); ?>/" + id + "/mark-as-read",
                type: 'POST',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>'
                },
                success: function(response) {
                    location.reload();
                },
                error: function() {
                    showToast('Error marking as read', 'error');
                }
            });
        });

        $('body').on('click', '.deleteNotification', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this notification?')) {
                $.ajax({
                    url: "<?php echo e(url('admin/notifications')); ?>/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        showToast('Error deleting notification', 'error');
                    }
                });
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\admin\notifications\index.blade.php ENDPATH**/ ?>