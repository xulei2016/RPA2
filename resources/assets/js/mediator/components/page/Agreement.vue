<template>
    <layout title="签署协议" >
        <div style="margin-top: 60px;">
            <van-divider>请仔细阅读以下协议</van-divider>
            <van-cell title="《居间协议书》" class="no" :value="xy1" is-link :url="joinUrl(1)" />
<!--            <van-cell title="《居间人权利义务告知书》" :value="xy2" is-link  :url="joinUrl(2)" />-->
            <van-cell title="《居间人自律承诺书》" :value="xy3" is-link :url="joinUrl(3)" />
            <van-divider></van-divider>
            <van-checkbox style="margin-left:4%" shape="square" v-if="!disabled"  v-model="checked">我已认真阅读并同意上述全部协议</van-checkbox>
            <div style="text-align: center">
                <van-button style="margin-top: 50px;width: 94%" type="info" @click="next()">{{btnName}}</van-button>
            </div>
        </div>
    </layout>
</template>

<script>
    export default {
        data() {
            return {
                btnName:'下一步',
                disabled:false,
                checked:false,
                xy1: '未阅读',
                xy2: '',
                xy3: '未阅读',
                form:{
                    func:'agreement'
                }
            }
        },
        props:{
             readonly:{
                 type:String,
                 default:'0'
             }
        },
        methods: {
            next(){
                if(this.readonly === '1') {
                    history.go(-1);
                    return false;
                }
                if(!this.checked) {
                    this.$toast('请先阅读并同意上述全部协议!');
                    return false;
                }

                if(!this.xy1 || !this.xy3) {
                    this.$toast('请先阅读并同意上述全部协议!');
                    return false;
                }

                Vue.api.doInfo(this.form).then(res => {
                    this.$toast.success('保存成功');
                    Vue.utils.next();
                }).catch(error => this.$toast(error));
            },
            joinUrl(url){
                let u = "/index/mediator/agreementDetail/"+url;
                if(this.disabled) {
                    u += '?read=1'
                }
                return u;
            }
        },
        created:function(){
            let read = this.readonly;
            if(read === '1') {
                this.disabled = true;
                this.xy1 = '';
                this.xy2 = '';
                this.btnName = '返回';
            } else {
                let agreement = sessionStorage.getItem('agreement');
                let time = sessionStorage.getItem('agreementTime');
                let current = Math.ceil(((new Date()).getTime())/1000); // 当前时间
                if(time && (current-time < 3600*4 )) {

                } else {
                    agreement = '';sessionStorage.setItem('agreement', '');
                    sessionStorage.setItem('agreementTime', current);
                }
                if(agreement) {
                    if(agreement.indexOf('1') > -1) {
                        this.xy1 = '已阅读'
                    } else {
                        this.xy1 = '';
                    }
                    // if(agreement.indexOf('2') > -1) {
                    //     this.xy2 = '已同意'
                    // } else {
                    //     this.xy2 = '';
                    // }
                    if(agreement.indexOf('3') > -1) {
                        this.xy3 = '已阅读'
                    } else {
                        this.xy3 = '';
                    }
                }
            }


        }
    }
</script>

<style scoped>

</style>