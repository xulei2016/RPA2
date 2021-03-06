/**
 * global.js
 * @name 后台系统全局js
 * @author hus lay
 * @since 2018/2
 * @version 2.0
 */
// const ajaxSetup = require('./Common/AjaxSetup');

var RPA = RPA || {};

RPA.prototype = {
    init: function (obj) {
        //event bind
        this.bind.call(this);
    },
    config: {
        modal: '#modal-lg',
        canMove: '.modal-header.move',
        pjax: {
            container: '#pjax-container', //pjax 容器
            element: 'a:not(a[target="_blank"])', //pjax 监听对象
            _search: 'body nav .search .input-group-append .search-submit',//搜索
            obj: $(document),
            //load model element
            model: $(this.modal + ' .modal-content .modal-body'),
        },
        NProgressParent: '#body', //nprogress 父级作用元素
        adminPopup: $('.navbar .navbar-nav .admin-info-list,.navbar .navbar-nav .admin-message'),
        sidebar: {
            obj: $('body aside .sidebar, .content-wrapper .tags-warp ,body .wrapper #pjax-container .card.usulMenus '),
            activeBar: sessionStorage.activeBar ? sessionStorage.activeBar : '/admin',
        },
        usulMenus:{
            obj: $('body .wrapper #pjax-container .card.usulMenus a'),
        },
        search: {//全局搜索框
            _sidebar: 'body aside .sidebar .nav-item',
            _search: 'body nav .search',
            _search1: $('body nav .search input'),
        },
        tags: {
            obj: $('.wrapper .tags-warp'),
        },
        //drawerPanel
        drawerPanel: {
            obj: $('.drawerPanel-container .drawerPanel .handle-button')
        },
        alerts: $('.alerts .close'),
        windowNotification: !!window.Notification,
        searchBtn: $('#pjax-container #search-group #formSearch #search-btn'),
        bootstrapTable: $('#tb_departments')
    },
    bind: function () {
        var _this = this;
        _this.initList();

        //search
        _this.config.search._search1.bind('keyup', _this.tools.throttle(_this.search, 1000));

        //pjax
        _this.initPage();
        _this.pjaxOperation.init.call(this);

        //modal move
        _this.modalMove(this);

        //is or not scroll
        var screen_operation_obj = $('body .main-header.navbar a[data-toggle="fullscreen"]');
        screen_operation_obj.bind('click', function (e) {
            !_this.screenOperation.isFullscreenForNoScroll() ? _this.screenOperation.requestFullScreen() : _this.screenOperation.exitFull();
        });

        //toastr configure
        toastr.options = _this.toastOptions;

        // //moprogress
        NProgress.configure({parent: '.content-wrapper'});

        //异步请求csrf头
        $.ajaxSetup({
            //X-CSRF-TOKEN
            headers: {'X-CSRF-TOKEN': LA.token}
        });

        //快捷菜单
        _this.config.adminPopup.mouseover(function () {
            $(this).find('.popup').removeClass('hidden');
        }).mouseout(function () {
            $(this).find('.popup').addClass('hidden');
        });

        //侧边栏点击事件
        _this.config.sidebar.obj.on('click', '.nav-item a.nav-link, a', function (e) {
            _this.config.sidebar.activeBar = sessionStorage.activeBar = $(this).attr('href');
            if (!$(this).parents('li').hasClass('active')) {
                $(this).parents('li').siblings('.active').removeClass('active');
                // $(this).parents('li').addClass('active');
            }
            _this.tags.addTags(_this, event);
        });

        //快捷菜单点击事件
        // _this.config.usulMenus.obj.on('click',function(e){
        //     _this.tags.addTags(_this, event);
        // });

        //关闭alerts
        _this.config.alerts.on('click', function () {
            $.get(`/admin/closeAlert/${$(this).data('id')}`);
            $(this).parent().remove();
        });

        //初始化tags
        _this.tags.initTags.call(_this);

        //drawerPanel
        _this.config.drawerPanel.obj.bind('click', function (e) {
            let d = $(this).parents('.drawerPanel-container');
            if (d.hasClass('show')) {
                d.removeClass('show');
            } else {
                d.addClass('show');
            }
        });
    },
    tags: {
        initTags: function (e) {
            let tagsList;
            if (tagsList = localStorage.getItem('tagsList')) {
                let html = '';
                tagsList = JSON.parse(tagsList);
            } else {
                tagsList = {
                    '首页': {
                        type: true,
                        uri: '/admin',
                        title: '首页'
                    }
                };
                localStorage.setItem('tagsList', JSON.stringify(tagsList));
            }
            this.tags.setHtml(this, tagsList);
        },
        addTags: function (_this, e) {
            let obj = $(e.target);
            let href = obj.attr('href') ? obj.attr('href') : obj.parents('a').attr('href');
            let tagsList, html;
            if ('#' != href) {
                let data = {
                    type: true,
                    uri: href,
                    title: obj[0].innerText
                };
                let array = {[obj[0].innerText]: data};
                if (localStorage.getItem('tagsList')) {
                    tagsList = JSON.parse(localStorage.getItem('tagsList'));
                    for (item in tagsList) {
                        tagsList[item].type = false;
                    }
                    if (tagsList[obj[0].innerText.trim()]) {
                        tagsList[obj[0].innerText.trim()].type = true;
                    } else {
                        tagsList[obj[0].innerText.trim()] = data;
                    }
                    array = tagsList;
                }
                ;
                localStorage.setItem('tagsList', JSON.stringify(array));
                _this.tags.setHtml(_this, tagsList);
            }
        },
        delTags: function (e) {
            e.preventDefault();
            e.returnValue = false;
            let _this = $(e.target);
            let uri, obj;
            if (localStorage.getItem('tagsList')) {
                tagsList = JSON.parse(localStorage.getItem('tagsList'));
                if (tagsList[_this.parents('a').text().trim()].type) {
                    delete tagsList[_this.parent().text().trim()];
                    for (i in tagsList) {
                        obj = i;
                    }
                    tagsList[obj].type = true;
                    uri = tagsList[obj].uri;
                    $.pjax.reload(RPA.config.pjax.container, {url: uri})
                } else {
                    delete tagsList[_this.parent().text().trim()];
                }
            }
            RPA.tags.setHtml(RPA, tagsList);
            localStorage.setItem('tagsList', JSON.stringify(tagsList));
        },
        setHtml: function (_this, tagsList) {
            let html = '';
            for (item in tagsList) {
                if (tagsList[item].type) {
                    html += ("首页" == item) ? `<a href="${tagsList[item].uri}"><span class="tags-item active">${tagsList[item].title} </span></a>` :
                        `<a href="${tagsList[item].uri}"><span class="tags-item active">${tagsList[item].title} <span class="fa fa-remove tags-close" onclick="RPA.tags.delTags(event);"></span></span></a>`;
                } else {
                    html += ("首页" == item) ? `<a href="${tagsList[item].uri}"><span class="tags-item">${tagsList[item].title} </span></a>` :
                        `<a href="${tagsList[item].uri}"><span class="tags-item">${tagsList[item].title} <span class="fa fa-remove tags-close" onclick="RPA.tags.delTags(event);"></span></span></a>`;
                }
            }
            _this.config.tags.obj.html(html);
        }
    },
    initTags: function (e) {
        let obj = $(e.target);
        let href = obj.attr('href') ? obj.attr('href') : obj.parents('a').attr('href');
        let tagsList, html;
        if ('#' != href) {
            let data = {
                type: true,
                uri: href,
                title: obj[0].innerText
            };
            let array = {[obj[0].innerText]: data};
            if (tagsList = localStorage.getItem('tagsList')) {
                tagsList = JSON.parse(tagsList);
                for (item in tagsList) {
                    tagsList[item].type = false;
                }
                if (tagsList[obj[0].innerText]) {
                    tagsList[obj[0].innerText].type = true;
                    return;
                }
                tagsList[obj[0].innerText] = data;
                array = tagsList;
            }
            ;
            localStorage.setItem('tagsList', JSON.stringify(array));
            html = `<a href="${data.uri}"><span class="tags-item active">${data.title} <span class="fa fa-remove tags-close" onclick="RPA.delTags(event);"></span></span></a>`;

            this.config.tags.obj.find('.active').removeClass('active');
            this.config.tags.obj.append(html);
        }
    },
    screenOperation: {
        requestFullScreen: function () {
            element = document.documentElement;
            // 判断各种浏览器，找到正确的方法
            var requestMethod = element.requestFullScreen || //W3C
                element.webkitRequestFullScreen || //Chrome等
                element.mozRequestFullScreen || //FireFox
                element.msRequestFullScreen; //IE11
            if (requestMethod) {
                requestMethod.call(element);
            } else if (typeof window.ActiveXObject !== "undefined") { //for Internet Explorer
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
        },
        exitFull: function () {
            // 判断各种浏览器，找到正确的方法
            var exitMethod = document.exitFullscreen || //W3C
                document.mozCancelFullScreen || //Chrome等
                document.webkitExitFullscreen || //FireFox
                document.webkitExitFullscreen; //IE11
            if (exitMethod) {
                exitMethod.call(document);
            } else if (typeof window.ActiveXObject !== "undefined") { //for Internet Explorer
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
        },
        isFullscreenForNoScroll: function () {
            return document.isFullScreen || document.mozIsFullScreen || document.webkitIsFullScreen
        }
    },
    toastOptions: {
        "closeButton": true, //是否显示关闭按钮
        "debug": false, //是否使用debug模式
        "positionClass": "toast-top-right", //弹出窗的位置
        "showDuration": "300", //显示的动画时间
        "hideDuration": "1000", //消失的动画时间
        "timeOut": "4000", //展现时间
        "extendedTimeOut": "1000", //加长展示时间
        "showEasing": "swing", //显示时的动画缓冲方式
        "hideEasing": "linear", //消失时的动画缓冲方式
        "showMethod": "fadeIn", //显示时的动画方式
        "hideMethod": "fadeOut", //消失时的动画方式
        "progressBar": false,
    },
    pjaxOperation: {
        init: function () {
            var e = this.config.pjax;
            var s = this.config.search._search;
            e.obj.pjax(e.element, e.container);
            e.obj.on({
                'pjax:timeout': function (event) {
                    event.preventDefault();
                }
            });

            $(document).on('click', e._search, function (event) {
                let v = $(`${s} input`).val();
                let url = $(`${s} datalist option[value="${v}"]`).data('href');
                $.pjax({url: url, container: e.container});
            });

            $(document).on("pjax:popstate", function () {

                $(document).one("pjax:end", function (event) {
                    $(event.target).find("script[data-exec-on-popstate]").each(function () {
                        $.globalEval(this.text || this.textContent || this.innerHTML || '');
                    });
                });
            });

            $(document).on('pjax:send', function (xhr) {
                if (xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
                    $submit_btn = $('form[pjax-container] :submit');
                    if ($submit_btn) {
                        $submit_btn.button('loading')
                    }
                }
                NProgress.start();
            });

            $(document).on('pjax:complete', function (xhr) {
                if (xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
                    $submit_btn = $('form[pjax-container] :submit');
                    if ($submit_btn) {
                        $submit_btn.button('reset')
                    }
                }
                NProgress.done();
            });
        },
        modelLoad: function operation(_this) {
            let e = RPA.config.modal;
            let url = _this.attr('url');
            $(e + ' .modal-content').text('').load(url);
            $(e).modal('show');
        }
    },
    modalMove: _this => {
        let mouseStartPoint = {"left": 0, "top": 0};
        let mouseEndPoint = {"left": 0, "top": 0};
        let mouseDragDown = false;
        let oldP = {"left": 0, "top": 0};
        let moveTarget;
        $(document).ready(function () {
            $(document).on("mousedown", _this.config.canMove, function (e) {
                if ($(e.target).hasClass("close"))//点关闭按钮不能移动对话框
                    return;
                mouseDragDown = true;
                moveTarget = $(this).parent();
                mouseStartPoint = {"left": e.clientX, "top": e.clientY};
                oldP = moveTarget.offset();
            });
            $(document).on("mouseup", function (e) {
                mouseDragDown = false;
                moveTarget = undefined;
                mouseStartPoint = {"left": 0, "top": 0};
                oldP = {"left": 0, "top": 0};
            });
            $(document).on("mousemove", function (e) {
                if (!mouseDragDown || moveTarget === undefined) return;
                let mousX = e.clientX;
                let mousY = e.clientY;
                if (mousX < 0) mousX = 0;
                if (mousY < 0) mousY = 25;
                mouseEndPoint = {"left": mousX, "top": mousY};
                const width = moveTarget.width();
                const height = moveTarget.height();
                mouseEndPoint.left = mouseEndPoint.left - (mouseStartPoint.left - oldP.left);//移动修正，更平滑
                mouseEndPoint.top = mouseEndPoint.top - (mouseStartPoint.top - oldP.top);
                moveTarget.offset(mouseEndPoint);
            });
        });
    },
    initPage: function () {
        selectedMenu = RPA.config.sidebar.activeBar;
        selectedMenu = selectedMenu == '#' ? '/admin' : selectedMenu;
        //菜单显示
        var selector = $('.sidebar').find('a[href="' + selectedMenu + '"]');
        // selector.addClass('active');
        selector.parents('li.has-treeview').addClass('menu-open');
    },
    initList: function (e) {   //缓存菜单
        let sidebarList = localStorage.getItem("sidebarList");
        if (!sidebarList) {
            var data = [];
            $(this.config.search._sidebar).each(function (index) {
                if (!$(this).hasClass('has-treeview')) {
                    let i = [
                        $(this).find('a').attr('href'),
                        $(this).find('p').text()
                    ];
                    data.push(i);
                }
            });
            sidebarList = data;
            localStorage.setItem("sidebarList", JSON.stringify(data));
        }
        return sidebarList;
    },
    ///////////////////////////////////////////////////////// tools func /////////////////////////////////////////////////////////////
    tools: {
        throttle: function (fn, delay) {
            let canRun = true;
            return function () {
                if (!canRun) return;
                canRun = false;
                setTimeout(() => {
                    fn.apply(this, arguments);
                    canRun = true;
                }, delay);
            }
        }
    },

    ///////////////////////////////////////////////////////// form start///////////////////////////////////////////////////////////

    form: {
        reset: function (e, callback) {
            //重置复选框
            let formContinue = $(RPA.config.modal + ' input.icheck').each(function (e) {
                $(this).iCheck('uncheck');
            });
            $(e)[0].reset();//重置表单，必须放下面
        },
        response: function (callback) {
            let obj = RPA.config.modal;
            toastr.success('操作成功！');
            // $.pjax.reload('#pjax-container');
            $('#tb_departments').bootstrapTable('refresh');
            if ($(obj + ' #form-continue').length > 0) {
                $(obj + ' #form-continue').is(':checked') ? RPA.form.reset(obj + ' #form') : $(obj).modal('hide');
                ;
            }
            callback ? callback() : '';
        },
        ajaxSubmit: function (e, FormOptions) {
            e.ajaxSubmit($.extend(true, {}, {
                beforeSubmit: this.formValidation,
                type: 'post',
                dataType: 'json',
                clearForm: false,
                resetForm: false
            }, FormOptions));
        },
        formValidation: function (arr, $form, options) {
            // 如果JQuery.Validate检测不通过则返回false
            if (!$form.valid()) {
                return false;
            }
            for (var i = 0; i < arr.length; i++) {
                //去除前后空格
                if (arr[i].type != 'file') {
                    arr[i].value = $.trim(arr[i].value);
                }
            }
        },
        errorResponse: function errorResponse(XMLHttpRequest, textStatus, errorThrown) {
            toastr.success('网络异常，请求失败！');
        },
    },
    clearCache: function () {
        $.post('/admin/clearCache', function (json) {
            200 == json.code ? toastr.success('清除成功！') : toastr.error('网络异常，请求失败！');
            localStorage.removeItem('sidebarList');
        });
    },

    /////////////////////////////////////////////////////////bootstrap table start///////////////////////////////////////////////////////////
    TableInit: function () {
        var oTableInit = new Object();
        //初始化Table
        oTableInit.Init = function (selector, param) {
            $(selector).bootstrapTable({
                url: param.url,         //请求后台的URL（*）
                method: 'get',                      //请求方式（*）
                toolbar: '#toolbar',                //工具按钮用哪个容器
                striped: true,                      //是否显示行间隔色
                cache: false,                       //是否使用缓存，默认为true，所以一般情况下需要设置一下这个属性（*）
                pagination: true,                   //是否显示分页（*）
                sortable: true,                     //是否启用排序
                silentSort: false,
                sortStable: true,
                sortOrder: param.sortOrder ? param.sortOrder : 'desc',                   //排序方式
                queryParams: oTableInit.queryParams,//传递参数（*）
                sidePagination: "server",           //分页方式：client客户端分页，server服务端分页（*）
                pageNumber: 1,                       //初始化加载第一页，默认第一页
                pageSize: 10,                       //每页的记录行数（*）
                pageList: [10, 25, 50, 100],        //可供选择的每页的行数（*）
                search: false,                       //是否显示表格搜索，此搜索是客户端搜索，不会进服务端，所以，个人感觉意义不大
                strictSearch: true,
                showColumns: true,                  //是否显示所有的列
                showRefresh: true,                  //是否显示刷新按钮
                minimumCountColumns: 1,             //最少允许的列数
                clickToSelect: true,                //是否启用点击选中行
                // height: 800,                     //行高，如果没有设置height属性，表格自动根据记录条数觉得表格高度
                uniqueId: "id",                     //每一行的唯一标识，一般为主键列
                showToggle: false,                    //是否显示详细视图和列表视图的切换按钮
                cardView: false,                    //是否显示详细视图
                detailView: param.detailView,                   //是否显示父子表
                showFullscreen: false,               //全屏显示,无效勿用
                maintainSelected: true,             //将记住checkbox的选择项

                columns: param.columns,
                responseHandler: function (res) {
                    res.rows = res.data;
                    return res;
                },
                onDblClickRow: function (row, $element) {
                    var id = row.ID;
                    // EditViewById(id, 'view');
                },
                onExpandRow: function (index, row, $detail) {
                    param.onExpandRow(index, row, $detail);
                },
                onLoadSuccess: function (data) {
                    if (param.hasOwnProperty("onLoadSuccess")) {
                        param.onLoadSuccess(data);
                    }
                }
                // onPostBody: function () {
                //     //改变复选框样式
                //     $(selector).find("input:checkbox").each(function (i) {
                //         var $check = $(this);
                //         if ($check.attr("id") && $check.next("label")) {
                //             return;
                //         }
                //         var name = $check.attr("name");
                //         var id = name + "-" + i;
                //         var $label = $('<label for="'+ id +'"></label>');
                //         $check.attr("id", id).parent().addClass("bella-checkbox").append($label);
                //     });
                //     if ($.isFunction(param.onPostBody)) {
                //         param.onPostBody();
                //     }
                // },
            });
        };

        return oTableInit;
    },
    /////////////////////////////////////////////////////////bootstrap table end//////////////////////////////////////////////////////////


    getIdSelections: function (table) {
        return $.map($(table).bootstrapTable('getSelections'), function (row) {
            return row.id
        });
    },
    Echo: {
        init: function (model) {
            let _this = this;
            //消息通知laravel-echo
            if (window.hasOwnProperty('Echo')) {
                window.Echo.private(model).notification(function (obj) {
                    switch (obj.notifi_type) {
                        case 'message':
                            _this.content(obj);
                            break;
                        case 'event':
                            _this.event(obj);
                            break;
                    }
                });
                return;
            }
            console.log('未启用即时消息服务，请联系管理员开启！');
        },
        content: function (obj) {
            let typeName = "";
            if (1 === obj.typeName) {
                typeName = "系统公告";
            } else if (2 === obj.typeName) {
                typeName = "RPA通知";
            } else if (3 === obj.typeName) {
                typeName = "管理员通知";
            } else {
                typeName = "RPA流程通知";
            }
            let html = "";
            html += '<div class="notify-wrap">'
                + '<div class="notify-title">' + typeName + '<span class="notify-off"><i class="fa fa-envelope-o"></i></span></div>'
                + '<div class="notify-title"><a href="JavaScript:void(0);" url="/admin/sys_message_list/view/' + obj.id + '" onclick="operation($(this));" title="查看站内信息">' + obj.title + '</a><div>'
                + '<div class="notify-content">' + obj.content + '</div>'
                + '</div>';

            $("body").append(html);

            //播放消息提醒音乐
            let au = document.createElement("audio");
            au.preload = "auto";
            au.src = "/common/voice/qipao.mp3";
            au.play();

            //更新右上角
            if ($("#notification_count span").length > 0) {
                let count = $("#notification_count span").text();
                $('#notification_count span').text(1 + parseInt(count));
                let html1 = '<li><a href="javascript:void(0);" onclick="operation($(this));" url="/admin/sys_message_list/view/' + obj.id + '"><i class="fa fa-users text-aqua"></i>' + obj.title + '</a></li>';
                $("#notification_list").prepend(html1);
            } else {
                $("#notification_count").append('<span class="badge badge-warning navbar-badge">1</span>');
                let html1 = '<ul class="menu" id="notification_list"><li><a href="javascript:;" onclick="operation($(this));" url="/admin/sys_message_list/view/' + obj.id + '"><i class="fa fa-users text-aqua"></i>' + obj.title + '</a></li></ul>'
                $('.notifications-menu').html(html1);
            }

            let lastNotify = $(".notify-wrap:last");
            lastNotify.delay(2000).slideDown(1000);
            setTimeout(function () {
                lastNotify.slideUp(1000);
            }, 8000);

            //桌面通知
            RPA.config.windowNotification && RPA.Notify.popNotice(typeName, obj.content);
        },
        event: obj => {
            switch (obj.event_type) {
                case 'single_login':
                    keepAlive();
                    break;
            }

            function keepAlive() {
                $.get('/admin/keepAlive', function (json) {
                    if (200 !== json.code && 'fail' === json.info) {
                        //登出
                        $('#modal-sm .modal-content').text('').load('/admin/singleOut');
                        $('#modal-sm').modal('show');
                    }
                });
            }
        }
    },
    Notify: {
        popNotice: (title, content) => {
            let notification = new Notification(`${title}:`, {
                body: content,
                icon: "https://rpa.haqh.com:8088/common/images/logo.png"
            });
            notification.onclick = function () {
                window.open('https://rpa.haqh.com:8088/admin');
                notification.close()
            }
        },
    },
    Alert: {
        howSearch: () => {
            Swal.fire({
                type: 'info',
                title: '<strong>模糊查询说明</strong>',
                html: '<ul><li>&#91;&#93;&#92;&#92;&#94;&#36;&#46;&#124;&#63;&#42;&#43;&#40;&#41;:关键字可以是js正则元字符</li><li>&#123;&#125;&#60;&#62;&#39;&#92;&#34;&#126;&#96;&#33;&#64;&#35;&#37;&#38;&#45;&#59;&#58;&#47;&#44;&#61;:关键字可以是其他字符</li><li>关键字查找不区分大小写</li><li>空 格:关键字是空格</li></ul>'
            });
        }
    },
    search: function (e, t) {
        let sidebarList = localStorage.getItem("sidebarList");
        if (sidebarList) {
            let options = '';
            let value = e.target.value;
            console.log(value);
            sidebarList = JSON.parse(sidebarList);
            sidebarList = sidebarList.filter(function (v, i, e) {
                return (v[0].indexOf(value) || v[1].indexOf(value)) ? true : false;
            });
            sidebarList.forEach(function (e) {
                options += `<option value="${e[1]}" data-href="${e[0]}">${e[0]}</option>`;
            });
            $(this).next().html(options);
        }
    }
};

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


RPA = RPA.prototype;
RPA.init(window);

let operation = (e) => {
    RPA.pjaxOperation.modelLoad(e);
};

//socket
if (socket.userId) {
    RPA.Echo.init(`App.Models.Admin.Admin.SysAdmin.${socket.userId}`);
}
;

//自定义函数处理queryParams的批量增加
$.fn.serializeJsonObject = function () {
    let json = {};
    let form = this.serializeArray();
    $.each(form, function () {
        if (json[this.name]) {
            if (!json[this.name].push) {
                json[this.name] = [json[this.name]];
            }
            json[this.name].push();
        } else {
            json[this.name] = this.value || '';
        }
    });
    return json;
};

/**
 * param 将要转为URL参数字符串的对象
 * key URL参数字符串的前缀
 * encode true/false 是否进行URL编码,默认为true
 *
 * return URL参数字符串
 */
const urlEncode = function (param, key, encode) {
    if (param == null) return '';
    let paramStr = '';
    let t = typeof (param);
    if (t === 'string' || t === 'number' || t === 'boolean') {
        paramStr += '&' + key + '=' + ((encode == null || encode) ? encodeURIComponent(param) : param);
    } else {
        for (let i in param) {
            let k = key == null ? i : key + (param instanceof Array ? '[' + i + ']' : '.' + i);
            paramStr += urlEncode(param[i], k, encode);
        }
    }
    return paramStr;
    // return paramStr.slice(1);
};

//datetime
function getFormatDate() {
    let nowDate = new Date();
    let year = nowDate.getFullYear();
    let month = nowDate.getMonth() + 1 < 10 ? "0" + (nowDate.getMonth() + 1) : nowDate.getMonth() + 1;
    let date = nowDate.getDate() < 10 ? "0" + nowDate.getDate() : nowDate.getDate();
    let hour = nowDate.getHours() < 10 ? "0" + nowDate.getHours() : nowDate.getHours();
    let minute = nowDate.getMinutes() < 10 ? "0" + nowDate.getMinutes() : nowDate.getMinutes();
    let second = nowDate.getSeconds() < 10 ? "0" + nowDate.getSeconds() : nowDate.getSeconds();
    return year + "-" + month + "-" + date + " " + hour + ":" + minute + ":" + second;
};
