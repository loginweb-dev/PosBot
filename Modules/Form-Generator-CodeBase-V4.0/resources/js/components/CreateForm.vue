<template>
<div>
    <form id="create_form">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="form-tab" data-toggle="tab" href="#form" role="tab" aria-controls="form" aria-selected="true">
                    <i class="fas fa-align-justify"></i> {{ trans('messages.form') }}
                </a>
            </li>
            <li class="nav-item" role="presentation" id="tour_step_4">
                <a class="nav-link" id="field_conditions-tab" data-toggle="tab" href="#field_conditions" role="tab" aria-controls="field_conditions" aria-selected="false">
                    <i class="fas fa-wrench"></i> {{trans('messages.field_conditions')}}
                </a>
            </li>
            <li class="nav-item" role="presentation" id="tour_step_5">
                <a class="nav-link" id="email-tab" data-toggle="tab" href="#email" role="tab" aria-controls="email" aria-selected="false">
                    <i class="fas fa-envelope"></i> {{trans('messages.email')}}
                </a>
            </li>
            <li class="nav-item" role="presentation" id="tour_step_6">
                <a class="nav-link" id="form-settings-tab" data-toggle="tab" href="#form-settings" role="tab" aria-controls="form-settings" aria-selected="false">
                    <i class="fas fa-cogs"></i> {{trans('messages.settings')}}
                </a>
            </li>
            <li class="nav-item" role="presentation" id="tour_step_7">
                <a class="nav-link" id="mailchimp-tab" data-toggle="tab" href="#mailchimp" role="tab" aria-controls="mailchimp" aria-selected="false">
                    <i class="fas fa-mail-bulk"></i> {{trans('messages.mailchimp')}}
                </a>
            </li>
            <li class="nav-item" role="presentation" id="tour_step_8">
                <a class="nav-link" id="js-css-tab" data-toggle="tab" href="#js-css" role="tab" aria-controls="js-css" aria-selected="false">
                    <i class="fas fa-file-code"></i> {{trans('messages.additional_js_css')}}
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <button type="button" class="btn btn-xs bg-gradient-danger app_tour_play_btn" @click="getAppTour">
                <i class="far fa-play-circle"></i>
                {{trans('messages.app_tour')}}
            </button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="form" role="tabpanel" aria-labelledby="form-tab">
                <form-tab :selected_elements="selected_elements" :settings="settings" :form="form" :placeholder_img="placeholderImg" :form_custom_attributes="form_custom_attributes"></form-tab>
            </div>
            <div class="tab-pane fade" id="field_conditions" role="tabpanel" aria-labelledby="field_conditions-tab">
                <condtionalFields
                :conditional-fields="conditional_fields"
                :selected-elements="selected_elements"
                ></condtionalFields>
            </div>
            <div class="tab-pane fade" id="email" role="tabpanel" aria-labelledby="email-tab">
                <email-tab 
                :emailConfig="emailConfig"
                :selected_elements="selected_elements"
                :settings="settings">
                </email-tab>
            </div>
            <div class="tab-pane fade" id="form-settings" role="tabpanel" aria-labelledby="form-settings-tab">
                <settings-tab
                :settings="settings"
                :selected_elements="selected_elements">
                </settings-tab>
            </div>
            <div class="tab-pane fade" id="mailchimp" role="tabpanel" aria-labelledby="mailchimp-tab">
                <mailchimp 
                :details="mailchimp_details"
                :selected_elements="selected_elements">
                </mailchimp>
            </div>
            <div class="tab-pane fade" id="js-css" role="tabpanel" aria-labelledby="js-css-tab">
                <additional-js-css :additional-data="additionalData">
                </additional-js-css>
            </div>
        </div>
        <!-- submit button -->
        <div v-if="selected_elements.length">
            <hr class="mt-5">
            <div id="tour_step_9">
                <button type="submit" class="btn btn-success btn-lg float-right ladda-form-save-btn mb-2" name="submit_type" value="0">
                    {{trans('messages.save_as_form')}}
                </button>
                <button type="submit" class="btn btn-secondary btn-lg float-right ladda-template-save-btn mb-2 mr-1" name="submit_type" value="1" v-if="disabled_save_temp_btn">
                    {{trans('messages.save_as_template')}}
                </button>
            </div>
        </div>
        <AskUserChoice id="userChoiceModal">
        </AskUserChoice>
    </form>
</div>
</template>

<script>
    import formTab from "./FormTab";
    import emailTab from "./EmailTab";
    import settingsTab from "./SettingsTab";
    import additionalJsCss from "./AdditionalJsCss";
    import mailchimp from "./Mailchimp";
    import condtionalFields from "./ConditionalField";
    import AskUserChoice from "./AskUserChoiceAfterSave"
    export default {
        props: ['formData', 'placeholderImg', 'saveTemplate'],
        components: {
            formTab,
            emailTab,
            settingsTab,
            mailchimp,
            additionalJsCss,
            condtionalFields,
            AskUserChoice
        },
        data() {
            return {
                selected_elements: [],
                form_parsed: [],
                form:[],
                emailConfig: {},
                settings:{},
                dashboard:'',
                additionalData: {},
                mailchimp_details: {},
                conditional_fields:[],
                form_scheduling: {closed_msg:'The form has been closed now!', start_date_time:'', end_date_time:'', is_enabled: false},
                form_submision_ref: {
                    is_enabled: false,
                    prefix: '',
                    suffix: '',
                    start_no: '1',
                    min_digit: '4'
                },
                form_theme: 'default',
                responseData:[],
                is_enabled_draft_submit: 0,
                form_custom_attributes : [],
                disabled_save_temp_btn:null,
            };
        },
        created() {
            this.form_parsed = JSON.parse(this.formData);
            this.form.name = this.form_parsed.name;
            this.form.slug = this.form_parsed.slug;
            this.form.description = this.form_parsed.description;
            this.form.is_template = this.form_parsed.is_template;
            this.disabled_save_temp_btn = JSON.parse(this.saveTemplate);
            if (this.form_parsed.schema === null) {
                this.emailConfig = this.getData('email_config');
                this.settings = this.getData('settings');
                this.additionalData = {js:'', css:''};
                this.conditional_fields = this.getData('conditional_fields');
            } else {
                this.selected_elements = this.form_parsed.schema.form;
                this.emailConfig = this.form_parsed.schema.emailConfig;
                this.settings = this.form_parsed.schema.settings;
                this.additionalData = _.isNull(this.form_parsed.schema.additional_js_css) ? {js:'', css:''} : this.form_parsed.schema.additional_js_css;

                this.mailchimp_details = _.isNull(this.form_parsed.mailchimp_details) ? this.getData('mailchimp') : this.form_parsed.mailchimp_details;

                this.conditional_fields = _.isUndefined(this.form_parsed.schema.conditional_fields) ? this.getData('conditional_fields') : this.form_parsed.schema.conditional_fields;
                this.form_custom_attributes = _.isUndefined(this.form_parsed.schema.form_attributes) ? this.form_custom_attributes : this.form_parsed.schema.form_attributes;
            }

            this.getNewlyAddedPropertyForExistingForm();
            this.$eventBus.$on('callRedirectUser', (data) => {
                $("#userChoiceModal").modal("hide");
                setTimeout(() => {
                    this.redirectUsersAccordingToResponse(data);
                }, 1000);
            });
        },
        beforeDestroy() {
            this.$eventBus.$off('callRedirectUser');
        },
        mounted(){
            const self = this;
            
            $('form#create_form').validate({
                ignore: ".note-editor *",
                submitHandler: function(form, e) {
                    var field_names = [];
                    if (self.selected_elements.length > 0) {
                        for (let index = 0; index < self.selected_elements.length; index++) {
                            self.selected_elements[index].extras.showConfigurator = false;
                            if (_.isEmpty(self.selected_elements[index].name)) {
                                toastr.error(self.trans('messages.field_dont_have_name_property', {
                                    input: self.selected_elements[index].label,
                                }));
                                return false;
                            } else if (/\s/.test(self.selected_elements[index].name)) {
                                toastr.error(self.trans('messages.field_contain_space', {
                                    input: self.selected_elements[index].label,
                                }));
                                return false;
                            } else if (_.includes(field_names, self.selected_elements[index].name)) {
                                toastr.error(self.trans('messages.field_contain_duplicate_field_name', {
                                    input: self.selected_elements[index].label,
                                }));
                                field_names = [];
                                return false;
                            }else if (!_.includes(field_names, self.selected_elements[index].name)) {
                                field_names.push(self.selected_elements[index].name);
                            }
                        }
                    }

                    let data = _.pick(self.form, ['name', 'description', 'slug']);
                    data.form = self.selected_elements;
                    data.email_config = self.emailConfig;
                    data.settings = self.settings;
                    data.js_css = self.additionalData;
                    data.mailchimp_details = self.mailchimp_details;
                    data.conditional_fields = self.conditional_fields;
                    data.is_template = $("input[name='submit_type']").val();
                    data.form_attributes = self.form_custom_attributes;

                    //get ladda btn based on submit type
                    if (data.is_template === '1') {
                        var ladda = Ladda.create(document.querySelector('.ladda-template-save-btn'));
                    } else {
                        var ladda = Ladda.create(document.querySelector('.ladda-form-save-btn'));
                    }

                    if ($('form#create_form').valid()) {

                        //disable both btn and start ladda
                        $("button.ladda-form-save-btn, button.ladda-template-save-btn").attr('disabled', 'disabled');
                        ladda.start();
                        
                        axios
                        .put('/forms/'+self.form_parsed.id, data)
                        .then(function(response) {
                            //remove disable from both btn and stop ladda
                            $("button.ladda-form-save-btn, button.ladda-template-save-btn").removeAttr("disabled");
                            ladda.stop();

                            if (response.data.success == true) {
                                self.responseData = response.data;
                                $("#userChoiceModal").modal("show");
                            } else {
                                toastr.error(response.data.msg);
                            }
                        })
                        .catch(function(error) {
                            console.log(error);
                        });
                    }
                }
            });

            //if mpfg_tour not finished call getAppTour
            if (_.isNull(localStorage.getItem("mpfg_tour"))) {
                localStorage.setItem("mpfg_tour", 'finished');
                this.getAppTour();
            }
        },
        methods: {
            getData(type) {
                if (type == 'email_config') {

                    var email = {
                        email: {enable: '', from: '', to: '', cc:'', bcc:'', subject: '', body:'', attach_pdf: false},
                        auto_response: {from:'', to:'', subject: '', body: '', is_enable: false, attach_pdf: false},
                        smtp: {host:'', port:'', from_address:'', from_name:'', encryption:'', username:'', password:'', use_system_smtp:1}
                    };

                    return email;
                } else if (type == 'settings') {
                    var setting = {
                        recaptcha:{is_enable:0, site_key:'', secret_key:''}, color:{label:'#000000', error_msg:'#a94442', required_asterisk_color:'#a94442', background:'#ffffff', image_path:''},
                        notification:{post_submit_action:'same_page',failed_msg:'Something went wrong, please try again.', success_msg:'Email sent successfully.', redirect_url:'', position: 'toast-top-right'},
                        submit:{text:'Submit', loading_text:'Submitting...', btn_alignment:'float-right', btn_size:'', btn_color:'btn-primary', btn_style: 'default', btn_icon: 'none', icon_position:'left'},
                        form_data:{col_visible:[],btn_enabled:['view', 'delete']},
                        background:{bg_type:'bg_color'},
                        form_scheduling:this.form_scheduling,
                        form_submision_ref: this.form_submision_ref,
                        theme: this.form_theme,
                        is_enabled_draft_submit: this.is_enabled_draft_submit
                    };

                    return setting;
                } else if(type == 'mailchimp'){
                    return {
                        is_enable: false,
                        api_key: '',
                        list_id: '',
                        email_field: '',
                        name_field: ''
                    };
                } else if (type == 'conditional_fields') {
                    var conditional_fields = [{'action':'', 'element':'', 'conditions':[{'condition':'', 'value':"", 'element_type' : 'text', 'element_index' : '', 'operator' : '=='}]}];

                    return conditional_fields;
                }
            },
            getNewlyAddedPropertyForExistingForm() {
                _.forEach(this.selected_elements, function(element) {
                    if (_.isUndefined(element.conditional_class)) {
                        element.conditional_class = '';
                    }
                    
                    if (_.isUndefined(element.col)) {
                        element.col = 'col-md-12';
                    }
                });

                if (_.isUndefined(this.settings.form_scheduling)) {
                    this.settings.form_scheduling = this.form_scheduling;
                }

                if (_.isUndefined(this.settings.form_submision_ref)) {
                    this.settings.form_submision_ref = this.form_submision_ref;
                }

                if (_.isUndefined(this.settings.theme)) {
                    this.settings.theme = this.form_theme;
                }

                if (_.isUndefined(this.settings.is_enabled_draft_submit)) {
                    this.settings.is_enabled_draft_submit = this.is_enabled_draft_submit;
                }

                if (_.isUndefined(this.settings.notification.position)) {
                    this.settings.notification.position = 'toast-top-right';
                }

                if (_.isUndefined(this.settings.submit.btn_style)) {
                    Vue.set(this.settings.submit, 'btn_style', 'default');
                }

                if (_.isUndefined(this.settings.submit.btn_icon)) {
                    Vue.set(this.settings.submit, 'btn_icon', 'none');
                }

                if (_.isUndefined(this.settings.submit.icon_position)) {
                    Vue.set(this.settings.submit, 'icon_position', 'left');
                }
            },
            redirectUsersAccordingToResponse(choice) {
                if (choice == 'home') {
                    window.location = this.responseData.redirect;
                } else if (choice == 'preview') {
                    window.open(this.responseData.preview, this.responseData.form_name);
                }
            },
            getAppTour() {

                if (!$('#appTab a[href="#form-generator"]').hasClass('active')) {
                    $('#appTab a[href="#form-generator"]').tab('show');
                }

                const self = this;
                var intro = introJs();
                intro.setOptions({
                    steps: [
                        {
                            intro : self.trans('messages.welcome_tour_msg')
                        },
                        {
                            element: document.querySelectorAll('#tour_step_1')[0],
                            intro: self.trans('messages.tour_step_1_intro'),
                            position: 'right',
                            scrollTo: 'tooltip'
                        },
                        {
                            element: '#tour_step_2',
                            intro: self.trans('messages.tour_step_2_intro'),
                            position: 'right',
                            scrollTo: 'tooltip'
                        },
                        {
                            element: '#tour_step_3',
                            intro: self.trans('messages.tour_step_3_intro'),
                            position: 'bottom',
                            scrollTo: 'tooltip'
                        },
                        {
                            element: '#tour_step_4',
                            intro: self.trans('messages.tour_step_4_intro'),
                            scrollTo: 'tooltip'
                        },
                        {
                            element: '#tour_step_5',
                            intro: self.trans('messages.tour_step_5_intro'),
                            scrollTo: 'tooltip'
                        },
                        {
                            element: '#tour_step_6',
                            intro: self.trans('messages.tour_step_6_intro'),
                            scrollTo: 'tooltip'
                        },
                        {
                            element: '#tour_step_7',
                            intro: self.trans('messages.tour_step_7_intro'),
                            scrollTo: 'tooltip'
                        },
                        {
                            element: '#tour_step_8',
                            intro: self.trans('messages.tour_step_8_intro'),
                            scrollTo: 'tooltip'
                        },
                        {
                            element: '#tour_step_9',
                            intro: self.trans('messages.tour_step_9_intro'),
                            scrollTo: 'tooltip'
                        }
                    ]
                });

                intro.start();
            }
        }
    }
</script>