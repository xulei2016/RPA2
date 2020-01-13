@extends('admin.layouts.wrapper-content')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title">数据分析</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
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
                                    <select name="state" id="state" class="form-control">
                                        <option value="0" selected>平台用户</option>
                                        <option value="1" disabled>掌厅用户群</option>
                                        <option value="2" disabled>员工端用户群</option>
                                        <option value="3" disabled>CRM用户群</option>
                                    </select>
                                </div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control" id="username" disabled placeholder="用户名称">
                                </div>
                                <div class="col-sm-2">
                                    <input type="date" class="form-control" id="startTime" placeholder="开始时间">
                                </div>
                                <div class="col-sm-2">
                                    <input type="date" class="form-control" id="endTime" placeholder="结束时间">
                                </div>
                            @endslot
                        @endcomponent
                    </div>
                </div>
            </div>

            <div class="row" style="width: 100%; margin: 0;">
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title">区域排名（省份）</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                            class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="tfrequency_region" style="display: block;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title">区域排名(城市)</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                            class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="tfrequency_city" style="display: block;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="width: 100%; margin: 0;">
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title">累计登录频次分析</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                            class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="tfrequency" style="display: block;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h5 class="card-title">累计登录频次分析</h5>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                            class="fa fa-minus"></i></button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                            class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="tfrequency_day" style="display: block;"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title">用户热力图分析</h5>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i
                                        class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                        class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="card-body">

                        <div id="heatmap" style="height:1000px;width:100%;">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="//api.map.baidu.com/api?v=2.0&ak={{env('BAIDU_MAP_AK')}}}"></script>
    <script type="text/javascript" src="//api.map.baidu.com/library/Heatmap/2.0/src/Heatmap_min.js"></script>
    <script src="{{URL::asset('/include/charts/Chart.min.js')}}"></script>
    <script src="{{URL::asset('/js/admin/MDC/DA/index.js')}}"></script>
@endsection