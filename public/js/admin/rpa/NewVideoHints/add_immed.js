$(function(){
    var type = false;

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

        //添加站点
        $("#modal form #add_web").on('click',function(){
            let _this = $(this);
            let html = '<div class="row"><div class="col-xs-10"><input type="text" class="form-control" name="data" placeholder="请输入营业部"></div>'
                +' <div class="col-xs-2"><a href="javascript:void(0);" class="btn btn-sm btn-danger del_web">删除</a></div>'
                +'</div>';
            _this.parents('.target_web').append(html);
    
            $("#modal form a.del_web").unbind().on('click',function(e){
                $(this).parents('div.row').remove();
            });
        });
    }

    //序列化
    function serializeForm(){
        let jsondata = {};
        let tmp = "";
        $('#modal form .target_web .row').each(function(){
            let data = $(this).find("input[name='data']").val().trim();
            tmp += data + ",";
        });
        jsondata.date = tmp.substr(0, tmp.length - 1);
        $('#modal form #jsondata').val(JSON.stringify(jsondata));
    }
    
    //添加
    function add(e){
        serializeForm();
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    //提交信息的表单配置
    var FormOptions={
        url:'/admin/rpa_NewVideoHints/insertImmedtasks',
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
            description:{
                required:true
            }
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});