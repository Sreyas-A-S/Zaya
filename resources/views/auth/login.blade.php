@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<!-- login page start-->
<div class="container-fluid p-0">
  <div class="row m-0">
    <div class="col-12 p-0">    
      <div class="login-card login-dark">
        <div>
          <div class="text-center"><a class="logo" href="{{ route('login') }}"><img class="img-fluid for-dark d-block m-auto" src="{{ asset('admiro/assets/images/logo/zaya wellness logo white.svg') }}" alt="logo" style="max-height: 60px;"></a></div>
          <div class="login-main"> 
            <form class="theme-form" method="POST" action="{{ route('login') }}">
                @csrf
              <h2 class="text-center">Sign in to account</h2>
              <p class="text-center">Enter your email &amp; password to login</p>
              
              @if(session('error'))
                <div class="alert alert-danger text-center py-2" role="alert">
                    {{ session('error') }}
                </div>
              @endif
              <div class="form-group">
                <label class="col-form-label">Email Address</label>
                <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Test@gmail.com">
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="form-group">
                <label class="col-form-label">Password</label>
                <div class="form-input position-relative">
                  <input class="form-control @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password" placeholder="*********">
                  <div class="show-hide"><span class="show">                         </span></div>
                  @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
              <div class="form-group mb-0 checkbox-checked">
                <div class="form-check checkbox-solid-info">
                  <input class="form-check-input" id="solid6" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                  <label class="form-check-label" for="solid6">Remember password</label>
                </div><a class="link" href="#">Forgot password?</a>
                <div class="text-end mt-3">
                  <button class="btn btn-primary btn-block w-100" type="submit">Sign in                 </button>
                </div>
              </div>
              <p class="mt-4 mb-0 text-center">Don't have account?<a class="ms-2" href="#" data-bs-toggle="modal" data-bs-target="#registerSelectionModal">Create Account</a></p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Registration Selection Modal -->
<div class="modal fade" id="registerSelectionModal" tabindex="-1" aria-labelledby="registerSelectionModalLabel" aria-hidden="true">
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
                        <a href="{{ route('register.form', ['type' => 'practitioner']) }}" class="text-decoration-none">
                            <div class="card h-100 border shadow-sm hover-card bg-light">
                                <div class="card-body p-4 text-center">
                                    <div class="mb-3">
                                        <svg class="stroke-icon text-primary" style="width: 48px; height: 48px;">
                                            <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Work') }}"></use>
                                        </svg>
                                    </div>
                                    <h4 class="mb-2 text-dark">Practitioner</h4>
                                    <p class="text-muted small">For healthcare professionals</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-sm-6">
                        <a href="{{ route('register.form', ['type' => 'patient']) }}" class="text-decoration-none">
                            <div class="card h-100 border shadow-sm hover-card bg-light">
                                <div class="card-body p-4 text-center">
                                    <div class="mb-3">
                                        <svg class="stroke-icon text-success" style="width: 48px; height: 48px;">
                                            <use href="{{ asset('admiro/assets/svg/iconly-sprite.svg#Profile') }}"></use>
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
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
        border-color: var(--theme-default) !important;
    }
</style>
@endsection