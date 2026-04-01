<?php $__env->startSection('title', 'Email Logs'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Email Logs</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Logs</li>
                    <li class="breadcrumb-item active">Email Logs</li>
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
                    <h3>System Email History</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="email-logs-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Recipient</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Duration</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($log->created_at->format('d M Y H:i')); ?></td>
                                    <td><?php echo e($log->to); ?></td>
                                    <td><?php echo e($log->subject); ?></td>
                                    <td>
                                        <?php if($log->status === 'success' || $log->status === 'sent'): ?>
                                            <span class="badge badge-success">Success</span>
                                        <?php else: ?>
                                            <div class="d-flex flex-column">
                                                <span class="badge badge-danger">Failed</span>
                                                <?php if($log->error_message): ?>
                                                    <small class="text-danger mt-1 text-truncate" style="max-width: 150px;" title="<?php echo e($log->error_message); ?>">
                                                        <?php echo e($log->error_message); ?>

                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($log->duration ? $log->duration . 's' : '-'); ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-info viewLog" data-id="<?php echo e($log->id); ?>">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger deleteLog" data-id="<?php echo e($log->id); ?>">
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
<div class="modal fade" id="log-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Email Log Details</h5>
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
        $('#email-logs-table').DataTable({
            order: [[0, 'desc']]
        });

        const logs = <?php echo json_encode($logs, 15, 512) ?>;

        $('body').on('click', '.viewLog', function() {
            const id = $(this).data('id');
            const log = logs.find(l => l.id == id);
            
            if (log) {
                const statusBadge = log.status === 'success' 
                    ? '<span class="badge badge-success text-white">Success</span>' 
                    : '<span class="badge badge-danger text-white">Failed</span>';

                let errorContent = '';
                if (log.error_message) {
                    errorContent = `
                        <div class="col-md-12 mt-3">
                            <div class="p-3 rounded" style="background-color: #fff5f5; border: 1px solid #feb2b2;">
                                <label class="fw-bold text-danger small text-uppercase d-block mb-2">
                                    <i class="fa fa-bug me-1"></i> Technical Cause
                                </label>
                                <div class="bg-white p-2 border rounded font-monospace small text-dark shadow-sm" style="word-break: break-all; white-space: pre-wrap;">
                                    ${log.error_message}
                                </div>
                            </div>
                        </div>
                    `;
                }

                const html = `
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-1">Recipient</label>
                            <p class="mb-0 text-dark">${log.to}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-1">Date</label>
                            <p class="mb-0 text-dark">${new Date(log.created_at).toLocaleString()}</p>
                        </div>
                        <div class="col-md-8">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-1">Subject</label>
                            <p class="mb-0 text-dark">${log.subject}</p>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-1">Status</label>
                            ${statusBadge}
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold text-muted small text-uppercase d-block mb-1">Duration</label>
                            <p class="mb-0 text-dark">${log.duration ? log.duration + ' seconds' : 'N/A'}</p>
                        </div>
                        ${errorContent}
                    </div>
                `;
                $('#view-modal-content').html(html);
                $('#log-view-modal').modal('show');
            }
        });

        $('body').on('click', '.deleteLog', function() {
            const id = $(this).data('id');
            if (confirm('Are you sure you want to delete this log?')) {
                $.ajax({
                    url: "<?php echo e(url('admin/email-logs')); ?>/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '<?php echo e(csrf_token()); ?>'
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        alert('Error deleting log');
                    }
                });
            }
        });
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\admin\email-logs\index.blade.php ENDPATH**/ ?>