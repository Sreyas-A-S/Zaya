@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6 col-12">
                <h2>Dashboard</h2>
                <p class="mb-0 text-title-gray">Welcome to your administration panel.</p>
            </div>
            <div class="col-sm-6 col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="iconly-Home icli svg-color"></i></a></li>
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item active">Overview</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<!-- Container-fluid starts-->
<div class="container-fluid default-dashboard">
    <div class="row">
        <!-- Welcome Card -->
        <div class="col-xl-4 proorder-xxl-1 col-sm-6 box-col-6">
            <div class="card welcome-banner">
                <div class="card-header p-0 card-no-border">
                    <div class="welcome-card"><img class="w-100 img-fluid" src="{{ asset('admiro/assets/images/dashboard-1/welcome-bg.png') }}" alt="" /><img class="position-absolute img-1 img-fluid" src="{{ asset('admiro/assets/images/dashboard-1/img-1.png') }}" alt="" /><img class="position-absolute img-2 img-fluid" src="{{ asset('admiro/assets/images/dashboard-1/img-2.png') }}" alt="" /><img class="position-absolute img-3 img-fluid" src="{{ asset('admiro/assets/images/dashboard-1/img-3.png') }}" alt="" /><img class="position-absolute img-4 img-fluid" src="{{ asset('admiro/assets/images/dashboard-1/img-4.png') }}" alt="" /><img class="position-absolute img-5 img-fluid" src="{{ asset('admiro/assets/images/dashboard-1/img-5.png') }}" alt="" /></div>
                </div>
                <div class="card-body">
                    <div class="d-flex align-center">
                        <h1>Hello, {{ $user->first_name ?: $user->name }} <img src="{{ asset('admiro/assets/images/dashboard-1/hand.png') }}" alt="" /></h1>
                    </div>
                    <p class="mb-4">Welcome back! Let’s start from where you left.</p>

                    @if($user->role !== 'super-admin')
                    <div class="d-flex flex-wrap gap-4 mb-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="flex-shrink-0 bg-light-primary p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <svg class="stroke-icon stroke-primary" style="width: 20px; height: 20px;">
                                    <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Location') }}"></use>
                                </svg>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted f-w-600" style="font-size: 12px;">Nationality</h6>
                                @if($user->nationality)
                                <span class="f-w-700 text-dark">{{ $user->nationality->name }}</span>
                                @else
                                <span class="text-muted f-w-500" style="font-size: 13px;">Not specified</span>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <div class="flex-shrink-0 bg-light-secondary p-2 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <svg class="stroke-icon stroke-secondary" style="width: 20px; height: 20px;">
                                    <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Chat') }}"></use>
                                </svg>
                            </div>
                            <div>
                                <h6 class="mb-0 text-muted f-w-600" style="font-size: 12px;">Languages</h6>
                                <div class="d-flex flex-wrap gap-1 mt-1">
                                    @forelse($myLanguages as $lang)
                                    <span class="badge badge-light-secondary">{{ $lang->name }}</span>
                                    @empty
                                    <span class="text-muted f-w-500" style="font-size: 13px;">Not specified</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="d-flex align-center justify-content-between mt-auto">
                        <span> 
                            <svg class="stroke-icon" style="width:16px; height:16px;">
                                <use href="{{ asset('admiro/assets/svg/icon-sprite.svg#watch') }}"></use>
                            </svg> {{ date('h:i A') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="col-xl-8 proorder-xxl-2 box-col-6">
            <div class="row">
                <!-- Total Users -->
                <div class="col-sm-6 col-xl-4">
                    <div class="card small-widget mb-sm-4 mb-xl-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <h2 class="mb-1">{{ $stats['total_users'] }}</h2>
                                    <p class="mb-0 font-roboto">Total Users</p>
                                </div>
                                <div class="flex-shrink-0 bg-light-primary p-3 rounded-pill">
                                    <i class="fa fa-users text-primary fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Total Practitioners -->
                <div class="col-sm-6 col-xl-4">
                    <div class="card small-widget mb-sm-4 mb-xl-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <h2 class="mb-1">{{ $stats['total_practitioners'] }}</h2>
                                    <p class="mb-0 font-roboto">Practitioners</p>
                                </div>
                                <div class="flex-shrink-0 bg-light-secondary p-3 rounded-pill">
                                    <i class="fa fa-user-md text-secondary fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Total Patients -->
                <div class="col-sm-6 col-xl-4">
                    <div class="card small-widget mb-sm-4 mb-xl-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <h2 class="mb-1">{{ $stats['total_patients'] }}</h2>
                                    <p class="mb-0 font-roboto">Patients</p>
                                </div>
                                <div class="flex-shrink-0 bg-light-warning p-3 rounded-pill">
                                    <i class="fa fa-user text-warning fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Total Services -->
                <div class="col-sm-6 col-xl-4">
                    <div class="card small-widget">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <h2 class="mb-1">{{ $stats['total_services'] }}</h2>
                                    <p class="mb-0 font-roboto">Services</p>
                                </div>
                                <div class="flex-shrink-0 bg-light-info p-3 rounded-pill">
                                    <i class="fa fa-heartbeat text-info fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Total Packages -->
                <div class="col-sm-6 col-xl-4">
                    <div class="card small-widget">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <h2 class="mb-1">{{ $stats['total_packages'] }}</h2>
                                    <p class="mb-0 font-roboto">Packages</p>
                                </div>
                                <div class="flex-shrink-0 bg-light-success p-3 rounded-pill">
                                    <i class="fa fa-archive text-success fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
