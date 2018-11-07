@extends('admin.layouts.wrapper-content')

@section('content')

    <div class="panel">
        <div id="toolbar">
            <div class="btn-group" >
                <button type="button" class="btn btn-default btn-sm">操作</button>
                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">删除选中</a></li>
                    <li><a href="#">导出全部</a></li>
                    <li><a href="#">导出选中</a></li>
                </ul>
            </div>
            <a class="btn btn-default btn-sm" data-toggle="collapse" data-target="#search-group" href="javascript:void(0)">
                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>筛选器
            </a>
            <a href="javascript:void(0)" class="btn btn-info btn-sm" url="/admin/admin/create" onclick="operation($(this));">
                <span class="glyphicon glyphicon-plus"></span> 添加
            </a>
        </div>
        <div class="panel panel-default panel-collapse collapse" id="search-group">
            <div class="panel-heading">查询条件</div>
            <div class="panel-body">
                <form id="formSearch" class="form-horizontal">
                    <div class="form-group" style="margin-top:15px">
                        <label class="control-label col-sm-1" for="name">姓名</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="name">
                        </div>
                        <label class="control-label col-sm-1" for="role">所属角色</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="role">
                        </div>
                        <label class="control-label col-sm-1" for="status">状态</label>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="status">
                        </div>
                        <div class="col-sm-2" style="text-align:left;">
                            <button type="button" style="margin-left:10px" id="search-btn" class="btn btn-primary">查询</button>
                            <button type="reset" id="reset" class="btn btn-default">重置</button>
                        </div>
                    </div>
                </form>
            </div>
        </div> 
        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
    </div>

<script src="{{URL::asset('/js/admin/admin/index.js')}}"></script>
@endsection