<template>
    <layout title="申请状态">
        <div style="margin-top: 60px;">
            <van-divider>申请状态</van-divider>
            <div v-if="status === '1'" style="text-align: center;font-size: 1.2rem;font-weight: bold;">
                申请完成
                <br><br>
                请耐心等待
            </div>
            <div v-if="status === '2'" style="text-align: center;font-size: 1.2rem;font-weight: bold;">
                申请被打回<br><br>
                {{ back }}
                <br><br>有误
                <div style="text-align: center">
                    <van-button style="margin-top: 40px;width: 94%" type="info" @click="handle()">点击处理</van-button>
                </div>
            </div>
            <div v-if="status === '3'" style="text-align: center;font-size: 1.2rem;font-weight: bold;">
                办理成功<br><br>
                <div style="text-align: center">
                    <van-button style="margin-top: 40px;width: 94%" type="info" @click="goPanel()">前往首页</van-button>
                </div>
        </div>
        </div>
    </layout>
</template>

<script>
    export default {
        props:{
            back:{
                type:String,
                default:''
            },
            status:{
                type:String,
                default: '1'
            },
        },
        methods:{
            handle(){
                Vue.utils.next(true);
            },
            goPanel(){
                Vue.utils.panel();
            }
        },
        created:function(){
        }
    }
</script>

<style scoped>

</style>