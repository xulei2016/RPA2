<template>
    <layout title="视频培训" >
        <div style="margin-top: 60px;">
            <van-divider>请仔细观看居间培训视频</van-divider>
            <video
                    controls
                    ref="myVideo" id="myVideo" onplay="Vue.videoPlay()" onended="Vue.videoEnd()" poster="http://www.haqh.com/oa2/Public/image/jjrpx.png"
                   class="video-js vjs-default-skin vjs-big-play-centered vjs-16-9" data-setup='{}' style='width: 100%;'>
                <source id="source" src="http://cdn.hatzjh.com/20190801TDEtVOvi.m3u8" type="application/x-mpegURL"></source>
            </video>
            <div style="text-align: center;">
                <van-button style="margin-top: 60px;width: 94%" type="primary" @click="click()">{{btnName}}</van-button>
                <van-button style="margin-top: 30px;width: 94%" type="info" @click="next()" v-if="showNext">下一步</van-button>
            </div>
        </div>
    </layout>
</template>

<script>
    import Video from 'video.js'
    import 'video.js/dist/video-js.css'
    Vue.prototype.$video = Video;
    export default {
        data() {
            return {
                showNext:false, // 是否显示下一步
                btnName:"点击播放",
                play:"onPlay()",
                form:{
                    is_video: 1,
                    func:'video'
                }
            }
        },
        methods: {
            onPlay(){
                console.log(131);
            },
            end(){
                console.log(222);
            },
            click(){
                document.querySelector("#myVideo button").click();
            },
            next(){
                Vue.api.doInfo(this.form).then(res => {
                    this.$toast.success('保存成功');
                    Vue.utils.next();
                }).catch(error => this.$toast(error));
            },
        },
        mounted:function(){
            console.log(this.$refs);
        },
        created:function(){
            Vue.videoEnd = () => {
                this.showNext = true;
            }
            Vue.videoPlay = () => {
                this.btnName = '正在播放';
            }

        }

    }
</script>

<style scoped>
</style>