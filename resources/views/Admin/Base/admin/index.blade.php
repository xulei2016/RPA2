@extends('admin.layouts.wrapper-content')

@section('content')

<div class="col-md-3">
    <!-- Profile Image -->
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">关于我</h3>
      </div>

      <div class="box-body box-profile">
        <div class="profile-avatar-container">
            <img class="profile-user-img img-responsive img-circle plupload" src="{{ URL::asset($info->head_img) }}" alt="User profile picture"  initialized="true">
            <div class="profile-avatar-text img-circle">点击编辑</div>
            <button id="plupload-avatar" class="plupload" data-input-id="c-avatar" initialized="true" style="/* z-index: 1; */"><i class="fa fa-upload"></i> 上传</button>
        </div>
        <h3 class="profile-username text-center">{{ $info->name }}</h3>
        <p class="text-muted text-center">{{ $info->roleLists }}</p>
        
        <hr>

        <strong><i class="fa fa-book margin-r-5"></i> </strong>

        <p class="text-muted">
          B.S. in Computer Science from the University of Tennessee at Knoxville
        </p>

        <hr>

        <strong><i class="fa fa-map-marker margin-r-5"></i> 位置</strong>

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
        <li class=""><a href="#rpasetting" data-toggle="tab" aria-expanded="false">RPA设置</a></li>
        <li class=""><a href="#another" data-toggle="tab" aria-expanded="false">其他</a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="settings">
        <form class="form-horizontal">

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
                  <input type="hidden" name="type" value="settings">
                  <button type="button" class="btn btn-danger submit">提交</button>
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
                    <input type="hidden" name="type" value="changePWD">
                    <button type="button" class="btn btn-danger submit">提交</button>
                </div>
              </div>
          </form>
      </div>
      <!-- /.tab-pane -->

      <div class="tab-pane" id="rpasetting">
          <form class="form-horizontal">
              <div class="form-group">
                <label for="accept_mes_info" class="col-sm-2 control-label">是否接收RPA消息</label>
                <div class="col-sm-10">
                    <div class="switch">
                        <input type="checkbox" name="accept_mes_info" id="accept_mes_info" value="1" @if($info->accept_mes_info) checked @endif/>
                    </div>
                </div>
              </div>
  
              <div class="form-group">
                <label for="accept_mes_type" class="col-sm-2 control-label">接收消息类型</label>
                <div class="col-sm-10">
                    <select name="accept_mes_type" id="accept_mes_type" class="form-control">
                      <option value="1" @if(1 == $info->accept_mes_type) selected @endif>短信</option>
                      <option value="2" @if(2 == $info->accept_mes_type) selected @endif>邮件</option>
                      <option value="3" @if(3 == $info->accept_mes_type) selected @endif>短信和邮件</option>
                    </select>
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="hidden" name="type" value="rpasetting">
                    <button type="button" class="btn btn-danger submit">提交</button>
                </div>
              </div>
          </form>
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