@extends('admin.layouts.wrapper-content')
@section('content')
    <script src="{{URL::asset('/include/echarts/echarts.min.js')}}"></script>
{{--    <script src="{{URL::asset('/include/echarts/echarts-theme.js')}}"></script>--}}
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="panel">
                    <div class="panel-body">
                        <h6 class="mt-3">今日总客户数</h6>
                        <h6 class="text-muted mt-2">88 位</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel  panel-intro panel-statistics">
                    <div class="panel-body">
                        <h6 class="mt-3">今日客户总等待时长</h6>
                        <h6 class="text-muted mt-2">164 （分钟）</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel  panel-intro panel-statistics">
                    <div class="panel-body">
                        <h6 class="mt-3">今日客户平均等待时长</h6>
                        <h6 class="text-muted mt-2">1.35 （分钟）</h6>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="panel  panel-intro panel-statistics">
                    <div class="panel-body">
                        <h6 class="mt-3">今日总客户数</h6>
                        <h6 class="text-muted mt-2">88 位</h6>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div id="echart">

                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card videoTop">
                <div class="card-header border-0">
                    <h2 class="card-title">视频榜单</h2>
                    <div class="card-tools">
                        <a href="#" class="btn btn-tool btn-sm">
                            <i class="fa fa-download"></i>
                        </a>
                        <div class="btn-group btn-group-toggle dropdown" data-toggle="buttons" id="topType">
                            <label class="btn btn-sm btn-secondary active" data-v="today" >
                                <input type="radio" name="options" autocomplete="off" checked=""> 今日
                            </label>
                            <label class="btn btn-sm btn-secondary" data-v="week" >
                                <input type="radio" name="options" autocomplete="off"> 本周
                            </label>
                            <label class="btn btn-sm btn-secondary" data-v="month">
                                <input type="radio" name="options" autocomplete="off"> 本月
                            </label>
                            <label class="btn btn-sm btn-secondary" data-v="custom">
                                <input type="radio" name="options" autocomplete="off"> 自定义
                            </label>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <div class="form-group custom" style="display:none;">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                            <input type="text" class="form-control float-right" id="reservation">
{{--                                        <input type="button" class="btn btn-xs btn-dark" value="查询">--}}
                        </div>
                        <!-- /.input group -->
                    </div>
                    <table class="table table-striped table-valign-middle">
                        <thead>
                        <tr>
                            <th>姓名</th>
                            <th>总次数</th>
                            <th>总成功数</th>
                            <th>总失败数</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>许亮</td>
                            <td>35</td>
                            <td>
                                <small class="text-success mr-1"><i class="fa fa-arrow-up"></i>
                                    12%
                                </small>
                                33
                            </td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>许亮</td>
                            <td>35</td>
                            <td>
                                <small class="text-success mr-1"><i class="fa fa-arrow-up"></i>
                                    12%
                                </small>
                                33
                            </td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>许亮</td>
                            <td>35</td>
                            <td>
                                <small class="text-success mr-1"><i class="fa fa-arrow-up"></i>
                                    12%
                                </small>
                                33
                            </td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>许亮</td>
                            <td>35</td>
                            <td>
                                <small class="text-success mr-1"><i class="fa fa-arrow-up"></i>
                                    12%
                                </small>
                                33
                            </td>
                            <td>2</td>
                        </tr>
                        <tr>
                            <td>许亮</td>
                            <td>35</td>
                            <td>
                                <small class="text-success mr-1"><i class="fa fa-arrow-up"></i>
                                    12%
                                </small>
                                33
                            </td>
                            <td>2</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex justify-content-between">
                        <h3 class="card-title">客户等待</h3>
                        <a href="javascript:void(0);">查看详细报告</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex">
                        <p class="d-flex flex-column">
                            <span class="text-bold text-lg">820</span>
                            <span>请求开户数</span>
                        </p>
                        <p class="ml-auto d-flex flex-column text-right">
                            <span class="text-success">
                              <i class="fa fa-arrow-up"></i> 12.5%
                            </span>
                            <span class="text-muted">Since last week</span>
                        </p>
                    </div>
                    <!-- /.d-flex -->

                    <div class="position-relative mb-4">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="visitors-chart" height="200" width="487" class="chartjs-render-monitor"
                                style="display: block; width: 487px; height: 200px;"></canvas>
                    </div>

                    <div class="d-flex flex-row justify-content-end">
                        <span class="mr-2">
                            <i class="fa fa-square text-primary"></i> This Week
                        </span>

                        <span>
                            <i class="fa fa-square text-gray"></i> Last Week
                        </span>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>

    <link rel="stylesheet" href="{{URL::asset('/include/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/css/admin/func/kpi/kpi.css')}}">

    <script src="{{URL::asset('/include/moment/moment.min.js')}}"></script>
    <script src="{{URL::asset('/include/daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{URL::asset('/js/admin/func/kpi/index.js')}}"></script>
@endsection