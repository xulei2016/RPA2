<template>
    <layout title="银行卡信息" >
        <div style="text-align: center;margin-top: 60px;">
            <van-divider>推荐使用工商银行等大型银行</van-divider>
            <van-uploader  name="yhk" upload-text="银行卡"  :after-read="afterRead" :before-read="beforeRead" >
                <img :src="yhk" width="96%" alt="">
            </van-uploader>

            <van-divider>基本信息</van-divider>

            <van-cell-group>
                <van-field  placeholder="选择银行"  label="选择银行"  required />
            </van-cell-group>

            <van-cell-group>
                <van-field  placeholder="银行网点"  label="银行网点"  required />
            </van-cell-group>

            <van-cell-group>
                <van-field  placeholder="银行户名"  label="银行户名"  required />
            </van-cell-group>

            <van-cell-group>
                <van-field  placeholder="银行卡号"  label="银行卡号"  required />
            </van-cell-group>
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
                yhk : "/images/index/mediator/yhk.png",
            }
        },
        methods: {
            afterRead(file, detail) {
                // 此时可以自行将文件上传至服务器
                if(detail.name == 'yhk') {
                    this.yhk = file.content
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
                window.location.href = "/index/mediator/handIdCard";return false;
            }
        }
    }
</script>

<style scoped>

</style>