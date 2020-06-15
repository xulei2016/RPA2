<template>
	<div>
		<TitleNav :title="title"></TitleNav>
		<!-- 上传证件 -->
		<van-form v-show="showForm == false">
			<UploadCredentials @upload-success="uploadSuccess" @delete-success="deleteSuccess"></UploadCredentials>

			<div style="display: flex;justify-content: center;margin-top:100px;">
				<van-button class="next-btn my-btn t-btn" type="primary" block @click="lastStep">
					上一步
				</van-button>
				<van-button :disabled="credentials.length < 2" class="next-btn my-btn t-btn" type="primary" block @click="commitInfo">
					提交
				</van-button>
			</div>

		</van-form>

		<!-- 填写信息 -->
		<div v-show="showForm">
			<van-field name="radio" label="业务类型" style="margin-top:20px;">
				<template #input>
					<div style="display: flex;">
						<van-button @click="setType('add')" size="small" plain :type="type=='add' ? 'primary' : 'default'">新增结算账户</van-button>
						<van-button @click="setType('update')" style="margin-left:10px;" size="small" plain :type="type=='update' ? 'primary' : 'default'">变更结算账户</van-button>
					</div>
				</template>
			</van-field>

			<van-field required name="radio" label="提示" v-show="type == 'update'">
				<template #input>
					<div class="tishi" style="display: flex;flex-direction: column;">
						<div class="tip">请确认已解除原有的银期关系</div>
					</div>
				</template>
			</van-field>

			<van-field label="原结算账户" v-show="type == 'update'">
				<template #input>
					<div class="cards-box">
						<div class="mycards">
							<div class="card jiesuan-card" :class="{'a-select': card.bankCardNum == oldAccount}" @click="selectUpdateAccount(card)" :key="card.bankCardNum" v-for="card in myAccountList">
								<div class="c-title">{{card.openingBank}}</div>
								<div class="c-account">{{card.bankCardNum}}</div>
								<!-- <div class="c-desc">{{card.bankName}}</div> -->
							</div>
							<div v-show="myAccountList.length == 0 && noAccountTip">{{noAccountTip}}</div>
						</div>
					</div>
				</template>
			</van-field>

			<van-field required name="radio" label="提示" v-show="type == 'add'">
				<template #input>
					<div class="tishi" style="display: flex;flex-direction: column;">
						<div class="tip">每家银行只能使用一张银行卡作为结算账户</div>
					</div>
				</template>
			</van-field>

			<!-- 银行卡列表 -->
			<van-field v-show="type" :label="type == 'update' ? '新结算账户' : '银行卡'">
				<template #input>
					<div class="cards-box">
						<div class="cards">
							<div class="card card-new" :key="card.account" v-for="card in cards">
								<div class="c-title">{{card.openingBank}}</div>
								<div class="c-account">{{card.bankCardNum}}</div>
								<!-- <div class="c-desc">{{card.bankName}}</div> -->
								<van-icon @click="deleteCard(card.bankCardNum)" color="#fff" name="close" class="close-icon" />
							</div>
						</div>

						<div>
							<van-button :disabled="(type == 'update') && (myAccountList.length == 0)" v-show="(type == 'add') || (type == 'update' && cards.length==0 )" icon="plus" size="mini" @click="clickAddCard" type="primary"></van-button>
						</div>
					</div>

				</template>
			</van-field>


			<!-- 添加银行卡信息 -->
			<van-popup @close="addBoxClose" class="popup-box" v-model="addBoxShow" position="top" :overlay="true">
				<van-field name="uploader" style="margin-top: 10px;" label="银行卡">
					<template #input>
						<van-uploader v-model="bankCard" accept="image/*" :after-read="bankCardRead" :max-count="1" />
					</template>
				</van-field>

				<van-field v-model="bankCardNum" clearable type="number" name="卡号" label="卡号" placeholder="请填写并核对卡号" />
				<van-field readonly clickable label="银行名" :value="openingBank" placeholder="请选择银行名" @click="clickSelectBank" />
				<van-field v-model="bankName" type="网点名称" name="网点名称" label="网点名称" placeholder="例:xxxx支行|分行|营业部|分理处" />
				<van-button style="width:60%;margin-left: 20%;margin-top: 20px;margin-bottom: 20px;" type="primary" @click="pushCard">确定</van-button>
			</van-popup>
			
			<!-- 显示银行列表 -->
			<van-popup v-model="showPicker" position="bottom">
				<van-picker :default-index="bankSelectIndex" show-toolbar :columns="bankNames" @cancel="showPicker = false" @confirm="onSelectBank" />
			</van-popup>

			<div style="display: flex;justify-content: center;margin-top:100px;">
				<van-button :disabled="!type || cards.length == 0" class="next-btn my-btn" type="primary" block @click="nextStep">
					下一步
				</van-button>
			</div>
		</div>
	</div>
</template>

<script>
	import UploadCredentials from '../../components/UploadCredentials.vue';
	import {
		commitYingqiChange,
		bankCardOcr,
		getBankList,
		getMyAccountList
	} from '../../api/api.js'
	export default {
		components: {
			UploadCredentials
		},
		data() {
			return {
				title: '业务办理',
				showForm: true,
				credentials: [],

				type: '',

				bankCard: [],
				bankCardNum: '',
				openingBank: '',
				bankName: '',

				bankNames: [],
				bankSelectIndex: 0,
				showPicker: false,

				cards: [],

				myAccountList: [

				],
				noAccountTip: "",
				oldAccount: '',
				oldBankName: '',
				bankCardSignature: '',

				addBoxShow: false

			};
		},
		methods: {
			// 上传证件后回调
			uploadSuccess(credentials) {
				this.credentials = credentials;
			},

			//删除文件
			deleteSuccess(credentials) {
				this.credentials = credentials;
			},

			//OCR识别
			doOCR(base64) {
				let data = {
					imageBase64: base64,
					type: this.type
				};
				this.$toast.loading({
					message: '卡号识别中...',
					forbidClick: true,
					duration: 0
				});
				bankCardOcr(data).then(res => {
					if (res) {
						this.$toast.clear();
						if (res.code) {
							this.$toast(res.msg);
						} else {
							let name = res.data.name;
							if (this.type == 'update') {
								for (let i in this.myAccountList) {
									if (this.oldAccount != this.myAccountList[i].bankCardNum && this.myAccountList[i].openingBank == name) {
										this.$toast(name + "存在结算账户");
										return false;
									}
								}
							}
							this.bankCardNum = res.data.account;
							for (let i in this.bankNames) {
								if (this.bankNames[i].text == name) {
									this.bankSelectIndex = i;
									this.openingBank = name;
									break;
								}
							}
							if (this.bankCardNum) {
								if (name && !this.openingBank) {
									this.$toast('请手动选择银行')
								} else {
									this.$toast('已识别请核对')
								}
							} else {
								this.$toast('未识别，请手动填写')
							}
						}
					}

				}).catch(() => {
					this.$toast.clear();
					this.$toast('未识别请手动填写');
				})
			},

			//上传银行卡回调
			bankCardRead(file) {
				this.compress(file.content, 1600, 0.7).then(val => {
					this.doOCR(val)
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
			},

			//点击选择银行
			clickSelectBank() {
				getBankList().then(res => {
					let names = res.data;
					for (let i in this.myAccountList) {
						if (this.myAccountList[i].bankCardNum != this.oldAccount) {
							for (let j in names) {
								if (names[j].text == this.myAccountList[i].openingBank) {
									names[j].disabled = true;
								}
							}
						}
					}

					this.bankNames = names;
					this.showPicker = true;
				}).catch(error => {
					console.log(error);
					this.bankNames = [{
						text: "其他",
						disabled: false
					}];
					this.showPicker = true;
				})
			},

			//选择银行
			onSelectBank(value) {
				this.openingBank = value.text;
				this.showPicker = false;
			},

			//选择添加或变更
			setType(type) {
				this.type = type;
				this.cards = [];
				this.oldAccount = "";
				this.oldBankName = "";
				this.bankCardSignature = "";
				this.myAccountList = [];
				this.initAddCard();
				if (this.type == 'update') {
					this.getMyAccountList();
				}
			},

			//获取CRM系统里的银期关系
			getMyAccountList() {
				getMyAccountList().then(res => {
					this.myAccountList = res.data;
					if (res.data.length) {
						this.selectUpdateAccount(res.data[0]);
					} else {
						this.noAccountTip = "无";
					}
				})
			},

			nextStep() {
				this.showForm = false;
				this.title = "上传证件";
			},

			lastStep() {
				this.showForm = true;
				this.title = "业务办理";
			},

			/* 点击添加银行卡 */
			clickAddCard() {
				if (this.type == 'add') {
					this.initAddCard();
				} else {
					if (!this.oldAccount) {
						this.$toast('请选择要变更的结算账户');
						return false;
					}
				}
				this.addBoxShow = true;
			},

			/* 添加银行卡popup隐藏 */
			addBoxClose() {
				//this.initAddCard();
			},

			//添加银行卡到列表
			pushCard() {
				if (this.bankCardNum.length < 10) {
					this.$toast('请填写正确的卡号');
					return false;
				}
				if (!this.bankName || !this.openingBank || !this.bankCard[0]) {
					this.$toast('请填写完整的信息');
					return false;
				}
				if (this.hasCard()) {
					this.$toast('请不要重复添加');
					return false;
				}
				if (this.bankCard[0]) {
					let card = {
						bankCard: this.bankCard[0].content,
						bankCardNum: this.bankCardNum,
						openingBank: this.openingBank,
						bankName: this.bankName,
					}

					this.cards.push(card);
					this.addBoxShow = false;
				}
			},

			//是否存在银行卡 有相同名称的不能添加
			hasCard() {
				for (let i in this.cards) {
					if (this.cards[i].openingBank == this.openingBank || this.cards[i].bankCardNum == this.bankCardNum) {
						return true;
					}
				}

				return false;
			},

			//删除一张银行卡
			deleteCard(num) {
				for (let i = this.cards.length - 1; i >= 0; i--) {
					if (this.cards[i].bankCardNum == num) {
						this.cards.splice(i, 1);
					}
				}
			},

			/* 初始化新添加的银行卡 */
			initAddCard() {
				this.bankCard = [];
				this.bankCardNum = '';
				this.openingBank = '';
				this.bankName = '';
			},


			//选择要更新的结算账户
			selectUpdateAccount(obj) {
				this.oldAccount = obj.bankCardNum;
				this.oldBankName = obj.openingBank;
				this.bankCardSignature = obj.bankCardSignature;
			},


			//提交信息
			commitInfo() {
				if (this.verifyForm()) {
					this.$toast(this.verifyForm());
					return false;
				}
				
				this.compress(this.credentials[0].content, 1600, 0.7).then(zm => {
					this.compress(this.credentials[1].content, 1600, 0.7).then(fm => {
						let data = {
							certificates_positive: zm,
							certificates_negative: fm,
							cards: this.cards,
							oldAccount: this.bankCardSignature,
							oldBankName: this.oldBankName,
							type: this.type == 'add' ? 1 : 2
						}
						this.$toast.loading({
							message: '提交中...',
							forbidClick: true,
							duration: 0
						});
						commitYingqiChange(data).then(res => {
							if (res) {
								this.$toast.clear();
								this.$router.push('/yq/state');
							}
						})
					});
					
				});
			},

			//验证表单
			verifyForm() {
				//var pattern = /^([1-9]{1})(\d{14}|\d{18})$/;
				if (!this.cards.length) {
					return '请上传银行卡';
				}

				return false;
			}


		},
		mounted() {
			getBankList().then(res => {
				this.bankNames = res.data;
			})
		}
	}
</script>

<style scoped>
	.next-btn {
		width: 45%;
	}

	.t-btn {
		width: 30%;
		margin: 10px;
	}

	.tishi div {
		color: #969799;
	}

	.tip {}

	.public {
		margin: 10px;
		font-size: 14px;
	}

	.popup-box {
		width: 90%;
		margin-top: 20%;
		margin-left: 5%;
	}

	.card {
		border-radius: 2px;
		margin-top: 5px;
		color: #969799;
		position: relative;
	}

	.card-new {
		border: 1px solid #6098ff;
		background-color: #6098ff;
		box-shadow: #1E90FF 0px 0px 10px;
		height: 60px;
	}

	.card-new div {
		color: #FFFFFF;
	}

	.jiesuan-card {
		border: 1px solid #969799
	}

	.close-icon {
		position: absolute;
		right: 2px;
		top: 2px;
	}

	.cards-box {
		display: flex;
		flex-direction: column;
		width: 80%;
	}

	.c-title {
		font-weight: bold;
		padding-left: 5px;
	}

	.c-account {
		padding-left: 5px;
	}

	.c-desc {
		padding-left: 5px;
	}

	.a-select {
		background-color: #42B983;
		border-color: #42B983;
		color: #FFFFFF;
	}
</style>
