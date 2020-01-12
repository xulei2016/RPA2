@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @component('admin.widgets.toolbar')
                            @slot('listsOperation')
                                <li><a class="dropdown-item" href="javascript:void(0)" id="deleteAll">删除全部</a></li>
                            @endslot

                            @slot('operation')
                                <a class="btn btn-success btn-sm tree-ntstable-add" url="/admin/rpa_cotton/add"
                                   title="一键导入excel" onclick="operation($(this));">
                                    <span class="hidden-xs">一键导入excel</span>
                                </a>
                                <a class="btn btn-primary btn-sm" href="/admin/rpa_cotton/official">查看归档数据</a>
                                <a class="btn btn-warning btn-sm" target="_blank" href="/admin/rpa_cotton/download">下载excel模板</a>
                            @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')
                                <div class="col-sm-2">
                                    <select name="operator" id="operator" class="form-control">
                                        <option value="" selected>用户名:全部</option>
                                        @foreach($list as $name)
                                            @if(session('sys_admin')['realName'] == $name['realName'])
                                                <option value="{{ $name['realName'] }}"
                                                        selected>{{ $name['realName'] }}</option>
                                            @else
                                                <option value="{{ $name['realName'] }}">{{ $name['realName'] }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select name="state" id="state" class="form-control">
                                        <option value="" selected>状态:全部</option>
                                        <option value="0">未解析</option>
                                        <option value="1">解析成功</option>
                                        <option value="2">解析失败</option>
                                        <option value="3">批号重复</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="customer" placeholder="客户姓名或客户编号">
                                </div>
                            @endslot
                        @endcomponent
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{URL::asset('/js/admin/func/Cotton/index.js')}}"></script>
@endsection