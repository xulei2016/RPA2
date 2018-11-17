@extends('admin.layouts.wrapper-content')

@section('content')

<div class="col-md-3">
    <!-- Profile Image -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">About Me</h3>
      </div>

      <div class="box-body box-profile">
        <img class="profile-user-img img-responsive img-circle" src="{{ $info->head_img }}" alt="User profile picture">
        <h3 class="profile-username text-center">{{ $info->name }}</h3>
        <p class="text-muted text-center">{{ $info->roleLists }}</p>
        
        <hr>

        <strong><i class="fa fa-book margin-r-5"></i> Education</strong>

        <p class="text-muted">
          B.S. in Computer Science from the University of Tennessee at Knoxville
        </p>

        <hr>

        <strong><i class="fa fa-map-marker margin-r-5"></i> Location</strong>

        <p class="text-muted">Malibu, California</p>

        <hr>

        <strong><i class="fa fa-pencil margin-r-5"></i> Skills</strong>

        <p>
          <span class="label label-danger">UI Design</span>
          <span class="label label-success">Coding</span>
          <span class="label label-info">Javascript</span>
          <span class="label label-warning">PHP</span>
          <span class="label label-primary">Node.js</span>
        </p>

        <hr>

        <strong><i class="fa fa-file-text-o margin-r-5"></i> Notes</strong>

        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam fermentum enim neque.</p>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
</div>

<div class="col-md-9">
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#settings" data-toggle="tab" aria-expanded="true">基础设置</a></li>
        <li class=""><a href="#changePWD" data-toggle="tab" aria-expanded="false">修改密码</a></li>
        <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">安全设置</a></li>
        <li class=""><a href="#another" data-toggle="tab" aria-expanded="false">其他</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="settings">
        <form class="form-horizontal">

            <div class="form-group">
              <label class="col-sm-2 control-label">头像</label>
              <div class="col-sm-10">
                  <img class="img-responsive img-circle" src="{{ $info->head_img }}" alt="用户头像" width="50px">
              </div>
            </div>

            <div class="form-group">
              <label class="col-sm-2 control-label">账户名</label>
              <div class="col-sm-10">
                  <input type="text" class="form-control" value="{{ $info->name }}" disabled>
              </div>
            </div>

            <div class="form-group">
              <label for="inputName" class="col-sm-2 control-label">姓名</label>
              <div class="col-sm-10">
                  <input type="email" class="form-control" name="realName" value="{{ $info->realName }}" id="inputName" placeholder="真实姓名">
              </div>
            </div>

            <div class="form-group">
              <label for="phone" class="col-sm-2 control-label">手机号</label>
              <div class="col-sm-10">
                  <input type="email" class="form-control" name="phone" value="{{ $info->phone }}" id="phone" placeholder="手机号码">
              </div>
            </div>

            <div class="form-group">
              <label for="inputEmail" class="col-sm-2 control-label">Email</label>
              <div class="col-sm-10">
                  <input type="email" class="form-control" name="email" value="{{ $info->email }}" id="inputEmail" placeholder="Email">
              </div>
            </div>

            <div class="form-group">
              <label for="desc" class="col-sm-2 control-label">自我描述</label>
              <div class="col-sm-10">
                  <textarea class="form-control" id="desc" name="desc" placeholder="desc">{{ $info->desc }}</textarea>
              </div>
            </div>
            
            <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                  <button type="button" class="btn btn-danger submit settings">提交</button>
              </div>
            </div>
        </form>
      </div>
        <!-- /.tab-pane -->
    
      <div class="tab-pane" id="changePWD">
          <form class="form-horizontal">
  
              <div class="form-group">
                <label for="oriPWD" class="col-sm-2 control-label">原始密码</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="oriPWD" id="oriPWD" placeholder="原始密码" required>
                </div>
              </div>
  
              <div class="form-group">
                <label for="password" class="col-sm-2 control-label">新密码</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control" name="password" id="password" placeholder="新密码" required>
                </div>
              </div>
  
              <div class="form-group">
                <label for="rePWD" class="col-sm-2 control-label">确认新密码</label>
                <div class="col-sm-10">
                    <input type="rePWD" class="form-control" name="rePWD" id="rePWD" placeholder="确认新密码" required>
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button type="button" class="btn btn-danger submit changePWD">提交</button>
                </div>
              </div>
          </form>
      </div>
      <!-- /.tab-pane -->

      <div class="tab-pane" id="timeline">
          <div class="text-center">正在开发中。。。</div>
      </div>

      <!-- /.tab-pane -->
      <div class="tab-pane" id="another">
          <div class="text-center">正在开发中。。。</div>
      </div>
      <!-- /.tab-pane -->

    </div>
    <!-- /.tab-content -->
  </div>
</div>

<script src="{{URL::asset('/js/admin/base/admin/index.js')}}"></script>

@endsection