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
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="startTime" placeholder="开始时间">
                    </div>

                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="endTime" placeholder="结束时间">
                    </div>

                    <div class="col-sm-2">
                    <input type="text" class="form-control" id="name" placeholder="姓名">
                    </div>
                    
                    <div class="col-sm-2">
                        <select name="isCtp" id="isCtp" class="form-control">
                            <option value="">ctp状态(全部)</option>
                            <option value="0">无</option>
                            <option value="1">仅ctp穿透</option>
                            <option value="2">仅ctp穿透(已分配)</option>
                        </select>
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
