@extends('layouts.app')

@section('content')
	<div class="container">
		<section class="content-header">
			<h1>
				@lang('messages.system_settings')
			</h1>
		</section>
        <div class="col-md-12">
			<form id="settings_form">
				<div class="card card-outline card-primary">
					<div class="card-body">
						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="APP_NAME">
									@lang('messages.app_name') *
								</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="fa fa-suitcase"></i>
										</span>
									</div>
									<input type="text" class="form-control" name="APP_NAME" id="APP_NAME" placeholder="{{__('messages.enter_app_name')}}" value="{{$settings['APP_NAME']}}" required>
								</div>
							</div>
							<div class="form-group col-md-6">
								<label for="APP_TITLE">
									@lang('messages.app_title') *
								</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-tag"></i>
										</span>
									</div>
									<input type="text" class="form-control" name="APP_TITLE" id="APP_TITLE" placeholder="{{__('messages.enter_app_title')}}" value="{{$settings['APP_TITLE']}}" required>
								</div>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-6">
								<label for="language">
									@lang('messages.language') *
								</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-language"></i>
										</span>
									</div>
									<select id="language" class="form-control" name="APP_LOCALE" required>
										@foreach($settings['APP_LOCALE'] as $key => $language)
										<option value="{{$key}}" 
											@if($key == env('APP_LOCALE'))
												selected
											@endif>
											{{$language['full_name']}}
										</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="APP_TIMEZONE">
										@lang('messages.timezone') *
									</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="far fa-hourglass"></i>
											</span>
										</div>
										<select class="form-control" name="APP_TIMEZONE" required id="APP_TIMEZONE">
											<option value="">
												@lang('messages.choose_timezone')
											</option>
											@foreach($settings['timezones'] as $timezone)
											<option value="{{$timezone}}" 
												@if($timezone == $settings['APP_TIMEZONE'])
													selected
												@endif>
												{{$timezone}}
											</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="CURRENCY_NAME">
										@lang('messages.currency_name') *
									</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="far fa-money-bill-alt"></i>
											</span>
										</div>
										<input type="text" name="CURRENCY_NAME" id="CURRENCY_NAME" class="form-control" value="{{$settings['CURRENCY_NAME']}}" required placeholder="{{__('messages.enter_currency_name')}}">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="CURRENCY_CODE">
										@lang('messages.currency_code') *
									</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="far fa-money-bill-alt"></i>
											</span>
										</div>
										<input type="text" name="CURRENCY_CODE" id="CURRENCY_CODE" class="form-control" value="{{$settings['CURRENCY_CODE']}}" required
										placeholder="{{__('messages.enter_currency_code')}}">
									</div>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="CURRENCY_SYMBOL">
										@lang('messages.currency_symbol') *
									</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="far fa-money-bill-alt"></i>
											</span>
										</div>
										<input type="text" name="CURRENCY_SYMBOL" id="CURRENCY_SYMBOL" class="form-control" value="{{$settings['CURRENCY_SYMBOL']}}" required
										placeholder="{{__('messages.enter_currency_symbol')}}">
									</div>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="APP_DATE_FORMAT">
										@lang('messages.date_format') *
									</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="fas fa-calendar-day"></i>
											</span>
										</div>
										<select class="form-control" name="APP_DATE_FORMAT" required id="APP_DATE_FORMAT">
											@foreach($date_formats as $key => $value)
												<option value="{{$key}}"
													@if($key == config('constants.APP_DATE_FORMAT'))
														selected
													@endif>
													{{$value}}
												</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="col-sm-4">
							    <div class="form-group">
							        <label for="APP_TIME_FORMAT">
										@lang('messages.time_format') *
									</label>
							        <div class="input-group">
							        	<div class="input-group-prepend">
											<span class="input-group-text">
												<i class="far fa-clock"></i>
											</span>
										</div>
							            <select class="form-control" name="APP_TIME_FORMAT" required id="APP_TIME_FORMAT">
							            	<option value="12"
							            		@if(config('constants.APP_TIME_FORMAT') == '12')
													selected
												@endif>
							            		@lang('messages.12_hour')
							            	</option>
							            	<option value="24"
							            		@if(config('constants.APP_TIME_FORMAT') == '24')
													selected
												@endif>
							            		@lang('messages.24_hour')
							            	</option>
							            </select>
							        </div>
							    </div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-4">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" name="ENABLE_REGISTRATION" id="ENABLE_REGISTRATION" value="1" @if($settings['ENABLE_REGISTRATION']) checked @endif>
									<label class="custom-control-label" for="ENABLE_REGISTRATION">
										@lang('messages.enable_registration')
										<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="{{__('messages.resgistration_will_be_enabled')}}"></i>
									</label>
								</div>
							</div>
							@if($__type_e)
								<div class="col-md-4">
									<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" name="ENABLE_SAAS_MODULE" id="ENABLE_SAAS_MODULE" value="1" @if($settings['ENABLE_SAAS_MODULE']) checked @endif>
										<label class="custom-control-label" for="ENABLE_SAAS_MODULE">
											@lang('messages.enable_saas_module')
										</label>
									</div>
								</div>
							@endif
						</div>
					</div>
				</div>
				@if($__enable_saas)
					<div class="card card-outline card-primary">
						<div class="card-header">
							<div class="card-title">
								<h4>
									@lang('messages.payment_api_settings')
								</h4>
							</div>
						</div>
						<div class="card-body">
							<h3 class="card-title">Paypal</h3><br><br>
							<div class="form-row">
								<div class="col-md-4">
									<div class="form-group">
									    <label for="PAYPAL_MODE">
									    	PAYPAL MODE *
									    </label>
									    <select class="form-control" name="PAYPAL_MODE" id="PAYPAL_MODE" required>
									      	<option value="sandbox"
									      		@if($settings['PAYPAL_MODE'] == 'sandbox')
									      			selected
									      		@endif
									      		>
									      		Sandbox
									      	</option>
									      	<option value="live"
									      		@if($settings['PAYPAL_MODE'] == 'live')
									      			selected
									      		@endif
									      		>
									      		Live
									      	</option>
									    </select>
									</div>
								</div>
							</div>
							<div class="form-row">
								<!-- live mode -->
								<div class="col-md-4">
									<div class="form-group">
										<label for="PAYPAL_LIVE_API_USERNAME">
											PAYPAL LIVE API USERNAME
										</label>
										<input type="text" class="form-control" name="PAYPAL_LIVE_API_USERNAME" id="PAYPAL_LIVE_API_USERNAME" placeholder="PAYPAL LIVE API USERNAME" value="{{$settings['PAYPAL_LIVE_API_USERNAME']}}">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="PAYPAL_LIVE_API_PASSWORD">
											PAYPAL LIVE API PASSWORD
										</label>
										<input type="text" class="form-control" name="PAYPAL_LIVE_API_PASSWORD" id="PAYPAL_LIVE_API_PASSWORD" placeholder="PAYPAL LIVE API PASSWORD" value="{{$settings['PAYPAL_LIVE_API_PASSWORD']}}">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="PAYPAL_LIVE_API_SECRET">
											PAYPAL LIVE API SECRET
										</label>
										<input type="text" class="form-control" name="PAYPAL_LIVE_API_SECRET" id="PAYPAL_LIVE_API_SECRET" placeholder="PAYPAL LIVE API SECRET" value="{{$settings['PAYPAL_LIVE_API_SECRET']}}">
									</div>
								</div>
								<!-- sandbox mode -->
								<div class="col-md-4">
									<div class="form-group">
										<label for="PAYPAL_SANDBOX_API_USERNAME">
											PAYPAL SANDBOX API USERNAME
										</label>
										<input type="text" class="form-control" name="PAYPAL_SANDBOX_API_USERNAME" id="PAYPAL_SANDBOX_API_USERNAME" placeholder="PAYPAL SANDBOX API USERNAME" value="{{$settings['PAYPAL_SANDBOX_API_USERNAME']}}">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="PAYPAL_SANDBOX_API_PASSWORD">
											PAYPAL SANDBOX API PASSWORD
										</label>
										<input type="text" class="form-control" name="PAYPAL_SANDBOX_API_PASSWORD" id="PAYPAL_SANDBOX_API_PASSWORD" placeholder="PAYPAL SANDBOX API PASSWORD" value="{{$settings['PAYPAL_SANDBOX_API_PASSWORD']}}">
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="PAYPAL_SANDBOX_API_SECRET">
											PAYPAL SANDBOX API SECRET
										</label>
										<input type="text" class="form-control" name="PAYPAL_SANDBOX_API_SECRET" id="PAYPAL_SANDBOX_API_SECRET" placeholder="PAYPAL SANDBOX API SECRET" value="{{$settings['PAYPAL_SANDBOX_API_SECRET']}}">
									</div>
								</div>
							</div>
						</div>
						<div class="card-body">
							<h3 class="card-title">Stripe</h3><br><br>
							<div class="form-row">
								<div class="col-md-4">
									<div class="form-group">
										<label for="STRIPE_PUB_KEY">
											STRIPE PUBLISHABLE KEY
										</label>
										<input type="text" class="form-control" name="STRIPE_PUB_KEY" id="STRIPE_PUB_KEY" placeholder="STRIPE PUBLISHABLE KEY" value="{{$settings['STRIPE_PUB_KEY']}}">
										<small id="help_text" class="form-text text-muted">
										  {{trans('messages.to_see_supported_curreny')}}
							      			<a href="https://stripe.com/docs/currencies" target="_blank">{{trans('messages.click_here')}}.</a>
										</small>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label for="STRIPE_SECRET_KEY">
											STRIPE SECRET KEY
										</label>
										<input type="text" class="form-control" name="STRIPE_SECRET_KEY" id="STRIPE_SECRET_KEY" placeholder="STRIPE SECRET KEY" value="{{$settings['STRIPE_SECRET_KEY']}}">
									</div>
								</div>
							</div>
						</div>
					</div>
				@endif
				<div class="card card-outline card-primary">
					<div class="card-header">
						<div class="card-title">
							<h4>
								@lang('messages.smtp_email_settings')
								<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="{{__('messages.system_settings_smtp_tooltips')}}"></i>
							</h4>
						</div>
					</div>
					<div class="card-body">
						<div class="form-row">
							<div class="form-group col-md-4">
								<label for="MAIL_HOST">
									@lang('messages.mail_host') *
								</label>
								<input type="text" class="form-control" name="MAIL_HOST" id="MAIL_HOST" placeholder="{{__('messages.enter_mail_host')}}" value="{{$settings['MAIL_HOST']}}" required>
							</div>
							<div class="form-group col-md-4">
								<label for="MAIL_PORT">
									@lang('messages.mail_port') *
								</label>
								<input type="text" class="form-control" name="MAIL_PORT" id="MAIL_PORT" placeholder="{{__('messages.enter_mail_port')}}" value="{{$settings['MAIL_PORT']}}" required>
							</div>
							<div class="form-group col-md-4">
								<label for="MAIL_USERNAME">
									@lang('messages.mail_username') *
								</label>
								<input type="text" class="form-control" name="MAIL_USERNAME" id="MAIL_USERNAME" placeholder="{{__('messages.enter_mail_username')}}" value="{{$settings['MAIL_USERNAME']}}" required>
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-4">
								<label for="MAIL_FROM_ADDRESS">
									@lang('messages.mail_from_address') *
								</label>
								<input type="text" class="form-control" name="MAIL_FROM_ADDRESS" id="MAIL_FROM_ADDRESS" placeholder="{{__('messages.mail_from_address')}}" value="{{$settings['MAIL_FROM_ADDRESS']}}" required>
							</div>

							<div class="form-group col-md-4">
								<label for="MAIL_FROM_NAME">
									@lang('messages.mail_from_name') *
								</label>
								<input type="text" class="form-control" name="MAIL_FROM_NAME" id="MAIL_FROM_NAME" placeholder="{{__('messages.mail_from_name')}}" value="{{$settings['MAIL_FROM_NAME']}}" required>
							</div>

							<div class="form-group col-md-4">
								<label for="MAIL_PASSWORD">
									@lang('messages.mail_password') *
								</label>
								<input type="text" class="form-control" name="MAIL_PASSWORD" id="MAIL_PASSWORD" placeholder="{{__('messages.enter_mail_password')}}" value="{{$settings['MAIL_PASSWORD']}}" required>
							</div>
							<div class="form-group col-md-4">
								<label for="MAIL_ENCRYPTION">
									@lang('messages.mail_encryption') *
								</label>
								<input type="text" class="form-control" name="MAIL_ENCRYPTION" id="MAIL_ENCRYPTION" placeholder="{{__('messages.enter_mail_encryption')}}" value="{{$settings['MAIL_ENCRYPTION']}}" required>
							</div>
						</div>
						<button type="submit" class="btn btn-success btn-sm submit_btn float-right m-1">
							@lang('messages.save')
						</button>
						<button type="button" 
							class="btn btn-sm btn-outline-primary ladda-button btn-test-smtp float-right m-1"
							data-style="expand-right"
							data-spinner-color="blue"
							>
							<span class="ladda-label">
								{{trans('messages.test_smtp_details')}}
							</span>
						</button>	
					</div>
				</div>
			</form>
        </div>
    </div>
@endsection
@section('footer')
	<script type="text/javascript">
		$(document).ready(function(){

			$('form#settings_form').validate({
				submitHandler: function(form, e) {
					var data = $('form#settings_form').serialize();
					if ($('form#settings_form').valid()) {
						$.ajax({
							method:"POST",
							url: "/superadmin/superadmin-settings",
							data: data,
							dataType: "json",
							success: function(response) {
								if(response.success == true){
			                        window.location = response.redirect;
		                        } else {    
		                            toastr.error(response.msg);
		                        }
							}
						});
					}
				}
			});

			$(document).on('click', '.btn-test-smtp', function(){

				var smtpdata = {
					'host': $("input[name=MAIL_HOST]").val(),
					'port' : $("input[name=MAIL_PORT]").val(),
					'from_address' : $("input[name=MAIL_FROM_ADDRESS]").val(),
					'from_name' : $("input[name=MAIL_FROM_NAME]").val(),
					'encryption' : $("input[name=MAIL_ENCRYPTION]").val(),
					'username' : $("input[name=MAIL_USERNAME]").val(),
					'password' : $("input[name=MAIL_PASSWORD]").val(),
				};

				var isValid = true;
				_.forEach(smtpdata, function(smtp){
					
					if (!_.isEmpty(smtp)) {
						isValid = true && isValid;
					} else {
						isValid = false;
					}
				});

				if (isValid) {
					var ladda = Ladda.create($('.btn-test-smtp')[0]);
		            ladda.start();
		            $.ajax({
						method:"GET",
						url: "/test-smtp",
						data: smtpdata,
						dataType: "json",
						success: function(response) {
							ladda.stop();
							if(response.success == true){
		                        toastr.success(response.msg);
	                        } else {    
	                            toastr.error(response.msg);
	                        }
						}
					});
		        } else {
		        	isValid = true;
					alert('Please fill all the SMTP details.');
		        }
			});

		});
	</script>
@endsection