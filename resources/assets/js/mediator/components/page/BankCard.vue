<template>
    <layout title="银行卡信息" >
        <div style="text-align: center;margin-top: 60px;">
            <van-divider>推荐使用工商银行等大型银行</van-divider>
            <van-uploader  name="bank_img" upload-text="银行卡" :disabled="disabled" :after-read="afterRead" :before-read="beforeRead" >
                <img :src="bank_img" width="96%" alt="">
            </van-uploader>

            <van-divider>基本信息</van-divider>

            <van-cell-group>
                <van-field
                        v-model="form.bank_name" placeholder="选择银行"
                        label="选择银行"
                        :error-message="errors['form.bank_name']"
                        @click="bankPicker=!disabled"
                        required
                        readonly />
            </van-cell-group>

            <van-cell-group>
                <van-field   v-model="form.bank_branch" :readonly="disabled" placeholder="银行网点"  label="银行网点" :error-message="errors['form.bank_branch']"  required />
            </van-cell-group>

            <van-cell-group>
                <van-field  v-model="form.bank_username" :readonly="disabled"  placeholder="银行户名"  label="银行户名" :error-message="errors['form.bank_username']"  required />
            </van-cell-group>

            <van-cell-group>
                <van-field  v-model="form.bank_number" @blur="onblur()" :readonly="disabled" placeholder="银行卡号"  label="银行卡号" :error-message="errors['form.bank_number']"  required />
            </van-cell-group>
            <div style="text-align: center">
                <van-button style="margin-top: 60px;width: 94%" type="info" @click="next()">{{btnName}}</van-button>
            </div>
        </div>
        <van-popup v-model="bankPicker" position="bottom">
            <van-picker
                    show-toolbar
                    :columns="bankList"
                    @cancel="bankPicker = false"
                    @confirm="bankConfirm"
            />
        </van-popup>
    </layout>
</template>

<script>
    import { Uploader } from 'vant';
    Vue.use(Uploader);
    export default {
        data() {
            return {
                btnName:'下一步',
                bank_img : "/images/index/mediator/yhk.png",
                bankPicker : false,
                bankList : [],
                disabled:false,
                error:'',
                form:{
                    bank_img:'',
                    bank_name:'',
                    bank_branch:'',
                    bank_username:'',
                    bank_number:'',
                    func:'bankCard'
                }
            }
        },
        props: {
            data: {
                type: String,
                default: ''
            },
            readonly: {
                type: String,
                default: '0'
            }

        },
        vuerify: {
            'form.bank_img': {
                test: /\w{1,}/,
                message: '银行卡图片必须上传'
            },
            'form.bank_name': {
                test: /^[\S|\s]+$/,
                message: '银行必填'
            },
            'form.bank_branch': {
                test: /\S{1,}/,
                message: '银行网点必填'
            },
            'form.bank_username': {
                test: /\S{1,}/,
                message: '银行户名必填'
            },
            'form.bank_number': {
                test: /\S{1,}/,
                message: '银行卡号必填'
            }
        },
        computed: {
            errors () {
                return this.$vuerify.$errors
            }
        },
        methods: {
            afterRead(file, detail) {
                let type = detail.name;
                Vue.api.uploadFile(file.file, type).then(res => { // 上传文件到服务器
                    if(type === 'bank_img') {
                        this.bank_img = file.content;
                        this.form.bank_img = res;
                    }
                    this.$toast.clear();
                }).catch(error => this.$toast(error));
                return true;
            },
            beforeRead(file) {
                if (file.type !== 'image/jpeg' && file.type !== 'image/png') {
                    this.$toast('请上jpg或者png图片');
                    return false;
                }
                this.$toast.loading({
                    duration: 0, // 持续展示 toast
                    forbidClick: true,
                    message:"图片上传中"
                });
                return true;
            },
            bankConfirm(value) { // 学历
                this.form.bank_name = value;
                this.bankPicker = false;
            },
            onblur(){
                let card = this.form.bank_number;
                if(card) {
                    Vue.api.checkBankCard(card).then(res => {
                        this.error = '';
                    }).catch(error => {
                        this.$toast.fail(error);
                        this.error = error;
                    });
                }

            },
            next(){
                if(this.readonly === '1') {
                    history.go(-1);
                    return false;
                }
                if(this.error) {
                    this.$toast.fail(this.error);
                    return false;
                }
                let verifyList = ['form.bank_img', 'form.bank_name', 'form.bank_branch', 'form.bank_username', 'form.bank_number'];
                // check() 校验所有规则，参数可以设置需要校验的数组
                if(!this.$vuerify.check(verifyList)){
                    this.$toast("保存失败");
                    return false;
                }
                Vue.api.doInfo(this.form).then(res => {
                    this.$toast.success('保存成功');
                    Vue.utils.next();
                }).catch(error => this.$toast(error));
            }
        },
        created: function () {
            Vue.api.getDistList('bank').then(res => { //银行卡选择
                this.bankList = res;
            });
            let info = JSON.parse(this.data);
            if(info.status === 1) {
                let user = info.data;
                this.bank_img = user.bank_img_base64;

                this.form.bank_img = user.bank_img;
                this.form.bank_name = user.bank_name;
                this.form.bank_branch = user.bank_branch;
                this.form.bank_username = user.bank_username;
                this.form.bank_number = user.bank_number;
            }
            if(this.readonly === '1') {
                this.btnName = '返回';
                this.disabled = true;
            }
        }
    }
</script>

<style scoped>

</style>