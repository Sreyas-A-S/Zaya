@extends('layouts.admin')
@section('content')
<style>
    #Admins-table_wrapper .dataTables_filter {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    #Admins-table_wrapper .dataTables_filter label {
        margin-bottom: 0;
    }
    #custom-filters-container {
        margin-bottom: 0 !important;
    }
</style>
    <div class="container-fluid">
        <div class="page-title">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Admins Management</h3>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="iconly-Home icli svg-color"></i></a></li>
                        <li class="breadcrumb-item">Admins</li>
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
                        <h3 class="mb-0">Admins List</h3>
                        <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                            <i class="iconly-Add-User icli me-2"></i>Register New Admin
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-start align-items-center mb-3 gap-3 d-none" id="custom-filters-container">
                            <div class="d-flex align-items-center gap-2">
                                <label class="mb-0 small fw-bold text-muted">COUNTRY:</label>
                                <select id="country-filter" class="form-select form-select-sm" style="width: 180px;">
                                    <option value="">All Countries</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="display" id="Admins-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Nationality</th>
                                        <th>Languages</th>
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

    <div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="editAdminForm">
                        @csrf
                        <input type="hidden" id="edit_id">
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" id="edit_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" id="edit_email" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" id="edit_phone" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Country</label>
                                <select id="edit_country" class="form-select" required>
                                    <option value="" disabled>Select a Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Language</label>
                                <select id="edit_language" class="form-select" required>
                                    <option value="" disabled>Select a Language</option>
                                    @foreach ($languages as $language)
                                        <option value="{{ $language->id }}">{{ $language->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select id="edit_status" class="form-select" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-end mt-3">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Admin</button>
                        </div>
                    </form>
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
                    <i class="iconly-Info-Circle icli text-primary mb-3" style="font-size: 50px;"></i>
                    <h5 id="status-confirmation-text">Update Admin Status</h5>
                    <p>Select the new status for this administrator:</p>
                    <div class="mb-3 px-5">
                        <select id="status-select-input" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <input type="hidden" id="status-admin-id">
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirm-status-btn">Confirm Change</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="admin-form-modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Register New Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('admin.admins.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Profile Picture</label>
                                <input type="file" name="profile_picture" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" name="firstname" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="lastname" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone number</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <select name="country" class="form-select" required>
                                    <option selected disabled>Select a Country</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Language</label>
                                <select name="language" class="form-select" required>
                                    <option selected disabled>Select a Language</option>
                                    @foreach ($languages as $language)
                                        <option value="{{ $language->id }}">{{ $language->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required>
                            </div>
                            <div class="col-12 text-end mt-4">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Create Admin</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {

                const table = $('#Admins-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('admin.admins.index') }}",
                        data: function (d) {
                            d.country_filter = $('#country-filter').val();
                        }
                    },
                    initComplete: function() {
                        // Move the country filter next to search box
                        const filterHtml = $('#custom-filters-container').removeClass('d-none').detach();
                        $('#Admins-table_wrapper .dataTables_filter').parent().prepend(filterHtml);
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'name',
                            name: 'users.name'
                        },
                        {
                            data: 'email',
                            name: 'users.email'
                        },
                        {
                            data: 'phone',
                            name: 'users.phone'
                        },
                        {
                            data: 'nationality',
                            name: 'countries.name'
                        },
                        {
                            data: 'languages',
                            name: 'users.languages',
                            orderable: false
                        },
                        {
                            data: 'status',
                            name: 'users.status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                $('#country-filter').on('change', function() {
                    table.ajax.reload();
                });

            });

            function openCreateModal() {
                $('#admin-form-modal').modal('show');
            }

            // Open Edit Modal
            $(document).on('click', '.editUser', function() {
                let id = $(this).data('id');
                $.ajax({
                    url: "{{ url('admin/admins') }}/" + id + "/edit",
                    type: "GET",
                    success: function(data) {
                        $('#edit_id').val(data.id);
                        $('#edit_name').val(data.name);
                        $('#edit_email').val(data.email);
                        $('#edit_phone').val(data.phone);
                        $('#edit_country').val(data.national_id);
                        $('#edit_language').val(data.languages);
                        $('#edit_status').val(data.status);
                        $('#editAdminModal').modal('show');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            // Update Admin
            $('#editAdminForm').submit(function(e) {
                e.preventDefault();
                let id = $('#edit_id').val();
                $.ajax({
                    url: '/admin/admins/' + id,
                    type: 'PUT',
                    data: {
                        _token: "{{ csrf_token() }}",
                        name: $('#edit_name').val(),
                        email: $('#edit_email').val(),
                        phone: $('#edit_phone').val(),
                        country: $('#edit_country').val(),
                        language: $('#edit_language').val(),
                        status: $('#edit_status').val()
                    },
                    success: function(response) {
                        $('#editAdminModal').modal('hide');
                        $('#Admins-table').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        alert('Something went wrong');
                    }
                });
            });

            // Handle Status Change Click
            $(document).on('click', '.toggle-status', function() {
                const $this = $(this);
                const id = $this.data('id');
                const currentStatus = $this.data('status') || 'inactive';

                $('#status-admin-id').val(id);
                $('#status-select-input').val(currentStatus);
                $('#status-confirmation-modal').modal('show');
            });

            // Handle Confirm Status Change
            $(document).off('click', '#confirm-status-btn').on('click', '#confirm-status-btn', function() {
                const id = $('#status-admin-id').val();
                const newStatus = $('#status-select-input').val();
                const btn = $(this);

                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

                $.ajax({
                    url: "{{ url('admin/admins') }}/" + id + "/status",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: newStatus
                    },
                    success: function(response) {
                        $('#status-confirmation-modal').modal('hide');
                        $('#Admins-table').DataTable().ajax.reload(null, false);
                    },
                    error: function() {
                        alert('Failed to update status.');
                    },
                    complete: function() {
                        btn.prop('disabled', false).text('Confirm Change');
                    }
                });
            });

            // Delete Admin
            $(document).on('click', '.deleteUser', function() {
                let id = $(this).data('id');
                if (confirm('Are you sure?')) {
                    $.ajax({
                        url: "{{ url('admin/admins') }}/" + id,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            $('#Admins-table').DataTable().ajax.reload(null, false);
                        },
                        error: function(xhr) {
                            alert('Error deleting admin');
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection
