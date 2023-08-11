<?php

namespace App\Http\Controllers;

use App\UserSetting;
use DateTimeZone;
use Illuminate\Http\Request;

class ManageSettingsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getSettings()
    {
        $user_id = request()->user()->id;

        $settings = UserSetting::where('user_id', $user_id)
                        ->first();
                        
        if (empty($settings)) {
            $settings['language'] = config('app.locale');
            $settings['timezone'] = config('app.timezone');
            $settings['smtp'] = [
                            'MAIL_HOST' =>  '',
                            'MAIL_PORT' => '',
                            'MAIL_USERNAME' => '',
                            'MAIL_PASSWORD' => '',
                            'MAIL_ENCRYPTION' => '',
                            'MAIL_FROM_ADDRESS' => '',
                            'MAIL_FROM_NAME' => '',
                        ];
        }

        $settings['languages'] = config('constants.langs');
        $settings['timezones'] = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        return view('user.settings.edit')
            ->with(compact('settings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postSettings(Request $request)
    {
        try {
            if ($request->ajax()) {
                $input = $request->only('language', 'timezone');
                $input['smtp'] = $request->only('MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME', 'MAIL_PASSWORD', 'MAIL_ENCRYPTION', 'MAIL_FROM_ADDRESS', 'MAIL_FROM_NAME');
                $input['user_id'] = $request->user()->id;

                $setting = UserSetting::where('user_id', $input['user_id'])->first();

                if (empty($setting)) {
                    UserSetting::create($input);
                } else {
                    $setting->smtp = $input['smtp'];
                    $setting->language = $input['language'];
                    $setting->timezone = $input['timezone'];
                    $setting->save();
                }

                $dashboard_url['redirect'] = action('HomeController@index');
                $output = $this->respondSuccess(null, $dashboard_url);
            }
        } catch (Exception $e) {
            $output = $this->respondWentWrong($e);
        }
        return $output;
    }
}
