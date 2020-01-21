<template>
    <layout title="上传身份证照片" >
        <div style="text-align: center; margin-top: 60px;">
            <van-divider>请确保身份证在有效期内,四边完整,字迹清晰</van-divider>
            <van-uploader name="sfz_zm_img" upload-text="身份证正面" :disabled="disabled" :after-read="afterRead" :before-read="beforeRead" :preview-image="true" >
                <img :src="sfz_zm_img" width="96%" alt="">
            </van-uploader>
            <br>
            <br>
            <van-uploader name="sfz_fm_img" upload-text="身份证反面" :disabled="disabled" :after-read="afterRead" :before-read="beforeRead" >
                <img :src="sfz_fm_img" width="96%" alt="">
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
                sfz_zm_img : "/images/index/mediator/sfz_zm.png",
                sfz_fm_img : "/images/index/mediator/sfz_fm.png",
                form:{
                    sfz_zm_img : "",
                    sfz_fm_img : "",
                    func:'IDCard'
                }
            }
        },
        props: {
            data: {
                type: String,
                default: ''
            },
            readonly:{
                type: String,
                default: ''
            }
        },
        vuerify: {
            'form.sfz_zm_img': {
                test: /\w{1,}/,
                message: '身份证正面必须上传'
            },
            'form.sfz_fm_img': {
                test: /\w{1,}/,
                message: '身份证反面必须上传'
            },
        },
        computed: {
            errors () {
                return this.$vuerify.$errors
            }
        },
        methods: {
            afterRead(file, detail) {
                let type = detail.name;
                Vue.api.uploadFile(file.file, type).then(res => {
                    if(type === 'sfz_zm_img') {
                        this.sfz_zm_img = file.content;
                        this.form.sfz_zm_img = res;
                    } else {
                        this.sfz_fm_img = file.content;
                        this.form.sfz_fm_img = res;
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
                let verifyList = ['form.sfz_zm_img', 'form.sfz_fm_img'];
                // check() 校验所有规则，参数可以设置需要校验的数组
                if(!this.$vuerify.check(verifyList)){
                    this.$toast("身份证必须上传");
                    return false;
                }
                Vue.api.doInfo(this.form).then(res => {
                    this.$toast.success('保存成功');
                    Vue.utils.next();
                }).catch(error => this.$toast(error));
            }
        },
        created:function(){
            let info = JSON.parse(this.data);
            if(info.status === 1) {
                let user = info.data;
                this.sfz_zm_img = user.sfz_zm_img_base64;
                this.sfz_fm_img = user.sfz_fm_img_base64;
                this.form.sfz_zm_img = user.sfz_zm_img;
                this.form.sfz_fm_img = user.sfz_fm_img;
            }
            if(this.readonly === '1') {
                this.disabled = true;
                this.btnName = '返回';
            }
        }
    }
</script>

<style scoped>

</style>