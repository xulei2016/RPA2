<template>
    <layout title="居间人权利义务告知书">

        <div style="text-align: center; margin-top: 60px;">
            <van-divider>请仔细阅读以下协议</van-divider>
            <div class="article">
                <p>您好！如果您成为我公司居间人，我公司需要向您告知居间人的权利和义务：</p>
                <p class="article-title">一、居间人享有的权利</p>
                <p>（1）因从事居间活动付出的劳务，有权按协议约定从甲方获取居间服务报酬；</p>
                <p>（2）按照协议约定，可以从甲方获取有关的市场宣传资料和资讯信息；</p>
                <p class="article-title">二、居间人应履行的义务</p>
                <p>（3）乙方应严格遵守国家法律、法规和甲方的规章制度，忠实履行自己的中介义务，积极促成投资者与甲方签订期货合同或介绍购买金融产品，不得阻挠或妨碍投资者与甲方的签约活动，不得损害投资者的合法权益；</p>
                <p>（4）乙方应如实告知甲方自己的真实身份，接受甲方的资格审核，不得以其他期货公司的员工身份与甲方签约，乙方应为甲方保守商业机密，为投资者保守商业秘密和个人隐私，不得泄露或传递给他人；</p>
                <p>（5）乙方应向客户如实告知自己的居间身份，告知客户期货公司的情况、有关签订期货经纪合同或其他金融产品合同的事项，正确提示期货投资的风险与收益，不得进行虚假或误导性宣传、非法咨询、代客下单、提供交易软件等各种违法违规行为，不得做盈利保证，不得代客户签署合同等书面文件；不得接受客户全权委托办理资金调拨、交易下单、签署交易账单等；乙方有义务向甲方如实告知客户的有关情况；</p>
                <p>（6）乙方不得以甲方员工、受托人或其他身份代表甲方发表、签署任何合同、文件或承诺承担任何法律责任；不得以甲方工作人员的名义开展经营活动，不得利用甲方居间人身份变相非法集资或融资，不得以甲方居间人的名义从事期货居间以外的活动，不得为客户提供集中交易的场所，不得以期货公司的名义设立经营服务网点；</p>
                <p>（7）乙方不得以不正当手段损毁甲方及甲方其他业务人员、其他居间人及客户的名誉，不得扰乱甲方及其他业务人员的正常业务秩序，不得无故开发甲方和甲方其他业务人员的已有客户；</p>
                <p>（8）乙方促成客户与甲方签署期货经纪合同或购买金融产品后，应按甲方规定的时间和程序进行居间客户开发申报，原则上应在客户开户后或认购金融产品后的5个工作日内提交归属申请，否则甲方将不予认定；乙方应按甲方要求提供合法合规的与居间报酬相一致的税务发票和纳税证明，否则甲方不予支付居间报酬；</p>
                <p>（9）乙方承诺将甲方作为乙方在期货行业的唯一居间合作伙伴；</p>
                <p>（10）乙方应主动接受甲方的相关居间业务培训；</p>
                <p>（11）乙方应接受以甲方电话、短信以及邮件等方式的回访业务。</p>
                <p>以上告知书的内容，本人/单位已阅读并完全理解。</p>
                <br>
                <p class="article-footer mt10"><span class="mr4" style="display:inline-block;width: 35%;text-align: left"> 居间人:{{ khxm }}</span></p>
            </div>

            <div style="text-align: center">
                <van-button style="margin-top: 40px;width: 94%" type="info" @click="confirm()">{{btnName}}</van-button>
            </div>
        </div>
    </layout>
</template>

<script>
    export default {
        data() {
            return {
                btnName:'我已阅读',
                khxm:''
            }
        },
        props:{
            read:{
                type:String,
                default:'0'
            }
        },
        methods: {
            confirm(){
                if(this.read !== '1') {
                    let agreement = sessionStorage.getItem('agreement');
                    if(!agreement) agreement = '';
                    sessionStorage.setItem('agreement', agreement+'2');
                }
                history.go(-1);
                return false;
            }
        },
        created:function(){
            if(this.read === '1') {
                this.btnName = '返回';
            }
        }
    }
</script>

<style scoped>

</style>