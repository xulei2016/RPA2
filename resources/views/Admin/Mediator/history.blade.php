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
                                <a class="btn btn-primary btn-sm" href="/admin/mediator">返回</a>
                        @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                        @slot('searchContent')
                        <input type="hidden" id="uid" value="{{ $id }}">
                        <div class="col-sm-2">
                            <select name="flow_status" id="flow_status" class="form-control">
                                <option value="" selected>审核状态:全部</option>
                                <option value="1">待审核</option>
                                <option value="2">待确认比例</option>
                                <option value="3">正在办理</option>
                                <option value="4">办理完成</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="type" id="type" class="form-control">
                                <option value="" selected>类型:全部</option>
                                <option value="0">新签</option>
                                <option value="1">续签</option>
                                <option value="2">变更</option>
                                <option value="3">注销</option>
                            </select>
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

    <script src="{{URL::asset('/js/admin/Mediator/history.js')}}"></script>
@endsection