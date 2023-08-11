<?php

namespace App\Http\Controllers;

use App\Form;
use App\User;
use App\UserForm;
use App\PackageSubscription;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            $subscription_info = [];
            if (!$this->isSubscribed($user)) {
                $subscription_info = $this->subscriptionExpired();
            } elseif (!$this->isQuotaAvailable($user->id)) {
                $subscription_info = $this->quotaNotAvailable();
            }

            $templates = Form::where('is_template', 1)
                            ->where('created_by', $user->id)
                            ->pluck('name', 'id')
                            ->toArray();

            return view('form.create')
                ->with(compact('templates', 'subscription_info'));
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
        $input = $request->only('name', 'description', 'slug');

        $template_id = $request->input('template_id');
        $id = $request->input('id');

        if (!empty($id) || !empty($template_id)) {
            $form_id = !empty($id) ? $id : $template_id;

            $input['schema'] = Form::find($form_id)->schema;
        }

        $input['created_by'] = request()->user()->id;
        $form = Form::create($input);

        return redirect()->action(
            'FormController@edit',
            ['id' => $form->id]
        );
    }

    /**
     * Display the specified resource.
     * This menthod is available publicly.
     *
     * @param  id_or_slug
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id_or_slug)
    {
        $form = Form::where('id', $id_or_slug)
                ->orWhere('slug', $id_or_slug)
                ->first();

        if (empty($form)) {
            abort(404);
        }

        //if token available get submitted data
        $token = request()->get('token');
        if (!empty($token)) {
            $form->load(['data' => function ($query) use ($token, $form) {
                $query->where('token', $token)
                    ->where('form_id', $form->id);
            }]);
        }

        $form_closed_msg = '';
        $is_form_closed = false;
        if (isset($form->schema['settings']['form_scheduling']['is_enabled']) && $form->schema['settings']['form_scheduling']['is_enabled']) {
            //getting form scheduling values
            $form_closed_msg = $form->schema['settings']['form_scheduling']['closed_msg'];
            $today = Carbon::now();
            $start_date_time = null;
            $end_date_time = Carbon::parse($form->schema['settings']['form_scheduling']['end_date_time']);

            //parse start date time if it is enabled set form to close by default
            if (isset($form->schema['settings']['form_scheduling']['start_date_time'])) {
                $is_form_closed = true;
                $start_date_time = Carbon::parse($form->schema['settings']['form_scheduling']['start_date_time']);
            }
            
            //check if form will be open
            if (((isset($start_date_time) && ($start_date_time->lessThanOrEqualTo($today))) && $end_date_time->greaterThanOrEqualTo($start_date_time))) {
                $is_form_closed = false;
            }

            //check if form will be close
            if ($end_date_time->lessThanOrEqualTo($today)) {
                $is_form_closed = true;
            }
        }

        if (!$this->checkIfUserIsFormCreatorOrSharedWith($form)) {
            $this->postVisitorsCount($request, $form->id);
        }

        $nav = false;
        $iframe_enabled = $request->get('iframe', false);
        return view('form.show')
            ->with(compact('form', 'nav', 'is_form_closed', 'form_closed_msg', 'iframe_enabled'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function edit(Form $form)
    {
        $user_id = request()->user()->id;
        $placeholder_img = asset('img/placeholder.png');

        //check subscription if form is not shared to user
        if (!$this->doUserHavePermission($form->id, 'can_design_form')) {
            if (!$this->isSubscribed(request()->user())) {
                return redirect('home')->with('status', $this->subscriptionExpired());
            }
        }

        //check permission if user is not a creator
        $has_permission = ($form->created_by != $user_id) ? $this->doUserHavePermission($form->id, 'can_design_form') : true;
        if (!$has_permission) {
            abort(404);
        }

        //if user is not creator then don't allow permission to save as template
        $save_as_template = ($form->created_by != $user_id) ? false : true;

        return view('form.edit')
            ->with(compact('form', 'placeholder_img', 'save_as_template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        try {
            $input = request()->only('name', 'description', 'slug');
            $form_data = [
                    'form' => request()->input('form'),
                    'emailConfig' => request()->input('email_config'),
                    'settings' => request()->input('settings'),
                    'additional_js_css' => request()->input('js_css'),
                    'conditional_fields' => request()->input('conditional_fields'),
                    'form_attributes' => request()->input('form_attributes')
                ];
            
            $is_template = request()->input('is_template');
            $input['schema'] = $form_data;
            
            $form = Form::find($id);
            
            $form->name = $input['name'];
            $form->slug = $input['slug'];
            $form->description = $input['description'];
            $form->schema = $input['schema'];
            $form->is_template = $is_template;
            $form->mailchimp_details = request()->input('mailchimp_details');
            $form->save();

            $data['redirect'] = action('HomeController@index');
            $data['preview'] = action('FormController@show', ['id' => $form->slug ?: $id]);
            $data['form_name'] = $input['name'];

            return $this->respondSuccess($message = null, $data);
        } catch (Exception $e) {
            return $this->respondWentWrong($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Form  $form
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!empty($this->notAllowedInDemo())) {
            return $this->notAllowedInDemo();
        }
        
        try {
            if (request()->ajax()) {
                $form = Form::findOrFail($id);

                $form->delete();
            }

            return $this->respondSuccess(__('messages.deleted_successfully'));
        } catch (Exception $e) {
            return $this->respondWentWrong($e);
        }
    }

    public function downloadCode($form_id)
    {
        if (!empty($this->notAllowedInDemo())) {
            return $this->notAllowedInDemo();
        }
        try {

            //check for form code download in subscription
            if (is_saas_enabled()) {
                $subscription = PackageSubscription::activeSubscription(request()->user()->id);
                if (!$this->isSubscribed(request()->user()) || isset($subscription->package_details['is_form_downloadable']) && !$subscription->package_details['is_form_downloadable']) {
                    die('Form code download not allowed');
                }
            }

            $form = Form::findOrFail($form_id);
            $form_slug = Str::slug($form->name);
        
            $validation_rules = [];
            $signature_elements = [];
            if (!empty($form->schema['form'])) {
                foreach ($form->schema['form'] as $key => $element) {
                    $rules = [];
                    foreach ($element['validations'] as $index => $validation) {
                        $rule = 'data-rule-' . $validation['rule'];
                        $error_msg = 'data-msg-' . $validation['rule'];
                        $is_rule_required = !empty($validation['value']) ? $validation['value'] : true;

                        $rules[$index]['rule'] = $rule;
                        $rules[$index]['error'] = $error_msg;
                        $rules[$index]['value'] = $is_rule_required;
                        $rules[$index]['msg'] = $validation['error_msg'];
                    }
                    $validation_rules[$key] = $rules;

                    //used in mail
                    if ($element['type'] == 'signature') {
                        $signature_elements[$element['name']] = $element['name'];
                    }
                }
            }

            //Generate html, CSS, JS
            $html = view('form.download')
                ->with(compact('form', 'form_slug', 'validation_rules'))
                ->render();
            
            //Generate PHP backend code.
            $this->GeneratorCommonPhp = new FormPhpGenerator();

            $form_php = $this->GeneratorCommonPhp->phpStart();

            //Check for recaptcha
            if ($form->schema['settings']['recaptcha']['is_enable']) {
                $form_php .= $this->GeneratorCommonPhp->reCaptcha($form->schema['settings']['recaptcha']['secret_key']);
            }

            $smtp = $form->schema['emailConfig']['smtp'];
            if (isset($form->schema['emailConfig']['email']['enable']) && $smtp['use_system_smtp'] == 1) {
                // my smtp settings
                $user = request()->user();
                $my_smtp_settings = $user->settings['smtp'];

                $smtp['host'] = $my_smtp_settings['MAIL_HOST'];
                $smtp['port'] = $my_smtp_settings['MAIL_PORT'];
                $smtp['from_address'] = $my_smtp_settings['MAIL_FROM_ADDRESS'];
                $smtp['from_name'] = $my_smtp_settings['MAIL_FROM_NAME'];
                $smtp['encryption'] = $my_smtp_settings['MAIL_ENCRYPTION'];
                $smtp['username'] = $my_smtp_settings['MAIL_USERNAME'];
                $smtp['password'] = $my_smtp_settings['MAIL_PASSWORD'];
            }
            
            //check for attachments
            $form_php .= $this->GeneratorCommonPhp->attachmentFields($form->schema['form']);

            $success_variable = true;

            //Send Email.
            if (isset($form->schema['emailConfig']['email']['enable']) && $form->schema['emailConfig']['email']['enable'] == 1) {
                $form_php .= $this->GeneratorCommonPhp->email($form->schema['emailConfig']['email'], $smtp, '$is_email_sent', false, $signature_elements);

                $success_variable = '$is_email_sent';
            }

            //Check and Send Auto response Email.
            if (isset($form->schema['emailConfig']['auto_response']['is_enable']) && $form->schema['emailConfig']['auto_response']['is_enable'] == 1) {
                $form_php .= $this->GeneratorCommonPhp->email(
                    $form->schema['emailConfig']['auto_response'],
                    $smtp,
                    '$is_autoresponse_email_sent',
                    true,
                    $signature_elements
            );

                $success_variable = ($success_variable == true) ? '$is_autoresponse_email_sent' : true;
            }

            // $form_php .= $this->GeneratorCommonPhp->databaseIntegration($this->form_data);
            //TODO: Error message in response.
            $form_php .= $this->GeneratorCommonPhp->phpEnd($form->schema['settings']['notification'], $success_variable);

            //Create a folder and put the contents in it.
            $directory = storage_path(config('constants.doc_path').'/'.$form_slug . '_' . time());
        
            $directory_library_php = $directory . '/library/';

            if (!is_writable(storage_path(config('constants.doc_path')))) {
                return back()
                ->with('status', ['success' => 0,
                        'msg' => "Unable to create temporary folder, Please create a folder " . storage_path(config('constants.doc_path') . ' and provide writable permission')
                    ]);
            }

            mkdir($directory, 0777, true);
            mkdir($directory_library_php);

            if ($form->schema['settings']['background']['bg_type'] == 'bg_image' && !empty($form->schema['settings']['color']['image_path'])) {
                $image_name = $form->schema['settings']['color']['image_path'];
                $src_file = public_path('uploads/'. config('constants.doc_path').'/'. $image_name);
                $destination_file = $directory . '/asset/';

                mkdir($destination_file);
                copy($src_file, $destination_file.$image_name);
            }

            $download_html = $directory . '/' . $form_slug . '.html';
            $download_php = $directory . '/' . $form_slug . '.php';

            //Generate html file.
            $f_html = fopen($download_html, 'w');
            fwrite($f_html, $html);
            fclose($f_html);

            //Generate php file.
            $f_php = fopen($download_php, 'w');
            fwrite($f_php, $form_php);
            fclose($f_php);

            //Copy all files from form_bundle.
            $src = __DIR__ . '/form_bundle_libraries';

            File::copyDirectory($src, $directory_library_php);
        
            return $this->_zipAndDownload($directory, $form_slug . '.zip');
        } catch (Exception $e) {
            die('Something Went Wrong');
        }
    }

    protected function _zipAndDownload($folder, $zipName)
    {
        if (!class_exists('ZipArchive')) {
            die('ZipArchive class not found, enable it from your php.ini file.');
        }

        $zipName = storage_path(). '/' . $zipName;

        //Zip the folder and download it.
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($folder),
            \RecursiveIteratorIterator::LEAVES_ONLY
                );

        $zip = new ZipArchive();
        $zip->open($zipName, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($files as $name => $file) {
            // Skip directories (they would be added automatically)
            if (!$file->isDir()) {
                // Get real and relative path for current file
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($folder));
                // Add current file to archive
                $zip->addFile($filePath, $relativePath);
            }
        }

        // Zip archive will be created only after closing object
        $zip->close();
        
        Storage::deleteDirectory($folder);

        return response()->download($zipName)
                ->deleteFileAfterSend();
    }

    public function generateWidget($id)
    {
        if (request()->ajax()) {

            $form = Form::where('created_by', \Auth::id())
                    ->findOrFail($id);

            $url = action('FormController@show', ['id' => $form->slug ?: $form->id]);

            $widget ='<iframe src="'.$url.'?iframe=true"'.' frameborder="0" width="100%"></iframe>';
            $widget_script = '<script src="'.asset('/js/iframeResizer.js').'"></script><script src="'.asset('/js/widget.js').'"></script>';

            $form = '<a href="'.$url.'"'.' target="_blank" class="btn btn-sm btn-info">Please fill out form</a>';

            return view('form.generate_widget')
                ->with(compact('widget', 'form', 'widget_script'));
        }
    }

    public function copyForm($id)
    {
        if (request()->ajax()) {
            $user = request()->user();
            $subscription_info = [];
            if (!$this->isSubscribed($user)) {
                $subscription_info = $this->subscriptionExpired();
            } elseif (!$this->isQuotaAvailable($user->id)) {
                $subscription_info = $this->quotaNotAvailable();
            }
            $form = Form::find($id);

            return view('form.copy_form')
            ->with(compact('form', 'subscription_info'));
        }
    }

    public function generateFormSlug(Request $request)
    {
        if ($request->ajax()) {
            $form_name = $request->input('name') .' '. Str::random(3);
            $form_slug = Str::slug($form_name);

            return $this->respond($form_slug);
        }
    }

    /**
     * get view to assign collab
     *
     * @param  \App\Form  $id
     * @return \Illuminate\Http\Response
     */
    public function getCollab($id)
    {
        if (request()->ajax()) {
            $user = request()->user();
            $form = Form::with('assignedTo', 'assignedTo.user')
                    ->where('created_by', $user->id)
                    ->find($id);

            return view('form.collaborate')
            ->with(compact('form'));
        }
    }

    /**
     * store the collab data in db
     *
     * @param  \App\Form  $id
     * @return \Illuminate\Http\Response
     */
    public function postCollab(Request $request)
    {
        try {
            if (request()->ajax()) {
                $form_id = $request->input('form_id');
                $permissions = $request->input('permissions');
                $email = $request->input('email');
                $user = User::where('email', $email)
                            ->first();
                $form = Form::findOrFail($form_id);

                if (!empty($email) && empty($user)) {
                    return $this->respondWithError(__('messages.user_doesnt_exist'));
                }elseif (!empty($user) && ($user->id == $form->created_by)) {
                    return $this->respondWithError(__('messages.form_cant_be_shared_with_creator'));
                }

                if (!empty($user) && !empty($permissions)) {
                    UserForm::create([
                        'form_id' => $form_id,
                        'assigned_to' => $user->id,
                        'assigned_by' => \Auth::id(),
                        'permissions' => $permissions
                    ]);
                }

                //update form permission(user)
                $edit_permissions = $request->input('edit_permissions');
                $assgined_form_ids = $request->input('edit_assigned_id');
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
            }
            return $this->respondSuccess(__('messages.success'));
        } catch (Exception $e) {
            return $this->respondWentWrong($e);
        }
    }

    /**
     * get form example views
     *
     * @return object
     */
    public function getFormExamples()
    {   
        if (!$this->isDemo()) {
            abort(403, 'Feature disabled');
        }

        return view('form_examples');
    }
}
