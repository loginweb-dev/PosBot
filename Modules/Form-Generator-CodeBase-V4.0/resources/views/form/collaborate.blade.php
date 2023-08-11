<div class="modal-dialog modal-lg" role="document">
    <form id="collaborate_form" action="{{action('FormController@postCollab')}}" method="POST">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    @lang('messages.collaborate') ({{$form->name}})
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if($form->assignedTo->count() > 0)
                    <h5>@lang('messages.assigned_users'):</h5>
                    @foreach($form->assignedTo as $key => $assigned_to)
                        <div class="card edit-assigned-form mb-2 p-3">
                            <label>
                                <i class="fas fa-user-tie"></i>
                                {{$assigned_to->user->name}}
                                <small>
                                    <code>({{$assigned_to->user->email}})</code>
                                </small>
                            </label>
                            <div class="row">  
                                <input type="hidden" name="edit_assigned_id[]" value="{{$assigned_to->id}}">
                                <div class="col-md-4">
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" name="edit_permissions[{{$assigned_to->id}}][]" id="form_design_{{$key}}" value="can_design_form"
                                        @if(in_array('can_design_form', $assigned_to->permissions))
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
                                        <input type="checkbox" class="form-check-input" name="edit_permissions[{{$assigned_to->id}}][]" id="form_data_{{$key}}" value="can_view_data"
                                        @if(in_array('can_view_data', $assigned_to->permissions))
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
                                        <input type="checkbox" class="form-check-input" name="edit_permissions[{{$assigned_to->id}}][]" id="can_view_form_{{$key}}" value="can_view_form"
                                        @if(in_array('can_view_form', $assigned_to->permissions))
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
                <input type="hidden" name="form_id" value="{{$form->id}}">
                <div class="card assign-form mt-4 p-3">
                    <h5 class="text-secondary">
                        @lang('messages.invite_to_collaborate'):
                    </h5>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="email">
                                    @lang('messages.email'):
                                </label>

                                <input type="email" class="form-control"
                                name="email" id="email">
                            </div>    
                        </div>
                    </div>
                    <h5>@lang('messages.permissions'):</h5>
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