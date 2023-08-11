<div class="modal-dialog modal-lg">
    <form id="edit_user_form" action="{{action('Superadmin\ManageUsersController@update', [$user->id])}}" method="PUT">
        {{ csrf_field() }}
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    @lang('messages.edit_user')
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="user_id" value="{{$user->id}}">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">
                                @lang('messages.name')
                                <span class="error">*</span>
                            </label>

                            <input type="text" class="form-control"
                            name="name" id="name" value="{{$user->name}}" required>
                        </div>    
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">
                                @lang('messages.email')
                                <span class="error">*</span>
                            </label>

                            <input type="email" class="form-control"
                            name="email" id="email" value="{{$user->email}}" required>
                        </div>    
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="password">
                                @lang('messages.password')
                            </label>
                            <input type="password" class="form-control"
                            name="password" id="password" aria-describedby="passwordHelp">
                            <small id="passwordHelp" class="form-text text-muted">
                                @lang('messages.dont_want_to_change_keep_it_blank')
                            </small>
                        </div>    
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" @if($user->is_active) checked @endif>
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
                            <input type="checkbox" class="form-check-input" name="can_create_form" id="can_create_form" value="1" @if($user->can_create_form) checked @endif>
                            <label class="form-check-label" for="can_create_form">
                                @lang('messages.can_create_form')
                                <i class="fas fa-info-circle text-info" data-toggle="tooltip" title="@lang('messages.can_create_form_tooltip')"></i>
                            </label>
                        </div>
                    </div>
                </div>
                @if(auth()->user()->id != $user->id)
                    @php
                        $form_ids = $assigned_forms->pluck('form_id')->toArray();
                    @endphp
                    @if($assigned_forms->count() > 0)
                        <h5>@lang('messages.assigned_forms'):</h5>
                        @foreach($assigned_forms as $key => $assigned_form)
                            <div class="card edit-assigned-form mb-4 p-3">
                                <label>
                                    <i class="fab fa-wpforms"></i>
                                    {{$assigned_form->form->name}}
                                </label>
                                <div class="row">  
                                    <input type="hidden" name="edit_assigned_form_id[]" value="{{$assigned_form->id}}">
                                    <div class="col-md-4">
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" name="edit_permissions[{{$assigned_form->id}}][]" id="form_design_{{$key}}" value="can_design_form"
                                            @if(in_array('can_design_form', $assigned_form->permissions))
                                                checked
                                            @endif>
                                            <label class="form-check-label" for="form_design_{{$key}}">
                                                @lang('messages.can_design_form')
                                                <i class="fas fa-info-circle text-info" data-toggle="tooltip" title="@lang('messages.can_design_form_tooltip')"></i>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" name="edit_permissions[{{$assigned_form->id}}][]" id="form_data_{{$key}}" value="can_view_data"
                                            @if(in_array('can_view_data', $assigned_form->permissions))
                                                checked
                                            @endif>
                                            <label class="form-check-label" for="form_data_{{$key}}">
                                                @lang('messages.can_view_data')
                                                <i class="fas fa-info-circle text-info" data-toggle="tooltip" title="@lang('messages.can_view_data_tooltip')"></i>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group form-check">
                                            <input type="checkbox" class="form-check-input" name="edit_permissions[{{$assigned_form->id}}][]" id="can_view_form_{{$key}}" value="can_view_form"
                                            @if(in_array('can_view_form', $assigned_form->permissions))
                                                checked
                                            @endif>
                                            <label class="form-check-label" for="can_view_form_{{$key}}">
                                                @lang('messages.can_view_form')
                                                <i class="fas fa-info-circle text-info" data-toggle="tooltip" title="@lang('messages.can_view_form_tooltip')"></i>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    <div class="card assign-form p-3">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="assign_form">
                                        @lang('messages.assign_forms'):
                                    </label>
                                    <select multiple class="form-control" id="assign_form" name="form_id[]">
                                        @foreach($forms as $key => $value)
                                            @if(!in_array($key, $form_ids))
                                                <option value="{{$key}}">
                                                    {{$value}}
                                                </option>
                                            @endif
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
                @endif
            <div class="modal-footer">
                <button type="submit" class="btn btn-sm btn-primary submit_btn">
                    @lang('messages.update')
                </button>
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
                    @lang('messages.close')
                </button>
            </div>
        </div>
    </form>
</div>