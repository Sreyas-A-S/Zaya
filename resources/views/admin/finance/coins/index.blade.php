@extends('layouts.admin')

@section('title', 'Coins Management')

@section('styles')
<style>
    /* Pills Styling to look like btn-primary */
    .nav-pills-custom .nav-link {
        color: #308e87;
        background: transparent;
        border: 1px solid #308e87;
        border-radius: 8px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
        margin-right: 15px;
        display: inline-flex;
        align-items: center;
    }

    .nav-pills-custom .nav-link:hover {
        background: rgba(48, 142, 135, 0.05);
        color: #308e87;
    }

    .nav-pills-custom .nav-link.active {
        background: #308e87 !important;
        color: #fff !important;
        border-color: #308e87;
        box-shadow: 0 4px 12px rgba(48, 142, 135, 0.25);
    }

    .nav-pills-custom .nav-link i {
        font-size: 14px;
    }

    .card-coins {
        border-radius: 15px;
        border: 1px solid #eee;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
    }
</style>
@endsection

@section('content')
<div class="container-fluid mb-4">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Coins Management</h3>
            </div>
            <div class="col-sm-6 text-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="iconly-Home icli"></i></a></li>
                    <li class="breadcrumb-item">Finance</li>
                    <li class="breadcrumb-item active">Coins</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Pills Tabs Outside Card -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-pills nav-pills-custom" id="coinsTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active btn-primary" id="users-tab" data-bs-toggle="tab" href="#users-content" role="tab" aria-controls="users-content" aria-selected="true">
                        <i class="fa-solid fa-users me-2"></i>User Coins List
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-primary" id="settings-tab" data-bs-toggle="tab" href="#settings-content" role="tab" aria-controls="settings-content" aria-selected="false">
                        <i class="fa-solid fa-sliders me-2"></i>Coin Value Setting
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="tab-content" id="coinsTabContent">
                <!-- User Coins List Tab Pane -->
                <div class="tab-pane fade show active" id="users-content" role="tabpanel" aria-labelledby="users-tab">
                    <div class="card card-coins shadow-sm">
                        <div class="card-header pb-4 card-no-border border-bottom">
                            <h5 class="mb-1 text-dark">Client Coin Balances</h5>
                            @php $adminCountry = session('admin_country', 'all'); @endphp
                            <p class="text-muted small mb-0">Overview of available coins for clients in <strong>{{ $adminCountry === 'all' ? 'All Regions' : strtoupper($adminCountry) }}</strong>.</p>
                        </div>
                        <div class="card-body py-4">
                            <div class="table-responsive custom-scrollbar">
                                <table class="display" id="users-coins-table">
                                    <thead>
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

                <!-- Coin Value Setting Tab Pane -->
                <div class="tab-pane fade" id="settings-content" role="tabpanel" aria-labelledby="settings-tab">
                    <div class="card card-coins">
                        <div class="card-header pb-0 card-no-border">
                            <h5 class="mb-1 text-dark">Exchange Rate Configuration</h5>
                            <p class="text-muted small">Define how much one Zaya coin is worth in the regional currency.</p>
                        </div>
                        <div class="card-body">
                            @if($adminCountry === 'all')
                                <div class="alert alert-light border d-flex align-items-center gap-3 py-4 m-0">
                                    <div class="bg-primary-subtle p-3 rounded-circle">
                                        <i class="iconly-Info-Circle icli fs-4 text-primary"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-1 fw-bold text-dark">Country Selection Required</h6>
                                        <p class="mb-0 text-muted small">Please select a specific country from the top navbar to manage regional coin values.</p>
                                    </div>
                                </div>
                            @else
                                <form action="{{ route('admin.coins.update') }}" method="POST" class="theme-form">
                                    @csrf
                                    <input type="hidden" name="currency_code" value="{{ $currencyCode }}">
                                    
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small text-uppercase text-muted">1 Zaya Coin =</label>
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-text bg-light fw-bold text-dark">{{ $symbol }}</span>
                                                    <input type="number" step="0.01" min="0" 
                                                           name="coin_value" 
                                                           class="form-control" 
                                                           value="{{ $coinSetting->coin_value ?? '0.00' }}" 
                                                           required>
                                                </div>
                                                <div class="form-text mt-2 small">
                                                    Value in <strong>{{ $currencyCode }}</strong> for <strong>{{ strtoupper($adminCountry) }}</strong>.
                                                </div>
                                            </div>
                                            
                                            <div class="mt-4 pt-2">
                                                <button type="submit" class="btn btn-primary px-5 py-2 fw-bold">
                                                    Update Value
                                                </button>
                                            </div>
                                        </div>
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
        // Handle tab switching for btn-primary class
        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            $(e.target).addClass('btn-primary');
            $(e.relatedTarget).removeClass('btn-primary');
        });

        const table = $('#users-coins-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.coins') }}",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'email', name: 'email' },
                { data: 'country', name: 'country', searchable: false },
                { 
                    data: 'coins', 
                    name: 'coins',
                    render: function(data) {
                        return `<span class="badge bg-light text-primary border px-3 py-2 fw-bold"><i class="fa-solid fa-coins me-1"></i> ${data}</span>`;
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search users..."
            }
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
                        showToast(response.message);
                        table.ajax.reload(null, false);
                    }
                },
                error: function(xhr) {
                    showToast('Failed to update coins. Please try again.', 'error');
                },
                complete: function() {
                    btn.prop('disabled', false).text(originalText);
                }
            });
        });
    });
</script>
@endsection
