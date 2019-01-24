<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> {{ config('admin.name') }} </title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{URL::asset('/include/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Bootstrap-table -->
    <link rel="stylesheet" href="{{URL::asset('/include/bootstrap-table/bootstrap-table.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{URL::asset('/include/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{URL::asset('/include/Ionicons/css/ionicons.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{URL::asset('/css/admin/common/skins/_all-skins.min.css')}}">
    <!-- Toastr -->
    <link rel="stylesheet" href="{{URL::asset('/include/toastr/toastr.min.css')}}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{URL::asset('/include/iCheck/minimal/blue.css')}}">
    <!-- nprogress -->
    <link rel="stylesheet" href="{{URL::asset('/include/nprogress/nprogress.css')}}">
    <!-- sweetalert2 -->
    <link rel="stylesheet" href="{{URL::asset('/include/sweetalert2/sweetalert2.min.css')}}">
    <!-- select2 -->
    <link rel="stylesheet" href="{{URL::asset('/include/select2/dist/css/select2.min.css')}}">
    <!-- laydate -->
    <link rel="stylesheet" href="{{URL::asset('/include/laydate/theme/default/laydate.css')}}">
    <!-- switch -->
    <link rel="stylesheet" href="{{URL::asset('/include/bootstrap-switch/css/bootstrap3/bootstrap-switch.min.css')}}">
    <!-- Daterange picker -->
    {{-- <link rel="stylesheet" href="{{URL::asset('/include/bootstrap-daterangepicker/daterangepicker.css')}}"> --}}
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="{{URL::asset('/include/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{URL::asset('/css/admin/common/AdminLTE.min.css')}}">

    <!-- app.css -->
    <link rel="stylesheet" href="{{URL::asset('/css/admin/app.css')}}">

    <!-- jQuery -->
    <script src="{{URL::asset('/include/jquery/jquery-3.3.1.min.js')}}"></script>
    {{-- <script src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script> --}}

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body class="hold-transition skin-blue fixed sidebar-mini">

    <div class="wrapper">
        <script>
            function LA() {}
            LA.token = '{{ csrf_token() }}';
        </script>

        {{-- header --}} 
        @include('admin.layouts.header')

        {{-- left bar --}} 
        @include('admin.layouts.sidebar')

        {{-- wrapper content --}}
        @yield('wrapper-content')

        {{-- model --}} 
        @include('admin.layouts.model')


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane" id="control-sidebar-home-tab">
                    <h3 class="control-sidebar-heading">Recent Activity</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
                                    <p>Will be 23 on April 24th</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-user bg-yellow"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>
                                    <p>New phone +1(800)555-1234</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>
                                    <p>nora@example.com</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-file-code-o bg-green"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>
                                    <p>Execution time 5 seconds</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                    <h3 class="control-sidebar-heading">Tasks Progress</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Custom Template Design
                                    <span class="label label-danger pull-right">70%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Update Resume
                                    <span class="label label-success pull-right">95%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Laravel Integration
                                    <span class="label label-warning pull-right">50%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Back End Framework
                                    <span class="label label-primary pull-right">68%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->

                <!-- Stats tab content -->
                <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                <!-- /.tab-pane -->
                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">
                    <form method="post">
                        <h3 class="control-sidebar-heading">General Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                              Report panel usage
                              <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Some information about this general settings option
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                              Allow mail redirect
                              <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Other sets of options are available
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                              Expose author name in posts
                              <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Allow the user to show his name in blog posts
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <h3 class="control-sidebar-heading">Chat Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                            Show me as online
                            <input type="checkbox" class="pull-right" checked>
                          </label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                              Turn off notifications
                              <input type="checkbox" class="pull-right">
                            </label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                              Delete chat history
                              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
                            </label>
                        </div>
                        <!-- /.form-group -->
                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->

    {{-- foot script --}}
    @include('admin.layouts.footer')
    <script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
    <script src="/js/app.js"></script>
    <script>
        let userId = {{ Auth::user()->id }}
        Echo.private('App.Models.Admin.Admin.SysAdmin.' + userId).notification(function(notification){
            console.log(notification)
            let typeName = "";
            if(notification.typeName == 1){
                typeName = "系统公告";
            }else if(notification.typeName == 2){
                typeName = "RPA通知";
            }else{
                typeName = "管理员通知";
            }
            let html = "";
            html += '<div class="notify-wrap">'
                    + '<div class="notify-title">' + typeName + '<span class="notify-off"><i class="icon iconfont">&#xe6e6;</i></span></div>'
                    + '<div class="notify-title"><a href="JavaScript:void(0);" url="/admin/sys_message_list/view/'+ notification.message_id +'" onclick="operation($(this));" title="查看站内信息">' + notification.title + '</a><div>'
                    + '<div class="notify-content">' + notification.content + '</div>'
                    + '</div>';

            $("body").append(html);
            $(".notify-wrap").slideDown(2000);
            setTimeout(function(){
                $(".notify-wrap").slideUp(2000);
            },8000);
        });
//        window.Echo.channel('message').listen('Message',function(e){
//            console.log(e);
//        });
    </script>
</body>
</html>