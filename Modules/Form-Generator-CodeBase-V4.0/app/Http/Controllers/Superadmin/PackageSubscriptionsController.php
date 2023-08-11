<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\PackageSubscription;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PackageSubscriptionsController extends Controller
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
            $package_subscription = PackageSubscription::join('users', 'package_subscriptions.user_id', 'users.id')
                ->select('users.name as user', 'package_subscriptions.package_details', 'start_date', 'end_date', 'package_price', 'payment_transaction_id', 'status', 'paid_via', 'package_subscriptions.id as package_subscription_id');

            return Datatables::of($package_subscription)
                        ->addColumn('action', '
                            <button type="button" data-href="{{action(\'Superadmin\PackageSubscriptionsController@edit\', [$package_subscription_id])}}" class="btn btn-icon btn-sm edit_subscription text-primary" data-toggle="tooltip" 
                            title="{{ __(\'messages.edit\') }}">
                                <i class="far fa-edit font_icon_size" aria-hidden="true"></i>
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
                        ->editColumn('paid_via', '
                                @if($paid_via == "offline")
                                    @lang("messages.offline")
                                @else
                                    {{ucfirst($paid_via)}}
                                @endif
                            ')
                        ->removeColumn('package_subscription_id')
                        ->rawColumns([ 'action', 'package', 'status', 'start_date', 'end_date', 'package_price', 'paid_via'])
                        ->make(true);
        }

        return view('superadmin.subscription.index');
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
            $subscription = PackageSubscription::findOrFail($id);
            $status_list = PackageSubscription::status();
            return view('superadmin.subscription.edit')
                    ->with(compact('subscription', 'status_list'));
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
            $subscription_info = $request->only('start_date', 'end_date', 'status', 'payment_transaction_id');

            PackageSubscription::where('id', $id)
                            ->update($subscription_info);

            $output = $this->respondSuccess(__('messages.updated_successfully'));
        } catch (Exception $e) {
            $output = $this->respondWentWrong($e);
        }
        return $output;
    }
}
