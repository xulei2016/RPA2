<template>
	<div>

		<TitleNav :title="title"></TitleNav>

		<!-- 上传证件 -->
		<van-form v-show="showForm == false">
			<UploadCredentials @upload-success="uploadSuccess" @delete-success="deleteSuccess"></UploadCredentials>
			
			<div style="display: flex;justify-content: center;margin-top:100px;">
				<van-button :disabled="credentials.length < 2" class="next-btn my-btn" type="primary" block @click="nextStep">
					下一步
				</van-button>
			</div>
		</van-form>

		<!-- 选择柜台 -->
		<div v-show="showForm">
			
			<van-field name="bar" label="业务类型" style="margin-top:20px;">
				<template #input>
					<div style="display: flex;">
						<van-button :disabled="optionsDisabled" @click="setBusinessType('add')" size="small" plain :type="businessType=='add' ? 'primary' : 'default'">开通</van-button>
						<van-button :disabled="optionsDisabled" @click="setBusinessType('cancel')" style="margin-left:10px;" size="small" plain :type="businessType=='cancel' ? 'primary' : 'default'">取消</van-button>
					</div>
				</template>
			</van-field>
			
			<van-field name="bar" label="选择柜台">
				<template #input>
					<div style="display: flex;">
						<van-button :disabled="optionsDisabled" @click="setBarType('ctp')" size="small" plain :type="barType=='ctp' ? 'primary' : 'default'">CTP</van-button>
						<van-button :disabled="optionsDisabled" @click="setBarType('yisheng')" style="margin-left:10px;" size="small" plain :type="barType=='yisheng' ? 'primary' : 'default'">易盛</van-button>
					</div>
				</template>
			</van-field>

			<van-field name="radio" label="请选择" v-show="barType == 'yisheng'">
				<template #input>
					<div style="display: flex;">
						<van-button :disabled="optionsDisabled" @click="setYiShengType('zhangjiang')" size="small" plain :type="type=='zhangjiang' ? 'primary' : 'default'">张江9.0</van-button>
						<van-button :disabled="optionsDisabled" @click="setYiShengType('zhengzhou')" style="margin-left:10px;" size="small" plain :type="type=='zhengzhou' ? 'primary' : 'default'">郑州9.0</van-button>
					</div>
				</template>
			</van-field>

			<van-field name="radio" :label="barType == 'ctp' ? '办理条件' : '提示'" v-show="barType">
				<template #input>
					<div class="tishi" style="display: flex;flex-direction: column;">
						<span v-show="barType == 'ctp'">
							<div>1、您有持仓的情况下，无法办理次席{{businessType == 'cancel' ? '取消' : '申请'}}</div>
							<div>2、盘中{{businessType == 'cancel' ? '取消' : '开通'}}条件：当天无交易、无持仓</div>
							<div>3、当天有交易、无持仓，系统在交易日下午3点后才能为您办理</div>
						</span>
						<span v-show="barType == 'yisheng'">
							<div>系统在交易日下午3点后才能为您办理</div>
						</span>
					</div>
				</template>
			</van-field>
			
			<van-field  label="注意事项" v-show="businessType == 'add'">
				<template #input>
					<div class="tishi" style="display: flex;flex-direction: column;">
						<div>1、开通次席后，次席不具备出入金功能，只能主席出入金、次席交易，请知悉。</div>
						<div>2、次席密码是独立的，需要单独修改。</div>
					</div>
				</template>
			</van-field>
			
<!-- 			<div class="tishi public" v-show="businessType == 'add'">
				<div></div>
				
			</div> -->

			<div style="display: flex;justify-content: center;margin-top:100px;">
				<van-button class="next-btn my-btn t-btn" type="primary" block @click="lastStep">
					上一步
				</van-button>
				<van-button :disabled="!barType || (barType == 'yisheng' && !type) || !businessType" class="next-btn my-btn t-btn" type="primary" block @click="commitInfo">
					提交
				</van-button>
			</div>
		</div>

	</div>
</template>

<script>
	import UploadCredentials from '../../components/UploadCredentials.vue'
	import {commitCixiApply, getLastCixiApplySuccss} from '../../api/api.js'
	export default {
		components: {
			UploadCredentials
		},
		mounted(){
			getLastCixiApplySuccss().then(res =>{
				if(res.data){
					//最近成功  是个申请  那么只能取消
					if(res.data.business_type == 1){
						this.businessType = 'cancel';
						this.barType = res.data.counter_type == 1  ? 'ctp' : 'yisheng';
						if(this.barType == 'yisheng'){
							this.type = res.data.type == 1 ? 'zhangjiang' : 'zhengzhou';
						}
						this.optionsDisabled = true;
					}
				}
			})
		},
		data() {
			return {
				showForm: false,
				credentials: [],

				businessType: '',
				barType: '',
				type: '',
				title: '上传证件',
				
				optionsDisabled: false
				
				
			};
		},
		methods: {
			// 上传文件后回调
			uploadSuccess(credentials) {
				this.credentials = credentials;
			},
			
			//删除文件
			deleteSuccess(credentials) {
				this.credentials = credentials;
			},
			
			//选择是申请还是取消
			setBusinessType(type){
				this.businessType = type;
			},

			/* 选择柜台 */
			setBarType(type) {
				if (type == 'ctp') {
					this.type = '';
				}
				this.barType = type;
			},

			//选择易盛
			setYiShengType(type) {
				this.type = type;
			},

			nextStep() {
				this.showForm = true;
				this.title = "选择柜台";
			},
			
			lastStep() {
				this.showForm = false;
				this.title = "上传证件";
			},
			
			//提交信息
			commitInfo() {
				if(!this.credentials.length>=2){
					this.$toast('请上传证件正反面');
					return false;
				}
				
				if(!this.barType){
					this.$toast('请选择柜台');
					return false;
				}
				
				if(this.barType == 'yingsheng' && !this.type){
					this.$toast('请选择类型');
					return false;
				}
				
				this.compress(this.credentials[0].content, 1600, 0.7).then(zm => {
					this.compress(this.credentials[1].content, 1600, 0.7).then(fm => {
						let data = {
							certificates_positive: zm,
							certificates_negative: fm,
							business_type: this.businessType == 'add' ? 1 : 2,
							counter_type: this.barType == 'ctp' ? 1 : 2,
							type: this.barType=='ctp' ? '' : this.type == 'zhangjiang' ? 1 : 2
						}
						this.$toast.loading({
							message: '提交中...',
							forbidClick: true,
							duration: 0
						});
						commitCixiApply(data).then(res=>{
							if(res.code){
								this.$toast(res.msg);
								//do nothing
							}else{
								this.$toast.clear();
								this.$router.push('/cx/state');
							}
						})
					});
					
				});
			},
			
			//压缩图片
			compress(base64String, w, quality) {
				var newImage = new Image();
				var imgWidth, imgHeight;
				var promise = new Promise(resolve => (newImage.onload = resolve));
				newImage.src = base64String;
				return promise.then(() => {
					imgWidth = newImage.width;
					imgHeight = newImage.height;
					var canvas = document.createElement("canvas");
					var ctx = canvas.getContext("2d");
					if (Math.max(imgWidth, imgHeight) > w) {
						if (imgWidth > imgHeight) {
							canvas.width = w;
							canvas.height = (w * imgHeight) / imgWidth;
						} else {
							canvas.height = w;
							canvas.width = (w * imgWidth) / imgHeight;
						}
					} else {
						canvas.width = imgWidth;
						canvas.height = imgHeight;
					}
					ctx.clearRect(0, 0, canvas.width, canvas.height);
					ctx.drawImage(newImage, 0, 0, canvas.width, canvas.height);
					var base64 = canvas.toDataURL("image/jpeg", quality);
					return base64;
				});
			}
			
		}
	}
</script>

<style scoped>
	.next-btn {
		width: 45%;
	}
	
	.t-btn{
		width: 30%;
		margin: 10px;
	}

	.tishi div {
		color: #969799;
		font-size: 12px;
	}
</style>
