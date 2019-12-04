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
                        <span class="info-box-number accumulated_time">{{ $data['total'] }}</span>
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
                        <span class="info-box-text">总在线时长</span>
                        <span class="info-box-number">{{ $data['total_time'] }} 分钟</span>
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
                        <span class="info-box-number">{{ $data['total_login'] }}</span>
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
                            @endslot
                        @endcomponent
                        <table id="tb_departments" class="table table-striped table-hover table-bordered"></table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{URL::asset('/js/admin/Hadmy/statistics/index.js')}}"></script>
@endsection