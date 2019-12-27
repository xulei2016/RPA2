<template>
    <layout title="佣金返还比例" >
        <div style="margin-top: 60px;">
            <van-divider>请选择佣金返还比例</van-divider>
            <van-cell-group>
                <van-field  placeholder="佣金返还比例(0-100)"  label="比例"  required />
            </van-cell-group>

            <div style="text-align: center">
                <van-button style="margin-top: 60px;width: 94%" type="info" @click="next()">下一步</van-button>
            </div>
        </div>
    </layout>
</template>

<script>
    export default {
        data() {
            return {

            }
        },
        methods: {

            next(){
                window.location.href = "/index/mediator/video";return false;
            }
        }
    }
</script>

<style scoped>

</style>