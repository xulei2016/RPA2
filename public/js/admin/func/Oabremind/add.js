$(function(){
    var type = false;

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
        //查询客户
        $(modal+" form #khh").change(function(){
            let khh = $(this).val();
            $.ajax({
                url: '/admin/rpa_customer_funds_search/getCustomerName',
                type: 'post',
                data: {khh:khh},
                dataType: 'json'
            }).then(function(json){
                console.log(json[0]);
                if(json[0] === undefined){
                    $('#khh').parent().find('.success').text('');
                    $('#khh').parent().find('.wrong').text('未查到该客户');
                }else{
                    $('#khh').parent().find('.success').text(json[0].KHXM);
                    $('#khh').parent().find('.wrong').text('');
                }
            },function(e){
                $('#khh').parent().find('.wrong').text('查询失败');
            })
        });
        //表单提交
        $(modal+' form #save').click(function(){
            add($(this).parents('form'));
        });
    }

    
    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    //提交信息的表单配置
    var FormOptions={
        url:'/admin/rpa_customer_funds_search/insert',
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
            khh:{//名称
                required:true,
                number:true
            },
            tid:{
                required:true
            }
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});