$(function(){
    let modal = RPA.config.modal;
    var time_type = $(modal+" form #type ").is(':checked');

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
        //点击事件
        $(modal+' form #address,#address_deep').click(function(){
            var addr = $(this).parent().prev().find("input").val();
            $(modal+' form #address_final').val(addr);
        }); 
    }
    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    var id = $(modal+' form #id').val();
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/rpa_address_recognition/'+id,
        success:function(json, xml){
            if(200 == json.code){
                RPA.form.response(function(){
                    //自动跳转下一条
                    if($(modal+' #form-continue').is(':checked')){
                        var dataModel=$('#tb_departments').bootstrapTable('getRowByUniqueId', id);
                        if(dataModel.next_id != null){
                            var url = '/admin/rpa_address_recognition/'+dataModel.next_id+'/edit';
                            $(modal+' .modal-content').text('').load(url);
                        }else{
                            $(modal).modal('hide');
                            toastr.error('当页数据已结束，请手动跳转下一页');
                        }
                        
                    }else{
                        $(modal).modal('hide');
                    }
                });
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
});