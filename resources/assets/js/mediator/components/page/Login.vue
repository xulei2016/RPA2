<template>
    <div>
        <layout title="居间人认证" >
            <div style="margin-top: 100px;">
                <van-cell-group :border="fieldBorder">
                    <van-field  placeholder="请输入手机号" error-message="" v-model="phone" :error-message="errors['phone']" />
                </van-cell-group>
                <br>
                <van-cell-group :border="fieldBorder">
                    <van-row>
                        <van-col span="17">
                            <van-field  placeholder="请输入图片验证码"  error-message="" v-model="imageCode" :error-message="errors['imageCode']"  />
                        </van-col>
                        <van-col span="7">
                                <img :src="codeUrl" alt="" style="margin-top: 4px;" @click="getImageCode()" >
                        </van-col>
                    </van-row>
                </van-cell-group>
                <br>
                <van-cell-group :border="fieldBorder">
                    <van-field  placeholder="请输入手机验证码"  error-message="" v-model="phoneCode" :error-message="errors['phoneCode']"  >
                        <van-button slot="button" size="small" type="primary" @click="sendCode()">发送验证码</van-button>
                    </van-field>
                </van-cell-group>
                <div style="text-align: center">
                    <van-button style="margin-top: 60px;width: 94%" type="info" @click="login()">手机校验</van-button>
                </div>
            </div>

        </layout>

    </div>

</template>

<script>
    export default {
        data() {
            return {
                fieldBorder:true,
                list: [],
                loading: false,
                finished: false,
                codeUrl:'',
                phone:'',
                imageCode:'',
                phoneCode:'',
            };
        },
        vuerify: {
            'phone': {
                test: /^1\d{10}$/,
                message: '请输入正确的手机号'
            },
            'imageCode': {
                test: /\w{4,}/,
                message: '至少 4 位字符'
            },
            'phoneCode': {
                test: /\w{4,}/,
                message: '至少 4 位字符'
            }
        },
        props: {
            data: {
                type: String,
                default: ''
            }
        },
        computed: {
            errors () {
                return this.$vuerify.$errors
            }
        },
        methods: {
            sendCode(){
                let verifyList = ['phone', 'imageCode'];
                // check() 校验所有规则，参数可以设置需要校验的数组
                if(!this.$vuerify.check(verifyList)){
                    this.$toast("短信发送失败!");
                    return false;
                }
            },
            getImageCode(){
                Vue.api.getImageCode().then((res) => {
                    this.codeUrl = res
                }).catch(error => this.$toast(error))
            },
            login(){
                window.location.href = "/index/mediator/IDCard";return false;
                let verifyList = ['phone', 'imageCode', 'phoneCode'];
                // check() 校验所有规则，参数可以设置需要校验的数组
                if(!this.$vuerify.check(verifyList)){
                    this.$toast("登录失败");
                    return false;
                }

            }
        },
        created: function () {

            this.getImageCode();

        }
    }
</script>

<style scoped>

</style>