$(function(){
    let modal = RPA.config.modal;

    function init(){
        bindEvent();
        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $(modal+" form").validate(validateInfo);
    }
    function bindEvent(){
        //表单提交
        $(modal+' form #save').click(function(){
            add($(this).parents('form'));
        });


        $("#select2-menu").select2({
            "allowClear":true,
            "placeholder":"角色选择",
        });
        $(modal+' form .switch input#type').bootstrapSwitch({onText:"启用", offText:"禁用"});
    }
    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    //提交信息的表单配置
    var FormOptions={
        url:'/admin/sys_role',
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
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };
    init();
});