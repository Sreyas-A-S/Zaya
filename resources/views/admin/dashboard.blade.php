@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
<div class="page-title">
    <div class="row">
    <div class="col-sm-6 col-12"> 
        <h2>Default Dashboard</h2>
        <p class="mb-0 text-title-gray">Welcome back! Let’s start from where you left.</p>
    </div>
    <div class="col-sm-6 col-12">
        <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="iconly-Home icli svg-color"></i></a></li>
        <li class="breadcrumb-item">Dashboard</li>
        <li class="breadcrumb-item active">Default</li>
        </ol>
    </div>
    </div>
</div>
</div>
<!-- Container-fluid starts-->
<div class="container-fluid default-dashboard">
<div class="row">
    <div class="col-xl-4 proorder-xxl-1 col-sm-6 box-col-6">
    <div class="card welcome-banner">
        <div class="card-header p-0 card-no-border">
        <div class="welcome-card"><img class="w-100 img-fluid" src="{{ asset('admiro/assets/images/dashboard-1/welcome-bg.png') }}" alt=""/><img class="position-absolute img-1 img-fluid" src="{{ asset('admiro/assets/images/dashboard-1/img-1.png') }}" alt=""/><img class="position-absolute img-2 img-fluid" src="{{ asset('admiro/assets/images/dashboard-1/img-2.png') }}" alt=""/><img class="position-absolute img-3 img-fluid" src="{{ asset('admiro/assets/images/dashboard-1/img-3.png') }}" alt=""/><img class="position-absolute img-4 img-fluid" src="{{ asset('admiro/assets/images/dashboard-1/img-4.png') }}" alt=""/><img class="position-absolute img-5 img-fluid" src="{{ asset('admiro/assets/images/dashboard-1/img-5.png') }}" alt=""/></div>
        </div>
        <div class="card-body">
        <div class="d-flex align-center">
            <h1>Hello, Amirreza  <img src="{{ asset('admiro/assets/images/dashboard-1/hand.png') }}" alt=""/></h1>
        </div>
        <p> Welcome back! Let’s start from where you left.</p>
        <div class="d-flex align-center justify-content-between"><a class="btn btn-pill btn-primary" href="">Whats New!</a><span> 
            <svg class="stroke-icon">
                <use href="{{ asset('admiro/assets/svg/icon-sprite.svg#watch') }}"></use>
            </svg> 11:14 AM</span></div>
        </div>
    </div>
    </div>
    <div class="col-xxl-3 col-xl-4 proorder-xxl-2 col-sm-6 box-col-6">
    <div class="card earning-card">
        <div class="card-header pb-0 card-no-border">
        <div class="header-top">
            <h3>Earnings Trend </h3>
            <div class="dropdown">
            <button class="btn dropdown-toggle" id="userdropdown3" type="button" data-bs-toggle="dropdown" aria-expanded="false">Monthly</button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userdropdown3"><a class="dropdown-item" href="#">Weekly</a><a class="dropdown-item" href="#">Monthly</a><a class="dropdown-item" href="#">Yearly</a></div>
            </div>
        </div>
        </div>
        <div class="card-body pb-0">
        <div class="d-flex align-center gap-3">
            <h2>$4,875</h2><span class="text-primary">
                36.28%
            <svg class="stroke-icon stroke-primary">
                <use href="{{ asset('admiro/assets/svg/icon-sprite.svg#arrow-down') }}"></use>
            </svg></span>
        </div>
        <div id="earnings-chart"></div>
        </div>
    </div>
    </div>
    <div class="col-xxl-5 col-xl-6 proorder-xxl-7 col-lg-12 box-col-12">
    <div class="card job-card">
        <div class="card-header pb-0 card-no-border">
        <div class="header-top">
            <h3>Job Today</h3>
            <div>  
            <p>Wednesday 6, <span>Dec 2022</span></p>
            </div>
        </div>
        </div>
        <div class="card-body pt-2">
        <ul class="d-flex align-center justify-content-center gap-3"> 
            <li>
            <div class="d-flex gap-2"> 
                <div class="flex-shrink-0 bg-light-primary">
                <svg class="stroke-icon">
                    <use href="{{ asset('admiro/assets/svg/icon-sprite.svg#job-bag') }}"></use>
                </svg>
                </div>
                <div class="flex-grow-1"> 
                <h3>40</h3>
                <p>Total Jobs </p>
                </div>
            </div>
            </li>
            <li>
            <div class="d-flex gap-2"> 
                <div class="flex-shrink-0 bg-light-secondary">
                <svg class="stroke-icon">
                    <use href="{{ asset('admiro/assets/svg/icon-sprite.svg#employees') }}"></use>
                </svg>
                </div>
                <div class="flex-grow-1"> 
                <h3>30</h3>
                <p>Employees</p>
                </div>
            </div>
            </li>
            <li>
            <div class="d-flex gap-2"> 
                <div class="flex-shrink-0 bg-light-warning">
                <svg class="stroke-icon">
                    <use href="{{ asset('admiro/assets/svg/icon-sprite.svg#hours-work') }}"></use>
                </svg>
                </div>
                <div class="flex-grow-1"> 
                <h3>40</h3>
                <p>Hours of work</p>
                </div>
            </div>
            </li>
        </ul>
        <div class="table-responsive theme-scrollbar">
            <table class="table display" style="width:100%">
            <thead>
                <tr> 
                <th>Time </th>
                <th>Job </th>
                <th>Company</th>
                <th class="text-center">Employee </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>10:AM</td>
                <td>Maintenace</td>
                <td>Apple Inc.</td>
                <td> 
                    <div class="d-flex align-items-center gap-2">
                    <div class="flex-shrink-0"><img src="{{ asset('admiro/assets/images/dashboard-1/user/1.png') }}" alt=""/></div>
                    <div class="flex-grow-1">
                        <h6>Michele Ronaldo</h6>
                    </div>
                    </div>
                </td>
                </tr>
                <tr>
                <td>07:AM</td>
                <td>General</td>
                <td>Hewlett packard</td>
                <td> 
                    <div class="d-flex align-items-center gap-2">
                    <div class="flex-shrink-0"><img src="{{ asset('admiro/assets/images/dashboard-1/user/2.png') }}" alt=""/></div>
                    <div class="flex-grow-1">
                        <h6>Naomi watson</h6>
                    </div>
                    </div>
                </td>
                </tr>
                <tr>
                <td>03:AM</td>
                <td>Cleaning</td>
                <td>Microsoft</td>
                <td> 
                    <div class="d-flex align-items-center gap-2">
                    <div class="flex-shrink-0"><img src="{{ asset('admiro/assets/images/dashboard-1/user/3.png') }}" alt=""/></div>
                    <div class="flex-grow-1">
                        <h6>Dann Petty</h6>
                    </div>
                    </div>
                </td>
                </tr>
            </tbody>
            </table>
        </div>
        </div>
    </div>
    </div>
    <div class="col-xxl-6 col-xl-8 proorder-xxl-8 col-lg-12 col-md-6 box-col-7">
    <div class="card">
        <div class="card-header card-no-border pb-0">
        <h3>Transition History</h3>
        </div>
        <div class="card-body transaction-history pt-0">
        <div class="table-responsive theme-scrollbar">
            <table class="table display table-bordernone" id="transaction" style="width:100%">
            <thead>
                <tr> 
                <th>Item Name</th>
                <th>Invoice No.</th>
                <th>Credit/Debit</th>
                <th>Date/Time</th>
                <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                    <div class="flex-shrink-0"><img src="{{ asset('admiro/assets/images/dashboard-1/icon/1.png') }}" alt=""/></div>
                    <div class="flex-grow-1"><a href="#">
                        <h6>Samsung TV</h6></a>
                        <p>Item Sold</p>
                    </div>
                    </div>
                </td>
                <td> #px0101</td>
                <td class="text-success">+ $3460</td>
                <td>
                    <div class="d-flex"> 
                    <div class="flex-grow-1">
                        <h6>Jan 25</h6>
                        <p>08:35:65</p>
                    </div>
                    </div>
                </td>
                <td class="text-end">
                    <div class="btn bg-light-success border-light-success text-success">Completed</div>
                </td>
                </tr>
                <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                    <div class="flex-shrink-0"><img src="{{ asset('admiro/assets/images/dashboard-1/icon/2.png') }}" alt=""/></div>
                    <div class="flex-grow-1"><a href="#">
                        <h6>Spring Bed</h6></a>
                        <p>Bought item</p>
                    </div>
                    </div>
                </td>
                <td> #rf304f</td>
                <td class="text-danger">- $910</td>
                <td>
                    <div class="d-flex"> 
                    <div class="flex-grow-1">
                        <h6>Feb 20</h6>
                        <p>12:35:00  </p>
                    </div>
                    </div>
                </td>
                <td class="text-end">
                    <div class="btn bg-light-success border-light-success text-success">Completed</div>
                </td>
                </tr>
                <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                    <div class="flex-shrink-0"><img src="{{ asset('admiro/assets/images/dashboard-1/icon/3.png') }}" alt=""/></div>
                    <div class="flex-grow-1"><a href="#">
                        <h6>Long Dress</h6></a>
                        <p>Bought item</p>
                    </div>
                    </div>
                </td>
                <td> #dnj480</td>
                <td class="text-success">+ $4380</td>
                <td>
                    <div class="d-flex"> 
                    <div class="flex-grow-1">
                        <h6>Oct 25</h6>
                        <p>04:39:08</p>
                    </div>
                    </div>
                </td>
                <td class="text-end">
                    <div class="btn bg-light-warning border-light-warning text-warning">Pending</div>
                </td>
                </tr>
                <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                    <div class="flex-shrink-0"><img src="{{ asset('admiro/assets/images/dashboard-1/icon/4.png') }}" alt=""/></div>
                    <div class="flex-grow-1"><a href="#">
                        <h6>Phillip Lightbulb</h6></a>
                        <p>Item Sold</p>
                    </div>
                    </div>
                </td>
                <td> #g189d0</td>
                <td class="text-success">+ $246</td>
                <td>
                    <div class="d-flex"> 
                    <div class="flex-grow-1">
                        <h6>Dec 25</h6>
                        <p>08:35:65</p>
                    </div>
                    </div>
                </td>
                <td class="text-end">
                    <div class="btn bg-light-danger border-light-danger text-danger">Canceled</div>
                </td>
                </tr>
                <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                    <div class="flex-shrink-0"><img src="{{ asset('admiro/assets/images/dashboard-1/icon/5.png') }}" alt=""/></div>
                    <div class="flex-grow-1"><a href="#">
                        <h6>Sofa Hauga</h6></a>
                        <p>Item Sold</p>
                    </div>
                    </div>
                </td>
                <td> #31d8fs</td>
                <td class="text-danger">- $220</td>
                <td>
                    <div class="d-flex"> 
                    <div class="flex-grow-1">
                        <h6>Jan 25</h6>
                        <p>10:20:87</p>
                    </div>
                    </div>
                </td>
                <td class="text-end">
                    <div class="btn bg-light-success border-light-success text-success">Completed</div>
                </td>
                </tr>
                <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                    <div class="flex-shrink-0"><img src="{{ asset('admiro/assets/images/dashboard-1/icon/6.png') }}" alt=""/></div>
                    <div class="flex-grow-1"><a href="#">
                        <h6>Apple iMac 19”</h6></a>
                        <p>Item Sold</p>
                    </div>
                    </div>
                </td>
                <td> #g5384h</td>
                <td class="text-success">+ $983</td>
                <td>
                    <div class="d-flex"> 
                    <div class="flex-grow-1">
                        <h6>Feb 05</h6>
                        <p>10:49:50</p>
                    </div>
                    </div>
                </td>
                <td class="text-end">
                    <div class="btn bg-light-success border-light-success text-success">Completed</div>
                </td>
                </tr>
            </tbody>
            </table>
        </div>
        </div>
    </div>
    </div>
</div>
</div>
@endsection