@if(!empty($form->schema['form']))
    <div class="row">
        @foreach($form->schema['form'] as $key => $element)
            @php
                $custom_attributes = isset($element['custom_attributes']) ? $element['custom_attributes'] : [];
            @endphp
            <div class="@if(!empty($element['col'])) {{$element['col']}} @else col-md-12 @endif">
                @if($element['type'] == 'text')
                    <div class="form-group">
                        <label for="{{$element['name']}}">
                            <span style="color:{{$form->schema['settings']['color']['label']}}">
                                {{$element['label']}}
                            </span>

                            @if($element['required'])
                                <span style="color:{{$form->schema['settings']['color']['required_asterisk_color']}}">*</span>
                            @endif
                        </label>
                        <div class="input-group">

                            @if(!empty($element['prefix_icon']) && $element['prefix_icon'] !== 'none')
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas {{$element['prefix_icon']}}"></i>
                                    </span>
                                </div>
                            @endif

                            <input type="{{$element['subtype']}}"
                                name="fields[{{$element['name']}}]"
                                placeholder="{{$element['placeholder']}}"
                                class="form-control {{$element['size']}} {{$element['custom_class']}}"
                                id="{{$element['name']}}"
                                @foreach ($validation_rules[$key] as $validation)
                                    {{$validation['rule']}} = "{{$validation['value']}}"
                                    {{$validation['error']}} = "{{$validation['msg']}}"
                                @endforeach
                                @if($element['required']) required @endif
                                data-msg-required="{{$element['required_error_msg']}}"
                                @includeIf('form.partials.custom_attribute_generator', ['attributes' => $custom_attributes])
                                >

                            @if(!empty($element['suffix_icon']) && $element['suffix_icon'] !== 'none')
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas {{$element['suffix_icon']}}"></i>
                                    </span>
                                </div>
                            @endif
                        </div>

                        @if(!empty($element['help_text']))
                            <small class="form-text text-muted">
                                {{$element['help_text']}}
                            </small>
                        @endif
                    </div>
                @elseif($element['type'] == 'range')
                    <div class="form-group mb-4 mt-3">
                        <label for="{{$element['name']}}">
                            <span style="color:{{$form->schema['settings']['color']['label']}}">
                                {{$element['label']}}
                            </span>
                            @if($element['required'])
                                <span style="color:{{$form->schema['settings']['color']['required_asterisk_color']}}">*</span>
                            @endif
                        </label>
                        <div class="row">
                            <div class="col-sm-1">{{$element['min']}}</div>
                                <div class="col-sm-10">
                                    <input type="range" 
                                        name="fields[{{$element['name']}}]"
                                        @if($element['required']) required @endif
                                        data-msg-required="{{$element['required_error_msg']}}"
                                        id="{{$element['name']}}"
                                        min="{{$element['min']}}"
                                        max="{{$element['max']}}"
                                        step="{{$element['step']}}"
                                        data-orientation="{{$element['data_orientation']}}"
                                        @includeIf('form.partials.custom_attribute_generator', ['attributes' => $custom_attributes])>
                                </div>
                            <div class="col-sm-1">{{$element['max']}}</div>
                        </div>
                        <b><output class="{{$element['name']}}" style="display: block;text-align:center;" for="{{$element['name']}}"></output></b>
                        @if(!empty($element['help_text']))
                            <small class="form-text text-muted">
                                {{$element['help_text']}}
                            </small>
                        @endif
                    </div>

                @elseif($element['type'] == 'calendar')
                    <div class="form-group">
                        <label for="{{$element['name']}}">
                            <span style="color:{{$form->schema['settings']['color']['label']}}">
                                {{$element['label']}}
                            </span>
                            @if($element['required'])
                                <span style="color:{{$form->schema['settings']['color']['required_asterisk_color']}}">*</span>
                            @endif
                        </label>
                        <div class="input-group date" id="{{$element['name']}}" data-target-input="nearest">
                            @if(!empty($element['prefix_icon']) && $element['prefix_icon'] !== 'none')
                                <div class="input-group-prepend">
                                    <span class="input-group-text" data-target="#{{$element['name']}}" data-toggle="datetimepicker">
                                        <i class="fas {{$element['prefix_icon']}}"></i>
                                    </span>
                                </div>
                            @endif
                                <input type="text"
                                    name="fields[{{$element['name']}}]"
                                    data-toggle="datetimepicker"
                                    id="{{$element['name']}}"
                                    class="form-control datetimepicker-input {{$element['custom_class']}}"
                                    readonly 
                                    data-target="#{{$element['name']}}"
                                    @if($element['required']) required @endif
                                    data-msg-required="{{$element['required_error_msg']}}"
                                    @includeIf('form.partials.custom_attribute_generator', ['attributes' => $custom_attributes])
                                >
                            @if(!empty($element['suffix_icon']) && $element['suffix_icon'] !== 'none')
                                <div class="input-group-append">
                                    <span class="input-group-text" data-target="#{{$element['name']}}" data-toggle="datetimepicker">
                                        <i class="fas {{$element['suffix_icon']}}"></i>
                                    </span>
                                </div>
                            @endif
                        </div>
                        @if(!empty($element['help_text']))
                            <small class="form-text text-muted">
                                {{$element['help_text']}}
                            </small>
                        @endif
                    </div>
                @elseif($element['type'] == 'textarea')

                    <div class="form-group">
                        <label for="{{$element['name']}}">
                            <span style="color:{{$form->schema['settings']['color']['label']}}">
                                {{$element['label']}}
                            </span>
                            @if($element['required'])
                                <span style="color:{{$form->schema['settings']['color']['required_asterisk_color']}}">*</span>
                            @endif
                        </label>
                        <div class="input-group">
                            @if(!empty($element['prefix_icon']) && $element['prefix_icon'] !== 'none')
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas {{$element['prefix_icon']}}"></i>
                                    </span>
                                </div>
                            @endif
                            <textarea
                                rows="{{$element['rows']}}"
                                name="fields[{{$element['name']}}]"
                                cols="{{$element['columns']}}"
                                placeholder="{{$element['placeholder']}}"
                                class="form-control {{$element['custom_class']}}"
                                id="{{$element['name']}}"
                                @foreach ($validation_rules[$key] as $validation)
                                    {{$validation['rule']}} = "{{$validation['value']}}"
                                    {{$validation['error']}} = "{{$validation['msg']}}"
                                @endforeach
                                @if($element['required']) required @endif
                                data-msg-required="{{$element['required_error_msg']}}"
                                @includeIf('form.partials.custom_attribute_generator', ['attributes' => $custom_attributes])
                                ></textarea>
                            @if(!empty($element['suffix_icon']) && $element['suffix_icon'] !== 'none')
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas {{$element['suffix_icon']}}"></i>
                                    </span>
                                </div>
                            @endif
                        </div>
                        @if(!empty($element['help_text']))
                            <small class="form-text text-muted">
                                {{$element['help_text']}}
                            </small>
                        @endif
                    </div>

                @elseif(in_array($element['type'], ['radio', 'checkbox']))
                    <div class="form-group">
                        <label for="{{$element['name']}}">
                            <span style="color:{{$form->schema['settings']['color']['label']}}">{{$element['label']}}</span>
                            @if($element['required'])
                                <span style="color:{{$form->schema['settings']['color']['required_asterisk_color']}}">*</span>
                            @endif
                        </label>
                        
                        @foreach(explode(PHP_EOL, $element['options']) as $option)
                            <div class="custom-control @if($element['type'] == 'radio') custom-radio @else custom-checkbox @endif">
                                <input class="custom-control-input" 
                                    type="{{$element['type']}}" 
                                    value="{{$option}}"
                                    name="fields[{{$element['name']}}][]"
                                    id="{{$element['name']}}_{{$loop->index}}"
                                    @foreach ($validation_rules[$key] as $validation)
                                        {{$validation['rule']}} = "{{$validation['value']}}"
                                        {{$validation['error']}} = "{{$validation['msg']}}"
                                    @endforeach
                                    @if($element['required'])
                                        required
                                    @endif
                                    data-msg-required="{{$element['required_error_msg']}}"
                                    @includeIf('form.partials.custom_attribute_generator', ['attributes' => $custom_attributes])
                                    >
                                <label class="custom-control-label" for="{{$element['name']}}_{{$loop->index}}">
                                    {{$option}}
                                </label>
                            </div>
                        @endforeach

                        @if(!empty($element['help_text']))
                            <small class="form-text text-muted">
                                {{$element['help_text']}}
                            </small>
                        @endif
                    </div>

                @elseif($element['type'] == 'dropdown')

                    <div class="form-group">
                        <label for="{{$element['name']}}">
                            <span style="color:{{$form->schema['settings']['color']['label']}}">{{$element['label']}}</span>
                            @if($element['required'])
                                <span style="color:{{$form->schema['settings']['color']['required_asterisk_color']}}">*</span>
                            @endif
                        </label>

                        <div class="input-group">
                            @if(!empty($element['prefix_icon']) && $element['prefix_icon'] !== 'none')
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <i class="fas {{$element['prefix_icon']}}"></i>
                                    </span>
                                </div>
                            @endif
                            <select class="custom-select {{$element['size']}} {{$element['custom_class']}}" @if($element['required']) required @endif data-msg-required="{{$element['required_error_msg']}}" id="{{$element['name']}}"
                             @if($element['multiselect'])
                                multiple
                                name="fields[{{$element['name']}}][]"
                             @else
                                name="fields[{{$element['name']}}]"
                             @endif
                            @foreach ($validation_rules[$key] as $validation)
                                {{$validation['rule']}} = "{{$validation['value']}}"
                                {{$validation['error']}} = "{{$validation['msg']}}"
                            @endforeach
                            @includeIf('form.partials.custom_attribute_generator', ['attributes' => $custom_attributes])>
                                @foreach(explode(PHP_EOL, $element['options']) as $option)
                                    <option>
                                        {{$option}}
                                    </option>
                                @endforeach
                            </select>
                            @if(!empty($element['suffix_icon']) && $element['suffix_icon'] !== 'none')
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="fas {{$element['suffix_icon']}}"></i>
                                    </span>
                                </div>
                            @endif
                        </div>
                        @if(!empty($element['help_text']))
                            <small class="form-text text-muted">
                                {{$element['help_text']}}
                            </small>
                        @endif
                    </div>
                @elseif($element['type'] == 'heading')

                    <div class="{{$element['custom_class'] ?? ''}}">
                        @php
                            echo '<' . $element['tag'] . ' style="color:' . $element['text_color'] . '">' . $element['content'] .'</' . $element['tag'] . '>';
                        @endphp
                    </div>
                @elseif($element['type'] == 'file_upload')
                    <div class="mb-2">
                        <label for="{{$element['name']}}">
                            <span style="color:{{$form->schema['settings']['color']['label']}}">{{$element['label']}}</span>
                            @if($element['required'])
                                <span style="color:{{$form->schema['settings']['color']['required_asterisk_color']}}">*</span>
                            @endif
                        </label>
                        <div class="dropzone {{$element['custom_class']}}"
                            id="{{$element['name']}}" @includeIf('form.partials.custom_attribute_generator', ['attributes' => $custom_attributes])></div>
                        <input type="hidden" name="fields[{{$element['name']}}][]" id="{{$element['name']}}" value="" @if($element['required']) required @endif data-msg-required="{{$element['required_error_msg']}}">
                        @if(!empty($element['help_text']))
                            <small class="form-text text-muted">
                                {{$element['help_text']}}
                            </small>
                        @endif
                    </div>
                @elseif($element['type'] == 'text_editor')
                    <div class="mb-2">
                        <label for="{{$element['name']}}">
                            <span style="color:{{$form->schema['settings']['color']['label']}}">{{$element['label']}}</span>
                            @if($element['required'])
                                <span style="color:{{$form->schema['settings']['color']['required_asterisk_color']}}">*</span>
                            @endif
                        </label>
                        <textarea class="form-control summer_note" id="{{$element['name']}}" name="fields[{{$element['name']}}]" @if($element['required']) required @endif data-msg-required="{{$element['required_error_msg']}}" 
                        @includeIf('form.partials.custom_attribute_generator', ['attributes' => $custom_attributes])></textarea>
                        @if(!empty($element['help_text']))
                            <small class="form-text text-muted">
                                {{$element['help_text']}}
                            </small>
                        @endif
                    </div>
                @elseif($element['type'] == 'terms_and_condition')
                    <div class="pt-2 mb-2">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input {{$element['custom_class']}}" type="checkbox" name="fields[{{$element['name']}}]" id="terms_and_condition" @if($element['required']) required @endif data-msg-required="{{$element['required_error_msg']}}" @includeIf('form.partials.custom_attribute_generator', ['attributes' => $custom_attributes])>
                            <label class="custom-control-label" for="terms_and_condition">
                                @if(!empty($element['link']))
                                    <a href="{{$element['link']}}" target="_blank">         {{$element['label']}}
                                    </a>
                                @else
                                    {{$element['label']}}
                                @endif
                                @if($element['required'])
                                    <span style="color:{{$form->schema['settings']['color']['required_asterisk_color']}}">*</span>
                                @endif
                            </label>
                        </div>
                    </div>
                @elseif($element['type'] == 'hr')
                    <hr style="
                        @if(!empty($element['padding_top']))
                            padding-top: {{$element['padding_top']}}px;
                        @endif
                        @if(!empty($element['padding_bottom']))
                            padding-bottom:{{$element['padding_bottom']}}px;
                        @endif
                        @if(!empty($element['border_size']) && !empty($element['border_color']))
                            border-top:{{$element['border_size']}}px {{$element['border_type']}} {{$element['border_color']}};
                        @endif">
                @elseif($element['type'] == 'html_text')
                    <div  class="{{$element['custom_class'] ?? ''}}">
                        {!!$element['html_text']!!}
                    </div>
                @elseif($element['type'] == 'rating')
                    <div class="form-group pt-2">
                        <label for="{{$element['name']}}">
                            <span style="color:{{$form->schema['settings']['color']['label']}}">{{$element['label']}}</span>
                            @if($element['required'])
                                <span style="color:{{$form->schema['settings']['color']['required_asterisk_color']}}">*</span>
                            @endif
                        </label>
                        <input id="{{$element['name']}}" name="fields[{{$element['name']}}]"
                            data-stars="{{$element['stars_to_display']}}"
                            data-min="{{$element['min_rating']}}"
                            data-max="{{$element['max_rating']}}"
                            data-step="{{$element['increment']}}"
                            dir="{{$element['direction']}}"
                            data-size="{{$element['size']}}"
                            class="star_rating"
                            @if($element['required']) required @endif
                            data-msg-required="{{$element['required_error_msg']}}"
                        >
                    </div>
                @elseif($element['type'] == 'switch')
                    <div class="form-group pt-2">
                        <span class="switch @if(!empty($element['size'])) {{$element['size']}} @endif">
                            <input type="checkbox" class="switch" name="fields[{{$element['name']}}]" id="{{$element['name']}}"" @if($element['required']) required @endif @includeIf('form.partials.custom_attribute_generator', ['attributes' => $custom_attributes]) data-msg-required="{{$element['required_error_msg']}}">
                            <label for="{{$element['name']}}">
                                <span style="color:{{$form->schema['settings']['color']['label']}}" class="ml-2">
                                    {{$element['label']}}
                                </span>
                                @if($element['required'])
                                    <span style="color:{{$form->schema['settings']['color']['required_asterisk_color']}}">*</span>
                                @endif
                            </label>
                        </span>
                        @if(!empty($element['help_text']))
                            <small class="form-text text-muted">
                                {{$element['help_text']}}
                            </small>
                        @endif
                    </div>
                @elseif($element['type'] == 'signature')
                    <label for="{{$element['name']}}">
                        <span style="color:{{$form->schema['settings']['color']['label']}}">{{$element['label']}}</span>
                        @if($element['required'])
                            <span style="color:{{$form->schema['settings']['color']['required_asterisk_color']}}">*</span>
                        @endif
                    </label>
                    <div class="form-group">
                        <canvas id="{{$element['name']}}" width="300" height="200" class="signature-pad" @includeIf('form.partials.custom_attribute_generator', ['attributes' => $custom_attributes])></canvas>
                        <input type="hidden" class="signature" name="fields[{{$element['name']}}]" id="output_{{$element['name']}}" value="" @if($element['required']) required @endif data-msg-required="{{$element['required_error_msg']}}">
                    </div>
                    <div class="form-text">
                        <span class="pointer mr-4" id="clear_{{$element['name']}}" title="@lang('messages.clear_signature_pad')" data-name="{{$element['name']}}">
                            <i class="far fa-times-circle"></i>
                            {{__('messages.clear')}}
                        </span>
                        <span class="pointer" id="undo_{{$element['name']}}" title="@lang('messages.undo')" data-name="{{$element['name']}}">
                            <i class="fas fa-undo"></i>
                            {{__('messages.undo')}}
                        </span>
                        @if(!empty($element['help_text']))
                            <small class="form-text text-muted">
                                {{$element['help_text']}}
                            </small>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@endif