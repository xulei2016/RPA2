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
                        @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                        @slot('searchContent')
                        <div class="col-sm-2">
                            <select name="status" id="status" class="form-control">
                                <option value="" selected>状态:全部</option>
                                <option value="0">资料待补全</option>
                                <option value="1">待上报</option>
                                <option value="2">待归档</option>
                                <option value="3">完成</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" id="fundsNum" name="fundsNum" class="form-control" placeholder="资金账号" >
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

    <script src="{{URL::asset('/js/admin/func/SecondFinance/index.js')}}"></script>
@endsection