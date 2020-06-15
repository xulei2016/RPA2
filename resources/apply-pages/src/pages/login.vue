<template>
	<div>
		<TitleNav title="登录"></TitleNav>
		<van-form style="margin-top: 10%;">
			<van-field v-model="username" name="姓名" label="姓名" placeholder="姓名" />

			<van-field v-model="account" type="number" name="资产账户" label="资产账户" placeholder="资产账户" />

			<van-field v-model="mobile" type="number" center clearable label="手机号" placeholder="请输入柜台手机号">
				<van-button :disabled="(disabledCodeBtn || mobileInlegal)" @click="getCode" class="my-btn" slot="button" size="small" type="primary">{{codeText}}</van-button>
			</van-field>

			<van-field v-model="code" type="number" name="验证码" label="验证码" placeholder="填写验证码" />

			<div style="margin: 16px;margin-top:100px;">
				<van-button :disabled="!(username && account && mobile && code)" class="my-btn" round block type="info" @click="clickLoginBtn">
					登录
				</van-button>
			</div>
		</van-form>

	</div>

</template>

<script>
	import {login, sendCode} from '../api/api.js';
	export default {
		data() {
			return {
				username: '',
				account: '',
				mobile: '',
				code: '',

				codeText: '发送验证码',
				disabledCodeBtn: false,
				
				timeout: false
			};
		},
		computed: {
			mobileInlegal: function() {
				return !(/^1[3456789]\d{9}$/.test(this.mobile));
			}
		},
		methods: {
			//点击登录
			clickLoginBtn(){
				if(this.timeout){
					clearTimeout(this.timeout);
				}
				this.timeout = setTimeout(this.doLogin, 500);
			},
			//执行登录
			doLogin() {	
				if (this.verifyForm()) {
					this.$toast(this.verifyForm());
					return false;
				}
				
				let data = {
					name: this.username,
					zjzh: this.account,
					phone: this.mobile,
					vCode: this.code,
					allowDormancy: this.$route.query.t == 'yq' ? 1 : 0
				};
				this.$toast.loading({
					message: '登录中..',
					forbidClick: true,
					duration: 0
				});
				login(data).then(res=>{
					if(res){
						this.$toast.clear();
						if(res.code == 200){
							localStorage.setItem('login', true);
							if (this.$route.query.t == 'cx') {
								this.$router.push('/cx/state')
							} else {
								this.$router.push('/yq/list')
							}
						}
					}
				})
				

			},

			//验证表单
			verifyForm() {
				if (!this.username) {
					return '请输入姓名';
				} else if (!this.account) {
					return '请输入资金账户';
				} else if (!(/^1[3456789]\d{9}$/.test(this.mobile))) {
					return '请输入正确的手机号';
				} else if (!this.code) {
					return '请输入验证码';
				}

				return false;
			},

			//获取验证码
			getCode() {
				if (!(/^1[3456789]\d{9}$/.test(this.mobile))) {
					this.$toast('请输入正确的手机号');
					return false;
				}
				//给一个手机号发送验证码并返回
				let data = {
						name: this.username,
						zjzh: this.account,
						phone: this.mobile,
						allowDormancy: this.$route.query.t == 'yq' ? 1 : 0
					};

				sendCode(data).then(res => {
					if(res && res.code == 200){
						this.countDown(60);
						//this.$toast(res.info);
					}
				})

			},

			countDown(time) {
				if (time === 0) {
					this.disabledCodeBtn = false
					this.codeText = "发送验证码"
					return
				} else {
					this.disabledCodeBtn = true;
					this.codeText = '重新发送(' + time + ')'
					time--
				}
				setTimeout(() => {
					this.countDown(time)
				}, 1000)
			}
		}
	};
</script>

<style>
</style>
