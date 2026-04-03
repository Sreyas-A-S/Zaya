@extends('layouts.admin')

@section('title', 'Referral Commission Rates')

@section('content')
<div class="container-fluid">
    <div class="page-title">
        <div class="row">
            <div class="col-sm-6">
                <h3>Referral Commission Rates</h3>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i></a></li>
                    <li class="breadcrumb-item">Finance</li>
                    <li class="breadcrumb-item active">Referral Commissions</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header pb-0 card-no-border">
                    <h3>Country-wise Configuration</h3>
                    <p>Set referral bonus (%) for the referrer and Zaya commission (%) for each allowed role combination.</p>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="GET" action="{{ route('admin.referral-commissions.index') }}" class="mb-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Country</label>
                                <select name="country_id" class="form-select" onchange="this.form.submit()">
                                    @foreach($countries as $c)
                                        <option value="{{ $c->id }}" {{ (int)$countryId === (int)$c->id ? 'selected' : '' }}>
                                            {{ $c->name }} ({{ $c->code }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <a href="{{ route('admin.other-fees.index') }}" class="btn btn-light">Other Fees</a>
                            </div>
                        </div>
                    </form>

                    <form action="{{ route('admin.referral-commissions.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="country_id" value="{{ $countryId }}">

                        @php
                            $roleKeys = array_keys($roles);
                            $pairs = [];
                            foreach($roleKeys as $ref) {
                                foreach($roleKeys as $to) {
                                    $pairs[] = [$ref, $to];
                                }
                            }
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th style="min-width: 220px;">From → To</th>
                                        <th style="min-width: 220px;">Referrer Bonus (%)</th>
                                        <th style="min-width: 220px;">Zaya Commission (%)</th>
                                        <th>Receiver Share</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pairs as $idx => [$refRole, $toRole])
                                        @php
                                            $key = $refRole . '>' . $toRole;
                                            $rate = $rates[$key] ?? null;
                                            $refVal = $rate ? $rate->referrer_commission_percent : 0;
                                            $compVal = $rate ? $rate->company_commission_percent : 0;
                                        @endphp
                                        <tr>
                                            <td>
                                                <span class="fw-bold">{{ $roles[$refRole] }}</span>
                                                <span class="text-muted">→</span>
                                                <span class="fw-bold">{{ $roles[$toRole] }}</span>
                                                <input type="hidden" name="rates[{{ $idx }}][referrer_role]" value="{{ $refRole }}">
                                                <input type="hidden" name="rates[{{ $idx }}][referred_role]" value="{{ $toRole }}">
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" min="0" max="100"
                                                        class="form-control"
                                                        name="rates[{{ $idx }}][referrer_commission_percent]"
                                                        value="{{ $refVal }}">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" step="0.01" min="0" max="100"
                                                        class="form-control"
                                                        name="rates[{{ $idx }}][company_commission_percent]"
                                                        value="{{ $compVal }}">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </td>
                                            <td class="text-muted">
                                                100% - (Referrer + Zaya)
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer text-end mt-4">
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fa-solid fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

