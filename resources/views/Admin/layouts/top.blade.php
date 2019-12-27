<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
        </li>
    </ul>
    <!-- SEARCH FORM -->
    <div class="search ml-3">
        <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" list="searchList" type="search" placeholder="Search" aria-label="Search" onkeydown="RPA.search(event);" onchange="RPA.search(event);" autocomplete="off">
            <datalist id="searchList">
                <option value="首页" href="/">首页</option>
            </datalist>
            <div class="input-group-append">
                <button class="btn btn-navbar search-submit">
                  <i class="fa fa-search"></i>
                </button>
            </div>
        </div>
    </div>
    <ul class="navbar-nav ml-auto">
        <!-- Messages Menu -->
        <li class="nav-item navbar-right">
            <a class="nav-link" data-toggle="fullscreen" href="#">
                <i class="fa fa-arrows-alt"></i>
            </a>
        </li>
        <!-- Notifications Menu -->
        <li class="nav-item admin-message navbar-right">
          <a class="nav-link" id="notification_count" href="#">
            <i style="font-size: 16px" class="fa fa-bell"></i>
              @if(Auth::user()->notification_count >0)
                    <span class="badge badge-warning navbar-badge">{{ Auth::user()->notification_count }}</span>
              @endif
          </a>
          <div class="hidden popup">
                <div class="admin-info message">
                    <div class="head">
                        <h3 class="popup-title">消息中心</h3>
                        <a href="javascript:void(0);" class="popup-close"><span class="fa fa-close"></span></a>
                    </div>
                    <div class="body notifications-menu">
                        @if(Auth::user()->notification_count >0)
                            <ul class="menu" id="notification_list">
                                @foreach(Auth::user()->unreadNotifications as $v)
                                    @if($loop->index < 5)
                                        <li><a href="javascript:;" onclick="operation($(this));readEvent($(this));"  url="/admin/sys_message_list/view/{{$v->id}}"><i class="fa fa-users text-aqua"></i> {{$v->data['title']}}</a></li>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            <div class="body-tool-info">暂无未读消息</div>
                        @endif
                    </div>
                    <div class="foot">
                        <a href="/admin/sys_message_list"> <span>进入消息中心</span> </a>
                    </div>
                </div>
            </div>
        </li>
        <!-- Notifications Menu -->
        <li class="user-panel d-flex admin-info-list navbar-right">
            <a class="nav-link" href="#" style="padding:0 1rem;">
                <img src="{{ URL::asset(session('sys_admin')['headImg']) }}" onerror="this.src='{{URL::asset('/common/images/default_head.png')}}'" class="img-circle elevation-2" alt="User Image">
            </a>
            {{--  popup page  --}}
            <div class="hidden popup">
                <div class="admin-info admin">
                    <div class="head">
                        <img src="{{ URL::asset(session('sys_admin')['headImg']) }}" onerror="this.src='{{URL::asset('/common/images/default_head.png')}}'" class="img-circle">
                        <p>
                            <span title="{{ session('sys_admin')['email'] }}">
                                {{ session('sys_admin')['name'] }}
                            </span>
                        </p>
                    </div>
                    <div class="body">
                        <a class="adminbar-list" href="/admin/sys_profile">
                            <span class="adminbar-icon"><span class="fa fa-user"></span></span><span>个人信息</span>
                        </a>
                        {{--<a class="adminbar-list" url="{{ url('/admin/sys_admin_center/changePWD') }}" onclick="pjaxContent($(this));">--}}
                            {{--<span class="adminbar-icon"><span class="fa fa-expeditedssl"></span></span><span>修改密码</span>--}}
                        {{--</a>--}}
                        {{--<a class="adminbar-list" url="{{ url('/admin/sys_admin_center/safeSetting') }}" onclick="pjaxContent($(this));">--}}
                            {{--<span class="adminbar-icon"><span class="fa fa-shield"></span></span><span>安全设置</span>--}}
                        {{--</a>--}}
                        {{--<a class="adminbar-list">--}}
                            {{--<span class="adminbar-icon"><span class="fa fa-expeditedssl"></span></span><span>修改密码</span>--}}
                        </a>
                        <a href="#" onclick="javascript:window.location.reload();" class="adminbar-list">
                            <span class="adminbar-icon"><span class="fa fa-refresh"></span></span><span>重新加载</span>
                        </a>
                        <a href="#" onclick="RPA.clearCache();" class="adminbar-list">
                            <span class="adminbar-icon"><span class="fa fa-undo"></span></span><span>清除缓存</span>
                        </a>
                    </div>
                    <div class="foot">
                        <a href="{{ url('/admin/logout') }}">
                            <span>退出登录</span>
                        </a>
                    </div>
                </div>
            </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#">
            <i class="fa fa-th-large"></i>
          </a>
        </li>
    </ul>
</nav>
