<template>
<!-- Modal -->
<div class="modal fade" id="AskUserChoice" tabindex="-1" role="dialog" aria-labelledby="AskUserChoiceModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title" id="AskUserChoiceModalLabel">
					<span v-html="trans('messages.what_do_u_want_next')"></span>
				</h6>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-4">
						<button type="button" class="btn btn-primary btn-sm"@click="getUserChoice('edit')">
							<i class="fa fa-edit"></i>
					        {{trans('messages.continue_editing')}}
					    </button>
					</div>
					
					<div class="col-md-4">
						<button type="button" class="btn btn-info btn-sm"@click="getUserChoice('preview')">
							<i class="fa fa-eye"></i>
					        {{trans('messages.preview_the_form')}}
					    </button>
					</div>

					<div class="col-md-4">
						<button type="button" class="btn btn-success btn-sm" @click="getUserChoice('home')">
							<i class="fas fa-home"></i>
					        {{trans('messages.go_to_home')}}
					    </button>
					</div>
				</div>
			</div>
			<div class="modal-footer"></div>
		</div>
	</div>
</div>
<!-- /modal -->
</template>
<script>
	export default {
		methods: {
			getUserChoice(choice){
				this.$eventBus.$emit('callRedirectUser', choice);
			}
		}
	}
</script>