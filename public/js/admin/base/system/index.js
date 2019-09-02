$(function(){
    /*
     * 初始化
     */
    function init(){
        $("form#sys_config").validate(validateInfo);
        bindEvent();
    }

    /*
     * 绑定事件
     */
    function bindEvent(){
        $('#pjax-container form .switch input').bootstrapSwitch({onText:"是", offText:"否"});

        //基本信息提交
        $('#pjax-container form button.submit').click(function(){
            add($(this).parents('form'));
        });
    }

    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }
    
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/sys_config_update',
        success:function(json, xml){
            if(200 == json.code){
                toastr.success('操作成功！');
                $.pjax.reload('#pjax-container');
            }else{
                swal('哎呦……',json.info,'warning');
            }
        },
        error:RPA.form.errorReponse
    };

    //表单验证信息
    var validateInfo ={
        errorPlacement:function(error,element){
            element.parent().next().append(error);
        }
    }
    init();
});
