@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @component('admin.widgets.toolbar')
                        @slot('listsOperation')
                            <li><a class="dropdown-item" href="javascript:void(0)" id="deleteAll">批量删除</a></li>
                        @endslot

                        @slot('operation')
                            @if(auth()->guard('admin')->user()->can('rpa_customer_add'))
                                <a class="btn btn-success btn-sm" url="/admin/rpa_jkzxPwd/create" title="新增" onclick="operation($(this));">
                                    <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;添加客户</span>
                                </a>
                                <a class="btn btn-primary btn-sm" id="yjsend" href="javascript:void(0);">
                                    <span class="fa fa-send-o"></span><span class="hidden-xs">&nbsp;短信一键发送</span>
                                </a>
                            @endif
                        @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                        @slot('searchContent')
                        <div class="col-sm-2">
                            <select name="type" id="type" class="form-control">
                                <option value="" selected>客户类型:全部</option>
                                <option value="普通">普通</option>
                                <option value="IB">IB</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="status" id="status" class="form-control">
                                <option value="" selected>发送状态:全部</option>
                                <option value="2">未发送</option>
                                <option value="1">已发送</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="customer" placeholder="资金账号或客户姓名">
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

    <script src="{{URL::asset('/js/admin/func/JKZXPwd/index.js')}}"></script>
@endsection