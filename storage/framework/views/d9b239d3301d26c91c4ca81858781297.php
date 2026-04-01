<?php $__env->startSection('title', 'Reset Password'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid p-0">
  <div class="row m-0">
    <div class="col-12 p-0">
      <div class="login-card">
        <div>
          <div class="text-center"><a class="logo" href="<?php echo e(route('admin.login')); ?>"><img class="img-fluid for-dark d-block m-auto" src="<?php echo e(asset('admiro/assets/images/logo/zaya-logo-admin.svg')); ?>" alt="logo" style="height: 100px;"></a></div>

          <div class="login-main">
            <form class="theme-form" method="POST" action="<?php echo e(route('admin.forgot-password.reset.update')); ?>">
              <?php echo csrf_field(); ?>
              <h2 class="text-center">Reset Password</h2>
              <p class="text-center">Enter your new password for: <strong><?php echo e(session('reset_email')); ?></strong></p>

              <div class="form-group">
                <label class="col-form-label">New Password</label>
                <div class="form-input position-relative">
                  <input class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="password" name="password" required placeholder="*********">
                  <div class="show-hide"><span class="show"> </span></div>
                  <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                  <span class="invalid-feedback" role="alert">
                    <strong><?php echo e($message); ?></strong>
                  </span>
                  <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
              </div>

              <div class="form-group">
                <label class="col-form-label">Confirm New Password</label>
                <div class="form-input position-relative">
                  <input class="form-control" type="password" name="password_confirmation" required placeholder="*********">
                  <div class="show-hide"><span class="show"> </span></div>
                </div>
              </div>

              <div class="form-group mb-0">
                <div class="text-end mt-3">
                  <button class="btn btn-primary btn-block w-100" type="submit" id="reset-password-btn">
                    <i class="fa fa-spinner fa-spin btn-loader"></i> Reset Password
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  /* Button Loader */
  .btn-loader {
    display: inline-block;
    width: 0;
    opacity: 0;
    overflow: hidden;
    transition: all 0.4s ease;
    vertical-align: middle;
  }

  .btn.loading .btn-loader {
    width: 16px;
    opacity: 1;
    margin-right: 5px;
  }
</style>

<?php $__env->startSection('scripts'); ?>
<script>
  $(document).ready(function() {
    $('.theme-form').on('submit', function() {
      var btn = $('#reset-password-btn');
      if (!btn.hasClass('loading')) {
        btn.addClass('loading');
        btn.css('pointer-events', 'none');
      }
    });
  });
</script>
<?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\auth\passwords\reset.blade.php ENDPATH**/ ?>