@extends('admin.layouts.wrapper-content')

@section('content')

    <div class="panel box box-primary">
        <div class="box-body">
            
            @component('admin.widgets.toolbar', ['operation_show' => false])
                @slot('listsOperation')
                    <li><a href="javascript:void(0)" id="deleteAll">删除全部</a></li>
                @endslot
                @slot('operation')
                    <a class="btn btn-warning btn-sm" url="/admin/rpa_customerGrouping/immedtasks" title="新增" onclick="operation($(this));">
                        <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;立即任务</span>
                    </a>
                    <a class="btn btn-primary btn-sm" url="/admin/rpa_customerGrouping/create" title="新增" onclick="operation($(this));">
                        <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;添加任务</span>
                    </a>
                @endslot
            @endcomponent

            <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>

        </div>
    </div>

<script src="{{URL::asset('/js/admin/rpa/customerGrouping/index.js')}}"></script>
@endsection