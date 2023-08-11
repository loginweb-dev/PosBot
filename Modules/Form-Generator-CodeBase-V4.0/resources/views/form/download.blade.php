<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $form->name }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    @php
        $bg_color = $form->schema['settings']['color']['background'];
        $additional_js = $form->schema['additional_js_css']['js'];
        $additional_css = $form->schema['additional_js_css']['css'];
        $bg_type = $form->schema['settings']['background']['bg_type'];
        $bg_image = $form->schema['settings']['color']['image_path'];
        $form_attributes = isset($form->schema['form_attributes']) ? $form->schema['form_attributes'] : [];
    @endphp
    <!-- Styles -->
    @include('layouts.partials.css', ['is_download' => true, 'error_msg_color' => $form->schema['settings']['color']['error_msg'], 'additional_css' => $additional_css])
</head>
<body>
    <div id="app">
        <main class="py-4">
            <div class="container">
                <div class="card">
                    <div class="card-body" style="@if(!empty($bg_image) && $bg_type == 'bg_image')background-image: url(./asset/{{$bg_image}}); background-repeat: no-repeat;background-size: cover;background-position: right top;@else background-color: {{$bg_color}}; @endif">
                        <div class="row">
                            <div class="col-md-12">
                                <form id="{{$form->id}}" 
                                    action="{{'./' . $form_slug . '.php'}}" @includeIf('form.partials.custom_attribute_generator', ['attributes' => $form_attributes])>
                                    @include('form.partials.fields_generator')

                                    @if($form->schema['settings']['recaptcha']['is_enable'])
                                        <div class="g-recaptcha" 
                                            data-sitekey="{{$form->schema['settings']['recaptcha']['site_key']}}"></div>
                                        <br/>
                                    @endif

                                    @php
                                        $submit_btn_setting = $form->schema['settings']['submit'];
                                    @endphp
                                    <button type="submit" class="btn {{$submit_btn_setting['btn_alignment']}} {{$submit_btn_setting['btn_size']}} {{$submit_btn_setting['btn_color']}}"
                                        data-btn_loading="{{$submit_btn_setting['loading_text']}}"
                                        data-btn_text="{{$submit_btn_setting['text']}}">
                                        @if((!empty($submit_btn_setting['btn_icon']) && $submit_btn_setting['btn_icon'] != 'none') && $submit_btn_setting['icon_position'] == 'left')
                                            <i class="fas {{$submit_btn_setting['btn_icon']}}">
                                            </i>
                                        @endif
                                        {{$submit_btn_setting['text']}}
                                        @if((!empty($submit_btn_setting['btn_icon']) && $submit_btn_setting['btn_icon'] != 'none') && $submit_btn_setting['icon_position'] == 'right')
                                            <i class="fas {{$submit_btn_setting['btn_icon']}}">
                                            </i>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    @include('layouts.partials.javascript', ['is_download' => true])

    <script type="text/javascript">
        $(document).ready(function(){

            @if(!empty($form->schema['form']))
                @foreach($form->schema['form'] as $element)
                    @if($element['type'] == 'calendar')
                        @php
                            $enable_time_picker = ($element['enable_time_picker'] == 1) ? 'true' : 'false';

                            $time_picker_inline = ($element['time_picker_inline'] == 1) ? 'true' : 'false';
                        @endphp

                        initialize_datetimepicker('{{$element['name']}}', '{{$element['start_date']}}', '{{$element['end_date']}}', '{{$element['format']}}', '{{$element['time_format']}}', {!!json_encode($element['disabled_days'])!!}, {{$enable_time_picker}}, {{$time_picker_inline}});
                    @elseif($element['type'] == 'range')
                        initialize_rangeslider('{{$element['name']}}');
                    @elseif($element['type'] == 'file_upload')
                        initialize_dropzone('{{$element['name']}}', '{{$element['upload_text']}}', '{{$element['no_of_files']}}', '{{$element['file_size_limit']}}', '{{$element['allowed_file_type']}}', 'library/upload.php');
                    @elseif($element['type'] == 'text_editor')
                        initialize_text_editor('{{$element['name']}}', '{{$element['placeholder']}}', '{{$element['editor_height']}}');
                    @elseif($element['type'] == 'rating')
                        initialize_star_rating('{{$element['name']}}');
                    @elseif($element['type'] == 'signature')
                        initialize_signature_pad('{{$element['name']}}');
                    @endif
                @endforeach

                @php
                    $toastr_position = isset($form->schema['settings']['notification']['position']) ? $form->schema['settings']['notification']['position'] : '';
                @endphp
                initializeToastrSettingsForForm("{{$toastr_position}}");
            @endif

            $('form#{{$form->id}}').validate({
                ignore: ".note-editor *",
                submitHandler: function(form, e) {
                    e.preventDefault();

                    var form_data = $('form#{{$form->id}}').serialize();
                    
                    var form_submit_btn = $(form).find('button[type="submit"]');
                    form_submit_btn.attr('disabled', 'disabled');
                    form_submit_btn.text(form_submit_btn.data('btn_loading'));
                    var dropzoneCount = $('.dropzone').length;
                    var textEditor = $('.summer_note').length;
                    $.ajax({
                        method: 'POST',
                        url: $(form).attr('action'),
                        dataType: 'json',
                        data: form_data,
                        success: function(result) {
                            form_submit_btn.text(form_submit_btn.data('btn_text'));
                            form_submit_btn.removeAttr('disabled', 'disabled');

                            $(form)[0].reset();
                            
                            window.onbeforeunload = null;

                            if(result.success == 1){
                                if(result.redirect == 1){
                                    window.location = result.url;
                                } else {
                                    toastr.success(result.msg);
                                    if (textEditor > 0) {
                                        $('.summer_note').each(function(){
                                            $(this).summernote("code", "");
                                        });
                                    }
                                    if (dropzoneCount > 0) {
                                        setTimeout(() => {
                                            location.reload();
                                        }, 2000);
                                    }
                                }
                            } else {
                                toastr.error(result.msg);
                            }
                        },
                    });
                }
            });

            //show aleart before page reloading
            var form_obj = $('form#{{$form->id}}');
            var orig_forn_data = form_obj.serialize();
            window.onbeforeunload = function() {
                if($('form#{{$form->id}}').length == 1){
                    if (form_obj.serialize() != orig_forn_data) {
                        return 'Are you sure?';
                    }
                }
            }
        });
    </script>
    @if(!empty($additional_js))
        {!!$additional_js!!}
    @endif
</body>
</html>