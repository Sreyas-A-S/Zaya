@extends('layouts.admin')

@section('title', 'Invoices Management')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Invoices Management</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Invoices</li>
                    <li class="breadcrumb-item active">List</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Invoices List</h3>
                    <a href="{{ route('admin.invoice.preview') }}" target="_blank" class="btn btn-secondary">
                        <i class="fa-solid fa-eye me-2"></i>Preview Design
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="invoices-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Practitioner</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->invoice_no }}</td>
                                    <td>{{ $booking->user->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->practitioner->user->name ?? 'N/A' }}</td>
                                    <td>{{ $booking->booking_date ? $booking->booking_date->format('M d, Y') : 'N/A' }}</td>
                                    @php
                                        $currencyCode = strtoupper($booking->currency ?? config('app.currency', 'INR'));
                                        $symbols = config('currencies.symbols', []);
                                        $currencySymbol = $symbols[$currencyCode] ?? $currencyCode;
                                    @endphp
                                    <td>{{ $currencySymbol }} {{ number_format($booking->total_price, 2) }}</td>
                                    <td>
                                        <span class="badge {{ $booking->status === 'Paid' ? 'bg-success' : 'bg-warning' }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('invoice.show', $booking->invoice_no) }}" target="_blank" class="btn btn-primary btn-sm">
                                            <i class="fa-solid fa-file-invoice"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
