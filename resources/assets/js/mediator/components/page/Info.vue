<template>
    <layout title="信息查看" >
        <div style="margin-top: 60px;">
            <van-divider>点击查看相关信息</van-divider>

            <van-cell v-for="(item, key) in infoList" :title="item.name" :key="key"  is-link  :url="joinUrl(item.component)" />

            <van-cell  title="用户协议" key="xy"  is-link  :url="joinUrl('showAgreement')" />


            <van-divider></van-divider>

            <div style="text-align: center">
                <van-button style="margin-top: 50px;width: 94%" type="info" @click="next()">返回</van-button>
            </div>
        </div>
    </layout>
</template>

<script>
    export default {
        data() {
            return {
                infoList:[]
            }
        },
        props:{
            data:{
                type:String,
                default:''
            }
        },
        methods:{
            next(){
                history.go(-1);
            },
            joinUrl(url){
                return '/index/mediator/infoDetail?component='+url;
            }
        },
        created:function(){
            this.infoList =  JSON.parse(this.data);
        }

    }
</script>

<style scoped>

</style>