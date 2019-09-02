@extends('Admin.layouts.wrapper-content')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-outline">
                <div class="card-body">
                    @component('admin.widgets.toolbar')
                    @slot('listsOperation')
                    {{--                    @if(auth()->guard('admin')->user()->can('sys_call_center_manager_export'))--}}
                    <li><a href="javascript:void(0)" class="dropdown-item" id="exportAll">导出全部</a></li>
                    <li><a href="javascript:void(0)" class="dropdown-item" id="export">导出筛选</a></li>
                    <li><a href="javascript:void(0)" class="dropdown-item" id="exportCurrent">导出当天</a></li>
                    {{--                    @endcan--}}
                    @endslot
                    @slot('operation')
                    <!-- <a class="btn btn-warning btn-sm tree-ntstable-add"  href="javascript:;" title="一键发送短信" id="sendAll">
                    <span class="hidden-xs">一键发送短信</span>
                    </a> -->
                    @endslot
                    @endcomponent

                    @component('admin.widgets.search-group')
                    @slot('searchContent')
                    <label class="control-label col-sm-1" for="startTime">开始时间</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="startTime">
                    </div>

                    <label class="control-label col-sm-1" for="startTime">结束时间</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="endTime">
                    </div>

                    <label class="control-label col-sm-1" for="name">姓名</label>
                    <div class="col-sm-2">
                    <input type="text" class="form-control" id="name">
                    </div>
                    @endslot
                    @endcomponent


                    <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="{{URL::asset('/js/admin/func/simulation/index.js')}}"></script>
@endsection
