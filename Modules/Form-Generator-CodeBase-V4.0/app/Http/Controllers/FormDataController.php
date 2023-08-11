<?php

namespace App\Http\Controllers;

use App\Form;
use App\FormData;
use App\Mail\FormSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Newsletter;
use App\Visitor;
use Carbon\Carbon;
use DB;
use PDF;

class FormDataController extends Controller
{
    public function store($form_id, Request $request)
    {
        try {

            //TODO: Check if form allowed to submit without login or not.
            $form = Form::findOrFail($form_id);

            $post_submit_action['notification'] = $form->schema['settings']['notification'];
            $is_enable_recaptcha = $form->schema['settings']['recaptcha']['is_enable'];
            $form_data = [];
            parse_str($request->get('form_data'), $form_data['data']);

            //Verification for google reCaptcha
            if (isset($is_enable_recaptcha) && $is_enable_recaptcha == 1) {
                if (isset($form_data['data']['g-recaptcha-response']) && !empty($form_data['data']['g-recaptcha-response'])) {
                    //your site secret key
                    $secret_key = $form->schema['settings']['recaptcha']['secret_key'];
                    //get verify response data
                    $verify_response = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$form_data['data']['g-recaptcha-response']);
                    
                    $response_data = json_decode($verify_response);
                    
                    if (!$response_data->success) {
                        $msg = 'reCaptcha error';
                        return $this->respondWithError($msg);
                    }
                } else {
                    $msg = 'reCaptcha error';
                    return $this->respondWithError($msg);
                }
            }

            //If user is logged in then save user id.
            if (Auth::check()) {
                $form_data['submitted_by'] = $request->user()->id;
            }

            $form_data['source'] = 'app';

            //status: if form data is draft(incomplete) or not
            $form_data['status'] = 'complete';
            if (isset($form_data['data']['status']) && $form_data['data']['status'] == 'incomplete') {
                $form_data['status'] = 'incomplete';
            }

            //if token, get existing data for token
            $existing_token_form_data = [];
            if (!empty($request->get('token'))) {
                $existing_token_form_data = FormData::where('form_id', $form->id)
                    ->where('token', $request->get('token'))
                    ->findOrFail($request->get('form_data_id'));
            }

            //if draft(incomplete) generate token & edit form url for user
            if ($form_data['status'] == 'incomplete') {
                if (!empty($request->get('token'))) {
                    $form_data['token'] = $existing_token_form_data['token'];
                } else {
                    $form_data['token'] = Str::random(4);
                }

                $post_submit_action['notification']['token'] = $form_data['token'];
                $post_submit_action['notification']['form_editable_url'] = action('FormController@show', ['id' => $form->slug ?: $form_id]).'?token='.$form_data['token'];
            } else {
                //if form submission status:complete, set token as null & get form view url
                $post_submit_action['notification']['view_form_url'] = action('FormController@show', ['id' => $form->slug ?: $form_id]);
                $form_data['token'] = null;
            }
            
            //generate form submission reference number if enabled
            $form_data['submission_ref'] = null;
            if ((isset($form->schema['settings']['form_submision_ref']['is_enabled']) && $form->schema['settings']['form_submision_ref']['is_enabled']) && $form_data['status'] == 'complete') {
                $start_no = $form->schema['settings']['form_submision_ref']['start_no'];
                $min_digit = $form->schema['settings']['form_submision_ref']['min_digit'];
                
                $new_count = $form->submission_count == 0 ? $start_no : ($form->submission_count + $start_no);

                $number = str_pad($new_count, $min_digit, '0', STR_PAD_LEFT);

                $form_data['submission_ref'] = $number;

                if (isset($form->schema['settings']['form_submision_ref']['prefix'])) {
                    $form_data['submission_ref'] = $form->schema['settings']['form_submision_ref']['prefix'] . $form_data['submission_ref'];
                }

                if (isset($form->schema['settings']['form_submision_ref']['suffix'])) {
                    $form_data['submission_ref'] = $form_data['submission_ref'] . $form->schema['settings']['form_submision_ref']['suffix'];
                }

                $form_data['data']['submission_ref'] = $form_data['submission_ref'];
            }

            //if token exist update the submitted data
            if (!empty($request->get('token'))) {
                $existing_data = FormData::where('form_id', $form->id)
                    ->where('token', $request->get('token'))
                    ->findOrFail($request->get('form_data_id'));

                $existing_data->data = $form_data['data'];
                $existing_data->submission_ref = $form_data['submission_ref'];
                $existing_data->source = $form_data['source'];
                $existing_data->status = $form_data['status'];
                $existing_data->token = $form_data['token'];
                $existing_data->save();
            } else {
                //store form data.
                $saved_form_data = $form->data()->create($form_data);
            }

            //if submission is draft(incomplete) then return form_data_id & saved draft msg
            if (isset($form_data['data']['status']) && $form_data['data']['status'] == 'incomplete') {
                $post_submit_action['notification']['form_data_id'] = !empty($request->get('form_data_id')) ? $request->get('form_data_id') : $saved_form_data->id;
                $post_submit_action['notification']['success_msg'] = __('messages.draft_saved');
            }

            //update form submission count if submission is complete
            if ((isset($form->schema['settings']['form_submision_ref']['is_enabled']) && $form->schema['settings']['form_submision_ref']['is_enabled']) && $form_data['status'] == 'complete') {
                $form->submission_count += 1;
                $form->save();
            }

            //check for demo environment & form is complete or not
            if (!$this->isDemo() && $form_data['status'] == 'complete') {

                //Send notification for form
                $emailConfig = $form->schema['emailConfig'];

                //check if attachment is enabled
                $attachments = [];
                foreach ($form->schema['form'] as $element) {
                    if ($element['type'] == 'file_upload' && $element['send_as_email_attachment'] == 1) {
                        $attachments[] = $element['name'];
                    }
                }

                if ((isset($emailConfig['email']['attach_pdf']) && $emailConfig['email']['attach_pdf']) || (isset($emailConfig['auto_response']['attach_pdf']) && $emailConfig['auto_response']['attach_pdf'])) {

                    $id = !empty($existing_data) ? $existing_data->id : $saved_form_data->id;
                    $pdf = $this->__generatePdf($id);
                    $pdf_name = Str::slug($form->name, '-').'.pdf';
                    
                }

                //Set user defined SMTP : use_system_smtp = User SMTP
                if ($emailConfig['smtp']['use_system_smtp']) {
                    //User SMTP
                    $form = Form::with('createdBy')->findOrFail($form_id);
                    $smtp = $form->createdBy->settings['smtp'];

                    config([
                        'mail.host' => $smtp['MAIL_HOST'],
                        'mail.port' => $smtp['MAIL_PORT'],
                        'mail.from.address' => $smtp['MAIL_FROM_ADDRESS'],
                        'mail.from.name' => $smtp['MAIL_FROM_NAME'],
                        'mail.encryption' => $smtp['MAIL_ENCRYPTION'],
                        'mail.username' => $smtp['MAIL_USERNAME'],
                        'mail.password' => $smtp['MAIL_PASSWORD'],
                    ]);
                } else {
                    config([
                        'mail.host' => $emailConfig['smtp']['host'],
                        'mail.port' => $emailConfig['smtp']['port'],
                        'mail.from.address' => $emailConfig['smtp']['from_address'],
                        'mail.from.name' => $emailConfig['smtp']['from_name'],
                        'mail.encryption' => $emailConfig['smtp']['encryption'],
                        'mail.username' => $emailConfig['smtp']['username'],
                        'mail.password' => $emailConfig['smtp']['password'],
                    ]);
                }

                //Form submission Notification
                if (!empty($emailConfig['email']['enable'])) {

                    //Replace the tags with values.
                    $temp = $this->_replaceTags(
                        $form_data['data'],
                        ['subject' => $emailConfig['email']['subject'],
                        'body' => $emailConfig['email']['body']],
                        $form['schema']['form']
                    );
                    $emailConfig['email']['subject'] = $temp['subject'];
                    $emailConfig['email']['body'] = $temp['body'];

                    //Attachments
                    if (!empty($attachments)) {
                        $emailConfig['email']['attachment'] = $this->getAttachments($attachments, $form_data['data']);
                    }
                    
                    if ((isset($emailConfig['email']['attach_pdf']) && $emailConfig['email']['attach_pdf'])) {
                        $emailConfig['email']['pdf_attachment'] = $pdf;
                        $emailConfig['email']['pdf_name'] = $pdf_name;
                    }

                    Mail::send(new FormSubmitted($emailConfig['email']));
                }

                //Auto-Response
                if ($emailConfig['auto_response']['is_enable']) {
                    
                    //Replace the tags with values.
                    $temp = $this->_replaceTags(
                        $form_data['data'],
                        ['subject' => $emailConfig['auto_response']['subject'],
                        'body' => $emailConfig['auto_response']['body']],
                        $form['schema']['form']
                    );
                    $emailConfig['auto_response']['subject'] = $temp['subject'];
                    $emailConfig['auto_response']['body'] = $temp['body'];

                    //"TO" field is dynamic input value.
                    $emailConfig['auto_response']['to'] = isset($form_data['data'][$emailConfig['auto_response']['to']]) ? $form_data['data'][$emailConfig['auto_response']['to']] : null;

                    if (!empty($attachments)) {
                        $emailConfig['auto_response']['attachment'] = $this->getAttachments($attachments, $form_data['data']);
                    }

                    if ((isset($emailConfig['auto_response']['attach_pdf']) && $emailConfig['auto_response']['attach_pdf'])) {
                        $emailConfig['auto_response']['pdf_attachment'] = $pdf;
                        $emailConfig['auto_response']['pdf_name'] = $pdf_name;
                    }

                    if (!empty($emailConfig['auto_response']['to'])) {
                        Mail::send(new FormSubmitted($emailConfig['auto_response']));
                    }
                }

                //Send data to mailchimp if enabled.
                if (!empty($form->mailchimp_details['is_enable']) && $form->mailchimp_details['is_enable'] == 1) {

                    //Set config details.
                    config(['newsletter.apiKey' => $form->mailchimp_details['api_key']]);
                    config(['newsletter.lists.subscribers.id' => $form->mailchimp_details['list_id']]);

                    //Subscribe if email is set.
                    if (isset($form_data['data'][$form->mailchimp_details['email_field']]) && !empty($form_data['data'][$form->mailchimp_details['email_field']])) {

                        //Get dynamic field from form input.
                        $email = $form_data['data'][$form->mailchimp_details['email_field']];

                        //explode name to get first & last name
                        $name = explode(' ', $form_data['data'][$form->mailchimp_details['name_field']], 2);
                        $fname = $name[0];
                        $lname = !empty($name[1]) ? $name[1] : '';
                        if ($form->mailchimp_details['status'] == 'subscribe') {
                            Newsletter::subscribe($email, ['FNAME'=> $fname, 'LNAME'=> $lname]);
                        } elseif ($form->mailchimp_details['status'] == 'subscribe_pending') {
                            Newsletter::subscribePending($email, ['FNAME'=> $fname, 'LNAME'=> $lname]);
                        }
                    }
                }
            }

            return $this->respondSuccess($message = null, $post_submit_action);
        } catch (\Exception $e) {
            return $this->respondWentWrong($e);
        }
    }

    protected function _replaceTags($form_data, $strings, $form_schema)
    {
        foreach ($form_data as $name => $value) {
            //replace signature field with image tag
            $index = array_search($name, array_column($form_schema, 'name'));
            $field = $form_schema[$index];
            if ($field['name'] == $name && $field['type'] == 'signature') {
                $signature = '<img src="__'.$name.'__" style="width: 100px;height: 50px;">';
                $strings['body'] = preg_replace('/__'.$name.'__/', $signature, $strings['body']);
            }

            foreach ($strings as $key => $string) {

                //If value is array(like for multiselect or checkbox) then implode it.
                $value = is_array($value) ? implode(',', $value) : $value;

                $strings[$key] = str_replace('__' . $name . '__', $value, $string);
            }
        }

        return $strings;
    }

    public function show($form_id, Request $request)
    {
        $user_id = $request->user()->id;
        
        $form = Form::findOrFail($form_id);
        $data = FormData::where('form_id', $form_id)->get();

        //check permission if user is not a creator
        $has_permission = ($form->created_by != $user_id) ? $this->doUserHavePermission($form->id, 'can_view_data') : true;
        if (!$has_permission) {
            abort(404);
        }

        return view('form_data.show')
            ->with(compact('form', 'data'));
    }

    public function viewData($id)
    {
        if (request()->ajax()) {
            $form_data = FormData::with(['form', 'submittedBy',
                'comments' => function($q) {
                    $q->latest();
                },
                'comments.commentedBy'])->findOrFail($id);

            return view('form_data.view_form_data')
                ->with(compact('form_data'));
        }
    }

    public function destroy($id)
    {
        try {
            if (request()->ajax()) {
                $form_data = FormData::findOrFail($id);
                $form_data->delete();
                $form_data->comments()->delete();
            }

            return $this->respondSuccess(__('messages.deleted_successfully'));
        } catch (Exception $e) {
            return $this->respondWentWrong($e);
        }
    }

    public function getAttachments($attachment_fields, $form_data)
    {
        $attachments = [];
        foreach ($attachment_fields as $attachment_field) {
            foreach ($form_data as $key => $values) {
                if ($attachment_field == $key) {
                    foreach ($values as $key => $value) {
                        $uploaded_file = explode(",", $value);
                    }
                    $attachments = array_merge($attachments, $uploaded_file);
                }
            }
        }

        return $attachments;
    }

    /**
     * return the data to display
     * a report
     */
    public function getReport($id)
    {
        $user = request()->user();
        $form = Form::with('data')
                ->findOrFail($id);

        //check permission if user is not a creator
        $has_permission = ($form->created_by != $user->id) ? $this->doUserHavePermission($form->id, 'can_view_data') : true;
        if (!$has_permission) {
            abort(404);
        }

        $charts = [];
        if (isset($form->schema['form'])) {
            foreach ($form->schema['form'] as $element) {
                if (in_array($element['type'], ['dropdown', 'radio', 'checkbox', 'rating'])) {
                    //chart title
                    $charts[$element['name']]['name']= $element['label'];

                    //getting given option
                    if ($element['type'] == 'rating') {
                        $all_rating =  implode(",", range($element['min_rating'], $element['max_rating'], $element['increment']));
                        $options = explode(',', $all_rating);
                    } else {
                        $options = explode(PHP_EOL, $element['options']);
                    }

                    $dropdowns = [];
                    foreach ($options as $key => $value) {
                        $dropdowns[$value] = 0;
                    }

                    //generating report
                    foreach ($options as $option) {
                        foreach ($form->data as $submitted_data) {
                            if (isset($submitted_data['data'][$element['name']]) && ($submitted_data['data'][$element['name']] == $option)) {
                                $dropdowns[$option] += 1;
                            } elseif (isset($submitted_data['data'][$element['name']]) && is_array($submitted_data['data'][$element['name']]) && in_array($option, $submitted_data['data'][$element['name']])) {
                                $dropdowns[$option] += 1;
                            }
                        }
                    }
                    
                    //storing report
                    $charts[$element['name']]['values'] = $dropdowns;
                }
            }
        }
        
        $visitors_chart = $this->_generateVisiorsLineChartData($form->id);
        $referrers_chart = $this->_generateVisiorsPieChartData($form->id);

        return view('form_data.report')
            ->with(compact('charts', 'form', 'visitors_chart', 'referrers_chart'));
    }

    private function __getVisitorsReport($form_id, $chart_type)
    {   
        $query = Visitor::where('form_id', $form_id)
                        ->whereBetween(DB::raw('date(created_at)'), [Carbon::now()->subDays(30), Carbon::now()])
                        ->select(
                            DB::raw('count(form_id) as total_visits'),
                            DB::raw('SUM(IF(is_unique = 1,1,0)) as unique_visits')
                        );

        if ($chart_type == 'line') {
            $query->addSelect(DB::raw('Date(created_at) as date'))
                ->groupBy(DB::raw('Date(created_at)'));
        } elseif ($chart_type == 'pie') {
            $query->addSelect('referrer')
                ->groupBy('referrer');
        }
                        
        $visitors = $query->get();

        return $visitors;
    }

    protected function _generateVisiorsLineChartData($form_id)
    {   
        $visitors = $this->__getVisitorsReport($form_id, 'line');
        //generate all the labels between 30 days
        $dates = [];
        $labels = [];
        for ($i=29; $i >= 0 ; $i--) { 
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dates[] = $date;
            $labels[] = date('j M Y', strtotime($date));
        }
        
        //get total & unique visits for last 30 days
        $total_visits_in_last_30_days = [];
        $unique_visits_in_last_30_days = [];
        foreach ($dates as $key => $date) {
            $visitor_date = null;
            $total_visits = 0;
            $unique_visits = 0;
            foreach ($visitors as $key => $visitor) {
                if ($visitor['date'] == $date) {
                    $visitor_date = $visitor['date'];
                    $total_visits = $visitor['total_visits'];
                    $unique_visits = $visitor['unique_visits'];
                    break;
                }
            }
            //if date match store values
            if ($visitor_date == $date) {
                $total_visits_in_last_30_days[] = (float) $total_visits;
                $unique_visits_in_last_30_days[] = (float) $unique_visits;
            } else{
                $total_visits_in_last_30_days[] = 0;
                $unique_visits_in_last_30_days[] = 0;
            }
        }

        $charts_data = [
            'title' => __('messages.total_visitors_in_last_30_days'),
            'labels' => $labels,
            'total_visits_label' => __('messages.total_visits'),
            'total_visits' => $total_visits_in_last_30_days,
            'unique_visits_label' => __('messages.unique_visits'),
            'unique_visits' => $unique_visits_in_last_30_days
        ];

        return $charts_data;
    }

    public function _generateVisiorsPieChartData($form_id)
    {
        $visitors = $this->__getVisitorsReport($form_id, 'pie');

        //get referrer as key value
        $referrers = [];
        foreach ($visitors as $key => $visitor) {
            if (!empty($visitor->referrer)) {
                $referrers[] = ['name' => $visitor->referrer, 'y' => $visitor->total_visits];
            } else {
                $referrers[] = ['name' => __('messages.direct_visits'), 'y' => $visitor->total_visits];
            }
        }

        $charts_data = [
                'name' => __('messages.referrers_in_last_30_days'),
                'values' => $referrers
            ];
            
        return $charts_data;
    }

    private function __generatePdf($id)
    {
        $form_data = FormData::with(['form', 'submittedBy'])->findOrFail($id);
        
        $pdf = PDF::loadView('form_data.partials.form_data_pdf', 
                ['form_data' =>  $form_data]
            );
        
        return $pdf;
    }

    public function downloadPdf($id)
    {   
        $formData = FormData::with(['form'])->find($id);

        if (!(auth()->user()->can('superadmin') || $this->checkIfUserIsCreatorOfGivenForm($formData->form->id) || $this->doUserHavePermission($formData->form->id, 'can_view_data'))) {
            abort(403, 'Unauthorized action.');
        }
        
        $pdf = $this->__generatePdf($id);

        return $pdf->stream('document.pdf');
    }

    public function getEditformData($id_or_slug, $data_id)
    {   

        $form = Form::where('id', $id_or_slug)
                ->orWhere('slug', $id_or_slug)
                ->first();

        if (empty($form)) {
            abort(404);
        }

        //if submitted data id available get submitted data
        if (!empty($data_id)) {
            $form->load(['data' => function ($query) use ($data_id, $form) {
                $query->where('id', $data_id)
                    ->where('form_id', $form->id);
            }]);
        }

        $nav = false;
        $action_by = 'admin';
        return view('form.show')
            ->with(compact('form', 'nav', 'action_by'));
    }

    public function postEditformData($form_id, $data_id)
    {
        try {
            $form = Form::findOrFail($form_id);
            $form_data = [];
            parse_str(request()->get('form_data'), $form_data['data']);

            if (!empty($data_id)) {
                $existing_data = FormData::where('form_id', $form->id)
                                    ->findOrFail($data_id);

                $existing_data->data = $form_data['data'];
                $existing_data->save();
            }
            $post_submit_action['notification'] = $form->schema['settings']['notification'];
            $post_submit_action['notification']['success_msg'] = __('messages.success');
            $post_submit_action['notification']['post_submit_action'] = 'redirect';
            $post_submit_action['notification']['redirect_url'] = action('FormDataController@show', ['id' => $form->id]);
            return $this->respondSuccess(null, $post_submit_action);
        } catch (Exception $e) {
            return $this->respondWentWrong($e);
        }
    }
}
