@extends("admin.layouts.wrapper-content") @section("content")

<link rel="stylesheet" href="{{asset('css/admin/common/callCenter.css')}}">

<script type="text/javascript" src='https://home.wolive.cc/assets/libs/jquery/jquery.cookie.js'></script>
<script src="{{asset('js/admin/base/callCenter/chatroom/common.js')}}"></script>
<script src="{{asset('js/admin/base/callCenter/chatroom/chats.js')}}"></script>
<script src="{{asset('js/admin/base/callCenter/chatroom/chat1.js')}}"></script>
<script src="{{asset('js/admin/base/callCenter/chatroom/chat.js')}}"></script>
<script>
    //表情
    var faceon = function () {
        $(".tool-box").toggle();
    };
</script>
<style type="text/css">

    #group-menus-main li{
        display: block;
        width: 100%;
        height: 45px;
        line-height: 20px;
    }

    #notices-icon{
        display: inline-block;
        width: 15px;
        height: 15px;
        background: url("https://home.wolive.cc/assets/images/admin/notice.png") no-repeat;
        background-size:100%;
        position: absolute;
        left:30px;
        top: 8px;
        z-index: 9990;
    }
   #group-menus-main i{
    font-size: 20px;
   }
</style>
<style>
        .layui-form-label{
          float: left;
          display: block;
          padding: 9px 15px;
          width: 100px;
          font-weight: 400;
          line-height: 20px;
          text-align: right;
      }
  
    .videos {
        font-size: 0;
        height: 100%;
        pointer-events: none;
        position: absolute;
        transition: all 1s;
        width: 100%;
    }

    #localVideo {
        height: 100%;
        max-height: 100%;
        max-width: 100%;
        /*object-fit: cover;*/
        transition: opacity 1s;
        width: 100%;
    }

    #remoteVideo {
        display: block;
        height: 100%;
        max-height: 100%;
        max-width: 100%;
        /*object-fit: cover;*/
        position: absolute;
        transition: opacity 1s;
        width: 100%;
    }
</style>
<div class="row" id="callCenter">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-body" style="padding:0;">
                <div class="chatroom">
                    <section class="chat-list">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="nav-item" style="width:50%;"><a class="nav-link active" href="#chatList" data-toggle="tab">当前对话</a></li>
                                <li class="nav-item" style="width:50%;"><a class="nav-link" href="#waitList" data-toggle="tab">排队列表</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="chatList">
                                    @if($chatList)
                                        @foreach($chatList as $list)
                                            <div id="v586145376b69584f2f74657374" class="visiter @if(false) active @endif" onmouseover="showcut(this)" onmouseout="hidecut(this)">
                                                <div class="visit_content" onclick="choose(56285)">
                                                    <img class="am-radius v-avatar
                                                    @if(!$list->status) icon_gray @endif "
                                                    id="img586145376b69584f2f74657374" src="{{asset('/callCenter/img')}}{{$list->avatar}}" width="50px">
                                                    <div><p class="c_name">{{$list->name}}</p><div id="msg586145376b69584f2f74657374" class="newmsg">{{$list->content}}</div></div>
                                                </div>
                                                <div class="del"><a href="javascript:void(0);"><i class="fa fa-minus-circle" title="删除" style="font_weight:blod" onclick="cut('XaE7kiXO')"></i></a></div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p style="margin:10px 0;color:#bbb;">暂无数据</p>
                                    @endif
                                </div>
                                <div class="tab-pane" id="waitList">
    
                                </div>
                            </div>
                        </div>
                    </section>
                    <section class="chat-box">

                        <div id="wrap" style="width: 100%;height:62%;overflow-y: auto;">
                            <ul class="conversation">
            
                                <li class="chatmsg">
                                    <div class="showtime">10:03</div>
                                    <div class="" style="position: absolute;left:3px;">
                                        <img class="my-circle  se_pic" src="https://www.wolive.cc/assets/upload/10.jpg" width="50px"
                                            height="50px">
                                    </div>
                                    <div class="outer-left">
                                        <div class="customer">
                                            <pre><a href="http://www.wolive.cc/demo/index/product1" target="_blank" class="product_msg"><img src="https://www.wolive.cc/assets/images/pro.jpg"><div class="product_info"><p class="title"> Apple MacBook Air </p><p class="info">13.3英寸笔记本电脑 银色(2017款Core i5 处理器/8GB内存/128GB闪存 MQD32CH/A)</p><p class="price">7588.00￥</p></div></a></pre>
                                        </div>
                                    </div>
                                </li>
                                <li class="chatmsg">
                                    <div class="showtime"></div>
                                    <div class="" style="position: absolute;left:3px;">
                                        <img class="my-circle  se_pic" src="https://www.wolive.cc/assets/upload/10.jpg" width="50px"
                                            height="50px">
                                    </div>
                                    <div class="outer-left">
                                        <div class="customer">
                                            <pre><a href="http://www.wolive.cc/demo/index/product1" target="_blank" class="product_msg"><img src="https://www.wolive.cc/assets/images/pro.jpg"><div class="product_info"><p class="title"> Apple MacBook Air </p><p class="info">13.3英寸笔记本电脑 银色(2017款Core i5 处理器/8GB内存/128GB闪存 MQD32CH/A)</p><p class="price">7588.00￥</p></div></a></pre>
                                        </div>
                                    </div>
                                </li>
                                <li class="chatmsg">
                                    <div class="showtime">10:49</div>
                                    <div class="" style="position: absolute;left:3px;">
                                        <img class="my-circle  se_pic" src="https://www.wolive.cc/assets/upload/10.jpg" width="50px"
                                            height="50px">
                                    </div>
                                    <div class="outer-left">
                                        <div class="customer">
                                            <pre><a href="http://www.wolive.cc/demo/index/product1" target="_blank" class="product_msg"><img src="https://www.wolive.cc/assets/images/pro.jpg"><div class="product_info"><p class="title"> Apple MacBook Air </p><p class="info">13.3英寸笔记本电脑 银色(2017款Core i5 处理器/8GB内存/128GB闪存 MQD32CH/A)</p><p class="price">7588.00￥</p></div></a></pre>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <script type="text/javascript">
            
                            window.onresize = function () {
                                var height = document.documentElement.clientHeight;
                                $("#callCenter .chatroom").css("height", (height - 170) + "px");
                            }
            
                            document.getElementById("wrap").onscroll = function () {
                                var t = document.getElementById("wrap").scrollTop;
                                if (t == 0) {
                                    var sdata = $.cookie("cu_com");
                                    if (!sdata) {
                                        return;
                                    }
                                    var jsondata = $.parseJSON(sdata);
                                    var chas = jsondata.visiter_id;
                                    if ($.cookie("hid") != "") {
                                        getdata(chas);
                                    }
                                }
                            }
            
            
                            function info() {
                                layer.tips("将您剪切好的图片粘贴到输入框即可", "#paste", { tips: [1, '#9EC6EA'] });
                            }
            
                        </script>
                        <div class="footer">
                            <div class="msg-toolbar" style="background: #fff;border: none;">
                                <div style="display: flex;">
                                    <a id="face_icon" onclick="faceon()"><i class="fa left fa-smile-o"></i></a>
                                    <a>
                                        <form id="picture" enctype="multipart/form-data" style="position: relative;overflow: hidden;">
                                            <div class="am-form-group am-form-file"><i class="fa left fa-image"></i>
                                                <input type="file" name="upload" accept="image/*" onchange="put()">
                                            </div>
                                        </form>
                                    </a>
                                    <a>
                                        <form id="file" enctype="multipart/form-data" style="position: relative;overflow: hidden;">
                                            <div class="am-form-group am-form-file"><i class="fa left fa-folder-o"></i>
                                                <input type="file" name="folder" onchange="putfile()">
                                            </div>
                                        </form>
                                    </a>
                                    <a onclick="getvideo()"><i class="fa left fa-youtube-play"></i></a>
                                    <a onclick="getaudio()"><i class="fa left fa-microphone" style="font-size: 22px;cursor: pointer;"></i></a>
                                    <a href="javascript:getblack()"><i class="fa left fa-minus-circle" title="拖入黑名单"></i></a>
                                    <a onclick="getswitch()"><i class="fa left fa-random"></i></a>
                                    <a onclick="gethistory()"><i class="fa left fa-file-text-o" title="聊天记录"></i></a>
                                </div>
                                <div>
                                    <a onmouseover="info()"><i class="fa fa-cut"></i> 怎样发截图？</a>
                                </div>
                            </div>
            
                            <div class="tool-box">
            
                                <div class="faces_content">
            
                                    <div class="faces_main">
                                        <ul>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="羊驼" src="https://home.wolive.cc/upload/emoji/emo_01.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="神马" src="https://home.wolive.cc/upload/emoji/emo_02.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="浮云" src="https://home.wolive.cc/upload/emoji/emo_03.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="给力" src="https://home.wolive.cc/upload/emoji/emo_04.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="围观" src="https://home.wolive.cc/upload/emoji/emo_05.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="威武" src="https://home.wolive.cc/upload/emoji/emo_06.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="熊猫" src="https://home.wolive.cc/upload/emoji/emo_07.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="兔子" src="https://home.wolive.cc/upload/emoji/emo_08.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="奥特曼" src="https://home.wolive.cc/upload/emoji/emo_09.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="囧" src="https://home.wolive.cc/upload/emoji/emo_10.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="互粉" src="https://home.wolive.cc/upload/emoji/emo_11.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="礼物" src="https://home.wolive.cc/upload/emoji/emo_12.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="微笑" src="https://home.wolive.cc/upload/emoji/emo_13.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="嘻嘻" src="https://home.wolive.cc/upload/emoji/emo_14.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="哈哈" src="https://home.wolive.cc/upload/emoji/emo_15.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="可爱" src="https://home.wolive.cc/upload/emoji/emo_16.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="可怜" src="https://home.wolive.cc/upload/emoji/emo_17.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="抠鼻" src="https://home.wolive.cc/upload/emoji/emo_18.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="吃惊" src="https://home.wolive.cc/upload/emoji/emo_19.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="害羞" src="https://home.wolive.cc/upload/emoji/emo_20.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="调皮" src="https://home.wolive.cc/upload/emoji/emo_21.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="闭嘴" src="https://home.wolive.cc/upload/emoji/emo_22.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="鄙视" src="https://home.wolive.cc/upload/emoji/emo_23.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="爱你" src="https://home.wolive.cc/upload/emoji/emo_24.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="流泪" src="https://home.wolive.cc/upload/emoji/emo_25.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="偷笑" src="https://home.wolive.cc/upload/emoji/emo_26.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="亲亲" src="https://home.wolive.cc/upload/emoji/emo_27.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="生病" src="https://home.wolive.cc/upload/emoji/emo_28.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="太开心" src="https://home.wolive.cc/upload/emoji/emo_29.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="白眼" src="https://home.wolive.cc/upload/emoji/emo_30.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="右哼哼" src="https://home.wolive.cc/upload/emoji/emo_31.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="左哼哼" src="https://home.wolive.cc/upload/emoji/emo_32.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="嘘" src="https://home.wolive.cc/upload/emoji/emo_33.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="衰" src="https://home.wolive.cc/upload/emoji/emo_34.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="委屈" src="https://home.wolive.cc/upload/emoji/emo_35.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="呕吐" src="https://home.wolive.cc/upload/emoji/emo_36.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="打哈欠" src="https://home.wolive.cc/upload/emoji/emo_37.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="抱抱" src="https://home.wolive.cc/upload/emoji/emo_38.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="怒" src="https://home.wolive.cc/upload/emoji/emo_39.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="问号" src="https://home.wolive.cc/upload/emoji/emo_40.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="馋" src="https://home.wolive.cc/upload/emoji/emo_41.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="拜拜" src="https://home.wolive.cc/upload/emoji/emo_42.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="思考" src="https://home.wolive.cc/upload/emoji/emo_43.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="汗" src="https://home.wolive.cc/upload/emoji/emo_44.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="打呼" src="https://home.wolive.cc/upload/emoji/emo_45.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="睡" src="https://home.wolive.cc/upload/emoji/emo_46.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="钱" src="https://home.wolive.cc/upload/emoji/emo_47.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="失望" src="https://home.wolive.cc/upload/emoji/emo_48.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="酷" src="https://home.wolive.cc/upload/emoji/emo_49.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="好色" src="https://home.wolive.cc/upload/emoji/emo_50.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="生气" src="https://home.wolive.cc/upload/emoji/emo_51.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="鼓掌" src="https://home.wolive.cc/upload/emoji/emo_52.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="晕" src="https://home.wolive.cc/upload/emoji/emo_53.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="悲伤" src="https://home.wolive.cc/upload/emoji/emo_54.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="抓狂" src="https://home.wolive.cc/upload/emoji/emo_55.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="黑线" src="https://home.wolive.cc/upload/emoji/emo_56.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="阴险" src="https://home.wolive.cc/upload/emoji/emo_57.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="怒骂" src="https://home.wolive.cc/upload/emoji/emo_58.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="心" src="https://home.wolive.cc/upload/emoji/emo_59.gif">
                                                </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;">
                                                    <img title="伤心" src="https://home.wolive.cc/upload/emoji/emo_60.gif">
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
            
                            <div class="msg-input">
                                <div class="input-box">
                                    <textarea id="text_in" class="edit-ipt" style="overflow-y: auto; font-weight: normal; font-size: 14px; overflow-x: hidden; word-break: break-all; font-style: normal; outline: none;padding: 5px;border:none;height:150px;"
                                        contenteditable="true" hidefocus="true" tabindex="0"></textarea>
                                </div>
                            </div>
                        </div>
            
                        <div class="msg-toolbar-footer grey12" style="bottom:63px;">
                            <div class="btn-group">
                                <button type="button" onclick="send()" class="btn btn-danger btn-flat" style="width: 70px; height: 40px;">发送</button>
                                <button type="button" class="btn btn-danger dropdown-toggle btn-flat" data-toggle="dropdown">
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="choosetype(this);" type='1'><i id="type1" class="fa fa-check hidden"></i>按Enter键发送消息，Ctrl+Enter换行</a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="choosetype(this);" type='2'><i id="type2" class="fa fa-check"></i>按Ctrl+Enter键发送消息，Enter换行</a>
                                </div>
                            </div>
                        </div>
                        
                    </section>
                    <section class="chat-info">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="nav-item" style="width:50%;"><a class="nav-link active" href="#customerInfo" data-toggle="tab">访客信息</a></li>
                                <li class="nav-item" style="width:50%;"><a class="nav-link" href="#speedMoudle" data-toggle="tab">快捷消息</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="customerInfo">
                                        <div style="margin-top: 20px;font-size: 15px;">
                                                <i class="am-icon-xs am-icon-bookmark" style="cursor: pointer;" title="访客浏览页面"></i>：
                                                <span class="record">
                                                    <a href="https://www.wolive.cc/demo/index/product1" target="_blank">https://www.wolive.cc/demo/index/product1</a>
                                                </span>
                                            </div>
                                            <div style="margin-top: 20px;font-size: 15px;">
                                                <i class="am-icon-map-marker am-icon-xs" title="访客来源地址"></i>：
                                                <span class="iparea">中国 、江苏 、苏州</span>
                                            </div>
                    
                                            <div style="margin-top: 20px;font-size: 15px;">
                                                <i class="am-icon-user-md am-icon-xs" title="访客状态">：
                                                    <span id="v_state">在线</span>
                                                </i>
                                            </div>
                    
                                            <div style="margin-top: 20px;position: relative;">
                                                <span style="font-size: 18px;"><i class="am-icon-user am-icon-xs" title="访客名称"></i>：</span>
                                                <input type="text" id="visiter_name" class="layui-input" style="position: absolute;right:0;top:0;width: 90%;height: 30px;" onblur="saveinfo()" />
                                            </div>
                    
                                                <div style="margin-top: 20px;position: relative;">
                                                <span style="font-size: 18px;"><i class="am-icon-phone am-icon-xs" title="访客联系方式"></i>：</span>
                                                <textarea  id="connect" class="layui-input" style="position: absolute;right:0;top:0;width: 90%;height: 60px;" onblur="saveinfo()"></textarea>
                                            </div>
                                            <div style="margin-top: 20px;position: relative;">
                                                <span style="font-size: 14px;">
                                                    <i class="am-icon-comment am-icon-xs" title="访客备注"></i>：</span>
                                                <textarea id="comment" placeholder="这里填写备注信息，填写后自动保存" class="layui-input" style="position: absolute;right:0;top:0;width: 90%;height: 60px;padding:8px;font-size:14px"
                                                    onblur="saveinfo()"></textarea>
                                            </div>
                                </div>
                                <div class="tab-pane" id="speedMoudle">

                                </div>
                            </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection