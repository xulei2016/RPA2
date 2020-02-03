<template>
    <layout title="确认佣金返还比例">
        <div style="margin-top: 60px;">
            <van-divider>请确认佣金返还比例</van-divider>
            <van-panel title="佣金返还比例" >
                <div style="height: 120px; text-align:center;">
                    <p>您已与客户经理协商统一返还比例！</p>
                    <p>佣金返还比例为 {{ rate }}%, 是否确认?</p>
                </div>
                <div slot="footer" style="text-align:right;">
                    <van-button size="small" @click="confirm(-1)">拒绝</van-button>
                    <van-button size="small" type="primary" @click="confirm(1)">确认</van-button>
                </div>
                <van-divider>温馨提示:<br>若对此有疑问，请咨询0551-62839083或者询问您的客户经理，谢谢!</van-divider>
            </van-panel>
        </div>
    </layout>
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        props:{
            rate:{
                type:String,
                default:''
            }
        },
        methods:{
            confirm:function(par){
                Vue.api.doConfirmRate(par).then(res => {
                    this.$toast.success('保存成功');
                    if(par === -1) {
                        setTimeout(function(){
                            Vue.utils.goLogin();
                        }, 500);
                    } else {
                        Vue.utils.next();
                    }
                }).catch(error => {this.$toast(error)});
            }
        }
    }
</script>

<style scoped>

</style>