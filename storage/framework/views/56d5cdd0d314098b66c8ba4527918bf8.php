<?php $__env->startSection('title', 'Contact Messages'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Contact Messages</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Contact Us</li>
                    <li class="breadcrumb-item active">Messages</li>
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
                    <h3>Messages List</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="contact-messages-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>User Type</th>
                                    <th>Message</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($message->created_at->format('d M Y H:i')); ?></td>
                                    <td><?php echo e($message->first_name); ?> <?php echo e($message->last_name); ?></td>
                                    <td><?php echo e($message->email); ?></td>
                                    <td><?php echo e($message->phone); ?></td>
                                    <td>
                                        <?php if($message->user_type): ?>
                                            <?php $__currentLoopData = $message->user_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="badge badge-primary"><?php echo e(ucfirst($type)); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(Str::limit($message->message, 50)); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info viewMessage" data-id="<?php echo e($message->id); ?>">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger deleteMessage" data-id="<?php echo e($message->id); ?>">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
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

<!-- View Modal -->
<div class="modal fade" id="message-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message Details</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="view-modal-content">
                <!-- Content will be loaded via JS -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#contact-messages-table').DataTable({
            order: [[0, 'desc']]
        });

        const messages = <?php echo json_encode($messages, 15, 512) ?>;

        $('body').on('click', '.viewMessage', function() {
            const id = $(this).data('id');
            const message = messages.find(m => m.id == id);
            
            if (message) {
                let userTypes = '';
                if (message.user_type) {
                    message.user_type.forEach(type => {
                        userTypes += `<span class="badge badge-primary me-1">${type.charAt(0).toUpperCase() + type.slice(1)}</span>`;
                    });
                }

                const html = `
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-1">Full Name</label>
                            <p class="mb-0 text-dark">${message.first_name} ${message.last_name}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-1">Date</label>
                            <p class="mb-0 text-dark">${new Date(message.created_at).toLocaleString()}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-1">Email</label>
                            <p class="mb-0 text-dark">${message.email}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-1">Phone</label>
                            <p class="mb-0 text-dark">${message.phone || 'N/A'}</p>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-1">User Type</label>
                            <div>${userTypes || 'N/A'}</div>
                        </div>
                        <div class="col-md-12 mt-4">
                            <div class="p-4 bg-light rounded shadow-sm">
                                <label class="fw-bold text-muted small text-uppercase d-block mb-2">Message</label>
                                <p class="mb-0 fs-6 text-dark" style="white-space: pre-wrap; line-height: 1.6;">${message.message}</p>
                            </div>
                        </div>
                    </div>
                `;
                $('#view-modal-content').html(html);
                $('#message-view-modal').modal('show');
            }
        });

        $('body').on('click', '.deleteMessage', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this message?')) {
                $.ajax({
                    url: "<?php echo e(url('admin/contact-messages')); ?>/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        alert('Error deleting message');
                    }
                });
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\admin\contact-us\messages.blade.php ENDPATH**/ ?>