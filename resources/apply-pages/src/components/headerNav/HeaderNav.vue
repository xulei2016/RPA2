<template>
	<div style="height:50px;">
		<!-- left-arrow -->
		<van-nav-bar :title="title" left-text=""  :z-index="999" @click-left="onBack">
		</van-nav-bar>
	</div>
</template>

<script>
	import {
		NavBar
	} from 'vant';
	export default {
		name: 'HeaderNav',
		components: {
			[NavBar.name]: NavBar,
		},
		props: {
			title: String,
		},
		methods: {
			onBack() {
				//history.back();
			}
		}
	}
</script>

<style>
	.van-nav-bar {
		background-color: #A72F23;
		width: 100%;
		position: fixed;
		z-index: 999;
		top: 0
	}

	.van-nav-bar__title {
		color: #fff;
	}

	.van-icon-arrow-left:before {
		content: "\F008";
		color: #fff;
	}

	.van-icon-user-o:before {
		content: "\F0CC";
		color: #fff;
		font-size: 0.6rem;
	}
</style>
