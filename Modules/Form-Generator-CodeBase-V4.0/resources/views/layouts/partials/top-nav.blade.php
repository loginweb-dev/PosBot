<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-light @guest navbar-laravel @else navbar-white @endguest">
    <div class="container">
        @auth
        <a href="{{action('HomeController@index')}}" class="navbar-brand">
            <!-- <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
             style="opacity: .8"> -->
            <span class="brand-text font-weight-light">{{ config('app.name', 'Laravel') }}</span>
        </a>
        <ul class="navbar-nav">
            <li class="nav-item d-none d-sm-inline-block">
                <a class="nav-link" href="{{ action('HomeController@index') }}">
                <i class="fas fa-home"></i>
                {{ __('messages.home') }}</a>
            </li>
        </ul>
        @endauth

        <!-- Right Side Of Navbar -->
        <ul class="navbar-nav ml-auto">
            <!-- Authentication Links -->
            @guest
                <li class="nav-item d-none d-sm-inline-block">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
            @else
                <!-- superadmin menu -->
                @php
                    $superadmin_emails = env('SUPERADMIN_EMAILS');
                    $email_array = explode(',', $superadmin_emails);
                @endphp
                @if(in_array(Auth::user()->email, $email_array))
                    <li class="nav-item dropdown">
                        <a id="superadminDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
                            <i class="fas fa-universal-access"></i>
                            @lang('messages.superadmin')
                        </a>

                        <ul aria-labelledby="superadminDropdown" class="dropdown-menu border-0 shadow">
                            <li>
                                @if($__enable_saas)
                                    <a href="{{action('Superadmin\PackageController@index')}}" class="dropdown-item">
                                        <i class="fas fa-money-check"></i>
                                        @lang('messages.packages')
                                    </a>
                                    <a href="{{action('Superadmin\PackageSubscriptionsController@index')}}" class="dropdown-item">
                                        <i class="fas fa-sync"></i>
                                        @lang('messages.package_subscription')
                                    </a>
                                @endif
                                <a href="{{action('Superadmin\ManageUsersController@index')}}" class="dropdown-item">
                                    <i class="fas fa-users"></i>
                                    @lang('messages.users')
                                </a>
                                <a class="dropdown-item" href="{{action('Superadmin\SuperadminSettingsController@create')}}">
                                    <i class="fa fa-cogs"></i>
                                    @lang('messages.system_settings')
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif
                <!-- /superadmin menu -->
                
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">
                        <i class="fas fa-user-tie"></i>
                        {{ ucfirst(Auth::user()->name) }} 
                    </a>

                    <ul aria-labelledby="navbarDropdown" class="dropdown-menu border-0 shadow">
                        <li>
                            <a class="dropdown-item" href="{{action('ManageProfileController@getProfile')}}">
                                <i class="fas fa-user-edit"></i>
                                @lang('messages.profile')
                            </a>
                            @if($__enable_saas)
                                <a class="dropdown-item" href="{{action('SubscriptionsController@index')}}">
                                    <i class="fas fa-sync"></i>
                                    @lang('messages.my_subscription')
                                </a>
                            @endif
                            <a class="dropdown-item" href="{{action('ManageSettingsController@getSettings')}}">
                                <i class="fas fa-user-cog"></i>
                                @lang('messages.my_settings')
                            </a>
                            <a class="dropdown-item" 
                                href="{{ route('logout') }}"
                                onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i>
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>
                </li>
            @endguest
        </ul>

      <!-- Left navbar links -->
        <!-- <ul class="navbar-nav">
            <li class="nav-item">
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="index3.html" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="#" class="nav-link">Contact</a>
            </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Help
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="#">FAQ</a>
            <a class="dropdown-item" href="#">Support</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Contact</a>
          </div>
        </li>
      </ul> -->

    </div>
</nav>
<!-- /.navbar -->