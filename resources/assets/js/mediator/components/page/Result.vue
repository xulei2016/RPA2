<template>
    <layout title="申请状态" left="">
        <div style="margin-top: 100px;">
            <div v-if="status === '1'" style="text-align: center;font-size: 1.2rem;font-weight: bold;">
                <svg t="1582697418163" class="icon" viewBox="0 0 1118 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2087" width="100" height="100">
                    <path d="M546.02916697 504.265625m-412.5 0a412.5 412.5 0 1 0 825 0 412.5 412.5 0 1 0-825 0Z" fill="#FFFFFF" p-id="2088"></path><path d="M558.45104197 32C293.37291697 32 78.45104197 246.921875 78.45104197 512s214.921875 480 480 480 480-214.921875 480-480S823.52916697 32 558.45104197 32z m-60.234375 785.390625C416.53697947 713.796875 320.32604197 634.9296875 253.06041697 610.203125l158.671875-98.671875L488.60729197 664.4609375s125.0390625-310.78125 322.1484375-414.375c-4.8046875 73.9453125-24.0234375 138.046875 9.609375 217.03125-86.6015625 19.8046875-264.4921875 241.7578125-322.1484375 350.2734375z m0 0" fill="#00B362" p-id="2089"></path></svg>
                <br><br>
                申请完成
                <br><br>
                请耐心等待
            </div>
            <div v-if="status === '2'" style="text-align: center;font-size: 1.2rem;font-weight: bold;">
                <svg t="1582697619963" class="icon" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2823" width="100" height="100">
                    <path d="M512 0C229.23 0 0 229.23 0 512s229.23 512 512 512 512-229.23 512-512S794.77 0 512 0z m44 256v339.492c0 24.301-19.7 44-44 44s-44-19.699-44-44V256c0-24.3 19.7-44 44-44s44 19.7 44 44z m-44 428c35.346 0 64 28.654 64 64 0 35.346-28.654 64-64 64-35.346 0-64-28.654-64-64 0-35.346 28.654-64 64-64z" fill="#FFA300" p-id="2824"></path></svg>
                <br><br>
                申请被打回<br><br>
                {{ back }}
                <br><br>步骤有误
                <div style="text-align: center">
                    <van-button style="margin-top: 40px;width: 94%" type="info" @click="handle()">点击处理</van-button>
                </div>
            </div>
            <div v-if="status === '3'" style="text-align: center;font-size: 1.2rem;font-weight: bold;">
                <svg t="1582697418163" class="icon" viewBox="0 0 1118 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="2087" width="100" height="100">
                    <path d="M546.02916697 504.265625m-412.5 0a412.5 412.5 0 1 0 825 0 412.5 412.5 0 1 0-825 0Z" fill="#FFFFFF" p-id="2088"></path><path d="M558.45104197 32C293.37291697 32 78.45104197 246.921875 78.45104197 512s214.921875 480 480 480 480-214.921875 480-480S823.52916697 32 558.45104197 32z m-60.234375 785.390625C416.53697947 713.796875 320.32604197 634.9296875 253.06041697 610.203125l158.671875-98.671875L488.60729197 664.4609375s125.0390625-310.78125 322.1484375-414.375c-4.8046875 73.9453125-24.0234375 138.046875 9.609375 217.03125-86.6015625 19.8046875-264.4921875 241.7578125-322.1484375 350.2734375z m0 0" fill="#00B362" p-id="2089"></path></svg>
                <br><br>
                办理成功<br><br>
                <div style="text-align: center">
                    <van-button style="margin-top: 40px;width: 94%" type="info" @click="goPanel()">个人中心</van-button>
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