<html>
    <head>
        <title>请修改初始密码</title>
        <link rel="stylesheet" href="{{URL::asset('/include/bootstrap/dist/css/bootstrap.css')}} ">
        <link rel="stylesheet" href="{{URL::asset('/include/sweetalert2/sweetalert2.css')}} ">
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">请修改初始密码</div>
                        <div class="panel-body">
                            <form class="form">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label for="oriPWD">旧密码</label>
                                    <input type="password" id="oriPWD" name="oriPWD" class="form-control" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="password">新密码</label>
                                    <input type="password" id="password" name="password" class="form-control" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label for="rePWD">重复密码</label>
                                    <input type="password" id="rePWD" name="rePWD" class="form-control" autocomplete="off">
                                </div>
                                <div class="pull-right">
                                    <a href="javascript:;" class="btn btn-primary submit">提交</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="{{URL::asset('/include/jquery/jquery.min.js')}}"></script>
    <script src="{{URL::asset('/include/bootstrap/dist/js/bootstrap.js')}}"></script>
    <script src="{{URL::asset('/include/sweetalert2/sweetalert2.js')}}"></script>
    <script>
        $('.submit').on('click', function(){
            var oriPWD = $('#oriPWD').val();
            var password = $('#password').val();
            var rePWD = $('#rePWD').val();
            if(!oriPWD || !password || !rePWD) {
                swal(
                    '提示',
                    '字段必填',
                    'warning'
                );
                return false;
            }
            var data = $('form').serialize();
            data += '&type=changePWD';
            $.ajax({
                url:'/admin/sys_profile',
                data:data,
                type:'post',
                dataType:'json',
                success:function(r){
                    if(r.code == 200) {
                        swal(
                            '提示',
                            '修改成功',
                            'success'
                        );
                        setTimeout(function(){
                            location.href = "/admin/logout";
                        }, 800);
                    } else {
                        swal(
                            '提示',
                            r.info,
                            'warning'
                        );
                        return false;
                    }

                },
                error:function(){
                    swal(
                        '提示',
                        '修改失败',
                        'warning'
                    );
                    return false;
                }
            })
        })
    </script>
</html>