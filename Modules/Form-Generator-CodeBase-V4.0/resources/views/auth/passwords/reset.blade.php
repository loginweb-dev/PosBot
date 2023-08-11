@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="{{action('HomeController@index')}}" class="h1">
                        <b>
                            {{ config('app.name', 'Laravel') }}
                        </b>
                    </a>
                </div>
                <div class="card-body login-card-body">
                    <p class="login-box-msg">
                        {{ __('messages.password_reset_heading') }}
                    </p>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="input-group mb-3">
                            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('E-Mail Address') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                        </div>

                        <div class="social-auth-links text-center mb-3">
                            <button type="submit" class="btn btn-block btn-primary">
                                {{ __('Reset Password') }}
                            </button>
                        </div>
                    </form>
                    <p class="text-center mt-3">
                        - {{ __('OR') }} -
                        <br>
                        <a href="{{ route('login') }}" class="btn btn-link">
                           {{ __('messages.already_have_an_account') }} <b>{{ __('Login') }}</b>
                        </a>
                        @if (Route::has('register'))
                            <a class="btn btn-link" href="{{ route('register') }}">
                                {{ __('messages.dont_have_an_account') }} <b>@lang('messages.register')</b>
                            </a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
