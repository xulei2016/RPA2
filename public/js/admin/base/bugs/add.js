$(function () {
    //初始化
    function init(){
        bindEvent();
        CKEDITOR.replace('editor');
    }

    //绑定事件
    function bindEvent(){
        //发送事件
        $('#pjax-container section.content button.submit').click(function(){
            for (instance in CKEDITOR.instances)
                CKEDITOR.instances[instance].updateElement();

            var project = $('#pjax-container section.content form input.project').val();
            if( !project){
                return swal('Oops...', '请完善发送信息！！', 'warning');
            }
            add($('#pjax-container section.content form'));
        });
    }

    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    //提交信息的表单配置
    var FormOptions={
        url:'/admin/Bugs',
        success:function(json, xml){
            if(200 == json.code){
                toastr.success('操作成功！');
            }else{
                toastr.error(json.info);
            }
        },
        error:RPA.errorReponse
    };

    init();
})