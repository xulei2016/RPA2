@extends('admin.layouts.wrapper-content')

@section('content')
    <div class="container-fluid">

        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                <span class="info-box-icon bg-info elevation-1">
                    <i class="fa fa-user"></i>
                </span>

                    <div class="info-box-content">
                        <span class="info-box-text">在线客户</span>
                        <span class="info-box-number">{{ $data['online'] }}
                    </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                <span class="info-box-icon bg-danger elevation-1">
                    <i class="fa fa-users"></i>
                </span>

                    <div class="info-box-content">
                        <span class="info-box-text">总客户数</span>
                        <span class="info-box-number total_count">{{ $data['total'] }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix hidden-md-up"></div>

            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                <span class="info-box-icon bg-success elevation-1">
                    <i class="fa fa-clock-o"></i>
                </span>

                    <div class="info-box-content">
                        <span class="info-box-text">总在线时长（分钟）</span>
                        <span class="info-box-number total_time">{{ $data['total_time'] }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1">
                    <i class="fa fa-sign-in"></i>
                </span>

                    <div class="info-box-content">
                        <span class="info-box-text">总登录次数</span>
                        <span class="info-box-number total_login">{{ $data['total_login'] }}</span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>

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
                                    <input type="text" class="form-control" id="zjzh" placeholder="资金账号">
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="tzjh_account" placeholder="投资江湖账号">
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

        <div class="row">
            <div class="progress-group search-count col-12 col-sm-8 col-md-4">
                当前查询客户数
                <span class="float-right"><b>0</b>/0</span>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-primary"></div>
                </div>
            </div>
            <div class="progress-group search-time col-12 col-sm-8 col-md-4">
                当前查询在线时长
                <span class="float-right"><b>0</b>/0</span>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-danger"></div>
                </div>
            </div>
            <div class="progress-group search-login col-12 col-sm-8 col-md-4">
                当前查询登陆数
                <span class="float-right"><b>0</b>/0</span>
                <div class="progress progress-sm">
                    <div class="progress-bar bg-warning"></div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{URL::asset('/js/admin/Hadmy/statistics/index.js')}}"></script>
@endsection