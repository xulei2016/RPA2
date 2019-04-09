$(function(){
    var type = false;

    /**
     * 页面初始化
     */
    function init(){
        bindEvent();

        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $("#modal form").validate(validateInfo);
    }
    
    //事件绑定
    function bindEvent(){
        //定义时间按钮事件
        //表单提交
        $('#modal form #save').click(function(){
            add($(this).parents('form'));
        });
    }

    //序列化
    function serializeForm(){
        let jsondata = {};
        $('#modal form .target_web .row').each(function(){
            jsondata.model_kind = $(this).find("input[name='jsondate']:checked").val().trim();
        });
        $('#modal form #jsondata').val(JSON.stringify(jsondata));
    }
    
    //添加
    function add(e){
        serializeForm();
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    //提交信息的表单配置
    var FormOptions={
        url:'/admin/rpa_SettlementFee/insertImmedtasks',
        success:function(json, xml){
            if(200 == json.code){
                RPA.form.response();
            }else{
                toastr.error(json.info);
            }
        },
        error:RPA.form.errorReponse
    };
    
    //表单验证信息
    var validateInfo ={
        rules:{
            name:{//名称
                required:true
            },
            jsondate:{
                required:true
            }
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});