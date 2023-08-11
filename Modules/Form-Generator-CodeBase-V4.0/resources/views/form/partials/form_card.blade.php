<div class="card">
    @php
        $bg_color = $form->schema['settings']['color']['background'];
        $error_msg_color = $form->schema['settings']['color']['error_msg'];
        $bg_type = $form->schema['settings']['background']['bg_type'];
        $bg_image = $form->schema['settings']['color']['image_path'];
    @endphp
    <div class="tab-content card-body" 
        id="" role="tabpanel" style='@if(!empty($bg_image) && $bg_type == 'bg_image')background-image: url("{{Storage::url(config('constants.doc_path').'/'.$bg_image)}}"); background-repeat: no-repeat;background-size: cover;background-position: right top;@else background-color: {{$bg_color}}; @endif'>

        @if(!empty($is_form_closed) && $is_form_closed)
            <div class="card-text">
                {!! $form_closed_msg !!}
            </div>
        @else
            <show-form form="{{json_encode($form)}}" action-by="{{$action_by ?? ''}}"></show-form>
        @endif
    </div>
</div>