$(function(){
    let modal = RPA.config.modal;
    var time_type = $(modal+" form #implement_type ").is(':checked');

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

        //执行时间类型
        $(modal+' form input#implement_type').bootstrapSwitch({"onColor":"info","offColor":"danger",'onText':"回访成功",'offText':"回访失败",onSwitchChange:function(e,state){
            if(!state){
                $(this).parents('div.form-group').next().find('div.row:first').addClass('hidden').siblings().removeClass('hidden');
            }else{
                $(this).parents('div.form-group').next().find('div.row:first').removeClass('hidden').siblings().addClass('hidden');
            }
            time_type = state;
        }});
    }
    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    var id = $(modal+' form #id').val();
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/rpa_cloud_distribution/'+id,
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

        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});