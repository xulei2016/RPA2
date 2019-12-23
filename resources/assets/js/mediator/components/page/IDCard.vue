<template>
    <layout title="上传身份证照片" >
        <div style="text-align: center; margin-top: 60px;">
            <van-divider>请确保身份证在有效期内,四边完整,字迹清晰</van-divider>
            <van-uploader name="sfz_zm" upload-text="身份证正面"  :after-read="afterRead" :before-read="beforeRead" :preview-image="true" >
                <img :src="sfz_zm" width="96%" alt="">
            </van-uploader>
            <br>
            <br>
            <van-uploader name="sfz_fm" upload-text="身份证反面"  :after-read="afterRead" :before-read="beforeRead" >
                <img :src="sfz_fm" width="96%" alt="">
            </van-uploader>

            <div style="text-align: center">
                <van-button style="margin-top: 60px;width: 94%" type="info" @click="next()">下一步</van-button>
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
                sfz_zm : "/images/index/mediator/sfz_zm.png",
                sfz_fm : "/images/index/mediator/sfz_fm.png",
            }
        },
        methods: {
            afterRead(file, detail) {
                console.log(file);
                // 此时可以自行将文件上传至服务器
                if(detail.name == 'sfz_zm') {
                    this.sfz_zm = file.content
                } else {
                    this.sfz_fm = file.content
                }
                return true;
            },
            beforeRead(file) {
                if (file.type !== 'image/jpeg' && file.type !== 'image/png') {
                    this.$toast('请上jpg或者png图片');
                    return false;
                }

                return true;
            },
            next(){
                window.location.href = "/index/mediator/perfectInformation";return false;
            }
        }
    }
</script>

<style scoped>

</style>