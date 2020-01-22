$(function () {
    let modal = RPA.config.modal;
    let media = $('#audio');
    let id = $(modal + ' form #id').val();

    /**
     * 页面初始化
     */
    function init() {
        bindEvent();

        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $(modal + " form").validate(validateInfo);

        const xhr = new XMLHttpRequest();
        xhr.open('get', `./rpa_customer_revisit/getAudio/${id}/`, true);
        // 请求类型 bufffer
        xhr.responseType = 'arraybuffer';
        xhr.onload = function () {
            if (xhr.status === 200 || xhr.status === 304) {
                let blob = new Blob([xhr.response], {type: 'audio/*'});
                let url = URL.createObjectURL(blob);
                if (typeof (url) == 'undefined') url = "";
                $('#audio')[0].src = url;
            }
        };
        xhr.send();
    }

    //事件绑定
    function bindEvent() {
        //表单提交
        $(modal + ' form #save').click(function () {
            add($(this).parents('form'));
        });

        //执行时间类型
        $(modal + ' form input#implement_type').bootstrapSwitch({
            "onColor": "info",
            "offColor": "danger",
            'onText': "回访成功",
            'offText': "回访失败",
            onSwitchChange: function (e, state) {
                if (!state) {
                    $(this).parents('div.form-group').next().find('div.row:first').addClass('hidden').siblings().removeClass('hidden');
                } else {
                    $(this).parents('div.form-group').next().find('div.row:first').removeClass('hidden').siblings().addClass('hidden');
                }
                time_type = state;
            }
        });
    }

    //添加
    function add(e) {
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    //提交信息的表单配置
    let FormOptions = {
        url: '/admin/rpa_customer_revisit/' + id,
        success: function (json, xml) {
            if (200 == json.code) {
                RPA.form.response();
            } else {
                toastr.error(json.info);
            }
        },
        error: RPA.form.errorReponse
    };

    //表单验证信息
    let validateInfo = {
        rules: {},
        errorPlacement: function (error, element) {
            element.parent().append(error);
        }
    };

    init();
});