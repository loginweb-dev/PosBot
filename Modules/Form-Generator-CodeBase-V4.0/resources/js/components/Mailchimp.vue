<template>
	<div class="mt-3">
		<div class="custom-control custom-checkbox">
			<input type="checkbox" 
				class="custom-control-input"
				id="mailchimp_is_enable"
				v-model="details.is_enable"
				value="1">
			<label class="custom-control-label" for="mailchimp_is_enable">
				{{trans('messages.enable')}}
			</label>
		</div>

		<div v-if="details.is_enable">

		<div class="text-left text-muted">
			<small>{{trans('messages.not_in_downloaded_code')}}</small><br/>
		</div>
		<div class="form-group row">
			<label for="site_key" class="col-sm-3 col-form-label">{{trans('messages.mailchimp_api_key')}}:
			<span class="error">*</span></label>
			<div class="col-sm-9">
				<input type="text" class="form-control" 
					id="mailchimp_api_key" 
					:placeholder="trans('messages.mailchimp_api_key')" 
					name="details.api_key" 
					:required="details.is_enable"
					v-model="details.api_key">
				<small class="form-text text-muted" v-html="trans('messages.mailchimp_api_key_help')"></small>
			</div>
		</div>

		<div class="form-group row">
			<label for="site_key" class="col-sm-3 col-form-label">{{trans('messages.mailchimp_list_id')}}: 
			<span class="error">*</span></label>
			<div class="col-sm-9">
				<input type="text" class="form-control" 
					id="mailchimp_list_id" 
					:placeholder="trans('messages.mailchimp_list_id')" 
					name="details.list_id" 
					:required="details.is_enable"
					v-model="details.list_id">
				<small class="form-text text-muted" v-html="trans('messages.mailchimp_list_id_help')"></small>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-3 col-form-label">
				{{trans('messages.mailchimp_subscription_status')}}
				<span class="error">*</span>
			</label>
			<div class="col-sm-9">
				<select class="form-control"
					:required="details.is_enable"
					name="details.status"
					v-model="details.status">
					<option value="subscribe_pending">{{trans('messages.mailchimp_subscribe_confirm')}}</option>
					<option value="subscribe">{{trans('messages.mailchimp_subscribe')}}</option>
				</select>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-3 col-form-label">
				{{trans('messages.email')}}
				<span class="error">*</span>
			</label>
			<div class="col-sm-9">
				<select class="form-control"
					:required="details.is_enable"
					name="details.email_field"
					v-model="details.email_field">
					<option :value="element.name" 
						v-if="_.includes(['text', 'email'], element.type)"
						v-for="element in selected_elements">{{element.label}}</option>
				</select>
				<small class="form-text text-muted">{{trans('messages.mailchimp_email_help')}}</small>
			</div>
		</div>

		<div class="form-group row">
			<label class="col-sm-3 col-form-label">
				{{trans('messages.name')}}
			</label>
			<div class="col-sm-9">
				<select class="form-control"
					name="details.name_field"
					v-model="details.name_field">
					<option :value="element.name" 
						v-if="_.includes(['text', 'email'], element.type)"
						v-for="element in selected_elements">{{element.label}}</option>
				</select>
				<small class="form-text text-muted">{{trans('messages.mailchimp_name_help')}}</small>
			</div>
		</div>

		</div>
	</div>
</template>
<script>
	export default {
		props: ['details', 'selected_elements'],
	}
</script>