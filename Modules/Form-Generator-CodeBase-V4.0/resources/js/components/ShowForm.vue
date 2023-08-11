<template>
    <form id="show_form" v-bind="getCustomAttributes(form_custom_attributes)">
        <div class="row">
            <div
                v-for="(element, index) in schema" 
                :key="index"
                :class="[element.col ? element.col : 'col-md-12']">
                <fieldGenerator 
                    :element="element"
                    :settings="settings"
                    :submitted_data="submitted_data"
                    @apply_conditions="applyConditions">
                </fieldGenerator>
            </div>
        </div>
        <div class="g-recaptcha" :data-sitekey="settings.recaptcha.site_key" v-if="settings.recaptcha.is_enable"></div>
        <br/>
        <button type="submit" class="btn submit_btn ladda-button" :class="[settings.submit.btn_alignment, settings.submit.btn_color, settings.submit.btn_size]" data-style="expand-right" name="status" value="complete">
            <i class="fas " :class="settings.submit.btn_icon" v-if="settings.submit.btn_icon != 'none' && settings.submit.icon_position == 'left'"></i>
            <span class="btn_text ladda-label"> {{settings.submit.text}} </span>
            <i class="fas " :class="settings.submit.btn_icon" v-if="settings.submit.btn_icon != 'none' && settings.submit.icon_position == 'right'"></i>
        </button>
        <button v-if="!actionBy.length && settings.is_enabled_draft_submit"
            formnovalidate="formnovalidate" type="submit" class="btn mr-2 draft_btn"
            :class="[settings.submit.btn_size, settings.submit.btn_alignment, settings.submit.btn_style == 'default' ? 'btn-warning': 'btn-outline-warning']" name="status" value="incomplete">
            <i class="fas " :class="settings.submit.btn_icon" v-if="settings.submit.btn_icon != 'none' && settings.submit.icon_position == 'left'"></i>
            {{trans('messages.draft')}}
            <i class="fas " :class="settings.submit.btn_icon" v-if="settings.submit.btn_icon != 'none' && settings.submit.icon_position == 'right'"></i>
        </button>
        <br><br>
        <div class="alert alert-success mt-3" role="alert"
            v-show="is_available_form_editable_url">
            <h4 class="alert-heading"
                v-html="trans('messages.form_editable_url')">
            </h4>
            <hr>
            <span id="form_editable_url"></span>
        </div>
    </form>
</template>

<script>
    import fieldGenerator from "./FieldGenerator";

    export default{
        props: ['form', 'actionBy'],
        components: {
            fieldGenerator
        },
        data() {
            return {
                schema: [],
                form_parsed: [],
                settings:{},
                conditional_fields:[],
                is_available_form_editable_url: false,
                submitted_data:{},
                token:'',
                form_data_id: '',
                form_custom_attributes : []
            };
        },
        mounted() {
            const self = this;

            var validator = $('#show_form').validate({
                ignore: ".note-editor *, .hide",
                submitHandler: function(form, e) {
                    e.preventDefault();
                    
                    var form_data = $('#show_form').serialize();

                    $("button.submit_btn, button.draft_btn").attr('disabled', 'disabled');
                    var status = $("input[name=status]").val();
                    if (status == 'complete') {
                        $("span.btn_text").text(self.settings.submit.loading_text);
                        var ladda = Ladda.create(document.querySelector('.ladda-button'));
                    } else if (status == 'incomplete') {
                        var ladda = Ladda.create(document.querySelector('.draft_btn'));
                    }

                    ladda.start();

                    if(typeof mpfg_form_submitted === "function"){
                        var form_array_data = $('#show_form').serializeArray();
                        mpfg_form_submitted(self.form_parsed.id, form_array_data);
                    }

                    let url = '/form-data/' + self.form_parsed.id + '?token=' + self.token + '&form_data_id=' + self.form_data_id;
                    if (self.actionBy.length && self.actionBy == 'admin') {
                        url = '/update/'+self.form_parsed.id+'/data/'+self.form_data_id;
                    }

                    axios
                        .post(url, {form_data})
                        .then(function(response) {

                            //set token & form data id for form
                            if (status == 'incomplete') {
                                self.token = response.data.notification.token;
                                self.form_data_id = response.data.notification.form_data_id;
                            }
                            //remove disabled attr.
                            $("button.submit_btn, button.draft_btn").removeAttr("disabled");
                            //set submit text to submit btn
                            $("span.btn_text").text(self.settings.submit.text);
                            ladda.stop();
                            window.onbeforeunload = null;
                            if (response.data.success == true) {
                                
                                toastr.success(response.data.notification.success_msg);

                                //if notification action is redirect & status is compelete, redirect user to given url
                                if ((response.data.notification.post_submit_action === 'redirect') && (status == 'complete')) {
                                    window.parent.location = response.data.notification.redirect_url;
                                }

                                //if submit action is on same page & status is complete, replace url to form view url after 10 sec
                                if (status == 'complete') {
                                    setTimeout(() => {
                                        location.replace(response.data.notification.view_form_url);
                                    }, 5000);
                                }

                                //if status is incomplete display form editable url
                                if (status == 'incomplete') {
                                    self.is_available_form_editable_url = true;
                                    $('span#form_editable_url').text(response.data.notification.form_editable_url);
                                }
                            } else {
                                toastr.error(response.data.msg);
                            }
                        })
                        .catch(function(error) {                            
                            toastr.error(error);
                        });
                }
            });

            //show aleart before page reloading
            var form_obj = $('form#show_form');
            var orig_forn_data = form_obj.serialize();
            window.onbeforeunload = function() {
                if($('form#show_form').length == 1){
                    if (form_obj.serialize() != orig_forn_data) {
                        return 'Are you sure?';
                    }
                }
            }
        },
        created() {

            this.$eventBus.$on('callApplyConditions', (data) => {
                this.applyConditions();
            });

            this.form_parsed = JSON.parse(this.form);
            this.schema = this.form_parsed.schema.form;
            this.settings = this.form_parsed.schema.settings;
            this.conditional_fields = this.form_parsed.schema.conditional_fields;
            this.form_custom_attributes = this.form_parsed.schema.form_attributes;
            if (!_.isEmpty(this.form_parsed.data)) {
                this.submitted_data = this.form_parsed.data[0].data;
                this.token = this.form_parsed.data[0].token;
                this.form_data_id = this.form_parsed.data[0].id;
            }
        },
        beforeDestroy() {
            this.$eventBus.$off('callApplyConditions');
        },
        methods: {
            applyConditions(){

                var schema = this.schema;
                var is_condition_satisfied = true;

                _.forEach(this.conditional_fields, function(conditional_field) {

                    _.forEach(conditional_field.conditions, function(element) {
                        
                        if (!_.isNull(element.condition)) {

                            //find element as condition is present & if present check its type and find it value
                           var index = schema.findIndex(field => field.name === element.condition);
                           var form_element_value = '';
                            if (schema[index].type === 'radio') {

                               form_element_value = $('input[name='+element.condition+']:checked').val();

                            } else if (schema[index].type === 'checkbox') {

                                var checked_value = [];

                                $('input[name='+'"'+element.condition+'[]"'+']:checked').each(function() {
                                    checked_value.push($(this).val());
                                });

                                if (_.includes(checked_value, element.value)) {
                                    form_element_value = element.value;
                                }

                            } else if (schema[index].type === 'dropdown' && schema[index].multiselect) {

                                var selected_value = $('select[name='+'"'+element.condition+'[]"'+']').val();

                                if (_.includes(selected_value, element.value)) {
                                    form_element_value = element.value;
                                }

                            } else if(schema[index].type === 'calendar') {
                                form_element_value = $('input[name='+element.condition+']').val();
                            } else if(schema[index].type === 'terms_and_condition') {
                                form_element_value = $('input[name='+element.condition+']').is(':checked') ? 'true' : 'false';
                            } else if(schema[index].type === 'switch') {
                                form_element_value = $('input[name='+element.condition+']').is(':checked') ? '1' : '0';
                            } else {
                                form_element_value = document.getElementById(element.condition).value;
                            }
                            
                            //check if condition_satisfied or not
                            if (element.operator == '==') {

                                if (form_element_value == element.value) {
                                    is_condition_satisfied = true && is_condition_satisfied;
                                } else {
                                    is_condition_satisfied = false;
                                }

                            } else if (element.operator == '!=') {

                                if (form_element_value != element.value) {
                                    is_condition_satisfied = true && is_condition_satisfied;
                                } else {
                                    is_condition_satisfied = false;
                                }

                            } else if (element.operator == 'empty') {

                                if (form_element_value.length == 0) {
                                    is_condition_satisfied = true && is_condition_satisfied;
                                } else {
                                    is_condition_satisfied = false;
                                }

                            } else if (element.operator == 'not_empty') {
                                
                                if (form_element_value.length > 0) {
                                    is_condition_satisfied = true && is_condition_satisfied;
                                } else {
                                    is_condition_satisfied = false;
                                }
                            }
                        }

                    });
                    
                    //check if element is exist or not in schema
                    var index = schema.findIndex(element => element.name === conditional_field.element);

                    //if element exist then toggle conditional class
                    if (index !== -1) {
                        if (is_condition_satisfied) {
                            schema[index].conditional_class = conditional_field.action;
                        } else {
                            schema[index].conditional_class = (conditional_field.action === 'show') ? 'hide' : 'show'
                        }

                        is_condition_satisfied = true;
                    }
                    
                });
            }
        }
    }
</script>