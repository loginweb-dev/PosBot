@extends('layouts.app')
@section('content')
	<div class="container">
		<div class="card card-outline card-info">
			<div class="card-header mb-2">
				<h4>
					@lang('messages.pay_n_subscribe')
				</h4>
			</div>
			<div class="card-body">
				<h5>
        			{{$package->name}}

        			@php
						$price_interval = __('messages.'.$package->price_interval);
					@endphp
					@if($package->price != 0)
						(<span class="currency">
							{{$package->price}}
						</span>
						<small class="text-muted">
							@lang('messages.subscription_price',[
								'interval' => $package->interval,
								'price_interval' => $price_interval
							])
						</small>)
					@else
						(<span class="currency">
							0000
						</span>)
					@endif
        		</h5>
				<ul>
					<li>
						@if($package->no_of_active_forms != 0)
							<span>
								@lang('messages.no_of_forms',['active_form' => $package->no_of_active_forms])
								<i class="far fa-check-circle text-success small"></i>
							</span>
						@else
							@lang('messages.unlimited_forms')
							<i class="far fa-check-circle text-success small"></i>
						@endif
					</li>
					<li>
						@if($package->is_form_downloadable)
							@lang('messages.form_code_download')
							<i class="far fa-check-circle text-success small"></i>
						@else
							@lang('messages.form_code_download')
							<i class="far fa-times-circle text-danger small"></i>
						@endif
					</li>
				</ul>
				<ul class="list-group">
					@foreach($payment_gateways as $k => $v)
						<div class="list-group-item">
							<b>@lang('messages.pay_via', ['method' => $v])</b>
							
							<div class="row" id="paymentdiv_{{$k}}">
								@php 
									$view = 'payments.partials.pay_'.$k;
								@endphp
								@includeIf($view)
							</div>
						</div>
					@endforeach
				</ul>
			</div>
		</div>
	</div>
@endsection
@section('footer')
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
	$(document).ready(function(){
		//notification after payment failure
		@if (!empty(session('status')) && session('status')['success'] == 1)
            var msg = '{!!session('status')['msg']!!}';
            toastr.success(msg);
        @elseif(!empty(session('status')) && session('status')['success'] == 0)
        	var msg = '{!!session('status')['msg']!!}';
            toastr.error(msg);
        @endif
		$('span.currency').each(function(index, element){
			var money = __formatCurrency($(this).text());
			$(this).text(money);
		});
		// stripe payment integration
		var stripe = Stripe('{{config("constants.STRIPE_PUB_KEY")}}');
		var stripe_payment_session_id = ''
		@if(!empty($stripe_payment_session))
			stripe_payment_session_id = '{{$stripe_payment_session->id}}';
		@endif
		$(document).on('click', '#checkout-stripe', function() {
			stripe.redirectToCheckout({
			  // Make the id field from the Checkout Session creation API response
			  // available to this file, so you can provide it as parameter here
			  // instead of the CHECKOUT_SESSION_ID placeholder.
			  sessionId: stripe_payment_session_id
			}).then(function (result) {
			  // If `redirectToCheckout` fails due to a browser or network
			  // error, display the localized error message to your customer
			  // using `result.error.message`.
			  toastr.error(result.error.message);
			});
		});
	});
</script>
@endsection