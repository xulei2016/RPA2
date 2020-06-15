@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @component('admin.widgets.toolbar')
                        @slot('listsOperation')
{{--                        <li><a class="dropdown-item" href="javascript:void(0)" id="exportAll">导出全部</a></li>--}}
{{--                            <li><a class="dropdown-item" href="javascript:void(0)" id="export">导出选中</a></li>--}}
                        @endslot

                        @slot('operation')
                        <a class="btn btn-default btn-sm instructions"  title="说明">
                            <i class="fa fa-question-circle"></i>
                        </a>
                        @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                        @slot('searchContent')
                        <div class="col-sm-2">
                            <select name="check_status" id="check_status" class="form-control">
                                <option value="" selected>状态:全部</option>
                                <option value="0">待审核</option>
                                <option value="1">待复核</option>
                                <option value="2">已上报</option>
                                <option value="3">打回</option>
                                <option value="4">上报成功</option>
                                <option value="5">上报失败</option>
                                <option value="6">未完成</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="zjzh" name="zjzh" class="form-control" placeholder="资金账号" >
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="start_id" name="start_id" class="form-control" placeholder="ID区间开始值" >

                        </div>

                        <div class="col-sm-2">
                            <input type="text" id="end_id" name="end_id" class="form-control" placeholder="ID区间结束值" >

                        </div>

                        @endslot
                        @endcomponent
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{URL::asset('/js/admin/func/MonitorPicture/index.js')}}"></script>
    <style>
        .swal2-popup #swal2-content{
            text-align:left;
        }
    </style>
@endsection