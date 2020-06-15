@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @component('admin.widgets.toolbar')
                            @slot('listsOperation')
                                <li><a href="javascript:void(0)" class="dropdown-item" id="exportAll">导出全部</a></li>
                                <li><a href="javascript:void(0)" class="dropdown-item" id="export">导出选中</a></li>
                            @endslot
                            @slot('operation')
                                <a class="btn btn-default btn-sm instructions"  title="说明">
                                    <i class="fa fa-question-circle"></i>
                                </a>
                                <a class="btn btn-primary btn-sm cale"  title="数据日历">
                                    数据日历
                                </a>
                                <a class="btn btn-success btn-sm get-data"  title="新增">
                                    获取数据
                                </a>
                            @endslot
                        @endcomponent
                        @component('admin.widgets.search-group')
                            @slot('searchContent')
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="rq" placeholder="日期" value="{{$date}}">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="zjzh" placeholder="资金账号">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="khxm" placeholder="客户姓名">
                                </div>
                                <div class="col-sm-2">
                                    <select name="jgbz" id="jgbz" class="form-control">
                                        <option value="">客户类型(全部)</option>
                                        <option value="0">个人</option>
                                        <option value="1">机构</option>
                                        <option value="2">自营</option>
                                        <option value="3">特殊客户</option>
                                    </select>
                                </div>
                                 <div class="col-sm-12" style="height: 10px;"></div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="bzj_rate" placeholder="公司风险度" >
                                </div>

                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="jys_rate" placeholder="交易所风险度" >
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="pz1_rate" placeholder="品种集中度" >
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control " id="exp1" placeholder="品种敞口" >
                                </div>
                                    <div class="col-sm-12" style="height: 10px;"></div>
                                <div class="col-sm-2">
                                    <label for="">连续两日</label>
                                    <input type="checkbox" id="two">
                                </div>
                            @endslot
                        @endcomponent

                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{URL::asset('/js/admin/func/risk/index.js')}}"></script>
    <script src="{{URL::asset('/include/simple-calendar/javascripts/simple-calendar.js')}}"></script>
@endsection
