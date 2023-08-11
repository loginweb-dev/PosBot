<template>
<div class="mt-3">
	<!-- reCAPTCHA settings -->
	<h5 class="text-primary">
		{{trans('messages.google_recaptcha_settings')}}:
	</h5>
	<div class="form-group">
		<div class="col-sm offset-3 col-sm-9">
			<div class="custom-control custom-checkbox">
				<input class="custom-control-input" type="checkbox" value="1" id="enable_recaptcha" name="enable_recaptcha" v-model="settings.recaptcha.is_enable">
				<label class="custom-control-label" for="enable_recaptcha">
					{{trans('messages.add_google_recaptcha')}}
				</label>
			</div>
		</div> <br>

		<div class="form-group row" v-if="settings.recaptcha.is_enable">
			<label for="site_key" class="col-sm-3 col-form-label">
				{{trans('messages.site_key')}}
			<span class="error">*</span></label>
			<div class="col-sm-9">
				<input type="text" class="form-control" id="site_key" :placeholder="trans('messages.enter_site_key')" 
					name="settings.recaptcha.site_key" 
					:required="settings.recaptcha.is_enable"
					v-model="settings.recaptcha.site_key">
			</div>
		</div>

		<div class="form-group row" v-if="settings.recaptcha.is_enable">
			<label for="secret_key" class="col-sm-3 col-form-label">
			{{trans('messages.secret_key')}}<span class="error">*</span></label>
			<div class="col-sm-9">
				<input type="text" name="settings.recaptcha.secret_key" class="form-control" id="secret_key" :placeholder="trans('messages.enter_secret_key')" v-model="settings.recaptcha.secret_key" 
				:required="settings.recaptcha.is_enable">
				<small id="help_text" class="form-text text-muted">
				  {{trans('messages.secret_key_help_text')}}
	      			<a href="https://www.google.com/recaptcha/admin" target="_blank">{{trans('messages.click_here')}}</a> {{trans('messages.to_create')}}
				</small>
			</div>
		</div>
	</div>
	<!-- Design settings -->
	<h5 class="text-primary">{{trans('messages.design_settings')}}:</h5>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">
			{{trans('messages.theme')}}
		</label>
		<div class="col-sm-9">
			<select class="custom-select" aria-describedby="theme" v-model="settings.theme">
		      <option v-for="(theme, index) in themes" :value="theme.key" :key="index">
		      		{{theme.value}}
		      </option>
		    </select>
		    <small id="theme" class="form-text text-muted">
				{{trans('messages.to_see_effect_preview_form')}}
			</small>
		</div>
	</div>
	<div class="form-group row">
		<label for="label_color" class="col-sm-3 col-form-label">
			{{trans('messages.label_color')}}
		</label>
		<div class="input-group col-sm-9">
			<input type="text" class="form-control" name="label_color" id="label_color" v-model="settings.color.label">
			<div class="input-group-append">
				<span class="input-group-text">
					<input type="color" v-model="settings.color.label">
				</span>
			</div>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 col-form-label" for="error_msg_color">
			{{trans('messages.error_msg_color')}}
		</label>
		<div class="input-group col-sm-9">
			<input type="text" class="form-control" name="error_msg_color" id="error_msg_color" v-model="settings.color.error_msg">
			<div class="input-group-append">
				<span class="input-group-text">
					<input type="color" v-model="settings.color.error_msg">
				</span>
			</div>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 col-form-label" for="required_asterisk_color">
			{{trans('messages.required_asterisk_color')}}
		</label>
		<div class="input-group col-sm-9">
			<input type="text" class="form-control" name="required_asterisk_color" id="required_asterisk_color" v-model="settings.color.required_asterisk_color">
			<div class="input-group-append">
				<span class="input-group-text">
					<input type="color" v-model="settings.color.required_asterisk_color">
				</span>
			</div>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-sm-3 col-form-label">
			{{trans('messages.background_setting')}}
		</label>
		<div class="col-sm-9">
			<div class="custom-control custom-radio custom-control-inline">
				<input class="custom-control-input" name="settings.bg_settings" type="radio" id="bg_color" :value="'bg_color'" v-model="settings.background.bg_type">
				<label class="custom-control-label" for="bg_color">
					{{trans('messages.background_color')}}
				</label>
			</div>
			<div class="custom-control custom-radio custom-control-inline">
				<input class="custom-control-input" name="settings.bg_settings" type="radio" id="bg_image" :value="'bg_image'" v-model="settings.background.bg_type">
				<label class="custom-control-label" for="bg_image">
					{{trans('messages.background_image')}}
				</label>
			</div>
		</div>
	</div>

	<div class="form-group row" v-show="settings.background.bg_type === 'bg_color'">	
		<label class="col-sm-3 col col-form-label" for="background_color">
			{{trans('messages.background_color')}}
		</label>
		<div class="input-group col-sm-9">
			<input type="text" name="background_color" id="background_color" class="form-control" v-model="settings.color.background">
			<div class="input-group-append">
				<span class="input-group-text">
					<input type="color" v-model="settings.color.background">
				</span>
			</div>
		</div>
	</div>

	<div class="form-group row" v-show="settings.background.bg_type === 'bg_image'">	
		<label class="col-sm-3 col col-form-label" for="background_color">
			{{trans('messages.background_image')}}
		</label>
		<div class="col-sm-9 dropzone" id="fileUpload"></div>
	</div>
	<!-- notification settings -->
	<h5 class="text-primary">
		{{trans('messages.notification_setting')}}:
	</h5>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">
			{{trans('messages.action_after_submit')}}
		</label>
		<div class="col-sm-9">
			<div class="custom-control custom-radio custom-control-inline">
				<input class="custom-control-input" type="radio" id="notification_same_page" :value="same_page" name="post_submit_action" v-model="settings.notification.post_submit_action" checked>
				<label class="custom-control-label" for="notification_same_page">
					{{trans('messages.show_notification_in_same_page')}}
				</label>
			</div>
			<div class="custom-control custom-radio custom-control-inline">
				<input class="custom-control-input" name="post_submit_action" type="radio" id="notification_redirect" :value="redirect" v-model="settings.notification.post_submit_action">
				<label class="custom-control-label" for="notification_redirect">
					{{trans('messages.redirect_to_thank_you_page')}}
				</label>
			</div>
		</div>
	</div>

	<div class="form-group row" v-show="settings.notification.post_submit_action === same_page">
		<label for="success_msg" class="col-sm-3 col-form-label">
			{{trans('messages.email_success_notification')}}
		</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" id="success_msg" :placeholder="trans('messages.enter_msg')" name="success_msg" v-model="settings.notification.success_msg">
		</div>
	</div>

	<div class="form-group row" v-show="settings.notification.post_submit_action === same_page">
		<label for="failed_msg" class="col-sm-3 col-form-label">
			{{trans('messages.email_failed_notification')}}
		</label>
		<div class="col-sm-9">
			<input type="text" class="form-control" 
				id="failed_msg" :placeholder="trans('messages.enter_msg')"
				name="failed_msg" v-model="settings.notification.failed_msg">
		</div>
	</div>

	<div class="form-group row" v-show="settings.notification.post_submit_action === same_page">
		<label for="position" class="col-sm-3 col-form-label">
			{{trans('messages.display_position')}}
		</label>
		<div class="col-sm-9">
			<select name="position" class="form-control" v-model="settings.notification.position">
				<option value="toast-top-right">
					{{trans('messages.top_right')}}
				</option>
				<option value="toast-bottom-right">
					{{trans('messages.bottom_right')}}
				</option>
				<option value="toast-bottom-left">
					{{trans('messages.bottom_left')}}
				</option>
				<option value="toast-top-left">
					{{trans('messages.top_left')}}
				</option>
				<option value="toast-top-center">
					{{trans('messages.top_center')}}
				</option>
				<option value="toast-bottom-center">
					{{trans('messages.bottom_center')}}
				</option>
			</select>
		</div>
	</div>

	<div class="form-group row" v-show="settings.notification.post_submit_action === redirect">
		<label for="redirect_url" class="col-sm-3 col-form-label">
			{{trans('messages.redirect_url')}}<span class="error">*</span>
		</label>
		<div class="col-sm-9">
			<input type="text" 
				class="form-control" 
				id="redirect_url" 
				:placeholder="trans('messages.enter_redirect_url')"
				name="settings.notification.redirect_url" 
				:required="settings.notification.post_submit_action === redirect"
				data-rule-url="true"
				v-model="settings.notification.redirect_url">
		</div>
	</div>

	<!-- submit button settings -->
	<h5 class="text-primary">
		{{trans('messages.submit_btn_setting')}}
	</h5>
	<div class="form-group row">
		<label for="btn_style"  class="col-sm-3 col-form-label">
	        {{trans('messages.submit_btn_style')}}
	    </label>
	    <div class="col-sm-9">
		    <label class="btn btn-primary">
				<input type="radio" name="btn_style" :value="'default'" v-model="settings.submit.btn_style"> {{trans('messages.default')}}
			</label>
			<label class="btn btn-outline-primary">
				<input type="radio" name="btn_style" :value="'outline'" v-model="settings.submit.btn_style"> {{trans('messages.outline')}}
			</label>
		</div>
	</div>

	<div class="form-group row">
		<label for="sb_btn_size" class="col-sm-3 col-form-label">
			{{trans('messages.submit_btn_size')}}
		</label>
		<div class="col-sm-6">
			<label class="btn btn-lg btn-primary">
				<input type="radio" name="btn_size" :value="btn_large" v-model="settings.submit.btn_size"> {{trans('messages.large')}}
			</label>
			<label class="btn btn-primary">
				<input type="radio" name="btn_size" :value="btn_default" v-model="settings.submit.btn_size"> {{trans('messages.medium')}}
			</label>
			<label class="btn btn-primary btn-sm" aria-describedby="sb_btn_size">
				<input type="radio" name="btn_size" :value="btn_sm" v-model="settings.submit.btn_size"> {{trans('messages.small')}}
			</label>
			<small id="sb_btn_size" class="form-text text-muted">
			  {{trans('messages.submit_btn_size_help_text')}}
			</small>
		</div>
	</div>

	<div class="form-group row">
		<label for="sb_btn_color" class="col-sm-3 col-form-label">
			{{trans('messages.submit_btn_color')}}
		</label>
		<div class="col-sm-6" v-show="settings.submit.btn_style == 'default'">
			<label class="btn">
				<input type="radio" name="sb_btn_color" :value="default_value" v-model="settings.submit.btn_color"> {{trans('messages.default')}}
			</label>
			<label class="btn btn-primary">
				<input type="radio" name="sb_btn_color" :value="primary" v-model="settings.submit.btn_color"> {{trans('messages.primary')}}
			</label>
			<label class="btn btn-success">
				<input type="radio" name="sb_btn_color" :value="success" v-model="settings.submit.btn_color"> {{trans('messages.success')}}
			</label>
			<label class="btn btn-warning">
				<input type="radio" name="sb_btn_color" :value="warning" v-model="settings.submit.btn_color"> {{trans('messages.warning')}}
			</label>
			<label class="btn btn-danger">
				<input type="radio" name="sb_btn_color" :value="danger" v-model="settings.submit.btn_color" aria-describedby="sb_btn_color"> {{trans('messages.danger')}}
			</label>
			<small id="sb_btn_color" class="form-text text-muted">
			  {{trans('messages.submit_btn_color_help_text')}}
			</small>
		</div>
		<div class="col-sm-6" v-show="settings.submit.btn_style == 'outline'">
			<label class="btn">
				<input type="radio" name="sb_btn_color" :value="default_value" v-model="settings.submit.btn_color"> {{trans('messages.default')}}
			</label>
			<label class="btn btn-outline-primary">
				<input type="radio" name="sb_btn_color" :value="'btn-outline-primary'" v-model="settings.submit.btn_color"> {{trans('messages.primary')}}
			</label>
			<label class="btn btn-outline-success">
				<input type="radio" name="sb_btn_color" :value="'btn-outline-success'" v-model="settings.submit.btn_color"> {{trans('messages.success')}}
			</label>
			<label class="btn btn-outline-warning">
				<input type="radio" name="sb_btn_color" :value="'btn-outline-warning'" v-model="settings.submit.btn_color"> {{trans('messages.warning')}}
			</label>
			<label class="btn btn-outline-danger">
				<input type="radio" name="sb_btn_color" :value="'btn-outline-danger'" v-model="settings.submit.btn_color" aria-describedby="sb_btn_color"> {{trans('messages.danger')}}
			</label>
			<small id="sb_btn_color" class="form-text text-muted">
			  {{trans('messages.submit_btn_color_help_text')}}
			</small>
		</div>
	</div>

	<div class="form-group row">
		<label for="sb_btn_alignment" class="col-sm-3 col-form-label">
			{{trans('messages.submit_btn_alignment')}}
		</label>
		<div class="col-sm-9">
			<div class="custom-control custom-radio custom-control-inline">
				<input class="custom-control-input" type="radio" id="btn_align_left" value="float-left" name="sb_btn_alignment" v-model="settings.submit.btn_alignment">
				<label class="custom-control-label" for="btn_align_left">
					{{trans('messages.left')}}
				</label>
			</div>
			<div class="custom-control custom-radio custom-control-inline" aria-describedby="btn_align">
				<input class="custom-control-input" name="sb_btn_alignment" type="radio" id="btn_align_right" value="float-right" v-model="settings.submit.btn_alignment">
				<label class="custom-control-label" for="btn_align_right">
					{{trans('messages.right')}}
				</label>
			</div>
			<small id="btn_align" class="form-text text-muted">
				{{trans('messages.submit_btn_alignment_help_text')}}
			</small>
		</div>
	</div>

	<div class="form-group row">
		<label for="sb_text" class="col-sm-3 col-form-label">
			{{trans('messages.submit_btn_text')}}
		</label>
		<div class="col-sm-9">
			<input type="text" name="sb_text" class="form-control" :placeholder="trans('messages.enter_submit_btn_txt')" v-model="settings.submit.text">
		</div>
	</div>

	<div class="form-group row">
		<label for="sb_loading_text" class="col-sm-3 col-form-label">
			{{trans('messages.submit_btn_loading_text')}}
		</label>
		<div class="col-sm-9">
			<input type="text" name="sb_loading_text" class="form-control" :placeholder="trans('messages.enter_submit_btn_load_txt')" v-model="settings.submit.loading_text">
		</div>
	</div>

	<div class="form-group row">
		<label for="btn_icon"  class="col-sm-3 col-form-label">
	        {{trans('messages.submit_btn_icon')}}
	    </label>
	    <div class="col-sm-9">
	    	<div class="input-group">
			    <select id="btn_icon" class="form-control" v-model="settings.submit.btn_icon">
			        <option :value="'none'">None</option>
			        <option v-for="(icon, index) in getIcons()" :value="icon.c" :key="index">
			            {{icon.l}}
			        </option>
			    </select>
			    <select id="icon_position" class="form-control" v-model="settings.submit.icon_position">
			        <option :value="'left'">{{trans('messages.left')}}</option>
			        <option :value="'right'">{{trans('messages.right')}}</option>
			    </select>
			</div>
		</div>
	</div>
	<!-- form data settings -->
	<h5 class="text-primary">
		{{trans('messages.form_data_setting')}}
	</h5>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">
			{{trans('messages.column_visibility')}}
		</label>
		<div class="col-sm-9">
			<select multiple class="custom-select" aria-describedby="col_visibility" v-model="settings.form_data.col_visible">
		      <option v-for="element in selected_elements" :value="element.name" v-if="!_.includes(['terms_and_condition', 'heading', 'hr', 'html_text'], element.type)">
		      		{{element.label}}
		      </option>
		    </select>
		    <small id="col_visibility" class="form-text text-muted">
				{{trans('messages.column_visibility_help_text')}}
			</small>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">
			{{trans('messages.enable_button')}}
		</label>
		<div class="col-sm-9">
			<div class="custom-control custom-checkbox custom-control-inline">
				<input class="custom-control-input" type="checkbox" id="view" value="view" v-model="settings.form_data.btn_enabled">
  				<label class="custom-control-label" for="view">
  					{{trans('messages.view')}}
  				</label>
			</div>
			<div class="custom-control custom-checkbox custom-control-inline">
			  <input class="custom-control-input" type="checkbox" id="delete" value="delete" v-model="settings.form_data.btn_enabled">
			  <label class="custom-control-label" for="delete">
			  	{{trans('messages.delete')}}
			  </label>
			</div>
		</div>
	</div>
	<!-- submit as draft -->
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">
			{{trans('messages.form_submit_as_draft')}}
			<tooltip-component :tooltip="trans('messages.form_submit_as_draft_tooltip')">
			</tooltip-component>
		</label>
		<div class="col-sm-9">
			<div class="custom-control custom-checkbox custom-control-inline">
				<input class="custom-control-input" type="checkbox" id="is_enabled_draft_submit" value="1" v-model="settings.is_enabled_draft_submit">
  				<label class="custom-control-label" for="is_enabled_draft_submit">
  					{{trans('messages.enable')}}
  				</label>
			</div>
		</div>
	</div>
	<!-- /submit as draft -->
	<!-- form scheduling settings -->
	<h5 class="text-primary">
		{{trans('messages.form_scheduling_setting')}}
	</h5>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">
			{{trans('messages.scheduling')}}
		</label>
		<div class="col-sm-9">
			<div class="custom-control custom-checkbox custom-control-inline">
				<input class="custom-control-input" type="checkbox" id="scheduling" value="1" v-model="settings.form_scheduling.is_enabled">
  				<label class="custom-control-label" for="scheduling">
  					{{trans('messages.enable')}}
  				</label>
			</div>
		</div>
	</div>
	<div class="text-left text-muted offset-sm-3 mb-2" v-if="settings.form_scheduling.is_enabled">
		<small>{{trans('messages.not_in_downloaded_code')}}</small><br/>
	</div>
	<div class="form-group row" v-show="settings.form_scheduling.is_enabled">
		<div class="col-sm-9 offset-sm-3">
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
					    <label for="start_date_time">
					    	{{trans('messages.start_date_time')}}
					    	<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" :title="trans('messages.form_closed_start_date_time_tooltip')"></i>
					    </label>
					    <div class="input-group date" id="start_date_time" data-target-input="nearest">
						    <input type="text"
			                    class="form-control datetimepicker-input" 
			                    data-target="#start_date_time"
			                    data-toggle="datetimepicker"
			                    name="start_date_time"
			                    id="start_date_time"
			                    readonly
			                />
			            </div>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
					    <label for="end_date_time">
					    	{{trans('messages.end_date_time')}} *
					    	<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" :title="trans('messages.form_closed_end_date_time_tooltip')"></i>
					    </label>
					    <div class="input-group date" id="end_date_time" data-target-input="nearest">
						    <input type="text"
			                    class="form-control datetimepicker-input" 
			                    data-target="#end_date_time"
			                    data-toggle="datetimepicker"
			                    name="end_date_time"
			                    id="end_date_time"
			                    :required="settings.form_scheduling.is_enabled"
			                    readonly
			                />
			            </div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group">
					    <label for="closed_msg">
					    	{{trans('messages.closed_msg')}} *
					    	<i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" data-html="true" :title="trans('messages.form_closed_msg_tooltip')"></i>
					    </label>
					    <textarea
					    	class="form-control summer_note" id="closed_msg"
					    	rows="3"
					    	v-model="settings.form_scheduling.closed_msg"
					    	:required="settings.form_scheduling.is_enabled">	
					    </textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--  form submission reference-->
	<h5 class="text-primary">
		{{trans('messages.form_submission_numbering')}}
	</h5>
	<div class="form-group row">
		<label class="col-sm-3 col-form-label">
			{{trans('messages.submission_numbering')}}
		</label>
		<div class="col-sm-9">
			<div class="custom-control custom-checkbox custom-control-inline">
				<input class="custom-control-input" type="checkbox" id="submission_numbering" value="1" v-model="settings.form_submision_ref.is_enabled">
  				<label class="custom-control-label" for="submission_numbering">
  					{{trans('messages.enable')}}
  				</label>
			</div>
		</div>
	</div>

	<div class="text-left text-muted offset-sm-3 mb-2" v-if="settings.form_submision_ref.is_enabled">
		<small>{{trans('messages.not_in_downloaded_code')}}</small><br/>
	</div>
	
	<div class="form-group row" v-if="settings.form_submision_ref.is_enabled">
		<div class="col-sm-9 offset-sm-3">
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
					    <label for="prefix">
					    	{{trans('messages.prefix')}}
					    </label>
					    <input type="text" class="form-control" id="prefix"
					    :placeholder="trans('messages.prefix')" name="settings.form_submision_ref.prefix" v-model="settings.form_submision_ref.prefix">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
					    <label for="suffix">
					    	{{trans('messages.suffix')}}
					    </label>
					    <input type="text" class="form-control" id="suffix"
					    :placeholder="trans('messages.suffix')" name="settings.form_submision_ref.suffix" v-model="settings.form_submision_ref.suffix">
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
					    <label for="start_no">
					    	{{trans('messages.start_no')}} *
					    </label>
					    <input type="text" class="form-control" id="start_no"
					    :placeholder="trans('messages.start_no')" name="settings.form_submision_ref.start_no" required v-model="settings.form_submision_ref.start_no">
					</div>
				</div>
				<div class="col-sm-6">
					<div class="form-group">
					    <label for="min_digit">
					    	{{trans('messages.min_digit')}} *
					    </label>
					    <select class="form-control" required
							name="settings.form_submision_ref.min_digit"
							v-model="settings.form_submision_ref.min_digit" id="min_digit">
							<option v-for="i in 9" :key="i">
								{{i}}
							</option>
						</select>

					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- / form subission reference -->
</div>
</template>
<script>
	import tooltipComponent from './TooltipComponent';
	export default {
		components: {
			tooltipComponent
		},
		props:{
    		settings: Object,
    		selected_elements: Array
    	},
		data() {
			const self = this;
			return {
				default_value:'',
				primary:'btn-primary',
				success:'btn-success',
				warning:'btn-warning',
				danger:'btn-danger',
				btn_large:'btn-lg',
				btn_sm:'btn-sm',
				btn_default:'',
				same_page:'same_page',
				redirect:'redirect',
				dropzone: null,
				themes: [
					{
						'key' : 'default',
						'value' : self.trans('messages.default')
					},
					{
						'key' : 'sketchy',
						'value' : 'Sketchy'
					},
					{
						'key' : 'materia',
						'value' : 'Material'
					},
					{
						'key' : 'cerulean',
						'value' : 'Cerulean'
					},
					{
						'key' : 'lux',
						'value' : 'Lux'
					},
					{
						'key' : 'cosmo',
						'value' : 'Cosmo'
					},
					{
						'key' : 'lumen',
						'value' : 'Lumen'
					},
					{
						'key' : 'simplex',
						'value' : 'Simplex'
					},
					{
						'key' : 'flatly',
						'value' : 'Flatly'
					},
					{
						'key' : 'journal',
						'value' : 'Journal'
					},
					{
						'key' : 'litera',
						'value' : 'Litera'
					},
					{
						'key' : 'minty',
						'value' : 'Minty'
					},
					{
						'key' : 'pulse',
						'value' : 'Pulse'
					},
					{
						'key' : 'sandstone',
						'value' : 'Sandstone'
					},
					{
						'key' : 'spacelab',
						'value' : 'Spacelab'
					},
					{
						'key' : 'yeti',
						'value' : 'Yeti'
					},
				]
			}
		},
		mounted() {
			this.initDropzone();
		},
		created() {
			const self = this;
			$(function() {
				
				initialize_datetimepicker_for_form_scheduling();

				//set values
				$('input#start_date_time').val(self.settings.form_scheduling.start_date_time);
				$('input#end_date_time').val(self.settings.form_scheduling.end_date_time);

                //on change of dateTimePicker get values
                $('div#start_date_time').on('change.datetimepicker', function() {
                	self.settings.form_scheduling.start_date_time = $('input#start_date_time').val();
                });

                $('div#end_date_time').on('change.datetimepicker', function() {
                	self.settings.form_scheduling.end_date_time = $('input#end_date_time').val();
                });

                $('#closed_msg').summernote({
                    placeholder: self.trans('messages.jot_down_here'),
                    height: 200,
                    callbacks: {
                        onChange: function(contents, editable) {
                          self.settings.form_scheduling.closed_msg = contents;
                        }
                    }
                });
              
			});
		},
		methods:{
			initDropzone() {
	            const self = this;
	            if (self.dropzone) {
	                self.dropzone.destroy();
	            }
	            self.dropzone = new Dropzone('div#fileUpload', {
	                url: '/file-upload',
	                uploadMultiple: false,
	                maxFiles: 1,
	                acceptedFiles: '.jpeg,.jpg,.png,.gif,.JPEG,.JPG,.PNG,.GIF',
	                autoProcessQueue: true,
	                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
	                init: function() {
	                    var prevFile;
	                    this.on('addedfile', function() {
	                        if (typeof prevFile !== 'undefined') {
	                            this.removeFile(prevFile);
	                        }
	                    });
	                    this.on('success', function(file, response) {
	                        prevFile = file;
	                    });
	                },
	                success: function(file, response) {
	                    if (response.success == true) {
	                    	self.settings.color.image_path = response.path;
	                    }
	                },
	            });
	        },
	        getIcons() {
	        	var icons = this.getFontAwesomeIcons();
                return icons;
	        }
		}
	}
</script>