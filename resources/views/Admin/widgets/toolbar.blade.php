

<div id="toolbar">
    <div class="btn-group" >
        <button type="button" class="btn btn-default btn-sm">操作</button>
        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
            <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
            <li><a href="javascript:void(0)" id="exportAll">导出全部</a></li>
        </ul>
    </div>
    <a class="btn btn-default btn-sm" data-toggle="collapse" data-target="#search-group" href="javascript:void(0)">
        <span class="glyphicon glyphicon-search" aria-hidden="true"></span>筛选器
    </a>

    @if(auth()->guard('admin')->user()->can('sys_admin_add'))
    <a href="javascript:void(0)" class="btn btn-info btn-sm" url="/admin/sys_admin/create" onclick="operation($(this));">
        <span class="glyphicon glyphicon-plus"></span> 添加
    </a>
    @endcan
</div>