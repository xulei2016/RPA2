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
                                <option value="" selected>居间状态:全部</option>
                                <option value="0">未完成</option>
                                <option value="1">正常</option>
                                <option value="2">过期</option>
                                <option value="3">注销</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="flow_status" id="flow_status" class="form-control">
                                <option value="" selected>流程状态:全部</option>
                                <option value="1">待审核</option>
                                <option value="2">待确认比例</option>
                                <option value="3">正在办理</option>
                                <option value="4">办理完成</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="dept_id" id="dept_id" class="form-control">
                                <option value="" selected>部门:全部</option>
                                @foreach($dept as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <input type="text" class="form-control" id="mediator" placeholder="居间姓名或居间编号">
                        </div>
                        <div class="col-sm-2">
                        </div>
                                    <div class="col-sm-2">
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

    <script src="{{URL::asset('/js/admin/Mediator/index.js')}}"></script>
@endsection