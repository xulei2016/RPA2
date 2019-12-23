<template>
    <layout title="在线测评" >
        <div style="margin-top: 60px;">
            <van-divider>一共{{total}}题, 当前为第{{current}}题</van-divider>
            <div v-for="(item, key) in list" :key="key" v-if="current == (key+1)" ref="list" :itemid="item.id">
                <van-panel :title="item.title" >
                    <van-radio-group v-model="answer">
                        <van-cell-group >
                            <van-cell v-for="(it, index) in item.data" :key="index" :title="it.value" clickable :item="it.key" @click="answer = it.key">
                                <van-radio slot="right-icon" :name="it.key" />
                            </van-cell>
                        </van-cell-group>
                    </van-radio-group>
                </van-panel>
            </div>

            <div style="text-align:center">
                <van-button style="margin-top: 30px;width: 94%" type="info" @click="nextReview()">下一题</van-button>
            </div>
        </div>
    </layout>
</template>

<script>
    export default {
        data() {
            return {
                total:12,
                current:1,
                answer:"",
                result:{},
                list: [
                    {id:11,title:"1.当客户发生纠纷时,居间人应当()", data:[
                        {key:"A",value:'告知客户改问题与自己无关'},
                        {key:"B",value:'刻意隐瞒事实真相'},
                        {key:"C",value:'配合期货公司解决纠纷'},
                        {key:"D",value:'逃避、拒绝与期货公司的联系'}
                        ]
                    },
                    {id:12,title:"2.当客户发生纠纷时,居间人应当()", data:[
                            {key:"A",value:'告知客户改问题与自己无关'},
                            {key:"B",value:'刻意隐瞒事实真相'},
                            {key:"C",value:'配合期货公司解决纠纷'},
                            {key:"D",value:'逃避、拒绝与期货公司的联系'}
                        ]
                    },
                ]

            }
        },
        methods:{
            nextReview(){
                if(!this.answer) {
                    this.$toast('请选择一项答案');
                    return false;
                }

                let id = this.$refs.list[0].getAttribute('itemid');

                if(this.current === this.total) {
                    console.log('结束');
                    return false;
                }
                this.current++;
                this.answer = '';
            },
            next(){

            }
        },
        created: function () {
            this.total = this.list.length;
        }
    }
</script>

<style scoped>

</style>