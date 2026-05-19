@extends('layouts.admin')

@section('title', 'Financial Transactions')

@section('content')
<style>
    #transactions-table_wrapper .dataTables_filter {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    #transactions-table_wrapper .dataTables_filter label {
        margin-bottom: 0;
    }
    #custom-filters-container {
        margin-bottom: 0 !important;
    }
</style>

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
    <div class="row" id="balances-cards-container">
        @foreach($balances as $balance)
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden border-0">
                <div class="bg-primary b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="align-self-center text-center"><i data-feather="database"></i></div>
                        <div class="media-body">
                            <span class="m-0">Total Revenue ({{ $balance->currency }})</span>
                            <h4 class="mb-0 counter">{{ number_format($balance->total_revenue, 2) }}</h4>
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5>Transaction History</h5>
                        <span>Breakdown of all payments based on commission rates at the time of transaction.</span>
                    </div>
                    <a href="{{ route('admin.financial.export') }}" class="btn btn-primary" id="export-excel-btn"><i class="fa fa-file-excel-o"></i> Export to Excel</a>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-start align-items-center mb-3 gap-3 d-none" id="custom-filters-container">
                        <div class="d-flex align-items-center gap-2">
                            <label class="mb-0 small fw-bold text-muted text-nowrap">TYPE:</label>
                            <select id="type-filter" class="form-select form-select-sm" style="width: 150px;">
                                <option value="">All Types</option>
                                <option value="booking">Booking</option>
                                <option value="referral">Referral</option>
                                <option value="registration">Registration</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label class="mb-0 small fw-bold text-muted text-nowrap">CLIENT:</label>
                            <select id="user-filter" class="form-select form-select-sm" style="width: 220px;">
                                <option value="">All Clients</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="btn btn-secondary btn-sm" id="reset-filters" title="Reset Filters">
                            <i class="fa fa-refresh"></i> Reset
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="display" id="transactions-table">
                            <thead>
                                <tr>
                                    <th>SL No</th>
                                    <th>Date</th>
                                    <th>Trx No</th>
                                    <th>Type</th>
                                    <th>Client</th>
                                    <th>Specialist</th>
                                    <th>Total Amount</th>
                                    <th>Company Share</th>
                                    <th>Specialist Share</th>
                                    <th>Referrer Share</th>
                                    <th>Action</th>
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
        let table = $('#transactions-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.financial.index') }}",
                data: function (d) {
                    d.type_filter = $('#type-filter').val();
                    d.user_filter = $('#user-filter').val();
                }
            },
            initComplete: function() {
                const filterHtml = $('#custom-filters-container').removeClass('d-none').detach();
                $('#transactions-table_wrapper .dataTables_filter').prepend(filterHtml);
            },
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'created_at', name: 'created_at' },
                { data: 'transaction_no', name: 'transaction_no' },
                { data: 'type', name: 'type' },
                { data: 'client_name', name: 'client_name' },
                { data: 'practitioner_name', name: 'practitioner_name' },
                { data: 'total_amount', name: 'total_amount' },
                { data: 'company_share', name: 'company_share' },
                { data: 'practitioner_share', name: 'practitioner_share' },
                { data: 'referrer_share', name: 'referrer_share' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            order: [[1, 'desc']]
        });

        // Dynamic Balance Card Updates from AJAX Response
        $('#transactions-table').on('xhr.dt', function (e, settings, json, xhr) {
            if (json && json.balances) {
                updateBalanceCards(json.balances);
            }
        });

        function updateBalanceCards(balances) {
            let container = $('#balances-cards-container');
            container.empty();
            
            if (balances.length === 0) {
                container.append('<div class="col-12"><div class="alert alert-light-primary text-center">No transactions found for the selected filters.</div></div>');
                return;
            }

            balances.forEach(function(balance) {
                let cards = `
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="database"></i></div>
                                <div class="media-body">
                                    <span class="m-0">Total Revenue (${balance.currency})</span>
                                    <h4 class="mb-0 counter">${parseFloat(balance.total_revenue).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</h4>
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
                                    <h4 class="mb-0 counter">${parseFloat(balance.total_company).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</h4>
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
                                    <h4 class="mb-0 counter">${parseFloat(balance.total_practitioners).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</h4>
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
                                    <h4 class="mb-0 counter">${parseFloat(balance.total_referrers).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                `;
                container.append(cards);
            });

            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        }

        // Apply Export URL Filter Update
        function updateExportUrl() {
            let type = $('#type-filter').val() || '';
            let user = $('#user-filter').val() || '';
            let baseUrl = "{{ route('admin.financial.export') }}";
            let newUrl = baseUrl + '?type_filter=' + encodeURIComponent(type) + '&user_filter=' + encodeURIComponent(user);
            $('#export-excel-btn').attr('href', newUrl);
        }

        // Filter events
        $('#type-filter, #user-filter').on('change', function() {
            table.ajax.reload();
            updateExportUrl();
        });

        $('#reset-filters').on('click', function() {
            $('#type-filter').val('');
            $('#user-filter').val('');
            table.ajax.reload();
            updateExportUrl();
        });

        // Initialize export url
        updateExportUrl();

        // Show global loader on PDF download click
        $(document).on('click', 'a[href*="/download"]', function() {
            $('.loader-wrapper').fadeIn('fast');
            // Hide loader after a few seconds as the browser download doesn't trigger a page refresh
            setTimeout(function() {
                $('.loader-wrapper').fadeOut('slow');
            }, 3000);
        });
    });
</script>
@endpush
