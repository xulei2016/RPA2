<html>
    <head>
        <title>失信查询</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="stylesheet" href="{{ URL::asset('/include/bootstrap3/css/bootstrap.css')}}">
        <link rel="stylesheet" href="{{ URL::asset('/include/sweetalert2/sweetalert2.css')}}">
        <link rel="stylesheet" href="{{ URL::asset('/include/bootstrap-switch/css/bootstrap3/bootstrap-switch.css')}}">
        <style>
            .mt10{
                margin-top: 10px;
            }
            .mt20{
                margin-top: 20px;
            }
            .mt30{
                margin-top: 30px;
            }
            label{
                text-align: right;
                line-height: 33px;
            }
            body{
                background: url("{{ URL::asset('/images/index/credit/bg.png')}}");
            }
        </style>
    </head>
    <body>
    <div class="container-fluid" style="margin-top: 100px;">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default">
{{--                <div class="panel-heading">失信查询</div>--}}
                <div class="panel-body">
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a href="#person" data-toggle="tab">个人查询</a></li>
                        <li><a href="#company" data-toggle="tab">企业查询</a></li>
                    </ul>

                    <div class="tab-content">
{{--                        个人查询--}}
                        <div class="tab-pane active" id="person">
                            <form class="form-horizontal mt30" id="person-form">
                                {{ csrf_field() }}
                                <div class="form-group row mt10">
                                    <label for="pName" class="col-md-4">姓名:</label>
                                    <div class="col-md-7">
                                        <input autocomplete="off" type="text" id="pName" name="pName" class="form-control" placeholder="请输入用户姓名">
                                    </div>
                                </div>
                                <div class="form-group row mt10">
                                    <label for="pCard" class="col-md-4">身份证号:</label>
                                    <div class="col-md-7">
                                        <input autocomplete="off"  type="text" id="pCard" name="pCard" class="form-control" placeholder="请输入用户身份证号码">
                                    </div>
                                </div>
                                <hr>
                                <div class="form-group row mt10">
                                    <div class="col-md-8 col-md-offset-3">
                                        <a href="javascript:;" class="btn btn-info pull-right query" type="person">查询</a>
                                    </div>
                                </div>
                            </form>
                        </div>
{{--                        企业查询--}}
                        <div class="tab-pane" id="company">
                            <form class="form-horizontal mt30" id="company-form">
                                {{csrf_field()}}
                                <div class="form-group row mt10">
                                    <label for="cName" class="col-md-4">企业名称:</label>
                                    <div class="col-md-7">
                                        <input autocomplete="off" type="text" name="cName" id="cName" class="form-control" placeholder="请输入企业全面">
                                    </div>
                                </div>
                                <div class="form-group row mt10">
                                    <label for="cCard" class="col-md-4">统一社会信用代码:</label>
                                    <div class="col-md-7">
                                        <input autocomplete="off" type="text" name="cCard" id="cCard" class="form-control" placeholder="请输入统一社会信用代码">
                                    </div>
                                </div>
                                <hr>

                                <div class="form-group row mt10">
                                    <label for="lName" class="col-md-4">法人姓名:</label>
                                    <div class="col-md-7">
                                        <input autocomplete="off" type="text" id="lName" name="lName"  class="form-control" placeholder="请输入法人姓名">
                                    </div>
                                </div>

                                <div class="form-group row mt10">
                                    <label for="lCard" class="col-md-4">法人身份证号:</label>
                                    <div class="col-md-7">
                                        <input autocomplete="off" type="text" id="lCard" name="lCard"  class="form-control" placeholder="请输入法人身份证号">
                                    </div>
                                </div>

                                <hr>
                                <div class="agent">
                                    <div class="form-group row mt10">
                                        <label for="" class="col-md-4">授权代理人是否和法人为同一人:</label>
                                        <div class="col-md-7">
                                            <input id="switch" type="checkbox" class="form-control" >
                                        </div>
                                    </div>

                                    <div class="form-group row mt10">
                                        <label class="col-md-4">授权代理人姓名:</label>
                                        <div class="col-md-7">
                                            <input autocomplete="off" type="text" id="aName"  name="aName"  class="form-control" placeholder="请输入代理人姓名">
                                        </div>

                                    </div>
                                    <div class="form-group row mt10">
                                        <label for="aCard" class="col-md-4">授权代理人身份证号:</label>
                                        <div class="col-md-7">
                                            <input autocomplete="off" type="text" id="aCard" name="aCard"  class="form-control" placeholder="请输入代理人身份证号">
                                        </div>

                                    </div>
                                    <hr>
                                </div>

                                <div class="form-group row mt10">
                                    <div class="col-md-8 col-md-offset-3">
                                        <a href="javascript:;" class="btn btn-info pull-right query" type="company">查询</a>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <input type="hidden" id="uu">
                    </div>
                </div>
            </div>
        </div>
    </div>

    </body>
    <script src="{{ URL::asset('/include/jquery/jquery.min.js')}} "></script>
    <script src="{{ URL::asset('/include/sweetalert2/sweetalert2.all.js')}} "></script>
    <script src=" {{ URL::asset('/include/bootstrap3/js/bootstrap.js')}} "></script>
    <script src=" {{ URL::asset('/include/bootstrap-switch/js/bootstrap-switch.js')}} "></script>
    <script src=" {{ URL::asset('/include/clipboard/clipboard.min.js')}} "></script>
    <script>
        $(function(){
            var code;
            var timer;
            var clickTime = 0;
            var clickTimer;
            var resetQuery = false;

            // swal通用模板
            var MySwal = Swal.mixin({
                type: 'warning', // 弹框类型
                confirmButtonText: '确定',// 确定按钮的 文字
                showCancelButton: true, // 是否显示取消按钮
                cancelButtonText: "取消", // 取消按钮的 文字
                focusCancel: true, // 是否聚焦 取消按钮
                reverseButtons: true  // 是否 反转 两个按钮的位置 默认是  左边 确定  右边 取消
            });

            // checkbox效果
            $("#switch").bootstrapSwitch({
                onText:"是",
                offText:"否",
                onSwitchChange:function(event,state){
                    var lName = $('#lName').val();
                    var lCard = $('#lCard').val();
                    if(state==true){
                        $('#aName').val(lName);
                        $('#aCard').val(lCard);
                    }else{
                        $('#aName').val('');
                        $('#aCard').val('');
                    }
                }
            });

            // 无失信时展示
            function showCorrect(code, token){
                $('#uu').val(code);
                var url = "/credit/showLocal?token="+token+"&uuid="+code;
                Swal('未查询到失信记录<br><br>'+code,
                    '<button  data-clipboard-target="#uu" data-clipboard-text="'+code+'" class="btn btn-info btn-xs" id="clipboard">复制</button>识别码,填写到crm相关流程中,' +
                    '<a href="'+ url+'" target="_blank" class="btn btn-xs btn-info">点此</a>查看失信详情',
                    'success');
                $('#clipboard').click();
            }

            // 查询失败时展示
            function showError(){
                MySwal.fire({
                    title:"查询失败",
                    text:"是否要重新查询"
                }).then((isConfirm) => {
                    try {
                        //判断 是否 点击的 确定按钮
                        if (isConfirm.value) {
                            clickTime = 0;
                            resetQuery = true;
                            $('.query').click();
                        }
                    } catch (e) {
                        Swal.fire("网络异常", '', 'warning');
                    }
                });
            }

            // 存在失信
            function showCredit(code, token){
                MySwal.fire({
                    title:"查询到失信记录",
                    text:"你可以选择查看失信记录或重新查询",
                    cancelButtonText: "失信记录",
                    confirmButtonText: '重新查询',
                    showCloseButton: true,
                    allowOutsideClick: true,
                }).then((isConfirm) => {
                    try {
                        //判断 是否 点击的 确定按钮
                        if (isConfirm.value) {
                            clickTime = 0;
                            resetQuery = true;
                            $('.query').click();
                        } else if(isConfirm.dismiss ===  'cancel') {
                            var url = "/credit/showLocal?token="+token+"&uuid="+code;
                            window.open(url);
                        }
                    } catch (e) {
                        Swal.fire("网络异常", '', 'warning');
                    }
                });
            }

            // 循环查询
            function loop(){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url:'/credit/loopQuery',
                    type:'post',
                    data:{code:code},
                    success:function(res){
                        if(res.code === 200) {
                            var data = res.data;
                            showCorrect(data.code, data.token);
                        }
                        if(res.code === 202) {
                            timer = setTimeout(function(){
                                loop();
                            }, 10000)
                        }
                        if(res.code === 201) {
                            var data = res.data;
                            showCredit(data.code, data.token);
                        }
                        if(res.code === 500) {
                            showError();
                        }
                        if(res.code !== 202 ) {
                            clearTimeout(timer);
                            return false;
                        }
                    },
                })
            }
            // 保存
            $('.query').on('click', function(){
                if(clickTime) {
                    Swal('请勿频繁查询, 请耐心等待'+ clickTime+'秒', '', 'info');
                    return false;
                }
                var _this = $(this);
                var flag = true;
                var type = _this.attr('type');
                var form = '#'+type+'-form';
                var inputs = $(form + ' input:text');
                $.each(inputs, function(index, item){
                    if(!$(item).val()) {
                        if(!resetQuery) Swal('字段必填', '', 'error');
                        flag = false;
                        return false;
                    }
                });
                if(flag) { // 所有字段均填写
                    var data = $(form).serialize();
                    data += '&type='+type;
                    if(resetQuery) {
                        data += '&resetQuery=1';
                        resetQuery = false;
                    }
                    $.ajax({
                        url:"/credit",
                        type:'post',
                        data:data,
                        success:function(res){
                            clickTime = 10;
                            clickTimer = setInterval(function(){
                                if(clickTime > 0) {
                                    clickTime--;
                                }
                                if(clickTime === 0) {
                                    clearInterval(clickTimer);
                                }
                            }, 1000);
                            if(res.code == 500) {
                                showError();
                                return false;
                            }

                            if(res.code == 201) {
                                var data = res.data;
                                if(data.status == 2) { // 未失信
                                    showCorrect(data.code, data.token);
                                    return false;
                                } else if(data.status == 3) {
                                    showCredit(data.code, data.token);
                                    return false;
                                } else if(data.status == 4) {
                                    showError();
                                    return false;
                                }
                            }
                            code = res.data.code;
                            loop();
                            Swal.fire({
                                title: '查询中,请稍后',
                                text: '平均查询时间1-2分钟, 请稍后过来查看结果',
                                allowOutsideClick: () => {clearTimeout(timer);return true;},
                                onBeforeOpen:() => {
                                    Swal.showLoading()
                                }
                            });
                        }
                    })
                }
            });

            // 点击事件
            $(document).on('click', '#clipboard' ,function(){
                if(ClipboardJS.isSupported()) {
                    var cjs = new ClipboardJS('#clipboard');
                    $('#uu').val('');
                }

            })
        })
    </script>
</html>
