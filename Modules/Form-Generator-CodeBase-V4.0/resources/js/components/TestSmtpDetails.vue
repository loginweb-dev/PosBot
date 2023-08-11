<template>
	<span>
		<button type="button" 
			class="btn btn-sm btn-outline-primary ladda-button btn-test-smtp"
			data-style="expand-right"
			data-spinner-color="blue"
			@click="testSmtp()">
			<span class="ladda-label">
				{{trans('messages.test_smtp_details')}}
			</span>
		</button>
		<small class="form-text text-muted">
			{{trans('messages.email_will_be_sent_to')}} {{this.details.from_address}}
		</small>
	</span>
</template>

<script>
	export default {
		props: ['details'],
		methods:{
			testSmtp(){

				var isValid = true;
				_.forEach(this.details, function(detail){
					
					if (!_.isNull(detail)) {
						isValid = true && isValid;
					} else {
						isValid = false;
					}
				});

				if (isValid) {
					var ladda = Ladda.create($('.btn-test-smtp')[0]);
	                ladda.start();
	    			axios.get('/test-smtp', {
	    				params:{
	    					host: this.details.host,
						    port: this.details.port,
						    from_name: this.details.from_name,
						    from_address: this.details.from_address,
						    encryption: this.details.encryption,
						    username: this.details.username,
						    password: this.details.password
	    				}
					})
					.then(function (response) {
						ladda.stop();
						alert(response.data.msg);
					})
					.catch(function (error) {
						ladda.stop();
						alert(error);					
					});
				} else {
					isValid = true;
					alert('Please fill all the SMTP details.');
				}
    		}
		}
	}
</script>