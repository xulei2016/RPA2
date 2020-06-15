<template>
	<div>
		<van-field name="uploader" label="身份证证件" style="margin-top:50px;">
			<template #input>
				<van-uploader @delete="afterDelete" :upload-text="uploadText" v-model="credentials" accept="image/*" :after-read="afterRead" :max-count="2" />
			</template>
		</van-field>
		<div class="tishi public">
			<div>请上传证件照的正反面，图片清晰无遮挡</div>
		</div>
	</div>
</template>

<script>
	export default {
		name: 'UploadCredentials',
		data(){
			return {
				credentials: []
			}
		},
		computed:{
			uploadText: function(){
				return this.credentials.length >=1 ? '反面' : '正面';
			}
		},
		methods:{
			afterRead(){
				this.$emit('upload-success', this.credentials);
			},
			
			afterDelete(){
				this.$emit('delete-success', this.credentials);
			}
		}
	}
</script>

<style scoped>
	.tishi div {
		color: #969799;
	}
	
	.public {
		margin: 10px;
		font-size: 12px;
	}
</style>
