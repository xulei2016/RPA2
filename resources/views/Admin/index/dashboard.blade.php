
    {{-- 管理员信息 --}}
    <div class="row dashboard-container">

            <div class="admin col-lg-6">
                <div class="content row">
                    <div class="head-img col-xs-4 col-lg-4">
                        <img src="{{ $admin['headImg'] }}" alt="头像" >
                    </div>
                    <div class="info col-xs-8 col-lg-8">
                        <ol>
                            <li><b>{{ $admin['name']}}</b></li>
                            <li>{{ $admin['email'] or ''}}</li>
                            <li><b>上次活跃：</b> {{ $admin['lastTime'] or '首次登陆'}}</li>
                        </ol>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="message col-lg-3" url="{{ url('/admin/sys_message_list') }}" onclick="pjaxContent($(this));">
                <div class="content">
                    <div class="card-panel blue left">
                        <i class="icon iconfont">&#xe721;</i>
                    </div>
                    <div class="card-panel-description right ">
                        <div class="card-panel-text">Messages</div>
                        <span class="card-panel-num">{{ $message['count'] }}</span>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="record col-lg-3">
                <div class="content">
                    <div class="card-panel red">
                        <i class="icon iconfont">&#xe7c3;</i>
                    </div>
                    <div class="card-panel-description right">
                        <div class="card-panel-text">Login Record</div>
                        <span class="card-panel-num">10086</span>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="clear"></div>
        </div>
        {{-- 系统信息 --}}
        <div class="sys row">
            <div class="sys-info col-lg-6">
                <div class="ibox-title">
                    <h5><i class="icon iconfont">&#xe6ec;</i> 服务器信息</h5>
                </div>
                <div class="ibox-content" style="display: block;">
                    <ul class="todo-list m-t small-list">
                        @foreach($info['sys'] as $key => $info)
                            <li>{{ $key }}：{{ $info }}</li>
                        @endforeach
                        <li>GD图形处理库：bundled (2.1.0 compatible)</li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                
            </div>
        </div>
    
        <link href="{{URL::asset('/css/admin/common/index.css')}}" rel="stylesheet">