@extends('layouts.admin')

@section('title', 'Services Management')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Services</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Services</li>
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
                    <h3>Services List</h3>
                    <button type="button" class="btn btn-primary" onclick="openCreateModal()">
                        <i class="fa-solid fa-plus me-2"></i>Add Service
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="services-table">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Link</th>
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

<!-- Form Modal -->
<div class="modal fade" id="service-form-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="form-modal-title">Add Service</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="service-form" method="POST" enctype="multipart/form-data" class="theme-form">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <input type="hidden" name="id" id="service_id">

                    <div class="row g-3">
                        <div class="col-md-12 text-center mb-4">
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input type='file' id="imageUpload" name="image" accept=".png, .jpg, .jpeg" />
                                    <label for="imageUpload"><i class="iconly-Edit icli"></i></label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="imagePreview" style="background-image: url('{{ asset('admiro/assets/images/user/user.png') }}');">
                                    </div>
                                </div>
                            </div>
                            <label class="form-label mt-2">Service Image</label>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Link</label>
                            <input type="text" class="form-control" name="link" placeholder="e.g. /services/yoga">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Order Column</label>
                            <input type="number" class="form-control" name="order_column" value="0">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="service-form" class="btn btn-primary" id="submit-btn">Save Service</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="service-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="iconly-Delete icli text-danger mb-3" style="font-size: 50px;"></i>
                <h5>Are you sure?</h5>
                <p>Do you want to delete this service? This action cannot be undone.</p>
                <input type="hidden" id="delete-service-id">
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
                <input type="hidden" id="status-service-id">
                <input type="hidden" id="status-new-value">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-status-btn">Confirm Change</button>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="service-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Service Details</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="view-modal-content">
                <!-- Content will be loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<style>
    .avatar-upload {
        position: relative;
        max-width: 150px;
        margin: 10px auto;
    }

    .avatar-upload .avatar-edit {
        position: absolute;
        right: 12px;
        z-index: 1;
        top: 10px;
    }

    .avatar-upload .avatar-edit input {
        display: none;
    }

    .avatar-upload .avatar-edit label {
        display: inline-block;
        width: 34px;
        height: 34px;
        margin-bottom: 0;
        border-radius: 100%;
        background: #FFFFFF;
        border: 1px solid transparent;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.12);
        cursor: pointer;
        font-weight: normal;
        transition: all .2s ease-in-out;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .avatar-upload .avatar-edit label:hover {
        background: #f1f1f1;
        border-color: #d6d6d6;
    }

    .avatar-preview {
        width: 150px;
        height: 150px;
        position: relative;
        border-radius: 100%;
        border: 4px solid #F8F8F8;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
    }

    .avatar-preview>div {
        width: 100%;
        height: 100%;
        border-radius: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
</style>


<script>
    var servicesTable;
    var baseUrl = "{{ asset('') }}";
    var storageUrl = "{{ asset('storage') }}";

    function openCreateModal() {
        $('#form-modal-title').text('Add Service');
        $('#service-form')[0].reset();
        $('#form-method').val('POST');
        $('#service_id').val('');
        $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
        $('#service-form-modal').modal('show');
    }

    $(document).ready(function() {
        // DataTable
        servicesTable = $('#services-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.services.index') }}",
                data: function(d) {
                    d.timestamp = new Date().getTime();
                },
                error: function(xhr, error, code) {
                    console.error('DataTable Error:', error, code, xhr.responseText);
                }
            },
            columns: [{
                    data: 'order_column',
                    name: 'order_column'
                },
                {
                    data: 'image_url',
                    name: 'image_url',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return '<img src="' + data + '" alt="Image" class="img-fluid rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">';
                    }
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'link',
                    name: 'link'
                },
                {
                    data: 'status_label',
                    name: 'status_label',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            order: [
                [0, 'asc']
            ]
        });


        // Image Preview
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#imageUpload").change(function() {
            readURL(this);
        });

        // Submit Form
        $('#service-form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let url = "{{ route('admin.services.store') }}";
            let method = $('#form-method').val();

            if (method === 'PUT') {
                url = "{{ url('admin/services') }}/" + $('#service_id').val();
                formData.append('_method', 'PUT');
            }

            $('#submit-btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#service-form-modal').modal('hide');
                    if (servicesTable) servicesTable.draw();
                    if (typeof showToast === 'function') showToast(response.success);
                    else alert(response.success);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    let errors = xhr.responseJSON ? xhr.responseJSON.errors : null;
                    if (errors) {
                        $.each(errors, function(key, value) {
                            alert(value[0]);
                        });
                    } else {
                        alert('Something went wrong');
                    }
                },
                complete: function() {
                    $('#submit-btn').prop('disabled', false).text('Save Service');
                }
            });
        });

        // Edit
        $('body').on('click', '.editService', function() {
            let id = $(this).data('id');
            $.get("{{ url('admin/services') }}/" + id + "/edit", function(data) {
                $('#form-modal-title').text('Edit Service');
                $('#form-method').val('PUT');
                $('#service_id').val(data.id);
                $('input[name="title"]').val(data.title);
                $('input[name="link"]').val(data.link);
                $('input[name="order_column"]').val(data.order_column);
                $('textarea[name="description"]').val(data.description);

                if (data.image) {
                    let imgPath = data.image.startsWith('frontend/') ? baseUrl + data.image : storageUrl + '/' + data.image;
                    // Add timestamp to prevent caching
                    imgPath += '?t=' + new Date().getTime();
                    $('#imagePreview').css('background-image', "url('" + imgPath + "')");
                } else {
                    $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
                }

                $('#service-form-modal').modal('show');
            }).fail(function() {
                alert('Error fetching service data');
            });
        });

        // Delete
        $('body').on('click', '.deleteService', function() {
            let id = $(this).data('id');
            $('#delete-service-id').val(id);
            $('#service-delete-modal').modal('show');
        });

        $('#confirm-delete-btn').click(function() {
            let id = $('#delete-service-id').val();
            let btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');

            $.ajax({
                type: "DELETE",
                url: "{{ url('admin/services') }}/" + id,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#service-delete-modal').modal('hide');
                    if (servicesTable) servicesTable.draw();
                    if (typeof showToast === 'function') showToast(data.success);
                    else alert(data.success);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Error deleting service');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Delete Now');
                }
            });
        });

        // View Service
        $('body').on('click', '.viewService', function() {
            var id = $(this).data('id');
            $.get("{{ url('admin/services') }}/" + id + "/edit", function(data) {
                var imageHtml = '';
                if (data.image) {
                    var src = data.image.startsWith('frontend/') ? '/' + data.image : '/storage/' + data.image;
                    imageHtml = '<div class="text-center mb-4"><img src="' + src + '" alt="Service Image" class="img-fluid rounded shadow-sm" style="max-height: 200px; border: 3px solid #f8f9fa;"></div>';
                }

                var html = `
                    ${imageHtml}
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded shadow-sm h-100">
                                <label class="fw-bold text-muted small text-uppercase d-block mb-1">Title</label>
                                <p class="h5 mb-0">${data.title}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded shadow-sm h-100">
                                <label class="fw-bold text-muted small text-uppercase d-block mb-1">Order Column</label>
                                <p class="h5 mb-0">${data.order_column || '0'}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <label class="fw-bold text-muted small text-uppercase d-block mb-1">Link</label>
                                <p class="mb-0 text-primary">${data.link || '<span class="text-muted">N/A</span>'}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="p-3 bg-light rounded shadow-sm">
                                <label class="fw-bold text-muted small text-uppercase d-block mb-1">Description</label>
                                <p class="mb-0 text-dark" style="white-space: pre-wrap;">${data.description || '<span class="text-muted fst-italic">No description provided.</span>'}</p>
                            </div>
                        </div>
                    </div>
                `;
                $('#view-modal-content').html(html);
                $('#service-view-modal').modal('show');
            });
        });


        // Toggle Status
        $('body').on('click', '.toggle-status', function() {
            var id = $(this).data('id');
            var currentStatus = $(this).data('status');
            var newStatus = (currentStatus === 'active') ? 0 : 1;
            var newStatusText = (currentStatus === 'active') ? 'Inactive' : 'Active';

            $('#status-service-id').val(id);
            $('#status-new-value').val(newStatus);
            $('#status-confirmation-text').text(`Are you sure you want to change the status of this service to ${newStatusText}?`);
            $('#status-confirmation-modal').modal('show');
        });

        // Handle Confirm Status Change
        $('#confirm-status-btn').on('click', function() {
            var id = $('#status-service-id').val();
            var newStatus = $('#status-new-value').val();
            var btn = $(this);

            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

            $.ajax({
                url: "{{ url('admin/services') }}/" + id + "/status",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: newStatus
                },
                success: function(response) {
                    $('#status-confirmation-modal').modal('hide');
                    if (servicesTable) servicesTable.draw(false);
                    if (typeof showToast === 'function') {
                        showToast(response.success);
                    } else {
                        alert(response.success);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
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