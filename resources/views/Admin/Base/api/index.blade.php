@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="panel box box-primary">
        <div class="box-body">
            @component('admin.widgets.toolbar')
            @slot('listsOperation')
                <li><a href="javascript:void(0)" id="deleteAll">删除全部</a></li>
            @endslot

            @slot('operation')
                <a class="btn btn-warning btn-sm" url="/admin/sys_api/create" title="新增" onclick="operation($(this));">
                    <span class="glyphicon glyphicon-plus"></span>
                </a>
            @endslot
            @endcomponent

            @component('admin.widgets.search-group')
            @slot('searchContent')
            <label for="api" class="control-label col-sm-1">名称:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="api" placeholder="接口名称">
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

    <script src="{{URL::asset('/js/admin/base/api/index.js')}}"></script>
@endsection