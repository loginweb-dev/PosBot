<div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">
            @lang('messages.view_package')
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="col-md-7 offset-md-2">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <div class="text-center">
                        {{$subscription->package_details['name']}}
                    </div>
                </div>
                <div class="card-body text-center">
                    @if($subscription->package_details['no_of_active_forms'] != 0)
                        <span>
                            <i class="far fa-check-circle text-success"></i>
                            @lang('messages.no_of_forms',[
                        'active_form' => $subscription->package_details['no_of_active_forms']])
                        </span>
                    @else
                        <span>
                            <i class="far fa-check-circle text-success"></i>
                            @lang('messages.unlimited_forms')
                        </span>
                    @endif
                    <br>
                    @if($subscription->package_details['is_form_downloadable'])
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
                    <br><br>
                    @php
                        $price_interval = __('messages.'.$subscription->package_details['price_interval']);
                    @endphp
                    @if($subscription->package_price != 0)
                        <h4>
                            <span class="convert_currency">
                                {{$subscription->package_price}}
                            </span>
                            <small class="text-muted">
                                @lang('messages.subscription_price',[
                                    'interval' => $subscription->package_details['interval'],
                                    'price_interval' => $price_interval
                                ])
                            </small>
                        </h4>
                    @else
                        <h4>
                            @lang('messages.free_for_interval', [
                                'interval' => $subscription->package_details['interval'],
                                'price_interval' => $price_interval
                            ])
                        </h4>
                    @endif
                </div>
                <div class="card-footer text-center">
                    {{$subscription->package_details['description']}}
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
               <strong>@lang('messages.start_date'): </strong> {{\Carbon\Carbon::parse($subscription->start_date)->isoFormat("D/M/YY")}}
            </div>
            <div class="col">
                <strong>@lang('messages.end_date'): </strong>{{\Carbon\Carbon::parse($subscription->end_date)->isoFormat("D/M/YY")}}
            </div>
        </div>
        <div class="row">
            <div class="col">
                <strong>@lang('messages.paid_via'): </strong>
                @if($subscription->paid_via == "offline")
                    @lang("messages.offline")
                @else
                    {{ucfirst($subscription->paid_via)}}
                @endif
            </div>
            <div class="col">
                <strong>@lang('messages.transaction_id'): </strong>
                {{$subscription->payment_transaction_id}}
            </div>
        </div>
        <div class="row">
            <div class="col">
                <strong>@lang('messages.status'): </strong>
                {{__('messages.'.$subscription->status)}}
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
            @lang('messages.close')
        </button>
      </div>
    </div>
  </div>
  <script type="text/javascript">
      var money = $(document).find('span.convert_currency');
      money.text(__formatCurrency(money.text()));
  </script>