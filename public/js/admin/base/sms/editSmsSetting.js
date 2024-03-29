$(function () {
    let modal = RPA.config.modal;

    let setting_container = document.getElementById('setting');
    let setting_editor = new JSONEditor(setting_container, {})

    let return_code_container = document.getElementById('return_code');
    let return_code_editor = new JSONEditor(return_code_container, {})

    // set json
    let setting_json = $('.modal input[name="setting"]').val();
    let return_code_json = $('.modal input[name="return_code"]').val();
    setting_editor.set(setting_json ? JSON.parse(setting_json) : {});
    return_code_editor.set(setting_json ? JSON.parse(return_code_json) : {});


    function init() {
        bindEvent();

        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $(modal + " form").validate(validateInfo);

    }

    /**
     * bindEvent
     */
    function bindEvent() {
        //表单提交
        $(modal + ' form #save').click(function () {
            add($(this).parents('form'));
        });

        $(modal + ' form input.switch ').bootstrapSwitch({onText: "启用", offText: "禁用"});
    }

    //添加
    function add(e) {
        //json赋值
        $('.modal input[name="setting"]').val(setting_editor.get() ? JSON.stringify(setting_editor.get()) : '');
        $('.modal input[name="return_code"]').val(return_code_editor.get() ? JSON.stringify(return_code_editor.get()) : '');

        RPA.form.ajaxSubmit(e, FormOptions);
    }

    let id = $('#form #id').val();

    //提交信息的表单配置
    var FormOptions = {
        url: '/admin/sys_sms/updateSmsSetting/' + id,
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
    var validateInfo = {
        rules: {
            name: {//名称
                required: true
            },
        },
        errorPlacement: function (error, element) {
            element.parent().append(error);
        }
    };
    init();
});