@extends('layouts.auth')

@section('title', 'Select Registration Type')

@section('content')
<div class="container-fluid p-0">
    <div class="row m-0 justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="col-12 col-md-8 col-lg-6 p-0">
            <div class="card login-dark">
                <div class="card-body text-center">
                    <div class="text-center">
                        <a class="logo" href="{{ route('login') }}">
                            <img class="img-fluid for-light m-auto mb-4 d-block" src="{{ asset('admiro/assets/images/logo/zaya wellness logo white.svg') }}" alt="logo" style="max-height: 60px;">
                            <img class="img-fluid for-dark m-auto mb-4 d-block" src="{{ asset('admiro/assets/images/logo/zaya wellness logo white.svg') }}" alt="logo" style="max-height: 60px;">
                        </a>
                    </div>
                    <h2 class="mb-4">Join Us As</h2>
                    <p class="mb-5">Please select how you would like to register</p>
                    
                    <div class="row g-4 justify-content-center">
                        <div class="col-sm-6">
                            <a href="{{ route('register.form', ['type' => 'practitioner']) }}" class="text-decoration-none">
                                <div class="card h-100 border shadow-sm hover-card">
                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            <svg class="stroke-icon text-primary" style="width: 48px; height: 48px;">
                                                <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Work') }}"></use>
                                            </svg>
                                        </div>
                                        <h4 class="mb-2">Practitioner</h4>
                                        <p class="text-muted small">For healthcare professionals</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="{{ route('register.form', ['type' => 'patient']) }}" class="text-decoration-none">
                                <div class="card h-100 border shadow-sm hover-card">
                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            <svg class="stroke-icon text-success" style="width: 48px; height: 48px;">
                                                <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Profile') }}"></use>
                                            </svg>
                                        </div>
                                        <h4 class="mb-2">Patient</h4>
                                        <p class="text-muted small">For clients seeking care</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                    <div class="mt-5">
                        <p class="mb-0">Already have an account? <a class="ms-2" href="{{ route('login') }}">Sign in</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-card {
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        border-color: var(--theme-default) !important;
    }
</style>
@endsection