<!DOCTYPE HTML>
<html>

<head>
    <title>RPA中台流程设计器</title>
    <meta name="keyword" content="流程设计器">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{URL::asset('/include/flowdesign/css/bootstrap.css')}}">

    <!--[if lte IE 6]>
    <link rel="stylesheet" type="text/css" href="/include/flowdesign/css/bootstrap-ie6.css?2025">
    <![endif]-->
    <!--[if lte IE 7]>
    <link rel="stylesheet" type="text/css" href="/include/flowdesign/css/ie.css?2025">
    <![endif]-->

    <link rel="stylesheet" href="{{URL::asset('/include/toastr/toastr.min.css')}}">
    <!-- design flow -->
    <link rel="stylesheet" href="{{URL::asset('/css/admin/flow/design.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/css/admin/flow/flowDesign.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/include/jquery-contentMenu/jquery.contextMenu.css')}}">
    <link rel="stylesheet" href="{{URL::asset('/include/jquery-multisect2side/css/jquery.multiselect2side.css')}}" media="screen">
</head>

<body>

    <!-- fixed navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">

                <div class="pull-right">
                    <button class="btn btn-info" type="button" id="_save">保存设计</button>
                    @if($flow->is_publish<1) 
                        <button class="btn btn-danger" type="button" id="_publish">发布流程</button>
                    @endif
                </div>

                <div class="nav-collapse collapse">
                    <ul class="nav">
                        <li><a href="javascript:history.go(-1)">工作流</a></li>
                        <li><a href="javascript:;">正在设计【{{$flow->title}}流程】</a></li>
                    </ul>
                </div>
                
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="alertModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3>消息提示</h3>
        </div>
        <div class="modal-body">
            <p>提示内容</p>
        </div>
        <div class="modal-footer">
            <button class="btn btn-primary" data-dismiss="modal" aria-hidden="true">我知道了</button>
        </div>
    </div>

    <!-- attributeModal -->
    <div id="attributeModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"
        style="width:800px;margin-left:-350px">
        <div class="modal-body" style="max-height:600px;">
            <!-- body -->
        </div>
    </div>

    <!--contextmenu div-->
    <div id="nodeMenu" style="display:none;">
        <ul>
            <li id="attribute">
                <i class="icon-cog"></i>&nbsp;
                <span class="_label">属性</span>
            </li>
            <li id="delete">
                <i class="icon-trash"></i>&nbsp;
                <span class="_label">删除</span>
            </li>
        </ul>
    </div>
    <div id="canvasMenu" style="display:none;">
        <ul>
            <li id="add">
                <i class="icon-plus"></i>&nbsp;
                <span class="_label">添加步骤</span>
            </li>
            <li id="save">
                <i class="icon-ok"></i>&nbsp;
                <span class="_label">保存设计</span>
            </li>
            <li id="refresh">
                <i class="icon-refresh"></i>&nbsp;
                <span class="_label">刷新 F5</span>
            </li>
            <!-- <li id="paste"><i class="icon-share"></i>&nbsp;<span class="_label">粘贴</span></li> -->
            <li id="help">
                <i class="icon-search"></i>&nbsp;
                <span class="_label">帮助</span>
            </li>
        </ul>
    </div>
    <!--end div-->

    <div class="container mini-layout" id="flowdesign_canvas"></div>

    <!-- jQuery 为最优版本，请勿替换，也不要使用本地低版本，只能使用cdn -->
    <script src="https://cdn.bootcss.com/jquery/1.7.2/jquery.min.js"></script>
    <script src="{{URL::asset('/include/toastr/toastr.min.js')}}"></script>
    <script src="{{URL::asset('/include/jquery-ui/jquery-ui-1.9.2-min.js')}}"></script>
    <script src="{{URL::asset('/include/jquery-contentMenu/jquery.contextMenu.r2.js')}}"></script>
    <script src="{{URL::asset('/include/flowdesign/js/bootstrap.min.js')}}"></script>
    <script src="{{URL::asset('/include/jquery-jsPlumb/jquery.jsPlumb-1.3.16-all-min.js')}}"></script>
    <script src="{{URL::asset('/include/jquery-multisect2side/js/jquery.multiselect2side.js')}}"></script>
    <script src="{{URL::asset('/include/flowdesign/leipi.flowdesign.v3.js')}}"></script>

    <script type="text/javascript">
        function callbackSuperDialog(selectValue){
            var aResult = selectValue.split('@leipi@');
            $('#'+window._viewField).val(aResult[0]);
            $('#'+window._hidField).val(aResult[1]);
        }
        /**
         * 弹出窗选择用户部门角色
         * showModalDialog 方式选择用户
         * URL 选择器地址
         * viewField 用来显示数据的ID
         * hidField 隐藏域数据ID
         * isOnly 是否只能选一条数据
         * dialogWidth * dialogHeight 弹出的窗口大小
         */
        function superDialog(URL,viewField,hidField,isOnly,dialogWidth,dialogHeight)
        {
            dialogWidth || (dialogWidth = 800)
            ,dialogHeight || (dialogHeight = 600)
            ,loc_x = 500
            ,loc_y = 40
            ,window._viewField = viewField
            ,window._hidField= hidField;
            if(window.ActiveXObject){ //IE  
                var selectValue = window.showModalDialog(URL,self,"edge:raised;scroll:1;status:0;help:0;resizable:1;dialogWidth:"+dialogWidth+"px;dialogHeight:"+dialogHeight+"px;dialogTop:"+loc_y+"px;dialogLeft:"+loc_x+"px");
                if(selectValue){
                    callbackSuperDialog(selectValue);
                }
            }else{  //非IE 
                var selectValue = window.open(URL, 'newwindow','height='+dialogHeight+',width='+dialogWidth+',top='+loc_y+',left='+loc_x+',toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no');  
            }
        }
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            //初始数据
            var id = {{ $flow -> id }};
            var nodeData = {!!$flow-> jsplumb ?: '{}'!!};
            var flow_obj = $("#flowdesign_canvas");

            /**
             * init 
             * 
             * @author hsu lay
             * @since 20191126
             */
            function init() {
                bindEvent();
            }

            //事件绑定
            function bindEvent() {
                //保存
                $("#_save").bind('click', function () {
                    var nodeInfo = _canvas.getNodeInfo();//连接信息
                    var url = `/admin/flowLink/${id}`;
                    $.post(url, { "node_info": nodeInfo }, function (data) {
                        toastr.success("保存成功!");
                    }, 'json');
                });

                //发布
                $("#_publish").bind('click', function () {
                    $.post('/admin/flow/publish', { "flow_id": id }, function (data) {
                        if (200 == data.code) {
                            toastr.success("发布成功!");
                            location.reload();
                        } else {
                            toastr.error(data.info);
                        }
                    }, 'json');
                });
            }

            //初始化流程设计器
            var _canvas = flow_obj.Flowdesign({
                nodeData: nodeData,
                canvasMenus: {
                    add: t => {
                        var mLeft = $("#jqContextMenu").css("left"), mTop = $("#jqContextMenu").css("top");
                        var url = "{{route('node.store')}}";
                        $.post(url, { "flow_id": id, "left": mLeft, "top": mTop }, function (data) {
                            if (200 == data.code) {
                                if (!_canvas.addNode(data.data)) {
                                    toastr.error("添加失败");
                                }
                            } else {
                                toastr.error(data.message);
                            }
                        }, 'json');
                    },
                    save: t => {
                        var nodeInfo = _canvas.getNodeInfo();//连接信息
                        var url = `/admin/flowLink/${id}`;
                        $.post(url, { "node_info": nodeInfo }, function (data) {
                            toastr.success("保存成功!");
                        }, 'json');
                    },
                    //刷新
                    refresh: t => { _canvas.refresh(); },
                    paste: t => {
                        var pasteId = _canvas.paste();//右键当前的ID
                        if (pasteId <= 0) {
                            alert("你未复制任何步骤");
                            return;
                        }
                        alert("粘贴:" + pasteId);
                    },
                    help: t => {
                        alert("查看帮助");
                    }
                },
                nodeMenus: {
                    "setting": function (t) {
                        var activeId = _canvas.getActiveId();//右键当前的ID
                        alert("设置:" + activeId);
                    },
                    "begin": function (t) {
                        var nodeInfo = _canvas.getNodeInfo();//连接信息
                        var url = `/admin/flowLink/${id}`;
                        $.post(url, { "node_info": nodeInfo }, function (data) {
                            toastr.success("保存成功!");
                        }, 'json');
                    },
                    // "stop":function(t)
                    // {
                    //     var activeId = _canvas.getActiveId();//右键当前的ID
                    //     // alert("设为最后一步:"+activeId);
                    //     $.post('/node/stop',{"flow_id":id,"node_id":activeId},function(data){
                    //         if(data.status_code==0){
                    //           layer.msg('设置成功');
                    //         }else{
                    //           layer.msg(data.message);
                    //         }
                    //     },'json');
                    // },
                    "addson": function (t) {
                        var activeId = _canvas.getActiveId();//右键当前的ID
                        alert("添加子步骤:" + activeId);
                    },
                    "copy": function (t) {
                        _canvas.copy();//右键当前的ID
                        alert("复制成功");
                    },
                    "delete": function (t) {
                        if (confirm("你确定删除步骤吗？")) {
                            var activeId = _canvas.getActiveId();//右键当前的ID
                            $.ajax({
                                type: 'DELETE',
                                dataType: 'json',
                                url: '/admin/node/' + activeId,
                                data: { flow_id: id },
                                success: function (res) {
                                    if (200 == res.code) {
                                        _canvas.delNode(activeId);
                                        toastr.success("删除成功,页面即将刷新!");
                                        location.reload();
                                    }
                                }
                            });
                        }
                    },
                    attribute: () => {
                        var activeId = _canvas.getActiveId();//右键当前的ID
                        ajaxModal('/admin/node/attribute?id=' + activeId, () => {
                            //alert('加载完成执行')
                        });
                    }
                },
                fnRepeat: () => { toastr.warning("步骤连接重复了，请重新连接"); },
                fnClick: () => {
                    var activeId = _canvas.getActiveId();//右键当前的ID
                    ajaxModal('/admin/node/attribute?id=' + activeId, () => {
                        //alert('加载完成执行')
                    });
                },
                fnDbClick: () => {
                    toastr.warning("yes, i do, but nothing happened!! :)");
                }
            });

            var attributeModal = $("#attributeModal");
            //属性设置
            attributeModal.on("hidden", function () {
                $(this).removeData("modal");//移除数据，防止缓存
            });
            ajaxModal = function (url, fn) {
                url += url.indexOf('?') ? '&' : '?';
                url += '_t=' + new Date().getTime();
                attributeModal.modal({
                    remote: url
                })
                //加载完成执行
                if (fn) {
                    attributeModal.on('shown', fn);
                }
            }

            init();
        });

    </script>

</body>

</html>