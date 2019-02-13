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
            <label for="name" class="control-label col-sm-1">任务名称:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="name" placeholder="请输入任务名称">
            </div>
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

<script src="{{URL::asset('/js/admin/rpa/statistics/log.js')}}"></script>
@endsection