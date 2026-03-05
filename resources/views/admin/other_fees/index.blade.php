@extends('layouts.admin')

@section('title', 'Other Fees Management')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Other Fees</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="iconly-Home icli svg-color"></i></a></li>
                    <li class="breadcrumb-item">Finance</li>
                    <li class="breadcrumb-item active">Other Fees</li>
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
                    <h3>Other Fee List</h3>
                    <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                        <i class="iconly-Add-User icli me-2"></i>Add New Fee
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="other-fees-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Fee Name</th>
                                    <th>Rate</th>
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
<div class="modal fade" id="other-fee-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Add New Fee</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="other-fee-form">
                    @csrf
                    <input type="hidden" id="other-fee-id" name="id">
                    <div class="mb-3">
                        <label class="form-label">Fee Name</label>
                        <input type="text" class="form-control" id="other-fee-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rate</label>
                        <input type="number" step="0.01" class="form-control" id="other-fee-rate" name="rate" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" id="other-fee-status" name="status" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var table = $('#other-fees-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.other-fees.index') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'name', name: 'name'},
                {data: 'rate', name: 'rate'},
                {data: 'status', name: 'status', orderable: false, searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#other-fee-form').on('submit', function(e) {
            e.preventDefault();
            var id = $('#other-fee-id').val();
            var url = id ? "{{ url('admin/other-fees') }}/" + id : "{{ route('admin.other-fees.store') }}";
            var method = id ? 'PUT' : 'POST';

            $.ajax({
                url: url,
                type: 'POST', // Use POST and override with _method for PUT
                data: $(this).serialize() + (id ? '&_method=PUT' : ''),
                success: function(response) {
                    $('#other-fee-modal').modal('hide');
                    table.ajax.reload();
                    showToast(response.success);
                },
                error: function(xhr) {
                    showToast('Error occurred while saving data.', 'error');
                }
            });
        });

        $(document).on('click', '.editOtherFee', function() {
            var id = $(this).data('id');
            $.get("{{ url('admin/other-fees') }}/" + id, function(data) {
                $('#modal-title').text('Edit Fee');
                $('#other-fee-id').val(data.id);
                $('#other-fee-name').val(data.name);
                $('#other-fee-rate').val(data.rate);
                $('#other-fee-status').val(data.status);
                $('#other-fee-modal').modal('show');
            });
        });
    });

    function openCreateModal() {
        $('#other-fee-form')[0].reset();
        $('#other-fee-id').val('');
        $('#modal-title').text('Add New Fee');
        $('#other-fee-modal').modal('show');
    }

    function updateStatus(id, status) {
        $.ajax({
            url: "{{ url('admin/other-fees') }}/" + id + "/status",
            type: 'POST',
            data: {
                _token: "{{ csrf_token() }}",
                status: status
            },
            success: function(response) {
                showToast(response.success);
            },
            error: function() {
                showToast('Error updating status', 'error');
            }
        });
    }
</script>
@endsection
