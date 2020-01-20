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
                <van-button v-if="!showNext" style="margin-top: 30px;width: 94%" type="info" @click="nextReview()">下一题</van-button>
                <van-button v-if="showNext" style="margin-top: 30px;width: 94%" type="info" @click="next()">下一步</van-button>
            </div>
        </div>
    </layout>
</template>

<script>
    import {Dialog} from 'vant';
    export default {
        data() {
            return {
                total:12,
                current:1,
                answer:"",
                result:[],
                list: [],
                showNext:false,
                form:{
                    func:'review',
                    is_answer: 1

                }
            }
        },
        methods:{
            nextReview(){
                if(!this.answer) {
                    this.$toast('请选择一项答案');
                    return false;
                }

                let id = this.$refs.list[0].getAttribute('itemid');
                this.result.push({id:id,option:this.answer});
                if(this.current === this.total-1) {
                    this.showNext = true;
                }
                this.current++;
                this.answer = '';

            },
            next(){
                if(!this.answer) {
                    this.$toast('请选择一项答案');
                    return false;
                }
                if(this.result.length !== this.total) {
                    let id = this.$refs.list[0].getAttribute('itemid');
                    this.result.push({id:id,option:this.answer});
                }

                Vue.api.checkReview({data:JSON.stringify(this.result)}).then(res => {
                    if(res.length) {
                        Dialog.alert({
                            title: '提示',
                            message: '第'+res.join(', ')+'题回答错误,请重新答题!',
                        }).then(() => {
                            window.location.reload();
                        });

                        return false;
                    } else {
                        Vue.api.doInfo(this.form).then(res => {
                            this.$toast.success('保存成功');
                            Vue.utils.next();
                        }).catch(error => this.$toast(error));
                    }

                }).catch(error => {this.$toast(error)})
            }
        },
        created: function () {
            Vue.api.getReviewList().then(res => {
                this.list = res;
                this.total = this.list.length;
            }).catch(error => {
                this.$toast(error);
            })
        }
    }
</script>

<style scoped>

</style>