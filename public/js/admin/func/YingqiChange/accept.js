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
            update($(this).parents('form'));
        });

        //执行时间类型
        $(modal+' form input#implement_type').bootstrapSwitch({"onColor":"info","offColor":"danger",'onText':"是",'offText':"否",onSwitchChange:function(e,state){
            if(state){
                $('.reasons').each(function(){
                    $(this).prop('checked', false);
                })
                $('#send_tpl').val('');
                $(this).parents('div.form-group').next().find('div.reason').addClass('hidden').siblings().removeClass('hidden');
            }else{
                $(this).parents('div.form-group').next().find('div.reason').removeClass('hidden').siblings().addClass('hidden');
            }
            time_type = state;
        }});
    }
    //修改状态
    function update(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    var id = $(modal+' form #id').val();
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/rpa_yq_change/status/'+id,
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

        //点击切换大图
        $('.pic').click(function(){
            $("#bigPic").css('transform', 'rotate(0deg)');
            $('.pic').removeClass('pic-select');
            $(this).addClass('pic-select');

            $('#bigPicDiv').attr('href', $(this).attr('src'));
            $('#bigPic').attr('src', $(this).attr('src'));
        })

        //生成短息内容
        $(modal+" form input.reasons").click(function(){
            var step = "";
            $(modal+" form input.reasons:checked").each(function(){
                if($(this).attr('data-name') != "其他"){
                    step += $(this).attr('data-name')+",";
                }
            });
            step = step.substring(0,step.length-1);
            var typeName = $('#m-type').val() == 1 ? '新增' : '变更';
            var text = "尊敬的客户，您提交的结算账户"+typeName+"业务因（"+ step +"）未能办理，";
            if((step.indexOf('证件地址不同') > -1) || step.indexOf('有效期不一致') > -1 || step.indexOf('证件过期') > -1){
                text+="请在微信公众号更新证件后继续办理。";
             }else{
                text+="请修正后办理。";
             }
            text+="如有疑问，请联系客服400-882-0628";
            $(modal+" form #send_tpl").val(text);
        });


    init();
});