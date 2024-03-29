<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="@{{ csrf_token }}">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, maximum-scale=1.0,minimum-scale=1.0,user-scalable=0"/>
    <link rel="stylesheet" href="{{asset('css/index/mediator/skin.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('/include/bootstrap3/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ URL::asset('/include/sweetalert2/sweetalert2.min.css')}}">
    <title>客户开户进度查询</title>
    <style>
        .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
            text-align: center;
        }
        .row{
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body style="max-width: 640px;margin: auto;background-color: #607D8B;">
<div id="container" style="height: 100%; background-color: white;min-height: 800px;">
    <div class="row header" style="text-align: center">
        <div>
            <h1 style="margin: 15% 0">客户开户进度查询</h1>
        </div>
        <div>
            <label>
                客户姓名<input type="name" id="name" class="form-control">
            </label>
        </div>
        <div>
            <label>
                客户手机号<input type="phone" id="phone" class="form-control">
            </label>
        </div>
        {{--        <div class="col-lg-3">--}}
        {{--            <label>--}}
        {{--                客户经理姓名<input type="name" id="mname" class="form-control" placeholder="必填" required>--}}
        {{--            </label>--}}
        {{--        </div>--}}
        {{--        <div class="col-lg-3">--}}
        {{--            <label>--}}
        {{--                客户经理工号<input type="text" id="mnum" class="form-control" placeholder="必填" required>--}}
        {{--            </label>--}}
        {{--        </div>--}}
        <div>
            <button class="button btn btn-primary" onclick="getCNode()">查询</button>
        </div>
    </div>
    <hr>

    <div id="content" style="padding:6px;">
        <table class="table table-hover">
            <tr class="title">
                <th>操作</th>
                <th>说明</th>
                <th>时间</th>
            </tr>
            <tr>
                <td colspan="3">请录入查询信息！</td>
            </tr>
        </table>
    </div>
    <hr>
    <div class="notice" style="text-align: center">
        <span class="text-danger">*</span>请注意，查询时间为工作日的08：30 ~ 17:30。
    </div>
</div>
</body>

<!-- jQuery -->
<script src="{{URL::asset('/include/jquery/jquery.min.js')}}"></script>
<script src="{{URL::asset('/include/bootstrap3/js/bootstrap.min.js')}}"></script>
<script src="{{ URL::asset('/include/sweetalert2/sweetalert2.min.js')}} "></script>
<script>
    function getCNode() {

        let CName = $('#name').val();
        let CPhone = $('#phone').val();
        // let mname = $('#mname').val();
        // let mnum = $('#mnum').val();

        if (!CName && !CPhone) {
            alert('请填写需要查询的客户姓名或手机号');
            return;
        }

        // if(!mname || !mnum){
        //     alert('请填写您的姓名和经理工号！');
        //     return;
        // }

        let data = {
            'cname': CName,
            'cphone': CPhone
            // 'mname': mname,
            // 'mnum': mnum
        };
        Swal.fire({
            title: '查询中,请稍后',
            text: '',
            allowOutsideClick: false,
            onBeforeOpen:() => {
                Swal.showLoading()
            }
        });
        $.get('/cnode/getResultView', data, function (json) {
            if (200 == json.code) {
                $("#content").html(json.data);
                Swal('查询成功', '', 'success');
                return false;
            }
            Swal(json.info, '', 'error');
        });
    }
</script>

</html>
