<div class="hidden popup">
    <div class="admin-info message">

        {{-- title --}}
        <div class="head">
            <h3 class="popup-title">消息中心</h3>
            <a href="javascript:void(0);" class="popup-close"><i class="icon iconfont">&#xe69a;</i></a>
        </div>
         {{-- /.title --}}

        <div class="body">
            @if($message['count'] != 0)
                @foreach($message['data'] as $message)
                    <div class="message-inner" id="{{$message['id']}}">
                        <div class="message-content">
                            <span class="pull-right">{{$message['add_time']}}</span>
                            <span class="pull-left">
                                @if($message['mode'] == 1)
                                    私信通知
                                @elseif($message['mode'] == 2)
                                    管理员通知
                                @else
                                    系统通知
                                @endif
                            </span>
                            <div class="clearfix visible-xs"></div>
                        </div>
                        <a href="JavaScript:void(0);" url="/admin/sys_message_list/view/{{$message['id']}}" onclick="operation($(this));" title="查看站内信息">{{$message['title']}}</a>
                    </div>
                @endforeach
            @else
                <div class="body-tool-info">暂无未读消息</div>
            @endif
        </div>

        {{-- widget foot --}}
        <div class="foot">
            <a href="javascript:void(0);" url="{{ url('/admin/sys_message_list') }}" onclick="pjaxContent($(this));">
                <span>进入消息中心</span>
            </a>
        </div>
        {{-- /.widget foot --}}

    </div>
</div>