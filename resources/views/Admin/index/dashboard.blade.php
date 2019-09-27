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
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">我的活跃内容</h5>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-widget="remove">
                            <i class="fa fa-times"></i>
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
                                <li>
                                    <i class="fa fa-circle-o"></i> {{ $footprint->simple_desc }}</li>
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
    </div>


    <div class="row">
        <section class="col-lg-7">
            <!-- Main row -->
            <div class="card">
                <div class="card-body">
                    <!-- The timeline -->
                    <div class="timeline timeline-inverse">
                        <!-- timeline time label -->
                        <div class="time-label">
                            <span class="bg-danger">
                                10 Feb. 2014
                            </span>
                        </div>
                        <!-- /.timeline-label -->
                        <!-- timeline item -->
                        <div>
                            <i class="fa fa-envelope bg-primary"></i>

                            <div class="timeline-item">
                                <span class="time">
                                    <i class="far fa-clock"></i> 12:05</span>

                                <h3 class="timeline-header">
                                    <a href="#">Support Team</a> sent you an email</h3>

                                <div class="timeline-body">
                                    Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem plugg dopplr jibjab, movity
                                    jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle quora plaxo ideeli
                                    hulu weebly balihoo...
                                </div>
                                <div class="timeline-footer">
                                    <a href="#" class="btn btn-primary btn-sm">Read more</a>
                                    <a href="#" class="btn btn-danger btn-sm">Delete</a>
                                </div>
                            </div>
                        </div>
                        <!-- END timeline item -->
                        <!-- timeline item -->
                        <div>
                            <i class="fa fa-user bg-info"></i>

                            <div class="timeline-item">
                                <span class="time">
                                    <i class="far fa-clock"></i> 5 mins ago</span>

                                <h3 class="timeline-header border-0">
                                    <a href="#">Sarah Young</a> accepted your friend request
                                </h3>
                            </div>
                        </div>
                        <!-- END timeline item -->
                        <!-- timeline item -->
                        <div>
                            <i class="fa fa-comments bg-warning"></i>

                            <div class="timeline-item">
                                <span class="time">
                                    <i class="far fa-clock"></i> 27 mins ago</span>

                                <h3 class="timeline-header">
                                    <a href="#">Jay White</a> commented on your post</h3>

                                <div class="timeline-body">
                                    Take me to your leader! Switzerland is small and neutral! We are more like Germany, ambitious and misunderstood!
                                </div>
                                <div class="timeline-footer">
                                    <a href="#" class="btn btn-warning btn-flat btn-sm">View comment</a>
                                </div>
                            </div>
                        </div>
                        <!-- END timeline item -->
                        <!-- timeline time label -->
                        <div class="time-label">
                            <span class="bg-success">
                                3 Jan. 2014
                            </span>
                        </div>
                        <!-- /.timeline-label -->
                        <!-- timeline item -->
                        <div>
                            <i class="fa fa-camera bg-purple"></i>

                            <div class="timeline-item">
                                <span class="time">
                                    <i class="far fa-clock"></i> 2 days ago</span>

                                <h3 class="timeline-header">
                                    <a href="#">Mina Lee</a> uploaded new photos</h3>

                                <div class="timeline-body">
                                    <img src="http://placehold.it/150x100" alt="...">
                                    <img src="http://placehold.it/150x100" alt="...">
                                    <img src="http://placehold.it/150x100" alt="...">
                                    <img src="http://placehold.it/150x100" alt="...">
                                </div>
                            </div>
                        </div>
                        <!-- END timeline item -->
                        <div>
                            <i class="far fa-clock bg-gray"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="col-lg-5">
            <!-- Main row -->
            <div class="card">
                <div class="card-header border-transparent">
                    <div class="ibox-title">
                        <h5>
                            <i class="fa fa-th-list"></i> 服务器信息</h5>
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
            var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
            var pieData = {
                labels: [
                    @foreach($data['footprint'] as $footprint)
                        "{{ $footprint->simple_desc }}( {{ round(($footprint->c)/$data['pie_all'],2)*100 }}% )",
                    @endforeach
                ],
                datasets: [
                    {
                        data: [{{ $data['pie_datas'] }}],
                        backgroundColor : ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#d2d6de'],
                    }
                ]
                }
            var pieOptions = {
                legend: {
                    display: false,
                },
                segmentShowStroke : true,
                percentageInnerCutout : 100
            }
            var pieChart = new Chart(pieChartCanvas, {
                type: 'doughnut',
                data: pieData,
                options: pieOptions
            })
        </script>
<!-- /.content -->