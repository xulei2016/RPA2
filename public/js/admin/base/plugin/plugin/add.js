$(function(){
    var type = false;
    var time_type = false;
    let modal = RPA.config.modal;
    /**
     * 页面初始化
     */
    function init(){
        bindEvent();
        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $(modal+" form").validate(validateInfo);
    }

    //事件绑定
    function bindEvent(){
        //表单提交
        $(modal+' form #save').click(function(){
            add($(this).parents('form'));
        });

    };


    //添加
    function add(e){
        // serializeForm();
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    //提交信息的表单配置
    var FormOptions={
        url:'/admin/sys_plugin',
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
            name:{
                required:true
            }
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});