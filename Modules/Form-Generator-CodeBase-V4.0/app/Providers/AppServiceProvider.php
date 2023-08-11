<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {   
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        
        //force https
        $url = parse_url(config('app.url'));
        if($url['scheme'] == 'https'){
           \URL::forceScheme('https');
        }
        
        Schema::defaultStringLength(191);

        View::share('asset_version', Config('constants.asset_version'));

        // Using Closure based composers...
        View::composer(['layouts.partials.top-nav',
                        'superadmin.settings.create',
                        'auth.register', 'user.subscription.index'
                    ], function ($view) {
                        $view->with('__type_e', Cache::get('type_e', false));
                        $view->with('__enable_saas', is_saas_enabled());
                    });
    }
}
