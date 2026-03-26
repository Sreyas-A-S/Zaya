@extends('layouts.admin')

@section('title', 'Gallery Page Settings')

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
    <div class="page-title">
        <div class="row">
            <div class="col-6">
                <h3>Gallery Page Settings</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active">Gallery Page Settings</li>
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
                    <h3>Manage Gallery Page Content</h3>
                    <p>Update images and text for the Gallery page sections.</p>
                </div>
                <div class="card-body">
                    <form id="gallerySettingsForm" action="{{ route('admin.gallery-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3">
                                <ul class="nav nav-pills flex-column h-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link active text-start mb-2" id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button" role="tab" aria-controls="v-pills-general" aria-selected="true">
                                        <i class="fa-solid fa-circle-info me-2"></i> General & CTA
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-sanctuary-tab" data-bs-toggle="pill" data-bs-target="#v-pills-sanctuary" type="button" role="tab" aria-controls="v-pills-sanctuary" aria-selected="false">
                                        <i class="fa-solid fa-hotel me-2"></i> The Sanctuary
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-movement-tab" data-bs-toggle="pill" data-bs-target="#v-pills-movement" type="button" role="tab" aria-controls="v-pills-movement" aria-selected="false">
                                        <i class="fa-solid fa-person-walking me-2"></i> Sacred Movement
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-rituals-tab" data-bs-toggle="pill" data-bs-target="#v-pills-rituals" type="button" role="tab" aria-controls="v-pills-rituals" aria-selected="false">
                                        <i class="fa-solid fa-leaf me-2"></i> Ayurvedic Rituals
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-retreats-tab" data-bs-toggle="pill" data-bs-target="#v-pills-retreats" type="button" role="tab" aria-controls="v-pills-retreats" aria-selected="false">
                                        <i class="fa-solid fa-users-rays me-2"></i> Community Retreats
                                    </button>
                                </ul>
                            </div>
                            <div class="col-md-9 border-start">
                                <div class="tab-content" id="v-pills-tabContent">
                                    @php
                                    $generalSettings = $settings->filter(fn($s) => !Str::contains($s->key, '_img_'));
                                    $sanctuarySettings = $settings->filter(fn($s) => Str::contains($s->key, 'sanctuary_img_'));
                                    $movementSettings = $settings->filter(fn($s) => Str::contains($s->key, 'movement_img_'));
                                    $ritualsSettings = $settings->filter(fn($s) => Str::contains($s->key, 'rituals_img_'));
                                    $retreatsSettings = $settings->filter(fn($s) => Str::contains($s->key, 'retreats_img_'));
                                    @endphp

                                    <!-- General Tab -->
                                    <div class="tab-pane fade show active p-3" id="v-pills-general" role="tabpanel" aria-labelledby="v-pills-general-tab">
                                        <div class="row g-4">
                                            @foreach($generalSettings as $setting)
                                            @include('admin.services-settings.partials.field', ['setting' => $setting])
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Sanctuary Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-sanctuary" role="tabpanel" aria-labelledby="v-pills-sanctuary-tab">
                                        <div class="row g-4">
                                            @foreach($sanctuarySettings as $setting)
                                            @include('admin.services-settings.partials.field', ['setting' => $setting, 'aspectRatio' => '1.33'])
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Movement Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-movement" role="tabpanel" aria-labelledby="v-pills-movement-tab">
                                        <div class="row g-4">
                                            @foreach($movementSettings as $setting)
                                            @include('admin.services-settings.partials.field', ['setting' => $setting, 'aspectRatio' => '0.75'])
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Rituals Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-rituals" role="tabpanel" aria-labelledby="v-pills-rituals-tab">
                                        <div class="row g-4">
                                            @foreach($ritualsSettings as $setting)
                                            @include('admin.services-settings.partials.field', ['setting' => $setting, 'aspectRatio' => '1.33'])
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Retreats Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-retreats" role="tabpanel" aria-labelledby="v-pills-retreats-tab">
                                        <div class="row g-4">
                                            @foreach($retreatsSettings as $setting)
                                            @include('admin.services-settings.partials.field', ['setting' => $setting, 'aspectRatio' => '1.33'])
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end mt-4">
                            <button type="submit" id="saveSettingsBtn" class="btn btn-primary px-5">Save All Settings</button>
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

        $('#gallerySettingsForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let btn = $('#saveSettingsBtn');
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
                        window.showToast(response.message, 'success');
                    } else {
                        window.showToast('Something went wrong.', 'error');
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        window.showToast(xhr.responseJSON.message, 'error');
                    } else {
                        window.showToast('An error occurred. Please try again.', 'error');
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).html('Save All Settings');
                }
            });
        });
    });
</script>
@endsection
