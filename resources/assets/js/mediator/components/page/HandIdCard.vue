<template>
    <layout title="手持身份证照片" >
        <div style="text-align: center;margin-top: 60px;">
            <van-divider >请确保图像清晰、面部完整</van-divider>

            <van-uploader name="sign" upload-text="签名"  :after-read="afterRead" :before-read="beforeRead" :preview-image="true" >
                    <img :src="handIdCard" width="96%" alt="">
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
                handIdCard : "/images/index/mediator/hand_id_card.jpg",
            }
        },
        methods: {
            afterRead(file, detail) {
                // 此时可以自行将文件上传至服务器
                if(detail.handIdCard == 'sign') {
                    this.handIdCard = file.content
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
                window.location.href = "/index/mediator/agreement";return false;
            }
        }
    }
</script>

<style scoped>

</style>