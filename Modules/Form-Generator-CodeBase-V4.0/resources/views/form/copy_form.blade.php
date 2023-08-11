<div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
                @lang('messages.copy_form') ({{$form->name}})
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @if(!isset($subscription_info['success']))
            <form id="copy_form" action="{{action('FormController@store')}}" method="POST">
                {{ csrf_field() }}
                <div class="form-row pb-4">
                    <div class="col-md-4">
                        <label for="name">
                            @lang('messages.form_name')
                            <span class="error">*</span>
                        </label>
                        <input type="text" class="form-control"
                        name="name" id="name" required>
                        <input type="hidden" name="id" value="{{$form->id}}">
                    </div>

                    <div class="col-md-4">
                        <label for="form_slug">
                            @lang('messages.form_slug')
                            <span class="error">*</span>
                        </label>

                        <input type="text" class="form-control"
                        name="slug" id="form_slug" required readonly>
                    </div>

                    <div class="col-md-4">
                        <label for="description">
                            @lang('messages.form_description')
                        </label>
                        <input type="text" name="description" id="description" class="form-control">
                    </div>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary btn-sm float-right">
                    @lang('messages.copy_form')
                </button>
                <button type="button" class="btn btn-secondary btn-sm float-right mr-2" data-dismiss="modal">
                    @lang('messages.close')
                </button>
            </form>
            @else
                <div class="alert alert-danger" role="alert">
                    <div class="row">
                        <div class="col-md-10">
                            {!!$subscription_info['msg']!!}
                        </div>
                        @if($subscription_info['subscribe_btn'])
                            <div class="col-md-2">
                                <a href="{{action('SubscriptionsController@index')}}" class="btn btn-primary text-deco-none float-right btn-sm">
                                    @lang('messages.subscribe')
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <button type="button" class="btn btn-secondary btn-sm float-right mr-2" data-dismiss="modal">
                    @lang('messages.close')
                </button>
            @endif
        </div>
    </div>
</div>
<script type="text/javascript">
    $('form#copy_form').validate({
        submitHandler: function(form) {
            $('#modal_div').modal('hide')
            form.submit();
        }
    });
    
    $(document).on('change', 'input#name',function() {
        var name = $('form#copy_form input#name').val();
        $.ajax({
            method: 'GET',
            url: '/generate-form-slug',
            dataType: 'json',
            data: {'name' : name},
            success: function(response) {
                $('form#copy_form input#form_slug').val(response);
            }
        });
    });
</script>