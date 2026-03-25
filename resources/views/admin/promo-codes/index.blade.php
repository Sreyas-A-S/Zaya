@extends('layouts.admin')

@section('title', 'Promo Code Management')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Promo Codes</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="iconly-Home icli svg-color"></i></a></li>
                    <li class="breadcrumb-item">Promo Code</li>
                    <li class="breadcrumb-item active">List</li>
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
                    <h3>Promo Code List</h3>
                    <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                        <i class="iconly-Add-User icli me-2"></i>Add New Promo Code
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="promo-codes-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Code</th>
                                    <th>Type</th>
                                    <th>Reward</th>
                                    <th>Usage Limit</th>
                                    <th>Used Count</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Data will be populated via AJAX --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Modal (Create/Edit) -->
<div class="modal fade" id="promo-code-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-white" id="modal-title"><i class="iconly-Ticket icli me-2"></i>Add New Promo Code</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="promo-code-form">
                <div class="modal-body">
                    @csrf
                    <input type="hidden" id="promo-code-id" name="id">
                    
                    <div class="row g-3">
                        <!-- Code Field -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="ri-qr-code-line me-1"></i>Promo Code</label>
                            <input type="text" class="form-control" id="promo-code-code" name="code" 
                                   placeholder="e.g. SUMMER2026" required style="text-transform: uppercase;">
                            <div class="form-text text-muted">Unique identifier used by customers.</div>
                        </div>

                        <!-- Status Field -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="ri-toggle-line me-1"></i>Status</label>
                            <select class="form-select" id="promo-code-status" name="status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <div class="form-text text-muted">Toggle visibility and usability.</div>
                        </div>

                        <!-- Type Field -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="ri-settings-3-line me-1"></i>Discount Type</label>
                            <select class="form-select" id="promo-code-type" name="type" required>
                                <option value="fixed">Fixed Amount ($)</option>
                                <option value="percentage">Percentage (%)</option>
                            </select>
                            <div class="form-text text-muted">How the reward is calculated.</div>
                        </div>

                        <!-- Reward Field -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="ri-money-dollar-circle-line me-1"></i>Reward Value</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" id="promo-code-reward" name="reward" required placeholder="0.00">
                                <span class="input-group-text" id="reward-addon">$</span>
                            </div>
                            <div class="form-text text-muted">Enter the discount amount or percentage.</div>
                        </div>

                        <!-- Usage Limit Field -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="ri-user-follow-line me-1"></i>Usage Limit</label>
                            <input type="number" class="form-control" id="promo-code-usage_limit" name="usage_limit" placeholder="Unlimited">
                            <div class="form-text text-muted">Total times this code can be used (empty for unlimited).</div>
                        </div>

                        <!-- Expiry Date Field -->
                        <div class="col-md-6">
                            <label class="form-label fw-bold"><i class="ri-calendar-event-line me-1"></i>Expiry Date</label>
                            <input type="date" class="form-control" id="promo-code-expiry_date" name="expiry_date">
                            <div class="form-text text-muted">The code will expire at the end of this day.</div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button class="btn btn-outline-dark" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary px-4" type="submit">
                        <i class="ri-save-line me-1"></i> Save Promo Code
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="delete-confirmation-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white"><i class="iconly-Delete icli me-2"></i>Confirm Delete</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="ri-error-warning-line text-danger" style="font-size: 50px;"></i>
                <h4 class="mt-3">Are you sure?</h4>
                <p class="text-muted">Do you really want to delete this promo code? This action cannot be undone.</p>
                <input type="hidden" id="delete-promo-code-id">
            </div>
            <div class="modal-footer border-top-0 justify-content-center">
                <button class="btn btn-outline-dark px-4" type="button" data-bs-dismiss="modal">No, Keep it</button>
                <button class="btn btn-danger px-4" type="button" id="confirm-delete-btn">Yes, Delete it</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Handle Reward Addon Toggle
        $('#promo-code-type').on('change', function() {
            const type = $(this).val();
            const addon = $('#reward-addon');
            if (type === 'percentage') {
                addon.text('%');
            } else {
                addon.text('$');
            }
        });

        var table = $('#promo-codes-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.promo-codes.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'code', name: 'code'},
                {data: 'type', name: 'type'},
                {data: 'reward', name: 'reward'},
                {data: 'usage_limit', name: 'usage_limit'},
                {data: 'used_count', name: 'used_count'},
                {data: 'expiry_date', name: 'expiry_date'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#promo-code-form').on('submit', function(e) {
            e.preventDefault();
            var id = $('#promo-code-id').val();
            var url = id ? "{{ url('admin/promo-codes') }}/" + id : "{{ route('admin.promo-codes.store') }}";
            var method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: 'POST', 
                data: $(this).serialize() + (id ? '&_method=PUT' : ''),
                success: function(response) {
                    $('#promo-code-modal').modal('hide');
                    table.ajax.reload();
                    if (typeof showToast === 'function') {
                        showToast(response.success);
                    } else {
                        alert(response.success);
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMsg = 'Error occurred while saving data.';
                    if (errors) {
                        errorMsg = Object.values(errors).flat().join('\n');
                    }
                    if (typeof showToast === 'function') {
                        showToast(errorMsg, 'error');
                    } else {
                        alert(errorMsg);
                    }
                }
            });
        });

        $(document).on('click', '.editPromoCode', function() {
            var id = $(this).data('id');
            $.get("{{ url('admin/promo-codes') }}/" + id, function(data) {
                $('#modal-title').text('Edit Promo Code');
                $('#promo-code-id').val(data.id);
                $('#promo-code-code').val(data.code);
                $('#promo-code-type').val(data.type);
                $('#promo-code-reward').val(data.reward);
                $('#reward-addon').text(data.type === 'percentage' ? '%' : '$');
                $('#promo-code-usage_limit').val(data.usage_limit);
                $('#promo-code-expiry_date').val(data.expiry_date ? data.expiry_date.split('T')[0] : '');
                $('#promo-code-status').val(data.status ? 1 : 0);
                $('#promo-code-modal').modal('show');
            });
        });

        $(document).on('click', '.deletePromoCode', function() {
            var id = $(this).data('id');
            $('#delete-promo-code-id').val(id);
            $('#delete-confirmation-modal').modal('show');
        });

        $('#confirm-delete-btn').on('click', function() {
            var id = $('#delete-promo-code-id').val();
            $('#delete-form-' + id).submit();
        });
    });

    function openCreateModal() {
        $('#promo-code-form')[0].reset();
        $('#promo-code-id').val('');
        $('#modal-title').text('Add New Promo Code');
        $('#promo-code-modal').modal('show');
    }

    window.updateStatus = function(id, status) {
        $.ajax({
            url: "{{ url('admin/promo-codes') }}/" + id + "/status",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                status: status
            },
            success: function(response) {
                showToast(response.success);
                $('#promo-codes-table').DataTable().ajax.reload(null, false);
            },
            error: function() {
                showToast('Error updating status', 'error');
            }
        });
    }
</script>
@endsection
