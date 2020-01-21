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
                                    <a class="btn btn-primary btn-sm" href="/admin/rpa_archives/create">新增</a>
                            @endslot
                        @endcomponent

                        @component('admin.widgets.search-group')
                            @slot('searchContent')

                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="name" placeholder="客户名称">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="zjbh" placeholder="证件编号">
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

    <script src="{{URL::asset('/js/admin/func/Archives/index.js')}}"></script>
@endsection