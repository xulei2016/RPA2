$(function(){
    /*
     * 初始化
     */
    function init(){
        bindEvent();

        //1.初始化Table
        var oTable = new RPA.TableInit();
        pageNation(oTable);
    }

    /*
     * 绑定事件
     */
    function bindEvent(){
        //基本信息提交
        $('#modal #form .submit').click(function(){
            add($(this).parents('form'));
        });

        //修改密码提交
        $('#modal #form .submit').click(function(){
            add($(this).parents('form'));
        });
    }

    //添加
    function add(e){
        RPA.ajaxSubmit(e, FormOptions);
    }
    
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/sys_menu',
        success:function(json, xml){
            if(200 == json.code){
                toastr.success('操作成功！');
                var formContinue = $('#form-continue').is(':checked');
                !formContinue ? $('#modal').modal('hide') : $('#form-reset').click();
                $.pjax.reload('#pjax-container');
            }else{
                toastr.error(json.info);
            }
        },
        error:RPA.errorReponse
    };

    init();
});
