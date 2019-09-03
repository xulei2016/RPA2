$(function(){
    let modal = RPA.config.modal;
    var type = false;
    var time_type = false;

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
        CKEDITOR.replace('answer');
        //表单提交
        $(modal+' form #save').click(function(){
            var answer = CKEDITOR.instances.answer.getData();
            $('#answer').val(answer);
            add($(this).parents('form'));
        });
    }

    
    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }
    var id = $(modal+' form #id').val();
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/sys_call_center_template/'+id,
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
            keyword:{//名称
                required:true
            },
            content:{//url
                required:true
            },
            answer:{
                required:true
            }
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});