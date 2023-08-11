<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PackageSubscription extends Model
{
    use SoftDeletes;
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'package_details' => 'array',
    ];

    public static function status()
    {
        $list = [
                'approved' => __("messages.approved"),
                'waiting' => __("messages.waiting"),
                'declined' => __("messages.declined")
            ];

        return $list;
    }

    public static function createSubscription($user_id, $package_id, $paid_via, $payment_transaction_id, $status)
    {
        $package = Package::find($package_id);

        $subscription_info['user_id'] = $user_id;
        $subscription_info['package_id'] = $package->id;
        $subscription_info['package_details'] = [
                            'name' => $package->name,
                            'description' => $package->description,
                            'no_of_active_forms' => $package->no_of_active_forms,
                            'is_form_downloadable' => $package->is_form_downloadable,
                            'price_interval' => $package->price_interval,
                            'interval' => $package->interval
                        ];
        $subscription_info['package_price'] = $package->price;
        $subscription_info['paid_via'] = $paid_via;
        $subscription_info['payment_transaction_id'] = $payment_transaction_id;
        $subscription_info['status'] = $status;

        $start_date = PackageSubscription::subscriptionExpiredOn($user_id);
        $end_date = PackageSubscription::subscriptionExpiredOn($user_id);
        $subscription_info['start_date'] = $start_date->toDateTimeString();
        if ($package->price_interval == 'days') {
            $subscription_info['end_date'] = $end_date->addDays($package->interval)->toDateTimeString();
        } elseif ($package->price_interval == 'months') {
            $subscription_info['end_date'] = $end_date->addMonths($package->interval)->toDateTimeString();
        } elseif ($package->price_interval == 'year') {
            $subscription_info['end_date'] = $end_date->addYears($package->interval)->toDateTimeString();
        }

        $package_subscribed = PackageSubscription::create($subscription_info);

        return $package_subscribed;
    }

    public static function activeSubscription($user_id)
    {
        $today = Carbon::today()->toDateString();

        $subscription = PackageSubscription::where('user_id', $user_id)
                            ->where('start_date', '<=', $today)
                            ->where('end_date', '>=', $today)
                            ->where('status', 'approved')
                            ->first();
                            
        return $subscription;
    }

    public static function upcomingSubscriptions($user_id)
    {
        $today = Carbon::today()->toDateString();
        
        $subscriptions = PackageSubscription::where('user_id', $user_id)
                            ->where('start_date', '>', $today)
                            ->where('status', 'approved')
                            ->get();

        return $subscriptions;
    }

    public static function waitingSubscriptions($user_id)
    {
        $today = Carbon::today()->toDateString();
        
        $subscriptions = PackageSubscription::where('user_id', $user_id)
                            ->where('start_date', '>=', $today)
                            ->where('status', 'waiting')
                            ->get();

        return $subscriptions;
    }

    public static function subscriptionExpiredOn($user_id)
    {
        $today = Carbon::today();

        $subscription = PackageSubscription::where('user_id', $user_id)
                            ->where('status', 'approved')
                            ->select(DB::raw("MAX(end_date) as end_date"))
                            ->first();

        if (empty($subscription->end_date)) {
            return $today;
        } else {
            return Carbon::parse($subscription->end_date)->addDay();
        }
    }
}
