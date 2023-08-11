<template>
	<div class="row mt-3">
		<div class="col-md-12">
			<div class="accordion" id="accordionEmail">
				<div class="card">
					<div class="card-header bg-success" id="headingEmailSettings">
						<h2 class="mb-0">
							<button class="btn btn-link text-white" type="button" data-toggle="collapse" 
							data-target="#collapseEmail" 
							aria-expanded="true" 
							aria-controls="collapseEmail">
							{{trans('messages.new_submission_email')}}:
							</button>
						</h2>
					</div>

					<div id="collapseEmail" 
						class="collapse show"
						aria-labelledby="headingEmailSettings" 
						data-parent="#accordionEmail">

						<div class="card-body">

							<div class="custom-control custom-checkbox">
								<input type="checkbox" 
									class="custom-control-input"
									id="enable_submission_email"
									v-model="emailConfig.email.enable"
									@change="emailToggle()"
									value="1">
								<label class="custom-control-label" for="enable_submission_email">
									{{trans('messages.enable')}}
								</label>
							</div>

						<div v-if="emailConfig.email.enable">
							
				        	<div class="form-group row">
								<label class="col-sm-2 col-form-label">
									{{trans('messages.from')}}<span class="error">*</span>
								</label>

								<div class="col-sm-10">
							  		<input type="email" 
							  			class="form-control"
							  			:required="emailConfig.email.enable"
							  			:placeholder="trans('messages.from_email')"
							  			name="emailConfig.email.from"
							  			v-model="emailConfig.email.from">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 col-form-label">
									{{trans('messages.to')}}<span class="error">*</span></label>

								<div class="col-sm-10">
							  		<input type="text" 
							  			class="form-control"
							  			:required="emailConfig.email.enable"
							  			name="emailConfig.email.to"
							  			:placeholder="trans('messages.multiple_email_help_text')"
							  			v-model="emailConfig.email.to">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 col-form-label">
									{{trans('messages.cc')}}
								</label>

								<div class="col-sm-10">
							  		<input type="text" 
							  			class="form-control"
							  			:placeholder="trans('messages.multiple_email_help_text')"
							  			v-model="emailConfig.email.cc">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 col-form-label">
									{{trans('messages.bcc')}}
								</label>

								<div class="col-sm-10">
							  		<input type="text" 
							  			class="form-control"
							  			:placeholder="trans('messages.multiple_email_help_text')"
							  			v-model="emailConfig.email.bcc">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 col-form-label">
									{{trans('messages.subject')}}
									<span class="error">*</span>
								</label>

								<div class="col-sm-10">
							  		<input type="text" 
							  			class="form-control"
							  			:required="emailConfig.email.enable"
							  			name="emailConfig.email.subject"
							  			:placeholder="trans('messages.email_subject')"
							  			v-model="emailConfig.email.subject">

							  		<small class="form-text text-muted">
							  			{{trans('messages.click_to_add_tags')}}: 
							  			<button type="button" 
							  				class="btn btn-primary btn-sm xs mr-2 mt-2"
							  				v-for="(element, index) in selected_elements"
							  				v-if="!_.includes(['heading','hr', 'html_text', 'signature'], element.type)"
							  				@click="appendTag(index, 'email_subject')">{{element.label}}</button>

							  			<button type="button" 
							  				class="btn btn-primary btn-sm xs mr-2 mt-2"
							  				@click="appendTag(1, 'submission_ref_for_email_subject')">
							  					{{trans('messages.submission_numbering')}}
							  				<i class="fas fa-info-circle"  data-toggle="tooltip" data-html="true" :title="trans('messages.works_if_enabled_in_settings')"></i>
							  			</button>
							  		</small>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 col-form-label">
									{{trans('messages.body')}}: 
								<span class="error">*</span></label>

								<div class="col-sm-10">
									<textarea class="submission_email_body" 
									rows="5" 
									:required="emailConfig.email.enable"
									name="emailConfig.email.body"
									:placeholder="trans('messages.email_body')"
									v-model="emailConfig.email.body"></textarea>

									<small class="form-text text-muted">
							  			{{trans('messages.click_to_add_tags')}}:
							  			<button type="button" 
							  				class="btn btn-primary btn-sm xs mr-2 mt-2"
							  				v-for="(element, index) in selected_elements"
							  				v-if="!_.includes(['heading', 'hr', 'html_text'], element.type)"
							  				@click="appendTag(index, 'email_body')">{{element.label}}</button>

							  			<button type="button" 
							  				class="btn btn-primary btn-sm xs mr-2 mt-2"
							  				@click="appendTag(1, 'submission_ref_for_email_body')">
							  					{{trans('messages.submission_numbering')}}
							  				<i class="fas fa-info-circle"  data-toggle="tooltip" data-html="true" :title="trans('messages.works_if_enabled_in_settings')"></i>
							  			</button>
							  		</small>
								</div>
							</div>
							<div class="form-group row">
							    <label class="col-sm-2 col-form-label">
							    	{{trans('messages.pdf')}}
							    </label>
							    <div class="col-sm-10">
							      	<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" id="email_attach_pdf" value="1" name="attach_pdf" v-model="emailConfig.email.attach_pdf">
									  	<label class="custom-control-label" for="email_attach_pdf">
									  		{{trans('messages.attach_pdf')}}
									  	</label>
									  	<i class="fas fa-info-circle text-info"  data-toggle="tooltip" data-html="true" :title="trans('messages.pdf_of_submitted_data_will_be_attached_to_mail')"></i>
									</div>
							    </div>
							</div>
						</div>
				        </div>
    				</div>
  				</div>

  				<div class="card">
					<div class="card-header bg-success" id="headingEmailResponse">
						<h2 class="mb-0">
							<button class="btn btn-link text-white" type="button" data-toggle="collapse" data-target="#collapseEmailResponse" aria-expanded="true" aria-controls="collapseEmailResponse">
							{{trans('messages.auto_response_settings')}}: 
							</button>
						</h2>
					</div>

					<div id="collapseEmailResponse" class="collapse" aria-labelledby="headingEmailResponse" data-parent="#accordionEmail">
						<div class="card-body">

							<div class="custom-control custom-checkbox">
								<input type="checkbox" 
									class="custom-control-input"
									id="enable_auto_response"
									@change="replyToggle()"
									v-model="emailConfig.auto_response.is_enable"
									value="1">
								<label class="custom-control-label" for="enable_auto_response">
									{{trans('messages.enable_auto_response')}}
								</label>
							</div>

							<div v-show="emailConfig.auto_response.is_enable">
				        	<div class="form-group row">
								<label class="col-sm-2 col-form-label">
									{{trans('messages.from')}}
									<span class="error">*</span>
								</label>
								<div class="col-sm-10">
							  		<input type="email" 
							  			:required="emailConfig.auto_response.is_enable"
							  			class="form-control"
							  			:placeholder="trans('messages.from_email')"
							  			name="emailConfig.auto_response.from"
							  			v-model="emailConfig.auto_response.from">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 col-form-label">
									{{trans('messages.to')}}
									<span class="error">*</span>
								</label>

								<div class="col-sm-10">
									<select class="form-control"
										:required="emailConfig.auto_response.is_enable"
										name="emailConfig.auto_response.to"
										v-model="emailConfig.auto_response.to">
										<option :value="element.name" 
											v-if="_.includes(['text', 'email'], element.type)"
											v-for="element in selected_elements">{{element.label}}</option>
									</select>

								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 col-form-label">
									{{trans('messages.subject')}}
									 <span class="error">*</span>
								</label>

								<div class="col-sm-10">
							  		<input type="text" 
							  			:required="emailConfig.auto_response.is_enable"
							  			name="emailConfig.auto_response.is_enable"
							  			class="form-control"
							  			:placeholder="trans('messages.email_subject')"
							  			v-model="emailConfig.auto_response.subject">

							  		<small class="form-text text-muted">
							  			{{trans('messages.click_to_add_tags')}}: 
							  			<button type="button" 
							  				class="btn btn-primary btn-sm xs mr-2 mt-2"
							  				v-for="(element, index) in selected_elements"
							  				v-if="!_.includes(['heading', 'hr', 'html_text', 'signature'], element.type)"
							  				@click="appendTag(index, 'response_subject')">{{element.label}}</button>

							  			<button type="button" 
							  				class="btn btn-primary btn-sm xs mr-2 mt-2"
							  				@click="appendTag(1, 'submission_ref_for_response_subject')">
							  					{{trans('messages.submission_numbering')}}
							  				<i class="fas fa-info-circle"  data-toggle="tooltip" data-html="true" :title="trans('messages.works_if_enabled_in_settings')"></i>
							  			</button>
							  		</small>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-2 col-form-label">
									{{trans('messages.body')}}
									<span class="error">*</span>
								</label>

								<div class="col-sm-10">
									<textarea 
									class="autoresponse_email_body" 
									rows="5" 
									name="emailConfig.auto_response.body"
									:required="emailConfig.auto_response.is_enable"
									v-model="emailConfig.auto_response.body"></textarea>

									<small class="form-text text-muted">
							  			{{trans('messages.click_to_add_tags')}}: 
							  			<button type="button" 
							  				class="btn btn-primary btn-sm xs mr-2 mt-2"
							  				v-for="(element, index) in selected_elements"
							  				v-if="!_.includes(['heading', 'hr', 'html_text'], element.type)"
							  				@click="appendTag(index, 'response_body')">{{element.label}}</button>

							  			<button type="button" 
							  				class="btn btn-primary btn-sm xs mr-2 mt-2"
							  				@click="appendTag(1, 'submission_ref_for_response_body')">
							  					{{trans('messages.submission_numbering')}}
							  				<i class="fas fa-info-circle"  data-toggle="tooltip" data-html="true" :title="trans('messages.works_if_enabled_in_settings')"></i>
							  			</button>
							  		</small>
								</div>
							</div>

							<div class="form-group row">
							    <label class="col-sm-2 col-form-label">
							    	{{trans('messages.pdf')}}
							    </label>
							    <div class="col-sm-10">
							      	<div class="custom-control custom-checkbox">
										<input type="checkbox" class="custom-control-input" id="auto_response_attach_pdf" value="1" name="auto_response_attach_pdf" v-model="emailConfig.auto_response.attach_pdf">
									  	<label class="custom-control-label" for="auto_response_attach_pdf">
									  		{{trans('messages.attach_pdf')}}
									  	</label>
									  	<i class="fas fa-info-circle text-info"  data-toggle="tooltip" data-html="true" :title="trans('messages.pdf_of_submitted_data_will_be_attached_to_mail')"></i>
									</div>
							    </div>
							</div>

							</div>
				        </div>
    				</div>
  				</div>

  				<div class="card">
					<div class="card-header bg-success" id="headingSMTP">
						<h2 class="mb-0">
							<button class="btn btn-link text-white" type="button" data-toggle="collapse" data-target="#collapseSMTP" aria-expanded="true" aria-controls="collapseSMTP">
							{{trans('messages.smtp_settings')}}: 
							</button>
						</h2>
					</div>

					<div id="collapseSMTP" class="collapse" aria-labelledby="headingSMTP" data-parent="#accordionEmail">
						<div class="card-body">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" 
									class="custom-control-input" 
									id="use_system_smtp"
									v-model="emailConfig.smtp.use_system_smtp"
									value="1">
								<label class="custom-control-label" for="use_system_smtp">
									{{trans('messages.use_settings_smtp')}}
								</label>
							</div>

							<div v-show="!emailConfig.smtp.use_system_smtp">
					        	<div class="form-group row">
									<label class="col-sm-2 col-form-label">
									{{trans('messages.host')}}<span class="error">*</span>
									</label>
									<div class="col-sm-10">
								  		<input type="text" 
								  			name="emailConfig.smtp.host"
								  			:required="!emailConfig.smtp.use_system_smtp"
								  			class="form-control"
								  			v-model="emailConfig.smtp.host">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-2 col-form-label">
										{{trans('messages.port')}}
										 <span class="error">*</span>
									</label>
									<div class="col-sm-10">
								  		<input type="text" 
								  			:required="!emailConfig.smtp.use_system_smtp"
								  			class="form-control"
								  			name="emailConfig.smtp.port"
								  			v-model="emailConfig.smtp.port">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-2 col-form-label">
										{{trans('messages.from_address')}}<span class="error">*</span>
									</label>

									<div class="col-sm-10">
								  		<input type="email" 
								  			:required="!emailConfig.smtp.use_system_smtp"
								  			class="form-control"
								  			name="emailConfig.smtp.from_address"
								  			v-model="emailConfig.smtp.from_address">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-2 col-form-label">
										{{trans('messages.from_name')}}
										 <span class="error">*</span>
									</label>
									<div class="col-sm-10">
								  		<input type="text"
								  			:required="!emailConfig.smtp.use_system_smtp" 
								  			class="form-control"
								  			name="emailConfig.smtp.from_name"
								  			v-model="emailConfig.smtp.from_name">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-2 col-form-label">
										{{trans('messages.encryption')}}
									</label>
									<div class="col-sm-10">
								  		<input type="text" 
								  			class="form-control"
								  			v-model="emailConfig.smtp.encryption">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-2 col-form-label">
										{{trans('messages.username')}}<span class="error">*</span>
									</label>
									<div class="col-sm-10">
								  		<input type="text"
								  			:required="!emailConfig.smtp.use_system_smtp" 
								  			class="form-control"
								  			name="emailConfig.smtp.username"
								  			v-model="emailConfig.smtp.username">
									</div>
								</div>

								<div class="form-group row">
									<label class="col-sm-2 col-form-label">{{trans('messages.password')}}<span class="error">*</span>
									</label>
									<div class="col-sm-10">
								  		<input type="text" 
								  			class="form-control"
								  			:required="!emailConfig.smtp.use_system_smtp"
								  			name="emailConfig.smtp.password"
								  			v-model="emailConfig.smtp.password">
									</div>
								</div>

								<testSmtpDetails :details="emailConfig.smtp"></testSmtpDetails>
							</div>

				        </div>
    				</div>
  				</div>

			</div>
		</div>
	</div>
</template>
<script>
	import testSmtpDetails from "./TestSmtpDetails";

	export default {
    	props:{
    		emailConfig: Object,
    		selected_elements: Array,
    		settings: Object,
    	},
    	components: {
            testSmtpDetails
        },
    	created(){
    		this.emailToggle();
    		this.replyToggle();
    	},
    	computed: {
    		tags: function () {
      			return this.selected_elements;
    		}
    	},
    	methods: {
    		appendTag: function(index, to){
    			if(to == 'email_subject'){
    				this.emailConfig.email.subject += ' __' + this.selected_elements[index].name + '__';
    			} else if(to == 'email_body'){
    				var content = ' __' + this.selected_elements[index].name + '__';

    				this.emailConfig.email.body += content;
    				$('.submission_email_body').summernote('pasteHTML', content);
    			} else if(to == 'response_subject'){
    				this.emailConfig.auto_response.subject += ' __' + this.selected_elements[index].name + '__';
    			}  else if(to == 'response_body'){
    				var content = ' __' + this.selected_elements[index].name + '__';

    				this.emailConfig.auto_response.body += content;
    				$('.autoresponse_email_body').summernote('pasteHTML', content);
    			} else if (to == 'submission_ref_for_email_body') {
    				var content = ' __' + 'submission_ref' + '__';

    				this.emailConfig.email.body += content;
    				$('.submission_email_body').summernote('pasteHTML', content);
    			} else if(to == 'submission_ref_for_response_body'){
    				var content = ' __' + 'submission_ref' + '__';
    				this.emailConfig.auto_response.body += content;
    				$('.autoresponse_email_body').summernote('pasteHTML', content)
    			} else if(to == 'submission_ref_for_email_subject'){
    				this.emailConfig.email.subject += ' __' + 'submission_ref' + '__';
    			} else if(to == 'submission_ref_for_response_subject'){
    				this.emailConfig.auto_response.subject += ' __' + 'submission_ref' + '__';
    			}
    		},
    		emailToggle(){
    			const self = this;
    			if(this.emailConfig.email.enable){
    				setTimeout(function(){
	    				$('.submission_email_body').summernote({
	                    	height: 200,
	                    	placeholder: self.trans('messages.email_body'),
	                    	callbacks: {
		                        onChange: function(contents, editable) {
		                          self.emailConfig.email.body = contents;
		                        }
	                    	}
	                	});
    				}, 200);
    			}
    		},

    		replyToggle(){
    			const self = this;
    			if(this.emailConfig.auto_response.is_enable){
    				setTimeout(function(){
	    				$('.autoresponse_email_body').summernote({
	    					placeholder: self.trans('messages.email_body'),
                    		height: 200,
                    		callbacks: {
                        		onChange: function(contents, editable) {
                          			self.emailConfig.auto_response.body = contents;
                        		}
                    		}
                		});
    				}, 200);
    			}
    		}
    	}
    }
</script>