<template>
    <layout title="手持身份证照片" :left="left" >
        <div style="text-align: center;margin-top: 60px;">
            <van-divider >请确保图像清晰、面部完整</van-divider>
            <van-divider v-if="status">默认显示之前上传的图片,点击图片可以重新上传</van-divider>

            <van-uploader name="sfz_sc_img" upload-text="签名" :disabled="disabled" :after-read="afterRead" :before-read="beforeRead" :preview-image="true" >
                    <img :src="sfz_sc_img" width="96%" alt="">
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
                left:'返回',
                disabled:false,
                status:false,
                sfz_sc_img : "/images/index/mediator/hand_id_card.jpg",
                form:{
                    sfz_sc_img:'',
                    func:'handIdCard'
                }
            }
        },
        props:{
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
            'form.sfz_sc_img': {
                test: /\w{1,}/,
                message: '手持身份证照必须上传'
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
                Vue.api.uploadFile(file.file, 'sfz_sc_img').then(res => {
                    if(type === 'sfz_sc_img') {
                        this.sfz_sc_img = file.content;
                        this.form.sfz_sc_img = res.path
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
                let verifyList = ['form.sfz_sc_img'];
                // check() 校验所有规则，参数可以设置需要校验的数组
                if(!this.$vuerify.check(verifyList)){
                    this.$toast(this.errors["form.sfz_sc_img"]);
                    return false;
                }
                Vue.api.doInfo(this.form).then(res => {
                    Vue.utils.next();
                }).catch(error => this.$toast(error));
            }
        },
        created:function(){
            let info = JSON.parse(this.data);
            if(info.status === 1) {
                this.status = true;
                let user = info.data;
                if(user.sfz_sc_img_base64) {
                    this.sfz_sc_img = user.sfz_sc_img_base64;
                    this.form.sfz_sc_img = user.sfz_sc_img;
                }
            }
            if(this.readonly === '1') {
                this.disabled = true;
                this.status = false;
                this.btnName = '返回';
                this.left = '';
            }
        }
    }
</script>

<style scoped>

</style>