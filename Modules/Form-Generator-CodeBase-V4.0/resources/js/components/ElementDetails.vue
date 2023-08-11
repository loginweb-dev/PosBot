<template>
	<!-- modal -->
    <div class="modal fade" id="elementDetailsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">
                        {{trans('messages.element_details')}}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <b>{{trans('messages.input')}}:</b><br>
                    <div v-html="getTooltips('text')"></div><hr>

                    <b>{{trans('messages.textarea')}}:</b><br>
                    <div v-html="getTooltips('textarea')"></div><hr>

                    <b>{{trans('messages.dropdown')}}:</b><br>
                    <div v-html="getTooltips('dropdown')"></div><hr>

                    <b>{{trans('messages.radio')}}:</b><br>
                    <div v-html="getTooltips('radio')"></div><hr>

                    <b>{{trans('messages.checkbox')}}:</b><br>
                    <div v-html="getTooltips('checkbox')"></div><hr>

                    <b>{{trans('messages.heading_paragrahp')}}:</b><br>
                    <div v-html="getTooltips('heading')"></div><hr>

                    <b>{{trans('messages.range')}}:</b><br>
                    <div v-html="getTooltips('range')"></div><hr>

                    <b>{{trans('messages.datetime')}}:</b><br>
                    <div v-html="getTooltips('calendar')"></div><hr>

                    <b>{{trans('messages.file_upload')}}:</b><br>
                    <div v-html="getTooltips('file_upload')"></div><hr>

                    <b>{{trans('messages.text_editor')}}:</b><br>
                    <div v-html="getTooltips('text_editor')"></div> <hr>

                    <b>{{trans('messages.terms_condition')}}:</b><br>
                    <div v-html="getTooltips('terms_and_condition')"></div><hr>

                    <b>{{trans('messages.horizontal_line')}}:</b><br>
                    <div v-html="getTooltips('hr')"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        {{trans('messages.close')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- /modal -->
</template>
<script>
	export default {
		methods:{
			getTooltips(element_type) {

                var validation_rules = validationRules;
                var rules = [];
                var title = '';
                _.forEach(validation_rules, function(validation) {
                    if (_.includes(validation.applies_to, element_type)) {
                        rules.push(validation.display);
                    }
                });

                if (element_type == 'text') {
                    title = `
                             <span class="text-success">
                               <i>Usage:</i>
                              </span> To enter short information. <br>
                             <span class="text-success">
                                <i>Example:</i>
                             </span> Name, Email, Phone.<br>
                             <span class="text-success">
                                <i>Contains:</i>
                             </span> Is required,  
                             `+ rules.join(', ');
                } else if (element_type == 'textarea') {
                    title = `
                            <span class="text-success">
                                <i>Usage:</i>
                            </span> To enter long information.<br>
                            <span class="text-success">
                                <i>Example:</i>
                            </span> Address, About me, Description.<br>
                            <span class="text-success">
                                <i>Contains:</i>
                            </span> Is required, 
                            `+ rules.join(', ');
                } else if (element_type == 'dropdown') {
                    title = `
                            <span class="text-success">
                                <i>Usage:</i>
                            </span> To select Single or Multiple pre-defined values. <br>
                            <span class="text-success">
                                <i>Example:</i>
                            </span> Hobbies, Model <br>
                            <span class="text-success">
                                <i>Contains:</i>
                            </span> Is required, `+ rules.join(', ');
                } else if (element_type == 'radio') {
                    title = `
                            <span class="text-success">
                                <i>Usage:</i>
                            </span> To select ONE of a limited number of choices <br>
                            <span class="text-success">
                                <i>Example:</i>
                            </span> Gender, True-False <br>
                            <span class="text-success">
                                <i>Contains:</i>
                            </span> Is required.`;
                } else if (element_type == 'checkbox') {
                    title = `
                            <span class="text-success">
                                <i>Usage:</i>
                            </span> select ZERO or MORE options of a limited number of choices <br>
                            <span class="text-success">
                                <i>Example:</i>
                            </span> Hobbies, Qualification <br>
                            <span class="text-success">
                                <i>Contains:</i>
                            </span>Is required, `+ rules.join(', ');
                } else if(element_type == 'heading') {
                    title = `
                            <span class="text-success">
                                <i>Usage:</i>
                            </span> For heading or any type of texts with H1 to H6 & Paragraph tags with your defined color. <br>
                            <span class="text-success">
                                <i>Example:</i>
                            </span> Description & other informations.`;
                } else if(element_type == 'range') {
                    title = `
                            <span class="text-success">
                                <i>Usage:</i>
                            </span> To Select Minimum-maximum range value.<br>
                            <span class="text-success">
                                <i>Example:</i>
                            </span>
                                Price range`;
                } else if(element_type == 'calendar') {
                    title = `
                            <span class="text-success">
                                <i>Usage:</i>
                            </span> To pick a date and/or time, with your defined format.<br>
                            <span class="text-success">
                                <i>Example:</i>
                            </span> Appointment date <br>
                            <span class="text-success">
                                <i>Contains:</i>
                            </span> Is required.`;
                } else if(element_type == 'file_upload') {
                    title = `
                            <span class="text-success">
                                <i>Usage:</i>
                            </span> To upload file(s) and/or images.<br>
                            <span class="text-success">
                                <i>Example:</i>
                            </span> Documents, Photo <br>
                            <span class="text-success">
                                <i>Contains:</i>
                            </span> Is required.`;
                } else if(element_type == 'text_editor') {
                    title = `
                            <span class="text-success">
                                <i>Usage:</i>
                            </span> To enter long information with the many options to format<br>
                            <span class="text-success">
                                <i>Example:</i>
                            </span>
                            Description, blogs <br>
                            <span class="text-success">
                                <i>Contains:</i>
                            </span> Is required.`;
                } else if (element_type == 'terms_and_condition') {
                    title = `
                            <span class="text-success">
                                <i>Usage:</i>
                            </span>  Rules by which one must agree to abide in order to use a service<br>
                            <span class="text-success">
                                <i>Example:</i>
                            </span>
                            Privacy Policy<br>
                            <span class="text-success">
                                <i>Contains:</i>
                            </span> Is required.`;
                } else if (element_type == 'hr') {
                    title = `
                            <span class="text-success">
                                <i>Usage:</i>
                            </span>  This tag defines a thematic break in an HTML page.<br>
                            <span class="text-success">
                                <i>Example:</i>
                            </span>
                                a shift of topic<br>
                            `;
                }

                return title;
            }
		}
	}
</script>