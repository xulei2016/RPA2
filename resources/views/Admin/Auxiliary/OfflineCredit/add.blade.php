@component('Admin.widgets.viewForm')
    @slot('title')
        线下失信查询
    @endslot
    @slot('formContent')
        <link rel="stylesheet" href="{{URL::asset('/include/adminlte/css/adminlte.min.css')}}">
        <link rel="stylesheet" href="{{ URL::asset('/include/sweetalert2/sweetalert2.min.css')}}">
        <link rel="stylesheet" href="{{ URL::asset('/include/bootstrap-switch/css/bootstrap3/bootstrap-switch.css')}}">
        <div class="container-fluid">
            <div class="panel panel-default">
                    <div class="panel-body">

                        <br>

                        @if($type == 'person')
                            {{--                        个人查询--}}
                            <div id="person">
                                <form class="form-horizontal mt30" id="person-form">
                                    <div class="form-group row mt10">
                                        <label for="pName" class="col-md-4">姓名:</label>
                                        <div class="col-md-7">
                                            <input autocomplete="off" type="text"  id="pName" name="pName" class="form-control" placeholder="请输入用户姓名">
                                        </div>
                                    </div>
                                    <div class="form-group row mt10">
                                        <label for="pCard" class="col-md-4">身份证号:</label>
                                        <div class="col-md-7">
                                            <input autocomplete="off"   type="text" id="pCard" name="pCard" class="form-control" placeholder="请输入用户身份证号码">
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group row mt10">
                                        <div>
                                            <a href="javascript:;" class="btn btn-info pull-right query" type="person">查询</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @else
                            {{--                        企业查询--}}
                            <div id="company">
                                <form class="form-horizontal mt30" id="company-form">
                                    <div class="form-group row mt10">
                                        <label for="cName" class="col-md-4">企业名称:</label>
                                        <div class="col-md-7">
                                            <input autocomplete="off" type="text" name="cName" id="cName" class="form-control" placeholder="请输入企业名称">
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
                                        <div style="float: right">
                                            <a href="javascript:;"  class="btn btn-info pull-right query" type="person">查询</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        @endif



                        <input type="hidden" id="uu">

                    </div>
                </div>
        </div>

    @endslot

    @slot('formScript')
        <script src="{{ URL::asset('/include/sweetalert2/sweetalert2.min.js')}} "></script>
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

                $("#switch").bootstrapSwitch({
                    onText:"是",
                    offText:"否",
                    onSwitchChange:function(event,state){
                        var fName = $('#fName').val();
                        var fCard = $('#fCard').val();
                        if(state == true){
                            $('#dName').val(fName);
                            $('#dCard').val(fCard);
                        }else{
                            $('#dName').val('');
                            $('#dCard').val('');
                        }
                    }
                });

                // 查询失败时展示
                function showError(){
                    MySwal.fire({
                        title:"查询失败",
                        text:"是否要重新查询(频繁查询失败请联系金融科技部)"
                    }).then((isConfirm) => {
                        try {
                            //判断 是否 点击的 确定按钮
                            if (isConfirm.value) {
                                clickTime = 0;
                                resetQuery = true;
                                $(' .query').click();
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
                        text:"是否要重新查询",
                    }).then((isConfirm) => {
                        try {
                            //判断 是否 点击的 确定按钮
                            if (isConfirm.value) {
                                clickTime = 0;
                                resetQuery = true;
                                $(current + ' .query').click();
                            }
                        } catch (e) {
                            Swal.fire("网络异常", '', 'warning');
                        }
                    });
                }

                function loop(){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url:'/admin/rpa_offline_credit/loopQuery',
                        type:'post',
                        data:{code:code},
                        success:function(res){
                            if(res.code === 200) {
                                Swal('未查询到失信记录', '','success');
                                return false;
                            }
                            if(res.code === 202) {
                                setTimeout(function(){
                                    loop();
                                }, 10000)
                            }
                            if(res.code === 201) {
                                showCredit();
                                return false;
                            }
                            if(res.code === 500) {
                                showError();
                                return false;
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

                    var form = '#form';
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
                        if(resetQuery) {
                            data += '&resetQuery=1';
                            resetQuery = false;
                        }
                        $.ajax({
                            url:"/admin/rpa_offline_credit",
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
                                    Swal('未查询到失信记录', '', 'success');
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
    @endslot
@endcomponent



