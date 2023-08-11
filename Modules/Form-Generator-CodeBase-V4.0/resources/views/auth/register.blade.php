@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <a href="{{action('HomeController@index')}}" class="h1">
                        <b>
                            {{ config('app.name', 'Laravel') }}
                        </b>
                    </a>
                </div>
                <div class="card-body register-card-body">
                    <p class="login-box-msg">
                        {{ __('messages.register_a_new_membership') }}
                    </p>
                    <form method="POST"  action="{{action('RegistrationController@store')}}">
                        @csrf

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <div class="input-group mb-3">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="{{__('Name')}}">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                          <span class="fas fa-user"></span>
                                        </div>
                                      </div>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <div class="input-group mb-3">
                                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="{{ __('E-Mail Address') }}">
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
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <div class="input-group mb-3">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="{{ __('Password') }}">
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
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-3">
                                <div class="input-group mb-3">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="{{ __('Confirm Password') }}">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                          <span class="fas fa-lock"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($__enable_saas)
                            <div class="form-row">
                                @foreach($packages as $package)
                                    <div class="col-md-4">
                                        <label>
                                            <input type="radio" class="d-none" name="package_id" value="{{$package->id}}">
                                                <div class="card card-outline card-info on_hover pointer choosen_pack">
                                                    <div class="card-header">
                                                        <div class="text-center">
                                                            <h5>
                                                                {{$package->name}}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    <div class="card-body text-center">
                                                        @if($package->no_of_active_forms != 0)
                                                            <span>
                                                            <i class="far fa-check-circle text-success"></i>
                                                            @lang('messages.no_of_forms',[
                                                                            'active_form' => $package->no_of_active_forms])
                                                                </span>
                                                        @else
                                                            <span>
                                                                <i class="far fa-check-circle text-success"></i>
                                                                @lang('messages.unlimited_forms')
                                                            </span>
                                                        @endif
                                                        <hr>
                                                        @if($package->is_form_downloadable)
                                                            <span>
                                                                <i class="far fa-check-circle text-success"></i>
                                                                @lang('messages.form_code_download')
                                                            </span>
                                                        @else
                                                            <span>
                                                                <i class="far fa-times-circle text-danger"></i>
                                                                @lang('messages.form_code_download')
                                                            </span>
                                                        @endif
                                                        <hr>
                                                        @php
                                                            $price_interval = __('messages.'.$package->price_interval);
                                                        @endphp
                                                        @if($package->price != 0)
                                                            <h5>
                                                                <span class="currency">
                                                                    {{$package->price}}
                                                                </span>
                                                                <small class="text-muted">
                                                                    @lang('messages.subscription_price',[
                                                                        'interval' => $package->interval,
                                                                        'price_interval' => $price_interval
                                                                    ])
                                                                </small>
                                                            </h5>
                                                        @else
                                                            <h5>
                                                            @lang('messages.free_for_interval', [
                                                                'interval' => $package->interval,
                                                                'price_interval' => $price_interval
                                                            ])
                                                            </h5>
                                                        @endif
                                                    </div>
                                                    <div class="card-footer text-center">
                                                        {{$package->description}}
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    @if($loop->iteration%3 == 0)
                                        <div class="clearfix"></div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-3">
                                <button type="submit" class="btn btn-block btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <p class="text-center mt-3">
                        - {{ __('OR') }} -
                        <br>
                        <a href="{{ route('login') }}" class="btn btn-link">
                           {{ __('messages.already_have_an_account') }} <b>{{ __('Login') }}</b>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footer')
<script type="text/javascript">
    $(document).ready(function(){
        $('span.currency').each(function(index, element){
            var money = __formatCurrency($(this).text());
            $(this).text(money);
        });

        $(document).on('click', '.choosen_pack', function(){
            $('.choosen_pack').removeClass('border-success subscribed');
            $(this).closest('.card').addClass('border-success subscribed');
        });
    });
</script>
@endsection
