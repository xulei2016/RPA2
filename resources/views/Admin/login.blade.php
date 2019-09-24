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

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{URL::asset('/css/admin/common/style.css')}}" />
    <link rel="stylesheet" type="text/css" href="{{URL::asset('/include/iCheck/futurico/futurico.css')}}" />

    <!-- Scripts -->
    <script src="{{URL::asset('/include/jquery/jquery-3.3.1.min.js')}}"></script>
    <script src="{{URL::asset('/include/iCheck/icheck.js')}}"></script>

</head>

<body>
    <div id="container">
      <dl class="admin_login login-area">
          <dt>
              <strong>RPA 自动化程序管理系统</strong>
              <em>Management System</em>
          </dt>

          <form method="POST" action="{{ route('admin.login') }}">
              {{ csrf_field() }}

            <dd class="user_icon">
                <input type="text" id="name" name="name" placeholder="账号" value="{{ old('name') }}" class="login_txtbx" autocomplete="on"/>
                @if ($errors->has('name'))
                    <span class="invalid-feedback ">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif
            </dd>

            <dd class="pwd_icon">
                <input type="password" id="password" name="password" placeholder="密码" class="login_txtbx" autocomplete="on"/>
                @if ($errors->has('password'))
                    <span class="invalid-feedback ">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </dd>

            <dd style="line-height: 42px;">
                <label><input type="checkbox" value="1" class="remember" name="remember" {{ old('remember') ? 'checked' : '' }}> 记住登陆</label>
            </dd>

            <dd>
                <button type="submit" class="btn btn-primary submit_btn">
                  立即登陆
                </button>
            </dd>

            {{-- <dd>
                <p>© 2015-2018 HAQH 软件工程部</p>
                <p>皖ICP备17018938号</p>
            </dd> --}}
          </form>
      </dl>
    </div>

    <script src="{{URL::asset('/include/bootstrap/dist/js/bootstrap.min.js')}}" type="text/javascript"></script>
    <script>
      $("input.remember").iCheck({
          checkboxClass: "icheckbox_futurico",
          cursor: true
      });
    </script>
</body>
</html>
