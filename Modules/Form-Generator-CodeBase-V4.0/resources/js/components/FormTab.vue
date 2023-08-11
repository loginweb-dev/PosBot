<template>
<div class="row mt-3">
    <div class="col-md-2" id="tour_step_1">
        <div class="element-sidebar">
            <div class="card">
                <div class="card-header">
                    <p class="card-title">{{trans('messages.elements')}}</p>
                </div>
                
                <div class="collapse show" id="basicElements">
                    <div class="card-body pr-1 pl-1 elements_sidebar_height">
                        <draggable class="dragArea list-group"
                            :list="basic_elements"
                            :group="{ name: 'element', pull: 'clone', put: false }"
                            :clone="cloneElement"
                            @change="change"
                        >
                            <div class="list-group-item" 
                                v-for="element in basic_elements" 
                                :key="element.type">
                                    <button type="button" class="btn btn-primary btn-block" :title="element.tooltip">
                                        <i :class="'float-left fas fa-' + element.display_icon"></i>{{ element.label }}
                                    </button>
                            </div>
                        </draggable>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-7">
        <div class="card scrollableCard" :style="{'background': getBackgroundStyle(), 'background-image':`${backgroundImg}`}" style="height: 100%;" id="tour_step_2">
            <div class="text-center mt-2" v-if="selected_elements.length == 0">
                <h5>{{trans('messages.pls_add_element')}}</h5>
            </div>
            <draggable
                class="dragArea card-body row"
                :list="selected_elements"
                group="element"
                handle=".handle"
                @change="change"
                >   
                    <div
                        v-for="(element, index) in selected_elements" 
                        :key="element.id"
                        :class="element.col" 
                        @click="toggleConfigurator(index, true)"
                    >
                        <fieldGenerator 
                            :element="element" 
                            :settings="settings"
                            :applyValidations="applyValidations"
                            ></fieldGenerator>
                    </div>
            </draggable>
        </div>
    </div>

    <div class="col-md-3" id="tour_step_3">
        <div class="element-sidebar">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link" id="formDetails-tab" data-toggle="tab" href="#formDetails" role="tab" :class="isConfiguratorOpen ? '' : 'active'" aria-controls="formDetails" aria-selected="true">
                        {{ trans('messages.form') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="configuration-tab" :class="isConfiguratorOpen ? 'active' : ''" data-toggle="tab" href="#configuration" role="tab" aria-controls="configuration" aria-selected="false">
                        {{ trans('messages.element_configuration') }}
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="tab_content" role="tabpanel">
                <div class="tab-pane fade" :class="isConfiguratorOpen ? '' : 'active show'" id="formDetails" role="tabpanel" aria-labelledby="formDetails-tab">
                    <div class="card mt-1 configurator_form_height">
                        <div class="card-body">
                            <div class="form-group">
                                <div class="col-md-12 mb-2">
                                    <label>
                                        {{ trans('messages.form_name') }}
                                        <span class="error">*</span>
                                    </label>
                                    <input type="text" class="form-control"
                                    v-model="form.name" required @change="generateFormSlug(form.name)">
                                </div>

                                <div class="col-md-12 mb-2">
                                    <label for="form_slug">
                                        {{ trans('messages.form_slug') }}
                                        <span class="error">*</span>
                                    </label>

                                    <input type="text" class="form-control"
                                    v-model="form_slug" required readonly>
                                </div>

                                <div class="col-md-12">
                                    <label>
                                    {{ trans('messages.form_description') }}
                                    </label>
                                    <input type="text" class="form-control" v-model="form.description">
                                </div>
                                <!-- form custom attribute -->
                                <div class="col-md-12" v-if="form_custom_attributes.length">
                                    <hr>
                                    <label>{{trans('messages.custom_attributes')}}</label>
                                </div>
                                <div class="row form_custom_attr_field" v-for="(form_custom_attribute, index) in form_custom_attributes">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <input type="text" class="form-control" v-model="form_custom_attribute.key" :placeholder="trans('messages.key')">
                                            <input type="text" class="form-control" v-model="form_custom_attribute.value" :placeholder="trans('messages.value')">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-sm" @click="removeAttribute(index)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-info" @click="addMoreAttribute" style="margin: 28px;">
                                    <i class="fas fa-plus-circle"></i>
                                    {{trans('messages.add_form_custom_attribute')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade mb-2" :class="isConfiguratorOpen ? 'active show' : ''" id="configuration" role="tabpanel" aria-labelledby="configuration-tab">
                        
                    <span v-for="(element, index) in selected_elements"
                        v-if="element.extras.showConfigurator"
                        :key="element.id">
                        <fieldConfigurator  
                            :element="element" 
                            :index="index"
                            :selected_elements="selected_elements"
                            v-on:toggleConfigurator="toggleConfiguratorEvent"
                            v-on:deleteElement="deleteElement"
                            ></fieldConfigurator>
                    </span>

                    <div v-if="selected_elements.length == 0 || !isConfiguratorOpen">
                        <div class="mt-1 ml-2">
                            {{ trans('messages.pls_add_element_to_configure') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import draggable from "../../../node_modules/vuedraggable";
    import fieldGenerator from "./FieldGenerator";
    import fieldConfigurator from "./FieldConfigurator";
    import ElementDetails from "./ElementDetails";
    let idGlobal = 0;

    export default {
        props:{
            selected_elements: Array,
            settings:Object,
            form:Array,
            placeholder_img:String,
            form_custom_attributes: Array
        },
        components: {
            draggable,
            fieldGenerator,
            fieldConfigurator,
            ElementDetails
        },
        data(){
            const self = this;
            return {
                applyValidations: false,
                basic_elements: [
                    { type: 'text', 
                        subtype:'text',
                        label: self.trans('messages.input'),
                        help_text: '',
                        display_icon: 'grip-lines',
                        prefix_icon: 'none',
                        suffix_icon: 'none',
                        placeholder:'',
                        size:'',
                        custom_class:'',
                        conditional_class:'',
                        col:'col-md-12',
                        custom_attributes:[],
                        tooltip: self.trans('messages.text_tooltip')
                    },
                    { type: 'textarea', 
                        label: self.trans('messages.textarea'),
                        help_text: '', 
                        display_icon: 'list',
                        prefix_icon: 'none',
                        suffix_icon: 'none',
                        rows: 3,
                        columns:'',
                        placeholder:'',
                        custom_class:'',
                        conditional_class:'',
                        col:'col-md-12',
                        custom_attributes:[],
                        tooltip: self.trans('messages.textarea_tooltip')
                    },
                    { type: 'dropdown',                        
                        label: self.trans('messages.dropdown'),
                        help_text: '',
                        display_icon: 'caret-square-down',
                        prefix_icon: 'none',
                        suffix_icon: 'none',
                        options:'Option-1\nOption-2',
                        size:'',
                        custom_class:'',
                        conditional_class:'',
                        multiselect:false,
                        col:'col-md-12',
                        custom_attributes:[],
                        tooltip: self.trans('messages.dropdown_tooltip')
                    },
                    { type: 'radio', 
                        label: self.trans('messages.radio'),
                        help_text: '', 
                        display_icon: 'dot-circle',
                        options:'Option-1\nOption-2',
                        conditional_class:'',
                        col:'col-md-12',
                        custom_attributes:[],
                        tooltip: self.trans('messages.radio_tooltip')
                    },
                    { type: 'checkbox', 
                        label: self.trans('messages.checkbox'),
                        help_text: '', 
                        display_icon: 'check-circle',
                        options:'Option-1\nOption-2',
                        conditional_class:'',
                        col:'col-md-12',
                        custom_attributes:[],
                        tooltip: self.trans('messages.checkbox_tooltip')
                    },
                    { type: 'heading', 
                        label: self.trans('messages.heading_paragrahp'),
                        tag: 'h1',
                        text_color: '#212529',
                        content: 'Click to change it',
                        display_icon: 'heading',
                        col:'col-md-12',
                        tooltip: self.trans('messages.heading_paragrahp_tooltip')
                    },
                    { type: 'range', 
                        label: self.trans('messages.range'),
                        help_text: '',
                        display_icon: 'arrows-alt-h',
                        min:1,
                        max:10,
                        step:1,
                        data_orientation:'horizontal',
                        conditional_class:'',
                        col:'col-md-12',
                        custom_attributes:[],
                        tooltip: self.trans('messages.range_tooltip')
                    },
                    { type: 'calendar',
                        label: self.trans('messages.datetime'),
                        help_text: '',
                        size:'',
                        display_icon: 'calendar-alt',
                        prefix_icon: 'none',
                        suffix_icon: 'none',
                        custom_class:'',
                        start_date:'none',
                        end_date:'none',
                        format:'MM-DD-YYYY',
                        disabled_days:[],
                        enable_time_picker:false,
                        time_format:'12',
                        time_picker_inline:false,
                        conditional_class:'',
                        col:'col-md-12',
                        custom_attributes:[],
                        tooltip: self.trans('messages.datetime_tooltip')
                    },
                    {
                        type: 'file_upload',
                        label: self.trans('messages.file_upload'),
                        help_text:'',
                        display_icon: 'upload',
                        upload_text: self.trans('messages.drop_a_file_here'),
                        no_of_files:'1',
                        send_as_email_attachment: false,
                        file_size_limit: 5,
                        file_type:'all',
                        allowed_file_type:'',
                        custom_class:'',
                        conditional_class:'',
                        col:'col-md-12',
                        custom_attributes:[],
                        tooltip: self.trans('messages.file_upload_tooltip')
                    },
                    {
                        type: 'text_editor',
                        label: self.trans('messages.text_editor'),
                        placeholder: self.trans('messages.jot_down_here'),
                        help_text:'',
                        display_icon: 'keyboard',
                        editor_height: 150,
                        conditional_class:'',
                        col:'col-md-12',
                        custom_attributes:[],
                        tooltip: self.trans('messages.text_editor_tooltip')
                    },
                    {
                        type: 'terms_and_condition',
                        label: self.trans('messages.terms_condition'),
                        link: '',
                        display_icon: 'file-signature',
                        custom_class: '',
                        conditional_class:'',
                        col:'col-md-12',
                        custom_attributes:[],
                        tooltip: self.trans('messages.terms_condition_tooltip')
                    },
                    {
                        type: 'rating',
                        label: self.trans('messages.rating'),
                        display_icon: 'star',
                        conditional_class:'',
                        stars_to_display:5,
                        min_rating:0,
                        max_rating:5,
                        increment:0.5,
                        direction:'ltr',
                        size:'md',
                        col:'col-md-12',
                        custom_attributes:[],
                        tooltip: self.trans('messages.rating_tooltip')
                    },
                    {
                        type: 'switch',
                        label: self.trans('messages.switch'),
                        display_icon: 'toggle-off',
                        conditional_class: '',
                        help_text:'',
                        size:'switch',
                        col:'col-md-12',
                        custom_attributes:[],
                        tooltip: self.trans('messages.switch_tooltip')
                    },
                    {
                        type: 'hr',
                        label: self.trans('messages.horizontal_line'),
                        padding_top:1,
                        padding_bottom:1,
                        border_size:1,
                        border_type:'solid',
                        border_color:'#0a0a0a',
                        display_icon: 'window-minimize',
                        col:'col-md-12',
                        tooltip: self.trans('messages.hr_tooltip')
                    },
                    {
                        type: 'html_text',
                        label: self.trans('messages.html_text'),
                        html_text:'',
                        display_icon: 'code',
                        custom_class: '',
                        col:'col-md-12',
                        tooltip: self.trans('messages.html_text_tooltip')
                    },
                    {
                        type: 'signature',
                        label: self.trans('messages.signature'),
                        display_icon: 'signature',
                        col: 'col-md-12',
                        html_text:'',
                        custom_class:'',
                        custom_attributes:[],
                        tooltip: self.trans('messages.signature_tooltip')
                    }
                ],
                isConfiguratorOpen: false,
                form_slug: ''
            }
        },
        computed:{
            backgroundImg() {
                if (this.selected_elements.length == 0) {
                    return 'url(' + this.placeholder_img +')';
                } else {
                    return '';
                }
            }
        },
        created() {
            if (this.selected_elements.length > 0) {

                var ids = [];
                _.forEach(this.selected_elements, function(element) {
                  ids.push(element.id);
                });

                var id = _.max(ids);

                idGlobal = ++id;
            }
            this.form_slug = this.form.slug;
        },
        methods: {
            addMoreAttribute() {
                var custom_attribute = {'key':'', 'value':''};
                this.form_custom_attributes.push(custom_attribute);
            },
            removeAttribute(index) {
                this.form_custom_attributes.splice(index, 1);
            },
            getBackgroundStyle() {

                if (this.settings.background.bg_type !== 'bg_image') {
                    return this.settings.color.background;
                } else {
                    return '';
                }
                
            },
            change: function(evt) {
                window.console.log(evt);
                if(evt.added != undefined){
                    //find index of an added element
                    var index = this.selected_elements.indexOf(evt.added.element);
                    this.toggleConfigurator(index, true);
                    //add field for col visibility in datatable
                    this.settings.form_data.col_visible.push(evt.added.element.name);
                }
            },
            cloneElement(element_details) {
                var details_cloned = _.clone(element_details);
                var data = {
                    id: idGlobal++,
                    name: 'field_' + idGlobal,
                    extras: {showConfigurator: false}
                };

                _.unset(details_cloned, 'id');
                _.unset(details_cloned, 'name');
                _.unset(details_cloned, 'display_icon');
                _.unset(details_cloned, 'tooltip');

                var output = _.merge(data, details_cloned);

                output['required'] = false;
                output['required_error_msg'] = 'This field is required';
                output['validations'] = [];

                return output;
            },
            toggleConfigurator(index, show) {
                //Check if any previously open, then close it
                const self = this;
                if(show == true){
                    _.forEach(this.selected_elements, function(value, key) {
                        self.selected_elements[key].extras.showConfigurator = false;
                    });
                }

                this.selected_elements[index].extras.showConfigurator = show;
                this.isConfiguratorOpen = show;
            },
            toggleConfiguratorEvent(values){                
                this.toggleConfigurator(values.index, values.show);
            },
            deleteElement(index) {

                var element = this.selected_elements[index];
                var name = element.name;
                var deletedElementIndex = this.settings.form_data.col_visible.indexOf(name);

                this.selected_elements.splice(index, 1);
                this.isConfiguratorOpen = false;

                if (deletedElementIndex != '-1') {
                    this.settings.form_data.col_visible.splice(index, 1);
                }
            },
            generateFormSlug(name) {
                const self = this;
                axios
                .get('/generate-form-slug', {
                    params:{
                        name: name,
                    }
                })
                .then(function(response) {
                    self.form.slug = response.data;
                    self.form_slug = self.form.slug;
                }).catch(function(error) {
                    console.log(error);
                });
            }
        }
    }
</script>
<style scoped>
    .scrollableCard{
        display: flex;
        flex-direction: column;
        height: 200vh;
        min-height: 392px;
    }
    #tab_content {
        border-right: 1px solid #d2e3f5;
        border-left: 1px solid #d2e3f5;
        border-bottom: 1px solid #d2e3f5;
    }
    .card-title{
        font-size: 1rem !important;
    }
    .list-group-item{
        border: 0px !important;
        padding-left: 0.25rem !important;
        padding-right: 0.25rem !important;
    }
    .element-sidebar{
        position: -webkit-sticky;
        position: sticky;
        top: 0rem;
        height: 58vh;
    }
    .elements_sidebar_height{
        overflow-y: auto;
        max-height: 58vh;
    }
    .configurator_form_height {
        overflow-y: auto;
        max-height: 58vh;
    }
    #formDetails {
        height : 58vh;
    }
</style>