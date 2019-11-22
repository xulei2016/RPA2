@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @component('admin.widgets.toolbar')
                            @slot('listsOperation')
                                <li><a class="dropdown-item" href="javascript:void(0)" id="deleteAll">删除全部</a></li>
                            @endslot

                            @slot('operation')
                                <a class="btn btn-success btn-sm tree-ntstable-add" url="/admin/rpa_customer_funds_search/add" title="新增" onclick="operation($(this));">
                                    <span class="glyphicon glyphicon-plus"></span><span class="hidden-xs">&nbsp;添加客户</span>
                                </a>
                                <a class="btn btn-primary btn-sm" href="/admin/rpa_customer_funds_search/varietyset">品种设置</a>
                            @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')

                                <div class="col-sm-2">
                                    <select name="state" id="state" class="form-control">
                                        <option value="" selected>状态:全部</option>
                                        <option value="0">未达标</option>
                                        <option value="1">已达标</option>
                                        <option value="2">已归档</option>
                                        <option value="-1">客户不存在</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select name="blancenum" id="blancenum" class="form-control">
                                        <option value="" selected>达标量:全部</option>
                                        <option value="1">1天</option>
                                        <option value="2">2天</option>
                                        <option value="3">3天</option>
                                        <option value="4">4天</option>
                                        <option value="5">5天</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select name="tid" id="tid" class="form-control">
                                        <option value="" selected>品种:全部</option>
                                        @foreach($varietyList as $name)
                                            <option value="{{ $name['id'] }}">{{ $name['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="customer" placeholder="客户姓名或客户编号">
                                </div>
                                <div class="col-sm-4"></div>
                                <div class="col-sm-2">
                                    <input type="text" autocomplete="off" class="form-control" id="startTime" placeholder="开始时间">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" autocomplete="off" class="form-control" id="endTime" placeholder="结束时间">
                                </div>
                            
                            @endslot
                        @endcomponent
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{URL::asset('/js/admin/func/Oabremind/index.js')}}"></script>
@endsection