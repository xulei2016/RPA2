@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="panel box box-primary">
        <div class="box-body">
            @component('admin.widgets.toolbar')
            @slot('listsOperation')
            @endslot

            @slot('operation')
            @endslot
            @endcomponent

            @component('admin.widgets.search-group')
            @slot('searchContent')
            <label for="phone" class="control-label col-sm-1">手机号:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="phone" placeholder="手机号">
            </div>
            <label for="api" class="control-label col-sm-1">平台:</label>
            <div class="col-sm-2">
                <select name="api" id="api" class="form-control">
                    <option value="" selected>全部</option>
                    <option value="中正云">中正云</option>
                    <option value="优信">优信</option>
                </select>
            </div>
            <br/><br>
            <label for="startTime" class="control-label col-sm-1">时间:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="startTime" placeholder="请选择时间">
            </div>
            <div style="float:left;">-</div>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="endTime" placeholder="请选择时间">
            </div>
            @endslot
            @endcomponent
            <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>

        </div>
    </div>

    <script src="{{URL::asset('/js/admin/base/message/sms_list.js')}}"></script>
@endsection