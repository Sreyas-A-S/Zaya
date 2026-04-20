@extends('layouts.admin')

@section('title', 'Transaction Details')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <div class="d-flex align-items-center">
                    <a href="{{ route('admin.financial.index') }}" class="btn btn-outline-primary btn-sm me-3" title="Back to Transactions">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                    <h3 class="mb-0">Transaction #{{ $transaction->transaction_no }}</h3>
                </div>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i data-feather="home"></i></a></li>
                    <li class="breadcrumb-item">Finance</li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.financial.index') }}">Transactions</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-xl-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>Transaction Breakdown</h5>
                    <a href="{{ route('admin.financial.download', $transaction->id) }}" class="btn btn-secondary btn-sm">
                        <i class="fa fa-download"></i> Download PDF
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th width="30%">Transaction No</th>
                                    <td>{{ $transaction->transaction_no }}</td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td><span class="badge badge-{{ $transaction->type === 'booking' ? 'primary' : 'success' }}">{{ ucfirst($transaction->type) }}</span></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><span class="badge badge-pill badge-light-success">{{ strtoupper($transaction->status) }}</span></td>
                                </tr>
                                <tr>
                                    <th>Razorpay Payment ID</th>
                                    <td><code>{{ $transaction->payment_id ?: 'N/A' }}</code></td>
                                </tr>
                                <tr>
                                    <th>Currency</th>
                                    <td>{{ $transaction->currency }}</td>
                                </tr>
                                <tr>
                                    <th>Total Amount</th>
                                    <td><strong>{{ $transaction->currency }} {{ number_format($transaction->total_amount, 2) }}</strong></td>
                                </tr>
                                <tr class="table-info">
                                    <th>Company Share ({{ $transaction->company_commission_percent }}%)</th>
                                    <td>{{ $transaction->currency }} {{ number_format($transaction->company_share, 2) }}</td>
                                </tr>
                                <tr class="table-success">
                                    <th>Specialist Share</th>
                                    <td>{{ $transaction->currency }} {{ number_format($transaction->practitioner_share, 2) }}</td>
                                </tr>
                                @if($transaction->referrer_id)
                                <tr class="table-warning">
                                    <th>Referrer Share ({{ $transaction->referrer_commission_percent }}%)</th>
                                    <td>{{ $transaction->currency }} {{ number_format($transaction->referrer_share, 2) }}</td>
                                </tr>
                                @endif
                                @if($transaction->coin_discount > 0)
                                <tr>
                                    <th>Coin Discount Applied</th>
                                    <td>- {{ $transaction->currency }} {{ number_format($transaction->coin_discount, 2) }} ({{ $transaction->coins_used }} coins)</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>Date & Time</th>
                                    <td>{{ $transaction->created_at->format('d M Y, h:i A') }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    @if($services->count() > 0)
                    <div class="mt-4">
                        <h6>Included Services</h6>
                        <ul class="list-group">
                            @foreach($services as $service)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $service->title }}
                                <span class="badge badge-primary badge-pill">{{ $service->category->name ?? '' }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($transaction->booking || $transaction->referral)
                    <div class="mt-4">
                        <h6>Additional Information</h6>
                        <table class="table table-sm table-borderless">
                            @if($transaction->booking)
                                <tr>
                                    <th width="30%">Booking Mode:</th>
                                    <td>{{ ucfirst($transaction->booking->mode) }}</td>
                                </tr>
                                @if($transaction->booking->promo_code)
                                <tr>
                                    <th>Promo Code:</th>
                                    <td><span class="badge badge-dark">{{ $transaction->booking->promo_code }}</span> (-{{ $transaction->currency }} {{ number_format($transaction->booking->discount_amount, 2) }})</td>
                                </tr>
                                @endif
                            @endif

                            @if($transaction->referral && $transaction->referral->note)
                                <tr>
                                    <th width="30%">Referral Note:</th>
                                    <td>{{ $transaction->referral->note }}</td>
                                </tr>
                            @endif
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5>Parties Involved</h5>
                </div>
                <div class="card-body">
                    <div class="media mb-3">
                        <div class="media-body">
                            <h6>Client</h6>
                            <p>{{ $transaction->user->name ?? 'N/A' }}<br>
                            <small class="text-muted">{{ $transaction->user->email ?? '' }}</small></p>
                        </div>
                    </div>
                    <div class="media mb-3">
                        <div class="media-body">
                            <h6>Specialist</h6>
                            <p>{{ $transaction->practitioner->name ?? 'N/A' }}<br>
                            <small class="text-muted">{{ ucfirst($transaction->practitioner->role ?? '') }}</small></p>
                        </div>
                    </div>
                    @if($transaction->referrer)
                    <div class="media mb-3">
                        <div class="media-body">
                            <h6>Referrer</h6>
                            <p>{{ $transaction->referrer->name ?? 'N/A' }}<br>
                            <small class="text-muted">{{ ucfirst($transaction->referrer->role ?? '') }}</small></p>
                        </div>
                    </div>
                    @endif
                    
                    <hr>
                    
                    @if($transaction->booking)
                    <h6>Linked Booking</h6>
                    <p>Booking ID: #{{ $transaction->booking->id }}<br>
                    Invoice: {{ $transaction->booking->invoice_no }}</p>
                    @endif

                    @if($transaction->referral)
                    <h6>Linked Referral</h6>
                    <p>Referral No: {{ $transaction->referral->referral_no }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('a[href*="/download"]').on('click', function() {
            $('.loader-wrapper').fadeIn('fast');
            setTimeout(function() {
                $('.loader-wrapper').fadeOut('slow');
            }, 3000);
        });
    });
</script>
@endsection
