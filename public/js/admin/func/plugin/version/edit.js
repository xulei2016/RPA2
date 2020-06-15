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

        $("#select2-menu").select2({
            "allowClear":true,
            "placeholder":"所属插件",
        });

        $(modal+' form .switch input#status').bootstrapSwitch({onText:"启用", offText:"禁用"});

        //上传附件
        $('form input#zip').on('change', function(e){
            var file = document.getElementById("zip").files[0];
            var _this = $(this);
            var formData = new FormData(); // FormData 对象
            formData.append("file", file); // 文件对象
            $.ajax({
                url:"/admin/rpa_plugin_version/upload",
                type: "post",
                data: formData,
                contentType: false,
                dataType:'json',
                processData: false,
                mimeType: "multipart/form-data",
                success:function(r){
                    if(r.code == 200) {
                        toastr.success('上传成功');
                        _this.prev().html(r.data.name);
                        $('#url').val(r.data.url);
                        $('#show_name').val(r.data.name);
                    } else {
                        toastr.error(r.info);
                        return false;
                    }
                }
            });
        });


        // 搜索员工
        $('.searchDoc').on('click', function(){
            $('#modal-sm .modal-content').text('').load('/admin/rpa_plugin_version/searchDoc');
            $('#modal-sm').modal('show');
        });

        //监听事件
        document.addEventListener('searchDoc', function(e){
            var id = e.detail.id;
            var name = e.detail.oldname?e.detail.oldname:e.detail.name;
            $('#doc_id').html("<option value='"+id+"'>"+name+"</option>");
            $('#'+e.detail.parentId).modal('hide');
        });

    }


    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }
    var id = $(modal+' form #id').val();
    //提交信息的表单配置
    var FormOptions={
        url:'/admin/rpa_plugin_version/'+id,
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
            name:{
                required:true
            }
        },
        errorPlacement:function(error,element){
            element.parent().append(error);
        }
    };

    init();
});