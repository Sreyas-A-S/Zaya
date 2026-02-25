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
                                        <i class="fa-solid fa-circle-question me-2"></i> FAQs Title
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
                                        <div class="row g-4">
                                            @foreach($settings['faqs'] as $setting)
                                                @include('admin.contact-us.partials.field', ['setting' => $setting])
                                            @endforeach
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
    });


</script>
@endsection