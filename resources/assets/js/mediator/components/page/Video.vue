<template>
    <layout title="视频培训" >
        <div style="margin-top: 60px;">
            <van-divider>请仔细观看居间培训视频</van-divider>
            <video ref="myVideo" id="myVideo" @click="click()"  poster="http://www.haqh.com/oa2/Public/image/jjrpx.png"
                   class="video-js vjs-default-skin vjs-big-play-centered vjs-16-9" data-setup='{}' style='width: 100%;'>
                <source id="source" src="http://cdn.hatzjh.com/20190801TDEtVOvi.m3u8" type="application/x-mpegURL"></source>
            </video>
            <div style="text-align: center;">
                <van-button style="margin-top: 60px;width: 94%" type="primary" @click="click()">点击播放</van-button>
                <van-button style="margin-top: 30px;width: 94%" type="info" @click="next()">下一步</van-button>
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
            }
        },
        methods: {
            click(){
                this.initVideo();
            },
            next(){
                window.location.href = "/index/mediator/review";return false;
            },
            initVideo() {

                //初始化视频方法
                let myPlayer = this.$video('myVideo', {
                    //确定播放器是否具有用户可以与之交互的控件。没有控件，启动视频播放的唯一方法是使用autoplay属性或通过Player API。
                    controls: true,
                    //自动播放属性,muted:静音播放
                    autoplay: "muted",
                    //建议浏览器是否应在<video>加载元素后立即开始下载视频数据。
                    preload: "auto",
                });
                myPlayer.play();
            }
        }

    }
</script>

<style scoped>

</style>