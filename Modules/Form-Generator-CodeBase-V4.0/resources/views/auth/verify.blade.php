@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="login-box">
                <div class="card">
                    <div class="card-header text-center">
                        <a href="{{action('HomeController@index')}}" class="h1">
                            <b>{{ config('app.name', 'Laravel') }}</b>
                        </a>
                    </div>
                    <div class="card-body login-card-body">
                        @if (session('resent'))
                            <div class="alert alert-success" role="alert">
                                {{ __('A fresh verification link has been sent to your email address.') }}
                            </div>
                        @endif
                        <p class="login-box-msg">
                            {{ __('Verify Your Email Address') }}
                        </p>

                        {{ __('Before proceeding, please check your email for a verification link.') }}
                        {{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
