$(function(){
    var type = false;
    var time_type = false;
    let modal = "#fxq";
    
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
        var customernum = e.find('#customernum').val();
        $.ajax({
            url:'/api/v1/fxq',
            data:{customernum:customernum,ie:1},
            type:'POST',
            dataType:"json",
            success:function(_data){

                //清空右边表格
                $('table .model0').html('');
                $('table .model1').html('');
                $('table .model2').html('');
                $('table .model3').html('');

                if(_data.status == 200){
                    $('table .model0').html(_data.msg[0]);
                    $('table .model1').html(_data.msg[1]);
                    $('table .model2').html(_data.msg[2]);
                    $('table .model3').html(_data.msg[3]);
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