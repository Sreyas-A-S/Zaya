@extends('layouts.admin')

@section('title', 'Financial Transactions')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Financial Overview</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Finance</li>
                    <li class="breadcrumb-item active">Transactions</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Balance Cards -->
    <div class="row">
        @foreach($balances as $balance)
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden border-0">
                <div class="bg-primary b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="align-self-center text-center"><i data-feather="database"></i></div>
                        <div class="media-body">
                            <span class="m-0">Total Revenue ({{ $balance->currency }})</span>
                            <h4 class="mb-0 counter">{{ number_format($balance->total_revenue, 2) }}</h4>
                            <i class="icon-bg" data-feather="database"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden border-0">
                <div class="bg-secondary b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="align-self-center text-center"><i data-feather="shopping-bag"></i></div>
                        <div class="media-body">
                            <span class="m-0">Company Share</span>
                            <h4 class="mb-0 counter">{{ number_format($balance->total_company, 2) }}</h4>
                            <i class="icon-bg" data-feather="shopping-bag"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden border-0">
                <div class="bg-success b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="align-self-center text-center"><i data-feather="user-check"></i></div>
                        <div class="media-body">
                            <span class="m-0">Specialist Shares</span>
                            <h4 class="mb-0 counter">{{ number_format($balance->total_practitioners, 2) }}</h4>
                            <i class="icon-bg" data-feather="user-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden border-0">
                <div class="bg-warning b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="align-self-center text-center"><i data-feather="users"></i></div>
                        <div class="media-body">
                            <span class="m-0">Referrer Shares</span>
                            <h4 class="mb-0 counter">{{ number_format($balance->total_referrers, 2) }}</h4>
                            <i class="icon-bg" data-feather="users"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Transactions Table -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Transaction History</h5>
                    <span>Breakdown of all payments based on commission rates at the time of transaction.</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="transactions-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Trx No</th>
                                    <th>Type</th>
                                    <th>Client</th>
                                    <th>Specialist</th>
                                    <th>Total Amount</th>
                                    <th>Company Share</th>
                                    <th>Specialist Share</th>
                                    <th>Referrer Share</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        $('#transactions-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.financial.index') }}",
            columns: [
                { data: 'created_at', name: 'created_at' },
                { data: 'transaction_no', name: 'transaction_no' },
                { data: 'type', name: 'type' },
                { data: 'client_name', name: 'client_name' },
                { data: 'practitioner_name', name: 'practitioner_name' },
                { data: 'total_amount', name: 'total_amount' },
                { data: 'company_share', name: 'company_share' },
                { data: 'practitioner_share', name: 'practitioner_share' },
                { data: 'referrer_share', name: 'referrer_share' }
            ],
            order: [[0, 'desc']]
        });
    });
</script>
@endpush
