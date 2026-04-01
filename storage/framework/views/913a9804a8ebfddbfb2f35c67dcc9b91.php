<div class="<?php echo e(($setting->type === 'textarea' || $setting->type === 'image') ? 'col-12' : 'col-md-6'); ?>">
    <label class="form-label fw-bold"><?php echo e(str_replace('_', ' ', ucfirst($setting->key))); ?></label>

    <?php if($setting->type === 'text'): ?>
    <input type="text" id="<?php echo e($setting->key); ?>" name="<?php echo e($setting->key); ?>" value="<?php echo e($setting->value); ?>" class="form-control" placeholder="Enter content..." <?php echo e($setting->max_length ? 'maxlength='.$setting->max_length : ''); ?>>
    <?php if($setting->max_length): ?>
    <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: <?php echo e($setting->max_length); ?></div>
    <?php endif; ?>

    <?php elseif($setting->type === 'textarea'): ?>
    <textarea id="<?php echo e($setting->key); ?>" name="<?php echo e($setting->key); ?>" class="form-control" rows="4" placeholder="Enter long text..." <?php echo e($setting->max_length ? 'maxlength='.$setting->max_length : ''); ?>><?php echo e($setting->value); ?></textarea>
    <?php if($setting->max_length): ?>
    <div class="text-end text-muted" style="font-size: 11px; margin-top: 4px; opacity: 0.7;">Max: <?php echo e($setting->max_length); ?></div>
    <?php endif; ?>

    <?php elseif($setting->type === 'image'): ?>
    <div class="d-flex align-items-center gap-3">
        <div class="image-preview-container-<?php echo e($setting->key); ?>">
            <?php if($setting->value): ?>
            <div class="mb-2">
                <img src="<?php echo e(Str::startsWith($setting->value, 'frontend/') ? asset($setting->value) : asset('storage/' . $setting->value)); ?>" alt="Preview" class="img-thumbnail preview-<?php echo e($setting->key); ?>" style="max-height: 100px;">
            </div>
            <?php else: ?>
            <div class="mb-2 d-none">
                <img src="" alt="Preview" class="img-thumbnail preview-<?php echo e($setting->key); ?>" style="max-height: 100px;">
            </div>
            <?php endif; ?>
        </div>
        <div class="flex-grow-1">
            <input type="file" name="<?php echo e($setting->key); ?>" id="input-<?php echo e($setting->key); ?>" class="form-control image-ajax-input" data-key="<?php echo e($setting->key); ?>">
            <small class="text-muted">Current: <span class="current-path-<?php echo e($setting->key); ?>"><?php echo e($setting->value ?? 'None'); ?></span></small>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php /**PATH C:\wamp64\www\zaya\resources\views\admin\contact-us\partials\field.blade.php ENDPATH**/ ?>