@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @component('admin.widgets.toolbar')
                            @slot('listsOperation')
{{--                                <li><a class="dropdown-item" href="javascript:void(0)" id="exportAll">导出全部</a></li>--}}
{{--                                <li><a class="dropdown-item" href="javascript:void(0)" id="export">导出选中</a></li>--}}
                            @endslot

                            @slot('operation')
                            @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')
                                <div class="col-sm-2">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="客户姓名(企业名称)" >
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" id="idCard" name="idCard" class="form-control" placeholder="身份证号(企业统一社会信用代码)">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" id="code" name="code" class="form-control" placeholder="流程号" >
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="date" name="date" placeholder="查询日期">
                                </div>
                                <div class="col-sm-12" style="height: 10px;"></div>
                                <div class="col-sm-2">
                                    <select name="type" id="type" class="form-control">
                                        <option value="">类型(全部)</option>
                                        <option value="1">个人</option>
                                        <option value="2">企业</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <select name="status" id="status" class="form-control">
                                        <option value="">状态(全部)</option>
                                        <option value="0">初始化</option>
                                        <option value="1">正在查询</option>
                                        <option value="2">无失信</option>
                                        <option value="3">有失信</option>
                                        <option value="4">查询失败</option>
                                    </select>
                                </div>


                            @endslot
                        @endcomponent
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{URL::asset('/js/admin/Auxiliary/credit/index.js')}}"></script>
@endsection

