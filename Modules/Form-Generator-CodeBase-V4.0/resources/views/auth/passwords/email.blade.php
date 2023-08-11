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
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <p class="login-box-msg">
                        {{ __('messages.request_password_heading') }}
                    </p>
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="input-group mb-3">
                            <input type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="{{ __('E-Mail Address') }}">
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
                        <div class="social-auth-links text-center mb-3">
                            <button type="submit" class="btn btn-block btn-primary">
                                {{ __('Send Password Reset Link') }}
                            </button>
                        </div>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
