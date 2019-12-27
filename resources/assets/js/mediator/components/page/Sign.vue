<template>
    <layout title="上传签名" >
        <div style="text-align: center;margin-top: 60px;">
            <van-divider >请确保签名为本人名字,图片、字迹清晰</van-divider>

            <van-uploader name="sign" upload-text="签名"  :after-read="afterRead" :before-read="beforeRead" :preview-image="true" >
                    <img :src="sign" width="96%" alt="">
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
                sign : "/images/index/mediator/sign.png",
            }
        },
        methods: {
            afterRead(file, detail) {
                // 此时可以自行将文件上传至服务器
                if(detail.name == 'sign') {
                    this.sign = file.content
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
                window.location.href = "/index/mediator/bankCard";return false;
            }
        }
    }
</script>

<style scoped>

</style>