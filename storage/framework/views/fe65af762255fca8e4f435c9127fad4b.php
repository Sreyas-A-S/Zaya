<?php $__env->startSection('title', 'Register as ' . ucfirst($type)); ?>

<?php $__env->startSection('content'); ?>
<!-- login page start-->
<div class="container-fluid p-0">
  <div class="row m-0">
    <div class="col-12 p-0">    
      <div class="login-card login-dark">
        <div>
          <div class="text-center"><a class="logo" href="<?php echo e(route('login')); ?>"><img class="img-fluid for-light m-auto d-block" src="<?php echo e(asset('admiro/assets/images/logo/zaya wellness logo white.svg')); ?>" alt="looginpage" style="max-height: 60px;"><img class="img-fluid for-dark d-block m-auto" src="<?php echo e(asset('admiro/assets/images/logo/zaya wellness logo white.svg')); ?>" alt="logo" style="max-height: 60px;"></a></div>
          <div class="login-main"> 
            <form class="theme-form" method="POST" action="<?php echo e(route('register')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="role" value="<?php echo e($type === 'patient' ? 'client' : $type); ?>">
              <h2 class="text-center">Create <?php echo e(ucfirst($type)); ?> Account</h2>
              <p class="text-center">Enter your personal details to create account</p>
              <div class="form-group">
                <label class="col-form-label pt-0">Your Name <span class="text-danger">*</span></label>
                <input class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text" name="name" value="<?php echo e(old('name')); ?>" required autocomplete="name" autofocus placeholder="Full name">
                <?php $__errorArgs = ['name'];
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
                <label class="col-form-label">Email Address <span class="text-danger">*</span></label>
                <input class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="email" name="email" value="<?php echo e(old('email')); ?>" required autocomplete="email" placeholder="Test@gmail.com">
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
                <label class="col-form-label">Password <span class="text-danger">*</span></label>
                <div class="form-input position-relative">
                  <input class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="password" name="password" id="password" required autocomplete="new-password" placeholder="*********"
                      minlength="8" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}"
                      oninput="validateRegisterPassword()">
                  <div class="show-hide"><span class="show"></span></div>
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
                <div id="password-requirements" class="text-danger small mt-1">Password must contain at least 8 characters, one uppercase, one lowercase, one number and one special character.</div>
              </div>
              <div class="form-group">
                <label class="col-form-label">Confirm Password <span class="text-danger">*</span></label>
                <div class="form-input position-relative">
                  <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" placeholder="*********" minlength="8" oninput="validateRegisterPassword()">
                </div>
                <div id="password-match-error" class="text-danger small mt-1 d-none">Passwords do not match.</div>
              </div>
              <div class="form-group mb-0 checkbox-checked">
                <div class="form-check checkbox-solid-info">
                  <input class="form-check-input" id="solid6" type="checkbox" required>
                  <label class="form-check-label" for="solid6">Agree with</label><a class="ms-3 link" href="#">Privacy Policy</a>
                </div>
                <button class="btn btn-primary btn-block w-100 mt-3" type="submit">Create Account</button>
              </div>
              <p class="mt-4 mb-0 text-center">Already have an account?<a class="ms-2" href="<?php echo e(route('login')); ?>">Sign in</a></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
    function validateRegisterPassword() {
        const password = document.getElementById('password');
        const confirm = document.getElementById('password_confirmation');
        const requirements = document.getElementById('password-requirements');
        const matchError = document.getElementById('password-match-error');
        
        const pattern = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@$!%*?&]).{8,}$/;
        
        // Check requirements
        if (password.value === '') {
            requirements.classList.remove('d-none');
        } else if (pattern.test(password.value)) {
            requirements.classList.add('d-none');
        } else {
            requirements.classList.remove('d-none');
        }
        
        // Check match
        if (confirm.value !== '') {
            if (confirm.value !== password.value) {
                matchError.classList.remove('d-none');
                confirm.classList.add('is-invalid');
            } else {
                matchError.classList.add('d-none');
                confirm.classList.remove('is-invalid');
            }
        } else {
            matchError.classList.add('d-none');
            confirm.classList.remove('is-invalid');
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.auth', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\wamp64\www\zaya\resources\views\auth\register.blade.php ENDPATH**/ ?>