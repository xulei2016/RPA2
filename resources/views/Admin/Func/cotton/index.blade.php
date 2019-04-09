@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="panel box box-primary">
        <div class="box-body">
            @component('admin.widgets.toolbar')
            @slot('listsOperation')
                <li><a href="javascript:void(0)" id="deleteAll">删除全部</a></li>
            @endslot

            @slot('operation')
                <a class="btn btn-warning btn-sm tree-ntstable-add" url="/admin/rpa_cotton/add" title="一键导入excel" onclick="operation($(this));">
                    <span class="hidden-xs">一键导入excel</span>
                </a>
                <a class="btn btn-primary btn-sm" href="/admin/rpa_cotton/official">查看归档数据</a>
                <a class="btn btn-success btn-sm" href="/admin/rpa_cotton/download">下载excel模板</a>
            @endslot
            @endcomponent

            @component('admin.widgets.search-group')
            @slot('searchContent')
            <label for="operator" class="control-label col-sm-1">用户名:</label>
            <div class="col-sm-2">
                <select name="operator" id="operator" class="form-control">
                    <option value="" selected>无</option>
                    @foreach($list as $name)
                        @if(session('sys_admin')['realName'] == $name['realName'])
                            <option value="{{ $name['realName'] }}" selected>{{ $name['realName'] }}</option>
                        @else
                            <option value="{{ $name['realName'] }}">{{ $name['realName'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <label for="state" class="control-label col-sm-1">状态:</label>
            <div class="col-sm-2">
                <select name="state" id="state" class="form-control">
                    <option value="" selected>无</option>
                    <option value="0">未解析</option>
                    <option value="1">解析成功</option>
                    <option value="2">解析失败</option>
                    <option value="3">批号重复</option>
                </select>
            </div>
            <label for="customer" class="control-label col-sm-1">客户:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="customer" placeholder="请输入客户姓名或客户编号">
            </div>
            @endslot
            @endcomponent
            <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>

        </div>
    </div>

    <script src="{{URL::asset('/js/admin/func/Cotton/index.js')}}"></script>
@endsection