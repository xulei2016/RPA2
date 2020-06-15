<template>
    <layout title="确认佣金返还比例" left="">
        <div style="margin-top: 60px;">
            <van-divider>请确认佣金返还比例</van-divider>
            <van-panel title="佣金返还比例" >
                <div style="height: 120px; text-align:center;">
                    <p>您已与客户经理协商统一返还比例！</p>
                    <p>佣金返还比例为 {{ realRate }}, 是否确认?</p>
                </div>
                <div slot="footer" style="text-align:right;">
                    <van-button size="small" @click="confirm(-1)">拒绝</van-button>
                    <van-button size="small" type="primary" @click="confirm(1)">确认</van-button>
                </div>
                <van-divider>温馨提示:<br>若对此有疑问，请咨询400-8820-628或者询问您的客户经理，谢谢!</van-divider>
            </van-panel>
        </div>
    </layout>
</template>

<script>
    export default {
        data() {
            return {
                realRate:''
            }
        },
        props:{
            rate:{
                type:String,
                default:''
            },
            special:{
                type:String,
                default:''
            },
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
        },
        created: function () {
            console.log(this.special);
            if(this.special == '1') { // 特殊比例  直接展示
                this.realRate = this.rate;
            } else {
                this.realRate = this.rate + "%"; // 整数比例  加%
            }
        }
    }
</script>

<style scoped>

</style>