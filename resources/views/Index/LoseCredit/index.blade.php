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
                                    <label for="fName" class="col-md-4">法人姓名:</label>
                                    <div class="col-md-7">
                                        <input autocomplete="off" type="text" id="fName" name="fName"  class="form-control" placeholder="请输入法人姓名">
                                    </div>
                                </div>

                                <div class="form-group row mt10">
                                    <label for="fCard" class="col-md-4">法人身份证号:</label>
                                    <div class="col-md-7">
                                        <input autocomplete="off" type="text" id="fCard" name="fCard"  class="form-control" placeholder="请输入法人身份证号">
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
                                            <input autocomplete="off" type="text" id="dName"  name="dName"  class="form-control" placeholder="请输入代理人姓名">
                                        </div>

                                    </div>
                                    <div class="form-group row mt10">
                                        <label for="dCard" class="col-md-4">授权代理人身份证号:</label>
                                        <div class="col-md-7">
                                            <input autocomplete="off" type="text" id="dCard" name="dCard"  class="form-control" placeholder="请输入代理人身份证号">
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
            $("#switch").bootstrapSwitch({
                onText:"是",
                offText:"否",
                onSwitchChange:function(event,state){
                    var fName = $('#fName').val();
                    var fCard = $('#fCard').val();
                    if(state==true){
                        $('#dName').val(fName);
                        $('#dCard').val(fCard);
                    }else{
                        $('#dName').val('');
                        $('#dCard').val('');
                    }
                }
            });

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
                            $('#uu').val(code);
                            Swal('未查询到失信记录<br><br>'+code, '<button data-clipboard-target="#uu" data-clipboard-text="'+code+'" class="btn btn-info btn-xs" id="clipboard">复制</button>识别码,可在CRM系统查询到详细记录' , 'success');
                        }
                        if(res.code === 202) {
                            setTimeout(function(){
                                loop();
                            }, 10000)
                        }
                        if(res.code === 201) {
                            Swal('查询到失信记录', '' , 'info');
                        }
                        if(res.code === 500) {
                            Swal('查询失败, 请重试', '', 'error');
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
                        Swal('字段必填', '', 'error');
                        flag = false;
                        return false;
                    }
                });
                if(flag) { // 所有字段均填写
                    var data = $(form).serialize();
                    data += '&type='+type;
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
                                Swal(res.info?res.info:'查询失败,请重试', '', 'error');
                                return false;
                            }

                            if(res.code === 201) {
                                code = res.info;
                                $('#uu').val(code);
                                Swal('未查询到失信记录<br><br>'+code, '<button  data-clipboard-target="#uu" data-clipboard-text="'+code+'" class="btn btn-info btn-xs" id="clipboard">复制</button>识别码,可在CRM系统查询到详细记录' , 'success');
                                return false;
                            }
                            code = res.data;
                            loop();
                            Swal.fire({
                                title: '查询中,请稍后',
                                text: '请勿刷新网页, 平均查询时间1-2分钟',
                                allowOutsideClick: false,
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
