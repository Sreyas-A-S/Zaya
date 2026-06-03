@extends('layouts.admin')

@section('title', 'Find Practitioner Settings')

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
                <h3>Find Practitioner Settings</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"> <i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active">Find Practitioner Settings</li>
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
                    <h3>Manage Find Practitioner Content</h3>
                    <p>Update content for the Find Practitioner page, including header text and placeholders.</p>
                </div>
                <div class="card-body">
                    <form id="findPractitionerSettingsForm" action="{{ route('admin.find-practitioner-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="tab-content" id="v-pills-tabContent">
                                    @php
                                    $heroSettings = $settings->filter(fn($s) => Str::contains($s->key, ['title', 'subtitle', 'description']));
                                    $searchSettings = $settings->filter(fn($s) => Str::contains($s->key, 'placeholder'));
                                    $resultSettings = $settings->filter(fn($s) => Str::contains($s->key, ['results', 'load_more']));
                                    @endphp

                                    <!-- Hero Section Tab -->
                                    <div class="tab-pane fade show active p-3" id="v-pills-hero" role="tabpanel">
                                        <div class="row g-4">
                                            @foreach($heroSettings as $setting)
                                            <div class="col-12">
                                                <label class="form-label fw-bold">{{ str_replace('_', ' ', ucfirst(str_replace('find_practitioner_', '', $setting->key))) }}</label>

                                                @if($setting->type === 'text')
                                                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control" placeholder="Enter content..." {{ $setting->max_length ? 'maxlength='.$setting->max_length : '' }}>
                                                @if($setting->max_length)
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: {{ $setting->max_length }}</div>
                                                @endif

                                                @elseif($setting->type === 'textarea')
                                                <textarea name="{{ $setting->key }}" class="form-control" rows="4" placeholder="Enter content..." {{ $setting->max_length ? 'maxlength='.$setting->max_length : '' }}>{{ $setting->value }}</textarea>
                                                @if($setting->max_length)
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: {{ $setting->max_length }}</div>
                                                @endif
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Search & Filters Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-search" role="tabpanel">
                                        <div class="row g-4">
                                            @foreach($searchSettings as $setting)
                                            <div class="col-12">
                                                <label class="form-label fw-bold">{{ str_replace('_', ' ', ucfirst(str_replace('find_practitioner_', '', $setting->key))) }}</label>
                                                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control" placeholder="Enter placeholder text..." {{ $setting->max_length ? 'maxlength='.$setting->max_length : '' }}>
                                                @if($setting->max_length)
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: {{ $setting->max_length }}</div>
                                                @endif
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Results Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-results" role="tabpanel">
                                        <div class="row g-4">
                                            @foreach($resultSettings as $setting)
                                            <div class="col-12">
                                                <label class="form-label fw-bold">{{ str_replace('_', ' ', ucfirst(str_replace('find_practitioner_', '', $setting->key))) }}</label>
                                                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control" placeholder="Enter text..." {{ $setting->max_length ? 'maxlength='.$setting->max_length : '' }}>
                                                @if($setting->max_length)
                                                <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: {{ $setting->max_length }}</div>
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

        $('#findPractitionerSettingsForm').on('submit', function(e) {
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
