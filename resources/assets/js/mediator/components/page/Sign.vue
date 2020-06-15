<template>
    <layout title="上传签名" :left="left" >
        <div style="text-align: center;margin-top: 60px;">
            <van-divider >请确保签名为本人名字,图片、字迹清晰</van-divider>
            <van-divider v-if="status">默认显示之前上传的图片,点击图片可以重新上传</van-divider>
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
                left:'返回',
                disabled:false,
                status:false,
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
                let maxSize = 200*1024; // 最大200k
                let size = file.file.size;
                if (size > maxSize) {
                    let canvas = document.createElement('canvas'); // 创建Canvas对象(画布)
                    let context = canvas.getContext('2d');
                    let img = new Image();
                    img.src = file.content; // 指定图片的DataURL(图片的base64编码数据)
                    img.onload = () => {
                        let width = img.width;
                        let height = img.height;
                        let rate = width/height.toFixed(2);
                        let baseHeight = 500;
                        canvas.width = baseHeight*rate;
                        canvas.height = baseHeight;
                        context.drawImage(img, 0, 0, baseHeight*rate, baseHeight);
                        file.content = canvas.toDataURL(file.file.type, 0.98); // 0.92为默认压缩质量
                        let files = Vue.utils.dataURLtoFile(file.content, file.file.name);
                        if(files.size < 1024) { // 压缩完图片小于1k 上传原图
                           files = file.file;
                        }
                        Vue.api.uploadFile(files, 'sign_img').then(res => {
                            if(type === 'sign_img') {
                                this.sign_img = file.content;
                                this.form.sign_img = res.path
                            }
                            this.$toast.clear();
                        }).catch(error => this.$toast(error));
                        return true;
                    }
                } else {
                    Vue.api.uploadFile(file.file, 'sign_img').then(res => {
                        if(type === 'sign_img') {
                            this.sign_img = file.content;
                            this.form.sign_img = res.path
                        }
                        this.$toast.clear();
                    }).catch(error => this.$toast(error));
                }
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
                    Vue.utils.next();
                }).catch(error => this.$toast(error));
            }
        },
        created:function () {
            let info = JSON.parse(this.data);
            if(info.status === 1) {
                this.status = true;
                let user = info.data;
                if(user.sign_img_base64) {
                    this.form.sign_img = user.sign_img;
                    this.sign_img = user.sign_img_base64;
                }

            }
            if(this.readonly === '1') {
                this.btnName = '返回';
                this.status = false;
                this.disabled = true;
                this.left = '';
            }
        }
    }
</script>

<style scoped>

</style>