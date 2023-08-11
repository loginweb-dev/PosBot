<?php

namespace App\Http\Controllers;

use App\Form;
use App\PackageSubscription;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\UserForm;
use App\Visitor;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $statusCode;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function respondWithError($message = null)
    {
        return response()->json(
            ['success' => false, 'msg' => $message]
        );
    }

    /**
     * Returns a Unauthorized response.
     *
     * @param  string  $message
     * @return \Illuminate\Http\Response
     */
    public function respondUnauthorized($message = 'Unauthorized action.')
    {
        return $this->setStatusCode(403)
            ->respondWithError($message);
    }

    /**
     * Returns a went wrong response.
     *
     * @param  object  $exception = null
     * @return \Illuminate\Http\Response
     */
    public function respondWentWrong($exception = null)
    {
        //If debug is enabled then send exception message
        $message = (config('app.debug') && is_object($exception)) ? "File:" . $exception->getFile(). "Line:" . $exception->getLine(). "Message:" . $exception->getMessage() : __('messages.something_went_wrong');

        //TODO: show exception error message when error is enabled.
        return $this->setStatusCode(200)
            ->respondWithError($message);
    }

    /**
     * Returns a 200 response.
     *
     * @param  object  $message = null
     * @return \Illuminate\Http\Response
     */
    public function respondSuccess($message = null, $additional_data = [])
    {
        $message = is_null($message) ? __('messages.success') : $message;
        $data = ['success' => true, 'msg' => $message];

        if (!empty($additional_data)) {
            $data = array_merge($data, $additional_data);
        }

        return $this->respond($data);
    }

    /**
     * Returns a 200 response.
     *
     * @param  array  $data
     * @return \Illuminate\Http\Response
     */
    public function respond($data)
    {
        return response()->json($data);
    }

    /**
     * Returns a disabled response for demo
     *
     * @return \Illuminate\Http\Response
     */
    public function respondDemo()
    {
        $data = ['success' => false, 'msg' => __('messages.feature_disabled_in_demo')];
        return $this->respond($data);
    }

    public function isDemo()
    {
        if (config('app.env') == 'demo') {
            return true;
        }
    }

    /**
    * Checks if the feature is allowed in demo
    *
    * @return mixed
    */
    public function notAllowedInDemo()
    {
        //Disable in demo
        if (config('app.env') == 'demo') {
            $output = ['success' => 0,
                    'msg' => __('messages.feature_disabled_in_demo')
                ];
            if (request()->ajax()) {
                return $output;
            } else {
                return back()->with('status', $output);
            }
        }
    }

    /**
    * Checks if the user is subscribed
    * a package or not.
    *
    * @return boolean
    */
    public function isSubscribed($user)
    {
        $superadmins = env('SUPERADMIN_EMAILS');
        $superadmin_emails = explode(',', $superadmins);
        if (is_saas_enabled() && !in_array($user->email, $superadmin_emails)) {
            $today = Carbon::today()->toDateString();
            $subscription = PackageSubscription::activeSubscription($user->id);
            if (!isset($subscription->end_date) || $subscription->end_date <= $today) {
                return false;
            }
        }

        return true;
    }

    /**
    * Checks if the user's package
    * offers available or not.
    *
    * @return boolean
    */
    public function isQuotaAvailable($user_id)
    {
        if (is_saas_enabled()) {
            $subscription = PackageSubscription::activeSubscription($user_id);
            
            $subscription_start = '';
            $subscription_end = '';
            if (!empty($subscription)) {
                $subscription_start = Carbon::parse($subscription->start_date)->toDateTimeString();
                $subscription_end = Carbon::parse($subscription->end_date)->toDateTimeString();
            }

            $form_count = Form::where('created_by', $user_id)
                                ->whereBetween('created_at', [$subscription_start, $subscription_end])
                                ->where('is_template', 0)
                                ->count();

            if (isset($subscription->package_details['no_of_active_forms']) && $subscription->package_details['no_of_active_forms'] != 0) {
                if ($form_count >= $subscription->package_details['no_of_active_forms']) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Returns a subscription expired.
     *
     * @param  string  $message
     * @return \Illuminate\Http\Response
     */
    public function subscriptionExpired()
    {
        $output = ['success' => 0,
                    'msg' => __('messages.subscription_expired_pls_renew_it'),
                    'subscribe_btn' => 1
                ];

        return $output;
    }

    /**
     * Returns a quota not available.
     *
     * @param  string  $message
     * @return \Illuminate\Http\Response
     */
    public function quotaNotAvailable()
    {
        $output = ['success' => 0,
                    'msg' => __('messages.active_no_of_forms_for_package_crossed_limit'),
                    'subscribe_btn' => 0
                ];

        return $output;
    }

    /**
     * Returns the list of all configured payment gateway
     * @return Response
     */
    public function paymentGateways()
    {
        $gateways = [];

        //Check if paypal is configured or not
        if ((env('PAYPAL_SANDBOX_API_USERNAME') && env('PAYPAL_SANDBOX_API_PASSWORD')  && env('PAYPAL_SANDBOX_API_SECRET')) || (env('PAYPAL_LIVE_API_USERNAME') && env('PAYPAL_LIVE_API_PASSWORD')  && env('PAYPAL_LIVE_API_SECRET'))) {
            $gateways['paypal'] = 'PayPal';
        }

        //Check if stripe is configured or not
        if (!empty(config('constants.STRIPE_PUB_KEY')) && !empty(config('constants.STRIPE_SECRET_KEY'))) {
            $gateways['stripe'] = 'Stripe';
        }
        
        $gateways['offline'] = 'Offline';

        return $gateways;
    }

    public function doUserHavePermission($form_id, $permission)
    {
        $user_form = UserForm::where('form_id', $form_id)
                    ->where('assigned_to', \Auth::id())
                    ->first();

        if (!empty($user_form)) {
            return in_array($permission, $user_form->permissions);
        } else {
            return false;
        }
    }

    public function postVisitorsCount($request, $form_id)
    {   
        $is_unique = 0;
        if (($request->session()->get('referrer') !=
            $request->headers->get('referer')) ||
            ($request->session()->get('id') != $form_id)
        ) {

            session([
                'id' => $form_id,
                'referrer' => $request->headers->get('referer')
            ]);

            $is_unique = 1;
        }

        $visitor = Visitor::create([
                    'form_id' => $form_id,
                    'is_unique' => $is_unique,
                    'referrer' => $request->headers->get('referer')
                ]);

        return $visitor;
    }

    public function checkIfUserIsFormCreatorOrSharedWith($form)
    {
        if (!is_null(\Auth::id())) {
            $user_form = UserForm::where('form_id', $form->id)
                        ->where('assigned_to', \Auth::id())
                        ->first();

            return ($form->created_by != \Auth::id()) ? !empty($user_form) : true;
        }

        return false;
    }

    public function checkIfUserIsCreatorOfGivenForm($form_id)
    {
        $form = Form::where('id', $form_id)
                    ->where('created_by', \Auth::id())
                    ->first();

        return !empty($form);
    }
}
