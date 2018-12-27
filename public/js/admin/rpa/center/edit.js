$(function(){
    function init(){
        bindEvent();
        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $("#modal form").validate(validateInfo);
    }
    initCheckBox();
    function bindEvent(){
        //表单提交
        $('#modal form #save').click(function(){
            add($(this).parents('form'));
        });


        $('#modal form .switch input#isfp').bootstrapSwitch({onText:"是", offText:"否"});
        $('#modal form .switch input#type').bootstrapSwitch({onText:"启用", offText:"禁用"});
    }

    $('#modal form select#notice_type').on('change', function(){
        mesNotice($(this));
    });
    //消息通知方式
    function mesNotice(e){
        let val = $(e).val();
        if((val != 0) && (4 != val)){
            showAccepter(val);
        }else{
            $(e).parents('div.form-group').next().addClass('hidden').find('.accepter-content').html('');
        }
    }

    //查询通知人群
    function showAccepter(val){
        $.post('/admin/rpa_center/getAccepter',{param: val}, function(json){
            if(200 == json.code){
                let data = json.data;
                let _html = '';
                if(data.length > 0){
                    for(let _item of data){
                        _html += ' <label><input type="checkbox" name="noticeAccepter[]" value="'+ _item.id +'">'+_item.name+'</label> '
                    }
                }else{
                    _html += '暂无数据！';
                }
                $('#modal form .accepter .accepter-content').html(_html);
                $('#modal form .accepter').removeClass('hidden');
                initCheckBox();
                return;
            }
            swal('Oops...', '获取资源数据失败！', 'error');
        });
    }

    //init checkbox
    function initCheckBox(){
        $('#modal .accepter input').iCheck({
            checkboxClass: 'icheckbox_minimal-blue',
            radioClass: 'iradio_minimal-blue',
        });
    }

    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    var id = $('#modal #id').val();
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/rpa_center/'+id,
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
            filepath:{
                required:true
            },
            failtimes:{
                required:true
            },
            timeout:{
                required:true
            },
            bewrite:{
                required:true
            }
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };
    init();
});