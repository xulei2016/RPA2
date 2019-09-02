@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @component('admin.widgets.toolbar')
                        @slot('listsOperation')
                            <!-- <li><a href="javascript:void(0)" id="deleteAll">删除全部</a></li> -->
                        @endslot

                        @slot('operation')
                            <a class="btn btn-success btn-sm" url="/admin/sys_api/create" title="新增" onclick="operation($(this));">
                                <span class="glyphicon glyphicon-plus"></span>新增
                            </a>
                        @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                        @slot('searchContent')
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="api" placeholder="接口名称">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="desc" placeholder="接口描述">
                        </div>
                        <br/><br>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="startTime" placeholder="开始时间">
                        </div>
                        <div style="float:left;">-</div>
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

    <script src="{{URL::asset('/js/admin/base/api/index.js')}}"></script>
@endsection