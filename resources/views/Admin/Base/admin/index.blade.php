@extends('admin.layouts.wrapper-content')

@section('content')

<div class="row">
    <div class="col-md-3">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">

        <div class="card-body box-profile">
            <div class="profile-avatar-container">
                <img class="profile-user-img img-responsive img-circle plupload" src="{{ URL::asset($info->head_img) }}" alt="User profile picture" onerror="this.src='{{URL::asset('/common/images/default_head.png')}}'" initialized="true">
                <div class="profile-avatar-text img-circle">点击编辑</div>
                <button id="plupload-avatar" class="plupload" data-input-id="c-avatar" initialized="true" style="/* z-index: 1; */"><i class="fa fa-upload"></i> 上传</button>
            </div>
            <h3 class="profile-username text-center">{{ $info->name }}</h3>
            <p class="text-muted text-center">{{ $info->roleLists }}</p>
            

        </div>
        <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>

    <div class="col-md-9">
        <div class="card card-primary card-outline">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab" aria-expanded="true">基础设置</a></li>
                    <li class="nav-item"><a class="nav-link" href="#changePWD" data-toggle="tab" aria-expanded="false">修改密码</a></li>
                    <li class="nav-item"><a class="nav-link" href="#rpasetting" data-toggle="tab" aria-expanded="false">RPA设置</a></li>
                    <li class="nav-item"><a class="nav-link" href="#another" data-toggle="tab" aria-expanded="false">其他</a></li>
                </ul>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="settings">
                            <form class="form-horizontal">

                                <div class="form-group row">
                                <label class="col-sm-2 control-label">账户名</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="{{ $info->name }}" disabled>
                                </div>
                                </div>

                                <div class="form-group row">
                                <label for="inputName" class="col-sm-2 control-label">姓名</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" name="realName" value="{{ $info->realName }}" id="inputName" placeholder="真实姓名">
                                </div>
                                </div>

                                <div class="form-group row">
                                <label for="phone" class="col-sm-2 control-label">手机号</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" name="phone" value="{{ $info->phone }}" id="phone" placeholder="手机号码">
                                </div>
                                </div>

                                <div class="form-group row">
                                <label for="inputEmail" class="col-sm-2 control-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control" name="email" value="{{ $info->email }}" id="inputEmail" placeholder="Email">
                                </div>
                                </div>

                                <div class="form-group row">
                                <label for="desc" class="col-sm-2 control-label">自我描述</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control" id="desc" name="desc" placeholder="desc">{{ $info->desc }}</textarea>
                                </div>
                                </div>
                                
                                <div class="form-group row">
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
                    
                                <div class="form-group row">
                                    <label for="oriPWD" class="col-sm-2 control-label">原始密码</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" name="oriPWD" id="oriPWD" placeholder="原始密码" required>
                                    </div>
                                </div>
                    
                                <div class="form-group row">
                                    <label for="password" class="col-sm-2 control-label">新密码</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" name="password" id="password" placeholder="新密码" required>
                                    </div>
                                </div>
                    
                                <div class="form-group row">
                                    <label for="rePWD" class="col-sm-2 control-label">确认新密码</label>
                                    <div class="col-sm-10">
                                        <input type="rePWD" class="form-control" name="rePWD" id="rePWD" placeholder="确认新密码" required>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
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
                                <div class="form-group row">
                                    <label for="accept_mes_info" class="col-sm-2 control-label">是否接收RPA消息</label>
                                    <div class="col-sm-10">
                                        <div class="switch">
                                            <input type="checkbox" name="accept_mes_info" id="accept_mes_info" value="1" @if($info->accept_mes_info) checked @endif/>
                                        </div>
                                    </div>
                                </div>
                    
                                <div class="form-group row">
                                    <label for="accept_mes_type" class="col-sm-2 control-label">接收消息类型</label>
                                    <div class="col-sm-10">
                                        <select name="accept_mes_type" id="accept_mes_type" class="form-control">
                                        <option value="1" @if(1 == $info->accept_mes_type) selected @endif>短信</option>
                                        <option value="2" @if(2 == $info->accept_mes_type) selected @endif>邮件</option>
                                        <option value="3" @if(3 == $info->accept_mes_type) selected @endif>短信和邮件</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="hidden" name="type" value="rpasetting">
                                        <button type="button" class="btn btn-danger submit">提交</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="another">
                            <form class="form-horizontal">
                                <div class="form-group row">
                                    <label for="single_login" class="col-sm-2 control-label">是否开启单用户登录保护</label>
                                    <div class="col-sm-10">
                                        <div class="switch">
                                            <input type="checkbox" name="single_login" id="single_login" value="1" @if($info->single_login) checked @endif/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="login_protected" class="col-sm-2 control-label">是否开启异地登录保护（未完成）</label>
                                    <div class="col-sm-10">
                                        <div class="switch">
                                            <input type="checkbox" name="login_protected" id="login_protected" value="1" @if($info->login_protected) checked @endif/>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <input type="hidden" name="type" value="another">
                                        <button type="button" class="btn btn-danger submit">提交</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                </div>
                <!-- /.tab-content -->
            </div>
        </div>
    </div>
</div>

{{-- plupload --}}
<script src="{{URL::asset('/include/plupload/js/plupload.full.min.js')}}"></script>
<script src="{{URL::asset('/js/admin/base/admin/index.js')}}"></script>

@endsection