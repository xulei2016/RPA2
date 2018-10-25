<div class="navbar">
        <div class="navbar-header">
            <div class="navbar-company">
                
                {{--  bread-bar  --}}
                <div class="bread-bar">
                    <div class="sidebar-fold topbar-sidebar-unfold"><b><i class="iconfont icon">&#xe63d;</i></b></div>
                    <ol class="bread-crumb hidden-xs hidden-sm">
                        <li><a href="#" url="./admin/dashboard" onclick="pjaxContent($(this))">首页</a></li>
                        <li class="active"></li>
                    </ol>
                </div>

            </div>
        </div>
    
        <div class="collapse">
            <!-- Right Side Of Navbar -->
    
            <div class="nava change navbar-right">
                <a href="#">
                    <span><i class="iconfont">&#xe710;</i></span>
                </a>
            </div>

            <div class="nava navbar-right admin-message">
                <a href="#">
                    <span><i class="iconfont">&#xe61e;</i></span>
                    @if($message['count'] != 0)
                    <span class="topbar-notice-num">{{ $message['count'] }}</span>
                    @endif
                </a>
                {{--  popup page  --}}
                @include('admin.widgets.message')
            </div>

            <div class="nava navbar-right admin-theme"><a href="#">主题</a>
                {{--  popup page  --}}
                @include('admin.widgets.skins')
            </div>

            <div class="nava navbar-right"><a href="javascript:void(0);" url="{{ url('/admin/admin_center') }}" onclick="pjaxContent($(this));">系统</a></div>

            <div class="nava navbar-right admin-info-list">
                <span>
                    <a href="#">
                        <img src="{{ session('sys_admin')['headImg'] }}">
                        {{-- {{ session('sys_admin')['name'] }} <span class="caret"></span> --}}
                    </a>
                </span>
                
                {{--  popup page  --}}
                @include('admin.widgets.admin')
            </div>

        </div>
    </div>
    