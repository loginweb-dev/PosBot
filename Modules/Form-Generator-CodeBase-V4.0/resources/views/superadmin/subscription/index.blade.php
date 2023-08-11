@extends('layouts.app')
@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card card-outline card-info">
				<div class="card-header">
					<div class="card-title">
						<h3>
							<i class="fas fa-sync-alt"></i>
							@lang('messages.package_subscription')
						</h3>
					</div>
				</div>
				<div class="card-body">
					<table class="table" id="package_subscription_table">
						<thead>
							<tr>
								<th>@lang('messages.user_name')</th>
								<th>@lang('messages.package_name')</th>
								<th>@lang('messages.status')</th>
								<th>@lang('messages.start_date')</th>
								<th>@lang('messages.end_date')</th>
								<th>@lang('messages.price')</th>
								<th>@lang('messages.paid_via')</th>
								<th>@lang('messages.transaction_id')</th>
								<th>@lang('messages.action')</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@section('footer')
<script type="text/javascript">
	$(document).ready(function(){
		var package_subscription_table = $("#package_subscription_table").DataTable({
				processing: true,
                serverSide: true,
                ajax: '/superadmin/package-subscription',
                buttons: [],
                dom: 'lfrtip',
                fixedHeader: false,
                columnDefs: [
	                {
	                    targets: [1],
	                    orderable: false,
	                    searchable: false,
	                },
	            ],
                columns: [
                    { data: 'user' , name: 'users.name'},
                    { data: 'package' , name: 'package'},
                    { data: 'status' , name: 'status'},
                    { data: 'start_date' , name: 'start_date'},
                    { data: 'end_date', name: 'end_date'},
                    { data: 'package_price' , name: 'package_price'},
                    { data: 'paid_via', name: 'paid_via'},
                    { data: 'payment_transaction_id' , name: 'payment_transaction_id'},
                    { data: 'action', name: 'action', sortable:false }
                ],
                "fnDrawCallback": function (oSettings) {
              __convert_currency_in_datatable($('#package_subscription_table'));
          }
		});

		$(document).on('click', '.edit_subscription', function(){
			var url = $(this).data('href');
			$.ajax({
                method: "GET",
                url: url,
                dataType: "html",
                success: function(response) {
                    $("#modal_div").html(response).modal("show");
                }
            });
		});

		$(document).on('submit', 'form#edit_subscription', function(e){
            e.preventDefault();
            var data = $('form#edit_subscription').serialize();
            var url = $('form#edit_subscription').attr('action');
            $.ajax({
                method:"PUT",
                url: url,
                data: data,
                dataType: "json",
                success:function(response) {
                    if(response.success == true){
                        toastr.success(response.msg);
                        package_subscription_table.ajax.reload();
                        $("#modal_div").modal("hide");
                    } else {    
                        toastr.error(response.msg);
                    }
                }
            });
	    });
	});
</script>
@endsection