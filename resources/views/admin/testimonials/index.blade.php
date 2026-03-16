@extends('layouts.admin')

@section('title', 'Testimonials Management')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Testimonials</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Testimonials</li>
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
                    <h3>Testimonials List</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="testimonials-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Rating</th>
                                    <th>Likes/Replies</th>
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

<!-- Reply Management Modal -->
<div class="modal fade" id="replies-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manage Replies</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="testimonial-info" class="mb-4 p-3 bg-light rounded">
                    <!-- Original Testimonial info here -->
                </div>
                
                <div class="replies-list mb-4">
                    <h6>Existing Replies</h6>
                    <div id="replies-container" class="mt-3">
                        <!-- Replies will be loaded here -->
                    </div>
                </div>

                <hr>

                <div class="add-reply-section">
                    <h6>Add New Reply</h6>
                    <form id="reply-form" class="mt-3">
                        @csrf
                        <input type="hidden" name="testimonial_id" id="reply_testimonial_id">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Respondent Name</label>
                                <input type="text" class="form-control" name="name" value="Admin">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Respondent Role</label>
                                <input type="text" class="form-control" name="role" value="Management">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Reply Message <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="reply" rows="3" required placeholder="Write your response..."></textarea>
                            </div>
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary btn-sm">Post Reply</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Form Modal -->
<div class="modal fade" id="testimonial-form-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="form-modal-title">Add Testimonial</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="testimonial-form" method="POST" enctype="multipart/form-data" class="theme-form">
                    @csrf
                    <input type="hidden" name="_method" id="form-method" value="POST">
                    <input type="hidden" name="id" id="testimonial_id">

                    <div class="row g-3">
                        <div class="col-md-12 text-center mb-4">
                            <div class="avatar-upload">
                                <div class="avatar-edit">
                                    <input type='file' id="imageUpload" name="image" accept=".png, .jpg, .jpeg" required/>
                                    <label for="imageUpload"><i class="iconly-Edit icli"></i></label>
                                </div>
                                <div class="avatar-preview">
                                    <div id="imagePreview" style="background-image: url('{{ asset('admiro/assets/images/user/user.png') }}');">
                                    </div>
                                </div>
                            </div>
                            <label class="form-label mt-2">Client Photo</label>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" pattern="^[A-Z][a-zA-Z\s]{1,49}$"
                              title="First letter must be capital and only letters allowed" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Role/Designation</label>
                            <input type="text" class="form-control" name="role" placeholder="e.g. Yoga, Naturopathy" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Message <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="message" rows="4" required></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Rating <span class="text-danger">*</span></label>
                            <div class="rating-container">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating5" value="5" checked required>
                                    <label class="form-check-label" for="rating5">5 Stars</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating4" value="4" required>
                                    <label class="form-check-label" for="rating4">4 Stars</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating3" value="3" required>
                                    <label class="form-check-label" for="rating3">3 Stars</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating2" value="2" required>
                                    <label class="form-check-label" for="rating2">2 Stars</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="rating" id="rating1" value="1" required>
                                    <label class="form-check-label" for="rating1">1 Star</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" form="testimonial-form" class="btn btn-primary" id="submit-btn">Save Testimonial</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="testimonial-delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="iconly-Delete icli text-danger mb-3" style="font-size: 50px;"></i>
                <h5>Are you sure?</h5>
                <p>Do you want to delete this testimonial? This action cannot be undone.</p>
                <input type="hidden" id="delete-testimonial-id">
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
                <input type="hidden" id="status-testimonial-id">
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
<div class="modal fade" id="testimonial-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Testimonial Details</h5>
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
    $(document).ready(function() {
        // DataTable
        var table = $('#testimonials-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.testimonials.index') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'image',
                    name: 'image',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'role',
                    name: 'role'
                },
                {
                    data: 'rating_display',
                    name: 'rating',
                    orderable: true,
                    searchable: false
                },
                {
                    data: 'likes_replies',
                    name: 'likes_replies',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'status',
                    name: 'status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

        // Manage Replies
        $('body').on('click', '.manageReplies', function() {
            let id = $(this).data('id');
            $('#reply_testimonial_id').val(id);
            loadReplies(id);
            $('#replies-modal').modal('show');
        });

        function loadReplies(testimonialId) {
            $.get("{{ url('admin/testimonials') }}/" + testimonialId + "/replies", function(data) {
                let testimonial = data.testimonial;
                let replies = data.replies;

                let testimonialHtml = `
                    <div class="d-flex align-items-center gap-3">
                        <img src="${testimonial.image ? '/storage/' + testimonial.image : '{{ asset('admiro/assets/images/user/user.png') }}'}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0">${testimonial.name}</h6>
                            <p class="mb-0 small text-muted">${testimonial.message.substring(0, 100)}...</p>
                        </div>
                    </div>
                `;
                $('#testimonial-info').html(testimonialHtml);

                let repliesHtml = '';
                if (replies.length > 0) {
                    replies.forEach(reply => {
                        repliesHtml += `
                            <div class="reply-item p-3 border rounded mb-2 bg-white">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>${reply.name}</strong> <small class="text-muted">(${reply.role})</small>
                                        <div class="mt-1">${reply.reply}</div>
                                        <small class="text-muted">${new Date(reply.created_at).toLocaleString()}</small>
                                    </div>
                                    <button class="btn btn-link text-danger deleteReply" data-id="${reply.id}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    repliesHtml = '<p class="text-center text-muted py-3">No replies yet.</p>';
                }
                $('#replies-container').html(repliesHtml);
            });
        }

        // Submit Reply
        $('#reply-form').on('submit', function(e) {
            e.preventDefault();
            let testimonialId = $('#reply_testimonial_id').val();
            let btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Posting...');

            $.ajax({
                url: "{{ url('admin/testimonials') }}/" + testimonialId + "/reply",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#reply-form')[0].reset();
                    $('#reply-form input[name="name"]').val('Admin');
                    $('#reply-form input[name="role"]').val('Management');
                    loadReplies(testimonialId);
                    table.draw(false);
                    if (typeof showToast === 'function') showToast(response.success);
                },
                error: function() {
                    alert('Error posting reply');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Post Reply');
                }
            });
        });

        // Delete Reply
        $('body').on('click', '.deleteReply', function() {
            let id = $(this).data('id');
            let testimonialId = $('#reply_testimonial_id').val();
            if (confirm('Delete this reply?')) {
                $.ajax({
                    url: "{{ url('admin/testimonials/reply') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        loadReplies(testimonialId);
                        table.draw(false);
                        if (typeof showToast === 'function') showToast(response.success);
                    }
                });
            }
        });

        // Image Preview
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#imagePreview').css('background-image', 'url(' + e.target.result + ')');
                    $('#imagePreview').hide();
                    $('#imagePreview').fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imageUpload").change(function() {
            readURL(this);
        });

        // Open Modal
        window.openCreateModal = function() {
            $('#form-modal-title').text('Add Testimonial');
            $('#testimonial-form')[0].reset();
            $('#form-method').val('POST');
            $('#testimonial_id').val('');
            $('#imagePreview').css('background-image', "url('{{ asset('admiro/assets/images/user/user.png') }}')");
            $('#testimonial-form-modal').modal('show');
        }

        // Submit Form
        $('#testimonial-form').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            let url = "{{ route('admin.testimonials.store') }}";
            let method = $('#form-method').val();

            if (method === 'PUT') {
                url = "{{ url('admin/testimonials') }}/" + $('#testimonial_id').val();
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
                    $('#testimonial-form-modal').modal('hide');
                    table.draw();
                    if (typeof showToast === 'function') showToast(response.success);
                },
                error: function(xhr) {
                    let errors = xhr.responseJSON.errors;
                    if (errors) {
                        $.each(errors, function(key, value) {
                            alert(value[0]); // Simple alert for now
                        });
                    } else {
                        alert('Something went wrong');
                    }
                },
                complete: function() {
                    $('#submit-btn').prop('disabled', false).text('Save Testimonial');
                }
            });
        });

        // Delete
        $('body').on('click', '.deleteTestimonial', function() {
            let id = $(this).data('id');
            $('#delete-testimonial-id').val(id);
            $('#testimonial-delete-modal').modal('show');
        });

        $('#confirm-delete-btn').click(function() {
            let id = $('#delete-testimonial-id').val();
            let btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Deleting...');

            $.ajax({
                type: "DELETE",
                url: "{{ url('admin/testimonials') }}/" + id,
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    $('#testimonial-delete-modal').modal('hide');
                    table.draw();
                    if (typeof showToast === 'function') showToast(data.success);
                },
                error: function(data) {
                    alert('Error deleting testimonial');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Delete Now');
                }
            });
        });

        // Toggle Status
        $('body').on('click', '.toggle-status', function() {
            var id = $(this).data('id');
            var currentStatus = $(this).data('status');
            var newStatus = (currentStatus === 'approved') ? 'pending' : 'approved';
            var newStatusText = (currentStatus === 'approved') ? 'Pending' : 'Approved';

            $('#status-testimonial-id').val(id);
            $('#status-new-value').val(newStatus);
            $('#status-confirmation-text').text(`Are you sure you want to change the status of this testimonial to ${newStatusText}?`);
            $('#status-confirmation-modal').modal('show');
        });

        // Handle Confirm Status Change
        $('#confirm-status-btn').on('click', function() {
            var id = $('#status-testimonial-id').val();
            var newStatus = $('#status-new-value').val();
            var btn = $(this);

            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Updating...');

            $.ajax({
                url: "{{ url('admin/testimonials') }}/" + id + "/status",
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

        // View Testimonial
        $('body').on('click', '.viewTestimonial', function() {
            var id = $(this).data('id');
            $.get("{{ url('admin/testimonials') }}/" + id + "/edit", function(data) {
                var imageSrc = data.image ? "/storage/" + data.image : "{{ asset('admiro/assets/images/user/user.png') }}";
                var stars = '';
                for (var i = 1; i <= 5; i++) {
                    stars += '<i class="fa fa-star ' + (i <= data.rating ? 'text-warning' : 'text-muted') + '"></i>';
                }

                var html = `
                    <div class="text-center mb-4">
                        <img src="${imageSrc}" alt="Cient Image" class="img-fluid rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid #f8f9fa;">
                        <h4 class="mt-3 mb-1">${data.name}</h4>
                        <p class="text-primary fw-600 mb-1">${data.role || '<span class="text-muted">No Role</span>'}</p>
                        <div class="stars mb-3">${stars}</div>
                    </div>
                    <div class="p-4 bg-light rounded shadow-sm">
                        <label class="fw-bold text-muted small text-uppercase d-block mb-2">Message</label>
                        <div class="position-relative">
                            <i class="fa fa-quote-left text-muted opacity-25 position-absolute" style="top: -5px; left: -15px;"></i>
                            <p class="mb-0 fs-6 text-dark" style="white-space: pre-wrap; line-height: 1.6;">${data.message}</p>
                            <i class="fa fa-quote-right text-muted opacity-25 position-absolute" style="bottom: -5px; right: -5px;"></i>
                        </div>
                    </div>
                `;
                $('#view-modal-content').html(html);
                $('#testimonial-view-modal').modal('show');
            });
        });
    });
</script>
@endsection
