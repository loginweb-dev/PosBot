<?php

namespace App\Http\Controllers;

use App\Package;
use App\PackageSubscription;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $user_id = request()->user()->id;
        $active_packages = Package::where('is_active', 1)
                        ->orderBy('sort_order', 'asc')
                        ->paginate(20);
        $active_subscription = PackageSubscription::activeSubscription($user_id);
        $upcoming_subscriptions = PackageSubscription::upcomingSubscriptions($user_id);
        $waiting_subscriptions = PackageSubscription::waitingSubscriptions($user_id);

        return view('user.subscription.index')
            ->with(compact('active_packages', 'active_subscription', 'upcoming_subscriptions', 'waiting_subscriptions'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllSubscriptions(Request $request)
    {
        if ($request->ajax()) {
            $package_subscription = PackageSubscription::where('user_id', $request->user()->id)
                ->select('package_subscriptions.package_details', 'start_date', 'end_date', 'package_price', 'paid_via', 'payment_transaction_id', 'status', 'created_at', 'id as package_subscription_id');

            return Datatables::of($package_subscription)
                        ->addColumn('action', '
                            <button type="button" data-href="{{action(\'SubscriptionsController@show\', [$package_subscription_id])}}" class="btn btn-icon btn-sm view_subscription text-info" data-toggle="tooltip" 
                            title="{{ __(\'messages.view\') }}">
                                <i class="far fa-eye font_icon_size" aria-hidden="true"></i>
                            </button>
                        ')
                        ->editColumn('status', '
                            <span class="badge 
                                @if($status == "approved")
                                    badge-success
                                @elseif($status == "waiting")
                                    badge-warning text-white
                                @elseif($status == "declined")
                                    badge-danger
                                @endif
                            ">
                                @lang("messages.".$status)
                            </span>
                        ')
                        ->addColumn('package', function($row) {
                            $package_name = !empty($row->package_details['name']) ? $row->package_details['name'] : '';
                            return $package_name;
                        })
                        ->editColumn(
                            'start_date',
                            '@php
                                $date = \Carbon\Carbon::parse($start_date)->isoFormat("D/M/YY");
                                @endphp
                              {{$date}}
                            '
                        )
                        ->editColumn('end_date', '@php
                                $date = \Carbon\Carbon::parse($end_date)->isoFormat("D/M/YY");
                                @endphp
                              {{$date}}
                            ')
                        ->editColumn('package_price', '
                                <span class="currency">
                                    {{$package_price}}
                                </span>
                            ')
                        ->editColumn('created_at', '@php
                                $date = \Carbon\Carbon::parse($created_at)->isoFormat("D/M/YY");
                                @endphp
                              {{$date}}
                            ')
                         ->editColumn('paid_via', '
                                @if($paid_via == "offline")
                                    @lang("messages.offline")
                                @else
                                    {{ucfirst($paid_via)}}
                                @endif
                            ')
                        ->removeColumn('package_subscription_id')
                        ->rawColumns(['action', 'package', 'status', 'start_date', 'end_date', 'paid_via', 'package_price', 'created_at'])
                        ->make(true);
        }
        return view('user.subscription.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (request()->ajax()) {
            $subscription = PackageSubscription::find($id);

            return view('user.subscription.show')
                ->with(compact('subscription'));
        }
    }
}
