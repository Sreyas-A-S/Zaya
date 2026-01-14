@extends('layouts.auth')

@section('title', 'Register as ' . ucfirst($type))

@section('content')
<!-- login page start-->
<div class="container-fluid p-0">
  <div class="row m-0">
    <div class="col-12 p-0">    
      <div class="login-card login-dark">
        <div>
          <div class="text-center"><a class="logo" href="{{ route('login') }}"><img class="img-fluid for-light m-auto d-block" src="{{ asset('admiro/assets/images/logo/zaya wellness logo white.svg') }}" alt="looginpage" style="max-height: 60px;"><img class="img-fluid for-dark d-block m-auto" src="{{ asset('admiro/assets/images/logo/zaya wellness logo white.svg') }}" alt="logo" style="max-height: 60px;"></a></div>
          <div class="login-main"> 
            <form class="theme-form" method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="role" value="{{ $type === 'patient' ? 'client' : $type }}">
              <h2 class="text-center">Create {{ ucfirst($type) }} Account</h2>
              <p class="text-center">Enter your personal details to create account</p>
              <div class="form-group">
                <label class="col-form-label pt-0">Your Name</label>
                <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Full name">
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="form-group">
                <label class="col-form-label">Email Address</label>
                <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Test@gmail.com">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="form-group">
                <label class="col-form-label">Password</label>
                <div class="form-input position-relative">
                  <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="new-password" placeholder="*********">
                  <div class="show-hide"><span class="show"></span></div>
                  @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
              <div class="form-group">
                <label class="col-form-label">Confirm Password</label>
                <div class="form-input position-relative">
                  <input class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="*********">
                </div>
              </div>
              <div class="form-group mb-0 checkbox-checked">
                <div class="form-check checkbox-solid-info">
                  <input class="form-check-input" id="solid6" type="checkbox" required>
                  <label class="form-check-label" for="solid6">Agree with</label><a class="ms-3 link" href="#">Privacy Policy</a>
                </div>
                <button class="btn btn-primary btn-block w-100 mt-3" type="submit">Create Account</button>
              </div>
              <p class="mt-4 mb-0 text-center">Already have an account?<a class="ms-2" href="{{ route('login') }}">Sign in</a></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection