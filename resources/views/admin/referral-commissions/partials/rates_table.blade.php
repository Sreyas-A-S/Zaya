<!-- Section 1: Direct Bookings -->
<div class="mb-5">
    <div class="p-4 rounded-4 mb-4 border border-primary-subtle" style="background: linear-gradient(to right, #f0f7ff, #ffffff);">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-center" style="width: 40px; height: 40px;">
                <i class="fa-solid fa-user fs-5"></i>
            </div>
            <div>
                <h4 class="mb-1 text-primary fw-black">Scenario 1: Direct Bookings ({{ $title }})</h4>
                <p class="text-muted small mb-0 font-medium">Commission breakdown when a client books an expert directly.</p>
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="bg-gray-50">
                <tr>
                    <th style="width: 30%;" class="py-3 px-4">Performing Expert Role</th>
                    <th style="width: 35%;" class="py-3 px-4">Zaya Commission (%)</th>
                    <th style="width: 35%;" class="py-3 px-4">Expert's Share (%)</th>
                </tr>
            </thead>
            <tbody>
                @php $directIdx = 0; @endphp
                @foreach($roles as $roleKey => $roleLabel)
                    @if($roleKey !== 'practitioner') @continue @endif
                    @php
                        $rate = $directRates[$roleKey] ?? null;
                        $zayaVal = $rate ? $rate->company_commission_percent : 0;
                    @endphp
                    <tr>
                        <td class="fw-bold px-4">{{ $roleLabel }}</td>
                        <td class="px-4">
                            <div class="input-group">
                                <input type="hidden" name="{{ $prefix }}direct_rates[{{ $directIdx }}][referred_role]" value="{{ $roleKey }}">
                                <input type="number" step="0.01" min="0" max="100"
                                    class="form-control zaya-input border-primary-subtle"
                                    name="{{ $prefix }}direct_rates[{{ $directIdx }}][company_commission_percent]"
                                    value="{{ $zayaVal }}"
                                    oninput="updateExpertShare(this)"
                                    {{ $isDisabled ? 'disabled' : '' }}>
                                <span class="input-group-text bg-primary-subtle border-primary-subtle text-primary fw-bold">%</span>
                            </div>
                        </td>
                        <td class="px-4">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light expert-share border-gray-200" value="{{ (100 - $zayaVal) }}" readonly>
                                <span class="input-group-text bg-gray-100 border-gray-200 text-muted">%</span>
                            </div>
                        </td>
                    </tr>
                    @php $directIdx++; @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Section 2: Referral Bookings -->
<div>
    <div class="p-4 rounded-4 mb-4 border border-success-subtle" style="background: linear-gradient(to right, #f6fff9, #ffffff);">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-success text-white rounded-3 p-2 d-flex align-items-center justify-center" style="width: 40px; height: 40px;">
                <i class="fa-solid fa-share-nodes fs-5"></i>
            </div>
            <div>
                <h4 class="mb-1 text-success fw-black">Scenario 2: Referral Bookings ({{ $title }})</h4>
                <p class="text-muted small mb-0 font-medium">Commission breakdown when a practitioner refers a client to another expert.</p>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="bg-gray-50">
                <tr>
                    <th style="width: 25%;" class="py-3 px-4">Expert Referred To</th>
                    <th style="width: 25%;" class="py-3 px-4">Referring Practitioner Bonus (%)</th>
                    <th style="width: 25%;" class="py-3 px-4">Zaya Commission (%)</th>
                    <th style="width: 25%;" class="py-3 px-4">Referred Expert's Share (%)</th>
                </tr>
            </thead>
            <tbody>
                @php $refIdx = 0; @endphp
                @foreach($roles as $roleKey => $roleLabel)
                    @php
                        $rate = $referralRates[$roleKey] ?? null;
                        $refVal = $rate ? $rate->referrer_commission_percent : 0;
                        $compVal = $rate ? $rate->company_commission_percent : 0;
                    @endphp
                    <tr>
                        <td class="px-4">
                            <span class="badge bg-soft-primary text-primary border border-primary-subtle px-3 py-2 rounded-3 fw-bold">{{ $roleLabel }}</span>
                            <input type="hidden" name="{{ $prefix }}referral_rates[{{ $refIdx }}][referred_role]" value="{{ $roleKey }}">
                        </td>
                        <td class="px-4">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100"
                                    class="form-control ref-bonus-input border-success-subtle"
                                    name="{{ $prefix }}referral_rates[{{ $refIdx }}][referrer_commission_percent]"
                                    value="{{ $refVal }}"
                                    oninput="updateReferralExpertShare(this)"
                                    {{ $isDisabled ? 'disabled' : '' }}>
                                <span class="input-group-text bg-success-subtle border-success-subtle text-success fw-bold">%</span>
                            </div>
                        </td>
                        <td class="px-4">
                            <div class="input-group">
                                <input type="number" step="0.01" min="0" max="100"
                                    class="form-control zaya-commission-input border-primary-subtle"
                                    name="{{ $prefix }}referral_rates[{{ $refIdx }}][company_commission_percent]"
                                    value="{{ $compVal }}"
                                    oninput="updateReferralExpertShare(this)"
                                    {{ $isDisabled ? 'disabled' : '' }}>
                                <span class="input-group-text bg-primary-subtle border-primary-subtle text-primary fw-bold">%</span>
                            </div>
                        </td>
                        <td class="px-4">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light performing-expert-share border-gray-200" value="{{ (100 - $refVal - $compVal) }}" readonly>
                                <span class="input-group-text bg-gray-100 border-gray-200 text-muted">%</span>
                            </div>
                        </td>
                    </tr>
                    @php $refIdx++; @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</div>
