<div class="modal-dialog modal-xl" role="document">
    <div class="modal-content" id="print_form_data">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
                {{ucFirst($form_data->form->name)}} 
                @if(!empty($form_data->submittedBy))
                    <small>
                        (
                            <b>@lang('messages.submitted_by'): </b> 
                            {{$form_data->submittedBy->name}}
                        )
                    </small>
                @endif
            </h5>
            <button type="button" class="ml-auto btn btn-info no-print btn-sm formDataPrintBtn">
                <i class="fas fa-print"></i>
                @lang('messages.print')
            </button>
            <button type="button" class="close ml-0 no-print" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row mb-2">
                <div class="col-md-6">
                    @if(isset($form_data->form->schema['settings']['form_submision_ref']['is_enabled']) && $form_data->form->schema['settings']['form_submision_ref']['is_enabled'] && !empty($form_data->submission_ref))
                      <b>@lang('messages.submission_numbering'):</b> 
                      {{$form_data->submission_ref}}
                    @endif
                </div>
            </div>
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>@lang('messages.field')</th>
                        <th>@lang('messages.value')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($form_data->form->schema['form'] as $element)
                        @isset($form_data->data[$element['name']])
                        <tr>
                            <td>
                                <strong>{{$element['label']}}</strong>
                            </td>
                            <td>
                                @if($element['type'] == 'file_upload')

                                    @include('form_data.file_view', ['form_upload' => $form_data->data[$element['name']]])
                                @elseif($element['type'] == 'signature')
                                    <img src="{{$form_data->data[$element['name']]}}" class="signature">
                                @elseif(is_array($form_data->data[$element['name']]) && $element['type'] != 'file_upload')

                                    {{implode(', ', $form_data->data[$element['name']])}}
                                    
                                @else
                                    {!! nl2br($form_data->data[$element['name']]) !!}
                                @endif
                            </td>
                        </tr>
                        @endisset
                    @endforeach
                </tbody>
            </table>
            <div class="no-print mt-4">
                <hr>
                <form id="add_comment_form" action="{{action('FormDataCommentController@store')}}" method="POST">
                    {{ csrf_field() }}
                    <!-- hiden fields -->
                    <input type="hidden" name="form_data_id" id="form_data_id" value="{{$form_data->id}}">
                    <div class="form-group">
                        <label for="comment">
                            @lang('messages.comment'):
                        </label>
                        <textarea class="form-control" name="comment" id="comment" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success add_comment_btn">
                        @lang('messages.add_comment')
                    </button>
                </form>
                <div class="row mt-5">
                    <div class="col-md-12">
                        <h5>
                            <i class="fas fa-comments"></i>
                            @lang('messages.comments')
                        </h5>
                        <div class="direct-chat-messages" style="max-height: 500px;height: auto;">
                            @includeIf('form_data.partials.comment', ['comments' => $form_data->comments])
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer no-print">
            <button type="button" class="float-right m-1 btn btn-secondary btn-sm" data-dismiss="modal">
                @lang('messages.close')
            </button>
            <button type="button" class="float-right m-1 btn btn-info btn-sm formDataPrintBtn">
                <i class="fas fa-print"></i>
                @lang('messages.print')
            </button>
            <a class="btn float-right btn-primary btn-sm m-1" target="_blank" href="{{action('FormDataController@downloadPdf', [$form_data->id])}}">
                <i class="far fa-file-pdf" aria-hidden="true"></i>
                @lang('messages.download_pdf')
            </a>
        </div>
    </div>
</div>