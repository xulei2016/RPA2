@extends('admin.layouts.wrapper-content')

@section('content')

    <div class="card card-primary card-outline">
        <div class="card-body">
            
            @component('admin.widgets.toolbar')
                @slot('listsOperation')
                    @if(auth()->guard('admin')->user()->can('sys_admin_export'))
                        <li><a class="dropdown-item" href="javascript:void(0)" id="exportAll">导出全部</a></li>
                        <li><a class="dropdown-item" href="javascript:void(0)" id="export">导出选中</a></li>
                    @endcan
                @endslot
                @slot('operation')
                    <a class="btn btn-success btn-sm tree-ntstable-add" url="/admin/sys_admin/create" title="新增" onclick="operation($(this));">
                        <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;新增</span>
                    </a>
                @endslot
            @endcomponent

            @component('admin.widgets.search-group')
                @slot('searchContent')
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="name" placeholder="姓名">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="realName" placeholder="真实姓名">
                    </div>
                @endslot
            @endcomponent

            <table id="tb_departments" class="table table-striped table-hover table-bordered">

            </table>
        </div>
    </div>

<script src="{{URL::asset('/js/admin/admin/index.js')}}"></script>
@endsection