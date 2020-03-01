$(function () {
    let modal = RPA.config.modal;

    let choose_label = {};

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

        $(modal + ' form .available_list input').on('click', function(){
            let v = $(this).val();
            if($(this).is(':checked')){
                choose_label[v] = v;
                $(modal + ' form .show_available_list').append(` <span class="x-tag x-tag-sm x-tag-success ${v}">${v}</span> `);
            }else{
                delete choose_label[v];
                $(modal + ` form .show_available_list span.${v}`).remove();
            }
        });
    }

    //添加
    function add(e) {
        $(modal + ' form #available_list').val(JSON.stringify(choose_label));
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    //提交信息的表单配置
    var FormOptions = {
        url: '/admin/sys_sms',
        success: function (json, xml) {
            if ('200' === json.code) {
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