$(function(){
    /*
     * 初始化
     */
    function init(){
        $("form#setting").validate(validateInfo);
        bindEvent();
    }

    /*
     * 绑定事件
     */
    function bindEvent(){
        $('input:checkbox').bootstrapSwitch({onText:"启用", offText:"禁用"});

        $('input:checkbox').on('switchChange.bootstrapSwitch', function (e, data)  {
            $(this).parents('td').find('input:hidden').val(data?'on':'off');
        });

        //基本信息提交
        $('#save').click(function(){
            add($(this).parents('form'));
        });
    }

    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    //提交信息的表单配置
    var FormOptions={
        url:'/admin/rpa_contract_dict',
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
