@extends('layouts.admin')

@section('title', 'Homepage Settings')

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
            <div class="col-sm-6">
                <h3>Homepage Settings</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Master Settings</li>
                    <li class="breadcrumb-item active">Homepage Settings</li>
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
                    <h3>Manage Homepage Content</h3>
                    <p>Update titles, subtitles, and images for different sections of the landing page.</p>
                </div>
                <div class="card-body">
                    <form id="homepageSettingsForm" action="{{ route('admin.homepage-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3">
                                <ul class="nav nav-pills flex-column h-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    @foreach($settings as $section => $group)
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }} text-start mb-2" id="v-pills-{{ $section }}-tab" data-bs-toggle="pill" data-bs-target="#v-pills-{{ $section }}" type="button" role="tab" aria-controls="v-pills-{{ $section }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                        <i class="fa-solid fa-layer-group me-2"></i> {{ ucfirst($section) }} Section
                                    </button>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-9 border-start">
                                <div class="tab-content" id="v-pills-tabContent">
                                    @foreach($settings as $section => $group)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }} p-3" id="v-pills-{{ $section }}" role="tabpanel" aria-labelledby="v-pills-{{ $section }}-tab">
                                        <div class="row g-4">
                                            @foreach($group as $setting)
                                            <div class="col-12">
                                                <label class="form-label fw-bold">{{ str_replace('_', ' ', ucfirst($setting->key)) }}</label>

                                                @if($setting->type === 'text')
                                                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control" placeholder="Enter content...">

                                                @elseif($setting->type === 'textarea')
                                                <textarea name="{{ $setting->key }}" class="form-control" rows="4" placeholder="Enter long text...">{{ $setting->value }}</textarea>

                                                @elseif($setting->type === 'image')
                                                <div class="d-flex align-items-center gap-3">
                                                    @if($setting->value)
                                                    <div class="mb-2">
                                                        <img src="{{ asset('storage/' . $setting->value) }}" alt="Preview" class="img-thumbnail" style="max-height: 100px;">
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
                                    @endforeach
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
        $('#homepageSettingsForm').on('submit', function(e) {
            e.preventDefault();

            let form = $(this);
            let btn = $('#saveSettingsBtn');
            let formData = new FormData(this);

            // Disable button and show loading
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

                        // Reload after a short delay to allow toast to be seen
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(xhr) {
                    let errorMsg = 'An error occurred while saving.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    alert(errorMsg);
                },
                complete: function() {
                    btn.prop('disabled', false).text('Save All Settings');
                }
            });
        });

        @if(session('success'))
        if (typeof showToast === 'function') {
            showToast("{{ session('success') }}");
        } else {
            alert("{{ session('success') }}");
        }
        @endif
    });
</script>
@endsection