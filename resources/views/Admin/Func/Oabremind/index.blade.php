@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="panel box box-primary">
        <div class="box-body">
            @component('admin.widgets.toolbar')
            @slot('listsOperation')
                <li><a href="javascript:void(0)" id="deleteAll">删除全部</a></li>
            @endslot

            @slot('operation')
                <a class="btn btn-warning btn-sm tree-ntstable-add" url="/admin/rpa_customer_funds_search/add" title="新增" onclick="operation($(this));">
                    <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;添加客户</span>
                </a>
                <a class="btn btn-primary btn-sm" href="/admin/rpa_customer_funds_search/varietyset">品种设置</a>
            @endslot
            @endcomponent

            @component('admin.widgets.search-group')
            @slot('searchContent')
            <label for="state" class="control-label col-sm-1">状态:</label>
            <div class="col-sm-2">
                <select name="state" id="state" class="form-control">
                    <option value="" selected>无</option>
                    <option value="0">未达标</option>
                    <option value="1">已达标</option>
                    <option value="2">已归档</option>
                    <option value="-1">客户不存在</option>
                </select>
            </div>
            <label for="tid" class="control-label col-sm-1">品种:</label>
            <div class="col-sm-2">
                <select name="tid" id="tid" class="form-control">
                    <option value="" selected>无</option>
                    @foreach($varietyList as $name)
                        <option value="{{ $name['id'] }}">{{ $name['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <label for="customer" class="control-label col-sm-1">客户:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="customer" placeholder="请输入客户姓名或客户编号">
            </div>
            <br/><br>
            <label for="startTime" class="control-label col-sm-1">时间:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="startTime" placeholder="请选择时间">
            </div>
            <div style="float:left;">-</div>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="endTime" placeholder="请选择时间">
            </div>
            @endslot
            @endcomponent
            <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>

        </div>
    </div>

    <script src="{{URL::asset('/js/admin/func/Oabremind/index.js')}}"></script>
@endsection