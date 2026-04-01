<form id="editCountryForm" data-id="<?php echo e($country->id); ?>">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div class="mb-3">
        <label>Code</label>
        <input type="text" name="code" value="<?php echo e($country->code); ?>" class="form-control">
    </div>

    <div class="mb-3">
        <label>Name</label>
        <input type="text" name="name" value="<?php echo e($country->name); ?>" class="form-control">
    </div>

    <div class="mb-3">
        <label>Flag</label>
        <input type="text" name="flag" value="<?php echo e($country->flag); ?>" class="form-control">
    </div>

    <button type="submit" class="btn btn-success">Update</button>
</form>
<?php /**PATH C:\wamp64\www\zaya\resources\views\admin\countries\create.blade.php ENDPATH**/ ?>