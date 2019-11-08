$(function(){
    let modal = RPA.config.modal;
    /**
     * 页面初始化
     */
    function init(){
        bindEvent();
        CKEDITOR.replace('editor_desc');
        CKEDITOR.replace('editor_content');

        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $(modal+" form").validate(validateInfo);
    }

    //事件绑定
    function bindEvent(){
         //定义时间按钮事件
         let rq = '#online_time';
         laydate.render({ elem: rq, type: 'date'});
        //表单提交
        $(modal+' form #save').click(function(){
            for (instance in CKEDITOR.instances)
                CKEDITOR.instances[instance].updateElement();

            add($(this).parents('form'));
        });

    }


    //添加
    function add(e){
        // serializeForm();
        RPA.form.ajaxSubmit(e, FormOptions);
    }
    var id = $(modal+' form #id').val();
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/sys_version_update/'+id,
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
            desc:{
                required:true
            },
            type:{
                required:true
            },
            content:{
                required:true
            },
            online_time:{
                required:true
            }
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});