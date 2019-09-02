$(function () {
    var info;
    var echoClient;
    var manager_info;
    var callCenterConfig;
    var myTimer;
    var initSecond = 0;
    var timeoutFlag = true;

    function init(){
        checkLogin();
        echoClient = EchoClient.prototype;
        echoClient.setFunc({privateEventFunc:privateEventFunc});
        echoClient.init(info);
        var m = localStorage.getItem('manager_info');
        if(m) {
            manager_info =  JSON.parse(m);
            echoClient.saveManagerInfo(manager_info);
        }
        timer();
        bindEvent();
    }

    //定时器清除
    function timerClear(flag = false){
        clearTimeout(myTimer);
        if(flag) initSecond = 0;

    }

    //定时器
    function timer(){
        var timestamp = (Date.parse(new Date()))/1000;
        localStorage.setItem('call_center_timestamp', timestamp);
        initSecond++;
        if(initSecond > callCenterConfig.timeout_length && timeoutFlag) {  // 超时提示
            timeoutFlag = false;
            if(confirm("未操作事件过长,是否要退出")) {
                echoClient.logout();
            }
        }
        if(initSecond > callCenterConfig.disconnect_length) { // 超时 断开连接
            timerClear();
            alert('对不起, 聊天已经结束, 请重新登录');
            echoClient.logout();
        }
        myTimer = setTimeout(timer, 1000);
    }

    //收到私聊消息的时候 @todo 分类
    function privateEventFunc(r){
        if(r.receiver !== "customer") return;
        if(r.category === 'event') {
            handleEvent(r);
        } else {
            handleMessage(r);
            timer();
        }
    }

    //事件处理
    function handleEvent(r){
        console.log(r);
        if(r.type ===  'manager_connect') {
            echoClient.saveManagerInfo(r.content);
            showManagerMessage('客服 '+r.content.nickname+' 接入');
        } else if(r.type === 'manager_change') {
            echoClient.saveManagerInfo(r.content);
            showManagerMessage('已为您转接客服 '+r.content.nickname);
            echoClient.record_id = r.content.record_id;
            var customer = localStorage.getItem('customer_info');
            customer = JSON.parse(customer);
            customer.record_id = r.content.record_id;
            localStorage.setItem('customer_info', JSON.stringify(customer));
        }
    }

    //信息处理
    function handleMessage(r) {
        showManagerMessage(r.content);
    }

    // 客服信息显示
    function showManagerMessage(content) {
        var html = buildManager(content);
        $("#mychat").append(html);
        echoClient.soundPlay();
        scrollBottom();
    }

    // 构建客服信息
    function buildManager(content){
        var manager = localStorage.getItem('manager_info');
        var img, name;
        if(manager) {
            manager = JSON.parse(manager);
            img = '/'+manager.head_img;
            name = manager.nickname
        } else {
            img = '/callCenter/img/a.png';
            name = '客服';
        }
        content = replace(content);
        var html = '<div class="customer-chat-content-message-operator serviceChat chatWindow" data-id="0">' +
            '<div class="avatar customer-chat-content-message-avatar-operator">' +
            '<img src="'+img+'" alt="">' +
            '</div>' +
            '<div class="customer-chat-content-message-column">' +
            '<div class="customer-chat-content-message-author">'+name+'</div>' +
            '<div class="customer-chat-content-message-time">'+getCurrentTime()+'</div>' +
            '<div class="customer-chat-content-message-body">' +
            '<div class="mychatMessDe">'+content+'</div>' +
            '</div>' +
            '</div>' +
            '<div class="clear-both"></div>' +
            '</div>';
        return html;
    }

    //绑定事件
    function bindEvent() {

        //初始化信息
        $('#init-message').text(callCenterConfig.welcome);

// 发送按钮点击
        $(document).on('click', '.btn-send', function(){
            var getVal = $("#customer-chat-message-input").val();
            var getDataId = $("#mychat").children(".chatWindow:last-child").attr("data-id");
            if(!getDataId) getDataId = 0;
            if(getVal != "") {
                getVal = replace(getVal);
                $(".record").hide();
                if(getDataId == 1) {
                    $("#mychat").children(".chatWindow:last-child").find(".mychatMessDe").append('<div>' + getVal + '</div>')
                } else if(getDataId == 0) {
                    $(".mychat").append(buildCustomer('<div>'+getVal+'</div>'))
                }
                echoClient.send(getVal);
                timerClear(true);
                clearInput();
                kickBack();
                scrollBottom();
            }
        });

        //键盘事件监听,信息发送
        $(document).keyup(function(event) {
            if(event.keyCode == 13 ) {
                $('.btn-send').click();
            }
        });

        //模糊查询
        $("#customer-chat-message-input").on("input  propertychange", function() {
            if(echoClient.manager_id) return; //当客服存在的时候直接return
            if($(this).val()==""){
                $(".record").hide();
            }else{
                $.ajax({
                    url:'/call_center/template_list',
                    method : 'get',
                    dataType : 'json',
                    data:{content:$(this).val()},
                    success:function(r){
                        if(r.code !== 200) return;
                        var html = '';
                        var data = r.data;
                        for(var i of data) {
                            html += '<li item="'+i.id+'">'+i.content+'</li>';
                        }
                        $('#keyword').html(html);
                        $(".record").show();
                    }
                });
            }
        });

        //其它地方点击事件
        $(document).on("click", function() {
            $(".record").hide();
        });

        //模板消息列表点击
        $(document).on("click", ".record li", function() {
            var _this = $(this);
            var item_id = _this.attr('item');
            var item_value = _this.text();
            $(".mychat").append(buildCustomer(item_value));
            scrollBottom();
            clearInput();
            echoClient.send(item_id, 'template');
            timerClear(true);
        });

        // 结束聊天
        $(document).on("click","#customer-chat-action-end-chat",function(){
            echoClient.logout();
        });

        //图片上传事件
        $(document).on('change', '#file-input', function() {
            var file = document.getElementById("file-input").files[0];
            var formData = new FormData();
            formData.append('file', file);
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: "/call_center/upload",
                type: "post",
                data: formData,
                contentType: false,
                dataType:'json',
                processData: false,
                mimeType: "multipart/form-data",
                success: function (r) {
                    if(r.code !== 200) {
                        alert(r.info);
                        return;
                    }
                    var getDataId = $("#mychat").children(".chatWindow:last-child").attr("data-id");
                    var url = r.data.url;
                    var content = '<img class="upPic" src="' + url + '">';
                    echoClient.send(content);
                    if(!getDataId) getDataId = 0;
                    if(getDataId == 1) {
                        $("#mychat").children(".chatWindow:last-child").find(".mychatMessDe").append(content)
                    } else if(getDataId == 0) {
                        $(".mychat").append(buildCustomer(content))
                    }
                    scrollBottom();
                }
            });
        })

        // 展现工具
        $("#customer-chat-button-settings").click(function(){
            $(".customer-chat-header-menu").toggle(500)
        });

        // 是否有声音
        $(document).on("click","#customer-chat-setting-toggle-sound",function(){
            var _this = $(this);
            var flag = _this.attr('flag');
            if(flag == 1) {
                _this.addClass("customer-chat-disabled")
                _this.attr('flag', 0);
                echoClient.setSound(0);
            } else {
                _this.removeClass("customer-chat-disabled");
                _this.attr('flag', 1);
                echoClient.setSound(1);
            }
        });

        // 表情展现
        $(".customer-chat-content-message-emots-button").click(function() {
            var show = $(".customer-chat-emots-menu").attr('show');
            if(show == 1) {
                $(".customer-chat-emots-menu").hide().attr('show', 0);
            }  else {
                $(".customer-chat-emots-menu").show().attr('show', 1);
            }
        });

        // 表情展现在输入框中
        $(".customer-chat-emoticon").click(function() {
            var face = $(this).attr("id");
            var getIptVal = $("#customer-chat-message-input").val();
            face = "["+face+"]";
            var serIptVal = getIptVal + ' ' + face;
            $("#customer-chat-message-input").val(serIptVal);
            $(".customer-chat-emots-menu").hide().attr('show', 0);
        })
    }

    function replace(str){
        var data = {
            "e_:)":"emot-1",
            "e_;)":"emot-2",
            "e_:(":"emot-3",
            "e_:D":"emot-4",
            "e_:P":"emot-5",
            "e_=)":"emot-6",
            "e_:|":"emot-7",
            "e_=|":"emot-8",
            "e_>:|":"emot-9",
            "e_>:D":"emot-10",
            "e_o_O":"emot-11",
            "e_=O":"emot-12",
            "e_<3":"emot-13",
            "e_:S":"emot-14",
            "e_:*":"emot-15",
            "e_:$":"emot-16",
            "e_=B":"emot-17",
            "e_:-D":"emot-18",
            "e_;-D":"emot-19",
            "e_*-D":"emot-20",
        };
        var newValue;
        newValue = str.replace(/\[([\W\w].*?)\]/ig, function(word){
            word = word.substr(0, word.length-1);
            word = word.substr(1, word.length-1);
            var clas = data['e_'+ word];
            var i = '<i class="emot '+clas+'"></i>'
            return i
        });
        return newValue
    }

    // 检查登录信息
    function checkLogin() {
        var timestamp = localStorage.getItem('call_center_timestamp');
        var time = (Date.parse(new Date()))/1000;
        if(!timestamp) {
            timestamp = time;
            localStorage.setItem('call_center_timestamp', time)
        }
        var current = time;
        info = localStorage.getItem('customer_info');
        callCenterConfig = localStorage.getItem('call_center_config');
        if(!info) {
            window.location.href = '/call_center/login';
        }
        info = JSON.parse(info);
        callCenterConfig = JSON.parse(callCenterConfig);
        if(current - timestamp > callCenterConfig.leave_length) {
            localStorage.removeItem('call_center_timestamp');
            EchoClient.prototype.logout();
        }
    }

    // 清空输入框
    function clearInput() {
        $("#customer-chat-message-input").val("").blur();
    }

    // 客户信息构建
    function buildCustomer(content){
        var html = '<div class="mychatoperator chatWindow" data-id="1">' +
            '<div class="myChatCon">' +
            '<div class="mychatTime">'+getCurrentTime()+'</div>' +
            '<div class="mychatAuthor">你自己</div>' +
            '<div class="mychatMess">' +
            '<div class="mychatMessDe">' + content + '</div>' +
            '</div>' +
            '</div>' +
            '<div class="myChatHead"><img src="'+info.avatar+'" alt=""></div>' +
            '<div class="clear-both"></div>' +
            '</div>'
        return html;
    }

    // 滚动到底部
    function scrollBottom() {
        var getH = document.getElementById("mychat").offsetHeight
        window.scrollTo(0, getH);
    }

    function getCurrentTime() {
        var now = new Date();
        var year = now.getFullYear();       //年
        var month = now.getMonth() + 1;     //月
        var day = now.getDate();            //日
        var hh = now.getHours();            //时
        var mm = now.getMinutes();          //分
        var clock = year + "-";
        if(month < 10)
            clock += "0";
        clock += month + "-";
        if(day < 10)
            clock += "0";
        clock += day + " ";
        if(hh < 10)
            clock += "0";
        clock += hh + ":";
        if (mm < 10) clock += '0';
        clock += mm;
        return clock;
    }

    // 回到开始位置 针对苹果手机的一个兼容
    function kickBack() {
        setTimeout(() => {
            window.scrollTo(0, document.body.scrollTop + 1);
            document.body.scrollTop >= 1 && window.scrollTo(0, document.body.scrollTop - 1);
        }, 10)
    }

    init();
});





