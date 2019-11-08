$(function(){
    var type = false;
    var time_type = false;
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

        //是否有运行中调整
        $(modal+' form .switch input#has_change').bootstrapSwitch({
            onText:"是",
            offText:"否",
            onSwitchChange : function(e, state) {
                var runChange = $('#run-change');
                if(state) {
                    $(this).val(1);
                    runChange.show(500);
                } else {
                    $(this).val(0);
                    runChange.hide(500);
                }
            }
        });

        //是否有上线合约
        $(modal+' form .switch input#has_online').bootstrapSwitch({
            onText:"是",
            offText:"否",
            onSwitchChange : function(e, state) {
                var runChange = $('#online-contract');
                if(state) {
                    $(this).val(1);
                    runChange.show(500);
                } else {
                    $(this).val(0);
                    runChange.hide(500);
                }
            }
        })

        //全选
        $('#allCheck').on('click', function(){
            var _this = $(this);
            if(_this.is(':checked')) {
                $('.hy-month').prop('checked', 'checked');
            } else {
                $('.hy-month').prop('checked', false);
            }
        })

        //反选
        $('#reverseCheck').on('click', function(){
            var l = $('.hy-month');
            l.each(function(r, item){
               $(item).click();
            });
        })
    }


    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }
    var id = $(modal+' form #id').val();
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/rpa_contract_detail/'+id,
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
            pzfy_jysxf:{
                required:true
            },
            hy_month:{
                required:true
            }
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});