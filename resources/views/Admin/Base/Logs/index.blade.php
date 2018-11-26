@extends('admin.layouts.wrapper-content')

@section('content')

    <div class="panel box box-primary">
        <div class="box-body">
                
            @component('admin.widgets.toolbar')
                @slot('listsOperation')
                    @if(auth()->guard('admin')->user()->can('sys_logs_export'))
                        <li><a href="javascript:void(0)" id="exportAll">导出全部</a></li>
                        <li><a href="javascript:void(0)" id="export">导出选中</a></li>
                    @endcan
                @endslot
                @slot('operation')
                @endslot
            @endcomponent

            @component('admin.widgets.search-group')
                @slot('searchContent')
                <label class="control-label col-sm-1" for="name">账号</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control" id="name">
                    </div>
                @endslot
            @endcomponent

            
            <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
        </div>
    </div>

<script src="{{URL::asset('/js/admin/base/logs/index.js')}}"></script>
@endsection