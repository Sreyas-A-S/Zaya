@extends('layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="container-fluid p-0">
  <div class="row m-0">
    <div class="col-12 p-0">
      <div class="login-card">
        <div>
          <div class="text-center"><a class="logo" href="{{ route('admin.login') }}"><img class="img-fluid for-dark d-block m-auto" src="{{ asset('admiro/assets/images/logo/zaya-logo-admin.svg') }}" alt="logo" style="height: 100px;"></a></div>

          <div class="login-main">
            <form class="theme-form" method="POST" action="{{ route('admin.forgot-password.reset.update') }}">
              @csrf
              <h2 class="text-center">Reset Password</h2>
              <p class="text-center">Enter your new password for: <strong>{{ session('reset_email') }}</strong></p>

              <div class="form-group">
                <label class="col-form-label">New Password</label>
                <div class="form-input position-relative">
                  <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" id="password" required placeholder="*********" autocomplete="new-password">
                  <div class="show-hide" onclick="togglePasswordVisibility('password', this)"><span class="show"></span></div>
                  @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                
                <!-- Password Requirements UI -->
                <div class="password-requirements mt-2 px-1">
                  <p class="text-muted mb-1 small" style="font-size: 11px;">Password must contain:</p>
                  <ul class="list-unstyled mb-0" style="font-size: 11px;">
                    <li id="req-length" class="text-muted d-flex align-items-center gap-1">
                      <i class="fa fa-circle-o"></i> At least 8 characters
                    </li>
                    <li id="req-upper" class="text-muted d-flex align-items-center gap-1">
                      <i class="fa fa-circle-o"></i> One uppercase letter
                    </li>
                    <li id="req-lower" class="text-muted d-flex align-items-center gap-1">
                      <i class="fa fa-circle-o"></i> One lowercase letter
                    </li>
                    <li id="req-number" class="text-muted d-flex align-items-center gap-1">
                      <i class="fa fa-circle-o"></i> One number
                    </li>
                    <li id="req-match" class="text-muted d-flex align-items-center gap-1">
                      <i class="fa fa-circle-o"></i> Passwords match
                    </li>
                  </ul>
                </div>
              </div>

              <div class="form-group">
                <label class="col-form-label">Confirm New Password</label>
                <div class="form-input position-relative">
                  <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required placeholder="*********" autocomplete="new-password">
                  <div class="show-hide" onclick="togglePasswordVisibility('password_confirmation', this)"><span class="show"></span></div>
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

  .password-requirements i {
    font-size: 8px;
    width: 12px;
  }
  
  .requirement-met {
    color: #2e4b3d !important;
    font-weight: 500;
  }
  
  .requirement-met i {
    color: #2e4b3d;
  }

  .requirement-met i:before {
    content: "\f058"; /* fa-check-circle */
    font-size: 11px;
  }

  /* Override template default for show-hide to handle our custom logic */
  .show-hide {
    cursor: pointer;
    display: block !important;
  }
</style>

@section('scripts')
<script>
  $(document).ready(function() {
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    const requirements = {
      length: document.getElementById('req-length'),
      upper: document.getElementById('req-upper'),
      lower: document.getElementById('req-lower'),
      number: document.getElementById('req-number'),
      match: document.getElementById('req-match')
    };

    function updateRequirement(el, met) {
      if (met) {
        el.classList.add('requirement-met');
        el.classList.remove('text-muted');
      } else {
        el.classList.remove('requirement-met');
        el.classList.add('text-muted');
      }
    }

    function validate() {
      const val = password.value;
      const val2 = confirmPassword.value;
      updateRequirement(requirements.length, val.length >= 8);
      updateRequirement(requirements.upper, /[A-Z]/.test(val));
      updateRequirement(requirements.lower, /[a-z]/.test(val));
      updateRequirement(requirements.number, /[0-9]/.test(val));
      updateRequirement(requirements.match, val === val2 && val.length > 0);
    }

    if (password) {
      password.addEventListener('input', validate);
    }
    if (confirmPassword) {
      confirmPassword.addEventListener('input', validate);
    }

    $('.theme-form').on('submit', function() {
      var btn = $('#reset-password-btn');
      if (!btn.hasClass('loading')) {
        btn.addClass('loading');
        btn.css('pointer-events', 'none');
      }
    });
  });
</script>
@endsection
@endsection
