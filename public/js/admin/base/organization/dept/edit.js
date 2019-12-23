$(function(){
    let modal = RPA.config.modal;

    function init(){
        bindEvent();
        //表单的JQueryValidater配置验证---jquery.validate插件验证法
        $(modal+" form").validate(validateInfo);
    }
    function bindEvent(){
        //表单提交
        $(modal+' form #save').click(function(){
            add($(this).parents('form'));
        });

        // 搜索员工
        $('.searchUser').on('click', function(){
            $('#modal-sm .modal-content').text('').load('/admin/sys_dept/searchAdmins');
            $('#modal-sm').modal('show');
        });

        //监听事件
        document.addEventListener('searchAdmin', function(e){
            var id = e.detail.id.replace('admin_', '');
            var name = e.detail.oldname?e.detail.oldname:e.detail.name;
            $('#leader_id').html("<option value='"+id+"'>"+name+"</option>");
            $('#'+e.detail.parentId).modal('hide');
        })
    }

    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }
    var id = $(modal+' #id').val();
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/sys_dept/'+id,
        success:function(json, xml){
            if(200 == json.code){
                RPA.form.response();
                var newEvent = document.createEvent("HTMLEvents");
                newEvent.initEvent("updateUser",true,true);
                document.dispatchEvent(newEvent);
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
            pid:{
                required:true
            }
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };
    init();
});