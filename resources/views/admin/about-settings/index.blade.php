@extends('layouts.admin')

@section('title', 'About Us Settings')

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
                <h3>About Us Settings</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active">About Us Settings</li>
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
                    <h3>Manage About Us Content</h3>
                    <p>Update content for the About Us page, including the banner and team sections.</p>
                </div>
                <div class="card-body">
                    <form id="aboutSettingsForm" action="{{ route('admin.about-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3">
                                <ul class="nav nav-pills flex-column h-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link active text-start mb-2" id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button" role="tab" aria-controls="v-pills-general" aria-selected="true">
                                        <i class="fa-solid fa-circle-info me-2"></i> General
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-banner-tab" data-bs-toggle="pill" data-bs-target="#v-pills-banner" type="button" role="tab" aria-controls="v-pills-banner" aria-selected="false">
                                        <i class="fa-solid fa-image me-2"></i> Banner
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-team-tab" data-bs-toggle="pill" data-bs-target="#v-pills-team" type="button" role="tab" aria-controls="v-pills-team" aria-selected="false">
                                        <i class="fa-solid fa-users me-2"></i> Team
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-core-values-tab" data-bs-toggle="pill" data-bs-target="#v-pills-core-values" type="button" role="tab" aria-controls="v-pills-core-values" aria-selected="false">
                                        <i class="fa-solid fa-star me-2"></i> Core Values
                                    </button>
                                </ul>
                            </div>
                            <div class="col-md-9 border-start">
                                <div class="tab-content" id="v-pills-tabContent">
                                    @php
                                    $bannerSettings = $settings->filter(fn($s) => Str::contains($s->key, 'banner'));
                                    $teamSettings = $settings->filter(fn($s) => Str::contains($s->key, 'team'));
                                    $coreValuesSettings = $settings->filter(fn($s) => Str::contains($s->key, 'core_value'));
                                    $generalSettings = $settings->filter(fn($s) => !Str::contains($s->key, 'banner') && !Str::contains($s->key, 'team') && !Str::contains($s->key, 'core_value'));
                                    @endphp

                                    <!-- General Tab -->
                                    <div class="tab-pane fade show active p-3" id="v-pills-general" role="tabpanel" aria-labelledby="v-pills-general-tab">
                                        <div class="row g-4">
                                            @foreach($generalSettings as $setting)
                                            <div class="col-12">
                                                <label class="form-label fw-bold">{{ str_replace('_', ' ', ucfirst($setting->key)) }}</label>
                                                @include('admin.about-settings.partials.input-field', ['setting' => $setting])
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Banner Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-banner" role="tabpanel" aria-labelledby="v-pills-banner-tab">
                                        <div class="row g-4">
                                            @foreach($bannerSettings as $setting)
                                            <div class="col-12">
                                                <label class="form-label fw-bold">{{ str_replace('_', ' ', ucfirst($setting->key)) }}</label>
                                                @include('admin.about-settings.partials.input-field', ['setting' => $setting])
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Team Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-team" role="tabpanel" aria-labelledby="v-pills-team-tab">
                                        <div class="row g-4">
                                            @foreach($teamSettings as $setting)
                                            <div class="col-12">
                                                <label class="form-label fw-bold">{{ str_replace('_', ' ', ucfirst($setting->key)) }}</label>
                                                @include('admin.about-settings.partials.input-field', ['setting' => $setting])
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Core Values Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-core-values" role="tabpanel" aria-labelledby="v-pills-core-values-tab">
                                        <div class="row g-4">
                                            @foreach($coreValuesSettings as $setting)
                                            <div class="col-12">
                                                <label class="form-label fw-bold">{{ str_replace('_', ' ', ucfirst($setting->key)) }}</label>
                                                @include('admin.about-settings.partials.input-field', ['setting' => $setting])
                                            </div>
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

        $('#aboutSettingsForm').on('submit', function(e) {
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