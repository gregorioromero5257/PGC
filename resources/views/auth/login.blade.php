@extends('layouts.app')

@section('content')
<div class="bg-login h-100 d-flex justify-content-center align-items-center">
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12 text-center mb-3">
                <!-- <h1 class="display-5 font-weight-light"> -->
                    <img class="logo-login" src="/img/Logo PGC.png" >
                    <!-- PGC -->
                    <!-- <span class="font-weight-bold">2.0</span> -->
                <!-- </h1> -->
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-10 col-lg-5">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email" class="text-md-right">{{ __('Correo Electrónico') }}</label>

                        <input id="email" type="email" class="form-control login-input form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="text-md-right">{{ __('Contraseña') }}</label>

                        <input id="password" type="password" class="form-control login-input form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label class="form-check-label" for="remember">
                                {{ __('Recordar') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-dark btn-block">
                            {{ __('Iniciar sesión') }}
                        </button>

                        <!-- <a href="{{ url('/redirect') }}" class="btn btn-google btn-block">
                            <img class="mr-2" src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg"/>
                            {{ __('Iniciar sesión con Google') }}
                        </a> -->
                    </div>

                    <div class="form-group text-center">
                        @if (Route::has('password.request'))
                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                {{ __('¿Olvidaste tu contraseña?') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
