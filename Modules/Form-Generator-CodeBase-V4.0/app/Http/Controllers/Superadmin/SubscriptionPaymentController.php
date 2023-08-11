<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Notifications\SendApprovalNotificationToAdminForOfflinePayment;
use App\Package;
use App\PackageSubscription;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Notification;
use Srmklive\PayPal\Services\ExpressCheckout;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class SubscriptionPaymentController extends Controller
{


    /**
     * Show pay form for a new package.
     * @return Response
     */
    public function subscriptionPay($package_id)
    {
        return $this->pay($package_id, $register_form = true);
    }


    /**
      * Show pay form for a new package.
      * @return Response
      */
    public function pay($package_id, $register_form = null)
    {
        try {
            $package = Package::find($package_id);
            $user = request()->user();
            //Check for free package & subscribe it.
            if ($package->price == 0) {
                DB::beginTransaction();
                $paid_via = null;
                $payment_transaction_id = 'FREE';
                $status = 'approved';
                PackageSubscription::createSubscription($user->id, $package_id, $paid_via, $payment_transaction_id, $status);

                DB::commit();

                if (!empty($register_form)) {
                    $output = [
                            'success' => 1,
                            'msg' => __('messages.registered_and_subscribed')
                        ];

                    return redirect()->action('SubscriptionsController@index')
                        ->with('status', $output);
                } else {
                    $output = [
                            'success' => 1,
                            'msg' => __('messages.success')
                        ];
                    return redirect()->action('SubscriptionsController@index')
                        ->with('status', $output);
                }
            }

            $nav = false;
            $payment_gateways = $this->paymentGateways();
            $stripe_payment_session = [];
            if (array_key_exists('stripe', $payment_gateways)) {
                Stripe::setApiKey(config('constants.STRIPE_SECRET_KEY'));
                $stripe_payment_session = Session::create([
                  'customer_email' => $user->email,
                  'payment_method_types' => ['card'],
                  'line_items' => [[
                    'name' => $package->name,
                    'description' => $package->description,
                    'images' => [],
                    'amount' => $package->price*100,
                    'currency' => strtolower(env('CURRENCY_CODE')),
                    'quantity' => 1,
                  ]],
                  'success_url' => action('Superadmin\SubscriptionPaymentController@confirmPayment', [$package_id]).'?paid_via=stripe&session_id={CHECKOUT_SESSION_ID}',
                  'cancel_url' => action('Superadmin\SubscriptionPaymentController@pay', [$package_id])
                ]);
            }
            
            return view('payments.create')
                ->with(compact('package', 'nav', 'payment_gateways', 'stripe_payment_session'));
        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                    'success' => 0,
                    'msg' => __('messages.something_went_wrong')
                ];
            return redirect()->action('SubscriptionsController@index')
                ->with('status', $output);
        }
    }

    /**
     * confirm the payment & Save the payment details and add subscription details
     * @return Response
     */
    public function confirmPayment($package_id, Request $request)
    {
        try {
            DB::beginTransaction();

            $user = request()->user();
            $paid_via = $request->get('paid_via');
            //Call the payment method
            $pay_function = 'pay_' . $paid_via;
            $payment_transaction_id = null;
            $status = 'approved';
            if (method_exists($this, $pay_function)) {
                $payment_transaction_id = $this->$pay_function($package_id, $request);
            }

            if (in_array($paid_via, ['offline'])) {
                $status = 'waiting';
            }
            //create subscription
            PackageSubscription::createSubscription($user->id, $package_id, $paid_via, $payment_transaction_id, $status);

            DB::commit();
            $msg = __('messages.success');
            if ($request->get('paid_via') == 'offline') {
                $msg = __('messages.notification_sent_for_approval');
            }
            $output = ['success' => 1, 'msg' => $msg];
        } catch (Exception $e) {
            DB::rollBack();
            $output = [
                'success' => 0,
                'msg' => __('messages.something_went_wrong')
            ];
        }

        return redirect()->action('SubscriptionsController@index')
            ->with('status', $output);
    }


    /**
    * Offline payment method
    * @return Response
    */
    protected function pay_offline($package_id, $request)
    {
        $user = request()->user();
        $admin_emails = explode(',', env('SUPERADMIN_EMAILS'));
        $superadmins = User::whereIn('email', $admin_emails)
                           ->get();

        $package = Package::find($package_id);
        $package['paid_via'] = 'Offline';
        $package['package_price'] = env('CURRENCY_SYMBOL') . number_format($package->price, 2);
        
        Notification::send($superadmins, new SendApprovalNotificationToAdminForOfflinePayment($user, $package));

        return null;
    }

    
    /**
     * Paypal payment method
     * @return Response
     */
    protected function pay_paypal($package_id, $request)
    {
        $provider = new ExpressCheckout();
        config(['paypal.currency' => strtoupper(env('CURRENCY_CODE'))]);

        $provider = new ExpressCheckout();
        $response = $provider->getExpressCheckoutDetails($request->token);

        // if response ACK value is not SUCCESS or SUCCESSWITHWARNING return back with error
        if (!in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            return back()
                ->with('status', ['success' => 0, 'msg' => 'Something went wrong with paypal transaction']);
        }

        
        $invoice_id = $response['INVNUM'];
        $package = Package::find($package_id);
        $data = [];
        $data['items'] = [
                [
                    'name' => $package->name,
                    'price' => (float)$package->price,
                    'qty' => 1
                ]
            ];
        $data['invoice_id'] = $invoice_id;
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $data['return_url'] = action('Superadmin\SubscriptionPaymentController@confirmPayment', [$package_id]);
        $data['cancel_url'] = action('Superadmin\SubscriptionPaymentController@pay', [$package_id]);
        $data['total'] = (float)$package->price;

        $token = $request->get('token');
        $PayerID = $request->get('PayerID');
        
        // if payment is not recurring just perform transaction on PayPal and get the payment status
        $payment_status = $provider->doExpressCheckoutPayment($data, $token, $PayerID);
        $status = isset($payment_status['PAYMENTINFO_0_PAYMENTSTATUS']) ? $payment_status['PAYMENTINFO_0_PAYMENTSTATUS'] : null;

        if (!empty($status) && $status != 'Invalid') {
            return $invoice_id;
        } else {
            $error = 'Something went wrong with paypal transaction';
            throw new \Exception($error);
        }
    }

    /**
     * Paypal payment method - redirect to paypal url for payments
     *
     * @return Response
     */
    public function paypalExpressCheckout($package_id, Request $request)
    {
        $package = Package::find($package_id);
        $data = [];
        $data['items'] = [
                [
                    'name' => $package->name,
                    'price' => (float)$package->price,
                    'qty' => 1
                ]
            ];
        $data['invoice_id'] = str_random(5);
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $data['return_url'] = action('Superadmin\SubscriptionPaymentController@confirmPayment', [$package_id]) . '?paid_via=paypal';
        $data['cancel_url'] = action('Superadmin\SubscriptionPaymentController@pay', [$package_id]);
        $data['total'] = (float)$package->price;

        //send request to paypal & in response get aaray of data and if it has payment link redirect on that
        $provider = new ExpressCheckout();
        $response = $provider->setCurrency(strtoupper(env('CURRENCY_CODE')))->setExpressCheckout($data);

        // if there is no link redirect back with error message
        if (!$response['paypal_link']) {
            return back()
                ->with('status', ['success' => 0, 'msg' => 'Something went wrong with paypal transaction']);
        }
        
        return redirect($response['paypal_link']);
    }

    /**
     * return transaction id
     * after successful payment
     * @return Response
     */
    protected function pay_stripe($package_id, $request)
    {
        Stripe::setApiKey(config('constants.STRIPE_SECRET_KEY'));
        $stripe_payment = Session::retrieve($request->session_id);
        return $stripe_payment->payment_intent;
    }
}
