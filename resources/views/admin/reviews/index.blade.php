@extends('layouts.admin')

@section('title', 'Zaya Reviews Management')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Zaya Reviews</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Reviews</li>
                    <li class="breadcrumb-item active">Zaya Reviews</li>
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
                    <h3>All Reviews List</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="reviews-table">
                            <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>ID</th>
                                    <th>Type</th>
                                    <th>Reviewer</th>
                                    <th>User Type</th>
                                    <th>Target</th>
                                    <th>Rating</th>
                                    <th>Review</th>
                                    <th>Date & Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="review-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="iconly-Delete icli text-danger mb-3" style="font-size: 50px;"></i>
                <h5>Are you sure?</h5>
                <p>Do you want to delete this review? This action cannot be undone.</p>
                <input type="hidden" id="delete-review-id">
                <input type="hidden" id="delete-review-type">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete Now</button>
            </div>
        </div>
    </div>
</div>

<!-- Status Confirmation Modal -->
<div class="modal fade" id="status-confirmation-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Status Change</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="iconly-Info-Square icli text-warning mb-3" style="font-size: 50px;"></i>
                <h5>Are you sure?</h5>
                <p id="status-confirmation-text"></p>
                <input type="hidden" id="status-review-id">
                <input type="hidden" id="status-review-type">
                <input type="hidden" id="status-new-value">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-status-btn">Confirm Change</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // DataTable
        var table = $('#reviews-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.reviews.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'id', name: 'id' },
                { 
                    data: 'type', 
                    name: 'type',
                    render: function(data) {
                        const badgeClass = data === 'Zaya Review' ? 'bg-primary' : 'bg-secondary';
                        return `<span class="badge ${badgeClass}">${data}</span>`;
                    }
                },
                { data: 'reviewer_name', name: 'reviewer_name' },
                { 
                    data: 'reviewer_role', 
                    name: 'reviewer_role',
                    render: function(data) {
                        return `<span class="text-xs font-medium text-gray-500 uppercase tracking-wider">${data}</span>`;
                    }
                },
                { data: 'target_name', name: 'target_name' },
                { 
                    data: 'rating_display', 
                    name: 'rating',
                    orderable: true,
                    searchable: false
                },
                { data: 'review', name: 'review' },
                { 
                    data: 'created_at', 
                    name: 'created_at',
                    render: function(data) {
                        const date = new Date(data);
                        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    }
                },
                { data: 'status', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            order: [[8, 'desc']]
        });

        // Delete
        $('body').on('click', '.deleteReview', function() {
            let id = $(this).data('id');
            let type = $(this).data('type');
            $('#delete-review-id').val(id);
            $('#delete-review-type').val(type);
            $('#review-delete-modal').modal('show');
        });

        $('#confirm-delete-btn').click(function() {
            let id = $('#delete-review-id').val();
            let type = $('#delete-review-type').val();
            let btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');

            $.ajax({
                type: "DELETE",
                url: "{{ url('admin/zaya-reviews') }}/" + id + "/" + type,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#review-delete-modal').modal('hide');
                    table.draw();
                    if (typeof showToast === 'function') showToast(data.success);
                },
                error: function(data) {
                    alert('Error deleting review');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Delete Now');
                }
            });
        });

        // Toggle Status
        $('body').on('click', '.toggle-status', function() {
            var id = $(this).data('id');
            var type = $(this).data('type');
            var currentStatus = $(this).data('status');
            var newStatus = (currentStatus === 'approved') ? 'pending' : 'approved';
            var newStatusText = (currentStatus === 'approved') ? 'Pending' : 'Approved';

            $('#status-review-id').val(id);
            $('#status-review-type').val(type);
            $('#status-new-value').val(newStatus);
            $('#status-confirmation-text').text(`Are you sure you want to change the status of this review to ${newStatusText}?`);
            $('#status-confirmation-modal').modal('show');
        });

        // Handle Confirm Status Change
        $('#confirm-status-btn').on('click', function() {
            var id = $('#status-review-id').val();
            var type = $('#status-review-type').val();
            var newStatus = $('#status-new-value').val();
            var btn = $(this);

            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

            $.ajax({
                url: "{{ url('admin/zaya-reviews') }}/" + id + "/status/" + type,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                },
                success: function(response) {
                    $('#status-confirmation-modal').modal('hide');
                    table.draw(false);
                    if (typeof showToast === 'function') {
                        showToast(response.success);
                    }
                },
                error: function() {
                    alert('Error updating status');
                },
                complete: function() {
                    btn.prop('disabled', false).html('Confirm Change');
                }
            });
        });
    });
</script>
@endsection
