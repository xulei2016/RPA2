@extends('admin.layouts.wrapper-content')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                
                        @component('admin.widgets.toolbar')
                            @slot('listsOperation')
                                @if(auth()->guard('admin')->user()->can('sys_logs_export'))
                                    <li><a class="dropdown-item" href="javascript:void(0)" id="exportAll">导出全部</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0)" id="export">导出选中</a></li>
                                @endcan
                            @endslot
                            @slot('operation')
                            @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="name" placeholder="账号">
                                </div>
                            @endslot
                        @endcomponent


                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="{{URL::asset('/js/admin/base/logs/index.js')}}"></script>
@endsection