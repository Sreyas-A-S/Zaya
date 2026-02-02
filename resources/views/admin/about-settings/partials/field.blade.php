<div class="{{ ($setting->type === 'textarea' || $setting->type === 'image') ? 'col-12' : 'col-md-6' }}">
    <label class="form-label fw-bold text-dark">{{ str_replace('_', ' ', ucfirst($setting->key)) }}</label>

    @if($setting->type === 'text')
    <div class="input-group input-group-merge">
        <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-pen text-muted"></i></span>
        <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control border-start-0 ps-0" placeholder="Enter content..." {{ $setting->max_length ? 'maxlength='.$setting->max_length : '' }}>
    </div>
    @if($setting->max_length)
    <div class="text-end text-muted small mt-1" style="font-size: 0.75rem;">Max: {{ $setting->max_length }}</div>
    @endif

    @elseif($setting->type === 'textarea')
    <textarea name="{{ $setting->key }}" class="form-control" rows="4" placeholder="Enter long text..." {{ $setting->max_length ? 'maxlength='.$setting->max_length : '' }}>{{ $setting->value }}</textarea>
    @if($setting->max_length)
    <div class="text-end text-muted small mt-1" style="font-size: 0.75rem;">Max: {{ $setting->max_length }}</div>
    @endif

    @elseif($setting->type === 'image')
    <div class="card bg-light border-0">
        <div class="card-body p-3">
            <div class="d-flex align-items-center gap-4">
                <div class="position-relative">
                    @if($setting->value)
                    <img src="{{ Str::startsWith($setting->value, 'frontend/') ? asset($setting->value) : asset('storage/' . $setting->value) }}"
                        alt="Preview"
                        class="rounded-3 shadow-sm object-cover"
                        style="width: 120px; height: 80px; object-fit: cover;">
                    @else
                    <div class="bg-white rounded-3 shadow-sm d-flex align-items-center justify-content-center text-muted" style="width: 120px; height: 80px;">
                        <i class="fa-solid fa-image fa-2x"></i>
                    </div>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <input type="file" name="{{ $setting->key }}" class="form-control mb-2">
                    <small class="text-muted d-block text-truncate" style="max-width: 300px;">
                        <i class="fa-solid fa-link me-1"></i> Current: {{ $setting->value ?? 'No image uploaded' }}
                    </small>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>