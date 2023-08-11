@extends('layouts.app')
@section('content')
	<div class="container">
		<div class="col-md-12">
			<div class="card card-outline card-primary">
				<div class="card-header">
					<div class="card-title">
						<h3>@lang('messages.edit_package')</h3>
					</div>
				</div>
				<div class="card-body">
					<form id="edit_package_form" action="{{action('Superadmin\PackageController@update', ['id' => $package->id])}}">
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="name">
										@lang('messages.name') *
									</label>
									<input type="text" name="name" class="form-control" id="name" value="{{$package->name}}" required>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="description">
										@lang('messages.description') *
									</label>
									<input type="text" name="description" class="form-control" id="description" value="{{$package->description}}" maxlength="100" required>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-4">
								<div class="form-group">
									<label for="price_interval">
										@lang('messages.price_interval') *
									</label>
									<select class="form-control" name="price_interval" id="price_interval" required>
										<option value="">
											@lang('messages.select_price_interval')
										</option>
										@foreach($lists as $key => $value)
											<option value="{{$key}}"
												@if($key == $package->price_interval)
													selected
												@endif
												>
												{{$value}}
											</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="interval">
										@lang('messages.interval') *
									</label>
									<input type="number" name="interval" class="form-control" id="interval" required value="{{$package->interval}}">
								</div>
							</div>
							<div class="col-md-4">
								<div class="form-group">
									<label for="price">
										@lang('messages.price')
									</label>
									<input type="number" name="price" class="form-control" id="price" value="{{$package->price}}" aria-describedby="price_help_text">
									<small id="price_help_text" class="form-text text-muted">
										@lang('messages.free_package_help_text')
									</small>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="no_of_active_forms">
										@lang('messages.no_of_active_forms')
									</label>
									<input type="number" name="no_of_active_forms" class="form-control" id="no_of_active_forms" value="{{$package->no_of_active_forms}}" aria-describedby="no_of_active_forms_help">
									<small id="no_of_active_forms_help" class="form-text text-muted">
										@lang('messages.unlimited_form_help_text')
									</small>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="sort_order">
										@lang('messages.sort_order') *
									</label>
									<input type="number" name="sort_order" class="form-control" id="sort_order" value="{{$package->sort_order}}" required>
									<small class="form-text text-muted">
										@lang('messages.sort_order_help_text')
									</small>
								</div>
							</div>
						</div>
						<div class="form-row">
							<div class="col-md-4">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" name="is_form_downloadable" id="is_form_downloadable" value="1" @if($package->is_form_downloadable) checked @endif>
									<label class="custom-control-label" for="is_form_downloadable">
										@lang('messages.allow_form_code_downloading')
									</label>
								</div>
							</div>
							<div class="col-md-4">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" name="is_active" id="is_active" value="1" @if($package->is_active) checked @endif>
									<label class="custom-control-label" for="is_active">
										@lang('messages.activate')
									</label>
								</div>
							</div>
						</div>
						<button type="submit" class="btn btn-success submit_btn float-right">
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
		$(document).ready(function() {
			$('form#edit_package_form').validate({
				submitHandler: function(form, e) {
					e.preventDefault();
					var data = $('form#edit_package_form').serialize();
					var url = $('form#edit_package_form').attr('action');
					if ($('form#edit_package_form').valid()) {
						$.ajax({
							method:"PUT",
							url: url,
							data: data,
							dataType: "json",
							success:function(response) {
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