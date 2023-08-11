@extends('layouts.app')

@section('content')
<div class="container">
	<section class="content-header">
		<h1>
			@lang('messages.my_settings')
		</h1>
	</section>
	<div class="col-md-12">
		<form id="settings_form">
			<div class="card card-outline card-primary">
				<div class="card-body">
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
								<select id="language" class="form-control" name="language" required>
									@foreach($settings['languages'] as $key => $language)
									<option value="{{$key}}" 
										@if($key == $settings['language'])
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
									<select class="form-control" name="timezone" required id="APP_TIMEZONE">
										<option value="">
											@lang('messages.choose_timezone')
										</option>
										@foreach($settings['timezones'] as $timezone)
										<option value="{{$timezone}}" 
											@if($timezone == $settings['timezone'])
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
				</div>
			</div>
			<div class="card card-outline card-primary">
					<div class="card-header">
						<div class="card-title">
							<h4>
								@lang('messages.smtp_email_settings')
								<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" title="{{__('messages.my_settings_smtp_tooltips')}}"></i>
							</h4>
						</div>
					</div>
					<div class="card-body">
						<div class="form-row">
							<div class="form-group col-md-4">
								<label for="MAIL_HOST">
									@lang('messages.mail_host') *
								</label>
								<input type="text" class="form-control" name="MAIL_HOST" id="MAIL_HOST" placeholder="{{__('messages.enter_mail_host')}}" required value="{{$settings['smtp']['MAIL_HOST']}}">
							</div>
							<div class="form-group col-md-4">
								<label for="MAIL_PORT">
									@lang('messages.mail_port') *
								</label>
								<input type="text" class="form-control" name="MAIL_PORT" id="MAIL_PORT" placeholder="{{__('messages.enter_mail_port')}}" required value="{{$settings['smtp']['MAIL_PORT']}}">
							</div>
							<div class="form-group col-md-4">
								<label for="MAIL_USERNAME">
									@lang('messages.mail_username') *
								</label>
								<input type="text" class="form-control" name="MAIL_USERNAME" id="MAIL_USERNAME" placeholder="{{__('messages.enter_mail_username')}}" required value="{{$settings['smtp']['MAIL_USERNAME']}}">
							</div>
						</div>

						<div class="form-row">

							<div class="form-group col-md-4">
								<label for="MAIL_FROM_ADDRESS">
									@lang('messages.mail_from_address') *
								</label>
								<input type="text" class="form-control" name="MAIL_FROM_ADDRESS" id="MAIL_FROM_ADDRESS" placeholder="{{__('messages.mail_from_address')}}" value="{{$settings['smtp']['MAIL_FROM_ADDRESS']}}" required>
							</div>

							<div class="form-group col-md-4">
								<label for="MAIL_FROM_NAME">
									@lang('messages.mail_from_name') *
								</label>
								<input type="text" class="form-control" name="MAIL_FROM_NAME" id="MAIL_FROM_NAME" placeholder="{{__('messages.mail_from_name')}}" value="{{$settings['smtp']['MAIL_FROM_NAME']}}" required>
							</div>
							
							<div class="form-group col-md-4">
								<label for="MAIL_PASSWORD">
									@lang('messages.mail_password') *
								</label>
								<input type="text" class="form-control" name="MAIL_PASSWORD" id="MAIL_PASSWORD" placeholder="{{__('messages.enter_mail_password')}}" required value="{{$settings['smtp']['MAIL_PASSWORD']}}">
							</div>
							<div class="form-group col-md-4">
								<label for="MAIL_ENCRYPTION">
									@lang('messages.mail_encryption') *
								</label>
								<input type="text" class="form-control" name="MAIL_ENCRYPTION" id="MAIL_ENCRYPTION" placeholder="{{__('messages.enter_mail_encryption')}}" required value="{{$settings['smtp']['MAIL_ENCRYPTION']}}">
							</div>
						</div>
						<button type="submit" class="btn btn-sm btn-success submit_btn float-right m-1">
							@lang('messages.update')
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
							url: "/post-user-settings",
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
					alert('Please fill all the SMTP detials.');
		        }
			});
		});
</script>
@endsection