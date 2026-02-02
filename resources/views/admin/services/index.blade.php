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
                                    <th>Sl. No</th>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Slug</th>
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
                <style>
                    /* Avatar Upload Styling from Clients Module */
                    .avatar-upload {
                        position: relative;
                        max-width: 150px;
                        margin: 0 auto;
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
                        align-items: center;
                        justify-content: center;
                    }

                    .avatar-upload .avatar-edit label:hover {
                        background: #f1f1f1;
                        border-color: #d6d6d6;
                    }

                    .avatar-upload .avatar-edit label i {
                        color: #757575;
                        font-size: 16px;
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
                <form id="service-form" method="POST" enctype="multipart/form-data" class="theme-form">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <input type="hidden" name="id" id="service_id">

                    <div class="row g-3">
                        <!-- Hidden Inputs for logic -->
                        <input type="hidden" name="main_image_gallery_id" id="main_image_gallery_id">
                        <!-- 'image' and 'gallery_images[]' will be handled via FormData logic -->

                        <div class="col-md-12">
                            <label class="form-label">Service Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="title" required placeholder="e.g. Yoga Therapy">
                        </div>

                        <!-- Categories (Root & Sub) -->
                        <div class="col-md-6 border-end">
                            <label class="form-label mb-2 fw-bold text-muted small text-uppercase">Categories</label>
                            <div class="row" id="root-categories-container">
                                @foreach($categories->whereNull('parent_id') as $parent)
                                <div class="col-12 category-item" data-id="{{ $parent->id }}">
                                    <div class="form-check checkbox-primary mb-2 d-flex align-items-center">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $parent->id }}" id="cat_{{ $parent->id }}">
                                        <label class="form-check-label flex-grow-1 mb-0 ms-2" for="cat_{{ $parent->id }}">{{ $parent->name }}</label>
                                        <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="{{ $parent->id }}" data-type="service_categories"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-2 input-group input-group-sm">
                                <input type="text" class="form-control new-master-data-input" id="new-root-category-input" placeholder="Add Category" data-type="service_categories">
                                <button class="btn btn-primary" type="button" id="add-root-category-btn"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label mb-2 fw-bold text-muted small text-uppercase">Subcategories</label>
                            <div class="row" id="sub-categories-container">
                                @foreach($categories->whereNotNull('parent_id') as $child)
                                <div class="col-12 category-item" data-id="{{ $child->id }}">
                                    <div class="form-check checkbox-secondary mb-2 d-flex align-items-center">
                                        <input class="form-check-input" type="checkbox" name="categories[]" value="{{ $child->id }}" id="cat_{{ $child->id }}">
                                        <label class="form-check-label flex-grow-1 mb-0 ms-2" for="cat_{{ $child->id }}">
                                            {{ $child->name }}
                                            <span class="text-muted small">({{ $child->parent->name ?? 'N/A' }})</span>
                                        </label>
                                        <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="{{ $child->id }}" data-type="service_categories"><i class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="mt-2 input-group input-group-sm">
                                <input type="text" class="form-control new-master-data-input" id="new-sub-category-input" placeholder="Subcategory Name" data-type="service_categories">
                                <select class="form-select" id="new-subcategory-parent">
                                    <option value="" selected disabled>Parent</option>
                                    @foreach($categories->whereNull('parent_id') as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary" type="button" id="add-sub-category-btn"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <!-- Service Images (Simplified) -->
                        <div class="col-12">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <label class="form-label fw-bold mb-0">Images</label>
                                <button type="button" class="btn btn-light text-dark btn-sm border" onclick="$('#bulk_upload').click()">
                                    <i class="fa fa-cloud-upload me-1"></i> Add Images
                                </button>
                                <input type="file" id="bulk_upload" multiple accept="image/*" class="d-none">
                            </div>
                            <div class="border rounded p-3 bg-white">
                                <div id="media-grid" class="d-flex flex-wrap gap-2"></div>
                                <div class="text-muted small mt-2 fst-italic">
                                    <i class="fa fa-info-circle me-1"></i>Click an image to set as <strong>Main Cover</strong>.
                                </div>
                            </div>
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

<!-- Master Data Delete Modal -->
<div class="modal fade" id="master-data-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="iconly-Delete icli text-danger mb-3" style="font-size: 50px;"></i>
                <h5>Are you sure?</h5>
                <p class="text-muted">Do you want to delete this specific category? This action is permanent and may affect other services.</p>
                <input type="hidden" id="delete-master-id">
                <input type="hidden" id="delete-master-type">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirm-master-delete-btn">Delete Now</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- Summernote -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
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

    /* Fix for Summernote bold typing issue */
    .note-editable {
        font-weight: 400 !important;
        color: #333 !important;
        background-color: #fff !important;
    }
</style>


<script>
    var servicesTable;
    var baseUrl = "{{ asset('') }}";
    var storageUrl = "{{ asset('storage') }}";

    var servicesTable;
    var baseUrl = "{{ asset('') }}";
    var storageUrl = "{{ asset('storage') }}";

    // Media Manager State
    let newFiles = []; // Array of File objects
    let existingImages = []; // Array of objects {id, path, type='gallery'/'main'}
    let mainSelection = null; // { type: 'new'|'existing', id: id|index }

    function openCreateModal() {
        $('#form-modal-title').text('Add Service');
        $('#service-form')[0].reset();
        $('#form-method').val('POST');
        $('#service_id').val('');
        $('input[type="checkbox"][name="categories[]"]').prop('checked', false);
        $('#description').summernote('reset');

        // Reset Media State
        newFiles = [];
        existingImages = [];
        mainSelection = null;
        renderMediaGrid();

        $('#service-form-modal').modal('show');
    }

    function renderMediaGrid() {
        let html = '';

        // Render Existing Images
        existingImages.forEach(function(img) {
            let isMain = (mainSelection && mainSelection.type === 'existing' && mainSelection.id == img.id);
            let badge = isMain ? '<span class="badge bg-primary position-absolute top-0 start-0 m-1">Main Cover</span>' : '';
            let borderClass = isMain ? 'border-primary border-3' : 'border-light';

            html += `
                <div class="position-relative media-item" style="width: 100px; height: 100px; cursor: pointer;" onclick="setMain('existing', ${img.id})">
                    <img src="${img.path}" class="w-100 h-100 object-fit-cover rounded border ${borderClass}">
                    ${badge}
                    ${!isMain ? `<button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 d-flex justify-content-center align-items-center" 
                        onclick="deleteExistingImage(event, ${img.id})" style="width: 20px; height: 20px; border-radius: 50%;">&times;</button>` : ''}
                </div>
            `;
        });

        // Render New Files
        newFiles.forEach(function(file, index) {
            let isMain = (mainSelection && mainSelection.type === 'new' && mainSelection.id === index);
            let badge = isMain ? '<span class="badge bg-primary position-absolute top-0 start-0 m-1">Main Cover</span>' : '';
            let borderClass = isMain ? 'border-primary border-3' : 'border-light';
            let objectUrl = URL.createObjectURL(file);

            html += `
                <div class="position-relative media-item" style="width: 100px; height: 100px; cursor: pointer;" onclick="setMain('new', ${index})">
                    <img src="${objectUrl}" class="w-100 h-100 object-fit-cover rounded border ${borderClass}">
                    ${badge}
                    ${!isMain ? `<button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 p-0 d-flex justify-content-center align-items-center" 
                        onclick="removeNewFile(event, ${index})" style="width: 20px; height: 20px; border-radius: 50%;">&times;</button>` : ''}
                </div>
            `;
        });

        if (existingImages.length === 0 && newFiles.length === 0) {
            html = '<div class="text-center w-100 p-3 text-muted border border-dashed rounded">No images selected</div>';
        }

        $('#media-grid').html(html);
    }

    function setMain(type, id) {
        mainSelection = {
            type: type,
            id: id
        };
        renderMediaGrid();
    }

    function removeNewFile(e, index) {
        e.stopPropagation();
        newFiles.splice(index, 1);
        if (mainSelection && mainSelection.type === 'new' && mainSelection.id === index) mainSelection = null;
        // Adjust index if we removed an item before the current selection
        else if (mainSelection && mainSelection.type === 'new' && mainSelection.id > index) mainSelection.id--;

        // Auto-select first if main is gone
        if (!mainSelection && (existingImages.length > 0 || newFiles.length > 0)) {
            if (existingImages.length > 0) setMain('existing', existingImages[0].id);
            else if (newFiles.length > 0) setMain('new', 0);
        }
        renderMediaGrid();
    }

    // Note: Deleting existing main image logic handled separately or via AJAX
    window.deleteExistingImage = function(e, id) {
        e.stopPropagation();
        if (!confirm('Delete this image permanently?')) return;

        let url = "";
        // Need to check if it's a gallery image or the main image (though main doesn't have delete btn in UI)
        let img = existingImages.find(i => i.id == id);

        if (img.type === 'main') {
            // Should not happen as delete btn is hidden for main, but safe check
            alert("Cannot delete the active main image. Select another main image first.");
            return;
        }

        $.ajax({
            url: "{{ url('admin/services/image') }}/" + id,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function() {
                existingImages = existingImages.filter(i => i.id != id);
                renderMediaGrid();
            }
        });
    };

    $(document).ready(function() {
        // Bulk Upload Handler
        $('#bulk_upload').on('change', function(e) {
            if (this.files) {
                Array.from(this.files).forEach(f => newFiles.push(f));

                // If no main image selected yet, select first new file
                if (!mainSelection && newFiles.length > 0) {
                    // Check existing priority
                    if (existingImages.length === 0) {
                        mainSelection = {
                            type: 'new',
                            id: 0
                        };
                    }
                }
                renderMediaGrid();
            }
            // Reset input so same files can be selected again if needed
            $(this).val('');
        });

        // Initialize Summernote
        $('#description').summernote({
            placeholder: 'Enter service description...',
            tabsize: 2,
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onPaste: function(e) {
                    var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
                    e.preventDefault();
                    document.execCommand('insertText', false, bufferText);
                }
            }
        });

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
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    orderable: false,
                    searchable: false
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
                    data: 'slug',
                    name: 'slug'
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
                [1, 'asc']
            ]
        });

        // Submit Form
        $('#service-form').on('submit', function(e) {
            e.preventDefault();

            // Ensure the textarea has the current Summernote content
            $('#description').val($('#description').summernote('code'));

            let formData = new FormData(this);

            // Construct Image Data
            if (mainSelection) {
                if (mainSelection.type === 'new') {
                    // New file is main
                    formData.append('image', newFiles[mainSelection.id]);
                } else if (mainSelection.type === 'existing') {
                    // Existing gallery image promoted to main
                    // If it was ALREADY the main image (id='main_...'), we don't send anything special for image
                    // If it was a gallery image, we send ID
                    if (String(mainSelection.id).indexOf('main_') === -1) {
                        formData.append('main_image_gallery_id', mainSelection.id);
                    }
                }
            } else if (newFiles.length > 0) {
                // Fallback: first new file is main
                formData.append('image', newFiles[0]);
                mainSelection = {
                    type: 'new',
                    id: 0
                };
            }

            // Append remaining new files to gallery_images
            newFiles.forEach((file, index) => {
                if (!(mainSelection && mainSelection.type === 'new' && mainSelection.id === index)) {
                    formData.append('gallery_images[]', file);
                }
            });

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

                $('input[name="title"]').val(data.title);

                $('#description').summernote('code', data.description || '');

                $('input[type="checkbox"][name="categories[]"]').prop('checked', false);
                if (data.categories) {
                    data.categories.forEach(function(cat) {
                        $('#cat_' + cat.id).prop('checked', true);
                    });
                }

                // Populate Media State
                newFiles = [];
                existingImages = [];
                mainSelection = null;

                // Main Image
                if (data.image) {
                    let imgPath = data.image.startsWith('frontend/') ? baseUrl + data.image : storageUrl + '/' + data.image;
                    let mainId = 'main_' + data.id; // Unique ID for current main
                    existingImages.push({
                        id: mainId,
                        path: imgPath + '?t=' + new Date().getTime(),
                        type: 'main'
                    });
                    mainSelection = {
                        type: 'existing',
                        id: mainId
                    };
                }

                // Gallery Images
                if (data.images) {
                    data.images.forEach(img => {
                        existingImages.push({
                            id: img.id,
                            path: storageUrl + '/' + img.image_path + '?t=' + new Date().getTime(),
                            type: 'gallery'
                        });
                    });
                }

                renderMediaGrid();

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
                // Prepare Images for Carousel
                let slides = [];
                if (data.image) {
                    slides.push({
                        src: data.image.startsWith('frontend/') ? baseUrl + data.image : storageUrl + '/' + data.image,
                        is_main: true
                    });
                } else {
                    // Placeholder if absolutely no image
                    if (!data.images || data.images.length === 0) {
                        slides.push({
                            src: "{{ asset('admiro/assets/images/user/user.png') }}",
                            is_main: true
                        });
                    }
                }

                if (data.images) {
                    data.images.forEach(img => {
                        slides.push({
                            src: storageUrl + '/' + img.image_path,
                            is_main: false
                        });
                    });
                }

                // Build Carousel HTML
                let carouselId = 'carouselServiceView';
                let indicators = '';
                let items = '';

                slides.forEach((slide, index) => {
                    let active = index === 0 ? 'active' : '';
                    indicators += `<button type="button" data-bs-target="#${carouselId}" data-bs-slide-to="${index}" class="${active}" aria-current="true" aria-label="Slide ${index+1}"></button>`;

                    let badge = slide.is_main ? '<span class="badge bg-primary position-absolute top-0 start-0 m-3 shadow">Main Cover</span>' : '';

                    items += `
                        <div class="carousel-item ${active}" style="height: 300px; background-color: #f8f9fa;">
                            <img src="${slide.src}" class="d-block w-100 h-100 object-fit-contain" alt="Service Image">
                            ${badge}
                        </div>
                    `;
                });

                let carouselControls = '';
                if (slides.length > 1) {
                    carouselControls = `
                        <button class="carousel-control-prev" type="button" data-bs-target="#${carouselId}" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon bg-dark rounded-circle p-3" aria-hidden="true" style="background-size: 50%;"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#${carouselId}" data-bs-slide="next">
                            <span class="carousel-control-next-icon bg-dark rounded-circle p-3" aria-hidden="true" style="background-size: 50%;"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    `;
                }

                let carouselHtml = `
                    <div id="${carouselId}" class="carousel slide mb-4 rounded-4 overflow-hidden border shadow-sm" data-bs-ride="carousel">
                        <div class="carousel-indicators">
                            ${indicators}
                        </div>
                        <div class="carousel-inner">
                            ${items}
                        </div>
                        ${carouselControls}
                    </div>
                `;

                // Split categories
                var roots = [];
                var subs = [];
                if (data.categories) {
                    data.categories.forEach(function(cat) {
                        if (cat.parent_id) subs.push(cat);
                        else roots.push(cat);
                    });
                }

                var rootsHtml = roots.length > 0 ?
                    roots.map(c => `<span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2 rounded-pill me-1 mb-1 fw-normal">${c.name}</span>`).join('') :
                    '<span class="text-muted small fst-italic">None</span>';

                var subsHtml = subs.length > 0 ?
                    subs.map(c => `<span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill me-1 mb-1 fw-normal">${c.name}</span>`).join('') :
                    '<span class="text-muted small fst-italic">None</span>';

                var statusBadge = data.status ?
                    '<span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill"><i class="fa fa-check-circle me-1"></i>Active</span>' :
                    '<span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill"><i class="fa fa-times-circle me-1"></i>Inactive</span>';

                var html = `
                    <div class="row g-0">
                        <!-- Carousel Section -->
                        <div class="col-12">
                            ${carouselHtml}
                        </div>
                        
                        <!-- Header Info -->
                        <div class="col-12 mb-4">
                             <h4 class="mb-2 fw-bold text-dark">${data.title}</h4>
                             <div class="d-flex align-items-center gap-2">
                                ${statusBadge}
                                <span class="text-muted small"><i class="iconly-Time-Square icli me-1"></i>Last Updated: ${new Date().toLocaleDateString()}</span>
                            </div>
                        </div>

                        <!-- Categories Section -->
                        <div class="col-md-6 pe-md-3 mb-4">
                            <div class="h-100">
                                <h6 class="text-uppercase text-muted fw-bold small mb-3"><i class="iconly-Category icli me-2 text-primary"></i>Service Categories</h6>
                                <div class="d-flex flex-wrap">
                                    ${rootsHtml}
                                </div>
                            </div>
                        </div>

                        <!-- Subcategories Section -->
                        <div class="col-md-6 ps-md-3 mb-4">
                            <div class="h-100">
                                <h6 class="text-uppercase text-muted fw-bold small mb-3"><i class="iconly-Filter-2 icli me-2 text-secondary"></i>Subcategories</h6>
                                <div class="d-flex flex-wrap">
                                    ${subsHtml}
                                </div>
                            </div>
                        </div>

                        <!-- Description Section -->
                        <div class="col-12">
                            <div class="p-4 rounded-4 bg-white border border-light shadow-sm">
                                <h6 class="text-uppercase text-muted fw-bold small mb-3"><i class="iconly-Document icli me-2 text-info"></i>Description & Details</h6>
                                <div class="text-dark lh-lg" style="white-space: pre-wrap; font-size: 0.95rem;">${data.description || '<span class="text-muted fst-italic">No description provided for this service.</span>'}</div>
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

        // Add Root Category
        $('#add-root-category-btn').on('click', function() {
            let btn = $(this);
            let inputInfo = $('#new-root-category-input');
            let type = inputInfo.data('type');
            let name = inputInfo.val();

            if (!name) {
                alert('Please enter a category name');
                return;
            }

            btn.prop('disabled', true);

            $.ajax({
                url: "{{ url('admin/master-data') }}/" + type,
                type: "POST",
                data: {
                    name: name,
                    status: 1,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success && response.data) {
                        let newItem = response.data;
                        let newHtml = `
                            <div class="col-md-6 category-item" data-id="${newItem.id}">
                                <div class="form-check checkbox-primary mb-2 d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" name="categories[]" value="${newItem.id}" id="cat_${newItem.id}" checked>
                                    <label class="form-check-label flex-grow-1 mb-0 ms-2" for="cat_${newItem.id}">${newItem.name}</label>
                                    <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="${newItem.id}" data-type="service_categories"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        `;
                        $('#root-categories-container').append(newHtml);

                        // Update Subcategory Parent Dropdown
                        $('#new-subcategory-parent').append(new Option(newItem.name, newItem.id));

                        inputInfo.val('');
                        if (typeof showToast === 'function') showToast(response.success);
                    } else {
                        alert('Error adding item');
                    }
                },
                error: function(xhr) {
                    alert('Failed to add item');
                },
                complete: function() {
                    btn.prop('disabled', false);
                }
            });
        });

        // Add Sub Category
        $('#add-sub-category-btn').on('click', function() {
            let btn = $(this);
            let inputInfo = $('#new-sub-category-input');
            let type = inputInfo.data('type');
            let name = inputInfo.val();
            let parentId = $('#new-subcategory-parent').val();
            let parentName = $('#new-subcategory-parent option:selected').text();

            if (!parentId) {
                alert('Please select a parent category');
                return;
            }
            if (!name) {
                alert('Please enter a subcategory name');
                return;
            }

            btn.prop('disabled', true);

            $.ajax({
                url: "{{ url('admin/master-data') }}/" + type,
                type: "POST",
                data: {
                    name: name,
                    parent_id: parentId,
                    status: 1,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success && response.data) {
                        let newItem = response.data;
                        let newHtml = `
                            <div class="col-md-6 category-item" data-id="${newItem.id}">
                                <div class="form-check checkbox-secondary mb-2 d-flex align-items-center">
                                    <input class="form-check-input" type="checkbox" name="categories[]" value="${newItem.id}" id="cat_${newItem.id}" checked>
                                    <label class="form-check-label flex-grow-1 mb-0 ms-2" for="cat_${newItem.id}">
                                        ${newItem.name} 
                                        <span class="text-muted small">(${parentName})</span>
                                    </label>
                                    <a href="javascript:void(0)" class="text-danger ms-2 delete-master-data-btn" data-id="${newItem.id}" data-type="service_categories"><i class="fa fa-trash"></i></a>
                                </div>
                            </div>
                        `;
                        $('#sub-categories-container').append(newHtml);

                        inputInfo.val('');
                        if (typeof showToast === 'function') showToast(response.success);
                    } else {
                        alert('Error adding item');
                    }
                },
                error: function(xhr) {
                    alert('Failed to add item');
                },
                complete: function() {
                    btn.prop('disabled', false);
                }
            });
        });

        // Delete Master Data (Modal Open)
        $('body').on('click', '.delete-master-data-btn', function() {
            let id = $(this).data('id');
            let type = $(this).data('type');
            $('#delete-master-id').val(id);
            $('#delete-master-type').val(type);

            // Store reference to remove from DOM later
            $('#master-data-delete-modal').data('target-row', $(this).closest('.category-item'));

            $('#master-data-delete-modal').modal('show');
        });

        // Confirm Delete Master Data
        $('#confirm-master-delete-btn').click(function() {
            let id = $('#delete-master-id').val();
            let type = $('#delete-master-type').val();
            let btn = $(this);
            let targetRow = $('#master-data-delete-modal').data('target-row');

            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');

            $.ajax({
                url: "{{ url('admin/master-data') }}/" + type + "/" + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#master-data-delete-modal').modal('hide');
                    if (targetRow) targetRow.remove();
                    if (typeof showToast === 'function') showToast(response.success);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Error deleting item');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Delete Now');
                }
            });
        });

    });
</script>
@endsection