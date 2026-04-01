<?php $__env->startSection('title', 'Forgot Password'); ?>

<?php $__env->startSection('content'); ?>
  <div class="container-fluid p-0 overflow-hidden">
    <!-- Leaves Images -->
    <img src="<?php echo e(asset('admiro/assets/images/admin-t-r.png')); ?>" alt="logo" class="img-fluid admin-top-right-img">
    <img src="<?php echo e(asset('admiro/assets/images/admin-t-l.png')); ?>" alt="logo" class="img-fluid admin-top-left-img">
    <img src="<?php echo e(asset('admiro/assets/images/admin-b-l.png')); ?>" alt="logo" class="img-fluid admin-bottom-left-img">
    <img src="<?php echo e(asset('admiro/assets/images/admin-b-r.png')); ?>" alt="logo" class="img-fluid admin-bottom-right-img">
    <!-- Smile image -->
    <img src="<?php echo e(asset('admiro/assets/images/zaya-smile-vector.png')); ?>" alt="logo" class="img-fluid admin-smile-img">

    <div class="row m-0">
      <div class="col-12 p-0">
        <div class="login-card position-relative z-3">
          <div class="w-100">
            <div class="text-center"><a class="logo" href="<?php echo e(route('admin.login')); ?>"><img
                  class="img-fluid for-dark d-block m-auto"
                  src="<?php echo e(asset('admiro/assets/images/logo/zaya-logo-admin.svg')); ?>" alt="logo"
                  style="height: 100px;"></a></div>

            <div class="login-main">
              <form class="theme-form" method="POST" action="<?php echo e(route('admin.forgot-password.send')); ?>">
                <?php echo csrf_field(); ?>
                <h2 class="text-center">Forgot Password</h2>
                <p class="text-center">Enter your email to receive an OTP</p>

                <?php if(session('status')): ?>
                  <div class="alert alert-success text-center py-2" role="alert">
                    <?php echo e(session('status')); ?>

                  </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                  <div class="alert alert-danger text-center py-2" role="alert">
                    <?php echo e(session('error')); ?>

                  </div>
                <?php endif; ?>

                <div class="form-group">
                  <label class="col-form-label">Email Address</label>
                  <input class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="email" name="email"
                    value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus placeholder="admin@example.com">
                  <?php $__errorArgs = ['email'];
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

                <div class="form-group mb-0">
                  <div class="text-end mt-3">
                    <button class="btn btn-primary btn-block w-100" type="submit" id="send-otp-btn">
                      <i class="fa fa-spinner fa-spin btn-loader"></i> Send OTP
                    </button>
                  </div>
                </div>

                <p class="mt-4 mb-0 text-center">Already have an account?<a class="ms-2"
                    href="<?php echo e(route('admin.login')); ?>">Sign in</a></p>
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
      $(document).ready(function () {
        $('.theme-form').on('submit', function () {
          var btn = $('#send-otp-btn');
          if (!btn.hasClass('loading')) {
            btn.addClass('loading');
            btn.css('pointer-events', 'none');
          }
        });
      });
    </script>
  <?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\auth\passwords\email.blade.php ENDPATH**/ ?>