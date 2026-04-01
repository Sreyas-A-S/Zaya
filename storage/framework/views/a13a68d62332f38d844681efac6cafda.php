<div class="<?php echo e(($Contact->type === 'textarea' || $Contact->type === 'image') ? 'col-12' : 'col-md-6'); ?>">
    <label class="form-label fw-bold"><?php echo e(str_replace('_', ' ', ucfirst($Contact->key))); ?></label>

    <?php if($Contact->type === 'text'): ?>
    <input type="text" name="<?php echo e($Contact->key); ?>" value="<?php echo e($Contact->value); ?>" class="form-control" placeholder="Enter content..." <?php echo e($Contact->max_length ? 'maxlength='.$Contact->max_length : ''); ?>>
    <?php if($Contact->max_length): ?>
    <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: <?php echo e($Contact->max_length); ?></div>
    <?php endif; ?>

    <?php elseif($Contact->type === 'textarea'): ?>
    <textarea name="<?php echo e($Contact->key); ?>" class="form-control" rows="4" placeholder="Enter long text..." <?php echo e($Contact->max_length ? 'maxlength='.$Contact->max_length : ''); ?>><?php echo e($Contact->value); ?></textarea>
    <?php if($Contact->max_length): ?>
    <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: <?php echo e($Contact->max_length); ?></div>
    <?php endif; ?>

    <?php elseif($Contact->type === 'image'): ?>
    <div class="d-flex align-items-center gap-3">
        <div class="image-preview-container-<?php echo e($Contact->key); ?>">
            <?php if($Contact->value): ?>
            <div class="mb-2">
                <img src="<?php echo e(Str::startsWith($Contact->value, 'frontend/') ? asset($Contact->value) : asset('storage/' . $Contact->value)); ?>" alt="Preview" class="img-thumbnail preview-<?php echo e($Contact->key); ?>" style="max-height: 100px;">
            </div>
            <?php else: ?>
            <div class="mb-2 d-none">
                <img src="" alt="Preview" class="img-thumbnail preview-<?php echo e($Contact->key); ?>" style="max-height: 100px;">
            </div>
            <?php endif; ?>
        </div>
        <div class="flex-grow-1">
            <input type="file" name="<?php echo e($Contact->key); ?>" id="input-<?php echo e($Contact->key); ?>" class="form-control image-ajax-input" data-key="<?php echo e($Contact->key); ?>">
            <small class="text-muted">Current: <span class="current-path-<?php echo e($Contact->key); ?>"><?php echo e($Contact->value ?? 'None'); ?></span></small>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\wamp64\www\zaya\resources\views\admin\contact-setting.blade.php ENDPATH**/ ?>