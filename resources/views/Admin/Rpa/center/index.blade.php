@extends('admin.layouts.wrapper-content')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                
                    <div class="card-body">

                        @component('admin.widgets.toolbar', ['operation_show' => false])
                            @slot('listsOperation')
                                @if(auth()->guard('admin')->user()->can('rpa_center'))
                                    <li><a class="dropdown-item" href="javascript:void(0)" id="deleteAll">删除全部</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" id="exportAll">导出全部</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" id="export">导出选中</a></li>
                                @endcan
                            @endslot
                            @slot('operation')
                                <a class="btn btn-primary btn-sm" href="/admin/rpa_center/queue">任务队列</a>
                                <a class="btn btn-primary btn-sm" href="/admin/rpa_center/taskList">发布任务</a>
                                <a class="btn btn-success btn-sm tree-ntstable-add" url="/admin/rpa_center/create" title="新增" onclick="operation($(this));">
                                    <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;新增</span>
                                </a>
                            @endslot
                        @endcomponent

                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                        
                    </div>

                </div>
            </div>
        </div>
    </div>

<script src="{{URL::asset('/js/admin/rpa/center/index.js')}}"></script>
@endsection