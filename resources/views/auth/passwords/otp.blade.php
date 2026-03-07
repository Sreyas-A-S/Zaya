@extends('layouts.auth')

@section('title', 'Verify OTP')

@section('content')
<div class="container-fluid p-0">
  <div class="row m-0">
    <div class="col-12 p-0">
      <div class="login-card">
        <div>
          <div class="text-center"><a class="logo" href="{{ route('admin.login') }}"><img class="img-fluid for-dark d-block m-auto" src="{{ asset('admiro/assets/images/logo/zaya-logo-admin.svg') }}" alt="logo" style="height: 100px;"></a></div>

          <div class="login-main">
            <form class="theme-form" method="POST" action="{{ route('admin.forgot-password.otp.verify') }}">
              @csrf
              <h2 class="text-center">Verify OTP</h2>
              <p class="text-center">Enter the 6-digit OTP sent to your email: <strong>{{ session('reset_email') }}</strong></p>

              @if(session('status'))
              <div class="alert alert-success text-center py-2" role="alert">
                {{ session('status') }}
              </div>
              @endif

              @if(session('error'))
              <div class="alert alert-danger text-center py-2" role="alert">
                {{ session('error') }}
              </div>
              @endif

              <div class="form-group">
                <label class="col-form-label">Enter OTP</label>
                <input class="form-control @error('otp') is-invalid @enderror" type="text" name="otp" required autofocus placeholder="123456" maxlength="6">
                @error('otp')
                <span class="invalid-feedback" role="alert">
                  <strong>{{ $message }}</strong>
                </span>
                @enderror
              </div>

              <div class="form-group mb-0">
                <div class="text-end mt-3">
                  <button class="btn btn-primary btn-block w-100" type="submit" id="verify-otp-btn">
                    <i class="fa fa-spinner fa-spin btn-loader"></i> Verify OTP
                  </button>
                </div>
              </div>

              <p class="mt-4 mb-0 text-center">Didn't receive the OTP?<a class="ms-2" href="{{ route('admin.forgot-password.show') }}">Try again</a></p>
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
      var btn = $('#verify-otp-btn');
      if (!btn.hasClass('loading')) {
        btn.addClass('loading');
        btn.css('pointer-events', 'none');
      }
    });
  });
</script>
@endsection
@endsection
