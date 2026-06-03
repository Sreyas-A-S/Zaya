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
                            <div class="col-md-12">
                                <div class="tab-content" id="v-pills-tabContent">
                                    @php
                                    $bannerSettings = $settings->filter(fn($s) => Str::contains($s->key, 'banner'));
                                    $teamSettings = $settings->filter(fn($s) => Str::contains($s->key, 'team'));
                                    $generalSettings = $settings->filter(fn($s) => !Str::contains($s->key, 'banner') && !Str::contains($s->key, 'team'));
                                    @endphp

                                    <!-- General Tab -->
                                    <div class="tab-pane fade show active p-3" id="v-pills-general" role="tabpanel">
                                        <div class="row g-4">
                                            @foreach($generalSettings as $setting)
                                            <div class="col-12">
                                                <label class="form-label fw-bold">{{ str_replace('_', ' ', ucfirst($setting->key)) }}</label>

                                                @if($setting->type === 'text')
                                                <input type="text" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control" placeholder="Enter content..." {{ $setting->max_length ? 'maxlength='.$setting->max_length : '' }}>
                                                @if($setting->max_length)
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: {{ $setting->max_length }}</div>
                                                @endif

                                                @elseif($setting->type === 'textarea')
                                                <textarea id="{{ $setting->key }}" name="{{ $setting->key }}" class="form-control" rows="4" placeholder="Enter long text..." {{ $setting->max_length ? 'maxlength='.$setting->max_length : '' }}>{{ $setting->value }}</textarea>
                                                @if($setting->max_length)
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: {{ $setting->max_length }}</div>
                                                @endif

                                                @elseif($setting->type === 'image')
                                                <div class="d-flex align-items-center gap-3">
                                                    @if($setting->value)
                                                    <div class="mb-2">
                                                        <img src="{{ Str::startsWith($setting->value, 'frontend/') ? asset($setting->value) : asset('storage/' . $setting->value) }}" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                                                    </div>
                                                    @endif
                                                    <div class="flex-grow-1">
                                                        <input type="file" name="{{ $setting->key }}" class="form-control">
                                                        <small class="text-muted">Current: {{ $setting->value }}</small>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Banner Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-banner" role="tabpanel">
                                        <div class="row g-4">
                                            @foreach($bannerSettings as $setting)
                                            <div class="col-12">
                                                <label class="form-label fw-bold">{{ str_replace('_', ' ', ucfirst($setting->key)) }}</label>

                                                @if($setting->type === 'text')
                                                <input type="text" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control" placeholder="Enter content..." {{ $setting->max_length ? 'maxlength='.$setting->max_length : '' }}>
                                                @if($setting->max_length)
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: {{ $setting->max_length }}</div>
                                                @endif

                                                @elseif($setting->type === 'textarea')
                                                <textarea id="{{ $setting->key }}" name="{{ $setting->key }}" class="form-control" rows="4" placeholder="Enter long text..." {{ $setting->max_length ? 'maxlength='.$setting->max_length : '' }}>{{ $setting->value }}</textarea>
                                                @if($setting->max_length)
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: {{ $setting->max_length }}</div>
                                                @endif

                                                @elseif($setting->type === 'image')
                                                <div class="d-flex align-items-center gap-3">
                                                    @if($setting->value)
                                                    <div class="mb-2">
                                                        <img src="{{ Str::startsWith($setting->value, 'frontend/') ? asset($setting->value) : asset('storage/' . $setting->value) }}" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                                                    </div>
                                                    @endif
                                                    <div class="flex-grow-1">
                                                        <input type="file" name="{{ $setting->key }}" class="form-control">
                                                        <small class="text-muted">Current: {{ $setting->value }}</small>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Team Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-team" role="tabpanel">
                                        <div class="row g-4">
                                            @foreach($teamSettings as $setting)
                                            <div class="col-12">
                                                <label class="form-label fw-bold">{{ str_replace('_', ' ', ucfirst($setting->key)) }}</label>

                                                @if($setting->type === 'text')
                                                <input type="text" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control" placeholder="Enter content..." {{ $setting->max_length ? 'maxlength='.$setting->max_length : '' }}>
                                                @if($setting->max_length)
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: {{ $setting->max_length }}</div>
                                                @endif

                                                @elseif($setting->type === 'textarea')
                                                <textarea id="{{ $setting->key }}" name="{{ $setting->key }}" class="form-control" rows="4" placeholder="Enter long text..." {{ $setting->max_length ? 'maxlength='.$setting->max_length : '' }}>{{ $setting->value }}</textarea>
                                                @if($setting->max_length)
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: {{ $setting->max_length }}</div>
                                                @endif

                                                @elseif($setting->type === 'image')
                                                <div class="d-flex align-items-center gap-3">
                                                    @if($setting->value)
                                                    <div class="mb-2">
                                                        <img src="{{ Str::startsWith($setting->value, 'frontend/') ? asset($setting->value) : asset('storage/' . $setting->value) }}" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
                                                    </div>
                                                    @endif
                                                    <div class="flex-grow-1">
                                                        <input type="file" name="{{ $setting->key }}" class="form-control">
                                                        <small class="text-muted">Current: {{ $setting->value }}</small>
                                                    </div>
                                                </div>
                                                @endif
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
            $('.tab-pane').removeClass('show active');
            if (hash && $(hash).length) {
                $(hash).addClass('show active');
            } else {
                $('.tab-pane').first().addClass('show active');
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