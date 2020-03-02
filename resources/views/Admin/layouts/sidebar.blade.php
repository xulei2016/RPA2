<!-- Main Sidebar Container -->
<aside class="main-sidebar fixed">
    <!-- Brand Logo -->
    <div class="logo">
        <a href="/admin">
            <img src="{{URL::asset('/common/images/logo.png')}}" alt="{!! config('admin.name') !!}" class="img-circle elevation-3">
            <h1>{!! config('admin.logo') !!}</h1>
        </a>
    </div>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                {{-- <li class="nav-item">
                    <a href="/admin" class="nav-link active"><i class="nav-icon fa fa-bars"></i><p>首页</p></a>
                </li> --}}

                @inject('menus','App\Http\Controllers\Admin\Base\MenuController')

                {!! $menus->getMenuList() !!}

                <li class="nav-header">其他</li>
                <li class="nav-item"><a href="/admin/Bugs" class="nav-link"><i class="nav-icon fa fa-bug text-red"></i><p class="text">BUG提交</p></a></li>
                <li class="nav-item"><a href="/admin/Improvement" class="nav-link"><i class="nav-icon fa fa-comment text-yellow"></i><p class="text">改进意见</p></a></li>
                <li class="nav-item"><a href="https://jq.qq.com/?_wv=1027&k=5PO47UO" class="nav-link" target="_blank"><i class="nav-icon fa fa-qq text-primary"></i><p class="text">QQ交流群</p></a></li>

            </ul>
        </nav>
    </div>
</aside>