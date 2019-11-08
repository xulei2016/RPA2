$(function(){
    let modal = RPA.config.modal;
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

        laydate.render({ elem: "#date", type: 'date'}); //日历选择器

        //表单提交
        $(modal+' form #save').click(function(){
            add($(this).parents('form'));
        });

        //交易所发生改变
        $('select#jys_id').on('change', function(){
            var jys_id = $(this).val();
            $.ajax({
                url:'/admin/rpa_contract_pz/getByJys',
                data:{jys_id:jys_id},
                success:function(r){
                    $('#pz_id').html(r);
                }
            })
        });

    }


    //添加
    function add(e){
        // serializeForm();
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    //提交信息的表单配置
    var FormOptions={
        url:'/admin/rpa_contract_extra',
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
            jys_id:{
                required:true
            },
            pz_id:{
                required:true
            },
            date:{
                required:true
            },
            hydm:{
                required:true
            }
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});