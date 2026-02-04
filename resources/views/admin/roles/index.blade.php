@extends('layouts.admin')

@section('title', 'Roles & Permissions')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Roles Management</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Roles</li>
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
                    <h3>Roles List</h3>
                    <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                        <i class="fa-solid fa-plus me-2"></i>Create New Role
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="roles-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Role Name</th>
                                    <th>Action</th>
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

<!-- Role Form Modal (Create/Edit) -->
<div class="modal fade" id="role-form-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="form-modal-title">Create New Role</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="role-form" method="POST" class="theme-form">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" name="role_id" id="role_id">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label">Role Name</label>
                        <input type="text" class="form-control" name="name" id="role_name" required placeholder="e.g. Manager">
                    </div>
                </div>
                <div class="modal-footer border-top pt-3">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submit-btn"><i class="fa-solid fa-check-circle me-2"></i> Save Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="role-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="fa-solid fa-trash-can text-danger mb-3" style="font-size: 50px;"></i>
                <h5>Are you sure?</h5>
                <p>Deleting this role will remove all associated permissions and may affect user access.</p>
                <input type="hidden" id="delete-role-id">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete Now</button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto" id="toast-title">Notification</strong>
            <small>Just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-message"></div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    let table;
    let toastInstance;

    function showToast(message, type = 'success') {
        const toastEl = document.getElementById('liveToast');
        const titleEl = document.getElementById('toast-title');
        const messageEl = document.getElementById('toast-message');
        if (!toastInstance) toastInstance = new bootstrap.Toast(toastEl);
        toastEl.classList.remove('bg-success', 'bg-danger', 'text-white');
        if (type === 'success') {
            toastEl.classList.add('bg-success', 'text-white');
            titleEl.innerText = 'Success';
        } else {
            toastEl.classList.add('bg-danger', 'text-white');
            titleEl.innerText = 'Error';
        }
        messageEl.innerText = message;
        toastInstance.show();
    }

    $(document).ready(function() {
        table = $('#roles-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.roles.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        if ("{{ session('success') }}") {
            showToast("{{ session('success') }}");
        }
    });

    function openCreateModal() {
        $('#role-form')[0].reset();
        $('#role_id').val('');
        $('#form-method').val('POST');
        $('#form-modal-title').text('Create New Role');
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        $('#role-form-modal').modal('show');
    }

    $('body').on('click', '.editRole', function() {
        const id = $(this).data('id');
        $.get("{{ url('admin/roles') }}/" + id + "/edit", function(data) {
            $('#role_id').val(data.id);
            $('#role_name').val(data.name);
            $('#form-method').val('PUT');
            $('#form-modal-title').text('Edit Role');
            $('#role-form-modal').modal('show');
        });
    });

    $('#role-form').on('submit', function(e) {
        e.preventDefault();
        const id = $('#role_id').val();
        const url = id ? "{{ url('admin/roles') }}/" + id : "{{ route('admin.roles.store') }}";
        const formData = $(this).serialize();
        const btn = $('#submit-btn');
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            success: function(response) {
                $('#role-form-modal').modal('hide');
                table.draw();
                showToast(response.success);
            },
            error: function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value) {
                        $(`[name="${key}"]`).addClass('is-invalid').after(`<div class="invalid-feedback">${value[0]}</div>`);
                    });
                } else {
                    showToast('Something went wrong.', 'error');
                }
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fa-solid fa-check-circle me-2"></i> Save Role');
            }
        });
    });

    // Delete Logic
    $('body').on('click', '.deleteRole', function() {
        $('#delete-role-id').val($(this).data('id'));
        $('#role-delete-modal').modal('show');
    });

    $('#confirm-delete-btn').on('click', function() {
        const id = $('#delete-role-id').val();
        const btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');
        $.ajax({
            type: "DELETE",
            url: "{{ url('admin/roles') }}/" + id,
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(data) {
                $('#role-delete-modal').modal('hide');
                table.draw();
                showToast(data.success);
            },
            error: function() {
                showToast('Error deleting role', 'error');
            },
            complete: function() {
                btn.prop('disabled', false).text('Delete Now');
            }
        });
    });
</script>
@endsection