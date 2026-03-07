@extends('layouts.admin')

@section('title', 'Testimonial Details')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Testimonial Details</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.testimonials.index') }}">Testimonials</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Testimonial Summary -->
        <div class="col-sm-12">
            <div class="card testimonial-header-card border-0 shadow-sm overflow-hidden">
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-4 bg-primary-light d-flex align-items-center justify-content-center p-4 py-5 text-center border-end">
                            <div>
                                <div class="position-relative d-inline-block mb-3">
                                    <img src="{{ $testimonial->image ? asset('storage/' . $testimonial->image) : asset('admiro/assets/images/user/user.png') }}" 
                                         alt="Client" class="img-fluid rounded-circle shadow border-white border-4" style="width: 130px; height: 120px; object-fit: cover;">
                                    <span class="position-absolute bottom-0 end-0 bg-success border border-white border-2 rounded-circle" style="width: 20px; height: 20px;" title="Active Status"></span>
                                </div>
                                <h4 class="mb-1 text-dark f-w-700">{{ $testimonial->name }}</h4>
                                <span class="badge bg-primary rounded-pill px-3">{{ $testimonial->role ?? 'Client' }}</span>
                                <div class="mt-3 fs-5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fa fa-star {{ $i <= $testimonial->rating ? 'text-warning' : 'text-muted opacity-50' }}"></i>
                                    @endfor
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 p-4 p-lg-5 d-flex flex-column justify-content-center">
                            <div class="testimonial-content-wrapper">
                                <div class="quote-icon mb-3">
                                    <i class="fa fa-quote-left text-primary opacity-25 fs-1"></i>
                                </div>
                                
                                @php $isLong = strlen($testimonial->message) > 250; @endphp
                                
                                <div id="message-container" class="position-relative">
                                    <p class="testimonial-text mb-0 fs-6 text-dark lh-base {{ $isLong ? 'truncated' : '' }}" id="testimonial-message" style="font-style: italic;">
                                        {{ $testimonial->message }}
                                    </p>
                                    
                                    @if($isLong)
                                        <button class="btn btn-link btn-sm p-0 mt-2 f-w-600 text-primary text-decoration-none" id="read-more-btn">
                                            Read More <i class="fa fa-chevron-down ms-1 small"></i>
                                        </button>
                                    @endif
                                </div>
                                
                                <div class="mt-4 pt-4 border-top d-flex align-items-center gap-4">
                                    <div class="stats-item">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="icon-box bg-info-light text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                <i class="fa fa-thumbs-up"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $testimonial->likes_count }}</h6>
                                                <small class="text-muted">Total Likes</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="stats-item">
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="icon-box bg-secondary-light text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                                <i class="fa fa-comment"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $testimonial->replies_count }}</h6>
                                                <small class="text-muted">Total Replies</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="stats-item ms-auto">
                                        <small class="text-muted">Submitted on: {{ $testimonial->created_at->format('M d, Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .testimonial-text.truncated {
                display: -webkit-box;
                -webkit-line-clamp: 3;
                -webkit-box-orient: vertical;
                overflow: hidden;
            }
            .bg-primary-light { background-color: rgba(151, 86, 61, 0.05); }
            .bg-info-light { background-color: rgba(30, 166, 236, 0.1); }
            .bg-secondary-light { background-color: rgba(108, 117, 125, 0.1); }
        </style>

        <!-- Tabular Views for Likes and Replies -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border">
                    <ul class="nav nav-tabs border-tab" id="testimonialTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="replies-tab" data-bs-toggle="tab" href="#replies-content" role="tab">
                                <i class="fa fa-reply me-2"></i>Replies (<span id="replies-count-badge">{{ $testimonial->replies_count }}</span>)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="likes-tab" data-bs-toggle="tab" href="#likes-content" role="tab">
                                <i class="fa fa-thumbs-up me-2"></i>Likes (<span id="likes-count-badge">{{ $testimonial->likes_count }}</span>)
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="testimonialTabContent">
                        <!-- Replies Tab -->
                        <div class="tab-pane fade show active" id="replies-content" role="tabpanel">
                            <div class="mb-4 text-end">
                                <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#addReplyForm">
                                    <i class="fa fa-plus me-2"></i>Add Official Reply
                                </button>
                                <div class="collapse mt-3 text-start" id="addReplyForm">
                                    <form id="reply-form-show" class="p-3 border rounded bg-light">
                                        @csrf
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Name</label>
                                                <input type="text" class="form-control form-control-sm" name="name" value="Admin">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">Role</label>
                                                <input type="text" class="form-control form-control-sm" name="role" value="Management">
                                            </div>
                                            <div class="col-md-12">
                                                <textarea class="form-control" name="reply" rows="3" required placeholder="Write your response..."></textarea>
                                            </div>
                                            <div class="col-md-12 text-end">
                                                <button type="submit" class="btn btn-primary btn-sm">Post Reply</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="display" id="replies-table">
                                    <thead>
                                        <tr>
                                            <th>Respondent</th>
                                            <th>Role</th>
                                            <th>Message</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Likes Tab -->
                        <div class="tab-pane fade" id="likes-content" role="tabpanel">
                            <div class="table-responsive">
                                <table class="display" id="likes-table">
                                    <thead>
                                        <tr>
                                            <th>IP Address</th>
                                            <th>User Agent</th>
                                            <th>Date</th>
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
    </div>
</div>

<!-- Reply View Modal -->
<div class="modal fade" id="reply-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reply Details</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="reply-view-content">
                <!-- Content will be loaded via JS -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Like View Modal -->
<div class="modal fade" id="like-view-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Like Details</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="like-view-content">
                <!-- Content will be loaded via JS -->
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize Replies DataTable
        var repliesTable = $('#replies-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.testimonials.replies', $testimonial->id) }}",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'role', name: 'role' },
                { data: 'reply', name: 'reply' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[3, 'desc']]
        });

        // Initialize Likes DataTable
        var likesTable = $('#likes-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.testimonials.likes', $testimonial->id) }}",
            columns: [
                { data: 'ip_address', name: 'ip_address' },
                { data: 'user_agent', name: 'user_agent' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[2, 'desc']]
        });

        // Refresh tables on tab switch to fix column alignment issues
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
        });

        // Submit Reply
        $('#reply-form-show').on('submit', function(e) {
            e.preventDefault();
            let btn = $(this).find('button[type="submit"]');
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Posting...');

            $.ajax({
                url: "{{ route('admin.testimonials.reply.store', $testimonial->id) }}",
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    $('#reply-form-show')[0].reset();
                    $('#addReplyForm').collapse('hide');
                    repliesTable.draw();
                    updateCounts();
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
            if (confirm('Delete this reply?')) {
                $.ajax({
                    url: "{{ url('admin/testimonials/reply') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        repliesTable.draw(false);
                        updateCounts();
                        if (typeof showToast === 'function') showToast(response.success);
                    }
                });
            }
        });

        // Delete Like
        $('body').on('click', '.deleteLike', function() {
            let id = $(this).data('id');
            if (confirm('Are you sure you want to remove this like?')) {
                $.ajax({
                    url: "{{ url('admin/testimonials/like') }}/" + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        likesTable.draw(false);
                        updateCounts();
                        if (typeof showToast === 'function') showToast(response.success);
                    }
                });
            }
        });

        // View Reply Detail
        $('body').on('click', '.viewReply', function() {
            let id = $(this).data('id');
            // Get data from DataTable row
            let data = repliesTable.row($(this).closest('tr')).data();
            
            let html = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="fw-bold text-muted small text-uppercase d-block mb-1">Respondent</label>
                        <p class="mb-0 text-dark">${data.name}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <label class="fw-bold text-muted small text-uppercase d-block mb-1">Role</label>
                        <p class="mb-0 text-dark">${data.role || 'N/A'}</p>
                    </div>
                    <div class="col-md-12">
                        <label class="fw-bold text-muted small text-uppercase d-block mb-1">Date</label>
                        <p class="mb-0 text-dark">${data.created_at}</p>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="p-3 bg-light rounded border">
                            <label class="fw-bold text-primary small text-uppercase d-block mb-2">Reply Message</label>
                            <p class="mb-0 text-dark" style="white-space: pre-wrap;">${data.reply}</p>
                        </div>
                    </div>
                </div>
            `;
            $('#reply-view-content').html(html);
            $('#reply-view-modal').modal('show');
        });

        // View Like Detail
        $('body').on('click', '.viewLike', function() {
            let id = $(this).data('id');
            // Get data from DataTable row
            let data = likesTable.row($(this).closest('tr')).data();
            
            let html = `
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="fw-bold text-muted small text-uppercase d-block mb-1">IP Address</label>
                        <p class="mb-0 text-dark font-monospace">${data.ip_address}</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <label class="fw-bold text-muted small text-uppercase d-block mb-1">Date</label>
                        <p class="mb-0 text-dark">${data.created_at}</p>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="p-3 bg-light rounded border">
                            <label class="fw-bold text-info small text-uppercase d-block mb-2">User Agent (Browser Info)</label>
                            <p class="mb-0 text-dark small font-monospace" style="word-break: break-all;">${data.user_agent}</p>
                        </div>
                    </div>
                </div>
            `;
            $('#like-view-content').html(html);
            $('#like-view-modal').modal('show');
        });

        function updateCounts() {
            // Logic could be added here to fetch and update the badge numbers if needed
            // For now, we'll rely on page refresh or simple count logic
        }

        // Read More Toggle
        $('#read-more-btn').on('click', function() {
            const container = $('#testimonial-message');
            const btn = $(this);
            
            if (container.hasClass('truncated')) {
                container.removeClass('truncated');
                btn.html('Read Less <i class="fa fa-chevron-up ms-1 small"></i>');
            } else {
                container.addClass('truncated');
                btn.html('Read More <i class="fa fa-chevron-down ms-1 small"></i>');
            }
        });
    });
</script>
@endsection
