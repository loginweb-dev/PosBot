<template>
	<span class="text-success">
		<i class="fas fa-info-circle ml-1" data-toggle="tooltip" :title="tooltip"></i>
	</span>
</template>
<script>
	export default {
		props:['tooltip'],
	}
</script>