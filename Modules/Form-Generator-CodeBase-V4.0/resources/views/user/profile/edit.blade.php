@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-md-12">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<div class="card-title">
						<h3>@lang('messages.edit_profile')</h3>
					</div>
				</div>
				<div class="card-body">
					<form id="edit_profile" action="{{action('ManageProfileController@postProfile', [$user['id']])}}">
						<div class="form-row">
							<div class="form-group col-md-4">
								<label for="name">
									@lang('messages.name') *
								</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-user-tie"></i>
										</span>
									</div>
									<input type="text" class="form-control" name="name" id="name" placeholder="{{__('messages.enter_name')}}" value="{{$user['name']}}" required>
								</div>
							</div>

							<div class="form-group col-md-4">
								<label for="email">
									@lang('messages.email') *
								</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-envelope"></i>
										</span>
									</div>
									<input type="email" class="form-control" name="email" id="email" placeholder="{{__('messages.enter_email')}}" value="{{$user['email']}}" required>
								</div>
							</div>

							<div class="form-group col-md-4">
								<label for="password">
									@lang('messages.password')
								</label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-user-lock"></i>
										</span>
									</div>
									<input type="password" class="form-control" name="password" id="password" placeholder="{{__('messages.enter_password')}}" value="" aria-describedby="passwordHelp">
								</div>
								<small id="passwordHelp" class="form-text text-muted">
							 		@lang('messages.password_edit_help')
								</small>
							</div>
						</div>
						<button type="submit" class="btn btn-success float-right submit_btn">
						 	@lang('messages.update')
						</button>
					</form>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('footer')
	<script type="text/javascript">
		$(document).ready(function(){
			$('form#edit_profile').validate({
				submitHandler: function(form, e) {
					var data = $('form#edit_profile').serialize();
					var url = $('form#edit_profile').attr('action');
					if ($('form#edit_profile').valid()) {
						$.ajax({
							method:"PUT",
							url: url,
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
		});
	</script>
@endsection