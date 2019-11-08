@component('admin.widgets.editForm')
    @slot('formContent')
        {{--播放器--}}
        <div id="video" style="width:600px;height:400px;"></div>
        <div style="position:absolute;top:20px;right:40px;">
            <!-- <button type="button" class="btn btn-default" onclick="player.videoZoom(1)">默认大小</button><br><br> -->
			<button type="button" class="btn btn-default" onclick="changeZoom('+')"><i class="fa fa-search-plus"></i> 放大</button><br><br>
			<button type="button" class="btn btn-default" onclick="changeZoom('-')"><i class="fa fa-search-minus"></i> 缩小</button><br><br>
            <button type="button" class="btn btn-success" onclick="player.videoRotation(1)"><i class="fa fa-rotate-right"></i> 顺时针旋转</button><br><br>
            <button type="button" class="btn btn-success" onclick="player.videoRotation(-1)"><i class="fa fa-rotate-left"></i> 逆时针旋转</button>
        </div>
        {{--视频列表--}}
        @if(json_decode($customer->jsondata ,true))
            <div class="list">
            @foreach(json_decode($customer->jsondata ,true) as $v)
                @if( $v['state'] == 1)
                    <span style="margin:20px 10px;" class="changeV btn btn-info" onclick="changeVideo(this,'{{ $v["path"] }}' );">{{ $v['filename'] }}</span>
                @endif
            @endforeach
            </div>
        @endif
        {{--审核表单--}}
        <div class="form-group row">
            <label for="implement_type" class="col-sm-2 control-label"><span class="must-tag">*</span>审核状态</label>
            <div class="col-sm-10">
                <input type="checkbox" class="my-switch" id="implement_type" name="status" value="1" checked>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-12 row rowlist">
                <label for="reason" class="col-sm-2 control-label">视频备注</label>
                <div class="col-sm-10">
                @foreach(json_decode($customer->jsondata ,true) as $k=>$v)
                    @if( $v['state'] == 1)
                        <input type="text" class="form-control" name="newRemark_{{$k}}" value="{{ $v['remark'] }}">
                    @endif
                @endforeach
                </div>
            </div>

            <div class="col-sm-12 row rowlist hidden">
                <label for="reason" class="col-sm-2 control-label">打回原因</label>
                <div class="col-sm-10">
                    <textarea id="reason" name="reason" cols="60" rows="3"></textarea>
                </div>
            </div>
        </div>
        <input type="hidden" class="form-control" id="id" name="id" value="{{$customer->id}}">
    @endslot

    @slot('formScript')
        <script src="{{URL::asset('/js/admin/func/CustomerVideos/check.js')}}"></script>
        <script type="text/javascript" src="/include/ckplayer/ckplayer/ckplayer.js" charset="UTF-8"></script>
		<script type="text/javascript">
			var videoObject = {
				container: '#video', //容器的ID或className
				variable: 'player', //播放函数名称
				autoplay: false,//是否自动播放
				// flashplayer:true,
				// live:true,
				debug:false,
                video:'',
            };
            var zoom = 1;
            var player = new ckplayer(videoObject);
            //加载第一个视频
            $(".list span:first").click();
            //缩放
            function changeZoom(type){
                if(type == '+'){
                    if(zoom >= 2){
                        alert('已经是最大了！')
                        return;
                    }else{
                        player.videoZoom(zoom+=0.1);
                    }
                }else{
                    if(zoom <= 0.1){
                        alert('已经是最小了！')
                        return;
                    }else{
                        player.videoZoom(zoom-=0.1)
                    }
                }

            }
            //切换视频
            function changeVideo(obj,videoUrl) {
                //修改按钮样式
                $(obj).removeClass('btn-info');
                $(obj).addClass('btn-primary');
                $(obj).siblings().removeClass('btn-primary');
                $(obj).siblings().addClass('btn-info');

				if(player == null) {
					return;
				}

				var newVideoObject = {
					container: '#video', //容器的ID
                    variable: 'player',
                    // live:true,
                    autoplay: true, //是否自动播放
                    // flashplayer:true,
					video: videoUrl
				}
				//判断是需要重新加载播放器还是直接换新地址

				if(player.playerType == 'html5video') {
					if(player.getFileExt(videoUrl) == '.flv' || player.getFileExt(videoUrl) == '.m3u8' || player.getFileExt(videoUrl) == '.f4v' || videoUrl.substr(0, 4) == 'rtmp') {
						player.removeChild();

						player = null;
						player = new ckplayer();
						player.embed(newVideoObject);
					} else {
						player.newVideo(newVideoObject);
					}
				} else {
					if(player.getFileExt(videoUrl) == '.mp4' || player.getFileExt(videoUrl) == '.webm' || player.getFileExt(videoUrl) == '.ogg') {
						player = null;
						player = new ckplayer();
						player.embed(newVideoObject);
					} else {
						player.newVideo(newVideoObject);
					}
				}
            }
		</script>
    @endslot
@endcomponent