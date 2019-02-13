<header class="main-header">

        <!-- Logo -->
        <a href="index2.html" class="logo">
          <span class="logo-mini"> {!! config('admin.logo-mini') !!} </span>
          <span class="logo-lg"> {!! config('admin.logo') !!} </span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
    
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

              <li class="hidden-xs">
                  <a href="#" data-toggle="fullscreen"><i class="fa fa-arrows-alt"></i></a>
              </li>
              <!-- Messages -->
              <li class="dropdown messages-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-envelope-o"></i>
                  <span class="label label-success">{{ \App\models\admin\base\SysUserMail::mailCount() }}</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">你有 {{ \App\models\admin\base\SysUserMail::mailCount() }} 条未读邮件</li>
                  <li>
                    <ul class="menu">
                    @foreach(\App\models\admin\base\SysUserMail::maillist() as $v)
                      <li>
                        <a onclick="operation($(this));" url='/admin/sys_mail/{{$v->mid}}'>
                          <div class="pull-left">
                            <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            {{env('APP_NAME')}}
                            <small><i class="fa fa-clock-o"></i>{{ \Carbon\Carbon::parse($v->mails->created_at)->diffForHumans()}}</small>
                          </h4>
                          <p>{{$v->mails->title}}</p>
                        </a>
                      </li>
                    @endforeach
                    </ul>
                  </li>
                  <li class="footer"><a href="#">查看所有邮件</a></li>
                </ul>
              </li>

              <!-- Notifications -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  @if(Auth::user()->notification_count >0)
                    <span class="label label-warning">{{ Auth::user()->notification_count}}</span>
                  @endif
                </a>
                @if(Auth::user()->notification_count >0)
                <ul class="dropdown-menu">
                  <li class="header">您有 {{ Auth::user()->notification_count}} 条新消息未读</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      @foreach(Auth::user()->unreadNotifications as $v)
                        @if($loop->index < 5)
                          <li><a href="javascript:;" onclick="operation($(this));" url="/admin/sys_message_list/view/{{$v->id}}"><i class="fa fa-users text-aqua"></i> {{$v->data['title']}}</a></li>
                        @endif
                      @endforeach
                    </ul>
                  </li>
                  <li class="footer"><a href="#">查看全部消息</a></li>
                </ul>
                @endif
              </li>

              <!-- User Account -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <img src="{{ URL::asset(session('sys_admin')['headImg']) }}" class="user-image" alt="User Image">
                  <span class="hidden-xs">{{ session('sys_admin')['name'] }}</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <img src="{{ URL::asset(session('sys_admin')['headImg']) }}" class="img-circle" alt="User Image">
    
                    <p>
                      victor
                      <small>Member since Nov. 2012</small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <li class="user-body">
                        <div class="row">
                            <div class="col-xs-4 text-center">
                                <a href="#" target="_blank">#</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="#" target="_blank">#</a>
                            </div>
                            <div class="col-xs-4 text-center">
                                <a href="javascript:void(0);" onclick="RPA.clearCache();" target="_blank">清除缓存</a>
                            </div>
                        </div>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="/admin/sys_profile" class="btn btn-primary addtabsit"><i class="fa fa-user"></i>
                                个人配置</a>
                        </div>
                        <div class="pull-right">
                            <a href="/admin/logout" class="btn btn-danger"><i class="fa fa-sign-out"></i>
                                注销</a>
                        </div>
                    </li>
                </ul>
              </li>
              
              <!-- Control Sidebar Toggle Button -->
              <li>
                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
              </li>
            </ul>
          </div>
        </nav>
      </header>