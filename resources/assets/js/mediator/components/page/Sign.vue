<template>
    <layout title="上传签名" >
        <div style="text-align: center;margin-top: 60px;">
            <van-divider >请确保签名为本人名字,图片、字迹清晰</van-divider>

            <van-uploader name="sign_img" upload-text="签名" :disabled="disabled"   :after-read="afterRead" :before-read="beforeRead" :preview-image="true" >
                    <img :src="sign_img" width="96%" alt="">
            </van-uploader>

            <div style="text-align: center">
                <van-button style="margin-top: 60px;width: 94%" type="info" @click="next()">{{btnName}}</van-button>
            </div>
        </div>

    </layout>
</template>

<script>
    import { Uploader } from 'vant';
    Vue.use(Uploader);
    export default {
        data() {
            return {
                btnName:'下一步',
                disabled:false,
                sign_img : "/images/index/mediator/sign.png",
                form:{
                    sign_img : "",
                    func:'sign'
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
            'form.sign_img': {
                test: /\w{1,}/,
                    message: '签名照必须上传'
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
                Vue.api.uploadFile(file.file, 'sign_img').then(res => {
                    if(type === 'sign_img') {
                        this.sign_img = file.content;
                        this.form.sign_img = res
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
            next(){
                if(this.readonly === '1') {
                    history.go(-1);
                    return false;
                }
                let verifyList = ['form.sign_img'];
                // check() 校验所有规则，参数可以设置需要校验的数组
                if(!this.$vuerify.check(verifyList)){
                    this.$toast(this.errors["form.sign_img"]);
                    return false;
                }
                Vue.api.doInfo(this.form).then(res => {
                    this.$toast.success('保存成功');
                    Vue.utils.next();
                }).catch(error => this.$toast(error));
            }
        },
        created:function () {
            let info = JSON.parse(this.data);
            if(info.status === 1) {
                let user = info.data;
                this.form.sign_img = user.sign_img;
                this.sign_img = user.sign_img_base64;
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