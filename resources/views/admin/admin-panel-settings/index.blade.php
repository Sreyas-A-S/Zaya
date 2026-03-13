@extends('layouts.admin')

@section('title', 'Admin Panel Settings')

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
                <h3>Admin Panel Settings</h3>
            </div>
            <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Settings</li>
                    <li class="breadcrumb-item active">Admin Panel Settings</li>
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
                    <h3>Manage Admin Panel Content</h3>
                    <p>Update admin panel page content, including banner and statistics.</p>      
                </div>
                <div class="card-body">
                    <form id="profileSettingsForm" action="{{ route('admin.admin-panel-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-3">
                                <ul class="nav nav-pills flex-column h-100" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                    <button class="nav-link active text-start mb-2" id="v-pills-general-tab" data-bs-toggle="pill" data-bs-target="#v-pills-general" type="button" role="tab" aria-controls="v-pills-general" aria-selected="true">
                                        <i class="fa-solid fa-circle-info me-2"></i> General
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-sidebar-tab" data-bs-toggle="pill" data-bs-target="#v-pills-sidebar" type="button" role="tab" aria-controls="v-pills-sidebar" aria-selected="false">
                                        <i class="fa-solid fa-bars me-2"></i> Sidebar
                                    </button>
                                    <button class="nav-link text-start mb-2" id="v-pills-stats-tab" data-bs-toggle="pill" data-bs-target="#v-pills-stats" type="button" role="tab" aria-controls="v-pills-stats" aria-selected="false">
                                        <i class="fa-solid fa-chart-simple me-2"></i> Statistics
                                    </button>
                                </ul>
                            </div>
                            <div class="col-md-9 border-start">
                                <div class="tab-content" id="v-pills-tabContent">
                                    @php
                                        $statsSettings = $settings->filter(fn($s) => Str::contains($s->key, 'stat'));
                                        $sidebarSettings = $settings->filter(fn($s) => Str::contains($s->key, 'sidebar_'));
                                        $generalSettings = $settings->filter(fn($s) => !Str::contains($s->key, 'stat') && !Str::contains($s->key, 'sidebar_'));
                                    @endphp
                                    <!-- General Tab -->
                                    <div class="tab-pane fade show active p-3" id="v-pills-general"        
                                        role="tabpanel" aria-labelledby="v-pills-general-tab">
                                        <div class="row g-4">
                                            @foreach($generalSettings as $setting)
                                                @include('admin.services-settings.partials.field',
                                                            ['setting' => $setting])
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- Sidebar Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-sidebar" role="tabpanel" aria-labelledby="v-pills-sidebar-tab">
                                        <div class="row g-4">
                                            @foreach($sidebarSettings as $setting)
                                                @include('admin.services-settings.partials.field',
                                                            ['setting' => $setting])
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- Stats Tab -->
                                    <div class="tab-pane fade p-3" id="v-pills-stats" role="tabpanel"
                                       aria-labelledby="v-pills-stats-tab">
                                        <div class="row g-4">
                                            @foreach($statsSettings as $setting)
                                                @include('admin.services-settings.partials.field',
                                                            ['setting' => $setting])
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
        // Function to activate tab based on hash
        function activateTabFromHash() {
            let hash = window.location.hash;
            if (hash) {
                // Find button that targets this hash
                let tabBtn = $(`button[data-bs-target="${hash}"]`);
                if (tabBtn.length) {
                    tabBtn.trigger('click');
                }
            }
        }
        // Run on load
        activateTabFromHash();
        // Run on hash change
        $(window).on('hashchange', function() {
            activateTabFromHash();
        });
        $('#profileSettingsForm').on('submit', function(e) {
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
                            showToast(response.message, 'success');
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
                    if (typeof showToast === 'function') {
                        showToast(errorMsg, 'error');
                    } else {
                        alert(errorMsg);
                    }
                },
                complete: function() {
                    btn.prop('disabled', false).text('Save All Settings');
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
        @if(session('success'))
        if (typeof showToast === 'function') {
            showToast("{{ session('success') }}", 'success');
        } else {
            alert("{{ session('success') }}");
        }
        @endif
    });
</script>
@endsection
