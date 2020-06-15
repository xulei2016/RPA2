<template>
	<div id="app">
		<router-view></router-view>
	</div>
</template>

<script>
	export default {
		name: 'App',
		data() {
			return {
				username: '',
				password: ''
			}
		},
		methods: {
			onSubmit(values) {
				console.log('submit', values);
			},
		}
	}
</script>

<style>
	#app {
		/* 		font-family: Avenir, Helvetica, Arial, sans-serif;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		text-align: center;
		color: #2c3e50;
		margin-top: 60px; */
	}

	.my-btn{
		background-color: #A72F23;
		border-color: #A72F23;
	}
</style>
