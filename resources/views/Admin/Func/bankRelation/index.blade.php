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
{{--                                <a class="btn btn-default btn-sm instructions"  title="说明">--}}
{{--                                    <i class="fa fa-question-circle"></i>--}}
{{--                                </a>--}}
                            @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')
                                <div class="col-sm-2">
                                    <select name="relation_status" id="relation_status" class="form-control">
                                        <option value="">关联结果(全部)</option>
                                        <option value="0">未关联</option>
                                        <option value="1">关联成功</option>
                                        <option value="2">关联失败</option>
                                        <option value="3">无需关联</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select name='bank_name' id='bank_name' class='form-control'>
                                        <option value="">银行(全部)</option>
                                        @foreach($bankList as $v)
                                            <option value="{{$v}}">{{$v}}</option>
                                        @endforeach
                                        <option value="three">兴业、民生、建行</option>
                                        <option value="notThree">非兴业、民生、建行</option>
                                    </select>  
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" id="zjzh" name="zjzh" class="form-control" placeholder="资金账号" >
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="客户姓名" >
                                </div>
                                <div class="col-sm-12" style="height: 10px;"></div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="startTime" placeholder="开始时间">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="endTime" placeholder="结束时间">
                                </div>
                                <div>
                                    <input type="hidden" id="uid" name="uid" value="{{ $uid }}">
                                </div>
                            @endslot
                        @endcomponent
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{URL::asset('/js/admin/func/bankRelation/index.js')}}"></script>
@endsection
