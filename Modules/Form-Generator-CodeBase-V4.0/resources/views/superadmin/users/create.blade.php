<div class="modal-dialog modal-lg">
    <form id="add_user_form" action="{{action('Superadmin\ManageUsersController@store')}}" method="POST">
        {{ csrf_field() }}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @lang('messages.add_user')
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">
                                @lang('messages.name')
                                <span class="error">*</span>
                            </label>

                            <input type="text" class="form-control"
                            name="name" id="name" required>
                        </div>    
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">
                                @lang('messages.email')
                                <span class="error">*</span>
                            </label>

                            <input type="email" class="form-control"
                            name="email" id="email" required>
                        </div>    
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="password">
                                @lang('messages.password')
                                <span class="error">*</span>
                            </label>

                            <input type="password" class="form-control"
                            name="password" id="password" required>
                        </div>    
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                            <label class="form-check-label" for="is_active">
                                @lang('messages.is_active')
                                <i class="fas fa-info-circle text-info" data-toggle="tooltip" title="@lang('messages.is_active_tooltip')"></i>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" name="send_email" id="send_email" value="1">
                            <label class="form-check-label" for="send_email">
                                @lang('messages.send_email')
                                <i class="fas fa-info-circle text-info" data-toggle="tooltip" title="@lang('messages.send_email_tooltip')"></i>
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" name="can_create_form" id="can_create_form" value="1">
                            <label class="form-check-label" for="can_create_form">
                                @lang('messages.can_create_form')
                                <i class="fas fa-info-circle text-info" data-toggle="tooltip" title="@lang('messages.can_create_form_tooltip')"></i>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card assign-form p-3">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="assign_form">
                                    @lang('messages.assign_forms'):
                                </label>
                                <select multiple class="form-control" id="assign_form" name="form_id[]">
                                    @foreach($forms as $key => $value)
                                        <option value="{{$key}}">
                                            {{$value}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <h5>@lang('messages.permission_for_forms'):</h5>
                    <div class="row">                    
                        <div class="col-md-4">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" name="permissions[]" id="form_design" value="can_design_form">
                                <label class="form-check-label" for="form_design">
                                    @lang('messages.can_design_form')
                                    <i class="fas fa-info-circle text-info" data-toggle="tooltip" title="@lang('messages.can_design_form_tooltip')"></i>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" name="permissions[]" id="form_data" value="can_view_data">
                                <label class="form-check-label" for="form_data">
                                    @lang('messages.can_view_data')
                                    <i class="fas fa-info-circle text-info" data-toggle="tooltip" title="@lang('messages.can_view_data_tooltip')"></i>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group form-check">
                                <input type="checkbox" class="form-check-input" name="permissions[]" id="form_view" value="can_view_form">
                                <label class="form-check-label" for="form_view">
                                    @lang('messages.can_view_form')
                                    <i class="fas fa-info-circle text-info" data-toggle="tooltip" title="@lang('messages.can_view_form_tooltip')"></i>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-primary submit_btn">
                    @lang('messages.save')
                </button>
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
                    @lang('messages.close')
                </button>
            </div>
        </div>
    </form>
</div>