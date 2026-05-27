@extends('layouts.admin')

@section('title', 'Coins Management')

@section('styles')
<style>
    /* Pills Styling to match Commissions page */
    .nav-pills-custom .nav-link {
        background-color: #f1f1f1;
        color: #6c757d;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
        padding: 10px 25px;
        font-weight: 600;
        border-radius: 8px;
        margin-right: 10px;
    }
    .nav-pills-custom .nav-link.active { 
        background-color: var(--bs-primary) !important; 
        color: #ffffff !important;
        border-color: var(--bs-primary) !important;
        box-shadow: 0 4px 12px rgba(var(--bs-primary-rgb), 0.2); 
    }
    
    .card-coins {
        border-radius: 15px;
        border: 1px solid #eee;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }

    .fw-black { font-weight: 900; }
    .font-medium { font-weight: 500; }
    .bg-soft-primary { background-color: rgba(var(--bs-primary-rgb), 0.1); }
    .bg-soft-success { background-color: rgba(var(--bs-success-rgb), 0.1); }
</style>
@endsection

@section('content')
@php 
    $adminCountry = session('admin_country', 'all'); 
    $isGlobalView = ($adminCountry === 'all');
@endphp

<div class="container-fluid mb-4">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Coins Management ({{ $isGlobalView ? 'All Regions' : strtoupper($adminCountry) }})</h3>
            </div>
            <div class="col-sm-6 text-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Finance</li>
                    <li class="breadcrumb-item active">Coins</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-pills nav-pills-custom" id="coinsTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="users-tab" data-bs-toggle="pill" data-bs-target="#users-content" type="button" role="tab">
                        <i class="fa-solid fa-users me-2"></i>User Coins List
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="settings-tab" data-bs-toggle="pill" data-bs-target="#settings-content" type="button" role="tab">
                        <i class="fa-solid fa-sliders me-2"></i>Coin Configuration
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="tab-content" id="coinsTabContent">
                <!-- Tab 1: User Coins List -->
                <div class="tab-pane fade show active" id="users-content" role="tabpanel">
                    <div class="card shadow-sm border-0" style="border-radius: 15px;">
                        <div class="card-header pb-4 card-no-border border-bottom">
                            <h5 class="mb-1 text-dark">Client Coin Balances</h5>
                            <p class="text-muted small mb-0">Overview of available coins for clients in <strong id="coins-region-label">{{ $selectedCountry !== '' ? $selectedCountry : ($isGlobalView ? 'All Regions' : strtoupper($adminCountry)) }}</strong>.</p>
                        </div>
                        <div class="card-body py-4">
                            <div class="row g-3 align-items-end mb-4">
                                <div class="col-md-5 col-lg-4">
                                    <label for="coins-country-filter" class="form-label fw-bold text-uppercase text-muted small">Country Filter</label>
                                    <select id="coins-country-filter" class="form-select">
                                        <option value="">{{ __('All Countries') }}</option>
                                        @foreach($countryOptions as $countryOption)
                                            <option value="{{ $countryOption }}" {{ strcasecmp((string) $selectedCountry, (string) $countryOption) === 0 ? 'selected' : '' }}>
                                                {{ $countryOption }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-auto">
                                    <button type="button" id="coins-country-reset" class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-rotate-left me-2"></i>Reset
                                    </button>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle" id="users-coins-table">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Country</th>
                                            <th>Coins Balance</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Coin Configuration -->
                <div class="tab-pane fade" id="settings-content" role="tabpanel">
                    <div class="card shadow-sm border-0" style="border-radius: 15px;">
                        <div class="card-header pb-0 card-no-border">
                            <h3>Manage Coin Economics</h3>
                            <p>Configure exchange rates and referral incentives for the selected region.</p>
                        </div>
                        <div class="card-body">
                            @if($isGlobalView)
                                <div class="alert bg-soft-primary border border-primary-subtle d-flex align-items-center gap-3 py-4">
                                    <div class="bg-primary text-white rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fa-solid fa-circle-info fs-4"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-dark">Country Selection Required</h6>
                                        <p class="mb-0 text-muted small">Please select a specific country from the top navbar to manage regional coin settings.</p>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('admin.coins.update') }}" method="POST" id="coin-settings-form">
                                    @csrf
                                    <input type="hidden" name="currency_code" value="{{ $currencyCode }}">

                                    <!-- Scenario 1: Session Payment -->
                                    <div class="mb-5">
                                        <div class="p-4 rounded-4 mb-4 border border-primary-subtle" style="background: linear-gradient(to right, #f0f7ff, #ffffff);">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fa-solid fa-coins fs-5"></i>
                                                </div>
                                                <div>
                                                    <h4 class="mb-1 text-primary fw-black">Scenario 1: Client Pays for Session with Coins</h4>
                                                    <p class="text-muted small mb-0 font-medium">The exchange rate for Zaya Coins when a client applies them to their booking payment in <strong>{{ $currencyCode }}</strong>.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row px-3">
                                            <div class="col-md-6">
                                                <div class="card bg-light border-0">
                                                    <div class="card-body">
                                                        <label class="form-label fw-bold text-uppercase text-muted small">Conversion Rate (1 Coin =)</label>
                                                        <div class="input-group input-group-lg">
                                                            <span class="input-group-text bg-primary-subtle border-primary-subtle text-primary fw-bold">{{ $symbol }}</span>
                                                            <input type="number" step="0.01" min="0" 
                                                                   name="coin_value" 
                                                                   class="form-control border-primary-subtle" 
                                                                   value="{{ $coinSetting->coin_value ?? '0.00' }}" 
                                                                   required>
                                                        </div>
                                                        <p class="mt-2 text-muted small">Set the monetary value of a single coin for users in {{ strtoupper($adminCountry) }}.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Scenario 2: Referral Rewards -->
                                    <div class="mb-4">
                                        <div class="p-4 rounded-4 mb-4 border border-success-subtle" style="background: linear-gradient(to right, #f6fff9, #ffffff);">
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="bg-success text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fa-solid fa-gift fs-5"></i>
                                                </div>
                                                <div>
                                                    <h4 class="mb-1 text-success fw-black">Scenario 2: Client Refers a Friend to Zaya</h4>
                                                    <p class="text-muted small mb-0 font-medium">The number of bonus coins awarded to a client when their referral results in a confirmed booking.</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row px-3">
                                            <div class="col-md-6">
                                                <div class="card bg-light border-0">
                                                    <div class="card-body">
                                                        <label class="form-label fw-bold text-uppercase text-muted small">Bonus Awarded Per Referral</label>
                                                        <div class="input-group input-group-lg">
                                                            <span class="input-group-text bg-success-subtle border-success-subtle text-success fw-bold"><i class="fa-solid fa-plus"></i></span>
                                                            <input type="number" step="1" min="0" 
                                                                   name="referral_coins" 
                                                                   class="form-control border-success-subtle" 
                                                                   value="{{ $coinSetting->referral_coins ?? '0' }}" 
                                                                   required>
                                                            <span class="input-group-text bg-white border-success-subtle text-muted">Coins</span>
                                                        </div>
                                                        <p class="mt-2 text-muted small">These coins are credited to the referrer's balance upon successful referral completion.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-footer text-end mt-4 px-0 pb-0 bg-transparent">
                                        <button type="submit" id="saveCoinSettingsBtn" class="btn btn-primary px-5 btn-lg shadow-sm">
                                            <i class="fa-solid fa-save me-2"></i> Save Coin Settings
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Coins Modal -->
<div class="modal fade" id="editCoinsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-bottom py-3">
                <h5 class="modal-title fw-bold">Update User Coins</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updateUserCoinsForm">
                @csrf
                <input type="hidden" id="edit_user_id" name="user_id">
                <div class="modal-body p-4">
                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted small text-uppercase">New Coin Balance</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-primary"><i class="fa-solid fa-coins"></i></span>
                            <input type="number" class="form-control" name="coins" id="edit_coins_balance" required min="0">
                        </div>
                        <div class="form-text small mt-2">Enter the absolute number of coins for this user.</div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 p-4 pt-0">
                    <button class="btn btn-outline-light text-dark px-4" type="button" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary px-4" type="submit" id="saveUserCoinsBtn">Update Balance</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        const defaultCountryFilter = @json($selectedCountry);
        const defaultRegionLabel = @json($isGlobalView ? 'All Regions' : strtoupper($adminCountry));
        const $countryFilter = $('#coins-country-filter');
        const $regionLabel = $('#coins-region-label');

        function updateRegionLabel() {
            const selectedCountry = $countryFilter.val();
            $regionLabel.text(selectedCountry || defaultRegionLabel);
        }

        const table = $('#users-coins-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.coins') }}",
                data: function(data) {
                    data.country = $countryFilter.val();
                }
            },
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'country', name: 'country', searchable: false },
                { 
                    data: 'coins', 
                    name: 'coins',
                    render: function(data) {
                        return `<span class="badge bg-soft-primary text-primary border border-primary-subtle px-3 py-2 fw-bold"><i class="fa-solid fa-coins me-1"></i> ${data}</span>`;
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search users...",
                paginate: {
                    next: '<i class="fa fa-chevron-right"></i>',
                    previous: '<i class="fa fa-chevron-left"></i>'
                }
            }
        });

        $countryFilter.on('change', function() {
            updateRegionLabel();
            table.ajax.reload();
        });

        $('#coins-country-reset').on('click', function() {
            $countryFilter.val(defaultCountryFilter);
            updateRegionLabel();
            table.ajax.reload();
        });

        $(document).on('click', '.editCoins', function() {
            const id = $(this).data('id');
            const coins = $(this).data('coins');
            $('#edit_user_id').val(id);
            $('#edit_coins_balance').val(coins);
            $('#editCoinsModal').modal('show');
        });

        $('#updateUserCoinsForm').on('submit', function(e) {
            e.preventDefault();
            const btn = $('#saveUserCoinsBtn');
            const originalText = btn.text();
            
            btn.prop('disabled', true).text('Updating...');

            $.ajax({
                url: "{{ route('admin.coins.update-user') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        $('#editCoinsModal').modal('hide');
                        if (window.showToast) showToast(response.message);
                        else alert(response.message);
                        table.ajax.reload(null, false);
                    }
                },
                error: function(xhr) {
                    const msg = 'Failed to update coins.';
                    if (window.showToast) showToast(msg, 'error');
                    else alert(msg);
                },
                complete: function() {
                    btn.prop('disabled', false).text(originalText);
                }
            });
        });

        $('#coin-settings-form').on('submit', function(e) {
            e.preventDefault();
            const btn = $('#saveCoinSettingsBtn');
            const originalHtml = btn.html();
            btn.prop('disabled', true).html('<i class="fa-solid fa-circle-notch fa-spin me-2"></i> Saving...');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (window.showToast) showToast('Settings saved successfully.', 'success');
                    else alert('Settings saved successfully.');
                },
                error: function(xhr) {
                    const errorMsg = 'An error occurred while saving.';
                    if (window.showToast) showToast(errorMsg, 'error');
                    else alert(errorMsg);
                },
                complete: function() { btn.prop('disabled', false).html(originalHtml); }
            });
        });
    });
</script>
@endsection
