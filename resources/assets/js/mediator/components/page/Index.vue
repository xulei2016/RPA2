<template>
    <layout title="首页" left="" >
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
            },
            message:{
                type: String,
                default: ""
            }
        },
        methods:{
            click(url){
                if(url === 'new') {
                    if(this.status.indexOf('1') > -1) {
                        window.location.href = '/mediator/panel?status=1';
                    } else {
                        this.$toast(this.message);
                    }
                    return false;
                } else if(url === 're') {
                    if(this.status.indexOf('2') > -1) {
                        window.location.href = '/mediator/panel?status=2';
                    } else {
                        this.$toast(this.message);
                    }
                    return false;
                } else if(url === 'info') {
                    if(this.status.indexOf('0') > -1) {
                        window.location.href = '/mediator/info';
                    } else {
                        this.$toast(this.message);
                    }
                    return false;
                }
            }
        },
        created:function(){
        }
    }
</script>

<style scoped>

</style>