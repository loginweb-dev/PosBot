@extends('layouts.app')
@section('content')
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-12">
				<div class="card card-outline card-info">
					<div class="card-header">
						<div class="card-title">
							<h3>
								<i class="fas fa-users-cog"></i>
								@lang('messages.all_users')
							</h3>
						</div>
						<button type="button" class="btn btn-sm btn-primary float-right" data-href="{{action('Superadmin\ManageUsersController@create')}}" id="add_user">
							<i class="fas fa-user-plus"></i>
							@lang('messages.add')
						</button>
					</div>
					<div class="card-body">
						<table class="table" id="users_table">
	                        <thead>
	                            <tr>
	                                <th>@lang('messages.name')</th>
	                                <th>@lang('messages.email')</th>
	                                <th>
	                                	@lang('messages.is_active')
	                                </th>
	                                <th>
	                                	@lang('messages.created_at')
	                                </th>
	                                <th>@lang('messages.action')</th>
	                            </tr>
	                        </thead>
	                        <tbody></tbody>
	                    </table>
					</div>
				</div>
			</div>
		</div>		
	</div>
	<div class="modal" id="user_modal" tabindex="-1" role="dialog"></div>
@endsection
@section('footer')
<script type="text/javascript">
	$(document).ready(function(){
		var users_table = $('#users_table').DataTable({
						processing: true,
		                serverSide: true,
		                ajax: '/superadmin/users',
		                buttons: [],
		                dom: 'lfrtip',
		                fixedHeader: false,
		                columns: [
		                    { data: 'name' , name: 'name'},
		                    { data: 'email' , name: 'email'},
		                    { data: 'is_active' , name: 'is_active'},
		                    { data: 'created_at', name: 'created_at'},
		                    { data: 'action', name: 'action', sortable:false }
		                ]
		});

		$(document).on('click', '.toggle_is_active', function(){
			url = $(this).data('href');
			$.ajax({
				method:"GET",
				url: url,
				dataType: "json",
				success:function(response) {
					if(response.success == true){
                        toastr.success(response.msg);
                        users_table.ajax.reload();
                    } else {    
                        toastr.error(response.msg);
                    }
				}
			});
		});

		$(document).on('click', '.delete_user', function () {
			var url = $(this).data('href');

			if (confirm('Are you sure?')) {
				$.ajax({
					method:"DELETE",
					url: url,
					dataType: "json",
					success:function(response) {
						if(response.success == true){
	                        toastr.success(response.msg);
	                        users_table.ajax.reload();
	                    } else {    
	                        toastr.error(response.msg);
	                    }
					}
				});
			}
		});

		$(document).on('click', '#add_user', function () {
			var url = $(this).data('href');
			$.ajax({
				method: "GET",
				url: url,
				dataType: "html",
				success: function (response) {
					$("#user_modal").html(response).modal('show');
				}
			});
		});

		$(document).on('click', '.edit_user', function () {
			var url = $(this).data('href');
			$.ajax({
				method: "GET",
				url: url,
				dataType: "html",
				success: function (response) {
					$("#user_modal").html(response).modal('show');
				}
			});
		});

		$("#user_modal").on('shown.bs.modal', function () {
			if ($("form#add_user_form").length) {
				$("form#add_user_form").validate({
					rules: {
			            email: {
			                email: true,
			                remote: {
			                    url: "/superadmin/users/check-email-exist",
			                    type: "post",
			                    data: {
			                        email: function() {
			                            return $( "#email" ).val();
			                        }
			                    }
			                }
			            }
			        },
			        messages: {
			            email: {
			                remote: '{{ __("validation.unique", ["attribute" => __("messages.email")]) }}'
			            }
			        }
		    	});
			}

			if ($("form#edit_user_form").length) {
				$("form#edit_user_form").validate({
					rules: {
			            email: {
			                email: true,
			                remote: {
			                    url: "/superadmin/users/check-email-exist",
			                    type: "post",
			                    data: {
			                        email: function() {
			                            return $( "#email" ).val();
			                        },
			                        user_id: $('input#user_id').val()
			                    }
			                }
			            }
			        },
			        messages: {
			            email: {
			                remote: '{{ __("validation.unique", ["attribute" => __("messages.email")]) }}'
			            }
			        }
		    	});
			}

			if ($("#form_design").length) {
				$(document).on('change', '#form_design', function(){
					if ($("#form_design").is(":checked")) {
						$("#form_view").attr('checked', true);
					} else {
						$("#form_view").attr('checked', false);
					}
				});
			}
		});

		$(document).on('submit', 'form#add_user_form', function (e) {
			e.preventDefault();
			var data = $("form#add_user_form").serialize();
			var url = $("form#add_user_form").attr('action');
			var ladda = Ladda.create(document.querySelector('.submit_btn'));
            ladda.start();
			$.ajax({
				method: "POST",
				url: url,
				dataType: "json",
				data: data,
				success: function (response) {
					ladda.stop();
					if (response.success) {
						$("#user_modal").modal('hide');
						toastr.success(response.msg);
                        users_table.ajax.reload();
					} else {
						toastr.error(response.msg);
					}
				}
			});
		});

		$(document).on('submit', 'form#edit_user_form', function (e) {
			e.preventDefault();
			var data = $("form#edit_user_form").serialize();
			var url = $("form#edit_user_form").attr('action');
			var ladda = Ladda.create(document.querySelector('.submit_btn'));
            ladda.start();
			$.ajax({
				method: "PUT",
				url: url,
				dataType: "json",
				data: data,
				success: function (response) {
					ladda.stop();
					if (response.success) {
						$("#user_modal").modal('hide');
						toastr.success(response.msg);
                        users_table.ajax.reload();
					} else {
						toastr.error(response.msg);
					}
				}
			});
		});
	});
</script>
@endsection