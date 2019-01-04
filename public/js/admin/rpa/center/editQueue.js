$(function(){
    /**
     * 页面初始化
     */
    function init(){
        bindEvent();
        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $("#modal form").validate(validateInfo);
    }
    
    //事件绑定
    function bindEvent(){
        //表单提交
        $('#modal form #save').click(function(){
            add($(this).parents('form'));
        });

        //时间
        let nowDate = getFormatDate();
        //定义时间按钮事件
        let st = '#modal #time';
        laydate.render({elem: st, type: 'datetime'});

        //添加站点
        $("#modal form #add_data").on('click',function(){
            let _this = $(this);
            let html = '<div class="row"><div class="col-xs-7"><input type="text" class="form-control" name="data_key" placeholder="参数名" required></div>'
                    +' <div class="col-xs-3"><input type="text" class="form-control" name="data_value" placeholder="参数值"></div>'
                    +' <div class="col-xs-2"><a href="javascript:void(0);" class="btn btn-sm btn-danger del_data">删除</a></div>'
                    +'</div>';
            _this.parents('.target_data').append(html);
    
            $("#modal form a.del_data").unbind().on('click',function(e){
                $(this).parents('div.row').remove();
            });
        });
    }

    //序列化
    function serializeForm(){

        let jsondata = {};
        $('#modal form .target_data .row').each(function(){
            let key = $(this).find("input[name='data_key']").val().trim();
            let value = $(this).find("input[name='data_value']").val().trim();
            jsondata[key] = value;
        });
        $('#modal form #jsondata').val(JSON.stringify(jsondata));
    }
    
    //添加
    function add(e){
        serializeForm();
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    //提交信息的表单配置
    var FormOptions={
        url:'/admin/rpa_center/updateQueue',
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
            name:{//名称
                required:true
            },
            data_key:{
                required:true
            },
            data_value:{
                required:true
            },
            tid:{
                required:true
            },
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});