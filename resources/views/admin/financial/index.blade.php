@extends('layouts.admin')

@section('title', 'Financial Transactions')

@push('css')
<link rel="stylesheet" type="text/css" href="{{ asset('admiro/assets/css/vendors/select2.css') }}">
@endpush

@section('content')
<style>
    #transactions-table_wrapper .dataTables_filter {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        gap: 15px;
        flex-wrap: wrap;
    }
    #transactions-table_wrapper .dataTables_filter label {
        margin-bottom: 0;
    }
    #custom-filters-container {
        margin-bottom: 0 !important;
    }
    #transactions-table_wrapper .select2-container .select2-selection--single {
        height: 31px !important;
        border: 1px solid #ced4da !important;
        border-radius: 0.2rem !important;
        display: flex !important;
        align-items: center !important;
    }
    #transactions-table_wrapper .select2-container .select2-selection__rendered {
        line-height: 29px !important;
        padding-left: 0.75rem !important;
        padding-right: 2rem !important;
    }
    #transactions-table_wrapper .select2-container .select2-selection__arrow {
        height: 29px !important;
    }
    .overview-card-value {
        font-size: 1.25rem;
        font-weight: 700;
        line-height: 1.4;
    }
    .overview-card-value small {
        font-size: 0.8rem;
        opacity: 0.75;
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
        @if(session('admin_country', 'all') === 'all')
        <div class="col-12" id="currency-prompt-alert">
            <div class="alert alert-warning text-center fw-bold border-warning">
                <i class="fa fa-exclamation-triangle me-2"></i> Please select a currency from the filter dropdown below to load the transaction history and metrics.
            </div>
        </div>
        @else
            @foreach($overview as $card)
            <div class="col-sm-6 col-xl-4 col-lg-6">
                <div class="card o-hidden border-0">
                    <div class="bg-{{ $card['color'] }} b-r-4 card-body">
                        <div class="media static-top-widget align-items-start">
                            <div class="align-self-center text-center"><i data-feather="{{ $card['icon'] }}"></i></div>
                            <div class="media-body">
                                <span class="m-0">{{ $card['title'] }}</span>
                                @if(!empty($card['amounts']))
                                    @foreach($card['amounts'] as $amount)
                                        <div class="overview-card-value {{ !$loop->first ? 'mt-1' : 'mt-2' }}">
                                            {{ $amount['currency'] }} {{ number_format($amount['amount'], 2) }}
                                        </div>
                                    @endforeach
                                @else
                                    <div class="overview-card-value mt-2">0.00</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
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
                        @if(session('admin_country', 'all') === 'all')
                        <div class="d-flex align-items-center gap-2">
                            <label class="mb-0 small fw-bold text-muted text-nowrap">CURRENCY:</label>
                            <select id="currency-filter" class="form-select form-select-sm" style="width: 160px;">
                                <option value="">Select Currency</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency }}">{{ $currency }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
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
                            <label class="mb-0 small fw-bold text-muted text-nowrap">USER TYPE:</label>
                            <select id="user-filter" class="form-select form-select-sm" style="width: 220px;">
                                <option value="">All Users</option>
                                @foreach($userRoles as $role)
                                    <option value="{{ $role }}">
                                        {{ ucwords(str_replace('_', ' ', $role)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label class="mb-0 small fw-bold text-muted text-nowrap">MONTH:</label>
                            <select id="month-filter" class="form-select form-select-sm" style="width: 150px;">
                                <option value="">All Months</option>
                                @foreach($months as $monthValue => $monthLabel)
                                    <option value="{{ $monthValue }}">{{ $monthLabel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label class="mb-0 small fw-bold text-muted text-nowrap">YEAR:</label>
                            <select id="year-filter" class="form-select form-select-sm" style="width: 140px;">
                                <option value="">All Years</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}">{{ $year }}</option>
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
<script src="{{ asset('admiro/assets/js/select2/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        function initFinancialFilters() {
            if (!$.fn.select2) {
                return;
            }

            const $currencyFilter = $('#currency-filter');
            if ($currencyFilter.length && $currencyFilter.hasClass('select2-hidden-accessible')) {
                $currencyFilter.select2('destroy');
            }
            const $typeFilter = $('#type-filter');
            if ($typeFilter.hasClass('select2-hidden-accessible')) {
                $typeFilter.select2('destroy');
            }
            const $userFilter = $('#user-filter');
            if ($userFilter.hasClass('select2-hidden-accessible')) {
                $userFilter.select2('destroy');
            }
            const $monthFilter = $('#month-filter');
            if ($monthFilter.hasClass('select2-hidden-accessible')) {
                $monthFilter.select2('destroy');
            }
            const $yearFilter = $('#year-filter');
            if ($yearFilter.hasClass('select2-hidden-accessible')) {
                $yearFilter.select2('destroy');
            }

            if ($currencyFilter.length) {
                $currencyFilter.select2({
                    placeholder: 'Select Currency',
                    allowClear: true,
                    width: '160px'
                });
            }

            $typeFilter.select2({
                placeholder: 'All Types',
                allowClear: true,
                width: '150px'
            });

            $userFilter.select2({
                placeholder: 'All Users',
                allowClear: true,
                width: '220px'
            });

            $monthFilter.select2({
                placeholder: 'All Months',
                allowClear: true,
                width: '150px'
            });

            $yearFilter.select2({
                placeholder: 'All Years',
                allowClear: true,
                width: '140px'
            });
        }

        let table = $('#transactions-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.financial.index') }}",
                data: function (d) {
                    d.type_filter = $('#type-filter').val();
                    d.user_filter = $('#user-filter').val();
                    d.month_filter = $('#month-filter').val();
                    d.year_filter = $('#year-filter').val();
                    if ($('#currency-filter').length) {
                        d.currency_filter = $('#currency-filter').val();
                    }
                }
            },
            initComplete: function() {
                const filterHtml = $('#custom-filters-container').removeClass('d-none').detach();
                $('#transactions-table_wrapper .dataTables_filter').prepend(filterHtml);
                initFinancialFilters();
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

        // Dynamic Overview Card Updates from AJAX Response
        $('#transactions-table').on('xhr.dt', function (e, settings, json, xhr) {
            if (json && json.overview) {
                updateBalanceCards(json.overview);
            }
        });

        function formatOverviewAmounts(amounts) {
            if (!amounts || amounts.length === 0) {
                return '<div class="overview-card-value mt-2">0.00</div>';
            }

            return amounts.map(function(amount, index) {
                return `<div class="overview-card-value ${index > 0 ? 'mt-1' : 'mt-2'}">${amount.currency} ${parseFloat(amount.amount).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}</div>`;
            }).join('');
        }

        function updateBalanceCards(overview) {
            let container = $('#balances-cards-container');
            container.empty();
            
            // Check if currency filter is present and unselected when country code is 'all'
            const isAllCountry = "{{ session('admin_country', 'all') }}" === 'all';
            const currencyVal = $('#currency-filter').length ? $('#currency-filter').val() : '';
            
            if (isAllCountry && !currencyVal) {
                container.append(`
                    <div class="col-12" id="currency-prompt-alert">
                        <div class="alert alert-warning text-center fw-bold border-warning">
                            <i class="fa fa-exclamation-triangle me-2"></i> Please select a currency from the filter dropdown below to load the transaction history and metrics.
                        </div>
                    </div>
                `);
                return;
            }

            if (!overview || overview.length === 0) {
                container.append('<div class="col-12"><div class="alert alert-light-primary text-center">No transactions found for the selected filters.</div></div>');
                return;
            }

            overview.forEach(function(card) {
                let cards = `
                <div class="col-sm-6 col-xl-4 col-lg-6">
                    <div class="card o-hidden border-0">
                        <div class="bg-${card.color} b-r-4 card-body">
                            <div class="media static-top-widget align-items-start">
                                <div class="align-self-center text-center"><i data-feather="${card.icon}"></i></div>
                                <div class="media-body">
                                    <span class="m-0">${card.title}</span>
                                    ${formatOverviewAmounts(card.amounts)}
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
            let month = $('#month-filter').val() || '';
            let year = $('#year-filter').val() || '';
            let currency = $('#currency-filter').length ? ($('#currency-filter').val() || '') : '';
            let baseUrl = "{{ route('admin.financial.export') }}";
            let newUrl = baseUrl
                + '?type_filter=' + encodeURIComponent(type)
                + '&user_filter=' + encodeURIComponent(user)
                + '&month_filter=' + encodeURIComponent(month)
                + '&year_filter=' + encodeURIComponent(year)
                + '&currency_filter=' + encodeURIComponent(currency);
            $('#export-excel-btn').attr('href', newUrl);
        }

        // Filter events
        $('#type-filter, #user-filter, #month-filter, #year-filter, #currency-filter').on('change', function() {
            table.ajax.reload();
            updateExportUrl();
        });

        $('#reset-filters').on('click', function() {
            $('#type-filter').val('');
            $('#user-filter').val('');
            $('#month-filter').val('');
            $('#year-filter').val('');
            if ($('#currency-filter').length) {
                $('#currency-filter').val('');
            }
            if ($('#type-filter').hasClass('select2-hidden-accessible')) {
                $('#type-filter').trigger('change.select2');
            }
            if ($('#user-filter').hasClass('select2-hidden-accessible')) {
                $('#user-filter').trigger('change.select2');
            }
            if ($('#month-filter').hasClass('select2-hidden-accessible')) {
                $('#month-filter').trigger('change.select2');
            }
            if ($('#year-filter').hasClass('select2-hidden-accessible')) {
                $('#year-filter').trigger('change.select2');
            }
            if ($('#currency-filter').length && $('#currency-filter').hasClass('select2-hidden-accessible')) {
                $('#currency-filter').trigger('change.select2');
            }
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
