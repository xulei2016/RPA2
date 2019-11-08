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
                                <label for="name" class="control-label col-sm-1">状态:</label>
                                <div class="col-sm-2">
                                    <select name="status" id="status" class="form-control">
                                        <option value="" selected>无</option>
                                        <option value="0">待处理</option>
                                        <option value="1">已归档</option>
                                        <option value="2">已打回</option>
                                    </select>
                                </div>
                                <label for="startTime" class="control-label col-sm-1">时间:</label>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="startTime" placeholder="请选择时间" autocomplete="off">
                                </div>
                                <div style="float:left;">-</div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="endTime" placeholder="请选择时间" autocomplete="off">
                                </div>
                            @endslot

                        @endcomponent
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{URL::asset('/js/admin/func/CustomerVideos/index.js')}}"></script>
@endsection