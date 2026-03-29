@extends('layouts.admin')

@section('title', 'Specialist Balances')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Specialist Balances</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Finance</li>
                    <li class="breadcrumb-item active">Practitioner Balances</li>
                </ol>
            </div>
        </div>
    </div>

    <!-- Balances Table -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Earnings Summary</h5>
                    <span>Accumulated earnings for each specialist from sessions and referrals.</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="display" id="practitioner-balances-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Total Earned (INR)</th>
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
        $('#practitioner-balances-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.financial.practitioners') }}",
            columns: [
                { data: 'name', name: 'name' },
                { data: 'role', name: 'role' },
                { data: 'email', name: 'email' },
                { data: 'total_balance', name: 'total_balance', searchable: false }
            ]
        });
    });
</script>
@endpush
