<template>
	<div class="mt-3">
		<p>
			{{trans('messages.additional_js_css_help_text')}}
		</p>
		<br>
		<div class="form-group row">
			<label class="col-sm-2">
				{{trans('messages.javascript')}}
				<i class="fa fa-info-circle" data-html="true" data-toggle="tooltip" :title="trans('messages.additional_js_help')"></i>
			</label>
			<div class="col-sm-10">
				<textarea class="form-control" rows="4" :placeholder="trans('messages.additional_js')" v-model="additionalData.js">
				</textarea>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-sm-2">
				{{trans('messages.css')}}
				<i class="fa fa-info-circle" data-html="true" data-toggle="tooltip" :title="trans('messages.additional_css_help')"></i>
			</label>
			<div class="col-sm-10">
				<textarea class="form-control" rows="4" :placeholder="trans('messages.additional_css')"
					v-model="additionalData.css">
				</textarea>
			</div>
		</div>
	</div>
</template>
<script>
	export default {
		props: ['additionalData'],
	}
</script>