<div class="{{ ($setting->type === 'textarea' || $setting->type === 'image') ? 'col-12' : 'col-md-6' }}">
    <label class="form-label fw-bold">{{ str_replace('_', ' ', ucfirst($setting->key)) }}</label>

    @if($setting->type === 'text')
    <input type="text" id="{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}" class="form-control" placeholder="Enter content..." {{ $setting->max_length ? 'maxlength='.$setting->max_length : '' }}>
    @if($setting->max_length)
    <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: {{ $setting->max_length }}</div>
    @endif

    @elseif($setting->type === 'textarea')
    <textarea id="{{ $setting->key }}" name="{{ $setting->key }}" class="form-control" rows="4" placeholder="Enter long text..." {{ $setting->max_length ? 'maxlength='.$setting->max_length : '' }}>{{ $setting->value }}</textarea>
    @if($setting->max_length)
    <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: {{ $setting->max_length }}</div>
    @endif

    @elseif($setting->type === 'image')
    <div class="d-flex align-items-center gap-3">
        <div class="image-preview-container-{{ $setting->key }} text-center">
            @if($setting->value)
            <div class="mb-2">
                <img src="{{ Str::startsWith($setting->value, 'frontend/') ? asset($setting->value) : asset('storage/' . $setting->value) }}" alt="Preview" class="img-thumbnail preview-{{ $setting->key }}" style="max-height: 100px;">
            </div>
            @else
            <div class="mb-2 d-none">
                <img src="" alt="Preview" class="img-thumbnail preview-{{ $setting->key }}" style="max-height: 100px;">
            </div>
            @endif
            <div class="mt-1 d-none resolution-info-{{ $setting->key }}">
                <span class="badge badge-light-secondary resolution-text-{{ $setting->key }}" style="font-size: 10px;">0x0</span>
            </div>
        </div>
        <div class="flex-grow-1">
            <input type="file" id="input-{{ $setting->key }}" class="form-control image-ajax-input" 
                   data-key="{{ $setting->key }}" 
                   data-aspect-ratio="{{ $aspectRatio ?? '' }}">
            <input type="hidden" name="{{ $setting->key }}_cropped" id="cropped-{{ $setting->key }}">
            <small class="text-muted">Current: <span class="current-path-{{ $setting->key }}">{{ $setting->value ?? 'None' }}</span></small>
        </div>
    </div>
    @endif
</div>