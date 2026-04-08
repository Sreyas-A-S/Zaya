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
                  <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" required placeholder="*********">
                  <div class="show-hide-independent"><span class="show"> </span></div>
                  @error('password')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                  @enderror
                </div>
              </div>

              <div class="form-group">
                <label class="col-form-label">Confirm New Password</label>
                <div class="form-input position-relative">
                  <input class="form-control" type="password" name="password_confirmation" required placeholder="*********">
                  <div class="show-hide-independent"><span class="show"> </span></div>
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

