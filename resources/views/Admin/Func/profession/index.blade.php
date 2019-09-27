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
                        @endslot
                        @slot('operation')
                        @endslot
                        @endcomponent
                        @component('admin.widgets.search-group')
                        @slot('searchContent')
                        <div class="col-sm-2">
                            <select name="status" id="status" class="form-control">
                                <option value="" selected>状态</option>
                                <option value="1">登录</option>
                                <option value="3">申请完成</option>
                                <option value="4">变更成功</option>
                                <option value="5">变更失败</option>
                                <option value="6">适当性户</option>
                            </select>
                        </div>

                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="name" placeholder="客户姓名">
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="zjzh" placeholder="资金账号">
                        </div>
                        @endslot
                        @endcomponent
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{URL::asset('/js/admin/func/profession/index.js')}}"></script>
@endsection