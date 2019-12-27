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

        $("#select2-menu").select2({
            "allowClear":true,
            "placeholder":"角色选择",
        });

        $("#dept_id").select2({
            "allowClear":true,
            "placeholder":"部门选择",
        });

        $("#posts").select2({
            "allowClear":true,
            "placeholder":"岗位",
        });

        $(modal+' form .switch input#sex').bootstrapSwitch({onText:"男", offText:"女"});
        $(modal+' form .switch input#type').bootstrapSwitch({onText:"启用", offText:"禁用"});

        //部门更新
        $(modal+ ' #dept_id').on('change', function(){
            var dept_id = $(this).val();
            getPostsByDepartmentId(dept_id);
        });

        // 搜索员工
        $('.searchUser').on('click', function(e){
            $('#modal-sm .modal-content').text('').load('/admin/sys_dept/searchAdmins');
            $('#modal-sm').modal('show');
            e.preventDefault();
            e.stopPropagation()
        });

        //监听事件
        document.addEventListener('searchAdmin', function(e){
            var id = e.detail.id.replace('admin_', '');
            var name = e.detail.oldname?e.detail.oldname:e.detail.name;
            $('#leader_id').html("<option value='"+id+"'>"+name+"</option>");
            $('#'+e.detail.parentId).modal('hide');
        })
    }

    /**
     * 根据部门id获取岗位
     * @param id
     */
    function getPostsByDepartmentId(id){
        $.post('/admin/sys_dept_post_relation/getByDeptId', {dept_id: id}, function(res){
            var html = "<option>未选择</option>";
            if(res.code == 200) {
                $.each(res.data, function(index, item){
                    html += "<option value='"+item.id+"'>"+item.postName+"</option>";
                });
            }
            $(modal+ ' #posts').html(html);
        });
    }
    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }
    var id = $(modal+' #id').val();
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/sys_admin/'+id,
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
            "roleLists[]":{
                required:true
            },
            realName:{
                required:true
            },
            dept_id:{
                required:true
            },
            post_id:{
                required:true
            },
            func_id:{
                required:true
            },
            groupID:{
                required:true
            },
            rePWD:{
                equalTo:"#password"
            }
        },
        messages:{
            rePWD:{
                equalTo:"两次密码输入不一致"
            }
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };
    init();
});