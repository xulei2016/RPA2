@extends('admin.layouts.wrapper-content')

@section('content')

    <div class="panel box box-primary">

        @component('admin.widgets.toolbar', ['operation_show' => false])
            @slot('listsOperation')
                @if(auth()->guard('admin')->user()->can('rpa_center'))
                    <li><a href="javascript:void(0)" id="deleteAll">删除全部</a></li>
                    <li><a href="javascript:void(0)" id="exportAll">导出全部</a></li>
                    <li><a href="javascript:void(0)" id="export">导出选中</a></li>
                @endcan
            @endslot
            @slot('operation')
                <a class="btn btn-primary btn-sm" href="/admin/rpa_center/queue">任务队列</a>
                <a class="btn btn-primary btn-sm" href="/admin/rpa_center/taskList">发布任务一览</a>
                <a class="btn btn-warning btn-sm tree-ntstable-add" url="/admin/rpa_center/create" title="新增" onclick="operation($(this));">
                    <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;新增</span>
                </a>
            @endslot
        @endcomponent



        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
    </div>

<script src="{{URL::asset('/js/admin/rpa/center/index.js')}}"></script>
@endsection