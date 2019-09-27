$(function(){
    var type = false;
    var time_type = false;
    let modal = "#discredit";
    
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
    }
    
    //添加
    function add(e){
        var name = e.find('#name').val();
        var idCard = e.find('#idCard').val();
        var flag = false;
        $.ajax({
            url:'/admin/rpa_customer_discredit/search',
            data:{name:name,idCard:idCard,type:1},
            type:'POST',
            dataType:"json",
            success:function(_data){

                //清空右边表格
                $('table #zq').html('');
                $('table #qh').html('');
                $('table #hs').html('');

                if(_data.status == 200){
                    var timer = setInterval(function () {
                        if(flag){
                            clearInterval(timer);
                        }else{
                            $.ajax({
                                url:'/admin/rpa_customer_discredit/search',
                                data:{name:name,idCard:idCard,type:2},
                                type:'POST',
                                dataType:"json",
                                success:function(_data){
                                    if(_data.status == 200){
                                        var zq = '';
                                        var qh = '';
                                        var hs = '';
                                        //证券
                                        if(_data.zq == 1){
                                            zq = '<span class="x-tag x-tag-danger x-tag-sm">是</span>';
                                        }else{
                                            zq = '<span class="x-tag x-tag-primary x-tag-sm">否</span>'
                                        }
                                        //期货
                                        if(_data.qh == 1){
                                            qh = '<span class="x-tag x-tag-danger x-tag-sm">是</span>';
                                        }else{
                                            qh = '<span class="x-tag x-tag-primary x-tag-sm">否</span>'
                                        }
                                        //恒生黑名单
                                        if(_data.hs == 1){
                                            hs = '<span class="x-tag x-tag-danger x-tag-sm">是</span>';
                                        }else{
                                            hs = '<span class="x-tag x-tag-primary x-tag-sm">否</span>'
                                        }

                                        //填写右边表格hs
                                        $('table #zq').html(zq);
                                        $('table #qh').html(qh);
                                        $('table #hs').html(hs);
                                        flag = true
                                    }else{
                                        toastr.error(_data.msg);
                                    }
                                },
                                error: function () {
                                    flag = true;
                                    toastr.error('网络错误');
                                }
                            })
                        }
                    },3000);
                }else{
                    toastr.error(_data.msg);
                }
            },
            error: function () {
                toastr.error('网络错误');
            }
        })
    }
    
    //表单验证信息
    var validateInfo ={
        rules:{
            name:{//名称
                required:true
            },
            idCard:{
                required:true
            },
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});