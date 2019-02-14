$(function(){
    var type = false;
    var time_type = false;

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

        //添加黑名单
        $("#modal form .add_data").on('click',function(){
            let _this = $(this);
            let html = '<div class="row"><div class="col-xs-7"><input type="text" class="form-control" name="ip" placeholder="ip" required></div>'
                    +' <div class="col-xs-3"><input type="text" class="form-control" name="name" placeholder="姓名"></div>'
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
        let black_list = {};
        let white_list = {};
        $('#modal form .target_data.black .row').each(function(){
            let ip = $(this).find("input[name='ip']").val().trim();
            let name = $(this).find("input[name='name']").val().trim();
            black_list[ip] = name;
        });
        $('#modal form .target_data.white .row').each(function(){
            let ip = $(this).find("input[name='ip']").val().trim();
            let name = $(this).find("input[name='name']").val().trim();
            white_list[ip] = name;
        });
        $('#modal form #black_list').val(JSON.stringify(black_list));
        $('#modal form #white_list').val(JSON.stringify(white_list));
    }
    
    //添加
    function add(e){
        serializeForm();
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    //提交信息的表单配置
    var FormOptions={
        url:'/admin/sys_api',
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
            api:{//名称
                required:true
            },
            url:{//url
                required:true
            }
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});