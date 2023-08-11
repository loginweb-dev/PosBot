<template>

    <div class="form-group" v-if="element.type == 'text'" :class="[element.extras.showConfigurator ? 'active_element' : '', form_group_class, element.conditional_class]" @mouseover="onMouseHover()" @mouseleave="onMouseLeave(element)" :title="applyValidations ? '' : trans('messages.click_to_edit')">
        
        <label :for="element.name">
            <i class="fas fa-sort handle pointer font_icon_size float-left mr-3" :class="[display_handler]" :title="trans('messages.drag_element_using_icon')"></i>
            <span :style="{'color': settings.color.label}">
                {{element.label}}
            </span>
            <span :style="{'color': settings.color.required_asterisk_color}" v-if="element.required">*</span>
        </label>
        <div class="input-group">
            <div class="input-group-prepend" v-if="element.prefix_icon && element.prefix_icon !== 'none'">
                <span class="input-group-text">
                    <i :class="'fas ' + element.prefix_icon"></i>
                </span>
            </div>
                <input :type="element.subtype" class="form-control" 
                    :name="element.name" 
                    :placeholder="element.placeholder" 
                    :class="[element.size, element.custom_class, element.conditional_class]" 
                    :required="element.required && applyValidations" 
                    v-bind="getDynamicallyGeneratedAttributeObj(element.validations, element.custom_attributes)" 
                    :id="element.name"
                    :value="_.get(submitted_data, element.name, '')"
                    :data-msg-required="element.required_error_msg"
                    @change="$emit('apply_conditions')">
            <div class="input-group-append" v-if="element.suffix_icon && element.suffix_icon !== 'none'">
                <span class="input-group-text">
                    <i :class="'fas ' + element.suffix_icon"></i>
                </span>
            </div>
        </div>
        <small class="form-text text-muted" v-if="element.help_text">
            {{element.help_text}}
        </small>
    </div>

    <div v-else-if="element.type == 'range'" :class="[element.extras.showConfigurator ? 'active_element' : '', form_group_class, element.conditional_class, 'mb-4 mt-3']" @mouseover="onMouseHover()" @mouseleave="onMouseLeave(element)" :title="applyValidations ? '' : trans('messages.click_to_edit')">
        <label :for="element.name">
            <i class="fas fa-sort handle pointer font_icon_size float-left mr-3" :class="[display_handler]" :title="trans('messages.drag_element_using_icon')"></i>
            <span :style="{'color': settings.color.label}">
                {{element.label}}
            </span>
            <span :style="{'color': settings.color.required_asterisk_color}" v-if="element.required">*</span>
        </label>
        <div class="row">
            <div class="col-sm-1">{{element.min}}</div>
                <div class="col-sm-10">
                    <input type="range" :name="element.name" :required="element.required && applyValidations" :id="element.name" :min="element.min" :max="element.max" :step="element.step" :data-orientation="element.data_orientation"
                    :value="_.get(submitted_data, element.name, '')"
                    :class="[element.conditional_class]"
                    v-bind="getCustomAttributes(element.custom_attributes)"
                    :data-msg-required="element.required_error_msg"
                    >
                </div>
            <div class="col-sm-1">{{element.max}}</div>
        </div>
        <b>
            <output :class="element.name" style="display: block;text-align:center;" :for="element.name">
                {{_.get(submitted_data, element.name, '')}}
            </output>
        </b>
        <small class="form-text text-muted" v-if="element.help_text">
            {{element.help_text}}
        </small>
    </div>

    <div class="form-group" v-else-if="element.type == 'calendar'" :class="[element.extras.showConfigurator ? 'active_element' : '', form_group_class, element.conditional_class]" @mouseover="onMouseHover()" @mouseleave="onMouseLeave(element)" :title="applyValidations ? '' : trans('messages.click_to_edit')">
        <label :for="element.name">
            <i class="fas fa-sort handle pointer font_icon_size float-left mr-3" :class="[display_handler]" :title="trans('messages.drag_element_using_icon')"></i>
            <span :style="{'color': settings.color.label}">
                {{element.label}}
            </span>
            <span :style="{'color': settings.color.required_asterisk_color}" v-if="element.required">*</span>
        </label>
        <div class="input-group date" :id="element.name" data-target-input="nearest">
            <div class="input-group-prepend"
                :data-target="'#' + element.name"
                data-toggle="datetimepicker"
                v-if="element.prefix_icon && element.prefix_icon !== 'none'">
                <span class="input-group-text">
                    <i :class="'fas ' + element.prefix_icon"></i>
                </span>
            </div>
                <input type="text" 
                    class="form-control datetimepicker-input" 
                    :data-target="'#' + element.name"
                    data-toggle="datetimepicker"
                    :name="element.name"
                    :id="element.name"
                    readonly
                    :class="[element.size, element.custom_class, element.conditional_class]"
                    :required="element.required && applyValidations"
                    :data-defaultDate="_.get(submitted_data, element.name, '')"
                    v-bind="getCustomAttributes(element.custom_attributes)"
                    :data-msg-required="element.required_error_msg"
                    />
                    
            <div class="input-group-append" 
                :data-target="'#' + element.name" 
                data-toggle="datetimepicker" 
                v-if="element.suffix_icon && element.suffix_icon !== 'none'">
            
                <div class="input-group-text">
                    <i :class="'fas ' + element.suffix_icon"></i>
                </div>
            </div>
        </div>
        <small class="form-text text-muted" v-if="element.help_text">
            {{element.help_text}}
        </small>
    </div>

    <div class="form-group" v-else-if="element.type == 'textarea'" :class="[element.extras.showConfigurator ? 'active_element' : '', form_group_class, element.conditional_class]" @mouseover="onMouseHover()" @mouseleave="onMouseLeave(element)" :title="applyValidations ? '' : trans('messages.click_to_edit')">
        <label :for="element.name">
            <i class="fas fa-sort handle pointer font_icon_size float-left mr-3" :class="[display_handler]" :title="trans('messages.drag_element_using_icon')"></i>
            <span :style="{'color': settings.color.label}">
                {{element.label}}
            </span>
            <span :style="{'color': settings.color.required_asterisk_color}" v-if="element.required">*</span>
        </label>
        <div class="input-group">
            <div class="input-group-prepend" v-if="element.prefix_icon && element.prefix_icon !== 'none'">
                <span class="input-group-text">
                    <i :class="'fas ' + element.prefix_icon"></i>
                </span>
            </div>
            <textarea class="form-control"
                :value="_.get(submitted_data, element.name, '')"
                :rows="element.rows"
                :name="element.name"
                :id="element.name"
                :cols="element.columns"
                :placeholder="element.placeholder"
                :class="[element.custom_class, element.conditional_class]"
                :required="element.required && applyValidations"
                v-bind="getDynamicallyGeneratedAttributeObj(element.validations, element.custom_attributes)"
                @change="$emit('apply_conditions')"
                :data-msg-required="element.required_error_msg"
                ></textarea>
            <div class="input-group-append" v-if="element.suffix_icon && element.suffix_icon !== 'none'">
                <span class="input-group-text">
                    <i :class="'fas ' + element.suffix_icon"></i>
                </span>
            </div>
        </div>
        <small class="form-text text-muted" v-if="element.help_text">
            {{element.help_text}}
        </small>
    </div>

    <div class="form-group" 
        v-else-if="element.type == 'radio' || element.type == 'checkbox'" :class="[element.extras.showConfigurator ? 'active_element' : '', form_group_class, element.conditional_class]" @mouseover="onMouseHover()" @mouseleave="onMouseLeave(element)" :title="applyValidations ? '' : trans('messages.click_to_edit')">
        <label :for="element.name">
            <i class="fas fa-sort handle pointer font_icon_size float-left mr-3" :class="[display_handler]" :title="trans('messages.drag_element_using_icon')"></i>
            <span :style="{'color': settings.color.label}">{{element.label}}</span>
            <span :style="{'color': settings.color.required_asterisk_color}" v-if="element.required">*</span>
        </label>
        
        <div class="custom-control" :class="[element.type == 'radio' ? 'custom-radio' : 'custom-checkbox']" v-for="(option, index) in element.options.split('\n')">
            <input class="custom-control-input" 
                :type="element.type" 
                :value="option"
                v-bind="getDynamicallyGeneratedAttributeObj(element.validations, element.custom_attributes)"
                :required="element.required && applyValidations"
                :name="(element.type == 'checkbox' ? element.name + '[]' : element.name)"
                :id="element.name +'_'+ index"
                @change="$emit('apply_conditions')"
                :checked="_.includes(_.get(submitted_data, element.name, ''), option)"
                :class="[element.conditional_class]"
                :data-msg-required="element.required_error_msg"
                >
            <label class="custom-control-label" :for="element.name +'_'+ index">
                {{option}}
            </label>
        </div>

        <small class="form-text text-muted" v-if="element.help_text">
            {{element.help_text}}
        </small>
    </div>

    <div class="form-group" v-else-if="element.type == 'dropdown'" :class="[element.extras.showConfigurator ? 'active_element' : '', form_group_class, element.conditional_class]" @mouseover="onMouseHover()" @mouseleave="onMouseLeave(element)" :title="applyValidations ? '' : trans('messages.click_to_edit')">
        <label :for="element.name">
            <i class="fas fa-sort handle pointer font_icon_size float-left mr-3" :class="[display_handler]" :title="trans('messages.drag_element_using_icon')"></i>
            <span :style="{'color': settings.color.label}">{{element.label}}</span>
            <span :style="{'color': settings.color.required_asterisk_color}" v-if="element.required">*</span>
        </label>

        <div class="input-group">
            <div class="input-group-prepend" v-if="element.prefix_icon && element.prefix_icon !== 'none'">
                <span class="input-group-text">
                    <i :class="'fas ' + element.prefix_icon"></i>
                </span>
            </div>
            <select class="custom-select" :class="[element.size, element.custom_class, element.conditional_class]" :required="element.required && applyValidations" v-bind="getDynamicallyGeneratedAttributeObj(element.validations, element.custom_attributes)"
            :id="element.name" :multiple="element.multiselect"
            :name="(element.multiselect == true ? element.name + '[]' : element.name)"
            @change="$emit('apply_conditions')"
            :data-msg-required="element.required_error_msg"
            >
                <option v-for="option in element.options.split('\n')"
                :selected="_.includes(_.get(submitted_data, element.name, ''), option)" 
                >
                    {{option}}
                </option>
            </select>
            <div class="input-group-append" v-if="element.suffix_icon && element.suffix_icon !== 'none'">
                <span class="input-group-text">
                    <i :class="'fas ' + element.suffix_icon"></i>
                </span>
            </div>
        </div>
        <small class="form-text text-muted" v-if="element.help_text">
            {{element.help_text}}
        </small>
    </div>

    <div v-else-if="element.type == 'heading'" :class="[element.extras.showConfigurator ? 'active_element' : '', form_group_class]" @mouseover="onMouseHover()" @mouseleave="onMouseLeave(element)" :title="applyValidations ? '' : trans('messages.click_to_edit')">
        <i class="fas fa-sort handle pointer font_icon_size float-left mr-3" :class="[display_handler]" :title="trans('messages.drag_element_using_icon')"></i>
        <div v-html="'<' + element.tag + ' style=color:' + element.text_color + '>' + element.content + '</' + element.tag + '>'" :class="[element.custom_class]">
        </div>
    </div>

    <div v-else-if="element.type == 'file_upload'" :class="[element.extras.showConfigurator ? 'active_element' : '', form_group_class, element.conditional_class]" @mouseover="onMouseHover()" @mouseleave="onMouseLeave(element)" :title="applyValidations ? '' : trans('messages.click_to_edit')" class="mb-3">
        <label :for="element.name">
            <i class="fas fa-sort handle pointer font_icon_size float-left mr-3" :class="[display_handler]" :title="trans('messages.drag_element_using_icon')"></i>
            <span :style="{'color': settings.color.label}">{{element.label}}</span>
            <span :style="{'color': settings.color.required_asterisk_color}" v-if="element.required">*</span>
        </label>
        <div class="dropzone" :class="[element.custom_class]" :id="element.name" v-bind="getCustomAttributes(element.custom_attributes)">
        </div>
        <input type="hidden" :name="element.name + '[]'" :id="element.name" :value="_.get(submitted_data, element.name, '')" :required="element.required && applyValidations" :class="element.conditional_class" :data-msg-required="element.required_error_msg">
        <small class="form-text text-muted" v-if="element.help_text">
            {{element.help_text}}
        </small>
    </div>

    <div v-else-if="element.type == 'text_editor'" :class="[element.extras.showConfigurator ? 'active_element' : '', form_group_class, element.conditional_class]" @mouseover="onMouseHover()" @mouseleave="onMouseLeave(element)" :title="applyValidations ? '' : trans('messages.click_to_edit')">
        <label :for="element.name">
            <i class="fas fa-sort handle pointer font_icon_size float-left mr-3" :class="[display_handler]" :title="trans('messages.drag_element_using_icon')"></i>
            <span :style="{'color': settings.color.label}">{{element.label}}</span>
            <span :style="{'color': settings.color.required_asterisk_color}" v-if="element.required">*</span>
        </label>
        <textarea :id="element.name" :name="element.name"
            :required="element.required && applyValidations" class="form-control summer_note"
            :class="[element.conditional_class]"
            :value="_.get(submitted_data, element.name, '')"
            v-bind="getCustomAttributes(element.custom_attributes)"
            :data-msg-required="element.required_error_msg"
            >
        </textarea>
        <small class="form-text text-muted" v-if="element.help_text">
            {{element.help_text}}
        </small>
    </div>

    <div v-else-if="element.type == 'terms_and_condition'" class="pt-3 mb-3" :class="[element.extras.showConfigurator ? 'active_element' : '', form_group_class, element.conditional_class]" @mouseover="onMouseHover()" @mouseleave="onMouseLeave(element)" :title="applyValidations ? '' : trans('messages.click_to_edit')">
        <i class="fas fa-sort handle pointer font_icon_size" :class="[display_handler]" :title="trans('messages.drag_element_using_icon')"></i>
        <div class="custom-control custom-checkbox">
          <input class="custom-control-input" type="checkbox" :name="element.name" id="terms_and_condition" :required="element.required && applyValidations" :class="[element.custom_class, element.conditional_class]" @change="$emit('apply_conditions')"
          :checked="_.includes(['on'], _.get(submitted_data, element.name, ''))"
          v-bind="getCustomAttributes(element.custom_attributes)"
          :data-msg-required="element.required_error_msg"
          >
          <label class="custom-control-label" for="terms_and_condition">
                <a :href="element.link" target="_blank" v-if="element.link">
                    {{element.label}}
                </a>
                <span v-else>{{element.label}}</span>
                <span :style="{'color': settings.color.required_asterisk_color}" v-if="element.required">*</span>
          </label>
        </div>
    </div>

    <div v-else-if="element.type == 'hr'" :class="[element.extras.showConfigurator ? 'active_element' : '', form_group_class]" @mouseover="onMouseHover()" @mouseleave="onMouseLeave(element)" :title="applyValidations ? '' : trans('messages.click_to_edit')">
        <i class="fas fa-sort handle pointer font_icon_size" :class="[display_handler]" :title="trans('messages.drag_element_using_icon')"></i>
        <hr :style="{'padding-top' : element.padding_top + 'px', 'padding-bottom' : element.padding_bottom + 'px', 'border-top' : element.border_size + 'px ' + element.border_type +  element.border_color}">
    </div>

    <div v-else-if="element.type == 'html_text'" :class="[element.extras.showConfigurator ? 'active_element' : '', form_group_class]" @mouseover="onMouseHover()" @mouseleave="onMouseLeave(element)" :title="applyValidations ? '' : trans('messages.click_to_edit')">
        <i class="fas fa-sort handle pointer font_icon_size float-left mr-3" :class="[display_handler]" :title="trans('messages.drag_element_using_icon')"></i>
        <span v-html="element.html_text" :class="element.custom_class"></span>
    </div>

    <div class="form-group" v-else-if="element.type == 'rating'" :class="[element.extras.showConfigurator ? 'active_element' : '', form_group_class, element.conditional_class]" @mouseover="onMouseHover()" @mouseleave="onMouseLeave(element)" :title="applyValidations ? '' : trans('messages.click_to_edit')">
        <label :for="element.name">
            <i class="fas fa-sort handle pointer font_icon_size float-left mr-3" :class="[display_handler]" :title="trans('messages.drag_element_using_icon')"></i>
            <span :style="{'color': settings.color.label}">{{element.label}}</span>
            <span :style="{'color': settings.color.required_asterisk_color}" v-if="element.required">*</span>
        </label>
        <input :id="element.name" :name="element.name"
            class="star_rating" :data-stars="element.stars_to_display"
            :data-min="element.min_rating" :data-max="element.max_rating"
            :data-step="element.increment" :dir="element.direction"
            :data-size="element.size"
            type="hidden" 
            :required="element.required && applyValidations"
            :data-msg-required="element.required_error_msg"
            :class="element.conditional_class"
            :value="_.get(submitted_data, element.name, '')"
            >
    </div>
    <div v-else-if="element.type == 'switch'" :class="[element.extras.showConfigurator ? 'active_element' : '', form_group_class, element.conditional_class]" @mouseover="onMouseHover()" @mouseleave="onMouseLeave(element)" :title="applyValidations ? '' : trans('messages.click_to_edit')">
        <i class="fas fa-sort handle pointer font_icon_size float-left mr-3" :class="[display_handler]" :title="trans('messages.drag_element_using_icon')"></i>
        <div class="form-group">
            <span class="switch" :class="element.size">
                <input type="checkbox" class="switch" :name="element.name" :id="element.name" :required="element.required && applyValidations" @change="$emit('apply_conditions')" :class="element.conditional_class"
                :checked="_.includes(['on'], _.get(submitted_data, element.name, ''))"
                v-bind="getCustomAttributes(element.custom_attributes)"
                :data-msg-required="element.required_error_msg"
                >
                <label :for="element.name">
                    <span :style="{'color': settings.color.label}" class="ml-2">
                        {{element.label}}
                    </span>
                    <span :style="{'color': settings.color.required_asterisk_color}" v-if="element.required">*</span>
                </label>
            </span>
            <small class="form-text text-muted" v-if="element.help_text">
                {{element.help_text}}
            </small>
        </div>
    </div>
    <div v-else-if="element.type == 'signature'" :class="[element.extras.showConfigurator ? 'active_element' : '', form_group_class, element.conditional_class]" :title="applyValidations ? '' : trans('messages.click_to_edit')" @mouseover="onMouseHover" @mouseleave="onMouseLeave(element)">
        <i class="fas fa-sort handle pointer font_icon_size float-left mr-3" :class="[display_handler]" :title="trans('messages.drag_element_using_icon')"></i>
        <label :for="element.name">
            <span :style="{'color': settings.color.label}" class="ml-2">
                {{element.label}}
            </span>
            <span :style="{'color': settings.color.required_asterisk_color}" v-if="element.required">*</span>
        </label>
        <div class="form-group">
            <canvas :id="element.name" width="300" height="200" class="signature-pad" v-bind="getCustomAttributes(element.custom_attributes)">
            </canvas>
            <input type="hidden" :name="element.name" :id="'output_'+element.name" :value="_.get(submitted_data, element.name, '')" :required="element.required && applyValidations" :class="[element.conditional_class, 'signature']"
            :data-msg-required="element.required_error_msg">
        </div>
        <div class="form-text">
            <span :id="'clear_' + element.name" class="pointer mr-4" :title="trans('messages.clear_signature_pad')" :data-name="element.name">
                <i class="far fa-times-circle"></i>
                {{trans('messages.clear')}}
            </span>
            <span :id="'undo_' + element.name" class="pointer" :title="trans('messages.undo')" :data-name="element.name">
                <i class="fas fa-undo"></i>
                {{trans('messages.undo')}}
            </span>
            <small class="form-text text-muted" v-if="element.help_text">
                {{element.help_text}}
            </small>
        </div>
    </div>

</template>

<script>
	export default {
		props: {
			element: Object,
            settings: Object,
            applyValidations: {
                type: Boolean,
                default: true //If passed false then validation will not be applied to field. Used while creating forms
            },
            submitted_data: Object
		},
        data(){
            return{
                form_group_class: '',
                display_handler:'hide',
            }
        },
        mounted() {
            this.$eventBus.$emit('callApplyConditions');
        },
        created() {
            const self = this;
            var field = self.element;
            var notification_position = self.settings.notification.position;
            $(function() {
                //input range
                if (field.type == 'range') {
                    initialize_rangeslider(field.name);

                    //on change of range silder call applyConditions through event
                    $('#'+field.name).on('change', function() {
                        self.$eventBus.$emit('callApplyConditions');
                    });
                }
                
                //datetime picker
                if (field.type == 'calendar') {

                    initialize_datetimepicker(field.name, field.start_date, field.end_date, field.format, field.time_format, field.disabled_days, field.enable_time_picker, field.time_picker_inline);

                    //on change of dateTimePicker call applyConditions through event
                    $('#'+field.name).on('change.datetimepicker', function() {
                        self.$eventBus.$emit('callApplyConditions');
                    });
                }

                if (field.type == 'file_upload') {
                    initialize_dropzone(field.name, field.upload_text, field.no_of_files, field.file_size_limit, field.allowed_file_type);
                }

                if (field.type == 'text_editor') {
                    initialize_text_editor(field.name, field.placeholder, field.editor_height);
                }

                if (field.type == 'rating') {
                    initialize_star_rating(field.name);

                    //on change of star rating call applyConditions through event
                    $('#'+field.name).on('rating:change', function(event, value){
                        self.$eventBus.$emit('callApplyConditions');
                    });
                }

                if (field.type == 'signature') {
                    initialize_signature_pad(field.name);
                }

                initializeToastrSettingsForForm(notification_position);
            });
        },
        methods:{
            getDynamicallyGeneratedAttributeObj(validations, attributes) {

                var rules = [];
                if(this.applyValidations){
                    _.forEach(validations, function(validation) {
                        var rule = 'data-rule-' + validation.rule;
                        var error_msg = 'data-msg-' + validation.rule;
                        var is_rule_required = validation.value ? validation.value : true;

                        rules.push({[rule]:is_rule_required, [error_msg]:validation.error_msg});
                    });
                }
                
                var custom_attr = this.getCustomAttributes(attributes);
                var custom_property =  _.merge({}, [custom_attr], rules);
                
                return custom_property[0];
            },
            onMouseHover() {
                if(!this.applyValidations){
                    this.form_group_class = 'hovered_element';
                    this.display_handler = '';
                }
            },
            onMouseLeave(element) {
                if(!this.applyValidations){
                    this.form_group_class = '';
                    this.display_handler = 'hide';
                }
                
            },
        }
	}
</script>
<style scoped>
    .active_element {
        border: 2px dashed red;
        padding:15px;
        cursor: pointer;
    }

    .hovered_element {
        border: 2px dashed red;
        padding:15px;
        cursor: pointer;
    }
    .hide {
        display: none;
    }
    .show {
        display: block;
    }
</style>