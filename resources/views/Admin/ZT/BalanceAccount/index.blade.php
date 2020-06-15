@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @component('admin.widgets.toolbar')
                            @slot('listsOperation')
                                @if(count(array_intersect(explode(',', Auth::User()->roleLists), ['superAdministrator', 'RpaAdmin'])))
                                    <li><a class="dropdown-item" href="javascript:void(0)" id="exportAll">导出全部</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" id="export">导出选中</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" id="delete">删除选中</a></li>
                                @endif
                            @endslot

                            @slot('operation')
                            @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="user" placeholder="资金账号或客户姓名">
                                </div>
                                <div class="col-sm-2">
                                    <select name="type" id="type" class="form-control">
                                        <option value="" selected>类型:全部</option>
                                        <option value="1">新增</option>
                                        <option value="2">变更</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select name="status" id="status" class="form-control">
                                        <option value="" selected>状态:全部</option>
                                        <option value="0">已办理</option>
                                        <option value="1">办理中</option>
                                        <option value="2">办理失败</option>
                                        <option value="3">待办理</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="orderNum" placeholder="流水号">
                                </div>
                            @endslot
                        @endcomponent
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{URL::asset('/js/admin/func/YingqiChange/index.js')}}"></script>
@endsection