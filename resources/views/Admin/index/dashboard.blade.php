<!-- Content Header (Page header) -->

    <!-- Main content -->
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box">
                <span class="info-box-icon bg-info elevation-1">
                    <i class="fa fa-cog"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">总计执行任务</span>
                    <span class="info-box-number">{{ $data['countTask'] }}
                        <small>次</small>
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
                    <i class="fa fa-thumbs-up"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">累计运行</span>
                    <span class="info-box-number accumulated_time">41,410</span>
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
                    <i class="fa fa-shopping-cart"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">接口调用次数</span>
                    <span class="info-box-number">{{ $data['countApi'] }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box mb-3">
                <span class="info-box-icon bg-warning elevation-1">
                    <i class="fa fa-users"></i>
                </span>

                <div class="info-box-content">
                    <span class="info-box-text">用户数-活跃度</span>
                    <span class="info-box-number">{{ $data['countUser'] }}-{{ round($data['countYUser']/$data['countUser'],2)*100 }}%</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        {{--   我的活跃内容   --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">我的活跃内容</h5>
    
                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                        <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    <div class="row">
                    <div class="col-md-8">
                        <div class="chart-responsive">
                            <canvas id="pieChart" height="150"></canvas>
                        </div>
                        <!-- ./chart-responsive -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4">
                        <ul class="chart-legend clearfix">
                        @foreach($data['footprint'] as $footprint)
                            <li><i class="fa fa-circle-o"></i> {{  $footprint->simple_desc }}</li>
                        @endforeach
                        </ul>
                    </div>
                    <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.card-body -->
            </div>
                <!-- /.card -->
        </div>
        {{--   待办事务    --}}
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">待办事务</h5>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-widget="remove"><i class="fa fa-times"></i>
                        </button>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body" style="height: 265px;">
                    <!-- /.row -->
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
    </div>

    
    <div class="row">
            <section class="col-lg-6">
                <!-- Main row -->
                <div class="card">
                    <div class="card-body">
                        <!-- The timeline -->
                        <div class="timeline timeline-inverse">
                                <!-- timeline time label -->
                                <div class="time-label">
                                    <span class="bg-primary">
                                    更 新 日 志
                                    </span>
                                </div>
                                @foreach($versionUpdateList as $v)
                                <div>
                                    @if($v['type'] == 3)
                                        <i class="fa fa-bolt bg-danger"></i>
                                        @elseif($v['type'] == 2)
                                        <i class="fa fa-level-up bg-success"></i>
                                        @else
                                        <i class="fa fa-code-fork bg-primary"></i>
                                    @endif
                                    <div class="timeline-item">
                                        <span class="time"><i class="far fa-clock"></i> {{$v['created_at']}}</span>
                                        <h3 class="timeline-header">
                                            <a href="#">{{$v['created_by']}}</a>
                                            @if($v['type'] == 3)
                                                紧急维护
                                                @elseif($v['type'] == 2)
                                                版本升级
                                                @else
                                                正常更新
                                            @endif
                                        </h3>

                                        <div class="timeline-body">
                                           {!! $v['desc'] !!}
                                        </div>
                                        <div class="timeline-footer">
                                            <a url="/admin/sys_version_update/{{$v['id']}}" class="btn btn-primary btn-sm" onclick="operation($(this))">查看详细</a>
{{--                                            <a href="javascript:;" class="btn btn-danger btn-sm">Delete</a>--}}
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                                <!-- END timeline item -->

                            </div>
                    </div>
                </div>
            </section>
{{--            配置信息--}}
            <section class="col-lg-6">
                <!-- Main row -->
                <div class="card">
                    <div class="card-header border-transparent">
                        <div class="ibox-title">
                            <h5><i class="fa fa-th-list"></i> 服务器信息</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="ibox-content" style="display: block;">
                            <ul class="todo-list m-t small-list">
                                <li>设备信息：{{ $data['sys']['PHP_OS'] }}</li>
                                <li>服务：{{ $data['sys']['SERVER_INFO'] }}</li>
                                <li>脚本版本：{{ $data['sys']['PHP_VERSION'] }}</li>
                                <li>框架版本：{{ $data['sys']['Laravel_VERSION'] }}</li>
                                <li>CGI：{{ $data['sys']['CGI'] }}</li>
                                <li>时区：{{ $data['sys']['TIMEZONE'] }}</li>
                                <li>协议：{{ $data['sys']['SERVER_PROTOCOL'] }}</li>
                                <li>缓存驱动：{{ $data['sys']['CACHE'] }}</li>
                                <li>session驱动：{{ $data['sys']['Session'] }}</li>
                                <li>队列驱动：{{ $data['sys']['QUEUE'] }}</li>
                                <li>允许文件上传最大尺寸：{{ $data['sys']['FILE_UPLOAD_MAX_SIZE'] }}</li>
                                <li>MySQL允许持久连接：{{ $data['database']['ALLOW_PERSISTENT'] }}</li>
                                <li>MySQL最大连接数：{{ $data['database']['ALLOW_PERSISTENT'] }}</li>
                                <li>MySQL版本：{{ $data['database']['MYSQL_VERSION'] }}</li>
                                <li>GD图形处理库：bundled (2.1.0 compatible)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
        </div>    

<script src="{{URL::asset('/include/charts/Chart.min.js')}}"></script>
<script src="{{URL::asset('/js/admin/dashboard.js')}}"></script>
<script>
</script>
</section>
<!-- /.content -->