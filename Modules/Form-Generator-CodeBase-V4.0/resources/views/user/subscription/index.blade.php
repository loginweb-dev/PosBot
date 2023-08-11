@extends('layouts.app')
@section('content')
<div class="container">
	<div class="card card-outline card-info">
		<div class="card-header">
			<div class="card-title">
				@lang('messages.active_subscription')
			</div>
		</div>
		<div class="card-body">
			@if(!empty($active_subscription))
				<div class="col-md-4">
					<div class="card card card-outline card-success">
                        <div class="card-header">
                        	<div class="text-center">
                        		{{$active_subscription->package_details['name']}}
                        		<span class="badge badge-pill badge-success float-right">
									@lang('messages.running')
								</span>
                        	</div>
                        </div>
						<div class="card-body text-center">
							@if($active_subscription->package_details['no_of_active_forms'] == 0)
								<span>
									<i class="far fa-check-circle text-success"></i>
									@lang('messages.unlimited_forms')
								</span>
								<hr>
							@endif
							@if($active_subscription->package_details['is_form_downloadable'])
								<span>
									<i class="far fa-check-circle text-success"></i>
									@lang('messages.form_code_download')
								</span>
							@else
								<span>
									<i class="far fa-times-circle text-danger"></i>
									@lang('messages.form_code_download')
								</span>
							@endif
							<hr>
							<span>
								<i class="far fa-check-circle text-success"></i>
								@lang('messages.package_activated_on', ['date' => \Carbon\Carbon::parse($active_subscription->start_date)->isoFormat("D/M/YY")])
							</span>
							<hr>
							<span>
								<i class="far fa-check-circle text-success"></i>
								@lang('messages.remaining_days', ['days' => \Carbon\Carbon::today()->diffInDays($active_subscription->end_date)])
							</span>
							<hr>
							<span>
								<i class="far fa-check-circle text-success"></i>
								@lang('messages.package_expire_on', ['date' => \Carbon\Carbon::parse($active_subscription->end_date)->isoFormat("D/M/YY")])
							</span>
						</div>
					</div>
				</div>
			@else
			<h3 class="text-danger text-center">
				@lang('messages.no_active_subscription')
			</h3>
			@endif

			@if(!empty($upcoming_subscriptions))
				<div class="row">
					@foreach($upcoming_subscriptions as $upcoming_subscription)
						<div class="col-md-4">
							<div class="card card card-outline card-success">
								<div class="card-header">
	                                <div class="text-center">
	                                    {{$upcoming_subscription->package_details['name']}}
	                                    <span class="badge badge-pill badge-info float-right">
											@lang('messages.upcoming')
										</span>
	                                </div>
	                            </div>
	                            <div class="card-body text-center">
									@if($upcoming_subscription->package_details['no_of_active_forms'] != 0)
										<span>
											<i class="far fa-check-circle text-success"></i>
											@lang('messages.no_of_forms',[
											'active_form' => $upcoming_subscription->package_details['no_of_active_forms']])
										</span>
									@else
										<span>
											<i class="far fa-check-circle text-success"></i>
											@lang('messages.unlimited_forms')
										</span>
									@endif
									<hr>
									@if($upcoming_subscription->package_details['is_form_downloadable'])
										<span>
											<i class="far fa-check-circle text-success"></i>
											@lang('messages.form_code_download')
										</span>
									@else
										<span>
											<i class="far fa-times-circle text-danger"></i>
											@lang('messages.form_code_download')
										</span>
									@endif
									<hr>
									<span>
										<i class="far fa-check-circle text-success"></i>
										@lang('messages.package_starts_on', ['date' => \Carbon\Carbon::parse($upcoming_subscription->start_date)->isoFormat("D/M/YY")])
									</span>
									<!-- @lang('messages.start_date') :  {{\Carbon\Carbon::parse($upcoming_subscription->start_date)->isoFormat("D/M/YY")}} -->
									<hr>
									<span>
										<i class="far fa-check-circle text-success"></i>
										@lang('messages.package_expire_on', ['date' => \Carbon\Carbon::parse($upcoming_subscription->end_date)->isoFormat("D/M/YY")])
									</span>
									<!-- @lang('messages.end_date') : {{\Carbon\Carbon::parse($upcoming_subscription->end_date)->isoFormat("D/M/YY")}} -->
	                            </div>
							</div>
						</div>
						@if($loop->iteration%3 == 0)
		    				<div class="clearfix"></div>
		    			@endif
					@endforeach
				</div>
			@endif
			@if(!empty($waiting_subscriptions))
				<div class="row">
					@foreach($waiting_subscriptions as $waiting_subscription)
						<div class="col-md-4">
							<div class="card card card-outline card-success">
								<div class="card-header">
	                                <div class="text-center">
	                                    {{$waiting_subscription->package_details['name']}}
	                                    <span class="badge badge-pill badge-warning float-right text-white">
											@lang('messages.waiting')
										</span>
	                                </div>
	                            </div>
	                            <div class="card-body text-center">
	                            	@if($waiting_subscription->package_details['no_of_active_forms'] != 0)
										<span>
											<i class="far fa-check-circle text-success"></i>
											@lang('messages.no_of_forms',[
											'active_form' => $waiting_subscription->package_details['no_of_active_forms']])
										</span>
									@else
										<span>
											<i class="far fa-check-circle text-success"></i>
											@lang('messages.unlimited_forms')
										</span>
									@endif
									<hr>
									@if($waiting_subscription->package_details['is_form_downloadable'])
										<span>
											<i class="far fa-check-circle text-success"></i>
											@lang('messages.form_code_download')
										</span>
									@else
										<span>
											<i class="far fa-times-circle text-danger"></i>
											@lang('messages.form_code_download')
										</span>
									@endif
									<hr>
									<span>
										<i class="far fa-check-circle text-success"></i>
										@lang('messages.package_starts_on', ['date' => \Carbon\Carbon::parse($waiting_subscription->start_date)->isoFormat("D/M/YY")])
									</span>
									<!-- @lang('messages.start_date') :  {{\Carbon\Carbon::parse($waiting_subscription->start_date)->isoFormat("D/M/YY")}} -->
									<hr>
									<span>
										<i class="far fa-check-circle text-success"></i>
										@lang('messages.package_expire_on', ['date' => \Carbon\Carbon::parse($waiting_subscription->end_date)->isoFormat("D/M/YY")])
									</span>
									<!-- @lang('messages.end_date') : {{\Carbon\Carbon::parse($waiting_subscription->end_date)->isoFormat("D/M/YY")}} -->
	                            </div>
							</div>
						</div>
						@if($loop->iteration%3 == 0)
		    				<div class="clearfix"></div>
		    			@endif
					@endforeach
				</div>
			@endif
		</div>
	</div>
	<div class="card card-outline card-info">
		<div class="card-header">
			<div class="card-title">
				@lang('messages.all_subscriptions')
			</div>
		</div>
		<div class="card-body">
			<table class="table" id="package_subscription_table">
				<thead>
					<tr>
						<th>@lang('messages.package_name')</th>
						<th>@lang('messages.start_date')</th>
						<th>@lang('messages.end_date')</th>
						<th>@lang('messages.price')</th>
						<th>@lang('messages.paid_via')</th>
						<th>@lang('messages.transaction_id')</th>
						<th>@lang('messages.status')</th>
						<th>@lang('messages.created_at')</th>
						<th>@lang('messages.action')</th>
					</tr>
				</thead>
				<tbody>
				</tbody>
			</table>
		</div>
	</div>
	<div class="card card-outline card-info">
		<div class="card-header">
			<div class="card-title">
				@lang('messages.packages')
			</div>
		</div>
		<div class="card-body">
			@if(count($active_packages) <= 0)
            	<div class="alert alert-danger" role="alert">
				 	<span class="text-white">@lang('messages.no_packages_found')</span>
				</div>
           	@endif
			<div class="row">
				@if($__enable_saas)
					@foreach($active_packages as $package)
						<div class="col-md-4">
							<div class="card card-outline card-success on_hover">
								<div class="card-header">
	                                <div class="text-center">
	                                    <h5>
	                                        {{$package->name}}
	                                    </h5>
	                                </div>
	                            </div>
								<div class="card-body text-center">
									@if($package->no_of_active_forms != 0)
										<span>
											<i class="far fa-check-circle text-success"></i>
											@lang('messages.no_of_forms',[
											'active_form' => $package->no_of_active_forms])
										</span>
									@else
										<span>
											<i class="far fa-check-circle text-success"></i>
											@lang('messages.unlimited_forms')
										</span>
									@endif
									<hr>
									@if($package->is_form_downloadable)
										<span>
											<i class="far fa-check-circle text-success"></i>
											@lang('messages.form_code_download')
										</span>
									@else
										<span>
											<i class="far fa-times-circle text-danger"></i>
											@lang('messages.form_code_download')
										</span>
									@endif
									<hr>
									@php
										$price_interval = __('messages.'.$package->price_interval);
									@endphp
									@if($package->price != 0)
										<h4>
										<span class="currency">
											{{$package->price}}
										</span>
										<small class="text-muted">
											@lang('messages.subscription_price',[
												'interval' => $package->interval,
												'price_interval' => $price_interval
											])
										</small>
										</h4>
									@else
										<h4>
										@lang('messages.free_for_interval', [
											'interval' => $package->interval,
											'price_interval' => $price_interval
										])
										</h4>
									@endif
								</div>
								<div class="card-footer text-center">
									<a href="{{action('Superadmin\SubscriptionPaymentController@pay', [$package->id])}}" class="btn btn-block btn-success btn-sm">
										@lang('messages.subscribe')
									</a>
								    {{$package->description}}
								</div>
							</div>
						</div>
						@if($loop->iteration%3 == 0)
		    				<div class="clearfix"></div>
		    			@endif
					@endforeach
				@endif
			</div>
			<div class="col-md-12">
                {{ $active_packages->links() }}
            </div>
		</div>
	</div>
</div>
@endsection
@section('footer')
<script type="text/javascript">
	$(document).ready(function(){
		//notification after payment
		@if (!empty(session('status')) && session('status')['success'] == 1)
            var msg = '{!!session('status')['msg']!!}';
            toastr.success(msg);
        @elseif(!empty(session('status')) && session('status')['success'] == 0)
        	var msg = '{!!session('status')['msg']!!}';
            toastr.error(msg);
        @endif
		var package_subscription_table = $("#package_subscription_table").DataTable({
				processing: true,
                serverSide: true,
                ajax: '/all-subscriptions',
                buttons: [],
                dom: 'lfrtip',
                fixedHeader: false,
                columnDefs: [
	                {
	                    targets: [0],
	                    orderable: false,
	                    searchable: false,
	                },
	            ],
                columns: [
                    { data: 'package' , name: 'package'},
                    { data: 'start_date' , name: 'start_date'},
                    { data: 'end_date', name: 'end_date'},
                    { data: 'package_price' , name: 'package_price'},
                    { data: 'paid_via', name: 'paid_via'},
                    { data: 'payment_transaction_id' , name: 'payment_transaction_id'},
                    { data: 'status' , name: 'status'},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'action', name: 'action', sortable:false}
                ],
                "fnDrawCallback": function (oSettings) {
              __convert_currency_in_datatable($('#package_subscription_table'));
          }
		});

		$(document).on('click', '.view_subscription', function(){
			var url = $(this).data('href');
			$.ajax({
				url:url,
				dataType:'html',
				method:'GET',
				success: function(response){
					$("#modal_div").html(response).modal("show");
				}
			});
		});

		$('span.currency').each(function(index, element){
			var money = __formatCurrency($(this).text());
			$(this).text(money);
		});
	});
</script>
@endsection