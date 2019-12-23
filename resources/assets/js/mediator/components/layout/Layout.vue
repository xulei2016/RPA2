<template>
    <div>
        <div >
            <van-nav-bar v-bind:title="title" fixed="fixed" />
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
            }
        }
    }
</script>

<style scoped>

</style>