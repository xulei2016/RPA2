<template>
    <layout title="上传身份证照片" :left="left" >
        <div style="text-align: center; margin-top: 60px;" >
            <van-divider>请确保身份证在有效期内,四边完整,字迹清晰</van-divider>
            <van-divider v-if="status">默认显示之前上传的图片,点击图片可以重新上传</van-divider>
            <van-uploader name="sfz_zm_img" upload-text="身份证正面" :disabled="disabled" :after-read="afterRead" :before-read="beforeRead" :preview-image="true" >
                <img :src="sfz_zm_img" width="96%" alt="">
            </van-uploader>
            <br>
            <br>
            <van-uploader name="sfz_fm_img" upload-text="身份证反面" :disabled="disabled" :after-read="afterRead" :before-read="beforeRead" >
                <img :src="sfz_fm_img" width="96%" alt="">
            </van-uploader>

            <div style="text-align: center">
                <van-button style="margin-top: 25px;width: 94%" type="info" @click="next()">{{btnName}}</van-button>
            </div>
        </div>

    </layout>
</template>

<script>
    import { Uploader,Dialog } from 'vant';
    Vue.use(Uploader).use(Dialog);
    export default {
        data() {
            return {
                btnName:'下一步',
                disabled:false,
                status:false,
                left:'返回',
                sfz_zm_img : "/images/index/mediator/sfz_zm.png",
                sfz_fm_img : "/images/index/mediator/sfz_fm.png",
                form:{
                    sfz_zm_img : "",
                    sfz_fm_img : "",
                    name: '',
                    zjbh: '',
                    sex: '',
                    sfz_address: '',
                    birthday: '',
                    sfz_date_end: '',
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
                    if(res.data.status === 'non_idcard') { // 不是一个身份证
                        Dialog.confirm({
                            title: '提示',
                            message: '系统无法正确识别您的证件，请确认是您本人的证件照或请在光线明亮的条件下拍摄，继续提交，可能会被打回哦。',
                            confirmButtonText: '继续提交',
                            cancelButtonText: '重新拍摄',
                        }).then(() => {
                            if(type === 'sfz_zm_img') {
                                this.sfz_zm_img = file.content;
                                this.form.sfz_zm_img = res.path;
                                this.form.name = res.data.name;
                                this.form.zjbh = res.data.zjbh;
                                this.form.sex = res.data.sex;
                                this.form.sfz_address = res.data.sfz_address;
                                this.form.birthday = res.data.birthday;

                            } else {
                                this.sfz_fm_img = file.content;
                                this.form.sfz_fm_img = res.path;
                                this.form.sfz_date_end = res.data.sfz_date_end;
                            }
                        }).catch(() => {
                            return false;
                        });
                    } else {
                        if(type === 'sfz_zm_img') {
                            this.sfz_zm_img = file.content;
                            this.form.sfz_zm_img = res.path;
                            this.form.name = res.data.name;
                            this.form.zjbh = res.data.zjbh;
                            this.form.sex = res.data.sex;
                            this.form.sfz_address = res.data.sfz_address;
                            this.form.birthday = res.data.birthday;
                        } else {
                            this.sfz_fm_img = file.content;
                            this.form.sfz_fm_img = res.path;
                            this.form.sfz_date_end = res.data.sfz_date_end;
                        }
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
                    Vue.utils.next();
                }).catch(error => this.$toast(error));
            }
        },
        created:function(){
            let info = JSON.parse(this.data);
            if(info.status === 1) {
                this.status = true;
                let user = info.data;

                if(user.sfz_zm_img_base64) {
                    this.form.sfz_zm_img = user.sfz_zm_img;
                    this.sfz_zm_img = user.sfz_zm_img_base64;
                }

                if(user.sfz_fm_img_base64) {
                    this.sfz_fm_img = user.sfz_fm_img_base64;
                    this.form.sfz_fm_img = user.sfz_fm_img;
                }



                this.form.name = user.name;
                this.form.zjbh = user.zjbh;
                this.form.sex = user.sex;
                this.form.sfz_address = user.sfz_address;
                this.form.birthday = user.birthday;
                this.form.sfz_date_end = user.sfz_date_end;
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