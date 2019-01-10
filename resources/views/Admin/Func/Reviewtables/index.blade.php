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
            <label for="name" class="control-label col-sm-1">回访人:</label>
            <div class="col-sm-2">
                <select name="name" id="name" class="form-control">
                    <option value="" selected>无</option>
                    @foreach($list as $name)
                        @if(session('sys_admin')['realName'] == $name['realName'])
                            <option value="{{ $name['realName'] }}" selected>{{ $name['realName'] }}</option>
                        @else
                            <option value="{{ $name['realName'] }}">{{ $name['realName'] }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <label for="name" class="control-label col-sm-1">状态:</label>
            <div class="col-sm-2">
                <select name="status" id="status" class="form-control">
                    <option value="" selected>无</option>
                    <option value="1">已回访</option>
                    <option value="0">未回访</option>
                </select>
            </div>
            <label for="startTime" class="control-label col-sm-1">时间:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="startTime" placeholder="请选择时间">
            </div>
            <div style="float:left;">-</div>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="endTime" placeholder="请选择时间">
            </div>
            <br>
            <label for="customer" class="control-label col-sm-1">客户:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="customer" placeholder="请输入客户姓名或资金账号">
            </div>
            <label for="videoPeopName" class="control-label col-sm-1">视频人:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="videoPeopName" placeholder="请输入视频人姓名">
            </div>
            <label for="checkPeopName" class="control-label col-sm-1">审核人:</label>
            <div class="col-sm-2">
                <input type="text" class="form-control" id="checkPeopName" placeholder="请输入审核人姓名">
            </div>
            @endslot
            @endcomponent
            <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>

        </div>
    </div>

    <script src="{{URL::asset('/js/admin/func/Reviewtables/index.js')}}"></script>
@endsection