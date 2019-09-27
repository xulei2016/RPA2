@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @component('admin.widgets.toolbar')
                        @slot('listsOperation')
                            <li><a class="dropdown-item" href="javascript:void(0)" id="exportAll">导出全部</a></li>
                            <li><a class="dropdown-item" href="javascript:void(0)" id="export">导出选中</a></li>
                        @endslot

                        @slot('operation')
                            @if(auth()->guard('admin')->user()->can('rpa_customer_add'))
                                <a class="btn btn-success btn-sm" url="/admin/rpa_customer/add" title="新增" onclick="operation($(this));">
                                    <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;添加客户</span>
                                </a>
                            @endif
                        @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                        @slot('searchContent')
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="customer" placeholder="资金账号或客户姓名">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="manager" placeholder="经理姓名或经理编号">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="mediator" placeholder="居间姓名或居间编号">
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

    <script src="{{URL::asset('/js/admin/func/Customer/index.js')}}"></script>
@endsection