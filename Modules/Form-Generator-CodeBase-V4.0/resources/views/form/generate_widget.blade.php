<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
            @lang('messages.generate_widget')
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="widget">
                    @lang('messages.iframe_code')
                </label>
                <textarea class="form-control form-control-sm" id="widget" rows="4" aria-describedby="widget_help" readonly>{{$widget}}</textarea>
                <small id="widget_help" class="form-text text-muted">
                    @lang('messages.widget_help_text')
                </small>
                <textarea class="form-control form-control-sm" id="widget" rows="4" aria-describedby="widget_script_help" readonly>{{$widget_script}}</textarea>
                <small id="widget_script_help" class="form-text text-muted">
                    @lang('messages.widget_script_help_text')
                </small>
            </div>
            <div class="form-group">
                <label>@lang('messages.new_window')</label>
                <textarea class="form-control form-control-sm" id="new_window" rows="4" aria-describedby="new_window_help" readonly>{{$form}}</textarea>
                <small id="widget_help" class="form-text text-muted">
                    @lang('messages.new_window_help_text')
                </small>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                @lang('messages.close')
            </button>
        </div>
    </div>
</div>