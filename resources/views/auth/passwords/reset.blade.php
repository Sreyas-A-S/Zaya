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
                  <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" required placeholder="*********">
                  <div class="show-hide"><span class="show"> </span></div>
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

@section('scripts')
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
@endsection
@endsection
