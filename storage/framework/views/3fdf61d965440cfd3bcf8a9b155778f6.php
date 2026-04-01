<!-- Edit Language Modal -->
<div class="modal fade" id="editLanguageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Edit Language</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="editLanguageForm">

                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <input type="hidden" id="edit_language_id">

                    <div class="mb-3">
                        <label>Language Code</label>
                        <input type="text" id="edit_code" name="code" class="form-control">
                        <small class="text-danger error-text code_error"></small>
                    </div>

                    <div class="mb-3">
                        <label>Language Name</label>
                        <input type="text" id="edit_name" name="name" class="form-control">
                        <small class="text-danger error-text name_error"></small>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            Update Language
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
<?php /**PATH C:\wamp64\www\zaya\resources\views\admin\languages\edit.blade.php ENDPATH**/ ?>