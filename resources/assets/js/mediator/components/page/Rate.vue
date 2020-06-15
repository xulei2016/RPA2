<template>
    <layout title="佣金返还比例" :left="left">
        <div style="margin-top: 60px;">
            <van-divider>请填写佣金返还比例</van-divider>
            <van-cell-group>
                <van-field v-model="form.rate" :readonly="disabled"  placeholder="佣金返还比例"  label="比例(单位:%)" :error-message="errors['form.rate']"  required />
            </van-cell-group>

            <div style="text-align: center">
                <van-button style="margin-top: 60px;width: 94%" type="info" @click="next()">{{btnName}}</van-button>
            </div>
        </div>
    </layout>
</template>

<script>
    export default {
        data() {
            return {
                btnName:'下一步',
                left:'返回',
                disabled:false,
                form:{
                    rate: '',
                    func:'rate'
                }
            }
        },
        props:{
            data:{
                type:String,
                default:''
            },
            readonly: {
                type: String,
                default: '0'
            }
        },
        vuerify: {
            'form.rate': {
                test: /^\d{1,2}$/,
                message: '请输入正确的返还比例'
            }
        },
        computed: {
            errors () {
                return this.$vuerify.$errors
            }
        },
        methods: {
            next(){
                if(this.readonly === '1') {
                    history.go(-1);
                    return false;
                }
                let verifyList = ['form.rate'];
                // check() 校验所有规则，参数可以设置需要校验的数组
                if(!this.$vuerify.check(verifyList)){
                    this.$toast(this.errors["form.rate"]);
                    return false;
                }
                Vue.api.doInfo(this.form).then(res => {
                    Vue.utils.next();
                }).catch(error => this.$toast(error));
            }
        },
        created:function(){
            let read = this.readonly;
            let info = JSON.parse(this.data);
            if(read === '1') {
                this.btnName = '返回';
                this.disabled = true;
                this.form.rate = info.data.rate;
                this.left = '';
            }
        }
    }
</script>

<style scoped>

</style>