<template>
<div class="container-fluid mt-3 mb-85">
	<div class="text-left text-muted pl-3">
		<i class="fas fa-info-circle mr-1"></i>
		<small>{{trans('messages.not_in_downloaded_code')}}</small><br/>
	</div>
	<template v-for="(conditionalField, index) in conditionalFields">
		<div class="card m-3 card-outline card-success">
			<div class="card-body">
				<div class="row">
					<div class="col-md-5">
						<div class="input-group">
							<select class="form-control" v-model="conditionalField.action">
								<option :value="''">
									{{trans('messages.show_hide')}}
								</option>
								<option :value="'show'">
									{{trans('messages.show')}}
								</option>
								<option :value="'hide'">
									{{trans('messages.hide')}}
								</option>
							</select>
							<select class="form-control" v-model="conditionalField.element">
								<option :value="''">
									{{trans('messages.choose_element')}}
								</option>
								<option v-for="(element, index) in selectedElements"
									:key="index"
									:value="element.name"
									v-if="!_.includes(['heading', 'hr', 'html_text'], element.type)">
										{{element.label}}
								</option>
							</select>
						</div>
					</div>
					<div class="col-md-6">
						<template v-for="(conditions, conditionIndex) in conditionalField.conditions">
							<div class="row">
								<div class="col-md-5">
									<div class="input-group mb-2">
										<div class="input-group-prepend">
											<span class="input-group-text" v-if="conditionIndex == 0">
												{{trans('messages.when')}}
											</span>
											<span class="input-group-text bg-info text-white" v-if="conditionIndex != 0">
												{{trans('messages.and')}}
											</span>
										</div>
										<select class="form-control" v-model="conditions.condition"
										@change="toggleConditionValueField(conditions.condition)">
											<option :value="''">
												{{trans('messages.condition')}}
											</option>
											<option v-for="(element, index) in selectedElements"
												:key="index"
												:value="element.name"
												v-if="!_.includes(['heading', 'hr', 'html_text', 'file_upload', 'text_editor', 'signature'], element.type)">
													{{element.label}}
											</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">

									<div class="input-group mb-2" v-if="_.includes(['text', 'textarea', 'range', 'calendar', 'rating'], conditions.element_type)">
										<div class="input-group-prepend">
											<select class="form-control input-group-text" v-model="conditions.operator">
												<option value="==">
													{{trans('messages.equal_to')}}
												</option>
												<option value="!=">
													{{trans('messages.not_equal_to')}}
												</option>
												<option value="empty">
													{{trans('messages.empty')}}
												</option>
												<option value="not_empty">
													{{trans('messages.not_empty')}}
												</option>
											</select>
										</div>
										<input type="text" class="form-control"
										:placeholder="trans('messages.write_conditional_value')"
										v-model="conditions.value" :disabled="_.includes(['empty', 'not_empty'], conditions.operator)">
									</div>

									<div class="input-group mb-2" v-if="_.includes(['dropdown', 'radio', 'checkbox'], conditions.element_type)">
										<div class="input-group-prepend">
											<select class="form-control input-group-text" v-model="conditions.operator">
												<option value="==">
													{{trans('messages.equal_to')}}
												</option>
												<option value="!=">
													{{trans('messages.not_equal_to')}}
												</option>
												<option value="empty" v-if="!_.includes(['radio', 'checkbox'], conditions.element_type)">
													{{trans('messages.empty')}}
												</option>
												<option value="not_empty" v-if="!_.includes(['radio', 'checkbox'], conditions.element_type)">
													{{trans('messages.not_empty')}}
												</option>
											</select>
										</div>
										<select class="form-control" v-model="conditions.value" :disabled="_.includes(['empty', 'not_empty'], conditions.operator)">
											<option :value="''">
												{{trans('messages.choose_value')}}
											</option>
											<option v-for="(option, index) in optionsForConditionalValue(selectedElements[conditions.element_index].options)"
											:value="option"
											:key="index"
											>
												{{option}}
											</option>
										</select>
									</div>

									<div class="input-group mb-2" v-if="_.includes(['terms_and_condition'], conditions.element_type)">
										<div class="input-group-prepend">
											<select class="form-control input-group-text" v-model="conditions.operator">
												<option value="==">
													{{trans('messages.equal_to')}}
												</option>
											</select>
										</div>
										<select class="form-control" v-model="conditions.value">
											<option :value="''">
												{{trans('messages.choose_value')}}
											</option>
											<option value="true">
												{{trans('messages.checked')}}
											</option>
											<option value="false">
												{{trans('messages.unchecked')}}
											</option>
										</select>
									</div>
									<div class="input-group mb-2" v-if="_.includes(['switch'], conditions.element_type)">
										<div class="input-group-prepend">
											<select class="form-control input-group-text" v-model="conditions.operator">
												<option value="==">
													{{trans('messages.equal_to')}}
												</option>
												<option value="!=">
													{{trans('messages.not_equal_to')}}
												</option>
											</select>
										</div>
										<select class="form-control" v-model="conditions.value">
											<option :value="''">
												{{trans('messages.choose_value')}}
											</option>
											<option value="1">
												{{trans('messages.on')}}
											</option>
											<option value="0">
												{{trans('messages.off')}}
											</option>
										</select>
									</div>
								</div>
								<div class="col-md-1">
									<button type="button" class="btn btn-primary btn-sm" @click="addCondition(index)" v-if="conditionIndex == 0" data-toggle="tooltip" data-placement="top" :title="trans('messages.add_condition')">
										<i class="fas fa-plus-circle"></i>
									</button>
									<button type="button" class="btn btn-danger btn-sm" @click="removeCondition(index,conditionIndex)" v-if="conditionIndex != 0">
										<i class="fas fa-trash-alt"></i>
									</button>
								</div>
							</div>
						</template>
					</div>
					<div class="col-md-1">
						<button type="button" class="btn btn-danger btn-sm" @click="removeConditionalField(index)">
							<i class="fas fa-trash-alt"></i>
						</button>
					</div>
				</div>
			</div>
		</div>
	</template>
	<button type="button" class="btn btn-info float-right mt-3" @click="addMoreConditionalField" data-toggle="tooltip" data-placement="top" :title="trans('messages.add_conditional_field')">
		<i class="fas fa-plus-circle"></i>
		{{trans('messages.add')}}
	</button>
</div>
</template>
<script>
	export default {
		props: ['conditionalFields', 'selectedElements'],
		methods:{
			addCondition(index) {
				var condition_field = {'condition':'', 'value':"", 'element_type' : 'text', 'element_index' : '', 'operator' : '=='};
				this.conditionalFields[index].conditions.push(condition_field);
			},
			addMoreConditionalField() {
				var conditional_field = {'action':'', 'element':'', 'conditions':[{'condition':'', 'value':"", 'element_type' : 'text', 'element_index' : '', 'operator' : '=='}]};
				this.conditionalFields.push(conditional_field);
			},
			removeCondition(index, conditionalIndex) {
				if (conditionalIndex != 0) {
	                this.conditionalFields[index].conditions.splice(conditionalIndex, 1);
	            }
			},
			removeConditionalField(index) {
	            this.conditionalFields.splice(index, 1);
			},
			toggleConditionValueField(choosenCondition) {
				
				var schema = this.selectedElements;

				_.forEach(this.conditionalFields, function(conditionalField){
					
					_.forEach(conditionalField.conditions, function(conditionalElement) {

						if (conditionalElement.condition === choosenCondition) {

							var index = schema.findIndex(element => element.name === choosenCondition);
							
							conditionalElement.element_type = _.isUndefined(schema[index]) ? 'text' : schema[index].type;
							conditionalElement.element_index = index;
						}
					});
				});
			},
			optionsForConditionalValue(options) {
				return (options || "").split("\n");
			}
		}
	}
</script>