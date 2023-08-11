<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SetDefaultConfigForUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if (!empty($user->settings['timezone'])) {
            config(['app.timezone' => $user->settings['timezone']]);
        }

        if (!empty($user->settings['language'])) {
            config(['app.locale' => $user->settings['language']]);
        }

        return $next($request);
    }
}
