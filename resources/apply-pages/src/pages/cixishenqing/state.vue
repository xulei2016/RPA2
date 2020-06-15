<template>
	<div>
		<TitleNav title="反馈"></TitleNav>

		<div v-show="id" style="display: flex;flex-direction: column;align-items: center;margin-top:100px;">
			
			<van-icon :name="result.iconName" size="50px" :color="result.color" />
			<p class="title">{{result.title}}</p>

			
			<div class="data" style="margin-bottom: 10px;">
				<div style="color:#969799;font-size: 14px;font-weight: bold;">次席{{business_type == 1 ? '申请' : '取消'}} <span>{{counter_type == 1 ? 'CTP' : '易盛'}} {{type==1 ? '张江9.0' : ''}} {{type==2 ? '郑州9.0' : ''}}</span></div>
			</div>
			
			<div class="comment" v-if="status == 0">
				<div style="text-align: center;">您已经成功办理业务</div>
				<div style="text-align: center;">请在1小时后尝试登录软件</div>
			</div>
			
			<div class="comment" v-if="status == 1">
				<div style="text-align: center;">工作人员正在为您办理</div>
				<div style="text-align: center;">请耐心等待1-2个工作日</div>
			</div>
			
			<div class="comment" v-if="status == 2">
				<div style="text-align: center;margin-bottom: 10px;">无法进行业务办理，原因如下</div>
				<div style="text-align: center;margin-top:5px;" v-for="r in reasonToArray(reason)" :key="r">{{r}}</div>
				<div v-show="reason && ((reason.indexOf('证件地址不同') > -1) || (reason.indexOf('有效期不一致') > -1) || (reason.indexOf('证件过期') > -1))"> 
					需要在公众号<a class="a-link" href="http://www.hatzjh.com/business/upsfz">更新证件</a>之后再来办理
				</div>
			</div>
			
			<div class="comment" v-if="status == 3">
				<div style="text-align: center;">后台工作人员已经收到您的申请</div>
				<div style="text-align: center;">一般1-2个工作日内办理</div>
			</div>
			
			<van-button v-if="status == 2" style="margin-top: 40px;" class="next-btn my-btn" type="primary" block @click="applyAgain">
				重新办理
			</van-button>
			
			<van-button v-if="status == 0" style="margin-top: 40px;" class="next-btn my-btn" type="primary" block @click="applyAgain">
				继续
			</van-button>
			
			<!-- <a @click="clearUser">debug clear user</a> -->
		</div>
	</div>
</template>

<script>
	import {getLastCixiApply} from '../../api/api.js'
	export default {
		data() {
			return {
				id: '',
				status: '',
				reason: '',
				business_type: '',
				counter_type: '',
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
			getLastCixiApply().then(res=>{
				if(res.data && res.data.id){
					let obj = res.data;
					this.id = obj.id;
					this.status = obj.status;
					this.reason = obj.reason;
					this.business_type = obj.business_type; 
					this.counter_type = obj.counter_type;
					this.type = obj.type;
					this.result = this.results[this.status];
				}else{
					this.$router.push('/cx/index');
				}
			})
		},
		
		methods:{
			clearUser(){
				localStorage.removeItem('account');
				this.$toast('用户身份已清除');
			},
			
			applyAgain(){
				this.$router.push('/cx/index');
			},
			
			reasonToArray(str){
				if(str){
					var arr = str.split(',');
					return arr;
				}	
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
	
	.a-link{
		color: #1E90FF;
		text-decoration: underline;
	}
</style>
