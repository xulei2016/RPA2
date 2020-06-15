<template>
	<div v-show="lists.length">
		<TitleNav title="办理记录"></TitleNav>
		<div class="tip">一般1-2个交易日内办理，请耐心等待</div>
		
		<div style="margin-bottom: 70px;">
			<van-card :key="list.id" v-for="list in lists">
				<div slot="desc">
					<div style="display: flex;justify-content: space-between;align-items: center;">
						<div class="title"> {{list.opening_bank}} [{{list.type == 1 ? '新增' : '变更'}}]
							<van-tag color="#07c160" type="primary" v-show="list.status == 0">办理成功</van-tag>
							<van-tag color="dodgerblue" type="primary" v-show="list.status == 1">办理中</van-tag>
							<van-tag color="#d43f3a" type="primary" v-show="list.status == 2">办理失败</van-tag>
							<van-tag color="dodgerblue" type="primary" v-show="list.status == 3">已提交</van-tag>
						</div>
						<div class="comment time">{{list.created_at}}</div>
					</div>
					
					<div class="comment"><span class="tt">流水号：</span>{{list.order_num}} <van-icon name="description" v-clipboard:copy="list.order_num" v-clipboard:success="onCopy" v-clipboard:error="onError" /></div>
					<div class="comment" v-show="list.old_account"><span class="tt">原卡号：</span>{{list.old_account}} [{{list.old_bank_name}}]</div>
					<div class="comment"><span class="tt">卡号：</span>{{list.bank_card_num}} [{{list.opening_bank}}]</div>
					<div class="comment" v-show="list.reason" v-for="r in reasonToArray(list.reason)" :key="r"><span class="tt"></span>{{r}}
					</div>
					<div class="comment" v-if="list.reason && ((list.reason.indexOf('证件地址不同') > -1) || (list.reason.indexOf('有效期不一致') > -1) || (list.reason.indexOf('证件过期') > -1))">
						需要在公众号<a class="a-link" href="http://www.hatzjh.com/business/upsfz">更新证件</a>之后再来办理
					</div>
					<van-button v-show="list.status == 2" size="mini" @click="toIndex()">重新办理</van-button>
				</div>
				<div slot="footer">

				</div>
			</van-card>
		</div>
	
		<van-button class="next-btn my-btn m-btn" @click="toIndex()" color="#A72F23">继续办理</van-button>
	</div>
</template>

<script>
	import {
		getYqChangeList
	} from '../../api/api.js'
	export default {
		data() {
			return {
				lists: []
			}
		},

		mounted() {
			getYqChangeList().then(res => {
				if (res.data.length) {
					this.lists = res.data;
				} else {
					this.$router.push('/yq/index');
				}
			})
		},

		methods: {
			toIndex() {
				this.$router.push('/yq/index');
			},

			reasonToArray(str) {
				if (str) {
					var arr = str.split(',');
					return arr;
				}
			},

			onCopy() {
				this.$toast('已复制');
			},
			onError() {
				this.$toast('复制失败');
			}
		}
	}
</script>

<style scoped>
	.record {
		margin-top: 20px;
		padding-left: 10px;
		color: #969799;
		font-size: 13px;
	}

	.comment {
		color: #969799;
		font-size: 13px;
		height: 22px;
	}

	.title {
		font-weight: bold;
		font-size: 16px;
		margin-bottom: 10px;
	}

	.tip {
		color: #969799;
		font-size: 12px;
		text-align: center;
		margin-top: 10px;
		margin-bottom: 10px;
	}
	
	.tt{
		color: #323233;
	}
	
	.m-btn{
		height: 35px;
		line-height: 35px;
		width: 40%;
		margin-left: 30%;
		position: fixed;
		bottom: 20px;
	}
	.time{
		font-size: 11px;
	}
	
	.a-link{
		color: #1E90FF;
		text-decoration: underline;
	}
</style>
