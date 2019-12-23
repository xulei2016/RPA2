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
    <div class="col-lg-6">
        {{-- 待办流程 --}}
        <div class="card flow">
            <div class="card-header">
                <h5 class="card-title">待办流程</h5>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body" style="height: 288px;overflow-y: scroll;">

            </div>
            <!-- /.card-body -->
            <!-- /.card-body -->
            <div class="card-footer text-center">
                <a href="/admin/sys_flow_mine" class="uppercase">查看更多</a>
            </div>
            <!-- /.card-footer -->
        </div>
        <!-- 更新日志 -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <div class="time-label">
                        <span>更 新 日 志</span>
                    </div>
                </h5>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
                <!-- The timeline -->
                <div class="timeline timeline-inverse">
                    <!-- timeline time label -->
                    @foreach($versionUpdateList as $v)
                    <div>
                        @if($v['type'] == 3)
                        <i class="fa fa-bolt bg-danger"></i> @elseif($v['type'] == 2)
                        <i class="fa fa-level-up bg-success"></i> @else
                        <i class="fa fa-code-fork bg-primary"></i> @endif
                        <div class="timeline-item">
                            <span class="time"><i class="far fa-clock"></i> {{$v['created_at']}}</span>
                            <h3 class="timeline-header">
                                <a href="#">{{$v['created_by']}}</a> @if($v['type'] == 3) 紧急维护 @elseif($v['type'] == 2) 版本升级
                                @else 正常更新 @endif
                            </h3>

                            <div class="timeline-body">
                                {!! $v['desc'] !!}
                            </div>
                            <div class="timeline-footer">
                                <a url="/admin/sys_version_update/{{$v['id']}}" class="btn btn-primary btn-sm" onclick="operation($(this))">查看详细</a>                                {{-- <a href="javascript:;" class="btn btn-danger btn-sm">Delete</a>--}}
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <!-- END timeline item -->

                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
    <div class="col-lg-6">
        {{-- 我的活跃内容 --}}
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">我的活跃内容</h5>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <canvas id="pieChart" height="150"></canvas>
            </div>
            <!-- /.card-body -->
        </div>
        {{-- 服务器磁盘状态 --}}
        <div class="card hidden">
            <div class="card-header">
                <h5 class="card-title">服务器磁盘状态</h5>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
            </div>
            <div class="card-body">
                {{-- @foreach(Cache::get('sys_disk')['DISK'] as $k => $disk)
                    <canvas id="{{$k}}" height="60"></canvas>
                @endforeach --}}
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <div class="time-label">
                        <span><i class="fa fa-th-list"></i> 服务器信息</span>
                    </div>
                </h5>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="card-body">
                <div class="ibox-content" style="display: block;">
                    <ul class="todo-list m-t small-list">
                        <li>设备信息：{{ $data['SYS']['PHP_OS'] }}</li>
                        <li>服务：{{ $data['SYS']['SERVER_INFO'] }}</li>
                        <li>脚本版本：{{ $data['SYS']['PHP_VERSION'] }}</li>
                        <li>框架版本：{{ $data['SYS']['Laravel_VERSION'] }}</li>
                        <li>CGI：{{ $data['SYS']['CGI'] }}</li>
                        <li>时区：{{ $data['SYS']['TIMEZONE'] }}</li>
                        <li>协议：{{ $data['SYS']['SERVER_PROTOCOL'] }}</li>
                        <li>缓存驱动：{{ $data['SYS']['CACHE'] }}</li>
                        <li>session驱动：{{ $data['SYS']['Session'] }}</li>
                        <li>队列驱动：{{ $data['SYS']['QUEUE'] }}</li>
                        <li>允许文件上传最大尺寸：{{ $data['SYS']['FILE_UPLOAD_MAX_SIZE'] }}</li>
                        <li>MySQL允许持久连接：{{ $data['DATABASE']['ALLOW_PERSISTENT'] }}</li>
                        <li>MySQL最大连接数：{{ $data['DATABASE']['ALLOW_PERSISTENT'] }}</li>
                        <li>MySQL版本：{{ $data['DATABASE']['MYSQL_VERSION'] }}</li>
                        <li>GD图形处理库：bundled (2.1.0 compatible)</li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </div>
</div>

<script src="{{URL::asset('/include/charts/Chart.min.js')}}"></script>
<script src="{{URL::asset('/js/admin/dashboard.js')}}"></script>

</section>
<!-- /.content -->