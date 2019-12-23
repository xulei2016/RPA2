$(function(){
    /**
     * 页面初始化
     */
    function init(){
        bindEvent();
        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $("#form").validate(validateInfo);
    }

    //事件绑定
    function bindEvent(){
        //表单提交
        $('#form #save').on('click', function(){
            add($(this).parents('form'));
        });

        $("#form #post_id").on('change', function(){
            var post = $('#post_id option:selected').text();
            $('#fullname').val(post);
        })
    }


    //添加
    function add(e){
        // serializeForm();
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    //提交信息的表单配置
    var FormOptions={
        url:'/admin/sys_dept_post_relation',
        success:function(json, xml){
            if(200 == json.code){
                RPA.form.response();
                var newEvent = document.createEvent("HTMLEvents");
                newEvent.initEvent("deptPostRelation",true,true);
                document.dispatchEvent(newEvent);
            }else{
                toastr.error(json.info);
            }
        },
        error:RPA.form.errorReponse
    };

    //表单验证信息
    var validateInfo ={
        rules:{
            dept_id:{
                required:true
            },
            post_id:{
                required:true
            }
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});