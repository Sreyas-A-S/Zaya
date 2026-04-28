@extends('layouts.auth')

@section('title', 'Set Password')

@section('content')
<div class="container-fluid p-0">
  <div class="row m-0">
    <div class="col-12 p-0">
      <div class="login-card">
        <div>
          <div class="text-center"><a class="logo" href="{{ route('login') }}"><img class="img-fluid for-dark d-block m-auto" src="{{ asset('admiro/assets/images/logo/zaya-logo-admin.svg') }}" alt="logo" style="height: 100px;"></a></div>

          <div class="login-main">
            <form class="theme-form" method="POST" action="{{ route('set-password.update') }}">
              @csrf
              <input type="hidden" name="email" value="{{ $email }}">
              <input type="hidden" name="token" value="{{ $token }}">

              <h2 class="text-center">Create Password</h2>
              <p class="text-center">Set a password for: <strong>{{ $email }}</strong></p>

              <div class="form-group">
                <label class="col-form-label">New Password</label>
                <div class="form-input position-relative">
                  <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" id="password" required placeholder="*********">
                  <div class="show-hide-independent"><span class="show"> </span></div>
                  @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
                
                <div id="password-requirements" class="mt-3 ps-2 space-y-1">
                    <div class="d-flex align-items-center gap-2 small text-muted transition-colors" id="req-length" style="font-size: 11px; font-weight: 600; text-transform: uppercase;">
                        <i class="fa fa-check-circle"></i>
                        <span>8+ Characters</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 small text-muted transition-colors" id="req-upper" style="font-size: 11px; font-weight: 600; text-transform: uppercase;">
                        <i class="fa fa-check-circle"></i>
                        <span>Uppercase</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 small text-muted transition-colors" id="req-lower" style="font-size: 11px; font-weight: 600; text-transform: uppercase;">
                        <i class="fa fa-check-circle"></i>
                        <span>Lowercase</span>
                    </div>
                    <div class="d-flex align-items-center gap-2 small text-muted transition-colors" id="req-special" style="font-size: 11px; font-weight: 600; text-transform: uppercase;">
                        <i class="fa fa-check-circle"></i>
                        <span>Number or Special</span>
                    </div>
                </div>

                <div id="password-strength-indication" class="mt-3 ps-2 d-none">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span id="strength-text" style="font-size: 10px; font-weight: 700; text-transform: uppercase;"></span>
                    </div>
                    <div class="progress" style="height: 4px; background-color: #f0f0f0; border-radius: 10px; overflow: hidden;">
                        <div id="strength-bar" class="progress-bar transition-all duration-300" role="progressbar" style="width: 0%; transition: width 0.3s ease;"></div>
                    </div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-form-label">Confirm New Password</label>
                <div class="form-input position-relative">
                  <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required placeholder="*********">
                  <div class="show-hide-independent"><span class="show"> </span></div>
                </div>
                <div id="password-match-indication" class="mt-2 ps-2 d-none">
                    <div class="d-flex align-items-center gap-2">
                        <i id="match-icon" class="fa"></i>
                        <span id="match-text" style="font-size: 11px; font-weight: 600;"></span>
                    </div>
                </div>
              </div>

              <div class="form-group mb-0">
                <div class="text-end mt-3">
                  <button class="btn btn-primary btn-block w-100" type="submit" id="set-password-btn">
                    <i class="fa fa-spinner fa-spin btn-loader"></i> Set Password
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

  /* Custom positioning for independent show-hide to replace template broken script */
  .show-hide-independent {
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    display: block;
    cursor: pointer;
    z-index: 10;
  }
  
  .show-hide-independent span {
    font-size: 13px;
    font-weight: 500;
    color: #8B3A8A; /* Matches Zaya theme color */
    text-transform: lowercase;
  }
  
  .show-hide-independent span.show:before {
    content: "show";
  }
  
  .show-hide-independent span:not(.show):before {
    content: "hide";
  }
</style>

@section('scripts')
<script>
  $(document).ready(function() {
    // Independent show/hide logic for multiple fields
    $('.show-hide-independent span').on('click', function() {
      var input = $(this).closest('.form-input').find('input');
      if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        $(this).removeClass('show');
      } else {
        input.attr('type', 'password');
        $(this).addClass('show');
      }
    });

    // Password Validation Logic
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const requirementsUI = document.getElementById('password-requirements');
    const strengthIndication = document.getElementById('password-strength-indication');
    const matchIndication = document.getElementById('password-match-indication');
    
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;
        
        // Strength indicators
        const reqLength = document.getElementById('req-length');
        const reqUpper = document.getElementById('req-upper');
        const reqLower = document.getElementById('req-lower');
        const reqSpecial = document.getElementById('req-special');
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        
        // Match indicators
        const matchIcon = document.getElementById('match-icon');
        const matchText = document.getElementById('match-text');

        if (password.length > 0) {
            requirementsUI.classList.remove('d-none');
            strengthIndication.classList.remove('d-none');
            
            let strength = 0;
            
            // 1. Length
            if (password.length >= 8) {
                strength += 25;
                reqLength.classList.remove('text-muted');
                reqLength.classList.add('text-success');
            } else {
                reqLength.classList.add('text-muted');
                reqLength.classList.remove('text-success');
            }

            // 2. Uppercase
            if (/[A-Z]/.test(password)) {
                strength += 25;
                reqUpper.classList.remove('text-muted');
                reqUpper.classList.add('text-success');
            } else {
                reqUpper.classList.add('text-muted');
                reqUpper.classList.remove('text-success');
            }

            // 3. Lowercase
            if (/[a-z]/.test(password)) {
                strength += 25;
                reqLower.classList.remove('text-muted');
                reqLower.classList.add('text-success');
            } else {
                reqLower.classList.add('text-muted');
                reqLower.classList.remove('text-success');
            }

            // 4. Special or Number
            if (/[0-9]/.test(password) || /[\W_]/.test(password)) {
                strength += 25;
                reqSpecial.classList.remove('text-muted');
                reqSpecial.classList.add('text-success');
            } else {
                reqSpecial.classList.add('text-muted');
                reqSpecial.classList.remove('text-success');
            }

            strengthBar.style.width = strength + '%';
            if (strength <= 25) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthText.textContent = 'Weak';
                strengthText.className = 'small font-bold text-danger';
            } else if (strength <= 50) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthText.textContent = 'Fair';
                strengthText.className = 'small font-bold text-warning';
            } else if (strength <= 75) {
                strengthBar.className = 'progress-bar bg-info';
                strengthText.textContent = 'Good';
                strengthText.className = 'small font-bold text-info';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthText.textContent = 'Strong';
                strengthText.className = 'small font-bold text-success';
            }
        } else {
            strengthIndication.classList.add('d-none');
            requirementsUI.classList.add('d-none');
        }

        // Match Logic
        if (confirmPassword.length > 0) {
            matchIndication.classList.remove('d-none');
            if (password === confirmPassword) {
                matchIcon.className = 'fa fa-check-circle text-success';
                matchText.textContent = 'Passwords match';
                matchText.className = 'small font-bold text-success';
                $(confirmPasswordInput).removeClass('is-invalid').addClass('is-valid');
            } else {
                matchIcon.className = 'fa fa-times-circle text-danger';
                matchText.textContent = 'Passwords do not match';
                matchText.className = 'small font-bold text-danger';
                $(confirmPasswordInput).removeClass('is-valid').addClass('is-invalid');
            }
        } else {
            matchIndication.classList.add('d-none');
            $(confirmPasswordInput).removeClass('is-valid is-invalid');
        }
    }

    if (passwordInput && confirmPasswordInput) {
        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    }

    // Form submission loader
    $('.theme-form').on('submit', function() {
      var btn = $('#set-password-btn');
      if (!btn.hasClass('loading')) {
        btn.addClass('loading');
        btn.css('pointer-events', 'none');
      }
      
      // Reset password types to password before submission for security
      $('.form-input input').attr('type', 'password');
      $('.show-hide-independent span').addClass('show');
    });
  });
</script>
@endsection
@endsection

