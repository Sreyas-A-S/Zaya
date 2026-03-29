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
                                    <th>Context</th>
                                    <th>Type</th>
                                    <th>Reward</th>
                                    <th>Limit/Used</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
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
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Promo Code</label>
                            <input type="text" class="form-control" id="promo-code-code" name="code" 
                                   placeholder="e.g. SUMMER2026" required style="text-transform: uppercase;">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Usage Context</label>
                            <select class="form-select" id="promo-code-usage_type" name="usage_type" required>
                                <option value="booking">Booking Only</option>
                                <option value="registration">Registration Only</option>
                                <option value="both">Both Registration & Booking</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Discount Type</label>
                            <select class="form-select" id="promo-code-type" name="type" required>
                                <option value="fixed">Fixed Amount</option>
                                <option value="percentage">Percentage (%)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Reward Value</label>
                            <div class="input-group">
                                <input type="number" step="0.01" class="form-control" id="promo-code-reward" name="reward" required placeholder="0.00">
                                <span class="input-group-text" id="reward-addon">₹</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold">Description</label>
                            <textarea class="form-control" id="promo-code-description" name="description" rows="2" placeholder="Describe the purpose of this code..."></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-bold mb-2 d-block">Additional Benefits</label>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="form-check checkbox-primary">
                                    <input class="form-check-input benefit-checkbox" type="checkbox" name="benefits[]" value="Free First Session" id="benefit-free">
                                    <label class="form-check-label" for="benefit-free">Free First Session</label>
                                </div>
                                <div class="form-check checkbox-secondary">
                                    <input class="form-check-input benefit-checkbox" type="checkbox" name="benefits[]" value="Referral Bonus" id="benefit-referral">
                                    <label class="form-check-label" for="benefit-referral">Referral Bonus</label>
                                </div>
                                <div class="form-check checkbox-success">
                                    <input class="form-check-input benefit-checkbox" type="checkbox" name="benefits[]" value="Priority Support" id="benefit-support">
                                    <label class="form-check-label" for="benefit-support">Priority Support</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Usage Limit</label>
                            <input type="number" class="form-control" id="promo-code-usage_limit" name="usage_limit" placeholder="Unlimited">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Expiry Date</label>
                            <input type="date" class="form-control" id="promo-code-expiry_date" name="expiry_date">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-bold">Status</label>
                            <select class="form-select" id="promo-code-status" name="status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
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
                <p class="text-muted">Do you really want to delete this promo code?</p>
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
        $('#promo-code-type').on('change', function() {
            $('#reward-addon').text($(this).val() === 'percentage' ? '%' : '₹');
        });

        var table = $('#promo-codes-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.promo-codes.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'code', name: 'code'},
                {data: 'usage_type', name: 'usage_type'},
                {data: 'type', name: 'type'},
                {data: 'reward', name: 'reward'},
                {
                    data: null, 
                    render: function(data) {
                        return (data.usage_limit || '∞') + ' / ' + data.used_count;
                    }
                },
                {data: 'expiry_date', name: 'expiry_date'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#promo-code-form').on('submit', function(e) {
            e.preventDefault();
            var id = $('#promo-code-id').val();
            var url = id ? "{{ url('admin/promo-codes') }}/" + id : "{{ route('admin.promo-codes.store') }}";
            
            $.ajax({
                url: url,
                type: 'POST', 
                data: $(this).serialize() + (id ? '&_method=PUT' : ''),
                success: function(response) {
                    $('#promo-code-modal').modal('hide');
                    table.ajax.reload();
                    showToast(response.success);
                },
                error: function(xhr) {
                    showToast(Object.values(xhr.responseJSON.errors || {}).flat().join('\n') || 'Error saving data', 'error');
                }
            });
        });

        $(document).on('click', '.editPromoCode', function() {
            var id = $(this).data('id');
            $.get("{{ url('admin/promo-codes') }}/" + id, function(data) {
                $('#modal-title').text('Edit Promo Code');
                $('#promo-code-id').val(data.id);
                $('#promo-code-code').val(data.code);
                $('#promo-code-usage_type').val(data.usage_type);
                $('#promo-code-type').val(data.type);
                $('#promo-code-reward').val(data.reward);
                $('#promo-code-description').val(data.description);
                $('#reward-addon').text(data.type === 'percentage' ? '%' : '₹');
                $('#promo-code-usage_limit').val(data.usage_limit);
                $('#promo-code-expiry_date').val(data.expiry_date ? data.expiry_date.split('T')[0] : '');
                $('#promo-code-status').val(data.status ? 1 : 0);
                
                // Reset and set benefits
                $('.benefit-checkbox').prop('checked', false);
                if (data.benefits) {
                    data.benefits.forEach(b => {
                        $(`.benefit-checkbox[value="${b}"]`).prop('checked', true);
                    });
                }

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
            $.ajax({
                url: "{{ url('admin/promo-codes') }}/" + id,
                type: 'DELETE',
                data: { _token: "{{ csrf_token() }}" },
                success: function() {
                    $('#delete-confirmation-modal').modal('hide');
                    table.ajax.reload();
                    showToast('Promo code deleted successfully');
                }
            });
        });
    });

    function openCreateModal() {
        $('#promo-code-form')[0].reset();
        $('#promo-code-id').val('');
        $('.benefit-checkbox').prop('checked', false);
        $('#modal-title').text('Add New Promo Code');
        $('#promo-code-modal').modal('show');
    }

    window.updateStatus = function(id, status) {
        $.ajax({
            url: "{{ url('admin/promo-codes') }}/" + id + "/status",
            type: 'POST',
            data: { _token: "{{ csrf_token() }}", status: status },
            success: function(response) {
                showToast(response.success);
                $('#promo-codes-table').DataTable().ajax.reload(null, false);
            }
        });
    }
</script>
@endsection
