@extends('layouts.app')
@section('content')
<div class="container">
	<div class="card card-outline card-info">
		<div class="card-header">
			<div class="card-title">
				<h4>
					<i class="fas fa-money-check"></i>
					@lang('messages.all_packages')
				</h4>
			</div>
			<div class="card-tools">
				<a href="{{action('Superadmin\PackageController@create')}}" class="btn btn-primary btn-sm float-right">
					<i class="fas fa-plus"></i>
					@lang('messages.create')
				</a>
			</div>
		</div>
		<div class="card-body">
			@if(count($packages) <= 0)
            	<div class="alert alert-danger" role="alert">
				 	<span class="text-white">@lang('messages.no_packages_found')</span>
				</div>
           	@endif
			<div class="row">
				@foreach($packages as $package)
					<div class="col-md-4">
						<div class="card card-outline card-success on_hover">
							<div class="card-header">
								<div class="text-center">
									{{$package->name}}
								</div>
								<div class="text-center">
									@if($package->is_active)
										<span class="badge badge-pill badge-success">
											@lang('messages.active')
										</span>
									@else
										<span class="badge badge-pill badge-danger">
											@lang('messages.inactive')
										</span>
									@endif
									<a href="{{action('Superadmin\PackageController@edit', ['id' => $package->id])}}" class="btn btn-link text-info">
										<i class="fa fa-edit"></i>
									</a>
									<a href="#" class="btn btn-link delete_package text-danger" data-url="{{action('Superadmin\PackageController@destroy', ['id' => $package->id])}}">
										<i class="fa fa-trash"></i>
									</a>
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
									<h3>
									<span class="currency">
										{{$package->price}}
									</span>
									<small class="text-muted">
										@lang('messages.subscription_price',[
											'interval' => $package->interval,
											'price_interval' => $price_interval
										])
									</small>
									</h3>
								@else
									<h3>
									@lang('messages.free_for_interval', [
										'interval' => $package->interval,
										'price_interval' => $price_interval
									])
									</h3>
								@endif
							</div>
							<div class="card-footer text-center">
							    {{$package->description}}
							</div>
						</div>
					</div>
					@if($loop->iteration%3 == 0)
	    				<div class="clearfix"></div>
	    			@endif
				@endforeach
			</div>
			<div class="col-md-12">
                {{ $packages->links() }}
            </div>
		</div>
	</div>
</div>
@endsection
@section('footer')
<script type="text/javascript">
	$(document).ready(function(){
		
		$('span.currency').each(function(index, element){
			var money = __formatCurrency($(this).text());
			$(this).text(money);
		});

		$(document).on('click', '.delete_package', function(){
			var url = $(this).data('url');
			var is_confirmed = confirm('Are You Sure?');
			if (is_confirmed) {
				$.ajax({
                    method: "DELETE",
                    url: url,
                    dataType: "json",
                    success: function(result){
                        if(result.success == true){
                            toastr.success(result.msg);
                            setTimeout(() => {
	                            location.reload();
	                        }, 1100);
                        } else {    
                            toastr.error(result.msg);
                        }
                    }
                });
			}
		});
	});
</script>
@endsection