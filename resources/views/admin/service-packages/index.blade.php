@extends('layouts.admin')

@section('title', 'Service Packages')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('admiro/assets/css/vendors/select2.css') }}">
<style>
    /* Select2 Fixes */
    .select2-container {
        width: 100% !important;
        display: block;
    }

    .select2-container--default .select2-selection--multiple {
        min-height: 45px;
        border: 1px solid #d9dde7;
        border-radius: 8px;
        padding: 5px 10px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #5c61f2;
        outline: 0;
    }

    .select2-dropdown {
        border-color: #d9dde7;
        border-radius: 10px;
        z-index: 9999;
    }

    /* Cropper Styles */
    .cropper-container-wrapper {
        min-height: 400px;
        background-color: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #package-cropper-image {
        max-width: 100%;
        display: block;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Service Packages</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Services</li>
                    <li class="breadcrumb-item active">Service Packages</li>
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
                    <h3>Service Package List</h3>
                    <button type="button" class="btn btn-primary" onclick="openServicePackageModal()">
                        <i class="fa-solid fa-plus me-2"></i>Add Service Package
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="service-packages-table">
                            <thead>
                                <tr>
                                    <th>Sl. No</th>
                                    <th>Cover</th>
                                    <th>Title</th>
                                    <th>Included Services</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Modal -->
<div class="modal fade" id="service-package-modal" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="service-package-modal-title">Add Service Package</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="service-package-form">
                    @csrf
                    <input type="hidden" id="service-package-id">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label">Cover Image</label>
                            <input type="file" class="form-control" id="package-image-input" accept="image/*">
                            <input type="hidden" name="cover_image_base64" id="cropped-package-cover">
                            <div class="mt-2 package-preview-container d-none">
                                <img src="" class="img-thumbnail" id="package-preview-img" style="height: 150px;">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="service-package-title" name="title" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Status</label>
                            <select class="form-control" id="service-package-status" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="service-package-description" name="description" rows="4"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Combo Services <span class="text-danger">*</span></label>
                            <select class="form-control" id="service-package-services" name="service_ids[]" multiple required>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->title }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Search and select at least 2 services for each package.</small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit" form="service-package-form">Save changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Cropper Modal -->
<div class="modal fade" id="package-cropper-modal" data-bs-backdrop="static" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crop Cover Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="cropper-container-wrapper">
                    <img id="package-cropper-image" src="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="apply-package-crop">Apply Crop</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('admiro/assets/js/select2/select2.full.min.js') }}"></script>
<script>
    $(function () {
        // --- Select2 Logic ---
        function initSelect2() {
            if ($.fn.select2) {
                $('#service-package-services').select2({
                    placeholder: "Search and choose services",
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('#service-package-modal')
                });
            }
        }

        // Re-init on modal open
        $('#service-package-modal').on('shown.bs.modal', function() {
            initSelect2();
        });

        // --- Cropper Logic ---
        let packageCropper;
        const cropperModal = new bootstrap.Modal(document.getElementById('package-cropper-modal'));
        const cropperImage = document.getElementById('package-cropper-image');

        $('#package-image-input').on('change', function() {
            const files = this.files;
            if (files && files.length > 0) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    cropperImage.src = e.target.result;
                    cropperModal.show();
                };
                reader.readAsDataURL(files[0]);
            }
        });

        document.getElementById('package-cropper-modal').addEventListener('shown.bs.modal', function() {
            packageCropper = new Cropper(cropperImage, {
                aspectRatio: 1.5,
                viewMode: 1,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        });

        document.getElementById('package-cropper-modal').addEventListener('hidden.bs.modal', function() {
            if (packageCropper) {
                packageCropper.destroy();
                packageCropper = null;
            }
            $('#package-image-input').val('');
        });

        $('#apply-package-crop').on('click', function() {
            if (!packageCropper) return;
            const canvas = packageCropper.getCroppedCanvas({
                width: 600,
                height: 400,
                imageSmoothingEnabled: true,
                imageSmoothingQuality: 'high',
            });
            const base64Data = canvas.toDataURL('image/jpeg', 0.9);
            $('#cropped-package-cover').val(base64Data);
            $('#package-preview-img').attr('src', base64Data);
            $('.package-preview-container').removeClass('d-none');
            cropperModal.hide();
        });

        // --- DataTable Logic ---
        const table = $('#service-packages-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.service-packages.index') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'cover_image', name: 'cover_image', orderable: false, searchable: false },
                { data: 'title', name: 'title' },
                { data: 'services', name: 'services', orderable: false, searchable: false },
                { data: 'status_label', name: 'status', orderable: false, searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });

        // --- Form Submission ---
        $('#service-package-form').on('submit', function (e) {
            e.preventDefault();
            const id = $('#service-package-id').val();
            const url = id ? "{{ url('admin/service-packages') }}/" + id : "{{ route('admin.service-packages.store') }}";
            const payload = $(this).serialize() + (id ? '&_method=PUT' : '');

            $.ajax({
                url: url,
                type: 'POST',
                data: payload,
                success: function (response) {
                    $('#service-package-modal').modal('hide');
                    table.ajax.reload(null, false);
                    if (typeof showToast === 'function') showToast(response.success);
                },
                error: function (xhr) {
                    const message = xhr.responseJSON?.message || 'Failed to save service package.';
                    if (typeof showToast === 'function') showToast(message, 'error');
                }
            });
        });

        // --- Edit Mode ---
        $(document).on('click', '.editServicePackage', function () {
            const id = $(this).data('id');
            $.get("{{ url('admin/service-packages') }}/" + id, function (data) {
                $('#service-package-modal-title').text('Edit Service Package');
                $('#service-package-id').val(data.id);
                $('#service-package-title').val(data.title);
                $('#service-package-description').val(data.description || '');
                $('#service-package-status').val(data.status ? '1' : '0');
                
                // Image handling
                $('#package-image-input').val('');
                $('#cropped-package-cover').val('');
                if (data.cover_image_url) {
                    $('#package-preview-img').attr('src', data.cover_image_url);
                    $('.package-preview-container').removeClass('d-none');
                } else {
                    $('#package-preview-img').attr('src', '');
                    $('.package-preview-container').addClass('d-none');
                }

                $('#service-package-services').val(data.service_ids || []).trigger('change');
                $('#service-package-modal').modal('show');
            });
        });

        // --- Delete & Status Logic ---
        $(document).on('click', '.deleteServicePackage', function () {
            const id = $(this).data('id');
            if (!confirm('Delete this service package?')) return;
            $.ajax({
                url: "{{ url('admin/service-packages') }}/" + id,
                type: 'POST',
                data: { _token: "{{ csrf_token() }}", _method: 'DELETE' },
                success: function (response) {
                    table.ajax.reload(null, false);
                    if (typeof showToast === 'function') showToast(response.success);
                }
            });
        });

        $(document).on('click', '.toggle-status', function () {
            const id = $(this).data('id');
            const nextStatus = $(this).data('status') === 'active' ? 0 : 1;
            $.post("{{ url('admin/service-packages') }}/" + id + "/status", {
                _token: "{{ csrf_token() }}",
                status: nextStatus
            }).done(function (response) {
                table.ajax.reload(null, false);
                if (typeof showToast === 'function') showToast(response.success);
            });
        });
    });

    function openServicePackageModal() {
        $('#service-package-form')[0].reset();
        $('#service-package-id').val('');
        $('#service-package-services').val([]).trigger('change');
        $('#service-package-modal-title').text('Add Service Package');
        $('#package-image-input').val('');
        $('#cropped-package-cover').val('');
        $('#package-preview-img').attr('src', '');
        $('.package-preview-container').addClass('d-none');
        $('#service-package-modal').modal('show');
    }
</script>
@endsection
