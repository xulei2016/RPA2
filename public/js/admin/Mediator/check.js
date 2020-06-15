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
        $(modal+' form input#implement_type').bootstrapSwitch({"onColor":"info","offColor":"danger",'onText':"通过",'offText':"打回",onSwitchChange:function(e,state){
            if(!state){
                $(this).parents('div.form-group').next().find('div.yes').addClass('hidden').siblings().removeClass('hidden');
            }else{
                $(this).parents('div.form-group').next().find('div.yes').removeClass('hidden').siblings().addClass('hidden');
            }
            time_type = state;
        }});

        $(modal+' form input#special_rate').bootstrapSwitch({"onColor":"info","offColor":"danger",'onText':"否",'offText':"是",onSwitchChange:function(e,state2){
            if(!state2){
                $(modal+" form input#rate").attr('type','text');
            }else{
                $(modal+" form input#rate").attr('type','number');
            }
            special_rate = state2;
        }});
        $(modal+' form input#is_send').bootstrapSwitch({"onColor":"info","offColor":"danger",'onText':"发送",'offText':"不发送"});
        $(modal+' form input#is_review').bootstrapSwitch({"onColor":"info","offColor":"danger",'onText':"加入",'offText':"不加入"});
    }
    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    var id = $(modal+' form #id').val();
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/mediator/check_data/'+id,
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

    //生成打回模板
    $(modal+" form input.back_step").click(function(){
       var step = "";
        $(modal+" form input.back_step:checked").each(function(){
           step += $(this).attr('data-name')+",";
       });
        step = step.substring(0,step.length-1);
        var text = "温馨提示：由于您的如下信息没有按照要求提交（"+ step +"），请及时重新登录网签居间系统进行修改。详情请咨询400-8820-628";
        $(modal+" form #send_tpl").val(text);
    });
});