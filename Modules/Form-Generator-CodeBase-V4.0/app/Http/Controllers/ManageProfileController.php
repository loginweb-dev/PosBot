<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ManageProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getProfile()
    {
        $user_id = request()->user()->id;

        $user = User::find($user_id);

        return view('user.profile.edit')
            ->with(compact('user'));
    }

    /**
     * Update the specified profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function postProfile(Request $request, $id)
    {
        $validate = validator($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
        ]);

        if ($validate->fails()) {
            return $this->respondWithError($validate->errors()->first());
        }

        try {

            //check for demo
            if ($this->isDemo()) {
                return $this->respondDemo();
            }
            
            if (request()->ajax()) {
                $input = request()->only('name', 'email');
                $password = request('password');

                if (!empty($password)) {
                    $input['password'] = bcrypt($password);
                }
                
                $user = User::findOrFail($id);

                $user->update($input);
                
                $dashboard_url['redirect'] = action('HomeController@index');
                $output = $this->respondSuccess(__('messages.success'), $dashboard_url);
            }
        } catch (Exception $e) {
            $output = $this->respondWentWrong($e);
        }

        return $output;
    }
}
