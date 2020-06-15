<template>
	<div>
		<TitleNav title="反馈"></TitleNav>

		<div  style="display: flex;flex-direction: column;align-items: center;margin-top:100px;">
			
			<van-icon :name="result.iconName" size="50px" :color="result.color" />
			<p class="title">{{result.title}}</p>

			<div class="comment">
				<div style="text-align: center;">后台工作人员已经收到您的申请</div>
				<div style="text-align: center;">一般1-2个工作日内办理</div>
			</div>

		</div>
		
		<van-button  class="next-btn my-btn m-btn" type="primary" @click="recordPage">
			提交记录
		</van-button>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				id: '',
				status: '',
				reason: '',
				bankCardNum: '',
				type: '',
				
				results: [
					{title: '办理成功', iconName: 'checked', color: '#07c160'},
					{title: '办理中', iconName: 'info', color: 'dodgerblue'},
					{title: '办理失败', iconName: 'warning-o', color: '#d43f3a'},
					{title: '已提交', iconName: 'info', color: '#07c160'},
					],
					
				result: {
					
				}
			}
		},
		
		mounted(){
			this.result = this.results[3];
		},
		
		methods:{

			recordPage(){
				this.$router.push('/yq/list');
			}
		}
	}
</script>

<style scoped>
	.title {
		font-weight: bold;
	}
	
	.next-btn {
		width: 40%;
	}
	

	.comment {
		color: #969799;
		font-size: 12px;
	}
	
	.m-btn{
		height: 35px;
		line-height: 35px;
		width: 40%;
		margin-left: 30%;
		position: fixed;
		bottom: 20px;
	}
</style>
