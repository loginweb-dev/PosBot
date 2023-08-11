<?php

namespace App\Http\Controllers;

use App\Package;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function store(Request $request)
    {
        $validator = $request->validate(
            [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                ]
            );

        $user = $request->only(['name','email']);

        if (!empty($request->input('password'))) {
            $user['password'] = bcrypt($request->input('password'));
        }

        $package_id = $request->get('package_id', null);

        $user = User::create($user);
        Auth::login($user);

        if (is_saas_enabled() && config('app.env') != 'demo' && !empty($package_id)) {
            $package = Package::find($package_id);
            if (!empty($package)) {
                return redirect()->action(
                    'Superadmin\SubscriptionPaymentController@subscriptionPay',
                    ['package_id' => $package_id]
                );
            }
        }

        return redirect()->route('home');
    }
}
