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
        //草稿事件
        $('#pjax-container section.content button.draft').click(function(){
            for (instance in CKEDITOR.instances)
                CKEDITOR.instances[instance].updateElement();
            var project = $('#pjax-container section.content form input.project').val();
            if( !project){
                return swal('Oops...', '请完善发送信息！！', 'warning');
            }
            draft($('#pjax-container section.content form'));
        });
        //重置事件
        $('#pjax-container section.content button.reset').click(function(){
            let notice_type = $('#pjax-container form select#notice_type');
            $('#pjax-container section.content form')[0].reset();
            mesNotice(notice_type);
            $('#pjax-container form input#attachment').prev().html("上传附件");
        });
        //修改发送对象
        $('#pjax-container form select#mode').on('change', function(){
            mesNotice($(this));
        });
        //上传附件
        $('#pjax-container form input#attachment').on('change', function(e){
            let fileMsg = e.currentTarget.files;
            let fileSize = fileMsg[0].size;
            if(fileSize > 10*1024*1024){
                return swal('Oops...', '附件大小超过限制！！！', 'warning');
            }
            let fileName = fileMsg[0].name;
            $(this).prev().html(fileName);
        });

    }

    //消息通知方式
    function mesNotice(e){
        let val = $(e).val();
        if(4 != val){
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
                        _html += ' <label><input type="checkbox" name="user[]" value="'+ _item.id +'">'+_item.name+'</label> '
                    }
                }else{
                    _html += '暂无数据！';
                }
                $('#pjax-container form .accepter .accepter-content').html(_html);
                $('#pjax-container form .accepter').removeClass('hidden');
                $('#pjax-container .accepter input').iCheck({
                    checkboxClass: 'icheckbox_minimal-blue',
                    radioClass: 'iradio_minimal-blue',
                });
                return;
            }
            swal('Oops...', '获取资源数据失败！', 'error');
        });
    }
    //添加
    function add(e){
        RPA.form.ajaxSubmit(e, FormOptions);
    }

    //提交信息的表单配置
    var FormOptions={
        url:'/admin/sys_mail/send',
        success:function(json, xml){
            if(200 == json.code){
                toastr.success('操作成功！');
            }else{
                toastr.error(json.info);
            }
        },
        error:RPA.form.errorReponse
    };

    //草稿
    function draft(e) {
        RPA.form.ajaxSubmit(e,FormOptions2)
    }
    //提交信息的表单配置
    var FormOptions2={
        url:'/admin/sys_mail/draft',
        success:function(json, xml){
            if(200 == json.code){
                toastr.success('操作成功！');
            }else{
                toastr.error(json.info);
            }
        },
        error:RPA.form.errorReponse
    };

    init();
})