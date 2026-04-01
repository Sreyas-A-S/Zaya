<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
  <!-- <style>
    html,*{
      border: 1px solid red;
    }
  </style> -->
  <!-- login page start-->
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
            <div class="text-center"><a class="logo" href="<?php echo e(route('home')); ?>"><img
                  class="img-fluid for-dark d-block m-auto"
                  src="<?php echo e(asset('admiro/assets/images/logo/zaya-logo-admin.svg')); ?>" alt="logo"
                  style="height: 100px;"></a></div>

            <div class="login-main">
              <form class="theme-form" method="POST" action="<?php echo e(route('admin.login.submit')); ?>">
                <?php echo csrf_field(); ?>
                <h2 class="text-center">Sign in to account</h2>
                <p class="text-center">Enter your email &amp; password to login</p>

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
                    value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus placeholder="Test@gmail.com">
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
                <div class="form-group">
                  <label class="col-form-label">Password</label>
                  <div class="form-input position-relative">
                    <input class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="password" name="password"
                      required autocomplete="current-password" placeholder="*********">
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
                <div class="form-group mb-0 checkbox-checked">
                  <div class="form-check checkbox-solid-info">
                    <input class="form-check-input" id="solid6" type="checkbox" name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="solid6">Remember Me</label>
                  </div>
                  <a class="link" href="<?php echo e(route('admin.forgot-password.show')); ?>">Forgot password?</a>
                  <div class="text-end mt-3">
                    <button class="btn btn-primary btn-block w-100" type="submit" id="sign-in-btn">
                      <i class="fa fa-spinner fa-spin btn-loader"></i> Sign in
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




  <!-- Registration Selection Modal -->
  <div class="modal fade" id="registerSelectionModal" tabindex="-1" aria-labelledby="registerSelectionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header border-0">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body pb-5">
          <div class="text-center mb-4">
            <h2>Join Us As</h2>
            <p class="text-muted">Please select how you would like to register</p>
          </div>

          <div class="row g-4 justify-content-center px-4">
            <div class="col-sm-6">
              <a href="<?php echo e(route('register.form', ['type' => 'practitioner'])); ?>" class="text-decoration-none">
                <div class="card h-100 border shadow-sm hover-card bg-light">
                  <div class="card-body p-4 text-center">
                    <div class="mb-3">
                      <svg class="stroke-icon text-primary" style="width: 48px; height: 48px;">
                        <use href="<?php echo e(asset('admiro/assets/svg/iconly-sprite.svg#Work')); ?>"></use>
                      </svg>
                    </div>
                    <h4 class="mb-2 text-dark">Practitioner</h4>
                    <p class="text-muted small">For healthcare professionals</p>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-sm-6">
              <a href="<?php echo e(route('register.form', ['type' => 'patient'])); ?>" class="text-decoration-none">
                <div class="card h-100 border shadow-sm hover-card bg-light">
                  <div class="card-body p-4 text-center">
                    <div class="mb-3">
                      <svg class="stroke-icon text-success" style="width: 48px; height: 48px;">
                        <use href="<?php echo e(asset('admiro/assets/svg/iconly-sprite.svg#Profile')); ?>"></use>
                      </svg>
                    </div>
                    <h4 class="mb-2 text-dark">Patient</h4>
                    <p class="text-muted small">For clients seeking care</p>
                  </div>
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    .hover-card {
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .hover-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
      border-color: var(--theme-default) !important;
    }

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
          var btn = $('#sign-in-btn');
          if (!btn.hasClass('loading')) {
            btn.addClass('loading');
            // Optional: disable button to prevent double submit
            // btn.prop('disabled', true); 
            // Note: Disabling button immediately might prevent form submit if not careful, 
            // but since this is 'submit' event, the form is already submitting.
            // However, aesthetically, we just want the animation.

            // If we disable it, sometimes the browser cancels the submit if the button triggered it.
            // Safer to just add class for visual effect. 
            // If we want to prevent double clicks, we can use a flag or pointer-events: none.
            btn.css('pointer-events', 'none');
          }
        });
      });
    </script>
  <?php $__env->stopSection(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\auth\login.blade.php ENDPATH**/ ?>