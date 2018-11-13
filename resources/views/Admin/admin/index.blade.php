@extends('admin.layouts.wrapper-content')

@section('content')

    <div class="panel">

        @component('admin.widgets.toolbar')
        @endcomponent

        @component('admin.widgets.search-group')
            @slot('searchContent')
            <label class="control-label col-sm-1" for="name">姓名</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="name">
                </div>
                <label class="control-label col-sm-1" for="role">所属角色</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="role">
                </div>
                <label class="control-label col-sm-1" for="status">状态</label>
                <div class="col-sm-2">
                    <input type="text" class="form-control" id="status">
                </div>
            @endslot
        @endcomponent

         
        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
    </div>

<script src="{{URL::asset('/js/admin/admin/index.js')}}"></script>
@endsection