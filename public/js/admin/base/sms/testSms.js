$(function () {
    let modal = RPA.config.modal;
    let jsonText = '';
    let isString = false;

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
    }

    //添加
    function add(e) {
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    //格式化处理
    function jsonFormat(jsonTemp) {
        let json = '';
        try {
            // stringify 时需指定缩进否则不会显示换行。为了防止传入的string没有指定 在此统一执行一遍
            if (typeof jsonTemp != 'string') {
                json = JSON.stringify(jsonTemp, undefined, 2);
            } else {
                json = JSON.stringify(JSON.parse(jsonTemp), undefined, 2)
            }
            let jsonObj = JSON.parse(json);
            if (typeof jsonObj === 'object') {
                isString = false;
                json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, match => {
                    let cls = 'number';
                    if (/^"/.test(match)) {
                        if (/:$/.test(match)) {
                            cls = 'key';
                        } else {
                            cls = 'string';
                        }
                    } else if (/true|false/.test(match)) {
                        cls = 'boolean';
                    } else if (/null/.test(match)) {
                        cls = 'null';
                    }
                    return '<span class="' + cls + '">' + match + '</span>';
                });
            } else {
                isString = true;
                return jsonTemp
            }
        } catch (e) {
            isString = true;
            return jsonTemp
        }
    }

    //提交信息的表单配置
    var FormOptions = {
        url: '/admin/sys_sms/testSms',
        success: function (jsonData, xml) {
            if (!jsonData) {
                jsonText = '';
                isString = false
            } else {
                jsonText = jsonFormat(jsonData)
            }
            $(modal + ' #form #return').html(jsonText);
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