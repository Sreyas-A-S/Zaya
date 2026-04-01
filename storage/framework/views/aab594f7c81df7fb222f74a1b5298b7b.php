<?php $__env->startSection('title', 'Specialist Balances'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Specialist Balances</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Finance</li>
                    <li class="breadcrumb-item active">Practitioner Balances</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Balances Table -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Earnings Summary</h5>
                    <span>Accumulated earnings for each specialist from sessions and referrals.</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="practitioner-balances-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Total Earned (INR)</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#practitioner-balances-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "<?php echo e(route('admin.financial.practitioners')); ?>",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'role', name: 'role' },
                { data: 'email', name: 'email' },
                { data: 'total_balance', name: 'total_balance', searchable: false }
            ]
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\admin\financial\practitioners.blade.php ENDPATH**/ ?>