@extends('layouts.admin')

@section('title', 'Contact Page Settings')

@section('content')
<style>
    .nav-pills .nav-link {
        color: #555;
        border-radius: 8px;
        transition: all 0.3s ease;
        padding: 12px 20px;
        margin-bottom: 5px;
    }

    .nav-pills .nav-link.active {
        background-color: var(--theme-default) !important;
        color: #fff !important;
    }

    .nav-pills .nav-link:hover:not(.active) {
        background-color: var(--bs-gray-100);
    }

    .btn-primary {
        background-color: var(--theme-default) !important;
        border-color: var(--theme-default) !important;
    }

    .btn-primary:hover {
        opacity: 0.9;
        background-color: var(--theme-default) !important;
        border-color: var(--theme-default) !important;
    }

    .tab-content {
        border-left: 1px solid #eee;
        min-height: 400px;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border">
                    <h3>Manage Contact Page Content</h3>
                    <p>Update content for the Contact Us page, including the banner and general settings.</p>
                </div>
                <div class="card-body">
                    <form id="contactSettingsForm" action="{{ route('admin.contact-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3">
                                <ul class="nav nav-pills flex-column h-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link active text-start mb-2" id="v-pills-hero_banner-tab" data-bs-toggle="pill" data-bs-target="#v-pills-hero_banner" type="button" role="tab" aria-controls="v-pills-hero_banner" aria-selected="true">
                                        <i class="fa-solid fa-image me-2"></i> Hero Banner
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-contact_information-tab" data-bs-toggle="pill" data-bs-target="#v-pills-contact_information" type="button" role="tab" aria-controls="v-pills-contact_information" aria-selected="false">
                                        <i class="fa-solid fa-address-book me-2"></i> Contact Info
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-message_form-tab" data-bs-toggle="pill" data-bs-target="#v-pills-message_form" type="button" role="tab" aria-controls="v-pills-message_form" aria-selected="false">
                                        <i class="fa-solid fa-envelope me-2"></i> Message Form
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-support_section-tab" data-bs-toggle="pill" data-bs-target="#v-pills-support_section" type="button" role="tab" aria-controls="v-pills-support_section" aria-selected="false">
                                        <i class="fa-solid fa-user-tie me-2"></i> Support Desk
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-faqs-tab" data-bs-toggle="pill" data-bs-target="#v-pills-faqs" type="button" role="tab" aria-controls="v-pills-faqs" aria-selected="false">
                                        <i class="fa-solid fa-circle-question me-2"></i> FAQ Section
                                    </button>
                                </ul>
                            </div>
                            <div class="col-md-9 border-start">
                                <div class="tab-content" id="v-pills-tabContent">

                                    <!-- Hero Banner Tab -->
                                    <div class="tab-pane fade show active p-3" id="v-pills-hero_banner" role="tabpanel" aria-labelledby="v-pills-hero_banner-tab">
                                        <div class="row g-4">
                                           
                                            @foreach($settings['hero_banner'] as $setting)
                                                @include('admin.contact-us.partials.field', ['setting' => $setting])
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Contact Info Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-contact_information" role="tabpanel" aria-labelledby="v-pills-contact_information-tab">
                                        <div class="row g-4">
                                            @foreach($settings['contact_information'] as $setting)
                                                @include('admin.contact-us.partials.field', ['setting' => $setting])
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    <!-- Message Form Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-message_form" role="tabpanel" aria-labelledby="v-pills-message_form-tab">
                                        <div class="row g-4">
                                            @foreach($settings['message_form'] as $setting)
                                                @include('admin.contact-us.partials.field', ['setting' => $setting])
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Support Section Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-support_section" role="tabpanel" aria-labelledby="v-pills-support_section-tab">
                                        <div class="row g-4">
                                            @foreach($settings['support_section'] as $setting)
                                                @include('admin.contact-us.partials.field', ['setting' => $setting])
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- FAQs Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-faqs" role="tabpanel" aria-labelledby="v-pills-faqs-tab">
                                        <div class="row g-4 mb-5">
                                            @foreach($settings['faqs'] as $setting)
                                                @include('admin.contact-us.partials.field', ['setting' => $setting])
                                            @endforeach
                                        </div>

                                        <hr class="my-5">

                                        <div class="d-flex justify-content-between align-items-center mb-4">
                                            <h4>FAQ List</h4>
                                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFaqModal">
                                                <i class="fa-solid fa-plus me-2"></i> Add New FAQ
                                            </button>
                                        </div>

                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead class="bg-light">
                                                    <tr>
                                                        <th style="width: 50px;">#</th>
                                                        <th>Question</th>
                                                        <th>Answer</th>
                                                        <th style="width: 100px;">Status</th>
                                                        <th style="width: 120px;">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($faqs as $faq)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td class="text-wrap" style="max-width: 250px;">{{ $faq->question }}</td>
                                                            <td class="text-wrap" style="max-width: 350px;">{{ Str::limit($faq->answer, 100) }}</td>
                                                            <td>
                                                                <span class="badge {{ $faq->status ? 'bg-success' : 'bg-danger' }} faq-status-toggle" 
                                                                    data-id="{{ $faq->id }}" data-status="{{ $faq->status }}" style="cursor: pointer;">
                                                                    {{ $faq->status ? 'Active' : 'Inactive' }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex gap-2">
                                                                    <button type="button" class="text-primary border-0 bg-transparent edit-faq" 
                                                                        data-id="{{ $faq->id }}" 
                                                                        data-question="{{ $faq->question }}" 
                                                                        data-answer="{{ $faq->answer }}">
                                                                        <i class="fa-solid fa-pen-to-square"></i>
                                                                    </button>
                                                                    <button type="button" class="text-danger border-0 bg-transparent delete-faq" data-id="{{ $faq->id }}">
                                                                        <i class="fa-solid fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="text-center py-4">No FAQs found. Add your first FAQ above.</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end mt-4">
                            <button type="submit" id="saveBtn" class="btn btn-primary px-5">Save All Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add FAQ Modal -->
<div class="modal fade" id="addFaqModal" tabindex="-1" aria-labelledby="addFaqModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFaqModalLabel">Add New FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addFaqForm" action="{{ route('admin.contact-settings.faq-store') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Question</label>
                        <input type="text" name="question" class="form-control" placeholder="Enter FAQ question" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Answer</label>
                        <textarea name="answer" class="form-control" rows="4" placeholder="Enter FAQ answer" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="addFaqBtn">Save FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit FAQ Modal -->
<div class="modal fade" id="editFaqModal" tabindex="-1" aria-labelledby="editFaqModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFaqModalLabel">Edit FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editFaqForm">
                @csrf
                <input type="hidden" name="id" id="edit_faq_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Question</label>
                        <input type="text" name="question" id="edit_faq_question" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Answer</label>
                        <textarea name="answer" id="edit_faq_answer" class="form-control" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="updateFaqBtn">Update FAQ</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>

    $(document).ready(function() {
        // Handle hash navigation
        function activateTabFromHash() {
            let hash = window.location.hash;
            if (hash) {
                let tabBtn = $(`button[data-bs-target="${hash}"]`);
                if (tabBtn.length) {
                    tabBtn.trigger('click');
                }
            }
        }

        activateTabFromHash();
        $(window).on('hashchange', function() {
            activateTabFromHash();
        });

           $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



        $('#contactSettingsForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let btn = $('#saveBtn');
            let formData = new FormData(this);

            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        if (typeof showToast === 'function') {
                            showToast(response.message);
                        } else {
                            alert(response.message);
                        }
                        
                        // Reload to reflect changes (especially images)
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    } else {
                        alert('Something went wrong.');
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert(errorMsg);
                },
                complete: function() {
                    btn.prop('disabled', false).html('Save All Settings');
                }
            });
        });

        // Image Preview only (Upload happens on form submit)
        $('.image-ajax-input').on('change', function() {
            const input = this;
            const key = $(this).data('key');
            const file = input.files[0];

            if (file) {
                // Immediate Preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('.preview-' + key).attr('src', e.target.result).parent().removeClass('d-none');
                }
                reader.readAsDataURL(file);
            }
        });

        // Add FAQ
        $('#addFaqForm').on('submit', function(e) {
            e.preventDefault();
            let btn = $('#addFaqBtn');
            let formData = $(this).serialize();
            
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Saving...');
            
            $.post($(this).attr('action'), formData, function(response) {
                if (response.success) {
                    location.reload();
                }
            }).fail(function(xhr) {
                alert('Failed to add FAQ.');
                btn.prop('disabled', false).text('Save FAQ');
            });
        });

        // Edit FAQ
        $('.edit-faq').on('click', function() {
            $('#edit_faq_id').val($(this).data('id'));
            $('#edit_faq_question').val($(this).data('question'));
            $('#edit_faq_answer').val($(this).data('answer'));
            $('#editFaqModal').modal('show');
        });

        $('#editFaqForm').on('submit', function(e) {
            e.preventDefault();
            let id = $('#edit_faq_id').val();
            let btn = $('#updateFaqBtn');
            let formData = $(this).serialize();
            
            btn.prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Updating...');
            
            $.post("{{ url('admin/contact-settings/faqs') }}/" + id, formData, function(response) {
                if (response.success) {
                    location.reload();
                }
            }).fail(function(xhr) {
                alert('Failed to update FAQ.');
                btn.prop('disabled', false).text('Update FAQ');
            });
        });

        // Delete FAQ
        $('.delete-faq').on('click', function() {
            if (!confirm('Are you sure you want to delete this FAQ?')) return;
            let id = $(this).data('id');
            $.ajax({
                url: "{{ url('admin/contact-settings/faqs') }}/" + id,
                type: 'DELETE',
                success: function(response) {
                    if (response.success) location.reload();
                }
            });
        });

        // Toggle FAQ Status
        $('.faq-status-toggle').on('click', function() {
            let id = $(this).data('id');
            let currentStatus = $(this).data('status');
            let newStatus = currentStatus ? 0 : 1;
            
            $.post("{{ url('admin/contact-settings/faqs') }}/" + id + "/status", {status: newStatus}, function(response) {
                if (response.success) location.reload();
            });
        });
    });


</script>
@endsection