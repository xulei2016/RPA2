<template>
    <layout title="签署协议" >
        <div style="margin-top: 60px;">
            <van-divider>请仔细阅读以下协议</van-divider>
            <van-cell title="《居间协议书》" is-link />
            <van-cell title="《居间人权利义务告知书》" is-link />
            <van-cell title="《居间人自律承诺书》" is-link />
            <van-divider></van-divider>
            <van-checkbox style="margin-left:4%" shape="square" v-model="checked">我已认真阅读并同意上述全部协议</van-checkbox>
            <div style="text-align: center">
                <van-button style="margin-top: 50px;width: 94%" type="info" @click="next()">下一步</van-button>
            </div>
        </div>
    </layout>
</template>

<script>
    export default {
        data() {
            return {
                checked:false
            }
        },
        methods: {

            next(){
                window.location.href = "/index/mediator/rate";return false;
            }
        }
    }
</script>

<style scoped>

</style>