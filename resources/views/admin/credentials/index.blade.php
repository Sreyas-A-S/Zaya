@extends('layouts.admin')

@section('title', 'User Credentials')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>User Credentials</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="iconly-Home icli svg-color"></i></a></li>
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item active">Credentials</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h3>Users Credentials List</h3>
                        </div>
                        <div class="col-md-6 text-end">
                            <div class="d-inline-block text-start" style="width: 200px;">
                                <label class="form-label mb-1">Filter by Type:</label>
                                <select id="role-filter" class="form-select">
                                    <option value="">All Types</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="credentials-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Full Name</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    <th>Created At</th>
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

<!-- Password Reset Modal -->
<div class="modal fade" id="password-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Password for <span id="user-name-display"></span></h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="password-form">
                    @csrf
                    <input type="hidden" id="user-id" name="id">
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Update Password</button>
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
        var table = $('#credentials-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.credentials.index') }}",
                data: function (d) {
                    d.role = $('#role-filter').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'full_name', name: 'full_name'},
                {data: 'email', name: 'email'},
                {data: 'role', name: 'role'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });

        $('#role-filter').change(function(){
            table.draw();
        });

        $(document).on('click', '.changePassword', function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#user-id').val(id);
            $('#user-name-display').text(name);
            $('#password-form')[0].reset();
            $('#password-modal').modal('show');
        });

        $(document).on('click', '.generateLink', function() {
            var id = $(this).data('id');
            var btn = $(this);
            btn.prop('disabled', true).text('Generating...');

            $.ajax({
                url: "{{ url('admin/credentials') }}/" + id + "/generate-link",
                type: 'POST',
                data: { _token: "{{ csrf_token() }}" },
                success: function(response) {
                    if (response.success) {
                        copyToClipboard(response.link);
                        showToast(response.success + " Link copied to clipboard.");
                    }
                },
                error: function() {
                    showToast('Error generating link.', 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Login Link');
                }
            });
        });

        function copyToClipboard(text) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(text).select();
            document.execCommand("copy");
            $temp.remove();
        }

        $('#password-form').on('submit', function(e) {
            e.preventDefault();
            var id = $('#user-id').val();
            var url = "{{ url('admin/credentials') }}/" + id + "/password";

            $.ajax({
                url: url,
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#password-modal').modal('hide');
                    showToast(response.success);
                },
                error: function(xhr) {
                    var errorMsg = 'Error updating password.';
                    if(xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMsg = Object.values(xhr.responseJSON.errors)[0][0];
                    }
                    showToast(errorMsg, 'error');
                }
            });
        });
    });
</script>
@endsection
