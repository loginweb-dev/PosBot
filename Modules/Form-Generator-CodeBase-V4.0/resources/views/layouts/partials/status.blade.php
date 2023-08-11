@if (session('status') && isset(session('status')['success']))
	@if(session('status')['success'] == 1)
		<div class="alert alert-success" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    			<span aria-hidden="true">&times;</span>
  			</button>
  			{!!session('status')['msg']!!}
		</div>
	@else
		<div class="alert alert-danger" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
    			<span aria-hidden="true">&times;</span>
  			</button>
			{!!session('status')['msg']!!}
		</div>
	@endif
@endif