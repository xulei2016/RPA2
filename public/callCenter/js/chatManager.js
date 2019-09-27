var echoManager;
var active_id = 0;  // 当前选中的聊天窗口
$(function(){
    window.onbeforeunload = function (){
        // echoManager.managerLeave();
    };

    window.onunload = function(){
        // echoManager.managerLeave();
    };

    function init(){
        echoManager = EchoManager.prototype;
        echoManager.manager_id = $('input#manager_id').val();
        echoManager.init();
        echoManager.setFunc({onCustomerMessage:onCustomerMessage});
        bindEvent();
    }

    function bindEvent(){
        // 添加聊天框
        $(document).on("click", ".online-users-info li", function() {
            var _this = $(this);
            var refresh = _this.attr('refresh');
            var manager_id = _this.attr('manager_id');
            if(manager_id && manager_id !== echoManager.manager_id) {
                swal(
                    '提示',
                    '该用户正在与其它客服聊天',
                    'warning'
                );
                return false;
            }
            var issetTab = false;
            var issetContent = false;
            var id = _this.attr('id');
            var customer_id = _this.attr('customer_id');
            if(!refresh) {
                showAllCustomerRecord(customer_id);
            }
            if(manager_id !== echoManager.manager_id) {
                if(echoManager.connectNumber > 8) {
                    swal(
                        '提示',
                        '超过最大聊天数量',
                        'warning'
                    );
                    return false;
                }
                _this.attr('manager_id', echoManager.manager_id);
                var record_id = _this.attr('record_id');
                echoManager.chatWithCustomer(customer_id, record_id);
                echoManager.connectNumber++;

                showCustomerRecord(customer_id); //显示聊天记录
            }
            handleChooseCustomer(customer_id);
            var name = _this.attr('customer_name');
            var tab_id = 'tab_'+id;
            var content_id = 'content_'+id;
            var tabs = $('.talk ul.nav-tabs li');
            tabs.each(function(i, item){
                if($(item).attr('id') === tab_id) {
                    issetTab = true;
                    $(item).addClass('active');
                } else {
                    $(item).removeClass('active');
                }
            });
            if(!issetTab) {
                var li = '<li customer_id="'+customer_id+'" role="presentation" id="'+tab_id+'" class="active"><a href="#'+content_id+'" data-toggle="tab" style="border: 0">'+name+'<span class="tabDel pull-right"></span></a></li>';
                $('.talk ul.nav-tabs').append(li);
            }
            var contents = $('.chat-content-out .chat-content');
            contents.each(function(i, item){
                if($(item).attr('id') === content_id) {
                    issetContent = true;
                    $(item).addClass('active').show();
                } else {
                    $(item).removeClass('active');
                }
            });
            if(!issetContent) {
                var content = '<div class="chat-content active" id="'+content_id+'"></div>';
                $('.chat-content-out').append(content);
            }
            _this.attr('refresh', 1);
            window.location.href = "#talk-content";
        });

        // tab切换
        $(document).on("click", ".talk .nav-tabs li", function() {
            var customer_id = $(this).attr('customer_id');
            $(this).addClass('active').siblings().removeClass('active');
            $('.chat-content-out .chat-content').removeClass('active');
            $('#content_customer_'+customer_id).addClass('active');
            handleChooseCustomer(customer_id);
        });

        // 删除tab
        $(document).on("click", ".nav-tabs .tabDel", function(event) {
            var _this = $(this);
            var content_id = _this.parent().attr('href');
            $(content_id).hide();
            _this.parents('li').remove();
            var li = $(".nav-tabs li").eq(0);
            var customer_id = li.attr('customer_id');
            if(typeof customer_id == "undefined") {
                active_id = 0;
            } else {
                active_id = customer_id;
            }
            echoManager.connectNumber++;
            changeCustomerInfo();
            event.stopPropagation()
        });

        // 键盘事件
        $(document).keyup(function(event) {
            var getVal = $("#customer-chat-message-input").val();
            var getDataId = $("#mychat").children(".chatWindow:last-child").attr("data-id");
            if(event.keyCode == 13 && getVal != "" && active_id) {
                getVal = replace(getVal);
                $("#content_customer_"+active_id).append(buildManager(getVal))
                echoManager.send(active_id, getVal);
                clearInput();
                scrollBottom();
            }
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
                        swal(
                            '提示',
                            r.info,
                            'warning'
                        );
                        return;
                    }
                    var url = r.data.url;
                    var content = '<img class="upPic" src="' + url + '">';
                    $("#content_customer_"+active_id).append(buildManager(content))
                    echoManager.send(active_id, content);
                    scrollBottom();
                }
            });
        });

        // 表情展现
        $(".customer-chat-content-message-emots-button").click(function() {
            $(".customer-chat-emots-menu").show();
            
        });
        
        $('.chat-content-out').on('click', function(){
            $(".customer-chat-emots-menu").hide();
        })

        // 表情展现在输入框中
        $(".customer-chat-emoticon").click(function() {
            var face = $(this).attr("id");
            var getIptVal = $("#customer-chat-message-input").val();
            face = "["+face+"]";
            var serIptVal = getIptVal + ' ' + face;
            $("#customer-chat-message-input").val(serIptVal);
            $(".customer-chat-emots-menu").hide()
            scrollBottom();
        });

        // 客服修改单个信息
        $(document).on("blur", ".changeDiv input", function() {
            var _this = $(this);
            var name = _this.attr('id');
            var value = _this.val();
            echoManager.axios('/call_center/updateOne','post',{key:name,value:value,id:echoManager.manager_id}).then(r => {
            }).catch(error => {
                console.log(error);
            })
        });

        // 跳转到其它页面
        $(document).on('click', '.sidebar-menu a', function(){
            echoManager.managerLeave();
        });

        // 展现模板信息
        $(document).on('mouseover', '.template li', function(){
            var _this = $(this);
            var t = _this.attr('id');
            $('.show-template div').html(getTemplate(t)).parent().show();
        });

        // 隐藏模板信息
        $(document).on('mouseout', '.template li', function(){
            $('.show-template').hide();
        });

        // 模板点击
        $(document).off('click', '.template li').on('click', '.template li', function(){
            var _this = $(this);
            var t = _this.attr('id');
            var content = getTemplate(t);
            $("#content_customer_"+active_id).append(buildManager(content))
            echoManager.send(active_id, content);
            clearInput();
            scrollBottom();
        });

        // 在线客服点击
        $(document).on('click', '.online-operators li', function(){
            var manager_id = $(this).attr('manager_id');
            if(manager_id == echoManager.manager_id) return;
            Swal.fire({
                showConfirmButton:false,
                title:'请选择你要进行的操作',
                type: 'info',
                html:
                    '<button class="btn btn-info transfer" manager_id="'+manager_id+'">客服转移</button> '
            });
        });

        // 客服转移功能
        $(document).on('click', 'button.transfer', function(){
            var _this = $(this);
            var manager_id = _this.attr('manager_id');
            if(!active_id) {
                Swal.fire('你没有选择任何客户');
                return;
            }
            echoManager.transferService(active_id, manager_id);
        })

        //常用操作
        //断开连接
        $("#disconnect").on('click', function(){
            if(!active_id) return false;
            swal({
                title: '警告',
                text: "您确认断开与对方的连接吗！",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '确认'
            }).then(function(isConfirm) {
                if (isConfirm.value) {
                    echoManager.axios(echoManager.logoutUrl, 'post', {customer_id:active_id}).then(r => {
                        console.log(r);
                    }).catch(error => {
                        console.log(error)
                    })
                }
            })
        });
    }


    function getTemplate(key) {
        var template_list = localStorage.getItem('template_list');
        template_list = JSON.parse(template_list);
        return template_list[key];
    }

    /**
     * 接受到用户消息的时候
     * @param data
     */
    function onCustomerMessage(data){
        if(data.category != 'message') return false;
        if(data.receiver != 'manager') return false;
        $("#content_customer_"+data.customer_id).append(buildCustomer(data));
        scrollBottom();
        if(active_id === data.customer_id) return false;
        var id = "customer_"+data.customer_id;
        $("#"+id+" span").attr('class', 'message')
        changeTips(data.customer_id);
    }

    /**
     * 处理选中客户的方法
     * @param customer_id
     */
    function handleChooseCustomer(customer_id){
        active_id = customer_id;
        $('#customer_'+ customer_id).find('span').attr('class', 'busy');
        changeTips(customer_id, true);
        changeCustomerInfo();
    }

    /**
     * 展示客户信息
     */
    function changeCustomerInfo() { // 先清空 在赋值
        $('#customer-name').text('暂无');
        var li = $('#customer_'+active_id);
        var customer_name = li.attr('customer_name');
        $('#customer-name').text(customer_name);
        // 清空数据
        $('#customer-zjzh').text('暂无');
        $('#customer-khrq').text('暂无');
        $('#customer-client').text('暂无');
        $('#customer-yyb').text('暂无');
        $('#customer-fxys').text('暂无');
        $('#customer-zjqy').text('暂无');
        $('#customer-sxf').text('暂无');
        $('#customer-bzj').text('暂无');
        $('#customer-jybm').text('');
        $('#customer-yq').text('暂无');
        // 请求获取客户信息并展示
        echoManager.axios(echoManager.getCustomerInfo, 'post', {id:active_id}).then(r => {
            $('#customer-zjzh').text(r.zjzh);
            $('#customer-khrq').text(r.khrq);
            $('#customer-client').text(r.client);
            $('#customer-zjqy').text(r.zjqy);
            $('#customer-sxf').text(r.sxf);
            $('#customer-bzj').text(r.bzj);
            $('#customer-yyb').text(r.yyb);
            $('#customer-fxys').text(r.fxys);
            $('#customer-yq').text(r.yq);
            var jybms = r.jybms;
            var jy = '';
            for(let jybm of jybms) {
                jy += '<p>'+jybm.name+': <span class="pull-right">'+jybm.auth+'</span></p>'
            }
            $('#customer-jybm').html(jy);
        })
    }

    /**
     * 添加信息或者移除信息图标
     * @param customer_id
     * @param flag
     */
    function changeTips(customer_id, flag = false) {
        var span = $('#tab_customer_'+ customer_id+' span');
        if(flag) {
            span.removeClass('message').addClass('tabDel');
        } else {
            span.removeClass('tabDel').addClass('message');
        }
    }

    /**
     * 获取聊天记录倒序显示
     * @param customer_id
     */
    function showAllCustomerRecord(customer_id){
        echoManager.axios(echoManager.getRecord, 'get', {customer_id : customer_id, manager_id:echoManager.manager_id}).then( r => {
            var length = r.length;
            for(let i = length-1; i>= 0; i--) {
                let data = r[i];
                if(data.sender == 'customer') {
                    showCustomerMessage(data);
                } else if(data.sender == 'manager') {
                    var content = data.content;
                    $("#content_customer_"+active_id).append(buildManager(content, data.created_at));
                }
            }
            $("#content_customer_"+active_id).append('<div  class="text-primary text-center customer-chat-content-message-operator serviceChat chatWindow mb10">* * * * 以上是历史消息 * * * *</div>');

            scrollBottom();
        }).catch(error => {

        })
    }

    /**
     * 连接时显示客户聊天记录
     * @param customer_id
     */
    function showCustomerRecord(customer_id){
        var record_id = $('#customer_'+customer_id).attr('record_id');
        echoManager.axios(echoManager.getRecordById, 'get', {record_id : record_id}).then( r => {
            for(let item of r) {
                if(!item.manager_id) {
                    showCustomerMessage(item);
                }
            }
            scrollBottom();
        }).catch(error => {

        })
    }

    /**
     * 展现客户信息
     * @param data
     */
    function showCustomerMessage(data){
        $("#content_customer_"+data.customer_id).append(buildCustomer(data));
    }

    /**
     * 构建客服信息html
     * @param content
     * @returns {string}
     */
    function buildManager(content, flag = false){
        content = replace(content);
        let t;
        if(flag) {
            t = flag;
        } else {
            t = getCurrentTime();
        }
        var html = '<div class="mychatoperator mychat chatWindow" data-id="1">\n' +
            '<div class="myChatCon">' +
            '<div class="mychatTime">'+t+'</div>' +
            '<div class="mychatAuthor">你自己</div>' +
            '<div class="mychatMess">' +
            '<div class="mychatMessDe">'+content+'</div>\n' +
            '</div>' +
            '</div>' +
            '<div class="myChatHead"></div>' +
            '<div class="clear-both"></div>' +
            '</div>';
        return html;
    }

    /**
     * 构建客户信息html
     * @param data
     * @returns {string}
     */
    function buildCustomer(data){
        var li = $('#customer_'+data.customer_id);
        var customer_name = li.attr('customer_name');
        var customer_avatar = li.attr('customer_avatar');
        var content = replace(data.content);
        var time = data.created_at?data.created_at:getCurrentTime();
        var html = '<div class="customer-chat-content-message-operator serviceChat chatWindow">' +
            '<div class="avatar customer-chat-content-message-avatar-operator"><img src="'+customer_avatar+'" alt=""></div>' +
            '<div class="customer-chat-content-message-column">' +
            '<div class="customer-chat-content-message-author">'+customer_name+'</div>' +
            '<div class="customer-chat-content-message-time">'+time+'</div>' +
            '<div class="customer-chat-content-message-body">' +
            '<div class="mychatMessDe">'+content+'</div>' +
            '</div>' +
            '</div>' +
            '<div class="clear-both"></div>' +
            '</div>';
        return html;
    }

    /**
     * 滚动到底部
     */
    function scrollBottom() {
        var win = document.getElementById("content_customer_"+active_id)
        if(win) win.scrollTop = win.scrollHeight;
    }

    /**
     * 表情替换
     * @param str
     * @returns {*}
     */
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

    /**
     * 清空输入框
     */
    function clearInput() {
        $("#customer-chat-message-input").val("");
    }

    /**
     * 获取当前时间
     * @returns {string}
     */
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

    init();
});