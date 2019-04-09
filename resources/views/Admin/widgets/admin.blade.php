<div class="hidden popup">
    <div class="admin-info admin">
        <div class="head">
            <img src="{{ session('sys_admin')['headImg'] }}">
            <p>
                <span title="{{ session('sys_admin')['email'] }}">
                    {{ session('sys_admin')['name'] }}
                </span>
            </p>
        </div>
        <div class="body">
            <a class="adminbar-list" url="{{ url('/admin/sys_admin_center/baseinfo') }}" onclick="pjaxContent($(this));">
                <span class="adminbar-icon"><i class="iconfont icon">&#xe668;</i></span><span>基本资料</span>
            </a>
            <a class="adminbar-list" url="{{ url('/admin/sys_admin_center/changePWD') }}" onclick="pjaxContent($(this));">
                <span class="adminbar-icon"><i class="iconfont icon">&#xe6ce;</i></span><span>修改密码</span>
            </a>
            <a class="adminbar-list" url="{{ url('/admin/sys_admin_center/safeSetting') }}" onclick="pjaxContent($(this));">
                <span class="adminbar-icon"><i class="iconfont icon">&#xe68e;</i></span><span>安全设置</span>
            </a>
            <a class="adminbar-list">
                <span class="adminbar-icon"><i class="iconfont icon">&#xe6ce;</i></span><span>修改密码</span>
            </a>
            <a href="#" onclick="javascript:window.location.reload();" class="adminbar-list">
                <span class="adminbar-icon"><i class="iconfont icon">&#xe63e;</i></span><span>重新加载</span>
            </a>
            <a href="#" onclick="clean_cache();" class="adminbar-list">
                <span class="adminbar-icon"><i class="iconfont icon">&#xe62d;</i></span><span>清除缓存</span>
            </a>
        </div>
        <div class="foot">
            <a href="{{ url('/admin/logout') }}">
                <span>退出登录</span>
            </a>
        </div>
    </div>
</div>