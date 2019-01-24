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
                  <span class="label label-success">4</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">You have 5 messages</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      <li><!-- start message -->
                        <a href="#">
                          <div class="pull-left">
                            <img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <!-- end message -->
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            AdminLTE Design Team
                            <small><i class="fa fa-clock-o"></i> 2 hours</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            Developers
                            <small><i class="fa fa-clock-o"></i> Today</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="dist/img/user3-128x128.jpg" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            Sales Department
                            <small><i class="fa fa-clock-o"></i> Yesterday</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                      <li>
                        <a href="#">
                          <div class="pull-left">
                            <img src="dist/img/user4-128x128.jpg" class="img-circle" alt="User Image">
                          </div>
                          <h4>
                            Reviewers
                            <small><i class="fa fa-clock-o"></i> 2 days</small>
                          </h4>
                          <p>Why not buy a new awesome theme?</p>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="#">See All Messages</a></li>
                </ul>
              </li>

              <!-- Notifications -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-warning">{{ Auth::user()->notification_count}}</span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">您有 {{ Auth::user()->notification_count}} 条新消息未读</li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                      @foreach(Auth::user()->unreadNotifications as $v)
                        @if($loop->index < 5)
                          <li>
                            <a href="">
                              <i class="fa fa-users text-aqua"></i> {{$v->data['title']}}
                            </a>
                          </li>
                        @endif
                      @endforeach
                    </ul>
                  </li>
                  <li class="footer"><a href="#">查看全部</a></li>
                </ul>
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