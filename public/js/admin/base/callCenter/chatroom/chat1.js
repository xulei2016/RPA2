



function getnow(data) {
    // 暂时屏蔽，不需要调用
    return;
    $.ajax({
        url:"/admin/set/getchatnow",
        type:"post",
        data:{sdata:data},
        dataType:'json',
        success:function (res) {

            var a="";
            if(res.code == 0){
               getchat();
            }
        }
    });
}


//储存 频道
var chaarr = new Array();

//初始化 监听
var getonline = function () {
    getchat();
    $.cookie("time","");
    //$(".conversation").empty();
};

$(function () {
    getonline();
});

// 获取访客状态
function getstatus(cha) {
    $.ajax({
        url:'/admin/set/getstatus',
        type:'post',
        data:{channel:cha},
        dataType:'json',
        success:function(res){
            if(res.code ==0){
                if(res.data){
                  if(res.data.state == 'online'){
                    $("#v_state").text("在线");
                  }else{
                    $("#v_state").text("离线");
                  }
                }
            }
        }
    });
}

// 正在聊天的队列表
function getchat() {
    $.ajax({
        url: "/admin/set/getchats",
        success: function (res) {
          
            
            if (res.code == 0) {
                $("#chat_list").empty();
                var sdata = $.cookie('cu_com');
                if (sdata) {
                    var json = $.parseJSON(sdata);
                    var debug = json.visiter_id;
                } else {
                    var debug = "";
                }
                var data = res.data;
                var a = '';
                $.each(data, function (k, v) {
                   
                    var str = JSON.stringify(v);
                    chat_data['visiter'+v.vid] =v;
                    if (debug == v.visiter_id) {

                        $(".chatbox").removeClass('hide');
                        $(".no_chats").addClass('hide');

                       if (v.state == 'online') {
                            a += '<div id="v' + v.channel + '" class="visiter onclick" onmouseover="showcut(this)" onmouseout="hidecut(this)" ><i class="layui-icon myicon hide" title="删除" style="font_weight:blod" onclick="cut(' + "'" + v.visiter_id + "'" + ')">&#x1006;</i><span id="c' + v.channel  + '" class="notice-icon hide"></span>';
                            a += "<div class='visit_content' onclick='choose(" +v.vid+ ")'><img class='am-radius v-avatar' id='img" +v.channel + "' src='" + v.avatar + "' width='50px'><span class='c_name'>" + v.visiter_name + "</span><div id='msg" +v.channel  + "' class='newmsg'>"+v.content+"</div>";
                            a += '</div></div>';
                        } else {
                            a += '<div id="v' + v.channel + '" class="visiter onclick" onmouseover="showcut(this)" onmouseout="hidecut(this)"><i class="layui-icon myicon hide" title="删除" style="font_weight:blod" onclick="cut(' + "'" + v.visiter_id + "'" + ')">&#x1006;</i><span id="c' +v.channel + '" class="notice-icon hide"></span>';
                            a += "<div class='visit_content' onclick='choose(" +v.vid+ ")'><img class='am-radius v-avatar icon_gray' id='img" + v.channel  + "' src='" + v.avatar + "' width='50px'><span class='c_name'>" + v.visiter_name + "</span><div id='msg" +v.channel  + "' class='newmsg'>"+v.content+"</div>";
                            a += '</div></div>';
                        }

                    } else {
                        if(v.count == 0){

                            if (v.state == 'online') {
                                a += '<div id="v' + v.channel + '" class="visiter" onmouseover="showcut(this)" onmouseout="hidecut(this)"><i class="layui-icon myicon hide" title="删除" style="font_weight:blod" onclick="cut(' + "'" + v.visiter_id + "'" + ')">&#x1006;</i><span id="c' +v.channel + '" class="notice-icon hide"></span>';
                                a += "<div class='visit_content' onclick='choose(" +v.vid+ ")'><img class='am-radius v-avatar' id='img" + v.channel + "' src='" + v.avatar + "'  width='50px'><span class='c_name'>" + v.visiter_name + "</span><div id='msg" + v.channel + "' class='newmsg'>"+v.content+"</div>";
                                a += '</div></div>';
                            } else {
                                a += '<div  id="v' + v.channel + '" class="visiter" onmouseover="showcut(this)" onmouseout="hidecut(this)"><i class="layui-icon myicon hide" title="删除" style="font_weight:blod" onclick="cut(' + "'" + v.visiter_id + "'" + ')">&#x1006;</i><span id="c' + v.channel + '" class="notice-icon hide"></span>';
                                a += "<div class='visit_content' onclick='choose(" +v.vid+ ")'><img class='am-radius v-avatar icon_gray' id='img" + v.channel + "' src='" + v.avatar + "'  width='50px'><span class='c_name'>" + v.visiter_name + "</span><div id='msg" + v.channel + "' class='newmsg'>"+v.content+"</div>";
                                a += '</div></div>';
                            }

                        }else{

                            if (v.state == 'online') {
                                a += '<div id="v' + v.channel + '" class="visiter" onmouseover="showcut(this)" onmouseout="hidecut(this)"><i class="layui-icon myicon hide" title="删除" style="font_weight:blod" onclick="cut(' + "'" + v.visiter_id + "'" + ')">&#x1006;</i><span id="c' +v.channel + '" class="notice-icon">'+v.count+'</span>';
                                a += "<div class='visit_content' onclick='choose(" +v.vid+ ")'><img class='am-radius v-avatar' id='img" + v.channel + "' src='" + v.avatar + "'  width='50px'><span class='c_name'>" + v.visiter_name + "</span><div id='msg" + v.channel + "' class='newmsg'>"+v.content+"</div>";
                                a += '</div></div>';
                            } else {
                                a += '<div  id="v' + v.channel + '" class="visiter" onmouseover="showcut(this)" onmouseout="hidecut(this)"><i class="layui-icon myicon hide" title="删除" style="font_weight:blod" onclick="cut(' + "'" + v.visiter_id + "'" + ')">&#x1006;</i><span id="c' + v.channel + '" class="notice-icon">'+v.count+'</span>';
                                a += "<div class='visit_content' onclick='choose(" +v.vid+ ")'><img class='am-radius v-avatar icon_gray' id='img" + v.channel + "' src='" + v.avatar + "'  width='50px'><span class='c_name'>" + v.visiter_name + "</span><div id='msg" + v.channel + "' class='newmsg'>"+v.content+"</div>";
                                a += '</div></div>';
                            }

                        }
                        
                     
                    }
                  
                });
                $("#chat_list").append(a);
            } else {
                $("#chat_list").empty();
                $(".chatbox").addClass('hide');
                $(".no_chats").removeClass('hide');
                $.cookie('cu_com', "");
            }
        }
    });
}

function showcut(obj){
    $(obj).children('i').removeClass('hide');
}

function hidecut(obj){
    $(obj).children('i').addClass('hide');
}

//获取队列的实时数据
function getwait() {

    $.ajax({
        url: "/admin/set/getwait",
        dataType:'json',
        success: function (res) {

            if (res.code == 0) {
              
                $("#wait_list").empty();
                $("#waitnum").addClass('hide');
                if (!res.data.length) {
                    return;
                }
                var a = "";
                var state = '';
                $.each(res.data, function (k, v) {
                    state = v.state =='offline' ? 'icon_gray' : '';
                    a += '<div class="waiter">';
                    a += '<img id="img'+v.channel+'" class="am-radius w-avatar '+state+'" src="' + v.avatar + '" width="50px" height="50px"><span class="wait_name">' + v.visiter_name + '</span>';
                    a += "<div class='newmsg'>"+v.groupname+"</div>";
                    a += '<i class="mygeticon " title="认领" onclick="get(' + "'" + v.visiter_id + "'" + ')"></i></div>';
                });
                $("#wait_list").append(a);

                $("#notices-icon").removeClass('hide');
                $("#waitnum").removeClass('hide');
                $("#waitnum").text(res.num);
                document.title ="【有客户等待】"+myTitle;


            } else {

                document.title =myTitle;
            }
        }
    });

}


//获取黑名单
function getblacklist() {

    $.ajax({
        url: "/admin/set/getblackdata",
        dataType:'json',
        success: function (res) {

            if (res.code == 0) {
              
                $("#black_list").empty();
                var data = res.data;
                var a = "";
                $.each(data, function (k, v) {

                    a += '<div class="visiter"><img class="am-radius v-avatar" src="' + v.avatar + '" width="50px">';
                    a += ' <span style="position:absolute;left:58px;top:20px;font-size: 14px;">' + v.visiter_name + '</span><button class="am-btn am-btn-danger am-btn-xs" style="position:absolute;right:10px;top:24px;" onclick="cut(' + "'" + v.visiter_id + "'" + ')">删除</button></div>';
                });

                $("#black_list").append(a);
            } else {

                $("#black_list").empty();
            }
        }
    });
}




//获取ip的详细信息
var getip = function (cip) {
    $.ajax({
        url: "/admin/set/getipinfo",
        type: "get",
        data: {
            ip: cip
        },
        dataType:'json',
        success: function (res) {

            if(res.code == 0){
                var data = res.data;
                var str = "";
                str += data[0] + " 、";
                str += data[1] + " 、";
                str += data[2];
                $(".iparea").text(str);
            }
           
        }
    })
};

//标记已看消息
function getwatch(cha) {
    $.ajax({
        url: "/admin/set/getwatch",
        type: "post",
        data: {visiter_id: cha}
    });
}

//获取最近历史消息
function getdata(cha) {

    var avatver;
    var sdata = $.cookie("cu_com");
    if (sdata) {
        var jsondata = $.parseJSON(sdata);
        avatver = jsondata.avatar;
    }
    var showtime;
    var curentdata =new Date();
    var time =curentdata.toLocaleDateString();
    var cmin =curentdata.getMinutes();
    if($.cookie("hid") != "" ){
        var cid =$.cookie("hid");
    }else{
        var cid ="";
    }
  
    $.ajax({
        url: "/admin/set/chatdata",
        type: "post",
        data: {
            visiter_id: cha,hid:cid
        },
        dataType:'json',
        success: function (res) {
            // alert(res);
            if (res.code == 0) {
                var sdata = $.cookie("cu_com");
                if (sdata) {
                    var jsondata = $.parseJSON(sdata);
                    if (jsondata.visiter_id != cha) {
                        return;
                    }
                }
                getwatch(cha);
                var se = $("#chatmsg_submit").attr("name");
                var str = "";
                var data = res.data;
                var pic = $("#se_avatar").attr('src');

                $.each(data, function (k, v) {
                    showtime = "";
                    if(!getdata.puttime || v.timestamp - getdata.puttime > 60) {
                        var myDate = new Date(v.timestamp * 1000);
                        var puttime = myDate.toLocaleDateString();
                        if (puttime == time) {
                            showtime = get_time(v.timestamp * 1000);
                        } else {
                            showtime = get_date(v.timestamp * 1000);
                        }
                    }

                    getdata.puttime = v.timestamp;
                    var picture_class = is_picture(v.content) ? ' no_border' : '';
                    if (v.direction == 'to_visiter') {

                        str += '<li class="chatmsg"><div class="showtime">'+showtime+'</div>';
                        str += '<div class="" style="position: absolute;top: 26px;right: 2px;"><img  class="my-circle cu_pic" src="' + pic + '" width="50px" height="50px"></div>';
                        str += "<div class='outer-right"+picture_class+"'><div class='service'>";
                        str += "<pre>" + v.content + "</pre>";
                        str += "</div></div>";
                        str += "</li>";
                    } else{

                        str += '<li class="chatmsg"><div class="showtime">' +showtime+ '</div><div class="" style="position: absolute;left:3px;">';
                        str += '<img class="my-circle  se_pic" src="' + avatver + '" width="50px" height="50px"></div>';
                        str += "<div class='outer-left"+picture_class+"'><div class='customer'>";
                        str += "<pre>" + v.content + "</pre>";
                        str += "</div></div>";
                        str += "</li>";
                    }
                   
                });

                var div = document.getElementById("wrap");
                if($.cookie("hid") == ""){
                 
                    $(".conversation").append(str);

                    if(div){
                        div.scrollTop = div.scrollHeight;
                    }
                }else{
                    $(".conversation").prepend(str);
                    if(res.data.length <= 2){

                        $("#top_div").remove();
                        $(".conversation").prepend("<div id='top_div' class='showtime'>已没有数据</div>");
                        if(div){
                            div.scrollTop =0;
                        }

                    }else{
                        if(div){
                            div.scrollTop =div.scrollHeight/3;
                        }
                    }
                }

                if(res.data.length >0){
                    $.cookie("hid",data[0]['cid']);

                }

            }
        }
    });
}

function saveinfo(){
    var data = $.cookie("cu_com");
    var jsondata = $.parseJSON(data);
    var name=$("#visiter_name").val();
    var connects=$('#connect').val();
    var comments=$("#comment").val();

    $.ajax({
      url:'/admin/manager/saveVisiter',
      type:'post',
      data:{comment:comments,visiter_id:jsondata.visiter_id},
      success:function(res){
        if(res.code == 0){
            getchat();
        }
      }
    });
    
}


function show(obj){
    
    $(obj).find('i').removeClass("mysize");
}

function hide(obj){
    $(obj).find('i').addClass("mysize");
}

function addreply(){
  
  var html='<form class="layui-form" style="margin-top:30px;">';
      html+='<div class="layui-form-item"><label class="layui-form-label">标签：</label>';
      html+='<div class="layui-input-block"><input id="tag" type="text" class="layui-input" style="width:260px" /></div></div>';
      html+='<div class="layui-form-item layui-form-text"><label class="layui-form-label">快捷用语：</label>';
      html+='<div class="layui-input-block"><textarea id="word" name="content" class="layui-textarea" style="width:260px" ></textarea></div></div>'
      html+='</form>';

    layer.open({
        type:1,
        title:'添加快捷语句',
        area: ['400px', '300px'],
        content: html,
        btn: ['确定', '取消'],
        yes:function(res){
              $.ajax({
                url: "/admin/manager/addword",
                type: "post",
                data: {word: $("#word").val(),tag:$("#tag").val()},
                success: function (res) {
                    if (res.code ==0) {
                        layer.msg(res.msg, {icon: 1,time:2000,end:function () {
                            getreply();
                            layer.closeAll();
                        }});
                    }
                }
            });
        }
       
    });
}

function close(id){
    $.ajax({
        url:'/admin/manager/delreply',
        type:'post',
        data:{id:id},
        success:function(res){
            if(res.code ==0){
                layer.msg(res.msg,{icon:1,end:function(){
                    
                     $("#reply"+id).remove();
                }});
            }
        }
    })
}


function showon(str){
  
  $("#text_in").val(str);

}



function getOs() {
    var OsObject = "";

    if (isFirefox = navigator.userAgent.indexOf("Firefox") > 0) {
        return "Firefox";
    }
}

function showDiv(){
   
   $("#fuceng").toggleClass('hide');
}


$(function (){

$("#showinfo").on('click',function(event){

    showDiv();

    $(document).one("click", function () {
    
     $("#fuceng").addClass('hide');

    }); 
    event.stopPropagation();//阻止事件向上冒泡
});

$("#fuceng").click(function (event) 
{
    event.stopPropagation();//阻止事件向上冒泡
    
});
});





function choosetype(obj){

    $(obj).find('i').removeClass('hidden');
    $(obj).next().find('i').addClass('hidden'); 
    var type =$(obj).attr('type');
    $.cookie('type',type);

    types();
}


//获取qq截图的图片
(function () {
    var imgReader = function (item) {
        var sdata = $.cookie('cu_com');
        if (sdata) {
            var json = $.parseJSON(sdata);
            var img = json.avater;
        }

        var sid = $('#channel').text();
        var se = $("#chatmsg_submit").attr('name');
        var customer = $("#customer").text();
        var pic = $("#se_avatar").attr('src');
        var time;

        var blob = item.getAsFile(),
            reader = new FileReader();
        var formData = new FormData();
        var name = encodeURIComponent('img-' + new Date().getTime() + '.png');
        formData.append('upload', blob, name);
        $.ajax({
            url:'/admin/set/upload',
            type: 'POST',
            data: formData,
            //这两个设置项必填
            contentType: false,
            processData: false,
            success:function(res){
                if (res.code == 0) {
                    var content = '![]('+res.data+')';
                    $.ajax({
                        url:'/admin/set/chats',
                        type:'post',
                        data: {
                            visiter_id:sid,content: content, avatar: img
                        },
                        success:function(res){}
                    });
                }
            }
        });
        // 读取文件后将其显示在网页中
        reader.onload = function (e) {
            var msg = '';
            msg += "<img onclick='getbig(this)'  src='" + e.target.result + "'>";

                if($.cookie("time") == ""){
                    var myDate = new Date();
                        time = myDate.getHours()+":"+myDate.getMinutes();
                    var timestamp = Date.parse(new Date());
                    $.cookie("time",timestamp/1000);

                }else{

                    var timestamp = Date.parse(new Date());

                    var lasttime =$.cookie("time");
                    if((timestamp/1000 - lasttime) >30){
                        var myDate =new Date(timestamp);
                        time = myDate.getHours()+":"+myDate.getMinutes();
                    }else{
                        time ="";
                    }

                    $.cookie("time",timestamp/1000);

                }
            
                var str = '';
                str += '<li class="chatmsg"><div class="showtime">' + time + '</div>';
                str += '<div style="position: absolute;top: 26px;right: 2px;"><img  class="my-circle se_pic" src="' + pic + '" width="50px" height="50px"></div>';
                str += "<div class='outer-right no_border'><div class='service'>";
                str += "<pre>" + msg + "</pre>";
                str += "</div></div>";
                str += "</li>";

                $(".conversation").append(str);
                $("#text_in").empty();

                var div = document.getElementById("wrap");
                div.scrollTop = div.scrollHeight;

        };
        // 读取文件
        reader.readAsDataURL(blob);
    };
    // document.getElementById('text_in').addEventListener('paste', function (e) {
    //     // 添加到事件对象中的访问系统剪贴板的接口
    //     var clipboardData = e.clipboardData,
    //         i = 0,
    //         items, item, types;

    //     if (clipboardData) {
    //         items = clipboardData.items;
    //         if (!items) {
    //             return;
    //         }
    //         item = items[0];
    //         // 保存在剪贴板中的数据类型
    //         types = clipboardData.types || [];
    //         for (; i < types.length; i++) {
    //             if (types[i] === 'Files') {
    //                 item = items[i];
    //                 break;
    //             }
    //         }
    //         // 判断是否为图片数据
    //         if (item && item.kind === 'file' && item.type.match(/^image\//i)) {
    //             imgReader(item);
    //         }
    //     }
    // });
})();


// 视频通话
var getvideo =function(){

    var sid = $('#channel').text();
    var pic = $("#se_avatar").attr('src');

    var times = (new Date()).valueOf();
    var se = $("#se").text();
    //申请
    $.ajax({
        url: '/admin/set/apply',
        type: 'post',
        data: {id: sid,channel: times,avatar:pic,name:se},
        success:function(res){
            if(res.code !=0){
                layer.msg(res.msg,{icon:2,offset:'20px'});
            }else{
               
                var str='';
                str+='<div class="videos">';
                str+='<video id="localVideo" autoplay></video>';
                str+='<video id="remoteVideo" autoplay class="hidden"></video></div>';


                  layer.open({
                      type:1
                      ,title: '视频'
                      ,shade:0
                      ,closeBtn:1
                      ,area: ['440px', '378px']
                      ,content:str
                      ,end:function(){

                       
                         mediaStreamTrack.getTracks().forEach(function (track) {
                            track.stop();
                        });
        
                      }
                });
                  
                  
                 try{
                     connenctVide(times);
                 }catch(e){
                     console.log(e);
                     return;
                 }

            }
        }

    });
    
  
}




//
var gethistory=function(){

   var sdata = $.cookie("cu_com");
   var jsondata = $.parseJSON(sdata);
   var vid =jsondata.visiter_id;
    layer.open({
        type: 2,
        title: '该用户所有历史消息',
        area: ['600px', '500px'],
        shade: false,
        content: '/admin/index/history?visiter_id='+vid
    });

}

var recorder = '';

var getaudio =function(){

    //音频先加载
    var audio_context;
    var recorder;
    var wavBlob;
    //创建音频
    try {
        // webkit shim
        window.AudioContext = window.AudioContext || window.webkitAudioContext;
        window.URL = window.URL || window.webkitURL;
        audio_context = new AudioContext;

        if (!navigator.mediaDevices) {
            layui.layer.msg('语音创建失败');
        }
    } catch (e) {
        show_user_media_error(e);
        return;
    }
    navigator.mediaDevices.getUserMedia({audio: true}).then(function (stream) {
        var input = audio_context.createMediaStreamSource(stream);
        recorder = new Recorder(input);

        var falg = window.location.protocol;
        if (falg == 'https:') {
            recorder && recorder.record();

            //示范一个公告层
            layui.use(['jquery', 'layer'], function () {
                var layer = layui.layer;

                layer.msg('录音中...', {
                    icon: 16
                    , shade: 0.01
                    , skin: 'layui-layer-lan'
                    , time: 0 //20s后自动关闭
                    , btn: ['发送', '取消']
                    , yes: function (index, layero) {
                        //按钮【按钮一】的回调
                        recorder && recorder.stop();
                        recorder && recorder.exportWAV(function (blob) {
                            wavBlob = blob;
                            var fd = new FormData();
                            var wavName = encodeURIComponent('audio_recording_' + new Date().getTime() + '.wav');
                            fd.append('wavName', wavName);
                            fd.append('file', wavBlob);

                            var xhr = new XMLHttpRequest();
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState == 4 && xhr.status == 200) {
                                    jsonObject = JSON.parse(xhr.responseText);

                                    voicemessage = '<div style="cursor:pointer;text-align:center;" onclick="getstate(this)" data="play"><audio src="'+jsonObject.data.src+'"></audio><i class="layui-icon" style="font-size:25px;">&#xe652;</i><p>音频消息</p></div>';
                                    var content = 'voice('+jsonObject.data.src+')';

                                    var sid = $('#channel').text();
                                    var pic = typeof imghead != 'undefined' && imghead ? imghead : $("#se_avatar").attr('src');
                                    var time;

                                    var sdata = $.cookie('cu_com');

                                    if (sdata) {
                                        var json = $.parseJSON(sdata);
                                        var img = json.avater;

                                    }

                                    if($.cookie("time") == ""){
                                        var myDate = new Date();
                                        time = myDate.getHours()+":"+myDate.getMinutes();
                                        var timestamp = Date.parse(new Date());
                                        $.cookie("time",timestamp/1000);

                                    }else{

                                        var timestamp = Date.parse(new Date());

                                        var lasttime =$.cookie("time");
                                        if((timestamp/1000 - lasttime) >30){
                                            var myDate =new Date(timestamp*1000);
                                            time = myDate.getHours()+":"+myDate.getMinutes();
                                        }else{
                                            time ="";
                                        }

                                        $.cookie("time",timestamp/1000);
                                    }

                                    var str = '';
                                    str += '<li class="chatmsg"><div class="showtime">' + time + '</div>';
                                    str += '<div style="position: absolute;top: 26px;right: 2px;"><img  class="my-circle se_pic" src="' + pic + '" width="50px" height="50px"></div>';
                                    str += "<div class='outer-right'><div class='service'>";
                                    str += "<pre>" +  voicemessage + "</pre>";
                                    str += "</div></div>";
                                    str += "</li>";

                                    $(".conversation").append(str);
                                    $("#text_in").empty();

                                    var div = document.getElementById("wrap");
                                    div.scrollTop = div.scrollHeight;

                                    $.ajax({
                                        url: "/admin/set/chats",
                                        type: "post",
                                        data: {visiter_id: sid || visiter_id,content:  content, avatar: img}
                                    });
                                }
                            };
                            xhr.open('POST', '/admin/event/uploadVoice');
                            xhr.send(fd);
                        });
                        recorder.clear();
                        layer.close(index);
                    }
                    , btn2: function (index, layero) {
                        //按钮【按钮二】的回调
                        recorder && recorder.stop();
                        recorder.clear();
                        audio_context.close();
                        layer.close(index);
                    }
                });

            });
        } else {
            layer.msg('音频输入只支持https协议！');
        }
    }).catch(function (e) {
        show_user_media_error(e);
    });
};

var getstate = function (obj) {

    var c = obj.children[0];

    var type = 'mp3';
    var url = $(c).attr('src');
    if (/\.amr$/.test(url)) {
        type = 'amr';
        if (!obj.c) {
            obj.c = new BenzAMRRecorder();
            obj.c.initWithUrl(url).then(function () {
                getstate(obj);
            });
            obj.c.onEnded(function () {
                $(obj).attr('data', 'play');
                $(obj).find('i').html("&#xe652;");
            });
            return;
        } else {
            c = obj.c;
        }
    }

    var state = $(obj).attr('data');

    if (state == 'play') {
        c.play();
        $(obj).attr('data', 'pause');
        $(obj).find('i').html("&#xe651;");

    } else if (state == 'pause') {
        c.pause();
        $(obj).attr('data', 'play');
        $(obj).find('i').html("&#xe652;");
    }

    if (type != 'amr') {
        c.addEventListener('ended', function () {
            $(obj).attr('data', 'play');
            $(obj).find('i').html("&#xe652;");
        }, false);
    }
};

var getswitch = function () {

    var sdata = $.cookie("cu_com");
    var jsondata = $.parseJSON(sdata);
    var sid = jsondata.visiter_id;

    var se = $("#se").text();

    layer.open({
        type: 2,
        title: '客服列表',
        area: ['300px', '400px'],
        shade: false,
        content: '/admin/index/service?visiter_id=' + sid + '&name=' + se
    });
};

function search_by_name(keyword) {
    reset_search();
    $.each($('#chat_list .visiter'), function(k, v){
        var name = $(v).find(".c_name").text();
        if(name.indexOf(keyword)==-1) {
            $(v).css('display','none');
        }
    });
}

function reset_search() {
    $.each($('#chat_list .visiter'), function(k, v){
        $(v).css('display','block');
    });
}

(function () {
    var hm = document.createElement("script");
    hm.src = "/assets/libs/webrtc/recorder.js";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();

(function () {
    var hm = document.createElement("script");
    hm.src = "/assets/libs/audio/BenzAMRRecorder.js";
    var s = document.getElementsByTagName("script")[0];
    s.parentNode.insertBefore(hm, s);
})();
