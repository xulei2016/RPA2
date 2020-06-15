<template>
    <div>
        <div >
            <van-nav-bar v-bind:left-text="left" v-bind:title="title" fixed="fixed" @click-left="onClickLeft" />
        </div>
        <div style="margin-top: 46px;">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Layout",
        data() {
            return {
                fixed:true
            };
        },
        props: {
            title: {
                type: String,
                default: '居间人认证'
            },
            left: {
                type:String,
                default: '返回'
            }
        },
        methods: {
            onClickLeft() {
               Vue.utils.goBack();
            }
        }
    }
</script>

<style scoped>

</style>