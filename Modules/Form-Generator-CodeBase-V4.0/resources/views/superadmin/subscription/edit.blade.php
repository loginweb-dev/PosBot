<div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
            @lang('messages.edit_subscription')
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="edit_subscription" action="{{action('Superadmin\PackageSubscriptionsController@update', [$subscription['id']])}}" method="PUT">
            <div class="form-group">
                <label for="start_date">
                    @lang('messages.start_date')
                </label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{$subscription['start_date']}}" required>
            </div>
            <div class="form-group">
                <label for="end_date">
                    @lang('messages.end_date')
                </label>
                <input type="date" name="end_date" id="end_date" class="form-control" value="{{$subscription['end_date']}}" required>
            </div>
            <div class="form-group">
                <label for="status">
                    @lang('messages.status')
                </label>
                <select name="status" id="status" class="form-control">
                    @foreach($status_list as $key=>$value)
                        <option value="{{$key}}"
                            @if($key == $subscription['status'])
                                selected 
                            @endif
                            >
                            {{$value}}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="transaction_id">
                    @lang('messages.transaction_id')
                </label>
                <input type="text" name="payment_transaction_id" id="transaction_id" class="form-control" value="{{$subscription['payment_transaction_id']}}">
            </div>
            <button type="submit" class="btn btn-primary btn-sm float-right m-1">
                @lang('messages.update')
            </button>
            <button type="button" class="btn btn-secondary btn-sm float-right m-1" data-dismiss="modal">
                @lang('messages.close')
            </button>
        </form>
      </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('form#edit_subscription').validate();
    });
</script>