<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('admin.name', 'RPA') }} - Admin</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link rel="icon" href="./themes/image/favicon.ico" sizes="16x16 32x32">

    <link rel="stylesheet" href="{{URL::asset('/include/bootstrap3/css/bootstrap.css')}}">


    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{URL::asset('/css/admin/common/style.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('/include/iCheck/futurico/futurico.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{URL::asset('/include/jigsaw/style.css')}}"/>

</head>

<body>
<div id="container">
    <dl class="admin_login login-area">
        <dt>
            <img src="/common/images/logo.png">
            <strong>综合期货业务自动管理平台</strong>
            <em>RPA Management System</em>
        </dt>

        <form method="POST" action="{{ route('admin.login') }}">
            {{ csrf_field() }}

            <dd class="user_icon">
                <span class="glyphicon glyphicon-user"></span>
                <input type="text" id="name" name="name" placeholder="账号" value="{{ old('name') }}" class="login_txtbx"
                       autocomplete="on"/>
                @if ($errors->has('name'))
                    <span class="invalid-feedback ">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </dd>

            <dd class="pwd_icon">
                <span class="glyphicon glyphicon-lock"></span>
                <input type="password" id="password" name="password" placeholder="密码" class="login_txtbx"
                       autocomplete="new-password"/>
                @if ($errors->has('password'))
                    <span class="invalid-feedback ">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </dd>
            <dd>
                @if($codeConfig == 1 || $codeConfig == 3)
                    <div class="input-group" style="height: 40px;" id="code-div">
                        <span class="glyphicon glyphicon-earphone"></span>
                        <input autocomplete="off" style="height: 40px;line-height: 28px;" type="text" id="captcha"
                               name="captcha"
                               class="form-control" placeholder="请输入验证码" aria-describedby="basic-addon2">
                        @if($codeConfig == 1)
                            <span class="input-group-addon" id="code-img" style="padding: 0 0;">
                                <img src="{{captcha_src()}}" style="cursor: pointer;"
                                     onclick="this.src='{{captcha_src()}}'+Math.random()">
                            </span>
                            <span class="input-group-addon" id="get-code" style="cursor: pointer;display: none">
                                获取验证码
                            </span>
                        @elseif($codeConfig == 3)
                            <span class="input-group-addon" id="code-img" style="padding: 0 0;display: none">
                                <img src="{{captcha_src()}}" style="cursor: pointer;"
                                     onclick="this.src='{{captcha_src()}}'+Math.random()">
                            </span>
                            <span class="input-group-addon" id="get-code" style="cursor: pointer;">
                                获取验证码
                            </span>
                        @endif

                    </div>
                @endif

                @if($errors->has('captcha'))
                    <span class="invalid-feedback">
                      <strong class="text-danger text-left">{{$errors->first('captcha')}}</strong>
                  </span>
                @endif
                @if($errors->has('account'))
                    <span class="invalid-feedback">
                      <strong class="text-danger text-left">账号被锁定</strong>
                  </span>
                @endif
            </dd>
            <dd style="line-height: 42px;">
                <label><input type="checkbox" value="1" class="remember"
                              name="remember" {{ old('remember') ? 'checked' : '' }}> 记住登陆</label>
            </dd>

            <dd>
                <a href="javascript:void(0)" class="btn btn-primary submit_btn">
                    立即登陆
                </a>
            </dd>

            {{--            <dd>--}}
            {{--                <p>© 2015-2018 HAQH 软件工程部</p>--}}
            {{--                <p>皖ICP备17018938号</p>--}}
            {{--            </dd>--}}
        </form>

    </dl>
    <div class="footer">
        <div class="copyright"> Copyright © 2017 HA-金融科技部</div>
    </div>
</div>
<div class="bg">
    <div class="logo-box">
        <div class="verBox">
            <div id="imgVer" style="display: inline-block"></div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{URL::asset('/include/jquery/jquery-3.3.1.min.js')}}"></script>
<script src="{{URL::asset('/include/iCheck/icheck.js')}}"></script>
<script src="{{URL::asset('/include/bootstrap3/js/bootstrap.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('/include/jigsaw/img_ver.min.js')}}" type="text/javascript"></script>
<script>
    function handle(func) {
        imgVer({
            el: '$("#imgVer")',
            width: '260',
            height: '116',
            success: function () {
                func();
            },
            error: function () {
            }
        });
    }

    function throwError(e, error){

    }

    function clear() {
        $(".bg").fadeOut(300);
    }

    function show() {
        $(".bg").fadeIn(100);
    }

    $("input").keydown(function(event){
        event = event ? event: window.event;
        if(event.keyCode == 13){
            $('form').submit();
        }
    });

    $("input.remember").iCheck({
        checkboxClass: "icheckbox_futurico",
        cursor: true
    });
    $("#get-code").on('click', function () {
        handle(function () {
            $('#code-img').show();
            $('#get-code').hide();
            clear();
        });
        show();
        return false;
    });
    $('.bg').on('click', function () {
        clear();
    });
    $('.logo-box').on('click', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });
    $('.submit_btn').one('click', function () {
        var code = "{{$codeConfig}}";
        if (code === "2") {
            handle(function () {
                clear();
                $('form').submit();
            });
            show();
        } else {
            $('form').submit();
        }
    });

    function checkLogin(){
        if(!$('#name').val()){
            return throwError($('#name'), '请输入姓名！')
        }
        if($('#password').val()){
            return throwError($('#password'), '请输入密码！')
        }
        if($('#captcha')){
            if(!$('#captcha').val()){
                return throwError($('#captcha'), '请输入验证码！')
            }
        }
    }
</script>
</body>
</html>
