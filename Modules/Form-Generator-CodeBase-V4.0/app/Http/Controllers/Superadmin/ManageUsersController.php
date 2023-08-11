<?php

namespace App\Http\Controllers\Superadmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Yajra\DataTables\Facades\DataTables;
use App\Notifications\UserNotification;
use App\Form;
use App\UserForm;

class ManageUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        if (!auth()->user()->can('superadmin')) {
            abort(403, 'Unauthorized action.');
        }
        
        if ($request->ajax()) {
            $users = User::select('name', 'email', 'is_active', 'created_at', 'id')
                ->get();

            return DataTables::of($users)
                    ->addColumn(
                        'action',
                        '   
                            @if($is_active)
                                <span title="@lang("messages.mark_inactive")">
                                    <a class="btn btn-link btn-icon btn-sm text-danger toggle_is_active pointer" data-href="{{ action(\'Superadmin\ManageUsersController@toggleUserActiveStatus\', [$id])}}">
                                        <i class="fas fa-toggle-on font_icon_size"></i>
                                    </a>
                                </span>
                            @else
                                <span title="@lang("messages.mark_active")">
                                    <a class="btn btn-link btn-icon btn-sm text-success toggle_is_active pointer" data-href="{{ action(\'Superadmin\ManageUsersController@toggleUserActiveStatus\', [$id])}}">
                                        <i class="fas fa-toggle-off font_icon_size"></i>
                                    </a>
                                </span>
                            @endif
                            <a class="btn btn-link btn-icon btn-sm text-info edit_user pointer" data-href="{{ action(\'Superadmin\ManageUsersController@edit\', [$id])}}" title="@lang("messages.edit")">
                                    <i class="fas fa-edit font_icon_size"></i>
                            </a>
                            <a class="btn btn-link btn-icon btn-sm text-danger delete_user pointer" data-href="{{ action(\'Superadmin\ManageUsersController@destroy\', [$id])}}" title="@lang("messages.delete")">
                                    <i class="fas fa-trash-alt font_icon_size"></i>
                            </a>
                        '
                    )
                    ->editColumn(
                        'is_active',
                        '
                            @if($is_active)
                                <span class="badge badge-pill badge-success">
                                    <i class="far fa-check-circle"></i>
                                    @lang(\'messages.active\')
                                </span>
                            @else
                                <span class="badge badge-pill badge-danger">
                                    <i class="far fa-times-circle"></i>
                                    @lang(\'messages.inactive\')
                                </span>
                            @endif
                        '
                    )
                    ->editColumn(
                        'created_at',
                        '
                            @php
                                $date = \Carbon\Carbon::parse($created_at)->isoFormat("D/M/YY HH:mm A");
                            @endphp
                            {{$date}}
                        '
                    )
                    ->removeColumn('id')
                    ->rawColumns([ 'action', 'is_active', 'created_at'])
                    ->make(true);
            ;
        }

        return view('superadmin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (request()->ajax()) {

            $user = request()->user();

            $forms = Form::where('is_template', 0)
                        ->where('created_by', $user->id)
                        ->pluck('name', 'id')
                        ->toArray();

            return view('superadmin.users.create')
                ->with(compact('forms'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            if (!empty($this->notAllowedInDemo())) {
                return $this->notAllowedInDemo();
            }

            $input = $request->only('name', 'email', 'is_active', 'can_create_form');

            if (!empty($request->input('password'))) {
                $input['password'] = bcrypt($request->input('password'));
            }

            $input['is_active'] = !empty($input['is_active']) ? 1 : 0;
            $input['can_create_form'] = !empty($input['can_create_form']) ? 1 : 0;

            $user = User::create($input);

            //save user forms (assgined)
            $permissions = $request->input('permissions');
            $form_ids = $request->input('form_id');
            $user_forms = [];
            if (!empty($form_ids) && !empty($permissions)) {
                foreach ($form_ids as $key => $form_id) {
                    $user_forms[] = [
                            'form_id' => $form_id,
                            'assigned_by' => \Auth::id(),
                            'permissions' => $permissions
                        ];
                }
            }

            $user->userForms()->createMany($user_forms);

            if (!empty($request->input('send_email'))) {
                $input['password'] = $request->input('password');
                $user->notify(new UserNotification($input));
            }

            $output = $this->respondSuccess();
        } catch (Exception $e) {
            $output = $this->respondWentWrong($e);
        }

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (request()->ajax()) {
            $user = User::findOrFail($id);

            $logged_in_user = request()->user();

            $forms = Form::where('is_template', 0)
                        ->where('created_by', $logged_in_user->id)
                        ->pluck('name', 'id')
                        ->toArray();

            $assigned_forms = UserForm::with('form')
                            ->where('assigned_by', \Auth::id())
                            ->where('assigned_to', $id)
                            ->get();

            return view('superadmin.users.edit')
                ->with(compact('user', 'forms', 'assigned_forms'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            if (!empty($this->notAllowedInDemo())) {
                return $this->notAllowedInDemo();
            }

            $input = $request->only('name', 'email', 'is_active', 'can_create_form');
            $input['is_active'] = !empty($input['is_active']) ? 1 : 0;
            $input['can_create_form'] = !empty($input['can_create_form']) ? 1 : 0;

            if (!empty($request->input('password'))) {
                $input['password'] = bcrypt($request->input('password'));
            }

            $user = User::findOrFail($id);
            $user->update($input);

            //update user forms (assgined)
            $edit_permissions = $request->input('edit_permissions');
            $assgined_form_ids = $request->input('edit_assigned_form_id');
            if (!empty($assgined_form_ids)) {
                $non_existing_ids = [];
                foreach ($assgined_form_ids as $key => $id) {
                    if (!empty($edit_permissions[$id])) {
                        $user_form = UserForm::find($id);
                        $user_form->permissions = $edit_permissions[$id];
                        $user_form->save();
                    } else {
                        $non_existing_ids[] = $id;
                    }
                }

                UserForm::whereIn('id', $non_existing_ids)
                    ->delete();
            }

            //save user forms (assgined)
            $permissions = $request->input('permissions');
            $form_ids = $request->input('form_id');
            $user_forms = [];
            if (!empty($form_ids) && !empty($permissions)) {
                foreach ($form_ids as $key => $form_id) {
                    $user_forms[] = [
                            'form_id' => $form_id,
                            'assigned_by' => \Auth::id(),
                            'permissions' => $permissions
                        ];
                }
            }

            $user->userForms()->createMany($user_forms);

            if (!empty($request->input('send_email'))) {
                $input['password'] = $request->input('password');
                $user->notify(new UserNotification($input));
            }

            $output = $this->respondSuccess();
        } catch (Exception $e) {
            $output = $this->respondWentWrong($e);
        }

        return $output;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if (request()->ajax()) {

                if (!empty($this->notAllowedInDemo())) {
                    return $this->notAllowedInDemo();
                }

                $user = User::findOrFail($id);

                if (\Auth::id() != $user->id) {
                    $user->userForms()->delete();
                    $user->delete();
                    $output = $this->respondSuccess();
                } else {
                    $output = $this->respondWithError(__('messages.something_went_wrong'));        
                }
            }
        } catch (Exception $e) {
            $output = $this->respondWentWrong($e);
        }

        return $output;
    }

    /**
     * toggle users status(active/inactive)
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleUserActiveStatus($id)
    {
        try {
            if (request()->ajax()) {
                if (!empty($this->notAllowedInDemo())) {
                    return $this->notAllowedInDemo();
                }

                $user = User::findOrFail($id);

                if (\Auth::id() != $user->id) {

                    $user->is_active = !$user->is_active;
                    $user->save();

                    $output = $this->respondSuccess();
                } else {
                    $output = $this->respondWithError(__('messages.something_went_wrong'));        
                }
            }
        } catch (Exception $e) {
            $output = $this->respondWentWrong($e);
        }
        return $output;
    }

    /**
     * check if email exist or not
     *
     * @return \Illuminate\Http\Response
     */
    public function checkIfEmailExist(Request $request)
    {
        $email = $request->input('email');

        $query = User::where('email', $email);

        if (!empty($request->input('user_id'))) {
            $user_id = $request->input('user_id');
            $query->where('id', '!=', $user_id);
        }

        $exists = $query->exists();
        if (!$exists) {
            echo "true";
            exit;
        } else {
            echo "false";
            exit;
        }
    }
}
