$(function(){
    let modal = RPA.config.modal;
    var time_type = $(modal+" form #type ").is(':checked');
    let url_prefix = "/admin/rpa_monitor_picture/";
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
        $(modal+' form input#check_status').bootstrapSwitch({"onColor":"info","offColor":"danger",'onText':"通过",'offText':"打回",});
        //表单提交
        $(modal+' form #save').click(function(){
            add($(this).parents('form'));
        });
        $("div[data-fancybox]").on('click', function(){
            zoomMaker();

        })
    }
    function zoomMaker(){
        var _this = $('.fancybox-image');
        if(_this.length > 0) {
            _this.zoomMarker({
                src: _this.attr('src'),
                rate: 0.2,
                markers:[]
            });
            clearTimeout(t);
        } else {
            var t = setTimeout(function(){
                zoomMaker();
            }, 1000);
        }

    }
    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    var id = $(modal+' form #id').val();
    //提交信息的表单配置
    var FormOptions={
        url: url_prefix + 'review/doReview/' + id,
        success:function(json, xml){
            if(200 == json.code){
                RPA.form.response(function(){
                    //自动跳转下一条
                    if($(modal+' #form-continue').is(':checked')){
                        var dataModel=$('#tb_departments').bootstrapTable('getRowByUniqueId', id);
                        if(dataModel.next_id != null){
                            var url = url_prefix + 'review/'+dataModel.next_id;
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