@extends('layouts.admin')

@section('title', 'Forms')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Forms</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Users</li>
                    <li class="breadcrumb-item active">Forms</li>
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
                    <h3>Forms List</h3>
                    <button type="button" class="btn btn-primary" onclick="openGenerateLinkModal()">
                        <i class="fa-solid fa-plus me-2"></i>Create
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="forms-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>URL</th>
                                    <th>User</th>
                                    <th>Expiry Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
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

<!-- Generate Registration Link Modal -->
<div class="modal fade" id="generate-link-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Registration Link</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">User Type</label>
                        <select class="form-select" id="reg_user_type">
                            <option value="" selected disabled>Select user type</option>
                            <option value="doctor">Doctors</option>
                            <option value="mindfulness-practitioner">Mindfulness Counsellors</option>
                            <option value="translator">Translators</option>
                            <option value="yoga-therapist">Yoga Therapists</option>
                        </select>
                        <div class="form-text">Secure, time-limited link.</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Reg Fee Currency</label>
                        <select class="form-select" id="reg_currency">
                            <option value="" selected disabled>Select currency</option>
                            @foreach($currencies as $code => $symbol)
                                <option value="{{ $code }}">{{ $code }} ({{ $symbol }})</option>
                            @endforeach
                        </select>
                        <div class="form-text">Currency for reg fee.</div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Expiry Date</label>
                        <input type="date" class="form-control" id="reg_expires_at" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                        <div class="form-text">When will this expire?</div>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="button" class="btn btn-success w-100" id="btn-generate-link" style="margin-bottom: 21px;">
                            <i class="fa-solid fa-link me-2"></i>Generate
                        </button>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Generated Link</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="generated_link" readonly placeholder="Click Generate to create a link">
                            <button class="btn btn-outline-secondary" id="btn-copy-link" type="button" disabled>Copy</button>
                        </div>
                        <div class="small text-muted mt-1" id="generated_link_hint"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline-dark" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="link-delete-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Link</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this link? This action cannot be undone.</p>
                <input type="hidden" id="delete-link-id">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-danger" type="button" id="confirm-delete-btn">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Share Link Modal -->
<div class="modal fade" id="share-link-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title text-white"><i class="iconly-Upload icli me-2"></i>Share Registration Link</h5>
                <button class="btn-close btn-close-white" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <h5 class="text-muted">Registration Link</h5>
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <h4 id="share-link-display" class="fw-bold text-primary mb-0" style="word-break: break-all;"></h4>
                        <input type="hidden" id="share_generated_link">
                        <button class="btn btn-sm btn-outline-primary" id="btn-copy-share-link" title="Copy to Clipboard">
                            <i class="fa-solid fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <a id="share-whatsapp" href="#" target="_blank" class="btn btn-light w-100 py-3 text-center border">
                            <i class="fa-brands fa-whatsapp text-success fs-4 d-block mb-1"></i>
                            <span>WhatsApp</span>
                        </a>
                    </div>
                    <div class="col-6">
                        <a id="share-email-mailto" href="#" class="btn btn-light w-100 py-3 text-center border">
                            <i class="fa-solid fa-envelope text-danger fs-4 d-block mb-1"></i>
                            <span>Email</span>
                        </a>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Or Send Direct Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" class="form-control" id="share_email_input" placeholder="Enter recipient email">
                        <button class="btn btn-primary" type="button" id="btn-send-share-email">
                            <i class="fa-solid fa-paper-plane me-1"></i>Send
                        </button>
                    </div>
                    <div class="small text-muted mt-1" id="share_hint"></div>
                </div>
            </div>
            <div class="modal-footer border-top-0">
                <button class="btn btn-outline-dark" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Status Confirmation Modal -->
<div class="modal fade" id="status-confirmation-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Status Change</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <i class="iconly-Info-Square icli text-primary mb-3" style="font-size: 50px;"></i>
                <h5>Update Link Status</h5>
                <p>Select the new status for this link:</p>
                <div class="mb-3 px-5">
                    <select id="status-select-input-link" class="form-select">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <input type="hidden" id="status-link-id">
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="button" id="confirm-status-btn">Confirm Change</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openGenerateLinkModal() {
        $('#reg_user_type').val('');
        $('#reg_expires_at').val("{{ date('Y-m-d', strtotime('+7 days')) }}");
        $('#generated_link').val('');
        $('#generated_link_hint').text('');
        $('#btn-copy-link').prop('disabled', true);
        new bootstrap.Modal(document.getElementById('generate-link-modal')).show();
    }

    $(document).ready(function() {
        const formsTable = $('#forms-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.forms.index') }}",
            },
            columns: [{
                    data: 'id',
                    name: 'open_register_links.id'
                },
                {
                    data: 'url',
                    name: 'url',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        if (!data) return '—';
                        return '<a href="' + data + '" target="_blank" rel="noopener">Open</a>';
                    }
                },
                {
                    data: 'user',
                    name: 'open_register_links.role',
                    orderable: false
                },
                {
                    data: 'expires_at',
                    name: 'open_register_links.expires_at',
                    render: function(data) {
                        if (!data) return 'Never';
                        return new Date(data).toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: 'short',
                            year: 'numeric'
                        });
                    }
                },
                {
                    data: 'status',
                    name: 'open_register_links.status',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ]
        });

        // Status modal open
        $(document).on('click', '.status-badge', function() {
            const id = $(this).data('id');
            const currentStatus = String($(this).data('status') || 'active').toLowerCase();
            $('#status-link-id').val(id);
            $('#status-select-input-link').val(currentStatus === 'inactive' ? 'inactive' : 'active');
            $('#status-confirmation-modal').modal('show');
        });

        // Confirm status change
        $(document).on('click', '#confirm-status-btn', function() {
            const btn = $(this);
            const id = $('#status-link-id').val();
            const status = $('#status-select-input-link').val();

            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                url: "{{ url('admin/forms') }}/" + id + "/status",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    status: status
                },
                success: function(res) {
                    $('#status-confirmation-modal').modal('hide');
                    formsTable.ajax.reload(null, false);
                    if (window.showToast) window.showToast(res.message || 'Status updated successfully', 'success');
                },
                error: function(xhr) {
                    const msg = (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error)) ? (xhr.responseJSON.message || xhr.responseJSON.error) : 'Failed to update status';
                    if (window.showToast) window.showToast(msg, 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Confirm Change');
                }
            });
        });

        // Delete modal open
        $(document).on('click', '.deleteLink', function() {
            $('#delete-link-id').val($(this).data('id'));
            $('#link-delete-modal').modal('show');
        });

        // Confirm delete
        $(document).on('click', '#confirm-delete-btn', function() {
            const btn = $(this);
            const id = $('#delete-link-id').val();

            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
            $.ajax({
                url: "{{ url('admin/forms') }}/" + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(res) {
                    $('#link-delete-modal').modal('hide');
                    formsTable.ajax.reload(null, false);
                    if (window.showToast) window.showToast(res.success || 'Deleted successfully', 'success');
                },
                error: function(xhr) {
                    const msg = (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error)) ? (xhr.responseJSON.message || xhr.responseJSON.error) : 'Failed to delete';
                    if (window.showToast) window.showToast(msg, 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).text('Delete');
                }
            });
        });

        // Clear validation on change
        $('#reg_user_type, #reg_currency').on('change', function() {
            if ($(this).val()) {
                $(this).removeClass('is-invalid');
                if (!$('#reg_user_type').val() && !$('#reg_currency').val()) {
                    $('#generated_link_hint').removeClass('text-danger').text('');
                }
            }
        });

        $('#btn-generate-link').on('click', function() {
            const userType = ($('#reg_user_type').val() || '').trim();
            const currency = ($('#reg_currency').val() || '').trim();
            
            let hasError = false;
            if (!userType) {
                $('#reg_user_type').addClass('is-invalid');
                hasError = true;
            }
            if (!currency) {
                $('#reg_currency').addClass('is-invalid');
                hasError = true;
            }

            if (hasError) {
                $('#generated_link_hint').text('Please select both User Type and Currency.').addClass('text-danger');
                if (window.showToast) window.showToast('Please select User Type and Currency.', 'error');
                return;
            }

            $('#reg_user_type, #reg_currency').removeClass('is-invalid');
            $('#generated_link_hint').removeClass('text-danger').text('Generating...');
            $('#btn-generate-link').prop('disabled', true);
            $('#btn-copy-link').prop('disabled', true);

            $.ajax({
                url: "{{ route('admin.forms.generate-link') }}",
                method: 'POST',
                data: {
                    user_type: userType,
                    currency: currency,
                    expires_at: $('#reg_expires_at').val()
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(resp) {
                    const link = resp && resp.link ? resp.link : '';
                    $('#generated_link').val(link);
                    const msg = (resp && resp.success) ? resp.success : (link ? 'Link generated successfully.' : 'No link generated.');
                    $('#generated_link_hint').text(msg);
                    if (window.showToast && link) window.showToast(msg, 'success');
                    $('#btn-copy-link').prop('disabled', !link);
                    if (link) formsTable.ajax.reload(null, false);
                },
                error: function(xhr) {
                    const msg = (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error)) ? (xhr.responseJSON.message || xhr.responseJSON.error) : 'Failed to generate link.';
                    $('#generated_link_hint').text(msg);
                    if (window.showToast) window.showToast(msg, 'error');
                },
                complete: function() {
                    $('#btn-generate-link').prop('disabled', false);
                }
            });
        });

        $('#btn-copy-link').on('click', async function() {
            const link = ($('#generated_link').val() || '').trim();
            if (!link) return;
            try {
                await navigator.clipboard.writeText(link);
                $('#generated_link_hint').text('Copied to clipboard.');
            } catch (e) {
                $('#generated_link_hint').text('Copy failed. Select the link and copy manually.');
            }
        });

        // Share buttons in table logic
        $(document).on('click', '.shareLink', function() {
            const link = $(this).data('url');
            $('#share_generated_link').val(link);
            $('#share-link-display').text(link);
            $('#share_email_input').val('');
            $('#share_hint').text('').removeClass('text-danger text-success');
            
            var shareMessage = "Registration Link: " + link + "\nPlease use this link to register on Zaya Wellness.";
            var whatsappUrl = "https://wa.me/?text=" + encodeURIComponent(shareMessage);
            var emailUrl = "mailto:?subject=Zaya Wellness Registration Link&body=" + encodeURIComponent(shareMessage);

            $('#share-whatsapp').attr('href', whatsappUrl);
            $('#share-email-mailto').attr('href', emailUrl);
            
            $('#share-link-modal').modal('show');
        });

        $(document).on('click', '#share-email-mailto', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            if (url && url !== '#') {
                window.location.href = url;
            }
        });

        $('#btn-copy-share-link').on('click', async function() {
            const link = $('#share_generated_link').val();
            try {
                await navigator.clipboard.writeText(link);
                if (window.showToast) window.showToast('Link copied to clipboard', 'success');
            } catch (e) {
                // Fallback for older browsers
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(link).select();
                document.execCommand("copy");
                $temp.remove();
                if (window.showToast) window.showToast('Link copied to clipboard', 'success');
            }
        });

        $('#btn-send-share-email').on('click', function() {
            const email = $('#share_email_input').val().trim();
            const link = $('#share_generated_link').val().trim();
            
            if (!email) {
                $('#share_hint').text('Please enter an email address.').addClass('text-danger');
                return;
            }
            
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Sending...');
            $('#share_hint').text('').removeClass('text-danger text-success');

            $.ajax({
                url: "{{ route('admin.forms.share-email') }}",
                method: 'POST',
                data: {
                    email: email,
                    link: link
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(resp) {
                    if (window.showToast) window.showToast('Email sent successfully!', 'success');
                    $('#share_email_input').val('');
                    $('#share_hint').text('Email sent successfully!').addClass('text-success');
                },
                error: function(xhr) {
                    const msg = (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error)) ? (xhr.responseJSON.message || xhr.responseJSON.error) : 'Failed to send email.';
                    $('#share_hint').text(msg).addClass('text-danger');
                    if (window.showToast) window.showToast(msg, 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).html('<i class="fa-solid fa-paper-plane me-1"></i>Send');
                }
            });
        });
    });
</script>
@endsection
