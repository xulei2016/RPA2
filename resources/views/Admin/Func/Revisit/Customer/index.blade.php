@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        @component('admin.widgets.toolbar')
                            @slot('listsOperation')

                            @endslot
                            @slot('operation')
                            @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')
                                <div class="col-sm-2">
                                    <select name="status" id="status" class="form-control">
                                        <option value="" selected>状态:全部</option>
                                        <option value="2">待审核</option>
                                        <option value="0">未回访</option>
                                        <option value="3">已归档</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="customer" placeholder="客户姓名或资金账号">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="yybName" placeholder="营业部名称">
                                </div>
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


    <script src="{{URL::asset('/js/admin/Revisit/customer/index.js')}}"></script>
@endsection