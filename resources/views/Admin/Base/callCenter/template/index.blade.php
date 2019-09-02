@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="panel box box-primary">
        <div class="box-body">
            @component('admin.widgets.toolbar')
                @slot('listsOperation')
                    @if(auth()->guard('admin')->user()->can('sys_call_center_template_export'))
                        <li><a href="javascript:void(0)" id="exportAll">导出全部</a></li>
                        <li><a href="javascript:void(0)" id="export">导出选中</a></li>
                    @endcan
                @endslot
                @slot('operation')
                    <a class="btn btn-warning btn-sm tree-ntstable-add" url="/admin/sys_call_center_template/create" title="新增" onclick="operation($(this));">
                        <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;新增</span>
                    </a>
                @endslot
            @endcomponent

            @component('admin.widgets.search-group')
                @slot('searchContent')
                    <label class="control-label col-sm-1" for="keyword">关键词</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="keyword">
                    </div>
                @endslot
            @endcomponent


            <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
        </div>
    </div>
    <script src="{{URL::asset('/include/ckeditor/ckeditor.js')}}"></script>

    <script src="{{URL::asset('/js/admin/base/callCenter/template/index.js')}}"></script>
@endsection