/**
 * global.js
 * @name 后台系统全局js
 * @author hus lay
 * @since 2018/2
 * @version 2.0
 */
var RPA = RPA || {};
RPA.prototype = {
    init: function(obj) {
        //event bind
        this.bind.call(this);
    },
    config: {
        'pjax': {
            container: '#pjax-container', //pjax 容器
            element: 'a:not(a[target="_blank"])', //pjax 监听对象
            // obj: $('body .wrapper aside .sidebar'),
            obj: $(document),
            //load model element
            model: $('#Modal .modal-content .modal-body'),
        },
        'NProgress-parent': '#pjax-container', //nprogress 父级作用元素
    },
    bind: function() {
        var _this = this;
        //pjax
        _this.initPage();
        _this.pjaxOperation.init.call(this);

        //is or not scroll
        var screen_operation_obj = $('body .wrapper .main-header .navbar .navbar-custom-menu a[data-toggle="fullscreen"]');
        screen_operation_obj.bind('click', function(e) {
            !_this.screenOperation.isFullscreenForNoScroll() ? _this.screenOperation.requestFullScreen() : _this.screenOperation.exitFull();
        });

        //toastr configure
        toastr.options = _this.toastOptions;

        //moprogress
        NProgress.configure({ parent: '#pjax-container' });

        //异步请求csrf头
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': LA.token }
        });
    },
    screenOperation: {
        requestFullScreen: function() {
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
        exitFull: function() {
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
        isFullscreenForNoScroll: function() {
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
        "progressBar": true,
    },
    pjaxOperation: {
        init: function() {
            var e = this.config.pjax;
            e.obj.pjax(e.element, e.container);
            e.obj.on({
                'pjax:timeout': function(event) {
                    event.preventDefault();
                }
            });

            $(document).on('submit', 'form[pjax-container]', function(event) {
                $.pjax.submit(event, '#pjax-container')
            });

            $(document).on("pjax:popstate", function() {

                $(document).one("pjax:end", function(event) {
                    $(event.target).find("script[data-exec-on-popstate]").each(function() {
                        $.globalEval(this.text || this.textContent || this.innerHTML || '');
                    });
                });
            });

            $(document).on('pjax:send', function(xhr) {
                if (xhr.relatedTarget && xhr.relatedTarget.tagName && xhr.relatedTarget.tagName.toLowerCase() === 'form') {
                    $submit_btn = $('form[pjax-container] :submit');
                    if ($submit_btn) {
                        $submit_btn.button('loading')
                    }
                }
                NProgress.start();
            });

            $(document).on('pjax:complete', function(xhr) {
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
            let url = _this.attr('url');
            $('#modal .modal-content').text('').load(url);
            $('#modal').modal('show');
        }
    },
    initPage: function() {
        selectedMenu = 'admin/admin';
        //整页刷新时，菜单显示
        var selector = $('.sidebar-menu').find('a[href="/' + selectedMenu + '"]');
        selector.parent().addClass('active');
        selector.parents('ul.treeview-menu').css('display', 'block');
        selector.parents('li.treeview').addClass('menu-open');
    },
    ajaxSubmit: function(e, FormOptions) {
        e.ajaxSubmit($.extend(true, {}, this.formOptions, FormOptions));
    },
    formValidation: function(arr, $form, options) {
        for (var i = 0; i < arr.length; i++) {
            //去除前后空格
            if (arr[i].type != 'file') {
                arr[i].value = $.trim(arr[i].value)+'0000000';
            }
        }
    },
    formOptions: {
        beforeSubmit: this.formValidation,
        type: 'post',
        dataType: 'json',
        clearForm: false, // clear all form fields after successful submit
        resetForm: false,
    },
    errorResponse: function errorResponse(XMLHttpRequest, textStatus, errorThrown) {
        toastr.success('网络异常，请求失败！');
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
                sortOrder: "asc",                   //排序方式
                queryParams: oTableInit.queryParams,//传递参数（*）
                sidePagination: "server",           //分页方式：client客户端分页，server服务端分页（*）
                pageNumber:1,                       //初始化加载第一页，默认第一页
                pageSize: 10,                       //每页的记录行数（*）
                pageList: [10, 25, 50, 100],        //可供选择的每页的行数（*）
                search: false,                       //是否显示表格搜索，此搜索是客户端搜索，不会进服务端，所以，个人感觉意义不大
                strictSearch: true,
                showColumns: true,                  //是否显示所有的列
                showRefresh: true,                  //是否显示刷新按钮
                minimumCountColumns: 1,             //最少允许的列数
                clickToSelect: true,                //是否启用点击选中行
                // height: 800,                     //行高，如果没有设置height属性，表格自动根据记录条数觉得表格高度
                uniqueId: "ID",                     //每一行的唯一标识，一般为主键列
                showToggle:false,                    //是否显示详细视图和列表视图的切换按钮
                cardView: false,                    //是否显示详细视图
                detailView: false,                   //是否显示父子表
                showFullscreen: false,               //全屏显示,无效勿用
                maintainSelected: true,             //将记住checkbox的选择项
                // classes: 'table-no-bordered',      //无边框
    
                showExport: true,                   //是否显示导出按钮
                buttonsAlign:"right",               //按钮位置
                exportTypes:['csv', 'txt', 'sql', 'doc', 'excel', 'xlsx', 'pdf'],              //导出文件类型
                Icons:'glyphicon-export icon-share',
                exportDataType: ['basic','all','selected'],
                exportOptions:{
                    ignoreColumn: [0,1],            //忽略某一列的索引
                    fileName: '用户表',          //文件名称设置
                    worksheetName: 'sheet1',        //表格工作区名称
                    tableName: '用户表',
                    excelstyles: ['background-color', 'color', 'font-size', 'font-weight'],
                },
                columns: param.columns,
                responseHandler: function(res){
                    res.rows = res.data;
                    return res;
                },
                onDblClickRow: function (row, $element) {
                    var id = row.ID;
                    // EditViewById(id, 'view');
                },
                onPostBody: function () {
                    //改变复选框样式
                    $(selector).find("input:checkbox").each(function (i) {
                        var $check = $(this);
                        if ($check.attr("id") && $check.next("label")) {
                            return;
                        }
                        var name = $check.attr("name");
                        var id = name + "-" + i;
                        var $label = $('<label for="'+ id +'"></label>');
                        $check.attr("id", id).parent().addClass("bella-checkbox").append($label);
                    });
                    if ($.isFunction(param.onPostBody)) {
                        param.onPostBody();
                    }
                }
            });
        };
    
        return oTableInit;
    },
    /////////////////////////////////////////////////////////bootstrap table end//////////////////////////////////////////////////////////
}

var RPA = RPA.prototype;
RPA.init(window);

var operation = (e) => {
    RPA.pjaxOperation.modelLoad(e);
}

//自定义函数处理queryParams的批量增加
$.fn.serializeJsonObject = function () {
    var json = {};
    var form = this.serializeArray();
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
}
