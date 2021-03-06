@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @component('admin.widgets.toolbar')
                            @slot('listsOperation')
                                @if(auth()->guard('admin')->user()->can('rpa_revisit_export'))
                                    <li><a class="dropdown-item" href="javascript:void(0)" id="exportAll">导出全部</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" id="export">导出选中</a></li>
                                @endcan
                            @endslot
                            @slot('operation')
                            @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')
                                <div class="col-sm-2">
                                    <select name="status" id="status" class="form-control">
                                        <option value="" selected>状态:全部</option>
                                        <option value="2">待审核</option>
                                        <option value="1">回访失败</option>
                                        <option value="0">未回访</option>
                                        <option value="3">已审核待归档</option>
                                        <option value="4">已归档</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="customer" placeholder="客户姓名或资金账号">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="yybName" placeholder="营业部名称">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="reviser" placeholder="回访人">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="checker" placeholder="审核人">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="startTime" placeholder="开始时间">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="endTime" placeholder="结束时间">
                                </div>
                            @endslot
                        @endcomponent
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="{{URL::asset('/js/admin/Func/Revisit/customer/index.js')}}"></script>
@endsection