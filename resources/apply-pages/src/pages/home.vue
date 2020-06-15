<template>
	<div>
		<div class="box">
			<van-button class="btn" type="primary" block @click="toLoginPage('/login?t=cx')">次席申请</van-button>
			<van-button class="btn" type="info" block @click="toLoginPage('/login?t=yq')">结算账户业务</van-button>
		</div>
	</div>

</template>

<script>
	export default {
		data() {
			return {

			};
		},
		methods: {
			toLoginPage(url) {
				this.$router.push(url);
			}
		}
	}
</script>

<style scoped>
	.box {
		margin-top: 20%;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: space-around;
		height: 150px;
	}

	.btn {
		width: 60%;
	}
</style>
