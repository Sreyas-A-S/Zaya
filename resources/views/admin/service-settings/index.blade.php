@extends('layouts.admin')

@section('title', 'Service Detail Settings')

@section('content')
<style>
    .btn-primary {
        background-color: var(--theme-default) !important;
        border-color: var(--theme-default) !important;
    }

    .btn-primary:hover {
        opacity: 0.9;
        background-color: var(--theme-default) !important;
        border-color: var(--theme-default) !important;
    }
</style>

<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Individual Service Page Settings</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i data-feather="home"></i>
                        </a>
                    </li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active">Service Detail Settings</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border">
                    <h3>Manage Individual Service Page Content</h3>
                    <p>Update global elements like the sidebar title and the call-to-action section found on individual service pages.</p>
                </div>

                <div class="card-body">
                    <form id="serviceSettingsForm"
                          action="{{ route('admin.service-settings.update') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="row g-4">
                            @foreach($settings as $setting)
                                @include('admin.service-settings.partials.field', ['setting' => $setting])
                            @endforeach
                            
                            @if($settings->isEmpty())
                                <div class="col-12 text-center py-5">
                                    <p class="text-muted">No settings found for the current language. Please ensure the seeder has been run.</p>
                                </div>
                            @endif
                        </div>

                        <div class="card-footer text-end mt-4">
                            <button type="submit"
                                    id="saveServiceSettingsBtn"
                                    class="btn btn-primary px-5">
                                Save Detail Settings
                            </button>
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

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#serviceSettingsForm').on('submit', function(e) {
        e.preventDefault();

        let form = $(this);
        let btn = $('#saveServiceSettingsBtn');
        let formData = new FormData(this);

        btn.prop('disabled', true)
           .html('<i class="fa-solid fa-spinner fa-spin me-2"></i> Saving...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    window.showToast(response.message, 'success');
                } else {
                    window.showToast('Something went wrong.', 'error');
                }
            },
            error: function(xhr) {
                window.showToast(
                    xhr.responseJSON?.message ?? 
                    'An error occurred. Please try again.',
                    'error'
                );
            },
            complete: function() {
                btn.prop('disabled', false)
                   .html('Save Detail Settings');
            }
        });
    });

    // Image Preview
    $('.image-ajax-input').on('change', function() {
        const key = $(this).data('key');
        const file = this.files[0];

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('.preview-' + key)
                    .attr('src', e.target.result)
                    .parent()
                    .removeClass('d-none');
            }
            reader.readAsDataURL(file);
        }
    });

});
</script>
@endsection