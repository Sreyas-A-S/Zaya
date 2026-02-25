<div class="{{ ($Contact->type === 'textarea' || $Contact->type === 'image') ? 'col-12' : 'col-md-6' }}">
    <label class="form-label fw-bold">{{ str_replace('_', ' ', ucfirst($Contact->key)) }}</label>

    @if($Contact->type === 'text')
    <input type="text" name="{{ $Contact->key }}" value="{{ $Contact->value }}" class="form-control" placeholder="Enter content..." {{ $Contact->max_length ? 'maxlength='.$Contact->max_length : '' }}>
    @if($Contact->max_length)
    <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: {{ $Contact->max_length }}</div>
    @endif

    @elseif($Contact->type === 'textarea')
    <textarea name="{{ $Contact->key }}" class="form-control" rows="4" placeholder="Enter long text..." {{ $Contact->max_length ? 'maxlength='.$Contact->max_length : '' }}>{{ $Contact->value }}</textarea>
    @if($Contact->max_length)
    <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: {{ $Contact->max_length }}</div>
    @endif

    @elseif($Contact->type === 'image')
    <div class="d-flex align-items-center gap-3">
        <div class="image-preview-container-{{ $Contact->key }}">
            @if($Contact->value)
            <div class="mb-2">
                <img src="{{ Str::startsWith($Contact->value, 'frontend/') ? asset($Contact->value) : asset('storage/' . $Contact->value) }}" alt="Preview" class="img-thumbnail preview-{{ $Contact->key }}" style="max-height: 100px;">
            </div>
            @else
            <div class="mb-2 d-none">
                <img src="" alt="Preview" class="img-thumbnail preview-{{ $Contact->key }}" style="max-height: 100px;">
            </div>
            @endif
        </div>
        <div class="flex-grow-1">
            <input type="file" name="{{ $Contact->key }}" id="input-{{ $Contact->key }}" class="form-control image-ajax-input" data-key="{{ $Contact->key }}">
            <small class="text-muted">Current: <span class="current-path-{{ $Contact->key }}">{{ $Contact->value ?? 'None' }}</span></small>
        </div>
    </div>
    @endif
</div>
