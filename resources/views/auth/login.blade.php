@extends('layouts.site')
@section('title', 'Ingresar')

@section('content')
<div class="container-fluid go-wallpaper">
  <div class="row">
    <div class="col-md-5 text-left logo-lateral">
      <img class="logo-lateral-img" src="{{ asset('img/site/logo-md.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
      <img class="logo-banner-img" src="{{ asset('img/site/logo-sm-slim.gif') }}" title="{{ __('Go') }}" alt="{{ __('Go') }}">
    </div>
    <div class="col-md-7">
      <div class="row">
        <div class="col-xs-2 col-sm-10 col-md-12 col-lg-10 col-xl-8  offset-sm-1 offset-md-0 offset-lg-1 offset-xl-2">
          <div class="card login login-cont">
            <div class="card-header">{{ __('Login') }}</div>

            <div class="card-body">
              <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group row">
                  <label for="email" class="col-md-5 col-form-label text-md-right">{{ __('Correo E.') }}</label>

                  <div class="col-md-7">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>

                <div class="form-group row">
                  <label for="password" class="col-md-5 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                  <div class="col-md-7">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                    @error('password')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>

            <!-- <div class="form-group row">
                  <div class="col-md-6 offset-md-4">
                    <div class="form-check">
                      <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                      <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                      </label>
                    </div>
                  </div>
                </div> -->

                <div class="form-group row mb-0">
                  <div class="col-md-8 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                      {{ __('Login') }}
                    </button>
                    <!--
                    @if (Route::has('password.request'))
                      <a class="btn btn-link" href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                      </a>
                    @endif
                    -->
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script type="application/javascript">
  window.addEventListener('load', function() {
    go.redimentions(false);
  });
</script>
@endpush

@endsection
