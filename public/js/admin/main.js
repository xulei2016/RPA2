/**
 * global.js
 * @name 后台系统全局js
 * @author hus lay
 * @since 2018/2
 * @version 2.0
 */
var RPA = RPA || {};
RPA.prototype = {
    init: function(obj){
        //event bind
        this.bind.call(this);
    },
    config: {
        'pjax': {
            container : '#pjax-container', //pjax 容器
            element: 'a:not(a[target="_blank"])', //pjax 监听对象
            obj: $('body .wrapper aside .sidebar'),
        },
        'NProgress-parent': '#pjax-container', //nprogress 父级作用元素
    },
    bind: function(){
        var _this = this;
        //pjax
        _this.initPage();
        _this.pjaxOperation();

        //is or not scroll
        var screen_operation_obj = $('body .wrapper .main-header .navbar .navbar-custom-menu a[data-toggle="fullscreen"]');
        screen_operation_obj.bind('click', function(e){
            !_this.screenOperation.isFullscreenForNoScroll() ? _this.screenOperation.requestFullScreen() : _this.screenOperation.exitFull();
        });

        //toastr configure
        toastr.options = _this.toastOptions;

        //moprogress
        NProgress.configure({ parent: '#pjax-container' });
    },
    screenOperation : {
        requestFullScreen: function(){
            element = document.documentElement;
            // 判断各种浏览器，找到正确的方法
            var requestMethod = element.requestFullScreen || //W3C
            element.webkitRequestFullScreen ||    //Chrome等
            element.mozRequestFullScreen || //FireFox
            element.msRequestFullScreen; //IE11
            if (requestMethod) {
                requestMethod.call(element);
            } else if (typeof window.ActiveXObject !== "undefined") {//for Internet Explorer
                var wscript = new ActiveXObject("WScript.Shell");
                if (wscript !== null) {
                    wscript.SendKeys("{F11}");
                }
            }
        },
        exitFull: function(){
            // 判断各种浏览器，找到正确的方法
            var exitMethod = document.exitFullscreen || //W3C
            document.mozCancelFullScreen ||    //Chrome等
            document.webkitExitFullscreen || //FireFox
            document.webkitExitFullscreen; //IE11
            if (exitMethod) {
                exitMethod.call(document);
            } else if (typeof window.ActiveXObject !== "undefined") {//for Internet Explorer
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
        "positionClass": "toast-top-right",//弹出窗的位置
        "showDuration": "300",//显示的动画时间
        "hideDuration": "1000",//消失的动画时间
        "timeOut": "4000", //展现时间
        "extendedTimeOut": "1000",//加长展示时间
        "showEasing": "swing",//显示时的动画缓冲方式
        "hideEasing": "linear",//消失时的动画缓冲方式
        "showMethod": "fadeIn",//显示时的动画方式
        "hideMethod": "fadeOut", //消失时的动画方式
        "progressBar": true,  
    },
    pjaxOperation: function (){
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
    initPage: function(){
        selectedMenu = 'admin/admin';
        //整页刷新时，菜单显示
        var selector = $('.sidebar-menu').find('a[href="/' + selectedMenu + '"]');
        selector.parent().addClass('active');
        selector.parents('ul.treeview-menu').css('display', 'block');
        selector.parents('li.treeview').addClass('menu-open');
    }
}

var RPA = RPA.prototype;
RPA.init(window);