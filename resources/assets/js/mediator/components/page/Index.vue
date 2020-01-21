<template>
    <layout title="首页" >
        <div style="margin-top: 60px;">
            <van-grid :column-num="1" :gutter="20">
                <van-grid-item
                        icon="orders-o"
                        text="新签协议"
                        @click="click('new')"
                />

                <van-grid-item
                        icon="orders-o"
                        text="续签协议"
                        @click="click('re')"
                />

                <van-grid-item
                        icon="user-o"
                        text="信息查看"
                        @click="click('info')"
                />
            </van-grid>


        </div>

    </layout>
</template>

<script>
    export default {
        props:{
            status:{
                type: String,
                default: 0
            }
        },
        methods:{
            click(url){
                if(url === 'new') {
                    if(this.status.indexOf('1') > -1) {
                        window.location.href = '/index/mediator/panel?status=1';
                    } else {
                        this.$toast('您暂时无法新签');
                    }
                    return false;
                } else if(url === 're') {
                    if(this.status.indexOf('2') > -1) {
                        window.location.href = '/index/mediator/panel?status=2';
                    } else {
                        this.$toast('您暂时无法续签');
                    }
                    return false;
                } else if(url === 'info') {
                    if(this.status.indexOf('0') > -1) {
                        window.location.href = '/index/mediator/info';
                    } else {
                        this.$toast('您无法查看信息');
                    }
                    return false;
                }
                console.log(url)
            }
        },
        created:function(){
            console.log(this.status)
        }
    }
</script>

<style scoped>

</style>